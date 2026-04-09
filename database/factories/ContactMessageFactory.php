<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        $subjects = [
            'استفسار عن حجز مزرعة',
            'مشكلة في عملية الدفع',
            'اقتراح إضافة مميزات جديدة',
            'شكوى بشأن نظافة المرفق',
            'طلب شراكة تجارية',
            'استرجاع مبلغ تأمين',
            'تغيير موعد الحجز',
            'سؤال عن توفر الخدمات الإضافية'
        ];

        $messages = [
            'مرحباً، أود الاستفسار عن توفر المزرعة في عطلة نهاية الأسبوع القادمة وهل هناك خصم للمجموعات؟',
            'واجهتني مشكلة أثناء الدفع ببطاقة الفيزا، يرجى التحقق من حالة العملية.',
            'الموقع رائع جداً، أقترح إضافة خيار لتصفية المزارع حسب وجود مسبح للأطفال.',
            'للأسف كانت تجربة الحجز الأخيرة غير مرضية بسبب نقص بعض التجهيزات في المطبخ.',
            'نحن شركة تنظيم رحلات ونود التعاون معكم لإدراج مزارعنا ضمن منصتكم.',
            'متى يتم إعادة مبلغ التأمين بعد انتهاء مدة الإقامة؟ لقد مضى يومان ولم يصلني شيء.',
            'هل يمكنني تعديل تاريخ الحجز الخاص بي دون دفع رسوم إضافية؟',
            'هل المزرعة مجهزة للتدفئة في فصل الشتاء؟ وهل تتوفر حطب للنار؟'
        ];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'subject' => $this->faker->randomElement($subjects),
            'message' => $this->faker->randomElement($messages),
            'status' => $this->faker->randomElement(['new', 'pending', 'in_progress', 'resolved']),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
