<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_issue_id');
            $table->string('expense_name');
            $table->decimal('expense_amount', 10, 2);
            $table->timestamps();

            $table->foreign('report_issue_id')
                ->references('id')
                ->on('travelodges');
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_expenses');
    }
};
