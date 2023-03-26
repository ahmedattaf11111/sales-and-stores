<template>
  <div class="sales-container">
    <DeleteConfirmation @confirm="deleteSaleInvoice" @closed="selectedSaleInvoice = null" />
    <SaleInvoiceMirrorForm />
    <SaleInvoiceForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedSaleInvoice="selectedSaleInvoice"
    />
    <Information :infos="infos" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("SALES_INVOICES") }}</span>
      </div>
    </div>
    <div class="px-4">
      <div class="table-container">
        <div class="controls">
          <div class="search">
            <input
              @keyup="search"
              v-model="text"
              type="text"
              :placeholder="$t('SEARCH')"
            />
            <i class="fa fa-search"></i>
          </div>
          <div class="actions">
            <span data-toggle="tooltip" data-placement="top" :title="$t('ADD')">
              <button
                @click="onAddClicked()"
                data-toggle="modal"
                data-target="#saleInvoiceFormModal"
                class="border text-secondary"
              >
                <i class="fas fa-plus"></i>
              </button>
            </span>

            <span
              data-toggle="tooltip"
              data-placement="top"
              :title="$t('INVOICE_MIRROR')"
            >
              <button
                @click="showInvoiceMirror()"
                data-toggle="modal"
                data-target="#saleInvoiceMirrorFormModal"
                class="border text-secondary"
              >
                <i class="fas fa-file-invoice"></i>
              </button>
            </span>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">{{ $t("INVOICE_NUMBER") }}</th>
                <th scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(saleInvoice, index) in saleInvoices" :key="saleInvoice.id">
                <td>{{ saleInvoice.invoice_number }}</td>
                <td>
                  <div class="actions">
                    <span v-if="!saleInvoice.is_approved" data-toggle="tooltip" data-placement="top" :title="$t('APPROVE')">
                      <button
                        @click="onEditClicked(saleInvoice, index)"
                        data-toggle="modal"
                        data-target="#saleInvoiceFormModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-check" aria-hidden="true"></i>
                      </button>
                    </span>
                    <span
                     v-if="!saleInvoice.is_approved"
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DELETE')"
                    >
                      <button
                        @click="onDeleteClicked(saleInvoice, index)"
                        data-toggle="modal"
                        data-target="#deleteConfirmationModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                    </span>
                    <span
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DETAILS')"
                    >
                      <button
                        @click="onItemInfoClicked(saleInvoice)"
                        data-toggle="modal"
                        data-target="#info"
                        class="border text-secondary"
                      >
                        <i class="fa fa-info" aria-hidden="true"></i>
                      </button>
                    </span>
              
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-1">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getSaleInvoices"
            :prevText="$t('PREV')"
            :nextText="$t('NEXT')"
          >
          </paginate>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import saleInvoiceClient from "../../../shared/http-clients/sale-invoice/sale-invoice-client";
import Paginate from "vuejs-paginate-next";
import SaleInvoiceMirrorForm from "./sales-invoices-mirror-form.vue";
import SaleInvoiceForm from "./sales-invoices-form.vue";
import saleInvoiceStore from "./sales-invoices-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import Information from "../../../shared/components/information.vue";

export default {
  components: {
    Paginate,
    SaleInvoiceMirrorForm,
    SaleInvoiceForm,
    DeleteConfirmation,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
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
    const { t, locale } = useI18n({ useScope: "global" });
    provide("sale_invoice_store", saleInvoiceStore);
    created();
    //Methods
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
      saleInvoiceClient
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
      saleInvoiceClient
        .delete(data.selectedSaleInvoice.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.saleInvoices.splice(data.selectedSaleInvoiceIndex, 1);
          if (data.saleInvoices.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getSaleInvoices();
          }
          data.selectedSaleInvoice = null;
        })
        .catch((error) => {});
    }
    function onCreated(event) {
      data.saleInvoices.unshift(event);
    }
    function onUpdated(event) {
      data.saleInvoices[data.selectedSaleInvoiceIndex] = event;
      data.selectedSaleInvoice = null;
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
    }
    function onItemInfoClicked(saleInvoice) {
      let infos = [
        { header: "CREATED_AT", text: saleInvoice.created_at, textDateTime: true },
        { header: "BY", text: saleInvoice.added_by.name },
      ];
      if (saleInvoice.approved_by) {
        infos = infos.concat([
          { header: "APPROVED_AT", text: saleInvoice.updated_at, textDateTime: true },
          { header: "BY", text: saleInvoice.approved_by.name },
        ]);
      }
      infos=infos.concat([
          { header: "INVOICE_DATE", text: saleInvoice.date },
          { header: "CUSTOMER", text: saleInvoice.customer.account.name },
          { header: "INVOICE_CATEGORY", text: saleInvoice.invoice_category.name },
          { header: "TOTAL_AMOUNT", text: saleInvoice.total_sale_price },
      ])
      data.infos = infos;
    }

    //Commons
    function created() {
      getSaleInvoices();
    }
    return {
      ...toRefs(data),
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
    };
  },
};
</script>

<style lang="scss">
.sales-container {
  padding-bottom: 50px;
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    flex-wrap: wrap[];
    justify-content: space-between;
    padding: 30px;
    .welcome {
      padding-top: 9px;
    }
    .title {
      * {
        color: #6c757d !important;
      }
      a {
        text-decoration: none;
        color: #868e96 !important;
        &:hover {
          color: #6c757d !important;
        }
      }
    }
  }
  .table-container {
    background: #ffffff;
    box-shadow: 0 5px 20px rgb(0 0 0 / 10%);
    padding: 30px;
    .controls {
      display: flex;
      justify-content: space-between;
      @media (max-width: 500px) {
        flex-direction: column;
      }
      body[dir="ltr"] & {
        .search {
          i {
            right: 25px;
          }
        }
      }
      body[dir="rtl"] & {
        .search {
          i {
            left: 25px;
          }
        }
      }
      .search {
        margin-bottom: 10px;
        i {
          position: relative;
          top: 1px;
          color: #888888;
        }
        input {
          padding: 4px 15px;
          border: 1px solid #dee2e6 !important;
          border-radius: 5px;
        }
      }
    }
    .actions {
      display: flex;
      a:hover {
        cursor: text;
      }
      button {
        width: 34px;
        height: 34px;
        background: none;
        margin: 3px 5px;
        border-radius: 5px;
      }
    }
    a:hover {
      cursor: pointer;
    }
    .active {
      a {
        color: #fff !important;
        background-color: #6d85fb !important;
        border-color: #dbdbdb !important;
      }
    }
    .page-link {
      padding: 3px 18px !important;
    }
    table {
      td,
      th {
        width: 50%;
      }
    }
  }
}
</style>
