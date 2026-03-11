<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // relación con users
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // relación con blood_types
            $table->foreignId('blood_type_id')
                ->nullable()
                ->constrained();

            $table->text('allergies')->nullable();
            $table->string('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('family_history')->nullable();
            $table->text('observations')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
