<template>
  <div class="purchase-invoice-container">
    <PurchaseInvoiceForm
      @created="onCreated"
      @updated="onUpdated"
      :stores="stores"
      :suppliers="suppliers"
      :selectedPurchaseInvoice="selectedPurchaseInvoice"
    />
    <ApproveInvoiceForm
      @onApproved="onUpdated"
      :selectedPurchaseInvoice="selectedPurchaseInvoice"
    />
    <Filter
      @onStoreSelected="onStoreSelected"
      @onSupplierSelected="onSupplierSelected"
      :stores="stores"
      :suppliers="suppliers"
    />
    <div v-if="!purchaseInvoices.length" class="no-data-found text-center">
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
      <button
        :disabled="!hasPermission('create purchase_invoice')"
        @click="onAddClicked()"
        data-toggle="modal"
        data-target="#purchaseInvoiceFormModal"
        class="btn submit mt-3"
      >
        {{ $t("ADD_NEW") }}
      </button>
      <button v-if="text" @click="back" class="btn submit mt-3">
        {{ $t("BACK") }}
      </button>
    </div>
    <div v-else class="px-4">
      <div class="table-container">
        <div class="row">
          <div class="col-md-6">
            <div class="header">{{ $t("PURCHASE_INVOICE_TABLE") }}</div>
          </div>
          <div class="col-md-6 sec-sec">
            <div class="row">
              <div class="col-md-7">
                <div class="controls mb-2">
                  <div class="search">
                    <div class="icon">
                      <i class="fa fa-search"></i>
                      <span class="vert-line"></span>
                    </div>
                    <input
                      @keyup="search"
                      v-model="text"
                      type="text"
                      :placeholder="$t('SEARCH')"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <button
                  :disabled="!hasPermission('create purchase_invoice')"
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#purchaseInvoiceFormModal"
                  class="add text-secondary"
                >
                  {{ $t("ADD_NEW") }}
                </button>
              </div>
              <div class="col-md-1">
                <button
                  data-toggle="modal"
                  data-target="#filter"
                  class="filter text-secondary"
                >
                  <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="head">
                <th scope="col">
                  <span>{{ $t("INVOICE_NUMBER") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("INVOICE_DATE") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("SUPPLIER") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("STORE") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("APPROVED") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("BY") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("APPROVED") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("PERCENT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("TAX") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("PERCENT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("DISCOUNT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("TOTAL_AMOUNT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("TOTAL_AMOUNT_AFTER_TAX") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("TOTAL_AMOUNT_AFTER_DISCOUNT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("DEFERRED") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("PAID_AMOUNT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("REMAINING_AMOUNT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">{{ $t("ACTIONS") }}</th>
                <th class="text-center" scope="col">{{ $t("INFORMATION") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="value"
                v-for="(purchaseInvoice, index) in purchaseInvoices"
                :key="purchaseInvoice.id"
              >
                <td>{{ purchaseInvoice.invoice_number }}</td>
                <td>{{ purchaseInvoice.date }}</td>
                <td>{{ purchaseInvoice.supplier.account.name }}</td>
                <td>{{ purchaseInvoice.store.name }}</td>
                <td>
                  <span class="active">{{
                    purchaseInvoice.is_approved == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>
                  {{
                    purchaseInvoice.is_approved
                      ? purchaseInvoice.approved_by.name
                      : "-"
                  }}
                </td>
                <td>
                  {{
                    purchaseInvoice.is_approved
                      ? $i18n.locale == "ar"
                        ? purchaseInvoice.ar_updated_at
                        : purchaseInvoice.en_updated_at
                      : "-"
                  }}
                </td>
                <td>
                  <span class="active">{{
                    purchaseInvoice.is_tax_percent == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>{{ purchaseInvoice.tax }}</td>
                <td>
                  <span class="active">{{
                    purchaseInvoice.is_discount_percent == 1
                      ? $t("YES")
                      : $t("NO")
                  }}</span>
                </td>
                <td>{{ purchaseInvoice.discount }}</td>
                <td>
                  {{ purchaseInvoice.total_purchase_price }}
                </td>
                <td>{{ getTotalBeforeDiscount(purchaseInvoice) }}</td>
                <td>{{ getTotalAfterDiscount(purchaseInvoice) }}</td>
                <td>
                  <span class="active">{{
                    purchaseInvoice.is_deferred == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>
                  {{ purchaseInvoice.paid_amount }}
                </td>
                <td>
                  {{
                    getTotalAfterDiscount(purchaseInvoice) -
                    purchaseInvoice.paid_amount
                  }}
                </td>
                <td>
                  <div class="dropdown commands">
                    <button
                      class="btn dropdown-toggle"
                      type="button"
                      id="dropdownMenuButton"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      {{ $t("COMMANDS") }}
                    </button>
                    <div
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton"
                    >
                      <div
                        v-if="!purchaseInvoice.is_approved"
                        class="text border-bottom"
                        style="padding-bottom: 10px"
                      >
                        <button
                          :disabled="!hasPermission('update purchase_invoice')"
                          @click="onEditClicked(purchaseInvoice, index)"
                          data-toggle="modal"
                          data-target="#purchaseInvoiceFormModal"
                          class="text-secondary"
                        >
                          <span>{{ $t("EDIT") }}</span>
                          <i class="fa fa-edit" style="color: #2bd27f"></i>
                        </button>
                      </div>
                      <div
                        v-if="!purchaseInvoice.is_approved && currentShift"
                        class="text border-bottom"
                        style="padding-bottom: 10px; padding-top: 10px"
                      >
                        <button
                          :disabled="!hasPermission('approve purchase_invoice')"
                          @click="onApproveClicked(purchaseInvoice, index)"
                          data-toggle="modal"
                          data-target="#approveInvoiceFormModal"
                          class="text-secondary"
                        >
                          <span>{{ $t("APPROVE_INVOICE") }}</span>
                          <i class="fas fa-check" style="color: #2bd27f"></i>
                        </button>
                      </div>
                      <div
                        class="text border-bottom"
                        v-if="!purchaseInvoice.is_approved"
                        style="padding-bottom: 10px; padding-top: 10px"
                      >
                        <button
                          :disabled="!hasPermission('delete purchase_invoice')"
                          @click="onDeleteClicked(purchaseInvoice, index)"
                          data-toggle="modal"
                          data-target="#deleteConfirmationModal"
                          class="border text-secondary"
                        >
                          <span>{{ $t("DELETE") }}</span>
                          <i class="fa fa-trash text-danger"></i>
                        </button>
                      </div>
                      <div
                        class="text"
                        :style="
                          !purchaseInvoice.is_approved
                            ? 'padding-top: 10px'
                            : ''
                        "
                      >
                        <button
                          @click="routeToItemsPage(purchaseInvoice.id)"
                          class="border text-secondary"
                        >
                          <span>{{ $t("ITEMS") }}</span>
                          <i class="fa fa-arrow-left" aria-hidden="true" style="color: #2bd27f"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <button
                    @click="onItemInfoClicked(purchaseInvoice)"
                    data-toggle="modal"
                    data-target="#info"
                    class="info"
                  >
                    <i class="fa fa-info"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 d-flex justify-content-end">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getPurchaseInvoices"
            :prevText="`<i class='fa fa-arrow-${
              $i18n.locale == 'en' ? 'left' : 'right'
            }'></i>`"
            :nextText="`<i class='fa fa-arrow-${
              $i18n.locale == 'en' ? 'right' : 'left'
            }'></i>`"
          >
          </paginate>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import authClient from "../../../../shared/http-clients/auth-client";
import purchaseInvoiceClient from "../../../../shared/http-clients/purchase-invoice/purchase-invoice-client";
import Paginate from "vuejs-paginate-next";
import PurchaseInvoiceForm from "./purchase-invoice-form.vue";
import ApproveInvoiceForm from "./approve-invoice-form.vue";
import purchaseInvoiceStore from "./purchase-invoice-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import Filter from "./filter";
import { useRouter } from "vue-router";
export default {
  components: {
    Paginate,
    PurchaseInvoiceForm,
    ApproveInvoiceForm,
    Filter,
  },
  setup() {
    const data = reactive({
      pageSize: 20,
      currentPermissions: [],
      page: 1,
      purchaseInvoices: [],
      infos: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedPurchaseInvoice: null,
      selectedPurchaseInvoiceIndex: 0,
      currentShift: null,
      suppliers: [],
      stores: [],
      store_id: "",
      supplier_id: "",
    });
    const toast = inject("toast");
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    const router = useRouter();
    provide("purchase_invoice_store", purchaseInvoiceStore);
    created();
    //Methods
    function getTaxValue(purchaseInvoice) {
      return purchaseInvoice.is_tax_percent
        ? (purchaseInvoice.tax / 100) * purchaseInvoice.total_purchase_price
        : purchaseInvoice.tax;
    }
    function getDiscountValue(purchaseInvoice) {
      return purchaseInvoice.is_discount_percent
        ? (purchaseInvoice.discount / 100) *
            purchaseInvoice.total_purchase_price
        : purchaseInvoice.discount;
    }
    function getTotalBeforeDiscount(saleInvoice) {
      return (
        saleInvoice.total_purchase_price +
        Number.parseFloat(getTaxValue(saleInvoice))
      );
    }
    function getTotalAfterDiscount(purchaseInvoice) {
      return (
        purchaseInvoice.total_purchase_price +
        Number.parseFloat(getTaxValue(purchaseInvoice)) -
        Number.parseFloat(getDiscountValue(purchaseInvoice))
      );
    }

    function back() {
      data.text = "";
      search();
    }
    function hasPermission(permission) {
      let filterResult = data.currentPermissions.filter(
        (perm) => perm.name == permission || perm.name == "super admin"
      );
      return filterResult.length > 0 ? true : false;
    }

    function onStoreSelected(event) {
      data.store_id = event;
      data.page = 1;
      getPurchaseInvoices();
    }
    function onSupplierSelected(event) {
      data.supplier_id = event;
      data.page = 1;
      getPurchaseInvoices();
    }
    function getTotalAmountAfterDiscount(purchaseInvoice) {
      return (
        Number.parseFloat(purchaseInvoice.total_purchase_price) +
        Number.parseFloat(getTaxValue(purchaseInvoice)) -
        Number.parseFloat(getDiscountValue(purchaseInvoice))
      );
    }
    function onItemInfoClicked(element) {
      swal({
        icon: "warning",
        confirmButtonText: t("OK"),
        title: t("LOG"),
        text: `${t("CREATED")} ${
          locale.value == "ar" ? element.ar_created_at : element.en_created_at
        } ${t("BY")} ${element.added_by.name}`,
      });
    }
    function routeToItemsPage(purchaseInvoiceId) {
      router.push(`/purchase-invoice-items/${purchaseInvoiceId}`);
    }
    function onAddClicked() {
      data.selectedPurchaseInvoice = null;
      //Make little delay to ensure that watcher that found in purchaseInvoice form component
      // catch selectedPurchaseInvoice input prop
      setTimeout(() => {
        purchaseInvoiceStore.onFormShow = !purchaseInvoiceStore.onFormShow;
      }, 1);
    }
    function onEditClicked(purchaseInvoice, index) {
      data.selectedPurchaseInvoice = purchaseInvoice;
      data.selectedPurchaseInvoiceIndex = index;
      //Make little delay to ensure that watcher catch selectedPurchaseInvoice input prop
      //in purchaseInvoice form component
      setTimeout(() => {
        purchaseInvoiceStore.onFormShow = !purchaseInvoiceStore.onFormShow;
      }, 1);
    }
    function onApproveClicked(purchaseInvoice, index) {
      data.selectedPurchaseInvoice = purchaseInvoice;
      data.selectedPurchaseInvoiceIndex = index;
      //Make little delay to ensure that watcher catch selectedPurchaseInvoice input prop
      //in approveInvoice form component
      setTimeout(() => {
        purchaseInvoiceStore.onApproveFormShow =
          !purchaseInvoiceStore.onApproveFormShow;
      }, 1);
    }
    function getPurchaseInvoices() {
      purchaseInvoiceClient
        .getPurchaseInvoices(
          data.page,
          data.pageSize,
          data.text,
          data.store_id,
          data.supplier_id
        )
        .then((response) => {
          data.purchaseInvoices = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.page=1;
      getPurchaseInvoices();
    }
    function onUpdated(event) {
      getPurchaseInvoices();
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getPurchaseInvoices();
      }, 500);
    }
    function deletePurchaseInvoice() {
      purchaseInvoiceClient
        .delete(data.selectedPurchaseInvoice.id)
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),

            icon: "success",
            title: t("SUCCESS"),
            text: t("DELETED_SUCCESSFULLY"),
          });
          if (data.page > 1 && data.items.length == 1) {
            data.page--;
          }
          getPurchaseInvoices();
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function onDeleteClicked(purchaseInvoice, index) {
      data.selectedPurchaseInvoice = purchaseInvoice;
      data.selectedPurchaseInvoiceIndex = index;
      swal
        .fire({
          title: t("Are you sure"),
          text: t("You will not be able to revert this"),
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: t("YES"),
          cancelButtonText: t("NO"),
        })
        .then((res) => {
          if (res.value) {
            deletePurchaseInvoice();
          }
        });
    }
    //Commons
    function getSuppliers() {
      purchaseInvoiceClient.getSuppliers().then((response) => {
        data.suppliers = response.data;
      });
    }
    function getStores() {
      purchaseInvoiceClient.getStores().then((response) => {
        data.stores = response.data;
      });
    }
    function getCurrentShift() {
      purchaseInvoiceClient
        .getCurrentShift()
        .then((response) => {
          data.currentShift = response.data;
        })
        .catch((error) => {});
    }
    function getTaxValue(purchaseInvoice) {
      return purchaseInvoice.is_tax_percent
        ? (purchaseInvoice.tax / 100) * purchaseInvoice.total_purchase_price
        : purchaseInvoice.tax;
    }
    function getDiscountValue(purchaseInvoice) {
      return purchaseInvoice.is_discount_percent
        ? (purchaseInvoice.discount / 100) *
            purchaseInvoice.total_purchase_price
        : purchaseInvoice.discount;
    }
    function created() {
      getPurchaseInvoices();
      getCurrentShift();
      getSuppliers();
      getStores();
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
    }
    return {
      ...toRefs(data),
      back,
      getTotalAfterDiscount,
      getTotalBeforeDiscount,
      hasPermission,
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      onApproveClicked,
      getPurchaseInvoices,
      onCreated,
      onUpdated,
      search,
      onDeleteClicked,
      deletePurchaseInvoice,
      routeToItemsPage,
      onStoreSelected,
      onSupplierSelected,
    };
  },
};
</script>

<style lang="scss">
.purchase-invoice-container {
  padding: 30px 0;
  .filter {
    margin-top: 8px;
    border: unset !important;
    background: none !important;
    i {
      color: #373757 !important;
      font-size: 20px !important;
    }
  }
  .info {
    border-radius: 50%;
    border: unset !important;
    width: 24px;
    height: 23px;
    background: #e7fbf0;
    color: #2bd27f !important;
    i {
      font-size: 11px !important;
    }
  }
  .table-container {
    .data-table {
      margin-bottom: 30px;
      font-size: 15px !important;
    }
    .header {
      font-size: 15px !important;
    }

    input::placeholder {
      font-size: 14px;
      color: #b9b9b9;
      position: relative;
      top: 2px;
    }
    background: #ffffff;
    padding: 30px;
    .top-sec {
      display: flex;
      justify-content: space-between;
    }
    .sec-sec {
      margin-bottom: 33px;
    }
    .controls {
      .search {
        display: flex;
        align-items: center;
        body[dir="ltr"] & {
          .icon {
            .vert-line {
              margin-left: 17px;
              height: 23px;
              border-right: 1px solid #091023 !important;
            }
            display: flex;
            align-items: center;
            padding: 6px 0px 9px 22px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-left: 1px solid #091023 !important;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
          }
          input {
            width: 100%;
            padding: 8px 15px;
            border: 1px solid #dee2e6 !important;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-right: 1px solid #091023 !important;
            border-left: none !important;
            width: 100%;
          }
        }
        body[dir="rtl"] & {
          .icon {
            .vert-line {
              margin-right: 17px;
              height: 23px;
              border-left: 1px solid #091023 !important;
            }
            display: flex;
            align-items: center;
            padding: 6px 22px 9px 0px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-right: 1px solid #091023 !important;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
          }
          input {
            width: 100%;
            padding: 8px 15px;
            border: 1px solid #dee2e6 !important;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-left: 1px solid #091023 !important;
            border-right: none !important;
            width: 100%;
          }
        }
      }
    }
    .add {
      color: #fff !important;
      padding: 8px 25px;
      border-radius: 50px;
      font-size: 13px !important;
      background: #373063 !important;
      border: 1px solid #373063 !important;
    }

    a:hover {
      cursor: pointer;
    }
    .pagination {
      .active {
        a {
          color: #fff !important;
          background-color: #00d82c !important;
          border-color: #dbdbdb !important;
          box-shadow: 0 5px 10px rgba(0, 216, 44, 0.3) !important;
        }
      }
      .page-item .page-link {
        border-radius: 4px;
        border: unset;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #373757;
      }
    }
    table {
      .active {
        font-weight: 300;
        width: 85px;
        color: #2bd27f !important;
        background: #e7fbf0;
        border-radius: 2px;
        font-size: 12px !important;
        text-align: center;
        display: inline-block;
        padding-top: 1px;
      }
      td,
      th {
        vertical-align: middle;
        white-space: nowrap;
      }
      tr.head {
        border: 1px solid #f9f9f9 !important;
      }
      tr.value td:not(.first) {
        border-bottom: 13px solid #fff;
      }
      tr.value {
        background: #f9f9f9;
      }
      td {
        padding: 8px 12px !important;
      }
      th {
        padding: 12px !important;
      }
    }
  }
  .commands {
    .dropdown-toggle {
      padding: 0;
      &:hover {
        border: unset !important;
      }
    }
    .dropdown-menu {
      .text:first {
        padding-bottom: 8px;
      }
      button {
        display: flex;
        width: 100%;
        background: none;
        border: unset !important;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
      }
    }
  }
  .no-data-found {
    margin-top: 200px;
    i {
      font-size: 40px !important;
      margin-bottom: 20px;
    }
    .submit {
      width: 90px;
      margin: 0 5px;
      border: 1px solid #373063 !important;
      color: #373063 !important;
      margin-top: 25px !important;
    }
  }
}
</style>
