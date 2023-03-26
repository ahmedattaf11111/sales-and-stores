<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("image")->nullable();
            $table->foreignId("item_category_id")->nullable()->constrained("item_categories");
            $table->string("type")->nullable(); //Has three values ItemType class (STORE,HAS_EXPIRATION_DATE,CUSTODY)
            $table->boolean("has_fixed_price")->default(1);
            $table->boolean("active")->default(1);
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
            $table->foreignId("updated_by_id")->nullable()->constrained("admins");
            //Main unit columns
            $table->foreignId("main_unit_of_measure_id")->nullable()->constrained("unit_of_measures");
            $table->decimal("main_unit_half_wholesale_price", 10, 2)->default(0);
            $table->decimal("main_unit_wholesale_price", 10, 2)->default(0);
            $table->decimal("main_unit_retail_price", 10, 2)->default(0);
            $table->boolean("has_sub_unit")->default(0);
            //Sub unit fields
            $table->integer("sub_to_main_quantity")->default(2);
            $table->foreignId("sub_unit_of_measure_id")->nullable()->constrained("unit_of_measures");
            $table->decimal("sub_unit_half_wholesale_price", 10, 2)->default(0);
            $table->decimal("sub_unit_wholesale_price", 10, 2)->default(0);
            $table->decimal("sub_unit_retail_price", 10, 2)->default(0);
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
        Schema::dropIfExists('items');
    }
}
