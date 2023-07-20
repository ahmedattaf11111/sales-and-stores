<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Store
        Permission::insert(
            [
                ["name" => "create store", "guard_name" => "admin"],
                ["name" => "update store", "guard_name" => "admin"],
                ["name" => "delete store", "guard_name" => "admin"],
                ["name" => "view store", "guard_name" => "admin"],
            ]
        );
        //general settings
        Permission::insert(
            [
                ["name" => "update general_setting", "guard_name" => "admin"],
                ["name" => "view general_setting", "guard_name" => "admin"],
            ]
        );
        //Item category
        Permission::insert(
            [
                ["name" => "create item_category", "guard_name" => "admin"],
                ["name" => "update item_category", "guard_name" => "admin"],
                ["name" => "delete item_category", "guard_name" => "admin"],
                ["name" => "view item_category", "guard_name" => "admin"],
            ]
        );
        //Item category
        Permission::insert(
            [
                ["name" => "create unit_of_measure", "guard_name" => "admin"],
                ["name" => "update unit_of_measure", "guard_name" => "admin"],
                ["name" => "delete unit_of_measure", "guard_name" => "admin"],
                ["name" => "view unit_of_measure", "guard_name" => "admin"],
            ]
        );
        //Item
        Permission::insert(
            [
                ["name" => "create item", "guard_name" => "admin"],
                ["name" => "update item", "guard_name" => "admin"],
                ["name" => "delete item", "guard_name" => "admin"],
                ["name" => "view item", "guard_name" => "admin"],
            ]
        );
        //Customer account
        Permission::insert(
            [
                ["name" => "create customer_account", "guard_name" => "admin"],
                ["name" => "update customer_account", "guard_name" => "admin"],
                ["name" => "delete customer_account", "guard_name" => "admin"],
                ["name" => "view customer_account", "guard_name" => "admin"],
            ]
        );
        //Delegate account
        Permission::insert(
            [
                ["name" => "create delegate_account", "guard_name" => "admin"],
                ["name" => "update delegate_account", "guard_name" => "admin"],
                ["name" => "delete delegate_account", "guard_name" => "admin"],
                ["name" => "view delegate_account", "guard_name" => "admin"],
            ]
        );
        //Supplier account
        Permission::insert(
            [
                ["name" => "create supplier_account", "guard_name" => "admin"],
                ["name" => "update supplier_account", "guard_name" => "admin"],
                ["name" => "delete supplier_account", "guard_name" => "admin"],
                ["name" => "view supplier_account", "guard_name" => "admin"],
            ]
        );
        //Supplier cateogry
        Permission::insert(
            [
                ["name" => "create supplier_category", "guard_name" => "admin"],
                ["name" => "update supplier_category", "guard_name" => "admin"],
                ["name" => "delete supplier_category", "guard_name" => "admin"],
                ["name" => "view supplier_category", "guard_name" => "admin"],
            ]
        );
        //Treasury
        Permission::insert(
            [
                ["name" => "create treasury", "guard_name" => "admin"],
                ["name" => "update treasury", "guard_name" => "admin"],
                ["name" => "delete treasury", "guard_name" => "admin"],
                ["name" => "view treasury", "guard_name" => "admin"],
            ]
        );
        //Shift
        Permission::insert(
            [
                ["name" => "create shift", "guard_name" => "admin"],
                ["name" => "update shift", "guard_name" => "admin"],
                ["name" => "delete shift", "guard_name" => "admin"],
                ["name" => "view shift", "guard_name" => "admin"],
                ["name" => "close shift", "guard_name" => "admin"],
            ]
        );
        //collect exchange money
        Permission::insert(
            [
                ["name" => "create collect_exchange_money", "guard_name" => "admin"],
                ["name" => "update collect_exchange_money", "guard_name" => "admin"],
                ["name" => "delete collect_exchange_money", "guard_name" => "admin"],
                ["name" => "view collect_exchange_money", "guard_name" => "admin"],
            ]
        );
        //purchase invoice
        Permission::insert(
            [
                ["name" => "create purchase_invoice", "guard_name" => "admin"],
                ["name" => "update purchase_invoice", "guard_name" => "admin"],
                ["name" => "delete purchase_invoice", "guard_name" => "admin"],
                ["name" => "view purchase_invoice", "guard_name" => "admin"],
                ["name" => "approve purchase_invoice", "guard_name" => "admin"],
            ]
        );
        //purchase return invoice
        Permission::insert(
            [
                ["name" => "create purchase_return_invoice", "guard_name" => "admin"],
                ["name" => "update purchase_return_invoice", "guard_name" => "admin"],
                ["name" => "delete purchase_return_invoice", "guard_name" => "admin"],
                ["name" => "view purchase_return_invoice", "guard_name" => "admin"],
                ["name" => "approve purchase_return_invoice", "guard_name" => "admin"],
            ]
        );
        //invoice category
        Permission::insert(
            [
                ["name" => "create invoice_category", "guard_name" => "admin"],
                ["name" => "update invoice_category", "guard_name" => "admin"],
                ["name" => "delete invoice_category", "guard_name" => "admin"],
                ["name" => "view invoice_category", "guard_name" => "admin"],
            ]
        );
        //sale invoice
        Permission::insert(
            [
                ["name" => "create sale_invoice", "guard_name" => "admin"],
                ["name" => "update sale_invoice", "guard_name" => "admin"],
                ["name" => "delete sale_invoice", "guard_name" => "admin"],
                ["name" => "view sale_invoice", "guard_name" => "admin"],
                ["name" => "approve sale_invoice", "guard_name" => "admin"],
            ]
        );
        Permission::insert(
            [
                ["name" => "create sale_return_invoice", "guard_name" => "admin"],
                ["name" => "update sale_return_invoice", "guard_name" => "admin"],
                ["name" => "delete sale_return_invoice", "guard_name" => "admin"],
                ["name" => "view sale_return_invoice", "guard_name" => "admin"],
                ["name" => "approve sale_return_invoice", "guard_name" => "admin"],
            ]
        );

        //Admin
        Permission::insert(
            [
                ["name" => "create admin", "guard_name" => "admin"],
                ["name" => "update admin", "guard_name" => "admin"],
                ["name" => "delete admin", "guard_name" => "admin"],
                ["name" => "view admin", "guard_name" => "admin"],
            ]
        );
        //supplier_account_statement
        Permission::insert(
            [
                ["name" => "view supplier_account_statement", "guard_name" => "admin"],
            ]
        );
    }
}
