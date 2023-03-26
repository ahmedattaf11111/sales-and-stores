<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTreasuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_treasuries', function (Blueprint $table) {
            $table->id();
            $table->foreignId("main_treasury_id")->nullable()->constrained("treasuries");
            $table->foreignId("sub_treasury_id")->nullable()->constrained("treasuries");
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
        Schema::dropIfExists('sub_treasuries');
    }
}
