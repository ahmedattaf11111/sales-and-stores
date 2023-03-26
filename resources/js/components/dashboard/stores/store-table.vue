<template>
  <div class="stores-container">
    <DeleteConfirmation @confirm="deleteStore" @closed="selectedStore = null" />
    <StoreForm @created="onCreated" @updated="onUpdated" :selectedStore="selectedStore" />
    <Information :infos="infos" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("STORES") }}</span>
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
                data-target="#storeFormModal"
                class="border text-secondary"
              >
                <i class="fa fa-plus" aria-hidden="true"></i>
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
              <tr v-for="(store, index) in stores" :key="store.id">
                <td>{{ store.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('EDIT')">
                      <button
                        @click="onEditClicked(store, index)"
                        data-toggle="modal"
                        data-target="#storeFormModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-edit" aria-hidden="true"></i>
                      </button>
                    </span>
                    <span
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DELETE')"
                    >
                      <button
                        @click="onDeleteClicked(store, index)"
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
                        @click="onItemInfoClicked(store)"
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
            :clickHandler="getStores"
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
import storeClient from "../../../shared/http-clients/store-client";
import Paginate from "vuejs-paginate-next";
import StoreForm from "./store-form.vue";
import storeStore from "./store-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import Information from "../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    StoreForm,
    DeleteConfirmation,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      stores: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedStore: null,
      selectedStoreIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("store_store", storeStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedStore = null;
      //Make little delay to ensure that watcher that found in store form component
      // catch selectedStore input prop
      setTimeout(() => {
        storeStore.onFormShow = !storeStore.onFormShow;
      }, 1);
    }
    function onEditClicked(store, index) {
      data.selectedStore = store;
      data.selectedStoreIndex = index;
      //Make little delay to ensure that watcher catch selectedStore input prop
      //in store form component
      setTimeout(() => {
        storeStore.onFormShow = !storeStore.onFormShow;
      }, 1);
    }
    function getStores() {
      storeClient
        .getStores(data.page, data.pageSize, data.text)
        .then((response) => {
          data.stores = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function deleteStore() {
      storeClient
        .delete(data.selectedStore.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.stores.splice(data.selectedStoreIndex, 1);
          if (data.stores.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getStores();
          }
          data.selectedStore = null;
        })
        .catch((error) => {});
    }
    function onCreated(event) {
      data.stores.unshift(event);
    }
    function onUpdated(event) {
      data.stores[data.selectedStoreIndex] = event;
      data.selectedStore = null;
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getStores();
      }, 500);
    }
    function onDeleteClicked(store, index) {
      data.selectedStore = store;
      data.selectedStoreIndex = index;
    }
    function onItemInfoClicked(store) {
      let infos = [
        { header: "CREATED_AT", text: store.created_at, textDateTime: true },
        { header: "BY", text: store.added_by.name },
      ];
      if (store.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: store.updated_at, textDateTime: true },
          { header: "BY", text: store.updated_by.name },
        ]);
      }
      data.infos = infos;
    }
    //Commons
    function created() {
      getStores();
    }
    return {
      ...toRefs(data),
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      getStores,
      onCreated,
      onUpdated,
      search,
      deleteStore,
      onDeleteClicked,
    };
  },
};
</script>

<style lang="scss">
.stores-container {
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
