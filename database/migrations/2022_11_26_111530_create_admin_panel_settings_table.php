<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminPanelSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_panel_settings', function (Blueprint $table) {
            $table->id();
            $table->string("system_name")->nullable();
            $table->string("image")->nullable();
            $table->text("general_alert")->nullable()->default(null);
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->foreignId("customer_parent_account_id")->nullable()->constrained("accounts");
            $table->foreignId("supplier_parent_account_id")->nullable()->constrained("accounts");
            $table->foreignId("delegate_parent_account_id")->nullable()->constrained("accounts");
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
        Schema::dropIfExists('admin_panel_settings');
    }
}
