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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('key')->unique();
            $table->text('condition')->nullable();
            $table->text('input')->nullable();
            $table->text('output')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('time')->default(0);
            $table->unsignedBigInteger('memory')->default(0);
            $table->unsignedBigInteger('difficulty')->default(0);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
