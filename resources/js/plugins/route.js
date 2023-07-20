import { createWebHistory, createRouter } from "vue-router";
import DashboardLayout from '../layouts/dashboard-layout';
import AuthLayout from '../layouts/auth-layout';
import Login from '../components/auth/login';
import HelloTable from '../components/dashboard/hello/hello-table';
import TreasuryTable from '../components/dashboard/treasuries/treasury-table';
import SupplierCategoryTable from '../components/dashboard/supplier/supplier-category/supplier-category-table';
import SupplierTable from '../components/dashboard/supplier/suppliers/supplier-table';
import PurchaseInvoiceTable from '../components/dashboard/purchase-invoice/purchase-invoices/purchase-invoice-table';
import PurchaseReturnInvoiceTable from '../components/dashboard/purchase-invoice/purchase-return-invoices/purchase-return-invoice-table';
import PurchaseInvoiceItemTable from '../components/dashboard/purchase-invoice/purchase-invoice-items/purchase-invoice-item-table';
import PurchaseReturnInvoiceItemTable from '../components/dashboard/purchase-invoice/purchase-return-invoice-items/purchase-return-invoice-item-table';
import UnitOfMeasureTable from '../components/dashboard/unit-of-measuries/unit-of-measure-table';
import StoreTable from '../components/dashboard/stores/store-table';
import ItemTable from '../components/dashboard/items/item-table';
import AccountTable from '../components/dashboard/accounts/account-table';
import CustomerTable from '../components/dashboard/customers/customer-table';
import DelegateTable from '../components/dashboard/delegates/delegate-table';
import InvoiceCategoryTable from '../components/dashboard/invoice-categories/invoice-category-table';
import ItemCategoryTable from '../components/dashboard/item-categories/item-category-table';
import AdminTable from '../components/dashboard/admins/admin-table';
import ShiftTable from '../components/dashboard/shift/shift-table';
import SaleInvoiceTable from '../components/dashboard/sales-invoices/sales-invoices-table';
import SaleReturnInvoiceTable from '../components/dashboard/sales-returns-invoices/sales-returns-invoices-table';
import TreasuryTransactionTable from '../components/dashboard/treasury-transaction/treasury-transaction-table';
import AdminPanelSettings from '../components/dashboard/admin-panel-settings';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
import PageNotFound from "../shared/components/page-not-found";
import PermissionGuard from "../shared/guards/permission-guard";
import SupplierAccountStatement from '../components/dashboard/reports/supplier_account_report';
const routes = [
  {
    path: "",
    redirect: "/admin-panel-settings"
  },
  {
    path: "",
    component: DashboardLayout,
    beforeEnter: [
      AuthenticatedGuard
    ],
    children: [
      { path: "hellos", component: HelloTable },
      { path: "admin-panel-settings", component: AdminPanelSettings },
      { path: "treasuries", component: TreasuryTable, name: "view treasury", beforeEnter: [PermissionGuard] },
      { path: "invoice-categories", component: InvoiceCategoryTable, name: "view invoice_category", beforeEnter: [PermissionGuard] },
      { path: "item-categories", component: ItemCategoryTable, name: "view item_category", beforeEnter: [PermissionGuard] },
      { path: "stores", component: StoreTable, name: "view store", beforeEnter: [PermissionGuard] },
      { path: "unit-of-measures", component: UnitOfMeasureTable, name: "view unit_of_measure", beforeEnter: [PermissionGuard] },
      { path: "items", component: ItemTable, name: "view item", beforeEnter: [PermissionGuard] },
      { path: "accounts", component: AccountTable, name: "view all_account", beforeEnter: [PermissionGuard] },
      { path: "customers", component: CustomerTable, name: "view customer_account", beforeEnter: [PermissionGuard] },
      { path: "delegates", component: DelegateTable, name: "view delegate_account", beforeEnter: [PermissionGuard] },
      { path: "supplier-categories", component: SupplierCategoryTable, name: "view supplier_category", beforeEnter: [PermissionGuard] },
      { path: "suppliers", component: SupplierTable, name: "view supplier_account", beforeEnter: [PermissionGuard] },
      { path: "admins", component: AdminTable, name: "view admin", beforeEnter: [PermissionGuard] },
      { path: "shifts", component: ShiftTable, name: "view shift", beforeEnter: [PermissionGuard] },
      { path: "treasury-transactions", component: TreasuryTransactionTable, name: "view collect_exchange_money", beforeEnter: [PermissionGuard] },
      { path: "sales-invoices", component: SaleInvoiceTable, name: "view sale_invoice", beforeEnter: [PermissionGuard] },
      { path: "sales-returns-invoices", component: SaleReturnInvoiceTable, name: "view sale_return_invoice", beforeEnter: [PermissionGuard] },
      { path: "supplier_account_statement",name:"view supplier_account_statement", component: SupplierAccountStatement},
      {
        path: "",
        beforeEnter: [PermissionGuard],
        children:
          [
            { path: "purchase-invoice-items/:purchaseInvoiceId", component: PurchaseInvoiceItemTable, name: "x_view purchase_invoice" },
            { path: "purchase-invoices", component: PurchaseInvoiceTable, name: "view purchase_invoice" },
          ]
      },
      {
        path: "",
        beforeEnter: [PermissionGuard],
        children:
          [
            { path: "purchase-return-invoice-items/:purchaseInvoiceId", component: PurchaseReturnInvoiceItemTable, name: "x_view purchase_return_invoice" },
            { path: "purchase-return-invoices", component: PurchaseReturnInvoiceTable, name: "view purchase_return_invoice", },
          ]
      }
    ]
  },
  {
    path: "",
    component: AuthLayout,
    beforeEnter: [
      GuestGuard
    ],
    children: [
      { path: "login", component: Login },
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    component: PageNotFound
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
export default router;