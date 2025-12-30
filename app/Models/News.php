<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'body_ar',
        'body_en',
        'summary_ar',
        'summary_en',
        'image',
        'category_id', // هذا هو الحقل الجديد
        'status',
    ];

    // إضافة العلاقة
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
