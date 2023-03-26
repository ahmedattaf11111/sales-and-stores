<template>
  <div class="supplier-container">
    <SupplierForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedSupplier="selectedSupplier"
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
        <span>{{ $t("SUPPLIERS") }}</span>
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
              data-target="#supplierFormModal"
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
              <tr v-for="(supplier, index) in suppliers" :key="supplier.id">
                <td>{{ supplier.account.name }}</td>
                <td>
                  <div class="actions">
                    <button
                      @click="onEditClicked(supplier, index)"
                      data-toggle="modal"
                      data-target="#supplierFormModal"
                      class="border text-secondary"
                    >
                      <i class="fa fa-edit" aria-hidden="true"></i>
                    </button>
                    <button
                      @click="onItemInfoClicked(supplier)"
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
            :clickHandler="getSuppliers"
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
import supplierClient from "../../../../shared/http-clients/supplier/supplier-client";
import Paginate from "vuejs-paginate-next";
import SupplierForm from "./supplier-form.vue";
import supplierStore from "./supplier-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import Information from "../../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    SupplierForm,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      suppliers: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedSupplier: null,
      selectedSupplierIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("supplier_store", supplierStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedSupplier = null;
      //Make little delay to ensure that watcher that found in supplier form component
      // catch selectedSupplier input prop
      setTimeout(() => {
        supplierStore.onFormShow = !supplierStore.onFormShow;
      }, 1);
    }
    function onEditClicked(supplier, index) {
      data.selectedSupplier = supplier;
      data.selectedSupplierIndex = index;
      //Make little delay to ensure that watcher catch selectedSupplier input prop
      //in supplier form component
      setTimeout(() => {
        supplierStore.onFormShow = !supplierStore.onFormShow;
      }, 1);
    }
    function getSuppliers() {
      supplierClient
        .getSuppliers(data.page, data.pageSize, data.text)
        .then((response) => {
          data.suppliers = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.suppliers.unshift(event);
    }
    function onUpdated(event) {
      data.suppliers[data.selectedSupplierIndex] = event;
      data.selectedSupplier = null;
    }
    function search(debounceSearch = true) {
      data.page = 1;
      if (debounceSearch) {
        // clear timeout variable
        clearTimeout(data.timeout);
        data.timeout = setTimeout(() => {
          getSuppliers();
        }, 500);
      } else getSuppliers();
    }
    function onItemInfoClicked(supplier) {
      let infos = [
        { header: "CREATED_AT", text: supplier.account.created_at, textDateTime: true },
        { header: "BY", text: supplier.account.added_by.name },
      ];
      if (supplier.account.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: supplier.account.updated_at, textDateTime: true },
          { header: "BY", text: supplier.account.updated_by.name },
        ]);
      }
      infos = infos.concat([
        { header: "ACCOUNT_NUMBER", text: supplier.account.id },
        { header: "CUSTOMER_NUMBER", text: supplier.id },
      ]);
      data.infos = infos;
    }
    //Commons
    function created() {
      getSuppliers();
    }
    return {
      ...toRefs(data),
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      getSuppliers,
      onCreated,
      onUpdated,
      search,
    };
  },
};
</script>

<style lang="scss">
.supplier-container {
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
