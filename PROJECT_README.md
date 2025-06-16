# مشروع تطوير نظام المعلم متعدد الحلقات القرآنية

## نظرة عامة
هذا المشروع يهدف إلى تطوير النظام الحالي لإدارة المعلمين والحلقات القرآنية ليدعم إمكانية عمل المعلم الواحد في أكثر من حلقة قرآنية مع اختلاف أوقات الحلقات.

## المشكلة الحالية
النظام الحالي يدعم فقط علاقة **واحد إلى كثير** (One-to-Many) بين المعلم والحلقات القرآنية، مما يعني أن المعلم يمكن أن يكون مرتبطاً بحلقة واحدة فقط.

## الحل المقترح
تطوير النظام ليدعم علاقة **كثير إلى كثير** (Many-to-Many) بين المعلمين والحلقات القرآنية من خلال:

1. **إنشاء جدول pivot** جديد للتكليفات
2. **تحديث النماذج** لدعم العلاقات الجديدة
3. **تطوير واجهات إدارية** محدثة
4. **إضافة منطق عمل** للرواتب والتقييمات

## هيكل المشروع

```
├── teacher_multi_circles_development_plan.md    # خطة التطوير الرئيسية
├── task_tracking.md                            # ملف تتبع المهام
├── technical_specifications.md                 # المواصفات التقنية
├── PROJECT_README.md                           # هذا الملف
└── migrations/                                 # ملفات الهجرة (سيتم إنشاؤها)
    ├── create_teacher_circle_assignments_table.php
    ├── update_teachers_table.php
    └── migrate_existing_data.php
```

## الملفات الأساسية

### 📋 خطة التطوير
**الملف**: `teacher_multi_circles_development_plan.md`
- نظرة عامة على المشروع
- المراحل الرئيسية (6 مراحل)
- المخاطر المحتملة والحلول
- التسليمات المتوقعة

### 📊 تتبع المهام  
**الملف**: `task_tracking.md`
- حالة المشروع العامة
- تفصيل جميع المهام (31 مهمة)
- نسب الإنجاز لكل مرحلة
- سجل التحديثات اليومية

### 🔧 المواصفات التقنية
**الملف**: `technical_specifications.md`
- هيكل قاعدة البيانات التفصيلي
- كود النماذج والعلاقات
- واجهات Filament
- API endpoints
- اختبارات مطلوبة
- قواعد العمل

## المراحل الأساسية

| المرحلة | الوصف | المدة | الحالة |
|---------|--------|-------|---------|
| 1️⃣ | تصميم قاعدة البيانات | 3-4 أيام | ⏳ قيد التخطيط |
| 2️⃣ | تطوير النماذج والعلاقات | 2-3 أيام | ⏳ في الانتظار |
| 3️⃣ | تطوير الواجهات الإدارية | 4-5 أيام | ⏳ في الانتظار |
| 4️⃣ | تطوير منطق العمل | 3-4 أيام | ⏳ في الانتظار |
| 5️⃣ | تطوير API | 2-3 أيام | ⏳ في الانتظار |
| 6️⃣ | الاختبار والتشغيل | 2-3 أيام | ⏳ في الانتظار |

## التقنيات المستخدمة

### Backend
- **Laravel Framework** - إطار العمل الأساسي
- **Eloquent ORM** - للتعامل مع قاعدة البيانات
- **Laravel Migrations** - لإدارة هيكل قاعدة البيانات

### Admin Panel
- **Filament Admin Panel** - لوحة الإدارة
- **Filament Forms** - نماذج الإدخال
- **Filament Tables** - جداول البيانات
- **Filament Widgets** - عناصر لوحة المعلومات

### Database
- **MySQL** - قاعدة البيانات الأساسية
- **Foreign Keys** - للحفاظ على سلامة البيانات
- **Indexes** - لتحسين الأداء

### Testing
- **PHPUnit** - اختبارات الوحدة
- **Laravel Testing** - اختبارات التكامل

## البدء السريع

### 1. نسخ المشروع والإعداد
```bash
# نسخ احتياطي من قاعدة البيانات
php artisan backup:run

# إنشاء فرع جديد للتطوير
git checkout -b feature/teacher-multi-circles

# تحديث dependencies إذا لزم الأمر
composer install
```

### 2. قراءة الوثائق
اقرأ الملفات التالية بالترتيب:
1. `teacher_multi_circles_development_plan.md` - للفهم العام
2. `technical_specifications.md` - للتفاصيل التقنية  
3. `task_tracking.md` - لتتبع التقدم

### 3. بدء التطوير
ابدأ بالمرحلة الأولى كما هو موضح في `task_tracking.md`

