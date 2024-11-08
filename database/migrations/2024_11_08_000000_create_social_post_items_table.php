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
        Schema::create('social_post_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id'); // Foreign key to related item
            $table->string('item_type');           // Polymorphic relationship type
            $table->unsignedBigInteger('social_post_id'); // Foreign key to social post
            $table->timestamps();

            // Indexes for optimization
            $table->index(['item_id', 'item_type']);
            $table->foreign('social_post_id')
                ->references('id')->on('social_posts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_post_items');
    }
};
