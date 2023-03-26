<template>
  <div class="delegate-container">
    <DelegateForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedDelegate="selectedDelegate"
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
        <span>{{ $t("DELEGATES") }}</span>
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
            <button
              @click="onAddClicked()"
              data-toggle="modal"
              data-target="#delegateFormModal"
              class="border text-secondary"
            >
              <i class="fa fa-plus" aria-hidden="true"></i>
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
              <tr v-for="(delegate, index) in delegates" :key="delegate.id">
                <td>{{ delegate.account.name }}</td>
                <td>
                  <div class="actions">
                    <button
                      @click="onEditClicked(delegate, index)"
                      data-toggle="modal"
                      data-target="#delegateFormModal"
                      class="border text-secondary"
                    >
                      <i class="fa fa-edit" aria-hidden="true"></i>
                    </button>
                    <button
                      @click="onItemInfoClicked(delegate)"
                      data-toggle="modal"
                      data-target="#info"
                      class="border text-secondary"
                    >
                      <i class="fa fa-info" aria-hidden="true"></i>
                    </button>
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
            :clickHandler="getDelegates"
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
import delegateClient from "../../../shared/http-clients/delegate-client";
import Paginate from "vuejs-paginate-next";
import DelegateForm from "./delegate-form.vue";
import delegateStore from "./delegate-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import Information from "../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    DelegateForm,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      delegates: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedDelegate: null,
      selectedDelegateIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("delegate_store", delegateStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedDelegate = null;
      //Make little delay to ensure that watcher that found in delegate form component
      // catch selectedDelegate input prop
      setTimeout(() => {
        delegateStore.onFormShow = !delegateStore.onFormShow;
      }, 1);
    }
    function onEditClicked(delegate, index) {
      data.selectedDelegate = delegate;
      data.selectedDelegateIndex = index;
      //Make little delay to ensure that watcher catch selectedDelegate input prop
      //in delegate form component
      setTimeout(() => {
        delegateStore.onFormShow = !delegateStore.onFormShow;
      }, 1);
    }
    function getDelegates() {
      delegateClient
        .getDelegates(data.page, data.pageSize, data.text)
        .then((response) => {
          data.delegates = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.delegates.unshift(event);
    }
    function onUpdated(event) {
      data.delegates[data.selectedDelegateIndex] = event;
      data.selectedDelegate = null;
    }
    function search(debounceSearch = true) {
      data.page = 1;
      if (debounceSearch) {
        // clear timeout variable
        clearTimeout(data.timeout);
        data.timeout = setTimeout(() => {
          getDelegates();
        }, 500);
      } else getDelegates();
    }
    function onItemInfoClicked(delegate) {
      let infos = [
        { header: "CREATED_AT", text: delegate.account.created_at, textDateTime: true },
        { header: "BY", text: delegate.account.added_by.name },
      ];
      if (delegate.account.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: delegate.account.updated_at, textDateTime: true },
          { header: "BY", text: delegate.account.updated_by.name },
        ]);
      }
      infos = infos.concat([
        { header: "ACCOUNT_NUMBER", text: delegate.account.id },
        { header: "DELEGATE_NUMBER", text: delegate.id },
        { header: "PERCENT_COLLECT_COMMISSION", text: `${delegate.percent_collect_commission}${delegate.percent_type?'%':''}` },
        { header: "PERCENT_SALES_RETAIL_COMMISSION", text: `${delegate.percent_sales_retail_commission}${delegate.percent_type?'%':''}` },
        { header: "PERCENT_HALFSALES_COMMISSION", text: `${delegate.percent_halfsale_commission}${delegate.percent_type?'%':''}` },
        { header: "PERCENT_WHOLESALES_COMMISSION", text: `${delegate.percent_wholesale_commission}${delegate.percent_type?'%':''}` },
      ]);
      data.infos = infos;
    }
    //Commons
    function created() {
      getDelegates();
    }
    return {
      ...toRefs(data),
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      getDelegates,
      onCreated,
      onUpdated,
      search,
    };
  },
};
</script>

<style lang="scss">
.delegate-container {
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
