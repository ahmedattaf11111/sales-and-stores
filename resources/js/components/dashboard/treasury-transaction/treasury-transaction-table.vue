<template>
  <div class="treasury-transaction-container">
    <TreasuryTransactionForm @created="onCreated" />
    <Information :infos="infos" />
    <Filter @onTypeSelected="onTypeSelected" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("COLLECT_EXCHANGE_MONEY") }}</span>
      </div>
    </div>
    <div class="px-4">
      <div class="table-container">
        <div v-if="!generalInfo" class="text-center">
          <div>
            {{ $t("COLLECT_EXCHANGE_UNABLE") }}
          </div>
          <router-link class="btn btn-danger mt-3" to="/shifts">{{
            $t("ADD_NEW_SHIFT")
          }}</router-link>
        </div>
        <template v-else>
          <div class="controls">
            <div class="actions">
              <span data-toggle="tooltip" data-placement="top" :title="$t('ADD')">
                <button
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#treasuryTransactionFormModal"
                  class="border text-secondary"
                >
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
              </span>
              <span data-toggle="tooltip" data-placement="top" :title="$t('DETAILS')">
                <button
                  @click="onGeneralInfoClicked"
                  data-toggle="modal"
                  data-target="#info"
                  class="border text-secondary"
                >
                  <i class="fa fa-info" aria-hidden="true"></i>
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
                  <th scope="col">{{ $t("RECEIPT_NUMBER") }}</th>
                  <th scope="col">{{ $t("ACTIONS") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="treasuryTransaction in treasuryTransactions"
                  :key="treasuryTransaction.id"
                >
                  <td>
                    {{
                      (treasuryTransaction.type == "COLLECT"
                        ? treasuryTransaction.collect_receipt_number
                        : treasuryTransaction.exchange_receipt_number) +
                      ` (${$t(treasuryTransaction.type)}) `
                    }}
                  </td>
                  <td>
                    <div class="actions">
                      <span
                        data-toggle="tooltip"
                        data-placement="top"
                        :title="$t('DETAILS')"
                      >
                        <button
                          @click="onItemInfoClicked(treasuryTransaction)"
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
              :clickHandler="getTreasuryTransactions"
              :prevText="$t('PREV')"
              :nextText="$t('NEXT')"
            >
            </paginate>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
<script>
import treasuryTransactionClient from "../../../shared/http-clients/treasury-transaction-client";
import Paginate from "vuejs-paginate-next";
import TreasuryTransactionForm from "./treasury-transaction-form.vue";
import Information from "../../../shared/components/information.vue";
import Filter from "./filter";
import treasuryTransactionStore from "./treasury-transaction-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    TreasuryTransactionForm,
    Information,
    Filter,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      type: "",
      treasuryTransactions: [],
      pageCounts: 0,
      timeout: null,
      infos: [],
      generalInfo: null,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("treasury_transaction_store", treasuryTransactionStore);
    created();
    //Methods
    function onTypeSelected(event) {
      data.type = event;
      data.page = 1;
      getTreasuryTransactions();
    }
    function onGeneralInfoClicked() {
      data.infos = [
        { header: "SHIFT_NUMBER", text: data.generalInfo.shift.id },
        { header: "TREASURY", text: data.generalInfo.shift.treasury.name },
        { header: "TREASURY_MOUNY", text: data.generalInfo.total_amount },
      ];
    }
    function onAddClicked() {
      setTimeout(() => {
        treasuryTransactionStore.onFormShow = !treasuryTransactionStore.onFormShow;
      }, 1);
    }
    function getTreasuryTransactions() {
      treasuryTransactionClient
        .getTreasuryTransactions(data.page, data.pageSize, data.type)
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
      data.treasuryTransactions.unshift(event);
      data.generalInfo.total_amount =
        Number.parseFloat(data.generalInfo.total_amount) +
        Number.parseFloat(event.amount);
    }
    function onItemInfoClicked(treasuryTransaction) {
      let infos = [
        {
          header: "CREATED_AT",
          text: treasuryTransaction.created_at,
          textDateTime: true,
        },
        { header: "BY", text: treasuryTransaction.added_by.name },
        { header: "SHIFT_NUMBER", text: treasuryTransaction.shift.id },
        { header: "TREASURY", text: treasuryTransaction.shift.treasury.name },
        { header: "MOVE_TYPE", text: treasuryTransaction.move_type.name },
        { header: "AMOUNT_DUE", text: treasuryTransaction.amount },
      ];
      if (treasuryTransaction.purchase_invoice ||treasuryTransaction.sale_invoice) {
        infos.push({
          header: "INVOICE_NUMBER",
          text: treasuryTransaction.purchase_invoice
            ? treasuryTransaction.purchase_invoice.invoice_number
            : treasuryTransaction.sale_invoice.invoice_number,
        });
      }
      infos.push({ header: "NOTE", text: treasuryTransaction.note });
      data.infos = infos;
    }
    //Commons
    function created() {
      getTreasuryTransactions();
      getGeneralInfo();
    }
    return {
      ...toRefs(data),
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
    table {
      td,
      th {
        width: 50%;
      }
    }
    .controls {
      display: flex;
      justify-content: flex-end;
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
  }
}
</style>
