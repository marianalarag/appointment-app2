<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Deshabilitar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Dropear tablas si existen
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('doctor_schedules');
        
        // Reabilitar verificación
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear doctor_schedules (corregido)
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->integer('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índice con nombre corto
            $table->unique(['doctor_id', 'day_of_week', 'start_time', 'end_time'], 'doc_sched_unique');
        });

        // Crear appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration')->default(15);
            $table->text('reason')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('doctor_id');
            $table->index('patient_id');
            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('doctor_schedules');
    }
};
