<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId("store_id")->nullable()->constrained("stores");
            $table->foreignId("item_id")->nullable()->constrained("items");
            $table->foreignId("unit_of_measure_id")->nullable()->constrained("unit_of_measures");
            $table->decimal("purchase_price")->default(0);
            $table->decimal("quantity")->default(0);
            $table->decimal("total_purchase_price")->default(0);
            $table->date("production_date")->nullable();
            $table->date("expiration_date")->nullable();
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
            $table->foreignId("updated_by_id")->nullable()->constrained("admins");
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
        Schema::dropIfExists('batches');
    }
}
