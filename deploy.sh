#!/bin/bash

# أوقف التنفيذ عند حدوث أي خطأ
set -e

echo "🚀 بدء عملية النشر..."

# تحديث الكود من المستودع
echo "📥 جلب آخر تحديثات الكود..."
git pull origin main

# تثبيت تبعيات PHP
echo "📦 تثبيت تبعيات Composer..."
composer install --optimize-autoloader --no-dev

# ترحيل قاعدة البيانات
echo "🗄️ ترحيل قاعدة البيانات..."
php artisan migrate --force

# مسح الذاكرة المؤقتة
echo "🧹 مسح الذاكرة المؤقتة..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ربط مجلد التخزين
echo "🔗 ربط مجلد التخزين..."
php artisan storage:link

# تثبيت تبعيات NPM وبناء الأصول
echo "🔨 بناء أصول الواجهة الأمامية..."
npm ci
npm run build

# إعادة تشغيل صفوف المهام
echo "🔄 إعادة تشغيل عمال الصفوف..."
php artisan queue:restart

echo "✅ تم الانتهاء من عملية النشر بنجاح!"