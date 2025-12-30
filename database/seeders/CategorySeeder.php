<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مصفوفة التصنيفات الأساسية
        $categoriesData = [
            // التصنيفات الرئيسية (Mapped to 'project' type)
            [
                'name_ar' => 'الأمن الغذائي والإغاثة',
                'name_en' => 'Food Security & Relief',
                'description_ar' => 'يشمل توفير الغذاء الطارئ، ومكافحة سوء التغذية، وتوزيع السلال الغذائية الأساسية.',
                'description_en' => 'Includes provision of emergency food, combating malnutrition, and distribution of essential food baskets.',
                'type' => 'project', // تم التعديل من Main إلى project
            ],
            [
                'name_ar' => 'الإيواء والحماية',
                'name_en' => 'Shelter & Protection',
                'description_ar' => 'يشمل توفير الخيام والمساكن المؤقتة، مواد التدفئة، وضمان سلامة وحماية النازحين واللاجئين.',
                'description_en' => 'Includes provision of tents, temporary housing, heating materials, and ensuring the safety and protection of displaced persons and refugees.',
                'type' => 'project', // تم التعديل من Main إلى project
            ],
            [
                'name_ar' => 'الصحة والمياه والإصحاح',
                'name_en' => 'Health, WASH & Sanitation',
                'description_ar' => 'يشمل توفير الرعاية الصحية الأولية والطارئة، الأدوية، المياه النظيفة، ومستلزمات النظافة والتعقيم.',
                'description_en' => 'Includes provision of primary and emergency healthcare, medicines, clean water, and hygiene and sanitation supplies.',
                'type' => 'project', // تم التعديل من Main إلى project
            ],
            [
                'name_ar' => 'الدعم اللوجستي والتمويل',
                'name_en' => 'Logistics & Funding',
                'description_ar' => 'يشمل دعم نقل المساعدات عبر المعابر، إدارة سلسلة الإمداد، والتخطيط للتمويل العالمي وسد الفجوات.',
                'description_en' => 'Includes support for aid transportation across borders, supply chain management, and planning for global funding and gap coverage.',
                'type' => 'project', // تم التعديل من Main إلى project
            ],
            [
                'name_ar' => 'الاستجابة للكوارث والمناخ',
                'name_en' => 'Disaster & Climate Response',
                'description_ar' => 'يشمل الاستجابة للأزمات البيئية مثل الجفاف والفيضانات، ومشاريع التكيف مع التغير المناخي طويلة الأمد.',
                'description_en' => 'Includes response to environmental crises such as drought and floods, and long-term climate change adaptation projects.',
                'type' => 'project', // تم التعديل من Main إلى project
            ],

            // التصنيفات الفرعية/الأوسمة (Mapped to 'news' type)
            [
                'name_ar' => 'أزمة غزة',
                'name_en' => 'Gaza Crisis',
                'description_ar' => 'مخصصة للأخبار والمشاريع المتعلقة بالاستجابة الطارئة في قطاع غزة.',
                'description_en' => 'Dedicated to news and projects related to the emergency response in the Gaza Strip.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'سوء التغذية الحاد',
                'name_en' => 'Acute Malnutrition',
                'description_ar' => 'يُستخدم للإشارة إلى المشاريع التي تركز على علاج الأطفال وكبار السن الذين يعانون من سوء التغذية الشديد.',
                'description_en' => 'Used to refer to projects focusing on treating severely malnourished children and the elderly.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'مستلزمات الشتاء',
                'name_en' => 'Winterization Kits',
                'description_ar' => 'لتصنيف حملات توفير الملابس الدافئة والبطانيات ووقود التدفئة في المناطق الباردة.',
                'description_en' => 'To classify campaigns providing warm clothing, blankets, and heating fuel in cold regions.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'نزوح داخلي',
                'name_en' => 'Internal Displacement',
                'description_ar' => 'لتصنيف الأخبار والمشاريع التي تستهدف الأشخاص الذين أجبروا على ترك منازلهم داخل حدود بلادهم.',
                'description_en' => 'To classify news and projects targeting people forced to leave their homes within their country’s borders.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'نقص التمويل',
                'name_en' => 'Funding Shortfall',
                'description_ar' => 'يُستخدم في الأخبار والمناقشات المتعلقة بالفجوة التمويلية اللازمة للاستجابة للأزمات.',
                'description_en' => 'Used in news and discussions related to the funding gap required for crisis response.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'دعم الممرات الآمنة',
                'name_en' => 'Safe Access Support',
                'description_ar' => 'لتصنيف الجهود الرامية لتأمين وصول المساعدات الإنسانية إلى المناطق المحاصرة أو الخطرة.',
                'description_en' => 'To classify efforts aimed at securing humanitarian aid access to besieged or dangerous areas.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'الجفاف',
                'name_en' => 'Drought',
                'description_ar' => 'يُستخدم لتصنيف الاستجابة للأزمات الناتجة عن شح الأمطار ونقص المياه في المناطق المتأثرة.',
                'description_en' => 'Used to classify responses to crises resulting from lack of rainfall and water scarcity in affected areas.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'إغاثة أوكرانيا',
                'name_en' => 'Ukraine Relief',
                'description_ar' => 'مخصصة للأخبار والمشاريع المتعلقة بالأزمة الإنسانية المستمرة في أوكرانيا.',
                'description_en' => 'Dedicated to news and projects related to the ongoing humanitarian crisis in Ukraine.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'وفيات المدنيين',
                'name_en' => 'Civilian Fatalities',
                'description_ar' => 'لتصنيف الأخبار التي تسلط الضوء على الخسائر البشرية بين غير المقاتلين.',
                'description_en' => 'To classify news highlighting human losses among non-combatants.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
            [
                'name_ar' => 'الأزمة الوجودية',
                'name_en' => 'Existential Crisis',
                'description_ar' => 'يُستخدم في سياق الأزمات التي تهدد بقاء السكان ككل، مثل مجاعة وشيكة.',
                'description_en' => 'Used in the context of crises that threaten the survival of the entire population, such as impending famine.',
                'type' => 'news', // تم التعديل من Tag إلى news
            ],
        ];

        // 1. إضافة حقل 'slug' و Timesatamps لكل عنصر في المصفوفة
        $categories = collect($categoriesData)->map(function ($item) {
            $item['slug'] = Str::slug($item['name_en']); // توليد الـ Slug من الاسم الإنجليزي
            $item['created_at'] = now();
            $item['updated_at'] = now();
            return $item;
        })->all();


        // 2. تنفيذ Upsert
        DB::table('categories')->upsert(
            $categories,
            ['slug'], // استخدام الـ Slug كمعرف فريد للتحقق من التكرار
            ['description_ar', 'description_en', 'type', 'name_ar', 'name_en', 'updated_at'] // الحقول التي سيتم تحديثها عند التكرار
        );
    }
}
