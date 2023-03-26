<template>
  <div class="items-container">
    <DeleteConfirmation @confirm="deleteItem" @closed="selectedItem = null" />
    <ItemForm
      @created="onCreated"
      @updated="onUpdated"
      :categories="categories"
      :selectedItem="selectedItem"
    />
    <Information :infos="infos" />
    <Filter
      @onCategorySelected="onCategorySelected"
      @onTypeSelected="onTypeSelected"
      :categories="categories"
    />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("ITEMS") }}</span>
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
                data-target="#itemFormModal"
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
              <tr v-for="(item, index) in items" :key="item.id">
                <td>{{ item.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('EDIT')">
                      <button
                        @click="onEditClicked(item, index)"
                        data-toggle="modal"
                        data-target="#itemFormModal"
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
                        @click="onDeleteClicked(item, index)"
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
                        @click="onItemInfoClicked(item)"
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
            :clickHandler="getItems"
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
import itemClient from "../../../shared/http-clients/item-client";
import Paginate from "vuejs-paginate-next";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import ItemForm from "./item-form.vue";
import itemStore from "./item-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import Information from "../../../shared/components/information.vue";
import Filter from "./filter";
export default {
  components: {
    Paginate,
    DeleteConfirmation,
    ItemForm,
    Information,
    Filter,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      items: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedItem: null,
      selectedItemIndex: 0,
      categories: [],
      item_category_id: "",
      type: "",
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("item_store", itemStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedItem = null;
      //Make little delay to ensure that watcher that found in item form component
      // catch selectedItem input prop
      setTimeout(() => {
        itemStore.onFormShow = !itemStore.onFormShow;
      }, 1);
    }
    function onEditClicked(item, index) {
      data.selectedItem = item;
      data.selectedItemIndex = index;
      //Make little delay to ensure that watcher catch selectedItem input prop
      //in item form component
      setTimeout(() => {
        itemStore.onFormShow = !itemStore.onFormShow;
      }, 1);
    }
    function onDeleteClicked(item, index) {
      data.selectedItem = item;
      data.selectedItemIndex = index;
    }
    function onCategorySelected(event) {
      data.item_category_id = event;
      data.page = 1;
      getItems();
    }
    function onTypeSelected(event) {
      data.type = event;
      data.page = 1;
      getItems();
    }
    function getItems() {
      itemClient
        .getItems(data.page, data.pageSize, data.text, data.item_category_id, data.type)
        .then((response) => {
          data.items = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getCategories() {
      itemClient.getItemCategories().then((response) => {
        data.categories = response.data;
      });
    }
    function onCreated(event) {
      data.items.unshift(event);
    }
    function onUpdated(event) {
      data.items[data.selectedItemIndex] = event;
      data.selectedItem = null;
    }
    function deleteItem() {
      itemClient
        .delete(data.selectedItem.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.items.splice(data.index, 1);
          if (data.items.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getItems();
          }
          data.selectedItem = null;
        })
        .catch((error) => {});
    }
    function search(debounceSearch = true) {
      data.page = 1;
      // clear timeout variable
      if (debounceSearch) {
        clearTimeout(data.timeout);
        data.timeout = setTimeout(() => {
          getItems();
        }, 500);
      } else getItems();
    }
    function onItemInfoClicked(admin) {
      let infos = [];
      if (admin.added_by) {
        infos = [
          { header: "CREATED_AT", text: admin.created_at, textDateTime: true },
          { header: "BY", text: admin.added_by.name },
        ];
      }
      if (admin.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: admin.updated_at, textDateTime: true },
          { header: "BY", text: admin.updated_by.name },
        ]);
      }
      data.infos = infos;
    }
    //Commons
    function created() {
      getItems();
      getCategories();
    }
    return {
      ...toRefs(data),
      onTypeSelected,
      onCategorySelected,
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      onDeleteClicked,
      getItems,
      onCreated,
      onUpdated,
      deleteItem,
      search,
    };
  },
};
</script>

<style lang="scss">
.items-container {
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
    td {
      img {
        width: 70px;
        height: 70px;
        border-radius: 3px;
        padding: 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
          rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
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
