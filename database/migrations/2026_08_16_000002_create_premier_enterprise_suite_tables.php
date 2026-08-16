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
        // 1. THR Payments (Tunjangan Hari Raya Keagamaan)
        Schema::create('thr_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('year', 4); // 2026
            $table->string('holiday_name')->default('Idul Fitri 1447 H');
            $table->integer('tenure_months')->default(12);
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('thr_amount', 15, 2);
            $table->date('payment_date')->nullable();
            $table->enum('status', ['paid', 'pending'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'year', 'holiday_name']);
        });

        // 2. Business Trips (SPPD / Surat Perintah Perjalanan Dinas)
        Schema::create('business_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('sppd_number')->unique(); // e.g. SPPD/2026/08/001
            $table->string('destination_city');
            $table->string('purpose');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(1);
            $table->decimal('daily_allowance_rate', 15, 2)->default(350000.00); // Pagu uang harian
            $table->decimal('total_allowance', 15, 2)->default(350000.00);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 3. Shift Swaps (Pengajuan Tukar Shift Antar Karyawan)
        Schema::create('shift_swaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_user_id')->constrained('users')->cascadeOnDelete();
            $table->date('swap_date');
            $table->foreignId('requester_shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('target_shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->text('reason');
            $table->enum('status', ['pending_admin', 'approved', 'rejected'])->default('pending_admin');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Notification Gateway Logs (WhatsApp & Email Dispatcher)
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'email'])->default('whatsapp');
            $table->string('recipient');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['sent', 'delivered', 'failed'])->default('sent');
            $table->timestamps();
        });

        // 5. Peer Kudos & Wall of Fame (Apresiasi Rekan Kerja)
        Schema::create('peer_kudos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->enum('badge_type', ['team_player', 'problem_solver', 'innovator', 'customer_hero', 'leadership'])->default('team_player');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peer_kudos');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('shift_swaps');
        Schema::dropIfExists('business_trips');
        Schema::dropIfExists('thr_payments');
    }
};
