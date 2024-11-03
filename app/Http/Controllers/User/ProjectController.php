<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectDetails;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::with(['details' => function ($query) {
            $query->select('title','project_id','language_id','id');
        }])
            ->where('status',1)
            ->where('is_deleted',0)
            ->orderBy('created_at','desc')
            ->paginate(8);
        return view('pages.project',compact('projects'));
    }

   public function details($slug)
   {
       $pageSeo = Page::where('slug', 'project_details')->first();
       $pageSeo->meta_keywords =isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords? implode(",", $pageSeo->meta_keywords):'';
       $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);
       $projectDetails = ProjectDetails::with('project')->where('slug',$slug)->first();
       if (!$projectDetails){
           abort(404);
       }
       $project = $projectDetails->project;
       return view(template().'pages.project_details',compact('project','pageSeo'));
   }
}
