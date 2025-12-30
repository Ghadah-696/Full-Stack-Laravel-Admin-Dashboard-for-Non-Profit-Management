@extends('layouts.admin')

@section('page_title', 'لوحة التحكم الرئيسية')

@section('content')
    <!-- <div class="bg-white p-6 rounded-lg shadow-md">
                                                                                                                                <h2 class="text-2xl font-bold mb-4" style="color: var(--primary-color);">مرحباً بك في لوحة التحكم!</h2>
                                                                                                                                <p class="text-gray-700">من هنا يمكنك إدارة كل محتوى موقعك.</p>

                                                                                                                                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                                                                                                                    <div class="card">
                                                                                                                                        <h3 class="text-lg font-semibold mb-2" style="color: var(--primary-color);">الأخبار</h3>
                                                                                                                                        <p class="text-3xl font-bold">150</p>
                                                                                                                                    </div>

                                                                                                                                    <div class="card">
                                                                                                                                        <h3 class="text-lg font-semibold mb-2" style="color: var(--primary-color);">المشاريع</h3>
                                                                                                                                        <p class="text-3xl font-bold">45</p>
                                                                                                                                    </div>

                                                                                                                                    <div class="card">
                                                                                                                                        <h3 class="text-lg font-semibold mb-2" style="color: var(--primary-color);">المستخدمون</h3>
                                                                                                                                        <p class="text-3xl font-bold">220</p>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                            </div> -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background-light);
        }

        :root {
            --primary-color: #38b6ff;
            /* أزرق سماوي */
            --primary-hover-color: #1e87c0;
            --secondary-color: #212529;
            --background-light: #f4f7fa;
            --trend-up: #10B981;
            /* أخضر للارتفاع */
            --trend-down: #EF4444;
            /* أحمر للانخفاض */
            --warning-color: #FBBF24;
            /* أصفر للتحذيرات */
            --danger-color: #EF4444;
            /* أحمر للخطر */
        }

        /* تنسيقات البطاقات والمخططات */
        .card-kpi {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.3s;
            border-right: 5px solid var(--primary-color);
            /* حافة جانبية مميزة */
        }

        /* بطاقة KPI صغيرة داخل الجزء المخصص للمخطط الرئيسي */
        .card-mini-kpi {
            background-color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
            border: 1px solid #e5e7eb;
        }

        .card-mini-kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .card-kpi:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .text-primary-custom {
            color: var(--primary-color);
        }

        .sparkline-canvas {
            display: block;
            margin-top: 10px;
            width: 100%;
            height: 40px;
        }

        /* إزالة الارتفاع الثابت للمخطط الرئيسي وجعله مرنًا */
        #main-chart-container {
            min-height: 250px;
            display: flex;
            /* لضمان أن Canvas يملأ حاويته */
            align-items: stretch;
            justify-content: stretch;
        }

        /* لجعل Canvas يملأ ارتفاع وعرض الحاوية المرنة */
        #main-chart {
            width: 100%;
            /* تم تعديل الارتفاع إلى 450px هنا */
            height: 350px;
        }

        /* تحسين مظهر قسم التنبيهات */
        .alert-card {
            border-right: 4px solid;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        /* لضمان أن الارتفاع الإجمالي للقسم مرن ويسمح بتمدد البطاقة الرئيسية */
        .dashboard-main-area {
            min-height: 500px;
        }

        /* تنسيق شاشة التحميل (Splash Screen) */
        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--primary-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.5s ease-out, visibility 0s 0.5s;
            visibility: visible;
        }

        #splash-screen.hidden {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-out, visibility 0s 0.5s;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #ffffff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-top: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* #main-chart {
                        width: 100%;
                        height: 320px;
                    } */
    </style>
    <!-- شاشة التحميل (Splash Screen) - تم الإضافة هنا -->
    <div id="splash-screen">
        <div class="text-center text-white p-8 rounded-lg">
            <i class="fas fa-chart-bar text-8xl mb-4"></i>
            <h1 class="text-6xl font-black mb-2 tracking-wide">Performance Management Dashboard</h1>
            <span>Monitor, Analyze & Improve Performance</span>
            <h2 class="text-3xl font-light italic">لوحة تحكم إدارة الأداء</h2>
            <div class="spinner"></div>
        </div>
    </div>
    <div id="main-content" class="space-y-8 opacity-0">
        <div class="space-y-8">
            <!-- قسم الترحيب و الملخص (تم تطوير المظهر) -->
            <div class="bg-white p-8 rounded-xl shadow-2xl border-b-4" style="border-color: var(--primary-hover-color);">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-5xl ml-4" style="color: var(--primary-color);"></i>
                    <div>
                        <h2 class="text-4xl font-extrabold mb-1" style="color: var(--secondary-color);">لوحة تحكم منظمة
                            الأمل
                            الخيرية</h2>
                        <p class="text-gray-600 text-lg">مرحباً بعودتك! أداء المنظمة لهذا الشهر يبدو قوياً جداً.</p>
                    </div>
                </div>
            </div>

            <!-- 1. بطاقات مؤشرات الأداء الرئيسية (KPI Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- البطاقة 1: إجمالي التبرعات -->
                <div class="card-kpi" style="border-right-color: var(--primary-color);">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-1">إجمالي التبرعات (الشهر)</h3>
                            <p class="text-4xl font-extrabold text-gray-900 mt-1">1.2 مليون <span
                                    class="text-lg font-normal text-gray-600">$</span></p>
                        </div>
                        <i class="fas fa-hand-holding-usd text-5xl text-primary-custom opacity-75"></i>
                    </div>
                    <canvas id="chart-donations" class="sparkline-canvas"></canvas>
                    <div class="mt-3 flex items-center">
                        <span class="flex items-center text-sm font-bold ml-2" style="color: var(--trend-up);">
                            <i class="fas fa-arrow-up ml-1"></i> 3.4%
                        </span>
                        <span class="text-xs text-gray-500">مقارنة بالشهر الماضي</span>
                    </div>
                </div>

                <!-- البطاقة 2: المستفيدون (أفراد) -->
                <div class="card-kpi" style="border-right-color: var(--trend-up);">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-1">المستفيدون (أفراد)</h3>
                            <p class="text-4xl font-extrabold text-gray-900 mt-1">45,980 <span
                                    class="text-lg font-normal text-gray-600">فرد</span></p>
                        </div>
                        <i class="fas fa-users text-5xl opacity-75" style="color: var(--trend-up);"></i>
                    </div>
                    <canvas id="chart-beneficiaries" class="sparkline-canvas"></canvas>
                    <div class="mt-3 flex items-center">
                        <span class="flex items-center text-sm font-bold ml-2" style="color: var(--trend-up);">
                            <i class="fas fa-arrow-up ml-1"></i> 11%
                        </span>
                        <span class="text-xs text-gray-500">منذ بداية العام</span>
                    </div>
                </div>

                <!-- البطاقة 3: المشاريع النشطة -->
                <div class="card-kpi" style="border-right-color: var(--warning-color);">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-1">المشاريع النشطة</h3>
                            <p class="text-4xl font-extrabold text-gray-900 mt-1">14 <span
                                    class="text-lg font-normal text-gray-600">مشروع</span></p>
                        </div>
                        <i class="fas fa-project-diagram text-5xl opacity-75" style="color: var(--warning-color);"></i>
                    </div>
                    <canvas id="chart-projects" class="sparkline-canvas"></canvas>
                    <div class="mt-3 flex items-center">
                        <span class="flex items-center text-sm font-bold ml-2" style="color: var(--trend-down);">
                            <i class="fas fa-arrow-down ml-1"></i> -2
                        </span>
                        <span class="text-xs text-gray-500">أُغلقت بنجاح الشهر الماضي</span>
                    </div>
                </div>

                <!-- البطاقة 4: ساعات العمل التطوعي -->
                <div class="card-kpi" style="border-right-color: var(--primary-color);">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-1">ساعات العمل التطوعي</h3>
                            <p class="text-4xl font-extrabold text-gray-900 mt-1">8,200 <span
                                    class="text-lg font-normal text-gray-600">ساعة</span></p>
                        </div>
                        <i class="fas fa-clock text-5xl text-primary-custom opacity-75"></i>
                    </div>
                    <canvas id="chart-volunteers" class="sparkline-canvas"></canvas>
                    <div class="mt-3 flex items-center">
                        <span class="flex items-center text-sm font-bold ml-2" style="color: var(--trend-up);">
                            <i class="fas fa-arrow-up ml-1"></i> +560 س.
                        </span>
                        <span class="text-xs text-gray-500">زيادة هذا الأسبوع</span>
                    </div>
                </div>
            </div>

            <!-- 2. قسم المخطط البياني الرئيسي والملخص والتنبيهات
                                                                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> -->




            <!-- 2. قسم المخطط البياني الرئيسي والملخص والتنبيهات -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 dashboard-main-area">

                <!-- العمود 1: المخطط البياني الرئيسي والبطاقات الإضافية (تم دمجهما في بطاقة واحدة) -->
                <!-- إضافة flex flex-col h-full لتمكين التمدد العمودي -->
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-xl flex flex-col h-full">

                    <!-- عنوان المخطط -->
                    <h3 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-2">أداء التبرعات ومقاييس الكفاءة (الربع
                        الأخير)
                    </h3>

                    <!-- Grid داخلي لتقسيم محتوى البطاقة -->
                    <div class="grid grid-cols-1 gap-6 flex-grow">

                        <!-- الصف العلوي: المخطط البياني -->
                        <!-- إضافة flex-grow لملء المساحة المتبقية من الأب -->
                        <div class="bg-white p-6 rounded-xl shadow-xl">
                            <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">توزيع التبرعات حسب الفئة (الربع
                                الأخير)</h3>
                            <canvas id="main-chart"></canvas>
                        </div>

                        <!-- الصف السفلي: 4 مقاييس سريعة إضافية -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100 flex-shrink-0">

                            <!-- بطاقة 1: معدل استغلال التبرعات -->
                            <div class="card-mini-kpi text-center border-b-4 border-l-4 border-green-500"
                                style="border-color: var(--trend-up);">
                                <i class="fas fa-check-circle text-2xl mb-1" style="color: var(--trend-up);"></i>
                                <p class="text-2xl font-bold text-gray-900">92%</p>
                                <p class="text-xs text-gray-500 mt-1 font-semibold">معدل الاستغلال</p>
                            </div>

                            <!-- بطاقة 2: عدد الحملات المفتوحة -->
                            <div class="card-mini-kpi text-center border-b-4 border-l-4"
                                style="border-color: var(--primary-color);">
                                <i class="fas fa-bullhorn text-2xl text-primary-custom mb-1"></i>
                                <p class="text-2xl font-bold text-gray-900">7</p>
                                <p class="text-xs text-gray-500 mt-1 font-semibold">حملات نشطة</p>
                            </div>

                            <!-- بطاقة 3: نسبة إنجاز الأهداف السنوية -->
                            <div class="card-mini-kpi text-center border-b-4 border-l-4 border-yellow-500"
                                style="border-color: var(--warning-color);">
                                <i class="fas fa-tasks text-2xl mb-1" style="color: var(--warning-color);"></i>
                                <p class="text-2xl font-bold text-gray-900">78%</p>
                                <p class="text-xs text-gray-500 mt-1 font-semibold">إنجاز الأهداف</p>
                            </div>

                            <!-- بطاقة 4: متوسط قيمة التبرع -->
                            <div class="card-mini-kpi text-center border-b-4 border-l-4 border-indigo-500">
                                <i class="fas fa-dollar-sign text-2xl mb-1" style="color: #6366f1;"></i>
                                <p class="text-2xl font-bold text-gray-900">125 $</p>
                                <p class="text-xs text-gray-500 mt-1 font-semibold">متوسط التبرع</p>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- العمود 2: بطاقة الملخص السريع والتنبيهات -->
                <div class="space-y-6">

                    <!-- بطاقة الملخص المالي والعمليات -->
                    <div class="bg-white p-6 rounded-xl shadow-xl">
                        <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">ملخص الأداء السريع</h3>

                        <div class="space-y-4">
                            <!-- صافي النفقات -->
                            <div class="flex justify-between items-center p-3 rounded-lg bg-red-50 border-r-4"
                                style="border-color: var(--trend-down);">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave text-xl ml-3" style="color: var(--trend-down);"></i>
                                    <span class="font-medium text-gray-700">صافي النفقات</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">350,000 $</span>
                            </div>

                            <!-- التبرعات المتكررة -->
                            <div class="flex justify-between items-center p-3 rounded-lg bg-blue-50 border-r-4"
                                style="border-color: var(--primary-color);">
                                <div class="flex items-center">
                                    <i class="fas fa-redo-alt text-xl text-primary-custom ml-3"></i>
                                    <span class="font-medium text-gray-700">التبرعات المتكررة</span>
                                </div>
                                <span class="text-lg font-bold text-primary-custom">22%</span>
                            </div>
                        </div>
                    </div>

                    <!-- بطاقة التنبيهات والأولويات -->
                    <div class="bg-white p-6 rounded-xl shadow-xl">
                        <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">التنبيهات والأولويات</h3>
                        <div class="space-y-3">
                            <!-- تنبيه 1: مشاريع تتجاوز الميزانية -->
                            <div class="alert-card border-red-500 bg-red-50" style="border-color: var(--danger-color);">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-exclamation-circle text-lg ml-2"
                                        style="color: var(--danger-color);"></i>
                                    <span class="font-semibold text-sm" style="color: var(--danger-color);">خطر: تجاوز
                                        الميزانية</span>
                                </div>
                                <p class="text-sm text-gray-700">يوجد **2** من المشاريع تجاوزت ميزانيتها المخصصة.</p>
                                <a href="#" class="text-xs font-medium text-red-600 hover:underline mt-1 block">عرض التفاصيل
                                    &larr;</a>
                            </div>

                            <!-- تنبيه 2: مراجعة المتطوعين الجدد -->
                            <div class="alert-card border-yellow-500 bg-yellow-50"
                                style="border-color: var(--warning-color);">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-clipboard-check text-lg ml-2" style="color: var(--warning-color);"></i>
                                    <span class="font-semibold text-sm" style="color: var(--warning-color);">مهمة
                                        عاجلة</span>
                                </div>
                                <p class="text-sm text-gray-700">45 متطوعاً جديداً ينتظرون الموافقة والتدريب.</p>
                                <a href="#" class="text-xs font-medium text-yellow-600 hover:underline mt-1 block">بدء
                                    المراجعة
                                    &larr;</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Script to draw simple Sparklines and the main Chart using Canvas (تم الحفاظ على الكود الأصلي هنا) -->
    <script>
        // دالة لإخفاء شاشة التحميل وعرض المحتوى الرئيسي
        function hideSplashScreen() {
            const splashScreen = document.getElementById('splash-screen');
            const mainContent = document.getElementById('main-content');

            // إخفاء شاشة التحميل
            splashScreen.classList.add('opacity-0');
            // إزالة العنصر بعد انتهاء انتقال الشفافية
            setTimeout(() => splashScreen.remove(), 500);

            // جعل المحتوى الرئيسي مرئياً
            mainContent.classList.remove('opacity-0');
        }

        // دالة لرسم مخطط بياني خطي بسيط (Sparkline)
        function drawSparkline(canvasId, data, color) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            // تحديد الأبعاد الداخلية (بالبكسل) بناءً على حجم CSS لضمان الدقة
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;

            // تطبيع البيانات ليناسب ارتفاع الرسم
            const maxVal = Math.max(...data);
            const minVal = Math.min(...data);
            const range = maxVal === minVal ? 1 : maxVal - minVal;

            ctx.clearRect(0, 0, width, height);
            ctx.beginPath();
            ctx.lineWidth = 2;
            ctx.strokeStyle = color;

            data.forEach((value, index) => {
                const x = (index / (data.length - 1)) * width;
                // إضافة هامش صغير في الأعلى والأسفل (5px)
                const effectiveHeight = height - 10;
                const y = height - 5 - ((value - minVal) / range) * effectiveHeight;

                if (index === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            ctx.stroke();
        }

        // دالة لرسم مخطط بياني أكبر (Bar Chart محاكى)
        function drawBarChart(canvasId, labels, data, color) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            // **التحسين الرئيسي لزيادة الدقة**
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;

            // تخصيص مساحة للأسماء والأرقام
            const padding = 30;
            const chartHeight = height - padding * 1.5;
            const chartWidth = width - padding * 2;
            const barWidth = (chartWidth / data.length) * 0.6;
            const gap = (chartWidth / data.length) * 0.4;

            const maxVal = Math.max(...data) * 1.1; // لترك مسافة أعلى أعلى عمود

            ctx.clearRect(0, 0, width, height);

            // رسم خط المحور الأفقي (Base Line)
            ctx.beginPath();
            ctx.strokeStyle = '#ccc';
            ctx.moveTo(padding, height - padding);
            ctx.lineTo(width - padding, height - padding);
            ctx.stroke();

            // رسم خطوط الشبكة والأرقام على المحور الرأسي
            const numSegments = 5;
            for (let i = 0; i <= numSegments; i++) {
                const value = Math.round((maxVal / numSegments) * i);
                const y = height - padding - (chartHeight * i / numSegments);

                // رسم خطوط شبكة خفيفة
                ctx.strokeStyle = '#eee';
                ctx.beginPath();
                ctx.moveTo(padding, y);
                ctx.lineTo(width - padding, y);
                ctx.stroke();

                // كتابة القيمة (المحور الرأسي)
                ctx.fillStyle = '#666';
                ctx.font = '12px Cairo';
                ctx.textAlign = 'right';
                ctx.fillText(value, padding - 5, y + 4);
            }


            // رسم الأعمدة والتسميات
            data.forEach((value, index) => {
                const barH = (value / maxVal) * chartHeight;
                const x = padding + index * (barWidth + gap) + gap / 2;
                const y = height - padding - barH;

                // تعبئة الشريط
                ctx.fillStyle = color;
                ctx.fillRect(x, y, barWidth, barH);

                // كتابة القيمة فوق الشريط
                ctx.fillStyle = '#333';
                ctx.font = '14px Cairo';
                ctx.textAlign = 'center';
                ctx.fillText(value + '%', x + barWidth / 2, y - 5);

                // رسم التسمية (المحور الأفقي)
                ctx.fillStyle = '#666';
                ctx.font = '12px Cairo';
                ctx.textAlign = 'center';
                ctx.fillText(labels[index], x + barWidth / 2, height - padding + 15);
            });
        }

        // دالة لإعادة رسم المخططات عند تغيير حجم النافذة
        function handleResize() {
            // الحصول على الألوان من متغيرات CSS
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
            const trendUpColor = getComputedStyle(document.documentElement).getPropertyValue('--trend-up').trim();

            // إعادة رسم Sparklines
            drawSparkline('chart-donations', [10, 15, 22, 18, 25, 30, 28], primaryColor);
            drawSparkline('chart-beneficiaries', [5, 7, 8, 10, 9, 12, 11], primaryColor);
            drawSparkline('chart-projects', [4, 6, 5, 4, 3, 5, 4], trendUpColor);
            drawSparkline('chart-volunteers', [2, 3, 4, 6, 8, 7, 9], primaryColor);

            // إعادة رسم المخطط الرئيسي
            const mainChartLabels = ['المساعدات الغذائية', 'التعليم', 'الإغاثة الطارئة', 'الصحة'];
            const mainChartData = [60, 40, 85, 30];
            drawBarChart('main-chart', mainChartLabels, mainChartData, primaryColor);
        }

        // **المنطق المصحح للتشغيل والتوقيت**
        document.addEventListener('DOMContentLoaded', () => {
            // 1. بدء التأخير الزمني (لإظهار شاشة التحميل لمدة ثانيتين)
            setTimeout(() => {

                // 2. إخفاء شاشة التحميل
                hideSplashScreen();

                // 3. تشغيل الرسم الأولي للمخططات
                handleResize();

                // 4. إضافة مستمع لحدث تغيير حجم النافذة لضمان استجابة المخططات
                window.addEventListener('resize', handleResize);

            }, 2000); // 2000 مللي ثانية (ثانيتان)
        });
    </script>

@endsection