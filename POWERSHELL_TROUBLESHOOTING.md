# مشاكل PowerShell الشائعة وحلولها
# PowerShell Common Issues and Solutions
# تاريخ: 9 يونيو 2025

===============================================
🚨 المشاكل الشائعة في ملفات PowerShell
===============================================

1. مشكلة تشفير النصوص العربية
   المشكلة: النصوص العربية تظهر كرموز غريبة
   السبب: تشفير الملف غير صحيح (UTF-8 vs UTF-16)
   الحل: 
   - احفظ الملف بتشفير UTF-8 with BOM
   - تجنب النصوص العربية في أسماء المتغيرات
   - استخدم النصوص العربية فقط في القيم

2. خطأ Execution Policy
   المشكلة: cannot be loaded because running scripts is disabled
   الحل: powershell -ExecutionPolicy Bypass -File "filename.ps1"

3. أخطاء في تحليل الكود (Parse Errors)
   المشكلة: Unexpected token, Missing closing
   السبب: أقواس غير مكتملة، رموز تالفة
   الحل: تحقق من:
   - جميع الأقواس مغلقة { }
   - جميع الاقتباسات مغلقة " "
   - جميع Try-Catch blocks مكتملة

4. مشكلة عدم ظهور النتائج
   المشكلة: الملف يعمل لكن لا توجد مخرجات
   الأسباب المحتملة:
   - الخادم لا يعمل (http://127.0.0.1:8000)
   - مشكلة في الاتصال بقاعدة البيانات
   - أخطاء في التحقق من صحة البيانات

===============================================
✅ كيفية اختبار الـ API بنجاح
===============================================

الطريقة الأولى: استخدام ملف PowerShell محسن
powershell -ExecutionPolicy Bypass -File "final_demo.ps1"

الطريقة الثانية: استخدام اختبار Laravel المدمج
php artisan test:recitation-complete --api

الطريقة الثالثة: اختبار يدوي بسيط
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/recitation/sessions" -Method GET -Headers @{"Accept"="application/json"}
$response.data.count

===============================================
📋 متطلبات تشغيل الـ API
===============================================

1. خادم Laravel يعمل على localhost:8000
   php artisan serve

2. قاعدة بيانات متصلة ومُهيأة
   php artisan migrate
   php artisan db:seed

3. بيانات تجريبية موجودة
   - طلاب (students)
   - معلمين (users مع role teacher)  
   - حلقات قرآنية (quran_circles)

4. PowerShell 5.0+ أو PowerShell Core

===============================================
🔧 اختبار سريع للتأكد من عمل النظام
===============================================

# تحقق من حالة الخادم
Test-NetConnection -ComputerName 127.0.0.1 -Port 8000

# تحقق من API
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/recitation/sessions" -Method GET -Headers @{"Accept"="application/json"}
Write-Host "API works! Sessions count: $($response.data.count)"

# تحقق من Laravel
php artisan route:list | findstr "recitation"

===============================================
