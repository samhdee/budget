<?php

use App\Models\Beneficiary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transac_recurring_patterns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->foreignIdFor(Beneficiary::class);
            $table->tinyInteger('active');
            $table->string('frequency_unit');
            $table->tinyInteger('frequency_count');
            $table->tinyText('type')->nullable();
            $table->decimal('amount');
            $table->date('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['beneficiary_id'], 'transac_recurring_patterns_beneficiary_id_index');
            $table->index(['active'], 'transac_recurring_patterns_active_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transac_recurring_patterns');
    }
};
