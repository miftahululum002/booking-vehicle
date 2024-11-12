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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('driver_id')->constrained('drivers')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('date');
            $table->text('necessary');
            $table->enum('status', ['SUBMITTED', 'APPROVAL', 'APPROVED', 'REJECTED'])->default('SUBMITTED');
            $table->enum('is_done', ['0', '1'])->default('0');
            $table->integer('done_by')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->integer('updated_by')->nullable();
            $table->enum('is_deleted', ['1', '0'])->default('0');
            $table->timestamp('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->enum('is_restored', ['1', '0'])->default('0');
            $table->timestamp('restored_at')->nullable();
            $table->integer('restored_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
