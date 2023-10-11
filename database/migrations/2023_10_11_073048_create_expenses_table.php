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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expenses_id');
            $table->string('image')->nullable();
            $table->string('expenses_date');
            $table->string('name');
            $table->string('category');
            $table->string('amount');
            $table->string('recipient_name');
            $table->string('payment_type');
            $table->string('expenses_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
