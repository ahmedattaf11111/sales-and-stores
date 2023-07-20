<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_category_id")->constrained("invoice_categories");
            $table->decimal("invoice_number")->default(0);
            $table->date("date")->nullable();
            $table->decimal("total_sale_price")->default(0);
            $table->foreignId("customer_id")->nullable()->constrained("customers");
            $table->foreignId("delegate_id")->nullable()->constrained("delegates");
            $table->boolean("is_discount_percent")->default(1);
            $table->decimal("discount")->default(0);
            $table->boolean("is_tax_percent")->default(1);
            $table->decimal("tax")->default(0);
            $table->decimal("paid_amount")->default(0);
            $table->boolean("is_deferred")->default(0);
            $table->boolean("is_approved")->default(0);
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
            $table->foreignId("approved_by_id")->nullable()->constrained("admins");
            $table->decimal("is_delegate_commission_percent")->default(1);
            $table->decimal("delegate_commission")->default(0);
            $table->string("sale_price_type")->nullable();
            $table->string("type")->nullable();
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
        Schema::dropIfExists('sale_invoices');
    }
}
