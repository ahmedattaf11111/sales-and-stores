<template>
  <div class="p-3 hellos-container">
    <DeleteConfirmation @confirm="deleteHello" @closed="selectedHello = null" />
    <HelloForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedHello="selectedHello"
    />
    <div class="controls">
      <div class="search">
        <input
          @keyup="search"
          v-model="text"
          type="text"
          :placeholder="$t('SEARCH')"
          ref="search"
        />
        <i class="fa fa-search"></i>
      </div>
      <div class="actions my-2">
        <button
          @click="onAddClicked()"
          data-toggle="modal"
          data-target="#helloFormModal"
          class="border text-secondary"
        >
          <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
        <button @click="downloadExcelFile" class="border text-secondary">
          <i class="fa fa-download" aria-hidden="true"></i>
        </button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">{{ $t("IMAGE") }}</th>
            <th scope="col">{{ $t("TITLE") }}</th>
            <th scope="col">{{ $t("TITLE") }}</th>
            <th scope="col">{{ $t("ACTIONS") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(hello, index) in hellos" :key="hello.id">
            <td><img :src="hello.image" /></td>
            <td>{{ hello.title_ar }}</td>
            <td>{{ hello.title_en }}</td>
            <td>
              <div class="actions">
                <button
                  @click="onEditClicked(hello, index)"
                  data-toggle="modal"
                  data-target="#helloFormModal"
                  class="border text-secondary"
                >
                  <i class="fa fa-edit" aria-hidden="true"></i>
                </button>
                <button
                  @click="onDeleteClicked(hello, index)"
                  data-toggle="modal"
                  data-target="#deleteConfirmationModal"
                  class="border text-secondary"
                >
                  <i class="fa fa-trash" aria-hidden="true"></i>
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
        :clickHandler="getHellos"
        :prevText="$t('PREV')"
        :nextText="$t('NEXT')"
      >
      </paginate>
    </div>
  </div>
</template>
<script>
import helloClient from "../../../shared/http-clients/hello-client";
import Paginate from "vuejs-paginate-next";
import exportFromJSON from "export-from-json";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import HelloForm from "./hello-form.vue";
import helloStore from "./hello-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    DeleteConfirmation,
    HelloForm,
  },
  setup() {
    const data = reactive({
      pageSize: 10,
      page: 1,
      hellos: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedHello: null,
      selectedHelloIndex: 0,
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("hello_store", helloStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedHello = null;
      //Make little delay to ensure that watcher that found in hello form component
      // catch selectedHello input prop
      setTimeout(() => {
        helloStore.onFormShow = !helloStore.onFormShow;
      }, 1);
    }
    function onEditClicked(hello, index) {
      data.selectedHello = hello;
      data.selectedHelloIndex = index;
      //Make little delay to ensure that watcher catch selectedHello input prop
      //in hello form component
      setTimeout(() => {
        helloStore.onFormShow = !helloStore.onFormShow;
      }, 1);
    }
    function onDeleteClicked(hello, index) {
      data.selectedHello = hello;
      data.selectedHelloIndex = index;
    }
    function getHellos() {
      helloClient
        .getHellos(data.page, data.pageSize, data.text)
        .then((response) => {
          data.hellos = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      const _data = data.hellos;
      const fileName = "Hellos";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.hellos.unshift(event);
    }
    function onUpdated(event) {
      data.hellos[data.selectedHelloIndex] = event;
      data.selectedHello = null;
    }
    function deleteHello() {
      helloClient
        .delete(data.selectedHello.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.hellos.splice(data.index, 1);
          if (data.hellos.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getHellos();
          }
          data.selectedHello = null;
        })
        .catch((error) => {});
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getHellos();
      }, 500);
    }
    //Commons
    function created() {
      getHellos();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      onDeleteClicked,
      getHellos,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteHello,
      search,
    };
  },
};
</script>

<style lang="scss" scoped>
.hellos-container {
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
        padding: 8px 15px;
        border: 1px solid #dee2e6 !important;
        border-radius: 5px;
      }
    }
  }
  .actions {
    button {
      width: 30px;
      height: 30px;
      background: none;
      margin: 3px 5px;
    }
  }
  a:hover {
    cursor: pointer;
  }
}
</style>