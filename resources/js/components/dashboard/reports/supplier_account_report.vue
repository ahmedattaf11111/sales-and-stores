<template>
  <div class="supplier-account-statement-container">
    <div class="px-4 mb-4">
      <div class="table-container">
        <div class="search-container">
          <div class="row">
            <div class="search col-md-3">
              <label>{{ $t("SUPPLIER_DATA") }}</label>
              <select v-model="supplier_id" class="form-control">
                <option
                  v-for="supplier in suppliers"
                  :key="supplier.id"
                  :value="supplier.id"
                >
                  {{ supplier.account.name }}
                </option>
              </select>
            </div>
            <div class="search col-md-3">
              <label>{{ $t("REPORT_TYPE") }}</label>
              <select
                @change="reportShown = false"
                v-model="report_type"
                class="form-control"
              >
                <option value="1">{{ $t("TOTAL_ACCOUNT_STATEMENT") }}</option>
                <option value="2">{{ $t("PURCHASE_INVOICES") }}</option>
                <option value="3">
                  {{ $t("GENERAL_PURCHASE_RETURN_INVOICES") }}
                </option>
                <option value="4">{{ $t("COLLECT_EXCHANGE_MONEY") }}</option>
              </select>
            </div>
            <div class="search col-md-3">
              <label>{{ $t("FROM_DATE") }}</label>
              <input v-model="from_date" type="date" class="form-control" />
            </div>
            <div class="search col-md-3">
              <label>{{ $t("TO_DATE") }}</label>
              <input v-model="to_date" type="date" class="form-control" />
            </div>
          </div>
        </div>
        <div class="mt-5 mb-3 d-flex justify-content-center">
          <button
            :disabled="!supplier_id || !report_type || !from_date || !to_date"
            @click="getReport"
            class="btn submit mx-2"
          >
            {{ $t("SHOW_REPORT") }}
          </button>
        </div>
      </div>
    </div>
    <div v-if="reportShown && report_type == 1" class="px-4">
      <div class="table-container">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="head">
                <th scope="col">
                  <span>{{ $t("SUPPLIER_CODE") }}</span>
                </th>
                <th scope="col">
                  <span>{{ $t("START_BALANCE") }}</span>
                </th>
                <th scope="col">
                  <span>{{ $t("PURCHASE_INVOICE_COUNT") }}</span>
                </th>
                <th scope="col">
                  <span>{{ $t("PURCHASE_RETURN_INVOICE_COUNT") }}</span>
                </th>
                <th scope="col">
                  <span>{{ $t("TOTAL_EXCHANGE_AMOUNT") }}</span>
                </th>
                <th scope="col">
                  <span>{{ $t("TOTAL_COLLECT_AMOUNT") }}</span>
                </th>
                <th scope="col">
                  <span>{{ $t("SUPPLIER_TOTAL_BALANCE") }}</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="value"
                v-for="(data, index) in totalAccountReportItems"
                :key="data.id"
              >
                <td>{{ data.supplier_id }}</td>
                <td>{{ data.start_balance }}</td>
                <td>
                  {{
                    $t("count_with_total", {
                      count: data.purchase_invoices_count,
                      total: data.purchase_invoices_amount,
                    })
                  }}
                </td>
                <td>
                  {{
                    $t("count_with_total", {
                      count: data.purchase_invoice_retruns_count,
                      total: Math.abs(data.purchase_invoice_retruns_amount),
                    })
                  }}
                </td>
                <td>
                  {{ data.total_exchange_amount }}
                </td>
                <td>
                  {{ Math.abs(data.total_collect_amount) }}
                </td>
                <td>
                  <template v-if="data.total_amount > 0">
                    {{ $t("CREDITOR") }}
                  </template>
                  <template v-if="data.total_amount < 0">
                    {{ $t("DEPTOR") }}
                  </template>
                  ({{ Math.abs(data.total_amount) }})
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div
      v-if="!purchaseInvoices.length && reportShown && report_type == 2"
      class="no-data-found text-center"
    >
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
    </div>
    <template v-else>
      <div v-if="reportShown && report_type == 2" class="px-4">
        <div class="table-container">
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
                </tr>
              </thead>
              <tbody>
                <tr
                  class="value"
                  v-for="(data, index) in purchaseInvoices"
                  :key="data.id"
                >
                  <td>{{ data.purchase_invoice.invoice_number }}</td>
                  <td>{{ data.purchase_invoice.date }}</td>
                  <td>
                    {{
                      data.purchase_invoice.is_deferred == 1
                        ? $t("YES")
                        : $t("NO")
                    }}
                  </td>
                  <td>
                    {{
                      getPurchaseTotalAmountAfterDiscount(data.purchase_invoice)
                    }}
                  </td>
                  <td>{{ `${data.account_amount}` }}</td>
                  <td>
                    {{
                      getPurchaseTotalAmountAfterDiscount(
                        data.purchase_invoice
                      ) - data.account_amount
                    }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 d-flex justify-content-end">
            <paginate
              v-model="purchaseInvoicePage"
              :pageCount="purchaseInvoicePageCount"
              :clickHandler="getPurchaseInvoiceReport"
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
    </template>
    <div
      v-if="!purchaseReturnInvoices.length&&reportShown && report_type == 3"
      class="no-data-found text-center"
    >
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
    </div>
    <template v-else>
      <div v-if="reportShown && report_type == 3" class="px-4">
        <div class="table-container">
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
                </tr>
              </thead>
              <tbody>
                <tr
                  class="value"
                  v-for="(data, index) in purchaseReturnInvoices"
                  :key="data.id"
                >
                  <td>{{ data.purchase_invoice.invoice_number }}</td>
                  <td>{{ data.purchase_invoice.date }}</td>
                  <td>
                    {{
                      data.purchase_invoice.is_deferred == 1
                        ? $t("YES")
                        : $t("NO")
                    }}
                  </td>
                  <td>
                    {{
                      getPurchaseTotalAmountAfterDiscount(data.purchase_invoice)
                    }}
                  </td>
                  <td>
                    {{ `${Math.abs(data.account_amount)}` }}
                  </td>
                  <td>
                    {{
                      getPurchaseTotalAmountAfterDiscount(
                        data.purchase_invoice
                      ) - Math.abs(data.account_amount)
                    }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 d-flex justify-content-end">
            <paginate
              v-model="purchaseReturnInvoicePage"
              :pageCount="purchaseReturnInvoicePageCount"
              :clickHandler="getPurchaseReturnInvoiceReport"
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
    </template>
    <div v-if="!moveItems.length&&reportShown && report_type == 4" class="no-data-found text-center">
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
    </div>
    <template v-else>
      <div v-if="reportShown && report_type == 4" class="px-4">
        <div class="table-container">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="head">
                  <th scope="col">
                    <span>{{ $t("RECEIPT_NUMBER") }}</span>
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
                    <span>{{ $t("DATE") }}</span>
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
                    <span>{{ $t("MOVE_TYPE") }}</span>
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
                    <span>{{ $t("NOTE") }}</span>
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
                </tr>
              </thead>
              <tbody>
                <tr
                  class="value"
                  v-for="(data, index) in moveItems"
                  :key="data.id"
                >
                  <td>
                    {{
                      data.type == "COLLECT"
                        ? data.collect_receipt_number
                        : data.exchange_receipt_number
                    }}
                  </td>
                  <td>{{ data.created_at }}</td>
                  <td>{{ data.move_type.name }}</td>
                  <td>{{ Math.abs(data.account_amount) }}</td>
                  <td>{{ data.note }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 d-flex justify-content-end">
            <paginate
              v-model="movePage"
              :pageCount="movePageCount"
              :clickHandler="getCollectExchangeReport"
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
    </template>
  </div>
</template>
<script>
import supplierReportClient from "../../../shared/http-clients/supplier-report-client";
import Paginate from "vuejs-paginate-next";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import authClient from "../../../shared/http-clients/auth-client";

export default {
  components: {
    Paginate,
  },
  setup() {
    const data = reactive({
      currentPermissions: [],
      totalAccountReportItems: [],
      text: "",
      reportShown: false,
      timeout: null,
      suppliers: [],
      supplier_id: null,
      report_type: "",
      to_date: "",
      from_date: "",
      purchaseInvoices: [],
      purchaseInvoicePageSize: 6,
      purchaseInvoicePage: 1,
      purchaseInvoicePageCount: 0,

      purchaseReturnInvoices: [],
      purchaseReturnInvoicePageSize: 6,
      purchaseReturnInvoicePage: 1,
      purchaseReturnInvoicePageCount: 0,

      moveItems: [],
      movePageSize: 6,
      movePage: 1,
      movePageCount: 0,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    created();
    //Methods
    function getPurchaseTotalAmountAfterDiscount(purchaseInvoice) {
      return (
        Number.parseFloat(purchaseInvoice.total_purchase_price) +
        Number.parseFloat(getPurchaseTaxValue(purchaseInvoice)) -
        Number.parseFloat(getPurchaseDiscountValue(purchaseInvoice))
      );
    }
    function getPurchaseTaxValue(purchaseInvoice) {
      return purchaseInvoice.is_tax_percent
        ? (purchaseInvoice.tax / 100) * purchaseInvoice.total_purchase_price
        : purchaseInvoice.tax;
    }
    function getPurchaseDiscountValue(purchaseInvoice) {
      return purchaseInvoice.is_discount_percent
        ? (purchaseInvoice.discount / 100) *
            purchaseInvoice.total_purchase_price
        : purchaseInvoice.discount;
    }
    function hasPermission(permission) {
      let filterResult = data.currentPermissions.filter(
        (perm) => perm.name == permission || perm.name == "super admin"
      );
      return filterResult.length > 0 ? true : false;
    }
    function getReport() {
      data.reportShown = true;
      if (data.report_type == 1) {
        getTotalAccountStatementReport();
      }
      if (data.report_type == 2) {
        getPurchaseInvoiceReport();
      }
      if (data.report_type == 3) {
        getPurchaseReturnInvoiceReport();
      }
      if (data.report_type == 4) {
        getCollectExchangeReport();
      }
    }
    function getTotalAccountStatementReport() {
      supplierReportClient
        .getTotalAccountStatementReport(
          data.supplier_id,
          data.from_date,
          data.to_date
        )
        .then((response) => {
          data.totalAccountReportItems = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getPurchaseInvoiceReport() {
      supplierReportClient
        .getPurchaseInvoiceReport(
          data.purchaseInvoicePage,
          data.purchaseInvoicePageSize,
          data.supplier_id,
          data.from_date,
          data.to_date
        )
        .then((response) => {
          data.purchaseInvoices = response.data.data;
          data.purchaseInvoicePageCount = Math.ceil(
            response.data.total / data.purchaseInvoicePageSize
          );
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getPurchaseReturnInvoiceReport() {
      supplierReportClient
        .getPurchaseReturnInvoiceReport(
          data.purchaseReturnInvoicePage,
          data.purchaseReturnInvoicePageSize,
          data.supplier_id,
          data.from_date,
          data.to_date
        )
        .then((response) => {
          data.purchaseReturnInvoices = response.data.data;
          data.purchaseReturnInvoicePageCount = Math.ceil(
            response.data.total / data.purchaseReturnInvoicePageSize
          );
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getCollectExchangeReport() {
      supplierReportClient
        .getCollectExchangeReport(
          data.movePage,
          data.movePageSize,
          data.supplier_id,
          data.from_date,
          data.to_date
        )
        .then((response) => {
          data.moveItems = response.data.data;
          data.movePageCount = Math.ceil(
            response.data.total / data.movePageSize
          );
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    //Commons
    function created() {
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
      supplierReportClient.getSuppliers().then((res) => {
        data.suppliers = res.data;
      });
    }
    return {
      ...toRefs(data),
      getCollectExchangeReport,
      getPurchaseInvoiceReport,
      getPurchaseReturnInvoiceReport,
      getPurchaseTotalAmountAfterDiscount,
      getReport,
      getTotalAccountStatementReport,
      hasPermission,
    };
  },
};
</script>

<style lang="scss">
.supplier-account-statement-container {
  .search {
    select,
    input {
      padding: 10px;
    }
  }
  padding: 30px 0;
  .submit {
    background: #373063 !important;
    color: #fff !important;
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
  .add-new-shift {
    margin-top: 200px;
    i {
      font-size: 40px !important;
      margin-bottom: 20px;
    }
    .submit {
      border: 1px solid #373063 !important;
      color: #373063 !important;
      margin-top: 25px !important;
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
