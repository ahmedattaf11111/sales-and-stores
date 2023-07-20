<template>
  <div class="sales-container">
    <SaleInvoiceForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedSaleInvoice="selectedSaleInvoice"
    />
    <div v-if="!saleInvoices.length" class="no-data-found text-center">
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
      <button
        :disabled="!hasPermission('create sale_invoice')"
        @click="onAddClicked()"
        data-toggle="modal"
        data-target="#saleInvoiceFormModal"
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
            <div class="header">{{ $t("RETURNS_INVOICES_TABLE") }}</div>
          </div>
          <div class="col-md-6 sec-sec">
            <div class="row">
              <div class="col-md-8">
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
                  :disabled="!hasPermission('create sale_invoice')"
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#saleInvoiceFormModal"
                  class="add text-secondary"
                >
                  {{ $t("ADD_NEW") }}
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
                  <span>{{ $t("INVOICE_CATEGORY") }}</span>
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
                  <span>{{ $t("CUSTOMER") }}</span>
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
                  <span>{{ $t("DELEGATE") }}</span>
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
                  <span>{{ $t("SALE_PRICE") }}</span>
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
                  <span>{{ $t("APPROVED_BY") }}</span>
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
                v-for="(saleInvoice, index) in saleInvoices"
                :key="saleInvoice.id"
              >
                <td>{{ saleInvoice.invoice_number }}</td>
                <td>
                  {{ saleInvoice.date }}
                </td>
                <td>
                  {{ saleInvoice.invoice_category.name }}
                </td>
                <td>
                  {{ saleInvoice.customer.account.name }}
                </td>
                <td>
                  {{ saleInvoice.delegate.account.name }}
                </td>
                <td>
                  {{ $t(saleInvoice.sale_price_type) }}
                </td>
                <td>
                  <span class="active">{{
                    saleInvoice.is_approved == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>
                  {{
                    saleInvoice.is_approved ? saleInvoice.approved_by.name : "-"
                  }}
                </td>
                <td>
                  {{
                    saleInvoice.is_approved
                      ? $i18n.locale == "ar"
                        ? saleInvoice.ar_updated_at
                        : saleInvoice.en_updated_at
                      : "-"
                  }}
                </td>
                <td>
                  <span class="active">{{
                    saleInvoice.is_tax_percent == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>{{ saleInvoice.tax }}</td>
                <td>
                  <span class="active">{{
                    saleInvoice.is_discount_percent == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>{{ saleInvoice.discount }}</td>
                <td>
                  {{ saleInvoice.total_sale_price }}
                </td>
                <td>{{ getTotalBeforeDiscount(saleInvoice) }}</td>
                <td>{{ getTotalAfterDiscount(saleInvoice) }}</td>
                <td>
                  <span class="active">{{
                    saleInvoice.is_deferred == 1 ? $t("YES") : $t("NO")
                  }}</span>
                </td>
                <td>
                  {{ saleInvoice.paid_amount }}
                </td>
                <td>
                  {{
                    getTotalAfterDiscount(saleInvoice) - saleInvoice.paid_amount
                  }}
                </td>
                <td>
                  <div class="actions">
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
                          v-if="!saleInvoice.is_approved"
                          class="text border-bottom"
                          style="padding-bottom: 10px"
                        >
                          <button
                            :disabled="!hasPermission('update sale_invoice')"
                            @click="onEditClicked(saleInvoice, index)"
                            data-toggle="modal"
                            data-target="#saleInvoiceFormModal"
                            class="border text-secondary"
                          >
                            <span>{{ $t("APPROVE") }}</span>
                            <i class="fa fa-edit" style="color: #2bd27f"></i>
                          </button>
                        </div>
                        <div
                          v-if="!saleInvoice.is_approved"
                          style="padding-top: 10px"
                        >
                          <button
                            :disabled="!hasPermission('delete sale_invoice')"
                            @click="onDeleteClicked(saleInvoice, index)"
                            data-toggle="modal"
                            data-target="#deleteConfirmationModal"
                            class="border text-secondary"
                          >
                            <span>{{ $t("DELETE") }}</span>
                            <i class="fa fa-trash text-danger"></i>
                          </button>
                        </div>
                        <div v-if="saleInvoice.is_approved">
                          <button
                            :disabled="!hasPermission('view sale_invoice')"
                            @click="onEditClicked(saleInvoice, index)"
                            data-toggle="modal"
                            data-target="#saleInvoiceFormModal"
                            class="border text-secondary"
                          >
                            <span>{{ $t("VIEW") }}</span>
                            <i class="fa fa-eye" style="color: #2bd27f"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <button
                    @click="onItemInfoClicked(saleInvoice)"
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
            :clickHandler="getSaleInvoices"
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
import authClient from "../../../shared/http-clients/auth-client";
import saleReturnInvoiceClient from "../../../shared/http-clients/sale-invoice/sale-return-invoice-client";
import Paginate from "vuejs-paginate-next";
import SaleInvoiceForm from "./sales-returns-invoices-form.vue";
import saleInvoiceStore from "./sales-returns-invoices-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";

export default {
  components: {
    Paginate,
    SaleInvoiceForm,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      currentPermissions: [],
      page: 1,
      saleInvoices: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedSaleInvoice: null,
      selectedSaleInvoiceIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("sale_invoice_store", saleInvoiceStore);
    created();
    //Methods
    function getTaxValue(saleInovice) {
      return saleInovice.is_tax_percent
        ? (saleInovice.tax / 100) * saleInovice.total_sale_price
        : saleInovice.tax;
    }
    function getDiscountValue(saleInovice) {
      return saleInovice.is_discount_percent
        ? (saleInovice.discount / 100) * saleInovice.total_sale_price
        : saleInovice.discount;
    }
    function getTotalBeforeDiscount(saleInvoice) {
      return (
        saleInvoice.total_sale_price +
        Number.parseFloat(getTaxValue(saleInvoice))
      );
    }
    function getTotalAfterDiscount(saleInovice) {
      return (
        saleInovice.total_sale_price +
        Number.parseFloat(getTaxValue(saleInovice)) -
        Number.parseFloat(getDiscountValue(saleInovice))
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

    function onAddClicked() {
      data.selectedSaleInvoice = null;
      //Make little delay to ensure that watcher that found in item form component
      // catch selectedItem input prop
      setTimeout(() => {
        saleInvoiceStore.onInvoiceShow = !saleInvoiceStore.onInvoiceShow;
      }, 1);
    }

    function showInvoiceMirror() {
      //Make little delay to ensure that watcher that found in store form component
      // catch selectedStore input prop
      setTimeout(() => {
        saleInvoiceStore.onFormShow = !saleInvoiceStore.onFormShow;
      }, 1);
    }
    function onEditClicked(saleInvoice, index) {
      data.selectedSaleInvoice = saleInvoice;
      data.selectedSaleInvoiceIndex = index;
      //Make little delay to ensure that watcher catch selectedStore input prop
      //in store form component
      setTimeout(() => {
        saleInvoiceStore.onInvoiceShow = !saleInvoiceStore.onInvoiceShow;
      }, 1);
    }
    function getSaleInvoices() {
      saleReturnInvoiceClient
        .getSaleInvoices(data.page, data.pageSize, data.text)
        .then((response) => {
          data.saleInvoices = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function deleteSaleInvoice() {
      saleReturnInvoiceClient
        .delete(data.selectedSaleInvoice.id)
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
          getSaleInvoices();
        })
        .catch((error) => {});
    }
    function onCreated(event) {
      data.page = 1;
      getSaleInvoices();
    }
    function onUpdated(event) {
      getSaleInvoices();
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getSaleInvoices();
      }, 500);
    }
    function onDeleteClicked(saleInvoice, index) {
      data.selectedSaleInvoice = saleInvoice;
      data.selectedSaleInvoiceIndex = index;
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
            deleteSaleInvoice();
          }
        });
    }
    function onItemInfoClicked(element) {
      swal({
        icon: "warning",
        confirmButtonText: t("OK"),
        title: t("LOG"),
        text: `${t("CREATED")} ${
          locale.value == "ar" ? element.ar_created_at : element.en_created_at
        } ${t("BY")} ${element.added_by.name} ${
          element.updated_by
            ? `| ${t("UPDATED")} ${
                locale.value == "ar"
                  ? element.ar_updated_at
                  : element.en_updated_at
              } ${t("BY")} ${element.updated_by.name}`
            : ""
        }`,
      });
    }

    //Commons
    function created() {
      getSaleInvoices();
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
    }
    return {
      ...toRefs(data),
      back,
      hasPermission,
      onAddClicked,
      getSaleInvoices,
      onItemInfoClicked,
      showInvoiceMirror,
      onEditClicked,
      getSaleInvoices,
      onCreated,
      onUpdated,
      search,
      deleteSaleInvoice,
      onDeleteClicked,
      getTotalBeforeDiscount,
      getTotalAfterDiscount,
    };
  },
};
</script>

<style lang="scss">
.sales-container {
  .no-data-found {
    margin-top: 200px;
    i {
      font-size: 40px !important;
      margin-bottom: 20px;
    }
    .submit {
      border: 1px solid #373063 !important;
      color: #373063 !important;
      margin-top: 25px !important;
      width: 90px;
      margin: 0 5px;
    }
  }
  padding: 30px 0;
  .shift-details {
    margin: 0 15px;
  }
  .close-shift,
  .shift-details {
    color: #2bd27f !important;
    position: relative;
    top: 4px;
    background: none;
    padding: 0;
    border: 0px solid #2bd27f !important;
    margin-bottom: 14px;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    display: flex;
    width: 22px;
    height: 22px;
    i {
      font-size: 12px !important;
    }
  }
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
}
</style>
