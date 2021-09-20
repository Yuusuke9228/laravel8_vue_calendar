<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // ここから追加
            $table->string('name', 100);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('timed')->nullable()->default(false);
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('calendar_id');
            // ここまで追加
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
        Schema::dropIfExists('events');
    }
}
