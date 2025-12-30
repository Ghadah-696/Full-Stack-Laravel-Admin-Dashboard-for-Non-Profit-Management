<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // //
    // protected $fillable = [
    //     'title_ar',
    //     'title_en',
    //     'slug',
    //     'body_ar',
    //     'body_en'
    // ];
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'body_ar',
        'body_en',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
        'keywords_ar',
        'keywords_en',
        'banner_image_path',
        'status',
        // 💡 تم إضافة Parent ID و Order
        'parent_id',
        'order',
    ];

    // علاقة الأب: الصفحة التي تنتمي إليها الصفحة الحالية
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    // علاقة الأبناء: الصفحات الفرعية التي تنتمي لهذه الصفحة
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('order');
    }
}

