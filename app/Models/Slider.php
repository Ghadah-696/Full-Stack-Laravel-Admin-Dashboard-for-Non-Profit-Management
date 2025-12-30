<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    //
    // protected $fillable = ['title_ar', 'title_en', 'body_ar', 'body_en', 'image', 'link'];
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar', // 💡 تم التحديث
        'description_en', // 💡 تم التحديث
        'image',
        'link',
        'order',    // 💡 تم الإضافة
        'status',   // 💡 تم الإضافة
    ];
}
