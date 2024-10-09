<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_inputs_outputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->text('input')->nullable();
            $table->text('output')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_inputs_outputs');
    }
};
