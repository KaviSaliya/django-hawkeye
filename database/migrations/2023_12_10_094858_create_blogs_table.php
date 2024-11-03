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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->integer('created_by')->notNull();
            $table->string('blog_image', 255)->nullable();
            $table->string('blog_image_driver', 255)->nullable();
            $table->string('breadcrumb_status', 255)->nullable();
            $table->string('breadcrumb_image', 255)->nullable();
            $table->string('breadcrumb_image_driver', 255)->nullable();
            $table->string('page_title', 191)->nullable();
            $table->string('meta_title', 191)->nullable();
            $table->string('meta_keywords', 191)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image', 191)->nullable();
            $table->string('meta_image_driver', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
