<?php

namespace App\Http\Controllers;

// 1. استيراد السمات الضرورية للمصادقة والتحقق (عادة تكون موجودة)
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
// 2. استيراد المتحكم الأساسي لـ Laravel وتسميته BaseController
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController // 💡 يجب أن يرث من BaseController
{
    use AuthorizesRequests, ValidatesRequests; // استخدام السمات المستوردة
}