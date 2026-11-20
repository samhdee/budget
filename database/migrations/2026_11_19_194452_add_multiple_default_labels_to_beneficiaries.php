<?php

use App\Models\Beneficiary;
use App\Models\Label;
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
        Schema::create('labels_beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Label::class, 'label_id');
            $table->foreignIdFor(Beneficiary::class, 'beneficiary_id');
            $table->timestamps();
        });

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn('label_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labels_beneficiaries');
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->foreignIdFor(Label::class, 'label_id')->nullable();
        });
    }
};
