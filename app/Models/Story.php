<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    //
    protected $fillable = [
        // 'title_ar', 'title_en', 'slug', 'body_ar', 'body_en', 'image'
        'name_ar',          // 💡 جديد
        'name_en',          // 💡 جديد
        'title_ar',
        'title_en',
        'content_ar',       // 💡 تم تغيير التسمية من body
        'content_en',       // 💡 تم تغيير التسمية من body
        'image',
        'order',            // 💡 جديد
        'status',           // 💡 جديد
    ];
}
