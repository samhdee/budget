<?php

use App\Models\CategoryModel;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100)->unique();
            $table->string('color', length: 9)->nullable();
            $table->string('description', length: 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100)->unique();
            $table->string('description', length: 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('label_category_pivot', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('label_id')->references('id')->on('labels');
            $table->bigInteger('transaction_id')->references('id')->on('transactions');
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignIdFor(CategoryModel::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('labels');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['', '']);
        });
    }
};
