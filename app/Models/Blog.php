<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['meta_keywords' => 'array'];

    public function details()
    {
        return $this->hasOne(BlogDetails::class, 'blog_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function getLanguageEditClass($id, $languageId)
    {
        return DB::table('blog_details')->where(['blog_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }

    public function blogImage()
    {
        return getFile($this->blog_image_driver,$this->blog_image);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function createdByInfo($info=null)
    {
       if ($this->createdBy){
           if ($info == 'name'){
               return $this->createdBy->name;
           }elseif ($info == 'username'){
               return '@'.$this->createdBy->username;
           }elseif ($info == 'image'){
               return getFile($this->createdBy->image_driver,$this->createdBy->image);
           }
       }

    }

    public function breadcrumbImage()
    {
        return getFile($this->breadcrumb_image_driver,$this->breadcrumb_image);
    }

}
