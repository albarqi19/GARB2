<?php
require __DIR__ . '/vendor/autoload.php';
\ = require_once __DIR__ . '/bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

try {
    \ = DB::select('SHOW COLUMNS FROM student_curriculum_progress');
    echo \
جدول
student_curriculum_progress
موجود
وله
البنية
التالية:\\n\;
    foreach (\ as \) {
        echo \->Field . ' (' . \->Type . ') ' . (\->Null === 'NO' ? 'NOT NULL' : 'NULL') . 
             (\->Default ? ' DEFAULT ' . \->Default : '') . 
             (\->Key ? ' KEY: ' . \->Key : '') . \\\n\;
    }
} catch (Exception \) {
    echo \خطأ:
\ . \->getMessage();
}

