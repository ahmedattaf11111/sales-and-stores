<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelegatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegates', function (Blueprint $table) {
            $table->id();
            $table->foreignId("account_id")->nullable()->constrained("accounts");
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->boolean("percent_type")->default(0);//0 percent 1 const
            $table->string("percent_collect_commission")->default(0);
            $table->string("percent_sales_retail_commission")->default(0);
            $table->string("percent_halfsale_commission")->default(0);
            $table->string("percent_wholesale_commission")->default(0);
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
        Schema::dropIfExists('delegates');
    }
}
