<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('goal')->nullable();
        });
        Schema::table('labels', function (Blueprint $table) {
            $table->integer('goal')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('goal');
        });
        Schema::table('labels', function (Blueprint $table) {
            $table->dropColumn('goal');
        });
    }
};
