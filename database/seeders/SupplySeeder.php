<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Supply;
use App\Models\User;

class SupplySeeder extends Seeder
{
    public function run()
    {
        // 1. تنظيف الجداول قبل البدء
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Supply::truncate();
        DB::table('supply_inventories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. جلب جميع شركات التوريد (الـ 12 شركة والمقر الرئيسي)
        $companies = User::where('role', 'supply_company')->get();

        if ($companies->isEmpty()) {
            $this->command->error('No supply companies found to link products to!');
            return;
        }

        $data = [
            'لحوم ومشاوي' => [
                ['name' => 'كباب بلدي 1كغ', 'price' => 9.00, 'stock' => 50, 'desc' => 'لحم خروف بلدي طازج مفروم مع البقدونس والبصل.', 'image' => 'كباب.webp'],
                ['name' => 'ريش غنم متبلة 1كغ', 'price' => 10.50, 'stock' => 30, 'desc' => 'ريش غنم بلدي متبلة بالخلطة الخاصة للجريل.', 'image' => 'ريش.webp'],
                ['name' => 'دجاج كامل متبل 1.2كغ', 'price' => 3.50, 'stock' => 45, 'desc' => 'دجاج طازج متبل ببهارات الشواء التقليدية.', 'image' => 'دجاج.webp'],
            ],
            'خضار وفواكه' => [
                ['name' => 'سلة خضار مشكلة (كبيرة)', 'price' => 2.00, 'stock' => 100, 'desc' => 'تتضمن بندورة، خيار، خس، فلفل وبصل.', 'image' => 'خضار.webp'],
                ['name' => 'بطيخ أحمر (حبة كبيرة)', 'price' => 2.50, 'stock' => 60, 'desc' => 'بطيخ حلو ومبرد من مزارع الغور.', 'image' => 'بطيخ.webp'],
                ['name' => 'صحن فواكه مقطع جاهز', 'price' => 4.00, 'stock' => 25, 'desc' => 'تشكيلة من الفواكه الموسمية المقطعة.', 'image' => 'فواكه.webp'],
            ],
            'مقبلات وسلطات' => [
                ['name' => 'صحن حمص نخب أول', 'price' => 1.00, 'stock' => 40, 'desc' => 'حمص بالطحينة والزيت النباتي.', 'image' => 'حمص.webp'],
                ['name' => 'متبل باذنجان مشوي', 'price' => 1.50, 'stock' => 40, 'desc' => 'باذنجان مشوي على الحطب مع الطحينة.', 'image' => 'باذنجان.webp'],
                ['name' => 'صحن تبولة كبير', 'price' => 1.50, 'stock' => 30, 'desc' => 'تبولة لبنانية أصلية بالبرغل والليمون.', 'image' => 'تبولة.webp'],
            ],
            'أدوات ومعدات الشواء' => [
                ['name' => 'فحم نخب أول 5كغ', 'price' => 5.00, 'stock' => 200, 'desc' => 'فحم سنديان سريع الاشتعال ويدوم طويلاً.', 'image' => 'فحم.png'],
                ['name' => 'مجموعة أسياخ ستانلس', 'price' => 3.00, 'stock' => 15, 'desc' => 'طقم 10 أسياخ عريضة للشواء.', 'image' => 'أسياخ.webp'],
                ['name' => 'شبكة شواء مزدوجة', 'price' => 2.50, 'stock' => 20, 'desc' => 'شبكة ستانلس للجريل واللحوم.', 'image' => 'شبكة.webp'],
                ['name' => 'منفاخ هواء يدوي', 'price' => 3.00, 'stock' => 50, 'desc' => 'منفاخ قوي لتسريع اشتعال الفحم.', 'image' => 'منفاخ.webp'],
            ],
            'تسالي وحلويات' => [
                ['name' => 'علبة بقلاوة مشكلة 500غ', 'price' => 2.00, 'stock' => 40, 'desc' => 'بقلاوة بالسمن البلدي والفستق الحلبي.', 'image' => 'بقلاوة.webp'],
                ['name' => 'مكسرات مشكلة نخب أول', 'price' => 6.00, 'stock' => 100, 'desc' => 'تشكيلة محمصة طازجة (لوز، فستق، كاجو).', 'image' => 'مكسرات.webp'],
                ['name' => 'شيبس وتسالي عائلية', 'price' => 1.50, 'stock' => 150, 'desc' => 'مجموعة سناك متنوعة للجلسات.', 'image' => 'شيبس.webp'],
                ['name' => 'كنافة نابلسية (طبق صغير)', 'price' => 1.00, 'stock' => 20, 'desc' => 'كنافة ساخنة بالجبنة والقطر.', 'image' => 'كنافة.webp'],
            ],
            'مشروبات وثلج' => [
                ['name' => 'باكيت ثلج نقي 2كغ', 'price' => 1.50, 'stock' => 300, 'desc' => 'مكعبات ثلج نقية مبردة.', 'image' => 'ثلج.webp'],
                ['name' => 'بيبسي/كولا 2.25 لتر', 'price' => 1.50, 'stock' => 200, 'desc' => 'عبوة عائلية غازية.', 'image' => 'مشروبات.webp'],
                ['name' => 'عصير برتقال طبيعي 1 لتر', 'price' => 1.50, 'stock' => 50, 'desc' => 'عصير طازج بدون سكر مضاف.', 'image' => 'عصير.webp'],
                ['name' => 'مجموعة عصائر مشكلة 6 حبات', 'price' => 3.00, 'stock' => 60, 'desc' => 'تشكيلة من العصائر المنوعة للرحلات.', 'image' => 'عصائر.webp'],
            ],
            'مستلزمات السفرة والنظافة' => [
                ['name' => 'طقم صحون بلاستيك 50 حبة', 'price' => 1.50, 'stock' => 150, 'desc' => 'صحون بلاستيكية مقواة وسميكة مناسبة للرحلات.', 'image' => 'صحون_بلاستيك.webp'],
                ['name' => 'طقم كاسات ورق وشاي 100 حبة', 'price' => 1.75, 'stock' => 200, 'desc' => 'كاسات كرتونية دبل للمشروبات الساخنة والباردة.', 'image' => 'كاسات_ورق.webp'],
                ['name' => 'مفرش طاولة سفرة (رول 20 م)', 'price' => 1.00, 'stock' => 120, 'desc' => 'رول سفرة بلاستيك منقوش وسهل القطع.', 'image' => 'مفرش_سفرة.webp'],
                ['name' => 'باكيت محارم ورقية عائلي 5 حبات', 'price' => 3.50, 'stock' => 100, 'desc' => 'محارم ناعمة وعالية الامتصاص ومتعددة الاستخدامات.', 'image' => 'محارم.webp'],
                ['name' => 'كيس نفايات برباط (حجم كبير)', 'price' => 1.50, 'stock' => 180, 'desc' => 'أكياس نفايات سميكة وقوية مزودة برباط لسهولة الغلق.', 'image' => 'اكياس_نفايات.webp'],
            ],
            'ألعاب وترفيه' => [
                ['name' => 'كرة قدم نخب أول', 'price' => 3.00, 'stock' => 40, 'desc' => 'كرة قدم جلدية متينة ومناسبة للملاعب العشبية في المزارع.', 'image' => 'كرة_قدم.webp'],
                ['name' => 'مجموعة ألعاب ورقية (شدة وطاولة)', 'price' => 3.00, 'stock' => 80, 'desc' => 'تتضمن ورق لعب بلوت/تركس عالي الجودة مع نرد.', 'image' => 'العاب_ورقية.webp'],
                ['name' => 'لعبة طاولة زهر خشبية', 'price' => 6.00, 'stock' => 25, 'desc' => 'طاولة زهر كلاسيكية مصنوعة من الخشب الفاخر لجلسات السمر.', 'image' => 'طاولة_زهر.webp'],
                ['name' => 'طقم مضارب تنس ريشة حبتين', 'price' => 3.50, 'stock' => 35, 'desc' => 'مضربين تنس ريشة مع حبتين ريش شبك، مسلية جداً للعائلات.', 'image' => 'تنس_ريشة.webp'],
                ['name' => 'لعبة أونو (UNO) الأصلية', 'price' => 1.50, 'stock' => 90, 'desc' => 'لعبة كروت جماعية حماسية ومناسبة لجميع الأعمار.', 'image' => 'اونو.webp'],
            ],
        ];

        // 3. المنطق الصحيح لإنشاء المنتجات وربطها بالشركات
        foreach ($data as $category => $products) {
            foreach ($products as $p) {

                // أ) إنشاء المنتج *مرة واحدة فقط* في جدول supplies
                $supply = Supply::create([
                    'name'        => $p['name'],
                    'category'    => $category,
                    'price'       => $p['price'],
                    'description' => $p['desc'],
                    'image'       => $p['image'],
                ]);

                // ب) المرور على جميع الشركات وربط هذا المنتج بها بكميات عشوائية
                $inventories = [];
                foreach ($companies as $company) {
                    // جعل المخزون عشوائياً (من نصف الكمية الأصلية إلى ضعفها) ليكون واقعياً
                    $randomStock = rand(max(1, intval($p['stock'] * 0.5)), intval($p['stock'] * 2));

                    $inventories[] = [
                        'supply_id'  => $supply->id,
                        'company_id' => $company->id,
                        'stock'      => $randomStock,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // ج) إدخال المخزون لكل الشركات دفعة واحدة (أفضل للأداء)
                DB::table('supply_inventories')->insert($inventories);
            }
        }

        $this->command->info("Supply Marketplace populated! " . Supply::count() . " unique products linked across {$companies->count()} branches with dynamic stock.");
    }
}
