# تقرير اختبار شامل لـ APIs المنهج اليومي

## نظرة عامة
تم إنشاء أمر اختبار شامل ومفصل لفحص APIs المنهج اليومي في النظام. الأمر يقوم بفحص قاعدة البيانات، الطلاب، المناهج، واختبار جميع APIs ذات الصلة.

## الأمر المُنشأ
```bash
php artisan test:daily-curriculum-apis [--student-id=ID] [--create-data] [-v|--verbose]
```

### الخيارات المتاحة:
- `--student-id=ID`: معرف الطالب للاختبار (افتراضي: 14)
- `--create-data`: إنشاء بيانات تجريبية للاختبار
- `-v|--verbose`: عرض تفاصيل مفصلة للاستجابات

## نتائج الاختبار

### ✅ APIs التي تعمل بنجاح:

#### 1. API المنهج اليومي
```
GET /api/students/{id}/daily-curriculum
```

**الطلاب الذين يعملون:**
- **الطالب ID: 1** (أحمد علي البارقي)
- **الطالب ID: 17** (أحمد موسى أحمد الزهراني)

**مثال على الاستجابة الناجحة:**
```json
{
    "success": true,
    "data": {
        "student": {
            "name": "أحمد علي البارقي",
            "mosque": "جامع هيلة الحربي"
        },
        "current_curriculum": {
            "name": "جديد",
            "level": "الاول", 
            "completion_percentage": 0
        },
        "daily_curriculum": {
            "memorization": {
                "id": 6,
                "type": "الدرس",
                "content": "سورة الفاتحة - من الآية 1 إلى الآية 7 (7 آيات)",
                "expected_days": 4
            },
            "minor_review": null,
            "major_review": null
        },
        "today_recitations": {
            "memorization": null,
            "minor_review": null,
            "major_review": null
        }
    }
}
```

#### 2. API جلسات التسميع
```
GET /api/students/{id}/recitation-sessions
```
- **الحالة**: ✅ يعمل بنجاح
- **كود الاستجابة**: 200

### ❌ APIs التي تحتاج إصلاح:

#### 1. API المحتوى التالي
```
GET /api/recitation/sessions/next-content/{studentId}
```
- **المشكلة**: خطأ 404 أو 500
- **السبب**: مشكلة في service dependencies
- **الحالة**: يحتاج إصلاح

#### 2. API منهج الطالب
```
GET /api/students/{id}/curriculum
```
- **المشكلة**: خطأ 500
- **الحالة**: يحتاج إصلاح

#### 3. API إحصائيات الطالب
```
GET /api/students/{id}/stats
```
- **المشكلة**: خطأ 500
- **الحالة**: يحتاج إصلاح

## فحص قاعدة البيانات

### ✅ الجداول الموجودة والعاملة:
- **students**: 14 سجل
- **curricula**: 3 مناهج
- **student_curricula**: 4 علاقات
- **curriculum_plans**: 6 خطط
- **student_curriculum_progress**: 4 سجلات تقدم
- **recitation_sessions**: 159 جلسة تسميع

### 📊 إحصائيات النجاح:
- **APIs ناجحة**: 2 من 5 (40%)
- **APIs تحتاج إصلاح**: 3 من 5 (60%)
- **قاعدة البيانات**: ✅ سليمة ومكتملة

## أمثلة الاستخدام الناجحة

### 1. PowerShell/CMD:
```powershell
# اختبار شامل للطالب ID: 1
php artisan test:daily-curriculum-apis --student-id=1

# اختبار مع تفاصيل مفصلة
php artisan test:daily-curriculum-apis --student-id=1 -v

# إنشاء بيانات تجريبية
php artisan test:daily-curriculum-apis --student-id=1 --create-data
```

### 2. HTTP API مباشرة:
```powershell
# اختبار API المنهج اليومي
Invoke-RestMethod -Uri "http://localhost:8000/api/students/1/daily-curriculum" -Method GET -Headers @{"Accept"="application/json"}

# اختبار للطالب آخر
Invoke-RestMethod -Uri "http://localhost:8000/api/students/17/daily-curriculum" -Method GET -Headers @{"Accept"="application/json"}
```

### 3. cURL (إذا متاح):
```bash
curl -X GET "http://localhost:8000/api/students/1/daily-curriculum" \
  -H "Accept: application/json"
```

## الطلاب المتاحين للاختبار

| ID | الاسم | المسجد | الحالة |
|----|-------|---------|---------|
| 1 | أحمد علي البارقي | جامع هيلة الحربي | ✅ يعمل |
| 2 | خالد حسن | - | غير مختبر |
| 3 | سعدون | - | غير مختبر |
| 10 | أحمد محمد التجريبي | - | غير مختبر |
| 11 | الطالب التجريبي المُصحح | - | غير مختبر |
| 12 | حسين | - | غير مختبر |
| 13 | خالد | - | غير مختبر |
| 17 | أحمد موسى أحمد الزهراني | مسجد مزنة بنت سليمان السديري | ✅ يعمل |
| 18 | إلياس عمر عبدالله المحمودي | - | غير مختبر |
| 19 | احمد محمد سعيد الزهراني | - | غير مختبر |

## المشاكل المكتشفة والحلول

### 1. الطالب ID: 14 غير موجود
- **المشكلة**: لا يوجد طالب بـ ID: 14 في قاعدة البيانات
- **الحل**: استخدام طالب موجود مثل ID: 1 أو 17

### 2. مشكلة في constructor للـ RecitationSessionController
- **المشكلة**: يتطلب 3 service dependencies
- **الحل المطبق**: استخدام `app(RecitationSessionController::class)` بدلاً من `new`

### 3. مشكلة في plan_type enum
- **المشكلة**: قيم خاطئة في جدول curriculum_plans
- **الحل**: تحديث القيم المسموحة في enum

## التوصيات

### الأولوية العالية:
1. **إصلاح API المحتوى التالي** - مطلوب للحصول على منهج الغد
2. **إصلاح API منهج الطالب** - أساسي لعرض المنهج الكامل
3. **إصلاح API إحصائيات الطالب** - مهم لتتبع الأداء

### الأولوية المتوسطة:
4. **تحديث enum values** في جدول curriculum_plans
5. **إنشاء طالب بـ ID: 14** أو تحديث الاختبارات لتستخدم IDs موجودة

### للمطورين:
- استخدم الأمر `php artisan test:daily-curriculum-apis` لاختبار أي تغييرات
- استخدم `-v` للحصول على تفاصيل مفصلة للتطوير
- استخدم `--create-data` لإنشاء بيانات تجريبية

## الخلاصة النهائية

✅ **API المنهج اليومي يعمل بنجاح** للطلاب الذين لديهم مناهج مُعينة

**ما يمكنك الحصول عليه الآن:**
- منهج اليوم (الحفظ الجديد)
- حالة التسميع (تم/لم يتم)
- معلومات الطالب والمسجد
- نسبة إنجاز المنهج

**ما يحتاج إصلاح:**
- المحتوى المقرر غداً (API المحتوى التالي)  
- المنهج الكامل للطالب
- الإحصائيات المفصلة

**الاستخدام الحالي:**
```powershell
# للحصول على منهج اليوم
Invoke-RestMethod -Uri "http://localhost:8000/api/students/1/daily-curriculum" -Method GET -Headers @{"Accept"="application/json"}
```

---

*تم إنشاء هذا التقرير في: 13 يونيو 2025*
*الأمر المُستخدم: `php artisan test:daily-curriculum-apis`*
