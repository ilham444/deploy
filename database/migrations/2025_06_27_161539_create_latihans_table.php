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
       Schema::create('latihans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('modul_id')->constrained('moduls')->onDelete('cascade');
        $table->string('judul');
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latihans');
    }
};
