<template>
  <div class="purchase-invoice-container">
    <DeleteConfirmation
      @confirm="deletePurchaseInvoice"
      @closed="selectedPurchaseInvoice = null"
    />
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
    <Information :infos="infos" />
    <Filter @onStoreSelected="onStoreSelected" @onSupplierSelected="onSupplierSelected" :stores="stores" :suppliers="suppliers" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("PURCHASE_INVOICES") }}</span>
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
                data-target="#purchaseInvoiceFormModal"
                class="border text-secondary"
              >
                <i class="fa fa-plus" aria-hidden="true"></i>
              </button>
            </span>
            <span data-toggle="tooltip" data-placement="top" :title="$t('FILTER')">
              <button
                data-toggle="modal"
                data-target="#filter"
                class="border text-secondary"
              >
                <i class="fa fa-filter" aria-hidden="true"></i>
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
              <tr
                v-for="(purchaseInvoice, index) in purchaseInvoices"
                :key="purchaseInvoice.id"
              >
                <td>{{ purchaseInvoice.invoice_number }}</td>
                <td>
                  <div class="actions">
                    <span
                      v-if="!purchaseInvoice.is_approved"
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('EDIT')"
                    >
                      <button
                        @click="onEditClicked(purchaseInvoice, index)"
                        data-toggle="modal"
                        data-target="#purchaseInvoiceFormModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-edit" aria-hidden="true"></i>
                      </button>
                    </span>
                    <span
                      v-if="!purchaseInvoice.is_approved"
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DELETE')"
                    >
                      <button
                        @click="onDeleteClicked(purchaseInvoice, index)"
                        data-toggle="modal"
                        data-target="#deleteConfirmationModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                    </span>
                    <button
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('INVOICE_ITEMS')"
                      @click="routeToItemsPage(purchaseInvoice.id)"
                      class="border text-secondary"
                    >
                      <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </button>
                    <span
                      v-if="!purchaseInvoice.is_approved && currentShift"
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('APPROVE_INVOICE')"
                    >
                      <button
                        @click="onApproveClicked(purchaseInvoice, index)"
                        data-toggle="modal"
                        data-target="#approveInvoiceFormModal"
                        class="border text-secondary"
                      >
                        <i class="fas fa-check"></i>
                      </button>
                    </span>
                    <span
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DETAILS')"
                    >
                      <button
                        @click="onItemInfoClicked(purchaseInvoice)"
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
            :clickHandler="getPurchaseInvoices"
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
import purchaseInvoiceClient from "../../../../shared/http-clients/purchase-invoice/purchase-invoice-client";
import Paginate from "vuejs-paginate-next";
import PurchaseInvoiceForm from "./purchase-invoice-form.vue";
import ApproveInvoiceForm from "./approve-invoice-form.vue";
import purchaseInvoiceStore from "./purchase-invoice-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../../shared/components/delete-confirmation.vue";
import Information from "../../../../shared/components/information.vue";
import Filter from "./filter";
import { useRouter } from "vue-router";
export default {
  components: {
    Paginate,
    PurchaseInvoiceForm,
    DeleteConfirmation,
    ApproveInvoiceForm,
    Information,
    Filter,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
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
    const { t, locale } = useI18n({ useScope: "global" });
    const router = useRouter();
    provide("purchase_invoice_store", purchaseInvoiceStore);
    created();
    //Methods
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
    function onItemInfoClicked(purchaseInvoice) {
      let infos = [
        { header: "CREATED_AT", text: purchaseInvoice.created_at, textDateTime: true },
        { header: "BY", text: purchaseInvoice.added_by.name },
      ];
      if (purchaseInvoice.updated_by) {
        infos = infos.concat([
          {
            header: "UPDATED_AT",
            text: purchaseInvoice.updated_at,
            textDateTime: true,
          },
          { header: "BY", text: purchaseInvoice.updated_by.name },
        ]);
      }
      if (purchaseInvoice.is_approved) {
        infos = infos.concat([
          {
            header: "APPROVED_AT",
            text: purchaseInvoice.updated_at,
            textDateTime: true,
          },
          { header: "BY", text: purchaseInvoice.approved_by.name },
          {
            header: "DISCOUNT",
            text: `${purchaseInvoice.discount}${
              purchaseInvoice.is_discount_percent ? "%" : ""
            }`,
          },
          {
            header: "TAX",
            text: `${purchaseInvoice.tax}${purchaseInvoice.is_tax_percent ? "%" : ""}`,
          },
          { header: "NET_TOTAL_AMOUNT", text: purchaseInvoice.total_purchase_price },
          {
            header: "TOTAL_AMOUNT_BEFORE_DISCOUNT",
            text:
              Number.parseFloat(purchaseInvoice.total_purchase_price) +
              Number.parseFloat(getTaxValue(purchaseInvoice)),
          },
          {
            header: "TOTAL_AMOUNT_AFTER_DISCOUNT",
            text: getTotalAmountAfterDiscount(purchaseInvoice),
          },
          {
            header: "DEFERRED",
            text: purchaseInvoice.is_deferred ? "YES" : "NO",
            textTranslated: true,
          },
        ]);
        if (purchaseInvoice.is_deferred) {
          infos = infos.concat([
            { header: "PAID_AMOUNT", text: purchaseInvoice.paid_amount },
            {
              header: "REMAINING_AMOUNT",
              text:
                getTotalAmountAfterDiscount(purchaseInvoice) -
                purchaseInvoice.paid_amount,
            },
          ]);
        }
      }
      data.infos = infos;
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
        purchaseInvoiceStore.onApproveFormShow = !purchaseInvoiceStore.onApproveFormShow;
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
      data.purchaseInvoices.unshift(event);
    }
    function onUpdated(event) {
      data.purchaseInvoices[data.selectedPurchaseInvoiceIndex] = event;
      data.selectedPurchaseInvoice = null;
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
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.purchaseInvoices.splice(data.selectedPurchaseInvoiceIndex, 1);
          if (data.purchaseInvoices.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getPurchaseInvoices();
          }
          data.selectedPurchaseInvoice = null;
        })
        .catch((error) => {});
    }
    function onDeleteClicked(purchaseInvoice, index) {
      data.selectedPurchaseInvoice = purchaseInvoice;
      data.selectedPurchaseInvoiceIndex = index;
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
        ? (purchaseInvoice.discount / 100) * purchaseInvoice.total_purchase_price
        : purchaseInvoice.discount;
    }
    function created() {
      getPurchaseInvoices();
      getCurrentShift();
      getSuppliers();
      getStores();
    }
    return {
      ...toRefs(data),
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
      onSupplierSelected
    };
  },
};
</script>

<style lang="scss">
.purchase-invoice-container {
  padding-bottom: 50px;
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    flex-wrap: wrap;
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
      a {
        text-decoration: none;
      }
      td,
      th {
        width: 50%;
      }
    }
  }
}
</style>
