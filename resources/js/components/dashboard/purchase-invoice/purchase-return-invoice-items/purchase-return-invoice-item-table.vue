<template>
  <div class="_purchase-invoice-item-container">
    <PurchaseInvoiceItemForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedPurchaseInvoiceItem="selectedPurchaseInvoiceItem"
    />
    <div v-if="!purchaseInvoiceItems.length" class="no-data-found text-center">
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
      <button
        :disabled="
          isPurchaseInvoiceApproved || !hasPermission('create purchase_return_invoice')
        "
        @click="onAddClicked()"
        data-toggle="modal"
        data-target="#purchaseInvoiceItemFormModal"
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
            <div class="header">{{ $t("RETURNS_INVOICE_ITEMS") }}</div>
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
                  :disabled="
                    isPurchaseInvoiceApproved ||
                    !hasPermission('create purchase_return_invoice')
                  "
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#purchaseInvoiceItemFormModal"
                  class="add text-secondary"
                >
                  {{ $t("ADD_NEW") }}
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <div class="d-flex">
            <div
              class="dropdown commands"
              style="margin-top: 6px; display: inline-block"
            >
              <button
                style="padding: 5px; border: none"
                class="btn dropdown-toggle"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              ></button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <div class="text">
                  <button
                    data-toggle="tooltip"
                    data-placement="top"
                    @click="routeToPurchaseInvoicesPage"
                    class="text-secondary"
                  >
                    <span>{{ $t("RETURNS_INVOICES") }}</span>
                    <i class="fa fa-arrow-left" style="color: #2bd27f"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <table class="table">
            <thead>
              <tr class="head">
                <th scope="col">
                  <span>{{ $t("ITEM") }}</span>
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
                  <span>{{ $t("UNIT_OF_MEASURE") }}</span>
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
                  <span>{{ $t("PURCHASE_PRICE") }}</span>
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
                  <span>{{ $t("RECEIVED_QUANTITY") }}</span>
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
                  <span>{{ $t("PRODUCTION_DATE") }}</span>
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
                  <span>{{ $t("EXPIRATION_DATE") }}</span>
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
                v-for="(purchaseInvoiceItem, index) in purchaseInvoiceItems"
                :key="purchaseInvoiceItem.id"
              >
                <td>{{ purchaseInvoiceItem.item.name }}</td>
                <td>{{ purchaseInvoiceItem.unit_of_measure.name }}</td>
                <td>{{ purchaseInvoiceItem.purchase_price }}</td>
                <td>{{ purchaseInvoiceItem.quantity }}</td>
                <td>
                  {{
                    purchaseInvoiceItem.item.type == "HAS_EXPIRATION_DATE"
                      ? purchaseInvoiceItem.production_date
                      : "-"
                  }}
                </td>
                <td>
                  {{
                    purchaseInvoiceItem.item.type == "HAS_EXPIRATION_DATE"
                      ? purchaseInvoiceItem.expiration_date
                      : "-"
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
                      v-if="!isPurchaseInvoiceApproved"
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton"
                    >
                   
                      <div>
                        <button
                          :disabled="!hasPermission('delete purchase_return_invoice')"
                          @click="onDeleteClicked(purchaseInvoiceItem, index)"
                          data-toggle="modal"
                          data-target="#deleteConfirmationModal"
                          class="border text-secondary"
                        >
                          <span>{{ $t("DELETE") }}</span>
                          <i class="fa fa-trash text-danger"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <button
                    @click="onItemInfoClicked(purchaseInvoiceItem)"
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
            :clickHandler="getPurchaseInvoiceItems"
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
      currentPermissions: [],
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
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    const route = useRoute();
    const router = useRouter();
    provide("purchase_invoice_item_store", purchaseInvoiceItemStore);
    created();
    //Methods
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

    function routeToPurchaseInvoicesPage() {
      router.push(`/purchase-return-invoices`);
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
    function onAddClicked() {
      data.selectedPurchaseInvoiceItem = null;
      //Make little delay to ensure that watcher that found in purchaseInvoice form component
      // catch selectedPurchaseInvoiceItem input prop
      setTimeout(() => {
        purchaseInvoiceItemStore.onFormShow =
          !purchaseInvoiceItemStore.onFormShow;
      }, 1);
    }
    function onEditClicked(purchaseInvoiceItem, index) {
      data.selectedPurchaseInvoiceItem = purchaseInvoiceItem;
      data.selectedPurchaseInvoiceItemIndex = index;
      //Make little delay to ensure that watcher catch selectedPurchaseInvoiceItem input prop
      //in purchaseInvoiceItem form component
      setTimeout(() => {
        purchaseInvoiceItemStore.onFormShow =
          !purchaseInvoiceItemStore.onFormShow;
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
      data.page = 1;
      getPurchaseInvoiceItems();
    }
    function onUpdated(event) {
      getPurchaseInvoiceItems();
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
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("DELETED_SUCCESSFULLY"),
          });
          if (data.page > 1 && data.items.length == 1) {
            data.page--;
          }
          getPurchaseInvoiceItems();
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function onDeleteClicked(purchaseInvoiceItem, index) {
      data.selectedPurchaseInvoiceItem = purchaseInvoiceItem;
      data.selectedPurchaseInvoiceItemIndex = index;
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
            deletePurchaseInvoiceItem();
          }
        });
    }
    //Commons
    function isPurchaseInvoiceApproved() {
      purchaseInvoiceItemClient
        .isPurchaseInvoiceApproved(route.params.purchaseInvoiceId)
        .then((response) => {
          data.isPurchaseInvoiceApproved =
            response.data.is_purchase_invoice_approved;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function created() {
      getPurchaseInvoiceItems();
      isPurchaseInvoiceApproved();
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
    }
    return {
      ...toRefs(data),
      back,
      hasPermission,
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
._purchase-invoice-item-container {
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
        white-space: nowrap;
        vertical-align: middle;
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