## القواعد الأساسية

### قواعد التكليف
- ✅ المعلم يمكن أن يعمل في عدة حلقات
- ✅ المعلم يمكن أن يكون له أدوار مختلفة (أساسي، مساعد، بديل)
- ❌ لا يمكن للمعلم أن يكون معلماً أساسياً في أكثر من حلقة
- ❌ لا يمكن تعارض أوقات الحلقات للمعلم الواحد

### قواعد الراتب
- راتب المعلم = راتب أساسي × مجموع نسب الحلقات
- إجمالي النسب لا يجب أن يتجاوز 100%
- المعلم الأساسي يحصل على نسبة أعلى

### قواعد التقييم
- تقييم منفصل لكل حلقة
- التقييم الإجمالي = متوسط مرجح

## الهيكل المستهدف لقاعدة البيانات

### الجدول الجديد: teacher_circle_assignments
```sql
- id (Primary Key)
- teacher_id (Foreign Key → teachers.id)
- quran_circle_id (Foreign Key → quran_circles.id)  
- role (enum: primary, assistant, substitute)
- is_active (boolean)
- start_date (date)
- end_date (date, nullable)
- salary_percentage (decimal)
- weekly_hours (integer)
- notes (text)
- timestamps
```

### التحديثات على الجداول الموجودة
- **teachers**: إزالة `quran_circle_id`، إضافة `max_circles`
- **quran_circles**: إضافة `max_teachers`, `requires_primary_teacher`

## خارطة طريق التنفيذ

### الأسبوع الأول
- [ ] تصميم وإنشاء قاعدة البيانات الجديدة
- [ ] تطوير النماذج والعلاقات
- [ ] بداية تطوير الواجهات

### الأسبوع الثاني  
- [ ] استكمال الواجهات الإدارية
- [ ] تطوير منطق العمل (رواتب، تقييمات)
- [ ] تطوير API endpoints

### الأسبوع الثالث
- [ ] الاختبار الشامل
- [ ] إصلاح الأخطاء
- [ ] النشر والتشغيل

## معايير الجودة

### اختبارات مطلوبة
- ✅ Unit Tests للنماذج
- ✅ Feature Tests للواجهات
- ✅ Integration Tests للـ API
- ✅ Performance Tests للاستعلامات

### متطلبات الأداء
- الاستعلامات لا تتجاوز 100ms
- دعم الـ Eager Loading لتجنب N+1 Problem
- استخدام Indexes مناسبة

### أمان البيانات
- التحقق من صحة البيانات (Validation)
- استخدام Eloquent Relationships بشكل آمن
- حماية من SQL Injection

## المساهمة في المشروع

### قواعد الكود
- اتباع PSR-12 coding standards
- كتابة تعليقات بالعربية للوضوح
- استخدام أسماء متغيرات واضحة

### Git Workflow
```bash
# إنشاء فرع للمهمة
git checkout -b task/database-design

# تنفيذ المهمة وcommit
git add .
git commit -m "feat: add teacher_circle_assignments table"

# دمج في الفرع الرئيسي
git checkout feature/teacher-multi-circles
git merge task/database-design
```

### تحديث ملف التتبع
بعد إكمال كل مهمة، قم بتحديث `task_tracking.md`:
- غير حالة المهمة من ⏳ إلى ✅
- أضف ملاحظات إذا لزم الأمر
- حدث نسبة الإنجاز

## جهات الاتصال والدعم

### للاستفسارات التقنية
- راجع `technical_specifications.md`
- تحقق من `task_tracking.md` للحالة الحالية

### للتقارير والمشاكل
- سجل المشاكل في ملف التتبع
- اتبع قالب التقرير المحدد

## الموارد المفيدة

### وثائق Laravel
- [Laravel Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Migrations](https://laravel.com/docs/migrations)

### وثائق Filament
- [Filament Forms](https://filamentphp.com/docs/forms)
- [Filament Tables](https://filamentphp.com/docs/tables)
- [Filament Resources](https://filamentphp.com/docs/admin/resources)

---

## ترخيص المشروع
هذا المشروع خاص بنظام إدارة المساجد والحلقات القرآنية.

## سجل التحديثات

### الإصدار 1.0.0 - 8 يونيو 2025
- ✅ إنشاء خطة التطوير الشاملة
- ✅ إعداد ملف تتبع المهام التفصيلي
- ✅ كتابة المواصفات التقنية
- ✅ إنشاء وثائق المشروع

### المقبل - الإصدار 1.1.0
- ⏳ تنفيذ المرحلة الأولى من التطوير
- ⏳ إنشاء قاعدة البيانات الجديدة
