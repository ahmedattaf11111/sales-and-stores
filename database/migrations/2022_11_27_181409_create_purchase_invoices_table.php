<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date("date")->nullable();
            $table->string("type")->nullable(); //Purchase , Return on same pill , return general 
            $table->string("invoice_number")->default(0);
            $table->foreignId("supplier_id")->nullable()->constrained("suppliers");
            $table->foreignId("store_id")->nullable()->constrained("stores");
            $table->boolean("is_discount_percent")->default(1);
            $table->decimal("discount")->default(0);
            $table->boolean("is_tax_percent")->default(1);
            $table->decimal("tax")->default(0);
            $table->boolean("is_deferred")->default(0);
            $table->decimal("paid_amount")->default(0);
            $table->boolean("is_approved")->default(0);
            $table->decimal("total_purchase_price")->default(0);
            $table->text("note")->nullable();
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
            $table->foreignId("updated_by_id")->nullable()->constrained("admins");
            $table->foreignId("approved_by_id")->nullable()->constrained("admins");
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
        Schema::dropIfExists('purchase_invoices');
    }
}
