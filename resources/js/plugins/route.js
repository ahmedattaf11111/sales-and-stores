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
import TreasuryTransactionTable from '../components/dashboard/treasury-transaction/treasury-transaction-table';
import AdminPanelSettings from '../components/dashboard/admin-panel-settings';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
import PageNotFound from "../shared/components/page-not-found";
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
      { path: "treasuries", component: TreasuryTable },
      { path: "invoice-categories", component: InvoiceCategoryTable },
      { path: "item-categories", component: ItemCategoryTable },
      { path: "stores", component: StoreTable },
      { path: "unit-of-measures", component: UnitOfMeasureTable },
      { path: "items", component: ItemTable },
      { path: "accounts", component: AccountTable },
      { path: "customers", component: CustomerTable },
      { path: "delegates", component: DelegateTable },
      { path: "supplier-categories", component: SupplierCategoryTable },
      { path: "suppliers", component: SupplierTable },
      { path: "purchase-invoices", component: PurchaseInvoiceTable },
      { path: "purchase-return-invoices", component: PurchaseReturnInvoiceTable },
      { path: "purchase-invoice-items/:purchaseInvoiceId", component: PurchaseInvoiceItemTable },
      { path: "purchase-return-invoice-items/:purchaseInvoiceId", component: PurchaseReturnInvoiceItemTable },
      { path: "admins", component: AdminTable },
      { path: "shifts", component: ShiftTable },
      { path: "treasury-transactions", component: TreasuryTransactionTable },
      { path: "sales-invoices", component: SaleInvoiceTable },
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