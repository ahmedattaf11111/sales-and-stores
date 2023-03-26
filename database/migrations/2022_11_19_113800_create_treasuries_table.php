<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasuries', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->integer("last_collection_receipt")->default(0);
            $table->integer("last_exchange_receipt")->default(0);
            $table->boolean("is_master")->default(0);
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
            $table->foreignId("updated_by_id")->nullable()->constrained("admins");
            $table->boolean("active")->default(1);
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
        Schema::dropIfExists('treasuries');
    }
}
