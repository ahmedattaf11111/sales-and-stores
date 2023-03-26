<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sale_invoice_id")->nullable()
            ->constrained("sale_invoices")->cascadeOnDelete();
            $table->foreignId("store_id")->nullable()->constrained("stores");
            $table->foreignId("item_id")->nullable()->constrained("items");
            $table->foreignId("unit_of_measure_id")->nullable()->constrained("unit_of_measures");
            $table->string("sale_price_type")->nullable();//Whole sale or half whole sale or retail
            $table->string("is_sale_price_normal")->nullable();
            $table->decimal("quantity", 10, 2)->default(0);
            $table->decimal("sale_price", 10, 2)->default(0);
            $table->decimal("total_sale_price", 10, 2)->default(0);
            $table->foreignId("batch_id")->nullable()->constrained("batches");
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
        Schema::dropIfExists('sale_invoice_items');
    }
}
