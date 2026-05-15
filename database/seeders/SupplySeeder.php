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
        DB::table('supply_inventories')->truncate(); // يفضل تنظيف جدول المخزون أيضاً
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 

        // 2. جلب جميع شركات التوريد (الـ 12 شركة)
        $companies = User::where('role', 'supply_company')->get();

        if ($companies->isEmpty()) {
            $this->command->error('No supply companies found to link products to!');
            return;
        }
        
        $data = [
            'لحوم ومشاوي' => [
                ['name' => 'كباب بلدي 1كغ', 'price' => 12.00, 'stock' => 50, 'desc' => 'لحم خروف بلدي طازج مفروم مع البقدونس والبصل.', 'image' => 'كباب.webp'],
                ['name' => 'ريش غنم متبلة 1كغ', 'price' => 14.50, 'stock' => 30, 'desc' => 'ريش غنم بلدي متبلة بالخلطة الخاصة للجريل.', 'image' => 'ريش.webp'],
                ['name' => 'دجاج كامل متبل 1.2كغ', 'price' => 6.50, 'stock' => 45, 'desc' => 'دجاج طازج متبل ببهارات الشواء التقليدية.', 'image' => 'دجاج.webp'],
            ],
            'خضار وفواكه' => [
                ['name' => 'سلة خضار مشكلة (كبيرة)', 'price' => 8.00, 'stock' => 100, 'desc' => 'تتضمن بندورة، خيار، خس، فلفل وبصل.', 'image' => 'خضار.webp'],
                ['name' => 'بطيخ أحمر (حبة كبيرة)', 'price' => 4.50, 'stock' => 60, 'desc' => 'بطيخ حلو ومبرد من مزارع الغور.', 'image' => 'بطيخ.webp'],
                ['name' => 'صحن فواكه مقطع جاهز', 'price' => 5.00, 'stock' => 25, 'desc' => 'تشكيلة من الفواكه الموسمية المقطعة.', 'image' => 'فواكه.webp'],
            ],
            'مقبلات وسلطات' => [
                ['name' => 'صحن حمص نخب أول', 'price' => 2.50, 'stock' => 40, 'desc' => 'حمص بالطحينة والزيت النباتي.', 'image' => 'حمص.webp'],
                ['name' => 'متبل باذنجان مشوي', 'price' => 2.50, 'stock' => 40, 'desc' => 'باذنجان مشوي على الحطب مع الطحينة.', 'image' => 'باذنجان.webp'],
                ['name' => 'صحن تبولة كبير', 'price' => 3.50, 'stock' => 30, 'desc' => 'تبولة لبنانية أصلية بالبرغل والليمون.', 'image' => 'تبولة.webp'],
            ],
            'أدوات ومعدات شواء' => [
                ['name' => 'فحم نخب أول 5كغ', 'price' => 7.00, 'stock' => 200, 'desc' => 'فحم سنديان سريع الاشتعال ويدوم طويلاً.', 'image' => 'فحم.png'],
                ['name' => 'مجموعة أسياخ ستانلس', 'price' => 5.00, 'stock' => 15, 'desc' => 'طقم 10 أسياخ عريضة للشواء.', 'image' => 'أسياخ.webp'],
                ['name' => 'شبكة شواء مزدوجة', 'price' => 6.50, 'stock' => 20, 'desc' => 'شبكة ستانلس للجريل واللحوم.', 'image' => 'شبكة.webp'],
                ['name' => 'منفاخ هواء يدوي', 'price' => 3.00, 'stock' => 50, 'desc' => 'منفاخ قوي لتسريع اشتعال الفحم.', 'image' => 'منفاخ.webp'],
            ],
            'تسالي وحلويات' => [
                ['name' => 'علبة بقلاوة مشكلة 500غ', 'price' => 9.00, 'stock' => 40, 'desc' => 'بقلاوة بالسمن البلدي والفستق الحلبي.', 'image' => 'بقلاوة.webp'],
                ['name' => 'مكسرات مشكلة نخب أول', 'price' => 10.00, 'stock' => 100, 'desc' => 'تشكيلة محمصة طازجة (لوز، فستق، كاجو).', 'image' => 'مكسرات.webp'],
                ['name' => 'شيبس وتسالي عائلية', 'price' => 3.50, 'stock' => 150, 'desc' => 'مجموعة سناك متنوعة للجلسات.', 'image' => 'شيبس.webp'],
                ['name' => 'كنافة نابلسية (طبق صغير)', 'price' => 12.00, 'stock' => 20, 'desc' => 'كنافة ساخنة بالجبنة والقطر.', 'image' => 'كنافة.webp'],
            ],
            'مشروبات وثلج' => [
                ['name' => 'باكيت ثلج نقي 2كغ', 'price' => 1.50, 'stock' => 300, 'desc' => 'مكعبات ثلج نقية مبردة.', 'image' => 'ثلج.webp'],
                ['name' => 'بيبسي/كولا 2.25 لتر', 'price' => 1.75, 'stock' => 200, 'desc' => 'عبوة عائلية غازية.', 'image' => 'مشروبات.webp'],
                ['name' => 'عصير برتقال طبيعي 1 لتر', 'price' => 3.00, 'stock' => 50, 'desc' => 'عصير طازج بدون سكر مضاف.', 'image' => 'عصير.webp'],
                ['name' => 'مجموعة عصائر مشكلة 6 حبات', 'price' => 5.00, 'stock' => 60, 'desc' => 'تشكيلة من العصائر المنوعة للرحلات.', 'image' => 'عصائر.webp'],
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

                // ب) المرور على جميع الشركات الـ 12 وربط هذا المنتج بها
                $inventories = [];
                foreach ($companies as $company) {
                    $inventories[] = [
                        'supply_id'  => $supply->id,     // ID المنتج الذي أنشأناه للتو
                        'company_id' => $company->id,    // ID الشركة الحالية في اللوب
                        'stock'      => $p['stock'],     // كمية المخزون (أو يمكنك جعلها rand() لتختلف بين الشركات)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // ج) إدخال المخزون لكل الشركات دفعة واحدة (أفضل للأداء)
                DB::table('supply_inventories')->insert($inventories);
            }
        }

        $this->command->info("Supply Marketplace populated! " . Supply::count() . " unique products linked across {$companies->count()} branches.");
    }
}