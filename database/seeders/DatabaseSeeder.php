<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\ContactMessage;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\FarmBlockedDate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء عملية بناء قواعد البيانات الشاملة للمزرعة (Omni-Seeding)...');

        $this->call([
            // 1. الأساس: المستخدمين، الملاك، والشركات، والسائقين
            UserSeeder::class,

            // 2. المزارع الأساسية والمستنسخة
            FarmSeeder::class,

            // 2.1 الألبومات والصور
            FarmImageSeeder::class,

            // 3. المنتجات والكتالوج والمخزون
            SupplySeeder::class,

            // 4. الحجوزات والمواصلات (تم دمج المواصلات هنا للترابط)
            FarmBookingSeeder::class,

            // 5. طلبات التوريد بناءً على الحجوزات
            SupplyOrderSeeder::class,

            // 6. العمليات المالية وتوزيع الأرباح والعمولات
            FinancialTransactionSeeder::class,
        ]);

        $this->command->info('جاري توليد التفاعلات الجانبية (تقييمات، مفضلات، إشعارات، دعم فني)...');

        // جلب الداتا الأساسية
        $users = User::where('role', 'user')->get();
        $farms = Farm::all();

        // ==========================================
        // 1. تقييمات واقعية باللغة العربية (Reviews)
        // ==========================================
        if ($users->isNotEmpty() && $farms->isNotEmpty()) {
            $reviewTexts = [
                'مزرعة بتجنن صراحة، المسبح نظيف جداً والتعامل راقي.',
                'المكان واسع ومناسب للعائلات، بس يا ريت يوفرو منقل شواء أكبر.',
                'تجربة رائعة، الهدوء والمناظر الخلابة بتسوى كل قرش.',
                'ممتازة جداً والمواصلات اللي وفروها كانت عالدقيقة.',
                'المزرعة حلوة بس كان في شوية نقص بأدوات المطبخ.',
                'فخامة لا توصف! قضينا ويك إند خرافي شكراً لكم.',
                'المكان بيعقد، بس سعر الشفت المسائي شوي غالي.',
                'أنصح فيها بقوة، الأجواء خيالية خصوصاً بالليل.'
            ];

            $reviewsData = [];
            foreach ($farms as $farm) {
                // من 3 إلى 8 تقييمات لكل مزرعة
                $numReviews = min(rand(3, 8), $users->count());

                // اختيار مستخدمين فريدين لهذه المزرعة لتجنب مشكلة Unique Constraint
                $uniqueUsers = $users->random($numReviews);

                foreach ($uniqueUsers as $user) {
                    $reviewsData[] = [
                        'user_id' => $user->id,
                        'reviewable_id' => $farm->id,
                        'reviewable_type' => Farm::class,
                        'rating' => rand(3, 5), // تقييمات إيجابية غالباً
                        'comment' => $reviewTexts[array_rand($reviewTexts)],
                        'created_at' => now()->subDays(rand(1, 100)),
                        'updated_at' => now(),
                    ];
                }
            }

            // استخدام insertOrIgnore لتخطي أي محاولات إدخال متكررة عن طريق الخطأ
            $chunks = array_chunk($reviewsData, 500);
            foreach ($chunks as $chunk) {
                DB::table('reviews')->insertOrIgnore($chunk);
            }
        }

        // ==========================================
        // 2. المفضلات (Favorites)
        // ==========================================
        if ($users->count() > 10 && $farms->count() > 10) {
            $favoritesData = [];
            $sampleUsers = $users->random(50); // 50 يوزر عشوائي
            foreach ($sampleUsers as $u) {
                $favFarms = $farms->random(rand(2, 6)); // كل يوزر بحب 2 لـ 6 مزارع
                foreach ($favFarms as $f) {
                    $favoritesData[] = [
                        'user_id' => $u->id,
                        'farm_id' => $f->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now(),
                    ];
                }
            }
            // استخدام insertOrIgnore هنا أيضاً كإجراء احترازي
            DB::table('favorites')->insertOrIgnore($favoritesData);
        }

        // ==========================================
        // 3. رسائل تواصل الدعم الفني (Contact Us)
        // ==========================================
        $contactData = [];
        $subjects = ['استفسار عن حجز', 'مشكلة بالدفع', 'اقتراح تطوير', 'شكوى بخصوص مزرعة', 'طلب الانضمام كشريك'];
        for ($i = 0; $i < 35; $i++) {
            $contactData[] = [
                'name' => 'زائر ' . rand(1, 100),
                'email' => 'visitor' . rand(1, 100) . '@example.com',
                'subject' => $subjects[array_rand($subjects)],
                'message' => 'مرحباً، أود الاستفسار عن خدماتكم وكيف يمكنني الاستفادة من العروض المتاحة. شكراً.',
                'status' => (rand(1, 100) > 70) ? 'resolved' : 'pending',
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now(),
            ];
        }
        // تصحيح: استخدام اسم الجدول contact_us كما في قاعدة البيانات بدلاً من contact_messages
        DB::table('contact_us')->insert($contactData);

        // ==========================================
        // 4. تواريخ محجوزة يدوياً (Blocked Dates)
        // ==========================================
        $blockedData = [];
        $premiumFarms = $farms->random(20);
        foreach ($premiumFarms as $farm) {
            for ($i = 1; $i <= 3; $i++) {
                $blockedData[] = [
                    'farm_id' => $farm->id,
                    'date' => now()->addDays($i * rand(5, 10))->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('farm_blocked_dates')->insertOrIgnore($blockedData);

        // ==========================================
        // 5. إشعارات النظام (Notifications)
        // ==========================================
        $allUsers = User::take(100)->get();
        $notificationData = [];

        $notifTypes = [
            'App\Notifications\BookingConfirmed',
            'App\Notifications\PaymentReceived',
            'App\Notifications\OrderDispatched',
            'App\Notifications\NewReviewPosted',
        ];

        $notifMessages = [
            ['title' => 'تأكيد الحجز', 'message' => 'تم تأكيد حجزك بنجاح للمزرعة المختارة.'],
            ['title' => 'تم استلام الدفعة', 'message' => 'لقد تم تسجيل مبلغ مالي جديد في محفظتك.'],
            ['title' => 'طلب قيد التوصيل', 'message' => 'يرجى العلم أن طلب التوريد الخاص بك قيد التوصيل الآن.'],
            ['title' => 'تقييم جديد', 'message' => 'قام أحد العملاء بإضافة تقييم جديد لمزرعتك.'],
        ];

        foreach ($allUsers as $account) {
            for ($i = 0; $i < 3; $i++) {
                $index = array_rand($notifTypes);
                $notificationData[] = [
                    'id' => Str::uuid()->toString(),
                    'type' => $notifTypes[$index],
                    'notifiable_type' => User::class,
                    'notifiable_id' => $account->id,
                    'data' => json_encode($notifMessages[$index]),
                    'read_at' => rand(0, 1) ? now() : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ];
            }
        }

        $chunks = array_chunk($notificationData, 500);
        foreach ($chunks as $chunk) {
            DB::table('notifications')->insert($chunk);
        }

        $this->command->info('OMNI-SEEDER COMPLETE 🟢 - تم تفجير قاعدة البيانات بنجاح تام! 100% تغطية واقعية.');
    }
}
