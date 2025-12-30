<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    // منع الحماية من التعبئة الجماعية (Mass Assignment Protection) 
    // عبر تحديد الحقول المسموح بتعبئتها
    protected $fillable = [
        // 1. التواصل الأساسي
        'email',
        'phone_number',
        'address_ar',
        'address_en',

        // 2. التواصل الاجتماعي
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',

        // 3. العلامات التجارية
        'logo_path',
        'favicon_path',

        // 4. النصوص الإضافية
        'footer_text_ar',
        'footer_text_en',

        // 5. الإعدادات الاحترافية (SEO والتكامل والتحكم)
        'google_analytics_id',
        'google_maps_api_key',
        'default_meta_desc_ar',
        'default_meta_desc_en',
        'default_og_image_path',
        'maintenance_mode',
        'enable_accessibility_bar', // 💡 حقل شريط الوصول
    ];
}
