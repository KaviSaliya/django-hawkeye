<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\ContentDetails;
use App\Models\InvestmentPlan;
use App\Models\Language;
use App\Models\ManageMenu;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Mailchimp\Transport\MandrillTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            DB::connection()->getPdo();

            $data['basicControl'] = basicControl();
            $data['theme'] = template();
            $data['themeTrue'] = template(true);
            View::share($data);

            view()->composer([
                $data['theme'] . 'partials.navbar',
            ], function ($view) {
                $languages = Language::orderBy('name')->where('status', 1)->get();
                $view->with('languages', $languages);
                $contentData = ContentDetails::with('content')
                    ->whereHas('content', function ($query)   {
                        $query->where('name', 'top_section');
                    })
                    ->get();
                $single_content = $contentData->where('content.name', 'top_section')->where('content.type', 'single')->first();
                $datas = [
                    'single' => $single_content? collect($single_content->description ?? [])->merge($single_content->content->only('media')) : []
                ];
                $view->with("top_section", $datas);
            });

            view()->composer([
                $data['theme'] . 'sections.project_section_2',
            ], function ($view) {
                $projects = Project::with(['details' => function ($query) {
                    $query->select('title','project_id','language_id','id','slug');
                }])
                    ->where(function ($query) {
                        $query->where('expiry_date','>',Carbon::now())
                            ->orWhere('project_duration_has_unlimited' ,1);
                    })
                    ->where('status',1)
                    ->where('is_deleted',0)
                    ->orderBy('created_at','desc')
                    ->paginate(6);
                $view->with('projects', $projects);
            });

            view()->composer([
                $data['theme'] . 'sections.pricing_section',
                $data['theme'] . 'sections.pricing_section_2',
            ], function ($view) {
                $plans = InvestmentPlan::where(['status'=>1,'soft_delete' => 0])->get();
                $view->with('plans', $plans);
            });

            view()->composer([
                $data['theme'] . 'sections.project_section',
            ],function ($view){
                $projects = Project::with(['details' => function ($query) {
                    $query->select('title','project_id','language_id','id','slug');
                }])
                    ->where(function ($query) {
                        $query->where('expiry_date','>',Carbon::now())
                            ->orWhere('project_duration_has_unlimited' ,1);
                    })
                    ->where('status',1)
                    ->where('is_deleted',0)
                    ->orderBy('created_at','desc')->limit(4)->get();

                $view->with('projects' , $projects);
            });

            view()->composer([
                $data['theme'] . 'sections.farming_section',
                $data['theme'] . 'pages.project_details',
            ], function ($view) {
                $projects = Project::with(['details' => function ($query) {
                    $query->select('title','project_id','language_id','id','short_description','slug');
                }])
                    ->where(function ($query) {
                        $query->where('expiry_date','>',Carbon::now())
                            ->orWhere('project_duration_has_unlimited' ,1);
                    })
                    ->where('status',1)
                    ->where('is_deleted',0)
                    ->inRandomOrder()->limit(4)->get();

                $view->with('projects' , $projects);
            });

            view()->composer([
                $data['theme'] . 'sections.product_section',
            ], function ($view) {
                $projects = Project::with(['details' => function ($query) {
                    $query->select('title','project_id','language_id','id','short_description','slug');
                }])
                    ->where(function ($query) {
                        $query->where('expiry_date','>',Carbon::now())
                            ->orWhere('project_duration_has_unlimited' ,1);
                    })
                    ->where('status',1)
                    ->where('is_deleted',0)
                    ->orderBy('created_at','desc')
                    ->limit(6)->get();

                $view->with('projects' , $projects);
            });

            \view()->composer([
                $data['theme'] . 'pages.project_details',
                $data['theme'] . 'pages.blog_details',
                $data['theme'] . 'pages.blogs',
            ], function ($view) {
                $contentData = ContentDetails::with('content')
                    ->whereHas('content', function ($query)   {
                        $query->where('name', 'subscribe_section');
                    })
                    ->get();
                $blog_section = ContentDetails::with('content')
                    ->whereHas('content', function ($query)  {
                        $query->where('name', 'blog_section');
                    })
                    ->get();
                $single_content2 = $blog_section->where('content.name', 'blog_section')->where('content.type', 'single')->first();
                $single_content = $contentData->where('content.name', 'subscribe_section')->where('content.type', 'single')->first();
                $datas = [
                    'single' => $single_content? collect($single_content->description ?? [])->merge($single_content->content->only('media')) : []
                ];
                $view->with("subscribe_section", $datas);

                $datas2 =  [
                    'single' => $single_content2? collect($single_content2->description ?? [])->merge($single_content2->content->only('media')) : []
                ];

                $view->with("blog_section", $datas2);


            });


            view()->composer([
                $data['theme'] . 'sections.investment_way_section',
            ], function ($view) {
                $contentData = ContentDetails::with('content')
                    ->whereHas('content', function ($query)   {
                        $query->where('name', 'banner_section_1');
                    })
                    ->get();

                $multipleContents = $contentData->where('content.name', 'banner_section_1')->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                    return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
                });

                $view->with('partners', $multipleContents);
            });

            view()->composer([
                $data['theme'] . 'sections.blog_section_3',
            ], function ($view) {
                $blogs = Blog::with('details')->paginate(9);
                $view->with('blogs', $blogs);
            });

            view()->composer([
                $data['theme'] . 'sections.project_section_2',
            ], function ($view) {
                $project_section = ContentDetails::with('content')
                    ->whereHas('content', function ($query)  {
                        $query->where('name', 'project_section');
                    })
                    ->get();
                $single_content = $project_section->where('content.name', 'project_section')->where('content.type', 'single')->first();
                $datas = [
                    'single' => $single_content? collect($single_content->description ?? [])->merge($single_content->content->only('media')) : []
                ];
                $view->with("project_section", $datas);
            });

            \view()->composer([
                $data['theme'] . 'partials.footer',
            ], function ($view) {
                $footer_section = ContentDetails::with('content')
                    ->whereHas('content', function ($query)  {
                        $query->where('name', 'footer_section');
                    })
                    ->get();
                $single_content = $footer_section->where('content.name', 'footer_section')->where('content.type', 'single')->first();
                $multipleContents = $footer_section->where('content.name', 'footer_section')->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                    return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
                });

                $datas = [
                    'single' => $single_content? collect($single_content->description ?? [])->merge($single_content->content->only('media')) : [],
                    'multiple' => $multipleContents
                ];
                $view->with("footer_section", $datas);
            });


            view()->composer([
                $data['theme'] . 'pages.blogs',
                $data['theme'] . 'pages.project_details',
            ], function ($view) {
                $counter_section = ContentDetails::with('content')
                    ->whereHas('content', function ($query)  {
                        $query->where('name', 'counter_section_2');
                    })
                    ->get();

                $single_content = $counter_section->where('content.name', 'counter_section_2')->where('content.type', 'single')->first();
                $multipleContents = $counter_section->where('content.name', 'counter_section_2')->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                    return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
                });

                $datas = [
                    'single' => $single_content? collect($single_content->description ?? [])->merge($single_content->content->only('media')) : [],
                    'multiple' => $multipleContents
                ];
                $view->with("counter_section_2", $datas);
                $farming = ContentDetails::with('content')
                    ->whereHas('content', function ($query)  {
                        $query->where('name', 'farming_section');
                    })
                    ->get();
                $single_content1 = $farming->where('content.name', 'farming_section')->where('content.type', 'single')->first();
                $datas2 = [
                    'single' => $single_content1? collect($single_content1->description ?? [])->merge($single_content1->content->only('media')) : [],
                ];
                $view->with("farming_section", $datas2);

            });

            \view()->composer([
                $data['theme'] . 'sections.blog_section',
                $data['theme'] . 'sections.blog_section_2',
                $data['theme'] . 'sections.blog_section_3',
            ], function ($view) {
                $blogs = Blog::with('details')->latest()->limit(4)->get();
                $view->with('blogs', $blogs);
            });

            view()->composer([
                $data['theme'] . 'auth.login',
                $data['theme'] . 'auth.register',
            ], function ($view) {
                $login_registration = ContentDetails::with('content')
                    ->whereHas('content', function ($query)  {
                        $query->where('name', 'login_registration');
                    })
                    ->get();

                $single_content = $login_registration->where('content.name', 'login_registration')->where('content.type', 'single')->first();
                $datas = [
                    'single' => $single_content? collect($single_content->description ?? [])->merge($single_content->content->only('media')) : [],
                ];
                $view->with("login_registration", $datas);
            });

            view()->composer([
                $data['theme'] . 'partials.footer',
            ], function ($view) {
                $languages = Language::where('status', 1)->get();
                $view->with('languages', $languages);
            });





            if (basicControl()->force_ssl == 1) {
                if ($this->app->environment('production') || $this->app->environment('local')) {
                    \URL::forceScheme('https');
                }
            }

            Mail::extend('sendinblue', function () {
                return (new SendinblueTransportFactory)->create(
                    new Dsn(
                        'sendinblue+api',
                        'default',
                        config('services.sendinblue.key')
                    )
                );
            });

            Mail::extend('sendgrid', function () {
                return (new SendgridTransportFactory)->create(
                    new Dsn(
                        'sendgrid+api',
                        'default',
                        config('services.sendgrid.key')
                    )
                );
            });

            Mail::extend('mandrill', function () {
                return (new MandrillTransportFactory)->create(
                    new Dsn(
                        'mandrill+api',
                        'default',
                        config('services.mandrill.key')
                    )
                );
            });

        } catch (\Exception $e) {
        }

    }
}
