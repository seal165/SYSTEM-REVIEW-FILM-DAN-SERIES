<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('genre');
            $table->integer('duration'); // in minutes
            $table->date('release_date');
            $table->string('rating'); // G, PG, PG-13, R
            $table->text('description');
            $table->string('poster_url')->nullable();
            $table->decimal('price', 8, 2);
            $table->enum('status', ['now_showing', 'coming_soon', 'ended'])->default('now_showing');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
};