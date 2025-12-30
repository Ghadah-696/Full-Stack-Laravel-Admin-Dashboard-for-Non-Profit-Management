<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'summary_ar',
        'summary_en',
        'body_ar',
        'body_en',
        'image',
        'category_id',
        'status',
        'start_date',
        'end_date',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class); // المشروع ينتمي لتصنيف واحد
    }
}
