<template>
  <div class="item-categories-container">
    <DeleteConfirmation
      @confirm="deleteItemCategory"
      @closed="selectedItemCategory = null"
    />
    <ItemCategoryForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedItemCategory="selectedItemCategory"
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
        <span>{{ $t("ITEM_CATEGORIES") }}</span>
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
                data-target="#itemCategoryFormModal"
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
              <tr v-for="(itemCategory, index) in itemCategories" :key="itemCategory.id">
                <td>{{ itemCategory.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('ADD')">
                      <button
                        @click="onEditClicked(itemCategory, index)"
                        data-toggle="modal"
                        data-target="#itemCategoryFormModal"
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
                        @click="onDeleteClicked(itemCategory, index)"
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
                        @click="onItemInfoClicked(itemCategory)"
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
            :clickHandler="getItemCategories"
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
import itemCategoryClient from "../../../shared/http-clients/item-category-client";
import Paginate from "vuejs-paginate-next";
import ItemCategoryForm from "./item-category-form.vue";
import ItemCategoryStore from "./item-category-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import Information from "../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    ItemCategoryForm,
    DeleteConfirmation,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      itemCategories: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedItemCategory: null,
      selectedItemCategoryIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("item_category_store", ItemCategoryStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedItemCategory = null;
      //Make little delay to ensure that watcher that found in itemCategory form component
      // catch selectedItemCategory input prop
      setTimeout(() => {
        ItemCategoryStore.onFormShow = !ItemCategoryStore.onFormShow;
      }, 1);
    }
    function onEditClicked(itemCategory, index) {
      data.selectedItemCategory = itemCategory;
      data.selectedItemCategoryIndex = index;
      //Make little delay to ensure that watcher catch selectedItemCategory input prop
      //in itemCategory form component
      setTimeout(() => {
        ItemCategoryStore.onFormShow = !ItemCategoryStore.onFormShow;
      }, 1);
    }
    function getItemCategories() {
      itemCategoryClient
        .getItemCategories(data.page, data.pageSize, data.text)
        .then((response) => {
          data.itemCategories = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.itemCategories.unshift(event);
    }
    function onUpdated(event) {
      data.itemCategories[data.selectedItemCategoryIndex] = event;
      data.selectedItemCategory = null;
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getItemCategories();
      }, 500);
    }
    function deleteItemCategory() {
      itemCategoryClient
        .delete(data.selectedItemCategory.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.itemCategories.splice(data.selectedItemCategoryIndex, 1);
          if (data.itemCategories.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getItemCategories();
          }
          data.selectedItemCategory = null;
        })
        .catch((error) => {});
    }
    function onDeleteClicked(itemCategory, index) {
      data.selectedItemCategory = itemCategory;
      data.selectedItemCategoryIndex = index;
    }
    function onItemInfoClicked(itemCategory) {
      let infos = [
        { header: "CREATED_AT", text: itemCategory.created_at, textDateTime: true },
        { header: "BY", text: itemCategory.added_by.name },
      ];
      if (itemCategory.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: itemCategory.updated_at, textDateTime: true },
          { header: "BY", text: itemCategory.updated_by.name },
        ]);
      }
      data.infos = infos;
    }
    //Commons
    function created() {
      getItemCategories();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onItemInfoClicked,
      onEditClicked,
      getItemCategories,
      onCreated,
      onUpdated,
      search,
      onDeleteClicked,
      deleteItemCategory,
    };
  },
};
</script>

<style lang="scss">
.item-categories-container {
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
  }
}
</style>
