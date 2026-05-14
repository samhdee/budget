<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->renameColumn('name', 'appellation');
            $table->string('color', length: 9)->nullable();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name', 'appellation');
        });
    }

    public function down(): void
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->renameColumn('appellation', 'name');
            $table->removeColumn('color');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name', 'appellation');
        });
    }
};
