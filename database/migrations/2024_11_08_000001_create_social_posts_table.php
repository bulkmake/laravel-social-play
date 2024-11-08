<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->string('caption')->nullable();
            $table->string('cta_link')->nullable();
            $table->json('media')->nullable();
            $table->string('type')->nullable(); // e.g., 'image', 'video'
            $table->date('scheduled_at')->nullable();
            $table->json('responses')->nullable(); // To store responses in JSON format
            $table->enum('status', ['draft', 'scheduled', 'published', 'error'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_posts');
    }
};
