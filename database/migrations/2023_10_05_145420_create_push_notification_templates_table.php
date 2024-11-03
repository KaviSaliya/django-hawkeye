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
        Schema::create('push_notification_templates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('language_id')->unsigned(); // language_id column, bigint(20) unsigned, not nullable
            $table->foreign('language_id')->references('id')->on('languages'); // Foreign key constraint with languages table
            $table->string('name', 255)->nullable(); // name column, varchar(255), nullable
            $table->string('email_from', 191)->nullable(); // email_from column, varchar(191), nullable
            $table->string('template_key', 255)->nullable(); // template_key column, varchar(255), nullable
            $table->text('subject')->nullable(); // subject column, text, nullable
            $table->text('short_keys')->nullable(); // short_keys column, text, nullable
            $table->text('email')->nullable(); // email column, text, nullable
            $table->text('sms')->nullable(); // sms column, text, nullable
            $table->text('in_app')->nullable(); // in_app column, text, nullable
            $table->text('push')->nullable(); // push column, text, nullable
            $table->string('status', 191)->nullable()->comment('mail = 0(inactive), mail = 1(active),\r\nsms = 0(inactive), sms = 1(active),\r\nin_app = 0(inactive), in_app = 1(active),\r\npush = 0(inactive), push = 1(active),\r\n '); // status column, varchar(191), nullable, with comment
            $table->tinyInteger('notify_for')->default(0)->comment('0 => user, 1 => admin'); // notify_for column, tinyint(1), not nullable, default 0, with comment
            $table->string('lang_code', 50)->nullable(); // lang_code column, varchar(50), nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notification_templates');
    }
};
