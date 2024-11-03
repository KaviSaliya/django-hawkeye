<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogDetails;
use App\Models\Content;
use App\Models\Page;
use App\Models\PageDetail;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $pageSeo = Page::where('slug', 'blogs')->first();
        $pageSeo->meta_keywords = isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords?  implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);

        $blogs = Blog::with('details')->paginate(9);
        return view(template().'pages.blogs', compact('blogs','pageSeo'));
    }

    public function details($slug)
    {
        $data['blogDetails'] = BlogDetails::with('blog')->where('slug',$slug)->first();
        if (!$data['blogDetails']){
            abort(404);
        }
        $pageSeo = $data['blogDetails']->blog;

        $pageSeo->meta_keywords = isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords? implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);
        $data['blog'] = $data['blogDetails']->blog;
        $data['blogs'] = Blog::with('details')->where('id','!=',$data['blog']->id)->limit(4)->orderBy('created_at', 'asc')->get();
        $data['categories'] = BlogCategory::get();
        return view(template().'pages.blog_details',$data,compact('pageSeo'));
    }

    public function categoryBlogs($id)
    {
        $pageSeo = Page::where('slug', 'blogs')->first();
        $pageSeo->meta_keywords = isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords? implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);
        $blogs = Blog::with('details')->where('category_id', $id)->paginate(9);

        return view(template().'pages.blogs', compact('blogs','pageSeo'));
    }

    public function search(Request $request)
    {
        $pageSeo = Page::where('slug', 'blogs')->first();
        $pageSeo->meta_keywords = isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords?implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);

        $blogs = Blog::with('details')
            ->whereHas('details', function($q) use ($request){
                $q->where('title', 'LIKE' , '%'.$request->title.'%');
            })->paginate(9);

        return view(template().'pages.blogs', compact('blogs','pageSeo'));
    }
}
