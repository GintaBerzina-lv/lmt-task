<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_post_statuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('code', 50);

            $table->unique('code', 'UNIQ_c_post_statuses_code');
        });

        Schema::create('c_reactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('code', 50);

            $table->unique('code', 'UNIQ_c_reactions_code');
        });

        Schema::create('d_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('published_at')->nullable()->default(null);
            $table->foreignId('user_id')->constrained('users');
            $table->string('title', 255);
            $table->text('content')->nullable();
            $table->string('slug', 255);
            $table->foreignId('status_id')->constrained('c_post_statuses');
            $table->unsignedBigInteger('totalReactions')->default(0);
            $table->unsignedBigInteger('totalLIKE')->default(0);
        });

        Schema::create('d_post_reactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('post_id')->constrained('d_posts');
            $table->foreignId('reaction_id')->constrained('c_reactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_post_reactions');
        Schema::dropIfExists('d_posts');
        Schema::dropIfExists('c_reactions');
        Schema::dropIfExists('c_post_statuses');
    }
};
