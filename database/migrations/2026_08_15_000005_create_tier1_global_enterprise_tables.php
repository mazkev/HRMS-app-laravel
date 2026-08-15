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
        // 1. Job Postings (ATS)
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->enum('type', ['full_time', 'contract', 'internship', 'remote'])->default('full_time');
            $table->string('experience_level')->default('1-3 Tahun');
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->text('description');
            $table->text('requirements');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamps();
        });

        // 2. Job Applications (Pipeline)
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->string('candidate_phone');
            $table->string('resume_path')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->enum('status', ['applied', 'screening', 'interview', 'offering', 'hired', 'rejected'])->default('applied');
            $table->dateTime('interview_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Company Assets
        Schema::create('company_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('asset_code')->unique(); // e.g. AST-LAP-001
            $table->string('name'); // MacBook Pro M3
            $table->enum('category', ['laptop', 'vehicle', 'monitor', 'furniture', 'device', 'other'])->default('laptop');
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->enum('condition', ['good', 'fair', 'damaged', 'maintenance'])->default('good');
            $table->enum('status', ['in_use', 'available', 'disposed'])->default('available');
            $table->date('assigned_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Employee Loans / Kasbon
        Schema::create('employee_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->integer('tenor_months')->default(3); // 1-12 bulan
            $table->decimal('monthly_installment', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('disbursed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 5. Trainings (LMS Lite)
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('trainer_name');
            $table->string('category')->default('Technical');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->default('Ruang Training Lt. 3 / Zoom');
            $table->integer('capacity')->default(20);
            $table->text('description');
            $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
            $table->timestamps();
        });

        // 6. Training Participants
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'attended', 'completed', 'certified'])->default('enrolled');
            $table->integer('score')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();

            $table->unique(['training_id', 'user_id']);
        });

        // 7. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // UPDATE_SALARY, APPROVE_LEAVE, CREATE_EMPLOYEE, etc.
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('training_participants');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('employee_loans');
        Schema::dropIfExists('company_assets');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_postings');
    }
};
