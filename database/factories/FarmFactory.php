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
        $names = ["فيلا رويال الأغوار", "مزرعة السنديان - جرش", "شاليهات البحر الميت اللؤلؤية", "منتجع الوادي - طريق المطار", "مزرعة الزيتون - إربيد", "فيلا موناكو - البحر الميت", "مزرعة الريف - الزرقاء", "شاليه بانوراما المطل"];
        $locations = ["طريق المطار, عمان", "جرش, الأردن", "الأغوار الوسطى, الأردن", "البحر الميت, الأردن", "سوف, جرش", "بيرين, الزرقاء", "منطقة التركمان, إربيد"];
        $descriptions = ["مزرعة فاخرة تحتوي على مسبح خارجي مدفأ، منطقة شواء واسعة، ملعب كرة قدم، وإطلالة خلابة.", "شاليه هادئ ومناسب للعائلات، يتميز بالخصوصية التامة ونظافة المرافق.", "فيلا مودرن بتصميم عصري، تحتوي على مسبح داخلي، تراسات خارجية، ومنطقة ألعاب للأطفال.", "مزرعة ريفية هادئة تحيط بها أشجار الزيتون، توفر أجواء استرخاء مثالية بعيداً عن ضجيج المدينة."];
        $images = [
            'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80'
        ];

        return [
            'name' => $this->faker->randomElement($names),
            'location' => $this->faker->randomElement($locations),
            'description' => $this->faker->randomElement($descriptions),
            'capacity' => $this->faker->numberBetween(5, 50),
            'price_per_morning_shift' => $this->faker->numberBetween(50, 150),
            'price_per_evening_shift' => $this->faker->numberBetween(60, 200),
            'price_per_full_day' => $this->faker->numberBetween(100, 300),
            'governorate' => $this->faker->randomElement(['Amman', 'Jerash', 'Irbid', 'Zarqa', 'Dead Sea', 'Balqa']),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'main_image' => $this->faker->randomElement($images),
            'owner_id' => User::where('role', 'farm_owner')->first()->id ?? User::factory()->create(['role' => 'farm_owner'])->id,
            'latitude' => $this->faker->latitude(29.0, 33.0),
            'longitude' => $this->faker->longitude(35.0, 39.0),
            'commission_rate' => $this->faker->randomElement([null, 5.0, 10.0, 15.0]),
            'is_approved' => true,
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
