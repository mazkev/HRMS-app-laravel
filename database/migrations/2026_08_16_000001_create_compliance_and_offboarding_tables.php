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
        // 1. Update Users Table with Tax & BPJS Identifiers
        Schema::table('users', function (Blueprint $table) {
            $table->string('ptkp_status', 10)->default('TK/0')->after('salary');
            $table->string('npwp', 30)->nullable()->after('ptkp_status');
            $table->string('bpjs_kesehatan_no', 30)->nullable()->after('npwp');
            $table->string('bpjs_ketenagakerjaan_no', 30)->nullable()->after('bpjs_kesehatan_no');
        });

        // 2. Update Payrolls Table with Statutory Tax & BPJS Deductions
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('pph21_amount', 15, 2)->default(0.00)->after('allowances');
            $table->decimal('bpjs_kesehatan_deduction', 15, 2)->default(0.00)->after('pph21_amount');
            $table->decimal('bpjs_tk_deduction', 15, 2)->default(0.00)->after('bpjs_kesehatan_deduction');
            $table->decimal('loan_deduction', 15, 2)->default(0.00)->after('bpjs_tk_deduction');
        });

        // 3. Update Leave Requests Table with Leave Types & SKD Upload
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->enum('leave_type', ['annual', 'sick', 'maternity', 'marriage', 'bereavement', 'unpaid'])->default('annual')->after('total_days');
            $table->string('medical_certificate')->nullable()->after('leave_type');
        });

        // 4. Warning Letters Table (Surat Peringatan)
        Schema::create('warning_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('letter_number')->unique(); // e.g. SP/2026/08/001
            $table->enum('level', ['SP 1', 'SP 2', 'SP 3'])->default('SP 1');
            $table->string('violation_type'); // e.g. Indisipliner Keterlambatan, Pelanggaran SOP
            $table->text('description');
            $table->date('issued_date');
            $table->date('valid_until');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'expired', 'revoked'])->default('active');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 5. Resignations & Paklaring Table
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('notice_date');
            $table->date('resign_date');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('paklaring_number')->nullable(); // e.g. PKL/2026/08/001
            $table->text('exit_clearance_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resignations');
        Schema::dropIfExists('warning_letters');
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['leave_type', 'medical_certificate']);
        });
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['pph21_amount', 'bpjs_kesehatan_deduction', 'bpjs_tk_deduction', 'loan_deduction']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ptkp_status', 'npwp', 'bpjs_kesehatan_no', 'bpjs_ketenagakerjaan_no']);
        });
    }
};
