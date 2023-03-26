<template>
  <div class="invoice-categories-container">
    <DeleteConfirmation
      @confirm="deleteInvoiceCategory"
      @closed="selectedInvoiceCategory = null"
    />
    <InvoiceCategoryForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedInvoiceCategory="selectedInvoiceCategory"
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
        <span>{{ $t("INVOICE_CATEGORIES") }}</span>
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
                data-target="#invoiceCategoryFormModal"
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
              <tr
                v-for="(invoiceCategory, index) in invoiceCategories"
                :key="invoiceCategory.id"
              >
                <td>{{ invoiceCategory.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('EDIT')">
                      <button
                        @click="onEditClicked(invoiceCategory, index)"
                        data-toggle="modal"
                        data-target="#invoiceCategoryFormModal"
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
                        @click="onDeleteClicked(invoiceCategory, index)"
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
                        @click="onItemInfoClicked(invoiceCategory)"
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
            :clickHandler="getInvoiceCategories"
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
import invoiceCategoryClient from "../../../shared/http-clients/invoice-category-client";
import Paginate from "vuejs-paginate-next";
import InvoiceCategoryForm from "./invoice-category-form.vue";
import invoiceCategoryStore from "./invoice-category-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import Information from "../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    InvoiceCategoryForm,
    DeleteConfirmation,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      invoiceCategories: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedInvoiceCategory: null,
      selectedInvoiceCategoryIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("invoice_category_store", invoiceCategoryStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedInvoiceCategory = null;
      //Make little delay to ensure that watcher that found in invoiceCategory form component
      // catch selectedInvoiceCategory input prop
      setTimeout(() => {
        invoiceCategoryStore.onFormShow = !invoiceCategoryStore.onFormShow;
      }, 1);
    }
    function onEditClicked(invoiceCategory, index) {
      data.selectedInvoiceCategory = invoiceCategory;
      data.selectedInvoiceCategoryIndex = index;
      //Make little delay to ensure that watcher catch selectedInvoiceCategory input prop
      //in invoiceCategory form component
      setTimeout(() => {
        invoiceCategoryStore.onFormShow = !invoiceCategoryStore.onFormShow;
      }, 1);
    }
    function getInvoiceCategories() {
      invoiceCategoryClient
        .getInvoiceCategories(data.page, data.pageSize, data.text)
        .then((response) => {
          data.invoiceCategories = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.invoiceCategories.unshift(event);
    }
    function onUpdated(event) {
      data.invoiceCategories[data.selectedInvoiceCategoryIndex] = event;
      data.selectedInvoiceCategory = null;
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getInvoiceCategories();
      }, 500);
    }
    function deleteInvoiceCategory() {
      invoiceCategoryClient
        .delete(data.selectedInvoiceCategory.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.invoiceCategories.splice(data.selectedInvoiceCategoryIndex, 1);
          if (data.invoiceCategories.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getInvoiceCategories();
          }
          data.selectedInvoiceCategory = null;
        })
        .catch((error) => {});
    }
    function onDeleteClicked(invoiceCategory, index) {
      data.selectedInvoiceCategory = invoiceCategory;
      data.selectedInvoiceCategoryIndex = index;
    }
    function onItemInfoClicked(invoiceCategory) {
      let infos = [
        { header: "CREATED_AT", text: invoiceCategory.created_at, textDateTime: true },
        { header: "BY", text: invoiceCategory.added_by.name },
      ];
      if (invoiceCategory.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: invoiceCategory.updated_at, textDateTime: true },
          { header: "BY", text: invoiceCategory.updated_by.name },
        ]);
      }
      data.infos = infos;
    }
    //Commons
    function created() {
      getInvoiceCategories();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onItemInfoClicked,
      onEditClicked,
      getInvoiceCategories,
      onCreated,
      onUpdated,
      search,
      onDeleteClicked,
      deleteInvoiceCategory,
    };
  },
};
</script>

<style lang="scss">
.invoice-categories-container {
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
