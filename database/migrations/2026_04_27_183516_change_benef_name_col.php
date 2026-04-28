<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->renameColumn('name', 'raw_name');
            $table->string('pretty_name', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->renameColumn('raw_name', 'name');
            $table->dropColumn('pretty_name');
        });
    }
};
