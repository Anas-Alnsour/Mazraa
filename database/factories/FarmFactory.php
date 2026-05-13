<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Farm;
use App\Models\User;
use App\Models\FarmImage;

class FarmFactory extends Factory
{
    protected $model = Farm::class;

    public function definition(): array
    {
        $names = [
            "فيلا رويال الأغوار", "مزرعة السنديان - جرش", "شاليهات البحر الميت اللؤلؤية", 
            "منتجع الوادي - طريق المطار", "مزرعة الزيتون - إربيد", "فيلا موناكو - البحر الميت", 
            "مزرعة الريف - الزرقاء", "شاليه بانوراما المطل", "فيلا دبي - الشونة الجنوبية",
            "مزرعة النخيل - الغور", "شاليه لافندر - السلط", "منتجع الطبيعة - عجلون",
            "فيلا الروز - دابوق", "مزرعة الياسمين - طريق المطار", "شاليه تلال البحر",
            "منتجع لؤلؤة الشمال", "مزرعة العائلة - بيرين", "فيلا مودرن - ناعور",
            "شاليه النجوم - البحر الميت", "مزرعة الأصالة - المفرق", "فيلا القرية - الفحيص",
            "شاليه تاهيتي - الأغوار", "منتجع الهدوء - جرش", "مزرعة السعادة - إربيد",
            "فيلا الفخامة - عمان", "شاليه الريم - طريق المطار", "مزرعة الأجداد - الغور",
            "فيلا السلطان - البحر الميت", "شاليه المرجان - الشونة", "منتجع الصنوبر - عجلون",
            "مزرعة الوادي الأخضر", "فيلا تلال عمان", "شاليه الشلال - جرش",
            "مزرعة الزيتونة المباركة", "فيلا الهدوء - ناعور", "شاليه الفيروز - البحر الميت",
            "منتجع الشمس - الأغوار", "مزرعة البركة - طريق المطار", "فيلا جاردينيا - دابوق",
            "شاليه النسيم - السلط"
        ];
        
        $locations = ["طريق المطار, عمان", "جرش, الأردن", "الأغوار الوسطى, الأردن", "البحر الميت, الأردن", "سوف, جرش", "بيرين, الزرقاء", "منطقة التركمان, إربيد", "ناعور, عمان", "الفحيص, الأردن", "عجلون, الأردن", "دابوق, عمان"];
        $descriptions = [
            "مزرعة فاخرة تحتوي على مسبح خارجي مدفأ، منطقة شواء واسعة، ملعب كرة قدم، وإطلالة خلابة.", 
            "شاليه هادئ ومناسب للعائلات، يتميز بالخصوصية التامة ونظافة المرافق.", 
            "فيلا مودرن بتصميم عصري، تحتوي على مسبح داخلي، تراسات خارجية، ومنطقة ألعاب للأطفال.", 
            "مزرعة ريفية هادئة تحيط بها أشجار الزيتون، توفر أجواء استرخاء مثالية بعيداً عن ضجيج المدينة.",
            "المكان المثالي للتجمعات العائلية الكبيرة، مجهز بأحدث وسائل الراحة والترفيه.",
            "إطلالة بانورامية رائعة على جبال عجلون، هواء نقي وجو هادئ جداً.",
            "مساحات خضراء شاسعة ومرافق متنوعة تناسب جميع الأعمار."
        ];

        // Random luxurious villa image from a large pool
        $imageNum = $this->faker->unique()->numberBetween(1, 100);
        $imageUrl = "https://images.unsplash.com/photo-" . (1580587771520 + $imageNum) . "?auto=format&fit=crop&w=1200&q=80";
        // Fallback for known good luxury photos
        $luxuryImages = [
            'https://images.unsplash.com/photo-1580587771525-78b9dba3b914',
            'https://images.unsplash.com/photo-1613977257363-707ba9348227',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9',
            'https://images.unsplash.com/photo-1564013799919-ab600027ffc6'
        ];
        
        $mainImage = $this->faker->boolean(20) ? $this->faker->randomElement($luxuryImages) . "?auto=format&fit=crop&w=1200&q=80" : $imageUrl;

        return [
            'name' => $this->faker->randomElement($names),
            'location' => $this->faker->randomElement($locations),
            'description' => $this->faker->randomElement($descriptions),
            'capacity' => $this->faker->numberBetween(5, 100),
            'price_per_morning_shift' => $this->faker->numberBetween(50, 250),
            'price_per_evening_shift' => $this->faker->numberBetween(60, 300),
            'price_per_full_day' => $this->faker->numberBetween(100, 600),
            'governorate' => $this->faker->randomElement(['Amman', 'Jerash', 'Irbid', 'Zarqa', 'Dead Sea', 'Salt']),
            'rating' => $this->faker->randomFloat(1, 3.5, 5),
            'main_image' => $mainImage,
            'owner_id' => User::where('role', 'farm_owner')->first()->id ?? User::factory()->create(['role' => 'farm_owner'])->id,
            'latitude' => $this->faker->latitude(29.0, 33.0),
            'longitude' => $this->faker->longitude(35.0, 39.0),
            'commission_rate' => 10.0,
            'is_approved' => $this->faker->boolean(90),
            'status' => 'active',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Farm $farm) {
            // ملاحظة: تأكد إنه عندك FarmImageFactory عشان هاد الكود يشتغل
            FarmImage::factory(4)->create([
                'farm_id' => $farm->id,
            ]);
        });
    }
}
