<?php

namespace App\Http\Controllers;

use App\Models\BlogCat;
use App\Models\CustomField;
use App\Models\DoDontCat;
use App\Models\Leadership;
use App\Models\User;
use App\Models\LoanOfficers;
use App\Models\LoanOption;
use App\Models\Branches;
use App\Models\StateLic;
use App\Models\Page;
use App\Models\Dodont;
use App\Models\Faq;
use App\Models\FaqCat;
use App\Models\LoanCheckCat;
use App\Models\Blog;
use Illuminate\Support\Str;


class MasterController extends Controller
{


    public function forget_session()
    {
        \session()->forget('user_id');
    }


    public function index()
    {


        $page = cache()->remember('page_hp', 60 * 60 * 24, function () {
            return Page::findOrFail(1);
        });


        return view('pages.home', [
            'page' => $page,
            'parent' => 'home'
        ]);
    }


    public function run($slug)
    {


        // TODO general page with or without templates

        $page = Page::where('friendlyUrl', $slug)->where('published', 1)->first();
        if ($page) {

            $page_redirect = $page->page_redirect;

            if ($page_redirect) {
                return redirect()->to($page_redirect);
            }
            return $this->renderPages($page);
        }

        // TODO loan program page

        $lp = LoanOption::where('friendlyUrl', $slug)->where('published', 1)->first();

        if ($lp) {
            return $this->renderLoanProgramPages($lp);
        }

        // TODO loan officer page

        $lo = LoanOfficers::Where('friendlyUrl', $slug)->where('published', 1)->first();
        if (!is_null($lo)) {
            if ($lo->published == 1) {
                if ($lo->show_lo_page == 1) {
                    return $this->renderLoanOfficerPages($lo);
                }
            }
        }

        // TODO branch page

        $branch = Branches::with('los')->Where('friendlyUrl', $slug)->first();
        if (!is_null($branch)) {
            return $this->renderBranchPages($branch);
        }

        // TODO state page

        $has_state = $this->renderStatePages($slug);

        if ($has_state) return $has_state;
        abort(404); // TODO 404 not found page

    }


    public function renderPages($page)
    {

        $request = \request();
        $t_name = 'general';
        if (optional($page->template)->slug) {
            $t_name = optional($page->template)->slug;
        }


        $template = 'pages.' . $t_name;
        $custom_fields = CustomField::query()->where('model_id', $page->id)->where('model_type', 'App\Page')->pluck('value', 'key');
        $custom_fields = json_decode($custom_fields->toJson());


        if ($page != null) {
            $title = $page->title;
            $title = Str::replace(' | Diamond Residential Mortgage', '', $title);
            view()->share('title', $title);
        }


        $data = [
            'page' => $page,
            'custom_fields' => $custom_fields,
            'sf' => $this->us_state_abbrevs_names,
        ];

        $models = optional($page->template)->models ?? '';

        if (str_contains(strtolower($models), 'leader')) {
            $data['leaders'] = Leadership::where('published', 1)->orderBy('display_order')->get();
        }
        if (str_contains(strtolower($models), 'dodont')) {
            $dds = Dodont::orderBy('item')->get();
            $dcats = DoDontCat::where('id', 1)->first();
            $data['ddsx'] = $dcats->dodonts;
            $data['dds'] = $dds;
        }
        if (str_contains(strtolower($models), 'faq')) {

            $faqs = Faq::query()->where('published', 1)->orderBy('display_order')->get();
            $faq_cat = FaqCat::has('faq_published')->get();
            $data['faqs'] = $faqs;
            $data['faq_cat'] = $faq_cat;
        }
        if (str_contains(strtolower($models), 'loanoption')) {

            $loan_programs = LoanOption::query()->orderBy('display_order')->get();
            $data['lp'] = $loan_programs;
        }

        if (str_contains(strtolower($models), 'loancheck')) {
            $lccs = LoanCheckCat::all();
            $data['lccs'] = $lccs;
        }
        if ($page->nav_label == 'Licensing') {
            $data['st'] = $this->us_state_abbrevs_names;
            $data['lics'] = StateLic::orderBy('state')->get();

        }


        if (str_contains(strtolower($models), 'loanofficer')) {

            $los_page = LoanOfficers::join('users', 'loan_officers.user_id', '=', 'users.id')->where('published', 1)->orderBy('users.lname')->get();
            $lf = LoanOfficers::query()->with('user')->where('published', 1)->get();
            $los = [];
            foreach ($lf as $item) {
                $los[] = ['label' => optional($item->user)->fname . ' ' . optional($item->user)->lname, 'link' => route('master.run', $item->friendlyUrl)];
            }


            $s = StateLic::query()->orderBy('state')->get()->filter(function ($st) {
                return Branches::query()->where('published', 1)->where('state', $st->state)->exists() || LoanOfficers::query()
                        ->with('user')
                        ->where('published', 1)
                        ->whereHas('stlic', function ($query) use ($st) {
                            $query->where('statelic_id', $st->id);
                        })->exists();

            })->toBase();


            $sc = [];
            foreach ($s as $item) {
                $sc[] = $item;
            }


            $data['los_page'] = $los_page;
            $data['los'] = $los;
            $data['states'] = $sc;


        }
        if (str_contains(strtolower($models), 'blog')) {

            $blogs = Blog::whereHas('categories', function ($q) {
                $q->where('blog_cats.id', '!=', 5);
            })->where('published', 1)->orderBy('date', 'DESC')->get();


            $cat = BlogCat::where('id', 5)->first();
            $press = $cat->blogs()->where('published', 1)->orderBy('date', 'DESC')->get();


            $data['blogs'] = $blogs;
            $data['press'] = $press;
        }

        return view($template, $data);
    }

