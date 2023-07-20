<template>
  <div class="treasury-transaction-container">
    <TreasuryTransactionForm @created="onCreated" />
    <Filter @onTypeSelected="onTypeSelected" />
    <div v-if="!generalInfo" class="add-new-shift text-center">
      <div>
        {{ $t("COLLECT_EXCHANGE_UNABLE") }}
      </div>
      <router-link class="btn submit mt-3" to="/shifts">{{
        $t("ADD_NEW_SHIFT")
      }}</router-link>
    </div>
    <div
      v-else-if="!treasuryTransactions.length"
      class="no-data-found text-center"
    >
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
      <button
        :disabled="!hasPermission('create collect_exchange_money')"
        @click="onAddClicked"
        data-toggle="modal"
        data-target="#treasuryTransactionFormModal"
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
            <div class="header">{{ $t("TREASURIES_TABLE") }}</div>
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
                  :disabled="!hasPermission('create collect_exchange_money')"
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#treasuryTransactionFormModal"
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
                    @click="onGeneralInfoClicked"
                    data-toggle="modal"
                    data-target="#info"
                    class="text-secondary"
                  >
                    <span>{{ $t("INFORMATION") }}</span>
                    <i class="fa fa-info" style="color: #2bd27f"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
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
                  <span>{{ $t("TYPE") }}</span>
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
                  <span>{{ $t("SHIFT_NUMBER") }}</span>
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
                  <span>{{ $t("ACCOUNT") }}</span>
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
                  <span>{{ $t("TREASURY") }}</span>
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
                  <span>{{ $t("AMOUNT_DUE") }}</span>
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
                <th class="text-center" scope="col">{{ $t("INFORMATION") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="value"
                v-for="treasuryTransaction in treasuryTransactions"
                :key="treasuryTransaction.id"
              >
                <td>
                  {{
                    treasuryTransaction.type == "COLLECT"
                      ? treasuryTransaction.collect_receipt_number
                      : treasuryTransaction.exchange_receipt_number
                  }}
                </td>
                <td>
                  <span class="active">{{ $t(treasuryTransaction.type) }}</span>
                </td>
                <td>{{ treasuryTransaction.shift.id }}</td>
                <td>{{ treasuryTransaction.account.name }}</td>
                <td>{{ treasuryTransaction.shift.treasury.name }}</td>
                <td>{{ treasuryTransaction.move_type.name }}</td>
                <td>{{ treasuryTransaction.amount }}</td>
                <td>
                  {{
                    treasuryTransaction.purchase_invoice ||
                    treasuryTransaction.sale_invoice
                      ? treasuryTransaction.purchase_invoice
                        ? treasuryTransaction.purchase_invoice.invoice_number
                        : treasuryTransaction.sale_invoice.invoice_number
                      : "-"
                  }}
                </td>
                <td class="text-center">
                  <button
                    @click="onItemInfoClicked(treasuryTransaction)"
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
            :clickHandler="getTreasuryTransactions"
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

import treasuryTransactionClient from "../../../shared/http-clients/treasury-transaction-client";
import Paginate from "vuejs-paginate-next";
import TreasuryTransactionForm from "./treasury-transaction-form.vue";
import Filter from "./filter";
import treasuryTransactionStore from "./treasury-transaction-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    TreasuryTransactionForm,
    Filter,
  },
  setup() {
    const data = reactive({
      pageSize: 20,
      currentPermissions: [],
      page: 1,
      type: "",
      treasuryTransactions: [],
      pageCounts: 0,
      timeout: null,
      infos: [],
      generalInfo: null,
      text: "",
    });
    const toast = inject("toast");
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("treasury_transaction_store", treasuryTransactionStore);
    created();
    //Methods
    function back() {
      data.text = "";
      search();
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getTreasuryTransactions();
      }, 500);
    }
    function hasPermission(permission) {
      let filterResult = data.currentPermissions.filter(
        (perm) => perm.name == permission || perm.name == "super admin"
      );
      return filterResult.length > 0 ? true : false;
    }

    function onTypeSelected(event) {
      data.type = event;
      data.page = 1;
      getTreasuryTransactions();
    }
    function onGeneralInfoClicked() {
      swal({
        icon: "warning",
        title: t("LOG"),
        confirmButtonText: t("OK"),
        text: `${t("SHIFT_NUMBER")} (${data.generalInfo.shift.id}) |
        ${t("TREASURY_NAME")} "${data.generalInfo.shift.treasury.name}" |
        ${t("TREASURY_MOUNY")} (${data.generalInfo.total_amount})
        `,
      });
    }

    function onAddClicked() {
      setTimeout(() => {
        treasuryTransactionStore.onFormShow =
          !treasuryTransactionStore.onFormShow;
      }, 1);
    }
    function getTreasuryTransactions() {
      treasuryTransactionClient
        .getTreasuryTransactions(data.page, data.pageSize, data.type, data.text)
        .then((response) => {
          data.treasuryTransactions = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getGeneralInfo() {
      treasuryTransactionClient
        .getGeneralInfo()
        .then((response) => {
          data.generalInfo = response.data;
        })
        .catch((error) => {});
    }
    function onCreated(event) {
      data.page = 1;
      getTreasuryTransactions();
      getGeneralInfo();
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
      getTreasuryTransactions();
      getGeneralInfo();
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
    }
    return {
      back,
      ...toRefs(data),
      search,
      hasPermission,
      onTypeSelected,
      onGeneralInfoClicked,
      onItemInfoClicked,
      onAddClicked,
      getTreasuryTransactions,
      onCreated,
    };
  },
};
</script>

<style lang="scss">
.treasury-transaction-container {
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
