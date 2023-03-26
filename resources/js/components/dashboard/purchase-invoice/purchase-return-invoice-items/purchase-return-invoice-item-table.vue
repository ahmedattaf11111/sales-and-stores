<template>
  <div class="purchase-invoice-item-container">
    <DeleteConfirmation
      @confirm="deletePurchaseInvoiceItem"
      @closed="selectedPurchaseInvoiceItem = null"
    />
    <PurchaseInvoiceItemForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedPurchaseInvoiceItem="selectedPurchaseInvoiceItem"
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
        <span>{{ $t("PURCHASE_INVOICE_ITEMS") }}</span>
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
                :disabled="isPurchaseInvoiceApproved"
                @click="onAddClicked()"
                data-toggle="modal"
                data-target="#purchaseReturnInvoiceItemFormModal"
                class="border text-secondary"
              >
                <i class="fa fa-plus" aria-hidden="true"></i>
              </button>
            </span>
            <button
              data-toggle="tooltip"
              data-placement="top"
              :title="$t('GENERAL_PURCHASE_RETURN_INVOICES')"
              @click="routeToPurchaseInvoicesPage"
              class="border text-secondary"
            >
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">{{ $t("NAME") }}</th>
                <th scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(purchaseInvoiceItem, index) in purchaseInvoiceItems"
                :key="purchaseInvoiceItem.id"
              >
                <td>{{ purchaseInvoiceItem.item.name }}</td>
                <td>
                  <div class="actions">
                    <span
                      v-if="!isPurchaseInvoiceApproved"
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DELETE')"
                    >
                      <button
                        @click="onDeleteClicked(purchaseInvoiceItem, index)"
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
                        @click="onItemInfoClicked(purchaseInvoiceItem)"
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
            :clickHandler="getPurchaseInvoiceItems"
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
import purchaseInvoiceItemClient from "../../../../shared/http-clients/purchase-invoice/purchase-return-invoice-item-client";
import Paginate from "vuejs-paginate-next";
import PurchaseInvoiceItemForm from "./purchase-return-invoice-item-form.vue";
import purchaseInvoiceItemStore from "./purchase-return-invoice-item-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../../shared/components/delete-confirmation.vue";
import Information from "../../../../shared/components/information.vue";
import { useRoute, useRouter } from "vue-router";
export default {
  components: {
    Paginate,
    PurchaseInvoiceItemForm,
    DeleteConfirmation,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      purchaseInvoiceItems: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedPurchaseInvoiceItem: null,
      selectedPurchaseInvoiceItemIndex: 0,
      infos: [],
      isPurchaseInvoiceApproved: false,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    const route = useRoute();
    const router = useRouter();
    provide("purchase_invoice_item_store", purchaseInvoiceItemStore);
    created();
    //Methods
    function routeToPurchaseInvoicesPage() {
      router.push(`/purchase-return-invoices`);
    }
    function onItemInfoClicked(purchaseInvoiceItem) {
      let infos = [
        {
          header: "CREATED_AT",
          text: purchaseInvoiceItem.created_at,
          textDateTime: true,
        },
        { header: "BY", text: purchaseInvoiceItem.added_by.name },
      ];
      if (purchaseInvoiceItem.updated_by) {
        infos = infos.concat([
          {
            header: "UPDATED_AT",
            text: purchaseInvoiceItem.updated_at,
            textDateTime: true,
          },
          { header: "BY", text: purchaseInvoiceItem.updated_by.name },
        ]);
      }
      infos.push({
        header: "TOTAL_AMOUNT",
        text: purchaseInvoiceItem.total_purchase_price,
      });
      console.log(purchaseInvoiceItem)
      data.infos = infos;
    }
    function onAddClicked() {
      data.selectedPurchaseInvoiceItem = null;
      //Make little delay to ensure that watcher that found in purchaseInvoice form component
      // catch selectedPurchaseInvoiceItem input prop
      setTimeout(() => {
        purchaseInvoiceItemStore.onFormShow = !purchaseInvoiceItemStore.onFormShow;
      }, 1);
    }
    function onEditClicked(purchaseInvoiceItem, index) {
      data.selectedPurchaseInvoiceItem = purchaseInvoiceItem;
      data.selectedPurchaseInvoiceItemIndex = index;
      //Make little delay to ensure that watcher catch selectedPurchaseInvoiceItem input prop
      //in purchaseInvoiceItem form component
      setTimeout(() => {
        purchaseInvoiceItemStore.onFormShow = !purchaseInvoiceItemStore.onFormShow;
      }, 1);
    }
    function getPurchaseInvoiceItems() {
      purchaseInvoiceItemClient
        .getPurchaseInvoiceItems(
          data.page,
          data.pageSize,
          route.params.purchaseInvoiceId,
          data.text
        )
        .then((response) => {
          data.purchaseInvoiceItems = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.purchaseInvoiceItems.unshift(event);
    }
    function onUpdated(event) {
      data.purchaseInvoiceItems[data.selectedPurchaseInvoiceItemIndex] = event;
      data.selectedPurchaseInvoiceItem = null;
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getPurchaseInvoiceItems();
      }, 500);
    }
    function deletePurchaseInvoiceItem() {
      purchaseInvoiceItemClient
        .delete(data.selectedPurchaseInvoiceItem.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.purchaseInvoiceItems.splice(data.selectedPurchaseInvoiceItemIndex, 1);
          if (data.purchaseInvoiceItems.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getPurchaseInvoiceItems();
          }
          data.selectedPurchaseInvoiceItem = null;
        })
        .catch((error) => {});
    }
    function onDeleteClicked(purchaseInvoiceItem, index) {
      data.selectedPurchaseInvoiceItem = purchaseInvoiceItem;
      data.selectedPurchaseInvoiceItemIndex = index;
    }
    //Commons
    function isPurchaseInvoiceApproved() {
      purchaseInvoiceItemClient
        .isPurchaseInvoiceApproved(route.params.purchaseInvoiceId)
        .then((response) => {
          data.isPurchaseInvoiceApproved = response.data.is_purchase_invoice_approved;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function created() {
      getPurchaseInvoiceItems();
      isPurchaseInvoiceApproved();
    }
    return {
      ...toRefs(data),
      routeToPurchaseInvoicesPage,
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      getPurchaseInvoiceItems,
      onCreated,
      onUpdated,
      search,
      onDeleteClicked,
      deletePurchaseInvoiceItem,
    };
  },
};
</script>

<style lang="scss">
.purchase-invoice-item-container {
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
