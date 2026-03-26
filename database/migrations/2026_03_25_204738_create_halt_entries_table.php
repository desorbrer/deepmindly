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
        Schema::create('halt_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('hungry')->default(0);
            $table->unsignedTinyInteger('angry')->default(0);
            $table->unsignedTinyInteger('lonely')->default(0);
            $table->unsignedTinyInteger('tired')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halt_entries');
    }
};
