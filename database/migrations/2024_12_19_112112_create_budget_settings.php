<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->timestamp('month');
            $table->decimal('budget_amount', 10, 2);
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->unique(['hotel_id', 'month'], 'hotel_month_unique');
        });
    }

    /*     public function down()
    {
        Schema::dropIfExists('budget_settings');
    } */
};
