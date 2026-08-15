<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Shifts Table
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Regular Office, Shift Pagi, Shift Siang
            $table->time('start_time');
            $table->time('end_time');
            $table->time('late_threshold_time');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Add shift_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->after('department_id')->constrained('shifts')->nullOnDelete();
        });

        // 3. Reimbursements (Expense Claims)
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('category', ['transport', 'medical', 'meal', 'office_supplies', 'other'])->default('other');
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->string('receipt_image')->nullable();
            $table->text('description');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 4. Performance Reviews (KPI Appraisal)
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('period_year', 4); // '2026'
            $table->string('period_quarter', 10); // 'Q1', 'Q2', 'Q3', 'Q4', 'Annual'
            $table->integer('kpi_score')->default(80); // 1-100
            $table->integer('attendance_score')->default(90); // 1-100
            $table->integer('teamwork_score')->default(85); // 1-100
            $table->string('final_grade', 2)->default('A'); // A, B, C, D
            $table->text('feedback')->nullable();
            $table->enum('status', ['draft', 'final'])->default('final');
            $table->timestamps();
        });

        // 5. Announcements (Company Bulletin)
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->enum('category', ['general', 'holiday', 'policy', 'event'])->default('general');
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        // 6. Employee Documents (Digital Vault)
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('document_type'); // KTP, NPWP, PKWT, Certificate, etc.
            $table->string('title');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('reimbursements');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
        Schema::dropIfExists('shifts');
    }
};