    public function renderStatePages($slug)
    {

        $sc = StateLic::query()->orderBy('state')->get();
        $page = Page::query()->where('friendlyUrl', 'locations')->first();

        $branches = Branches::query()->where('published', 1)
            ->where('state', $this->getStateShortName($slug))
            ->orderBy('branchName')->get();

        $currentState = StateLic::query()->firstWhere('state', $this->getStateShortName($slug));

        $loanOfficers = LoanOfficers::query()
            ->with(['user', 'branch'])
            ->where('published', 1)
            ->whereHas('stlic', function ($query) use ($currentState) {
                $query->where('statelic_id', optional($currentState)->id);
            })
            ->select('loan_officers.*')
            ->leftJoin('banches_loanofficers', 'loan_officers.id', '=', 'banches_loanofficers.loanofficers_id')
            ->orderByRaw('-ISNULL(banches_loanofficers.branches_id) DESC')
            ->distinct('user.id')
            ->get();

        $branch_ids = $branches->pluck('id')->toArray();
        $has_branch = $loanOfficers->filter(function ($lo) use ($branch_ids) {
            foreach ($lo->branch as $b) {
                if (in_array($b->id, $branch_ids)) {
                    return true;
                }
            }
            return false;
        });
        $hasnot_branch = $loanOfficers->filter(function ($lo) use ($branch_ids) {
            foreach ($lo->branch as $b) {
                if (!in_array($b->id, $branch_ids)) {
                    return true;
                }
            }
            return false;
        });

        if (count($branches) > 0) {
            $loanOfficers = collect([]);
        } else {
            $loanOfficers = $has_branch->merge($hasnot_branch);
        }
        $loanOfficers_m = $loanOfficers->where('branch_manager', 1)->sortByDesc('branch_manager');
        $loanOfficers_g = $loanOfficers->where('branch_manager', 0);
        $loanOfficers = $loanOfficers_m->merge($loanOfficers_g);
//        dd($loanOfficers_m->merge());

        if (count($branches) > 0 || count($loanOfficers) > 0) {

            $lf = LoanOfficers::with('user')->where('published', 1)->get();
            $los = [];
            foreach ($lf as $item) {
                $los[] = ['label' => optional($item->user)->fname . ' ' . optional($item->user)->lname, 'link' => route('master.run', $item->friendlyUrl)];
            }

            if (strlen($slug) == 2) {
                try {
                    $slug = $this->us_state_abbrevs_names[strtoupper($slug)];

                } catch (\Exception $exception) {

                }
            }

            return view('pages.locations-search', [
                'states' => $sc,
                'branches' => $branches,
                'sf' => $this->us_state_abbrevs_names,
                'state_name' => $slug,
                'los' => $loanOfficers,
                'searchable_los' => $los,
                'page' => $page,
                'is_state' => true,
                'title' => $slug
            ]);

        }

    }

