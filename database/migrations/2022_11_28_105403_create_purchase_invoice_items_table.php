<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("purchase_invoice_id")->nullable()
            ->constrained("purchase_invoices")->cascadeOnDelete();
            $table->foreignId("item_id")->nullable()->constrained("items");
            $table->foreignId("unit_of_measure_id")->nullable()->constrained("unit_of_measures");
            $table->decimal("quantity", 10, 2)->default(0);
            $table->decimal("purchase_price", 10, 2)->default(0);
            $table->decimal("total_purchase_price", 10, 2)->default(0);
            $table->date("production_date")->nullable();
            $table->date("expiration_date")->nullable();
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
            $table->foreignId("updated_by_id")->nullable()->constrained("admins");
            $table->foreignId("batch_id")->nullable()->constrained("batches");
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
        Schema::dropIfExists('purchase_invoice_items');
    }
}
