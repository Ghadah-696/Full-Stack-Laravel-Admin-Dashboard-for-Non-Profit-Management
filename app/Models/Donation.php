<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Donation extends Model
{
    protected $fillable = [
        // الأعمدة الأساسية
        'amount',
        'currency',
        'payment_method',
        'donor_name',
        'status',

        // علاقة المتبرع
        'user_id',

        // 💡 أعمدة تتبع التدقيق (Audit Fields)
        'created_by',
        'updated_by',
    ];

    // ... هنا يمكنك إضافة علاقة بسيطة لتتبع من أنشأ السجل
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    protected static function boot()
    {
        parent::boot();

        // 1. عند إنشاء سجل جديد
        static::creating(function ($model) {
            // التحقق من وجود مستخدم مُسجَّل
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        // 2. عند تحديث سجل موجود
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
