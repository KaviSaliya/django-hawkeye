<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetails extends Model
{
    use HasFactory,Translatable;


    protected  $fillable = [
        'project_id',
        'title',
        'slug',
        'description',
        'short_description',
        'language_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
