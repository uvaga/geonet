<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAiRewriteLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_rewrite_logs', function (Blueprint $table) {
            $table->increments('id');

            // связь с page_topics.id
            $table->unsignedInteger('page_topic_id');
            $table->timestamp('rewrite_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('status', 256)->nullable();

            $table->timestamps(); // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_rewrite_logs');
    }
}
