<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPlan;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageDetail;
use App\Models\Project;
use App\Traits\Frontend;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    use Frontend, Notify;



    public function __construct()
    {
        try {
            $connection = DB::connection()->getPdo();
        } catch (\Exception $exception) {
            \Cache::forget('ConfigureSetting');
            die("Unable to establish a connection to the database. Please check your connection settings and try again later");
        }
    }


    public function page($slug = '/')
    {

        $selectedTheme = basicControl()->theme ?? 'light';
        $existingSlugs = collect([]);
        DB::table('pages')->select('slug')->get()->map(function ($item) use ($existingSlugs) {
            $existingSlugs->push($item->slug);
        });

        if (!in_array($slug, $existingSlugs->toArray())) {
            abort(404);
        }
        try {
            $pageDetails = PageDetail::with('page')
                ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                    $query->where(['slug' => $slug, 'template_name' => $selectedTheme]);
                })
                ->first();

            $pageSeo = Page::where('slug', $slug)->first();
            if ($pageSeo->meta_keywords){
                $pageSeo->meta_keywords =  implode(",", $pageSeo->meta_keywords);
            }


            $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);

            $sectionsData = $this->getSectionsData($pageDetails->sections, $pageDetails->content, $selectedTheme);

            return view("themes.{$selectedTheme}.page", compact('sectionsData', 'pageSeo'));

        } catch (\Exception $exception) {
            
            dd($exception->getMessage());
            return redirect()->route('instructionPage');
        }
    }


    public function sentContactInfo(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required | numeric',
            'email' => 'required | email',
            'message' => 'required',
            'invest_type' => 'nullable'
        ]);
        $params = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'invest_type' => $request->invest_type,
            'message' => $request->message
        ];
        $actionAdmin = [
            "name" => $request->name,
            "image" => Auth::user() ? getFile(Auth::user()->image_driver, Auth::user()->image) : getFile('driver', 'image'),
            "link" => '#',
            "icon" => "fas fa-ticket-alt text-white"
        ];
        $firebaseAction = '#';
        $this->adminMail('CONTACT_INFO', $params);
        $this->adminPushNotification('CONTACT_INFO', $params, $actionAdmin);
        $this->adminFirebasePushNotification('CONTACT_INFO', $params, $firebaseAction);

        return redirect()->back()->with('success', 'Your information has been sent.');
    }


    public function language($locale)
    {
        app()->setLocale($locale);
        $lang = Language::where('short_name', $locale)->first();
        session()->put('lang', $locale);
        session()->put('language', $lang);
        return redirect()->back();
    }

    public function projects()
    {
        $projects = Project::with('details')
            ->where(function ($query) {
                $query->where('expiry_date','>',Carbon::now())
                    ->orWhere('project_duration_has_unlimited' ,1);
            })
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view(template() . 'user.projects.index', compact('projects'));
    }

    public function plans()
    {
        $plans = InvestmentPlan::where(['status' => 1, 'soft_delete' => 0])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view(template() . 'user.plan.index', compact('plans'));
    }
}
