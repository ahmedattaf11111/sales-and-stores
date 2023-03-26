<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasuryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasury_transactions', function (Blueprint $table) {
            $table->id();
            //Auto generated number started from treasury's last_receipt_collect field
            $table->unsignedBigInteger("collect_receipt_number")->default(0);
            //Auto generated number started from treasury's last_receipt_exchange field
            $table->unsignedBigInteger("exchange_receipt_number")->default(0);
            $table->foreignId("account_id")->nullable()->constrained("accounts");
            $table->foreignId("shift_id")->nullable()->constrained("shifts");
            $table->foreignId("move_type_id")->nullable()->constrained("move_types");
            $table->foreignId("purchase_invoice_id")->nullable()->constrained("purchase_invoices");
            $table->foreignId("sale_invoice_id")->nullable()->constrained("sale_invoices");
            $table->string("type")->nullable();
            //Treasury amount
            $table->decimal("amount")->default(0);
            $table->decimal("account_amount")->default(0);
            $table->text("note")->nullable();
            $table->foreignId("added_by_id")->nullable()->constrained("admins");
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
        Schema::dropIfExists('treasury_transactions');
    }
}
