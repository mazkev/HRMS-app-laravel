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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('image_out');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('distance_meters')->nullable()->after('longitude');
            $table->boolean('is_office_radius')->default(true)->after('distance_meters');
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('period_month', 7); // e.g. '2026-08'
            $table->decimal('basic_salary', 15, 2)->default(0.00);
            $table->decimal('allowances', 15, 2)->default(0.00);
            $table->decimal('late_deduction', 15, 2)->default(0.00);
            $table->decimal('other_deductions', 15, 2)->default(0.00);
            $table->decimal('net_salary', 15, 2)->default(0.00);
            $table->integer('total_present_days')->default(0);
            $table->integer('total_late_days')->default(0);
            $table->enum('status', ['draft', 'published', 'paid'])->default('published');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period_month']);
        });

        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration_hours', 4, 2);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
        Schema::dropIfExists('payrolls');
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'distance_meters', 'is_office_radius']);
        });
    }
};
