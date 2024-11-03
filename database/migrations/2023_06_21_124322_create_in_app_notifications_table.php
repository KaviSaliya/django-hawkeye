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
        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('in_app_notificationable_id'); // in_app_notificationable_id column, int(11), not nullable
            $table->string('in_app_notificationable_type'); // in_app_notificationable_type column, varchar(255), not nullable
            $table->text('description')->nullable(); // description column, text, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_app_notifications');
    }
};
