<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentAttendance;

class TestAllAttendanceStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:attendance-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار جميع حالات الحضور في API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== اختبار جميع حالات الحضور ===');
        $this->info('الوقت: ' . now()->format('Y-m-d H:i:s'));
        
        // 1. الحصول على بيانات المعلم والطالب
        $teacher = Teacher::first();
        $student = Student::first();
        
        if (!$teacher || !$student) {
            $this->error('لا توجد بيانات كافية في قاعدة البيانات');
            return;
        }
        
        $this->info("سيتم الاختبار على الطالب: {$student->name} (ID: {$student->id})");
        
        // 2. تسجيل دخول المعلم
        $this->info('1. تسجيل دخول المعلم...');
        $loginResponse = Http::accept('application/json')
            ->post('http://localhost:8000/api/auth/teacher/login', [
                'identity_number' => $teacher->identity_number,
                'password' => '0530996778'
            ]);
        
        if (!$loginResponse->successful()) {
            $this->error('فشل في تسجيل دخول المعلم');
            return;
        }
        
        $this->info('✅ تم تسجيل دخول المعلم بنجاح');
        
        // 3. اختبار جميع حالات الحضور
        $statuses = [
            'present' => 'حاضر',
            'absent' => 'غائب', 
            'late' => 'متأخر',
            'excused' => 'معذور'
        ];
        
        $this->info('2. اختبار جميع حالات الحضور...');
        $this->newLine();
        
        $results = [];
        
        foreach ($statuses as $englishStatus => $arabicStatus) {
            $this->info("اختبار حالة: {$arabicStatus} ({$englishStatus})");
            
            // حذف السجل السابق إن وجد
            StudentAttendance::where('attendable_id', $student->id)
                ->where('attendable_type', 'App\\Models\\Student')
                ->where('date', now()->format('Y-m-d'))
                ->delete();
            
            // إرسال طلب تسجيل الحضور
            $attendanceResponse = Http::accept('application/json')
                ->post('http://localhost:8000/api/attendance/record', [
                    'student_name' => $student->name,
                    'date' => now()->format('Y-m-d'),
                    'status' => $englishStatus,
                    'period' => 'العصر',
                    'notes' => "اختبار حالة {$arabicStatus}"
                ]);
            
            if ($attendanceResponse->successful()) {
                $this->info("  ✅ نجح تسجيل حالة {$arabicStatus}");
                
                // التحقق من قاعدة البيانات
                $attendance = StudentAttendance::where('attendable_id', $student->id)
                    ->where('attendable_type', 'App\\Models\\Student')
                    ->where('date', now()->format('Y-m-d'))
                    ->first();
                
                if ($attendance) {
                    $this->info("  ✅ تم حفظ السجل في قاعدة البيانات: {$attendance->status}");
                    $results[$englishStatus] = [
                        'success' => true,
                        'saved_status' => $attendance->status,
                        'expected_status' => $arabicStatus
                    ];
                } else {
                    $this->error("  ❌ لم يتم حفظ السجل في قاعدة البيانات");
                    $results[$englishStatus] = [
                        'success' => false,
                        'error' => 'السجل غير موجود في قاعدة البيانات'
                    ];
                }
                
            } else {
                $this->error("  ❌ فشل في تسجيل حالة {$arabicStatus}");
                $this->error("     كود الخطأ: " . $attendanceResponse->status());
                $this->error("     رسالة الخطأ: " . $attendanceResponse->body());
                
                $results[$englishStatus] = [
                    'success' => false,
                    'error' => $attendanceResponse->body()
                ];
            }
            
            $this->newLine();
            sleep(1); // انتظار ثانية واحدة بين كل اختبار
        }
        
        // 4. عرض النتائج النهائية
        $this->info('=== ملخص النتائج ===');
        
        $successCount = 0;
        $failureCount = 0;
        
        foreach ($results as $englishStatus => $result) {
            $arabicStatus = $statuses[$englishStatus];
            
            if ($result['success']) {
                $this->info("✅ {$arabicStatus} ({$englishStatus}): نجح - محفوظ كـ {$result['saved_status']}");
                
                // التحقق من صحة التحويل
                if ($result['saved_status'] === $result['expected_status']) {
                    $this->info("   ✅ تم تحويل الحالة بشكل صحيح");
                } else {
                    $this->warn("   ⚠️  تحويل الحالة غير متطابق (متوقع: {$result['expected_status']}, محفوظ: {$result['saved_status']})");
                }
                
                $successCount++;
            } else {
                $this->error("❌ {$arabicStatus} ({$englishStatus}): فشل");
                $failureCount++;
            }
        }
        
        $this->newLine();
        $this->info("إجمالي النجاح: {$successCount}/" . count($statuses));
        $this->info("إجمالي الفشل: {$failureCount}/" . count($statuses));
        
        if ($failureCount === 0) {
            $this->info('🎉 جميع حالات الحضور تعمل بشكل مثالي!');
        } else {
            $this->warn('⚠️  بعض حالات الحضور لا تعمل بشكل صحيح');
        }
        
        $this->info('=== انتهى الاختبار ===');
    }
}
