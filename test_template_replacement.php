<?php

// اختبار بسيط لاستبدال المتغيرات في القالب

$content = "أهلا بالأستاذ {teacher_name} 📚

تم إضافتك بنجاح في منصة غرب لإدارة حلقات القرآن الكريم
المسجد: {mosque_name}

بارك الله فيك وجعل عملك في خدمة كتاب الله في ميزان حسناتك 🤲
يمكنك الدخول من هنا
appgarb.vercel.app
برقم الهوية الخاصة بك وكلمة المرور  التالية :
{password}";

$variables = [
    'teacher_name' => 'أحمد محمد الاختبار',
    'mosque_name' => 'جامع هيلة الحربي',
    'password' => '190311'
];

echo "القالب الأصلي:\n";
echo $content . "\n\n";

echo "المتغيرات:\n";
print_r($variables);

echo "\nبعد الاستبدال:\n";
foreach ($variables as $key => $value) {
    $content = str_replace("{{$key}}", $value, $content);
}

echo $content . "\n";
