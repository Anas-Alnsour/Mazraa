<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;
use App\Models\User;

class SupplySeeder extends Seeder
{
    public function run()
    {
        $company = User::where('role', 'supply_company')->first();

        if (!$company) {
            $this->command->error('No supply company found to link products to!');
            return;
        }

        $data = [
            'لحوم ومشاوي' => [
                ['name' => 'كباب بلدي 1كغ', 'price' => 12.00, 'stock' => 50, 'desc' => 'لحم خروف بلدي طازج مفروم مع البقدونس والبصل.', 'image' => 'https://images.unsplash.com/photo-1529692236671-f1f6e9481bfa?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'ريش غنم متبلة 1كغ', 'price' => 14.50, 'stock' => 30, 'desc' => 'ريش غنم بلدي متبلة بالخلطة الخاصة للجريل.', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'دجاج كامل متبل 1.2كغ', 'price' => 6.50, 'stock' => 45, 'desc' => 'دجاج طازج متبل ببهارات الشواء التقليدية.', 'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=800&q=80'],
            ],
            'خضار وفواكه' => [
                ['name' => 'سلة خضار مشكلة (كبيرة)', 'price' => 8.00, 'stock' => 100, 'desc' => 'تتضمن بندورة، خيار، خس، فلفل وبصل.', 'image' => 'https://images.unsplash.com/photo-1540333674681-3069177197ae?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'بطيخ أحمر (حبة كبيرة)', 'price' => 4.50, 'stock' => 60, 'desc' => 'بطيخ حلو ومبرد من مزارع الغور.', 'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'صحن فواكه مقطع جاهز', 'price' => 5.00, 'stock' => 25, 'desc' => 'تشكيلة من الفواكه الموسمية المقطعة.', 'image' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&w=800&q=80'],
            ],
            'مقبلات وسلطات' => [
                ['name' => 'صحن حمص نخب أول', 'price' => 2.50, 'stock' => 40, 'desc' => 'حمص بالطحينة والزيت النباتي.', 'image' => 'https://images.unsplash.com/photo-1577906030551-84523c6ac11d?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'متبل باذنجان مشوي', 'price' => 2.50, 'stock' => 40, 'desc' => 'باذنجان مشوي على الحطب مع الطحينة.', 'image' => 'https://images.unsplash.com/photo-1574071318508-1cdbad80ad38?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'صحن تبولة كبير', 'price' => 3.50, 'stock' => 30, 'desc' => 'تبولة لبنانية أصلية بالبرغل والليمون.', 'image' => 'https://images.unsplash.com/photo-1547496502-affa22d38842?auto=format&fit=crop&w=800&q=80'],
            ],
            'أدوات ومعدات شواء' => [
                ['name' => 'فحم نخب أول 5كغ', 'price' => 7.00, 'stock' => 200, 'desc' => 'فحم سنديان سريع الاشتعال ويدوم طويلاً.', 'image' => 'https://images.unsplash.com/photo-1524338198850-8a2ff63aaceb?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'مجموعة أسياخ ستانلس', 'price' => 5.00, 'stock' => 15, 'desc' => 'طقم 10 أسياخ عريضة للشواء.', 'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'شبكة شواء مزدوجة', 'price' => 6.50, 'stock' => 20, 'desc' => 'شبكة ستانلس للجريل واللحوم.', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'منفاخ هواء يدوي', 'price' => 3.00, 'stock' => 50, 'desc' => 'منفاخ قوي لتسريع اشتعال الفحم.', 'image' => 'https://images.unsplash.com/photo-1618414349193-4a008c58f000?auto=format&fit=crop&w=800&q=80'],
            ],
            'تسالي وحلويات' => [
                ['name' => 'علبة بقلاوة مشكلة 500غ', 'price' => 9.00, 'stock' => 40, 'desc' => 'بقلاوة بالسمن البلدي والفستق الحلبي.', 'image' => 'https://images.unsplash.com/photo-1519676867240-f031ea49d06b?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'مكسرات مشكلة نخب أول', 'price' => 10.00, 'stock' => 100, 'desc' => 'تشكيلة محمصة طازجة (لوز، فستق، كاجو).', 'image' => 'https://images.unsplash.com/photo-1536591375315-1a8f94254443?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'شيبس وتسالي عائلية', 'price' => 3.50, 'stock' => 150, 'desc' => 'مجموعة سناك متنوعة للجلسات.', 'image' => 'https://images.unsplash.com/photo-1566478431379-79870be8610d?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'كنافة نابلسية (طبق صغير)', 'price' => 12.00, 'stock' => 20, 'desc' => 'كنافة ساخنة بالجبنة والقطر.', 'image' => 'https://images.unsplash.com/photo-1517433670267-08bbd4be890f?auto=format&fit=crop&w=800&q=80'],
            ],
            'مشروبات وثلج' => [
                ['name' => 'باكيت ثلج نقي 2كغ', 'price' => 1.50, 'stock' => 300, 'desc' => 'مكعبات ثلج نقية مبردة.', 'image' => 'https://images.unsplash.com/photo-1582298538104-fe2e74c27f59?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'بيبسي/كولا 2.25 لتر', 'price' => 1.75, 'stock' => 200, 'desc' => 'عبوة عائلية غازية.', 'image' => 'https://images.unsplash.com/photo-1581006852262-e4307cf6283a?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'عصير برتقال طبيعي 1 لتر', 'price' => 3.00, 'stock' => 50, 'desc' => 'عصير طازج بدون سكر مضاف.', 'image' => 'https://images.unsplash.com/photo-1544145945-f904253db0ad?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'مجموعة عصائر مشكلة 6 حبات', 'price' => 5.00, 'stock' => 60, 'desc' => 'تشكيلة من العصائر المنوعة للرحلات.', 'image' => 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?auto=format&fit=crop&w=800&q=80'],
            ],
            'إضافات فاخرة' => [
                ['name' => 'شيشة فاخرة للإيجار', 'price' => 15.00, 'stock' => 10, 'desc' => 'شيشة كاملة مع الفحم والمعسل والتوصيل.', 'image' => 'https://images.unsplash.com/photo-1527030280862-64139fba04ca?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'سماعات بلوتوث ضخمة', 'price' => 20.00, 'stock' => 5, 'desc' => 'سماعات حفلات للاستخدام اليومي.', 'image' => 'https://images.unsplash.com/photo-1589003077984-894e133dabab?auto=format&fit=crop&w=800&q=80'],
            ],
            'مستلزمات سفرة ونظافة' => [
                ['name' => 'طقم سفرة كرتون (50 شخص)', 'price' => 6.00, 'stock' => 80, 'desc' => 'صحون، كاسات، وشوك كرتون مقوى.', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'مناديل ورقية ناعمة 10 حبات', 'price' => 4.00, 'stock' => 100, 'desc' => 'بكيج مناديل عائلي.', 'image' => 'https://images.unsplash.com/photo-1583907659441-add9707432c2?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'سفرة نايلون (رول كبير)', 'price' => 2.00, 'stock' => 120, 'desc' => 'مفرش مائدة بلاستيك عالي الجودة.', 'image' => 'https://images.unsplash.com/photo-1595131838586-58d009ef3879?auto=format&fit=crop&w=800&q=80'],
            ],
            'ألعاب وتسلية' => [
                ['name' => 'مجموعة ألعاب ورقية (شدة)', 'price' => 2.00, 'stock' => 500, 'desc' => 'ورق لعب نخب أول.', 'image' => 'https://images.unsplash.com/photo-1610819013703-2bc67738a167?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'كرات قدم وطائرة', 'price' => 10.00, 'stock' => 15, 'desc' => 'كرات رياضية للمسابح والملاعب.', 'image' => 'https://images.unsplash.com/photo-1543351611-58f69d7c1781?auto=format&fit=crop&w=800&q=80'],
                ['name' => 'طاولة زهر خشبية', 'price' => 15.00, 'stock' => 10, 'desc' => 'طاولة زهر يدوية الصنع.', 'image' => 'https://images.unsplash.com/photo-1529148482759-b35b25c5f217?auto=format&fit=crop&w=800&q=80'],
            ],
        ];

        foreach ($data as $category => $products) {
            foreach ($products as $p) {
                Supply::create([
                    'company_id'  => $company->id,
                    'name'        => $p['name'],
                    'category'    => $category,
                    'price'       => $p['price'],
                    'stock'       => $p['stock'],
                    'description' => $p['desc'],
                    'image'       => $p['image'],
                ]);
            }
        }

        $this->command->info('Supply Marketplace populated with Arabic data successfully!');
    }
}
