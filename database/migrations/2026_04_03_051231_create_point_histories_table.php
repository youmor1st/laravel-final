<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rule_id')->constrained('discipline_rules');
            $table->foreignId('teacher_id')->constrained('users');
            $table->integer('points');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('comment', 500)->nullable();
            $table->date('occurred_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};