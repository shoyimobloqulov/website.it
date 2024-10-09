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
        Schema::create('code_execution_results', function (Blueprint $table) {
            $table->id();
            $table->string('language');
            $table->string('version');
            $table->text('code');
            $table->text('output');
            $table->text('error')->nullable();
            $table->integer('execution_time')->nullable();
            $table->integer('memory_used')->nullable();
            $table->integer('code_length')->nullable()->default(0);
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_execution_results');
    }
};
