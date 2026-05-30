<?php

use App\Models\Label;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('labels_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Label::class, 'label_id');
            $table->foreignIdFor(\App\Models\Transaction::class, 'transaction_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labels_transactions');
    }
};