    public function renderLoanProgramPages($lp)
    {
        $non_faq_cat = FaqCat::query()->where('label', 'Non-QM')->first();
        $non_faq = [];
        if ($non_faq_cat) {
            $non_faq = Faq::query()->where('published', 1)
                ->where('faqcat_id', $non_faq_cat->id)
                ->orderBy('display_order')->get();
        }

        $t_name = 'loan-program-item';
        if (optional($lp->template)->slug) {
            $t_name = optional($lp->template)->slug;
        }
        $template = 'pages.' . $t_name;

        return view($template, [
            'lp' => $lp,
            'page' => $lp,
            'parent' => 'lprog',
            'non_faq' => $non_faq
        ]);
    }

    public function renderLoanOfficerPages($lo)
    {


        $user = User::with('lo')->findOrFail($lo->user_id);
//                    Session::set('user_id', $user->id);
        session(['user_id' => $user->id]);

        $tests = [];
        $testRate = [];
        $testCount = [];

        $branch = $user->lo->branch->first();
        if (!is_null($lo->primay_branch)) {
            $branch = $lo->branch->where('id', $user->lo->primay_branch)->first();
        }


        return view('pages.lo-profile',
            [
                'user' => $user,
                'lo' => $user->lo,
                'tests' => $tests,
                'cavg' => $testRate,
                'rcount' => $testCount,
                'sf' => $this->us_state_abbrevs_names,
                'branch' => $branch,

            ]
        );
    }

    public function renderBranchPages($branch)
    {
        $corpTestimonials = [];
        $testCount = [];

        if ($branch->los->count() === 1) {

            return redirect()->route('master.run', $branch->los->first()->friendlyUrl);

        }


        // create branch manager and lo list and merge for use in frontend
        $branch_man = $branch->los->where('published', 1)->where('branch_manager', 1)->unique('id');
        $los = $branch->los->where('published', 1)->where('branch_manager', 0)->sortByDesc('branch_manager')->unique('id')->shuffle();
        $los = $branch_man->merge($los);

        $loList = $los->pluck('user.email')->toArray();


        return view('pages.branch',
            [
                'branch' => $branch,
                'los' => $los,
                'bm' => $branch->los->where('published', 1)->where('branch_manager', 1)->first(),
                'bms' => $branch_man,
                'rcount' => $testCount,
                'parent' => 'locations',
                'merged' => $los,
                'stlic' => StateLic::where('state', $branch->state)->first(),

            ]
        );

    }


    public function getStateShortName($text)
    {
        if (strlen($text) == 2) {
            return $text;
        }
//        dd($text);
        return $this->getKeyByValue($text);

    }

    public function press_release(Blog $blog)
    {
        $page = Page::with('custom_fields')->where('friendlyUrl', 'press-room')->firstOrFail();

        $bgs = Blog::whereHas('categories', function ($q) {
            $q->where('blog_cats.id', '!=', 5);
        })->where('published', 1)->orderBy('date', 'DESC')->take(3)->get();

        $cat = BlogCat::where('id', 5)->first();
        $press = $cat->blogs()->where('published', 1)->orderBy('date', 'DESC')->take(3)->get();

        return view('pages.resources.article', [
            'page' => $page,
            'bl' => $blog,
            'title' => $blog->meta_title ?? $blog->title,
            'parent' => 'resources',
            'bgs' => $bgs,
            'press' => $press,
            'type' => 'pr'
        ]);
    }

    public function page_press_article(Blog $blog)
    {
        $page = Page::with('custom_fields')->where('friendlyUrl', 'press-room')->firstOrFail();

        $bgs = Blog::whereHas('categories', function ($q) {
            $q->where('blog_cats.id', '!=', 5);
        })->where('published', 1)->orderBy('date', 'DESC')->take(3)->get();

        $cat = BlogCat::where('id', 5)->first();
        $press = $cat->blogs()->where('published', 1)->orderBy('date', 'DESC')->take(3)->get();

        return view('pages.resources.article', [
            'page' => $page,
            'bl' => $blog,
            'title' => ($blog->meta_title ?? $blog->title) . ' ',
            'parent' => 'resources',
            'bgs' => $bgs,
            'press' => $press,
            'type' => 'blog'
        ]);
    }


}
