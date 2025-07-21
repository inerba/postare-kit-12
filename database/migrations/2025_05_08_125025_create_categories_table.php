<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Disabilita i controlli delle chiavi esterne
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('categories');
        // Riabilita i controlli delle chiavi esterne
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
