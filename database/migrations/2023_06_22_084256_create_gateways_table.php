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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code', 191); // Code column, varchar(191), not nullable
            $table->string('name', 191); // Name column, varchar(191), not nullable
            $table->integer('sort_by')->default(1); // sort_by column, int(11), default 1
            $table->string('image', 191)->nullable(); // image column, varchar(191), nullable
            $table->string('driver', 20)->nullable(); // driver column, varchar(20), nullable
            $table->tinyInteger('status')->default(1)->comment('0: inactive, 1: active'); // status column, tinyint(1), not nullable, default 1, with comment
            $table->text('parameters')->nullable(); // parameters column, text, nullable
            $table->text('currencies')->nullable(); // currencies column, text, nullable
            $table->text('extra_parameters')->nullable(); // extra_parameters column, text, nullable
            $table->string('supported_currency', 255)->nullable(); // supported_currency column, varchar(255), nullable
            $table->text('receivable_currencies')->nullable(); // receivable_currencies column, text, nullable
            $table->text('description')->nullable(); // description column, text, nullable
            $table->tinyInteger('currency_type')->default(1); // currency_type column, tinyint(1), not nullable, default 1
            $table->tinyInteger('is_sandbox')->default(0); // is_sandbox column, tinyint(1), not nullable, default 0
            $table->enum('environment', ['test', 'live'])->default('live'); // environment column, enum('test','live'), not nullable, default 'live'
            $table->tinyInteger('is_manual')->default(1); // is_manual column, tinyint(1), default 1
            $table->text('note')->nullable(); // note column, text, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
