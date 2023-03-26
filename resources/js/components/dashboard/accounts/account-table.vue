<template>
  <div class="account-container">
    <AccountForm
      @created="onCreated"
      @updated="onUpdated"
      :types="types"
      :selectedAccount="selectedAccount"
    />
    <Information :infos="infos" />
    <Filter
      @onSelectType="onSelectType"
      @onSelectMaster="onSelectMaster"
      :types="types"
    />

    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("ALL_ACCOUNTS") }}</span>
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
                data-target="#accountFormModal"
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
                <th scope="col">{{ $t("NAME") }}</th>
                <th scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(account, index) in accounts" :key="account.id">
                <td>{{ account.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('EDIT')">
                      <button
                        @click="onEditClicked(account, index)"
                        data-toggle="modal"
                        data-target="#accountFormModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-edit" aria-hidden="true"></i>
                      </button>
                    </span>
                    <span
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DETAILS')"
                    >
                      <button
                        @click="onItemInfoClicked(account)"
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
            :clickHandler="getAccounts"
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
import accountClient from "../../../shared/http-clients/account-client";
import Paginate from "vuejs-paginate-next";
import AccountForm from "./account-form.vue";
import accountStore from "./account-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import Information from "../../../shared/components/information.vue";
import Filter from "./filter";

export default {
  components: {
    Paginate,
    AccountForm,
    Information,
    Filter
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      accounts: [],
      types: [],
      text: "",
      account_type_id: "",
      is_master: "",
      pageCounts: 0,
      timeout: null,
      selectedAccount: null,
      selectedAccountIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("account_store", accountStore);
    created();
    //Methods
    function onSelectType(event) {
      data.account_type_id = event;
      data.page = 1;
      getAccounts();
    }
    function onSelectMaster(event) {
      data.is_master = event;
      data.page = 1;
      getAccounts();
    }
    function onItemInfoClicked(account) {
      let infos = [
        { header: "CREATED_AT", text: account.created_at, textDateTime: true },
        { header: "BY", text: account.added_by.name },
      ];
      if (account.updated_by) {
        infos = infos.concat([
          {
            header: "UPDATED_AT",
            text: account.updated_at,
            textDateTime: true,
          },
          { header: "BY", text: account.updated_by.name },
        ]);
      }
      infos.push({ header: "ACCOUNT_NUMBER", text: account.id });
      data.infos = infos;
    }
    function onAddClicked() {
      data.selectedAccount = null;
      //Make little delay to ensure that watcher that found in account form component
      // catch selectedAccount input prop
      setTimeout(() => {
        accountStore.onFormShow = !accountStore.onFormShow;
      }, 1);
    }
    function onEditClicked(account, index) {
      data.selectedAccount = account;
      data.selectedAccountIndex = index;
      //Make little delay to ensure that watcher catch selectedAccount input prop
      //in account form component
      setTimeout(() => {
        accountStore.onFormShow = !accountStore.onFormShow;
      }, 1);
    }
    function getAccounts() {
      accountClient
        .getAccounts(
          data.page,
          data.pageSize,
          data.text,
          data.account_type_id,
          data.is_master
        )
        .then((response) => {
          data.accounts = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getTypes() {
      accountClient.getTypes().then((response) => {
        data.types = response.data;
      });
    }
    function onCreated(event) {
      data.accounts.unshift(event);
    }
    function onUpdated(event) {
      data.accounts[data.selectedAccountIndex] = event;
      data.selectedAccount = null;
    }
    function search(debounceSearch = true) {
      data.page = 1;
      if (debounceSearch) {
        // clear timeout variable
        clearTimeout(data.timeout);
        data.timeout = setTimeout(() => {
          getAccounts();
        }, 500);
      } else getAccounts();
    }
    //Commons
    function created() {
      getAccounts();
      getTypes();
    }
    return {
      ...toRefs(data),
      onSelectType,
      onSelectMaster,
      onAddClicked,
      onEditClicked,
      getAccounts,
      onCreated,
      onUpdated,
      search,
      onItemInfoClicked,
    };
  },
};
</script>

<style lang="scss">
.account-container {
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
    .page-link {
      padding: 3px 18px !important;
    }
    .active {
      a {
        color: #fff !important;
        background-color: #6d85fb !important;
        border-color: #dbdbdb !important;
      }
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
