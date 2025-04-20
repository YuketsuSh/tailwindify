<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tailwindify_replacements', function (Blueprint $table) {
            $table->id();
            $table->string('bootstrap_class')->unique();
            $table->string('tailwind_class')->nullable();
            $table->enum('status', ['pending', 'validated', 'ignored'])->default('pending');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tailwindify_replacements');
    }
};
