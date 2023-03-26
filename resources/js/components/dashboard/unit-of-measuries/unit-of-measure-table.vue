<template>
  <div class="p-3 unit-of-measuries-container">
    <DeleteConfirmation
      @confirm="deleteUnitOfMeasure"
      @closed="selectedUnitOfMeasure = null"
    />
    <UnitOfMeasureForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedUnitOfMeasure="selectedUnitOfMeasure"
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
        <span>{{ $t("UNIT_OF_MEASURES") }}</span>
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
                data-target="#unitOfMeasureFormModal"
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
                v-for="(unitOfMeasure, index) in unitOfMeasures"
                :key="unitOfMeasure.id"
              >
                <td>{{ unitOfMeasure.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('EDIT')">
                      <button
                        @click="onEditClicked(unitOfMeasure, index)"
                        data-toggle="modal"
                        data-target="#unitOfMeasureFormModal"
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
                        @click="onDeleteClicked(unitOfMeasure, index)"
                        data-toggle="modal"
                        data-target="#deleteConfirmationModal"
                        class="border text-secondary"
                      >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                    </span>
                    <span data-toggle="tooltip" data-placement="top" :title="$t('DETAILS')">
                      <button
                        @click="onItemInfoClicked(unitOfMeasure)"
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
            :clickHandler="getUnitOfMeasures"
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
import unitOfMeasureClient from "../../../shared/http-clients/unit-of-measure-client";
import Paginate from "vuejs-paginate-next";
import UnitOfMeasureForm from "./unit-of-measure-form.vue";
import unitOfMeasureStore from "./unit-of-measure-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import DeleteConfirmation from "../../../shared/components/delete-confirmation.vue";
import Information from "../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    UnitOfMeasureForm,
    DeleteConfirmation,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      unitOfMeasures: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedUnitOfMeasure: null,
      selectedUnitOfMeasureIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("unit_of_measure_store", unitOfMeasureStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedUnitOfMeasure = null;
      //Make little delay to ensure that watcher that found in unit of measure form component
      // catch selectedUnitOfMeasure input prop
      setTimeout(() => {
        unitOfMeasureStore.onFormShow = !unitOfMeasureStore.onFormShow;
      }, 1);
    }
    function onEditClicked(unitOfMeasure, index) {
      data.selectedUnitOfMeasure = unitOfMeasure;
      data.selectedUnitOfMeasureIndex = index;
      //Make little delay to ensure that watcher catch selectedUnitOfMeasure input prop
      //in unitOfMeasure form component
      setTimeout(() => {
        unitOfMeasureStore.onFormShow = !unitOfMeasureStore.onFormShow;
      }, 1);
    }
    function getUnitOfMeasures() {
      unitOfMeasureClient
        .getUnitOfMeasures(data.page, data.pageSize, data.text)
        .then((response) => {
          data.unitOfMeasures = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function deleteUnitOfMeasure() {
      unitOfMeasureClient
        .delete(data.selectedUnitOfMeasure.id)
        .then((response) => {
          toast.success(t("DELETED_SUCCESSFULLY"));
          data.unitOfMeasures.splice(data.selectedUnitOfMeasureIndex, 1);
          if (data.unitOfMeasures.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getUnitOfMeasures();
          }
          data.selectedUnitOfMeasure = null;
        })
        .catch((error) => {});
    }
    function onCreated(event) {
      data.unitOfMeasures.unshift(event);
    }
    function onUpdated(event) {
      data.unitOfMeasures[data.selectedUnitOfMeasureIndex] = event;
      data.selectedUnitOfMeasure = null;
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getUnitOfMeasures();
      }, 500);
    }
    function onDeleteClicked(unitOfMeasure, index) {
      data.selectedUnitOfMeasure = unitOfMeasure;
      data.selectedUnitOfMeasureIndex = index;
    }
    function onItemInfoClicked(unitOfMeasure) {
      let infos = [
        { header: "CREATED_AT", text: unitOfMeasure.created_at, textDateTime: true },
        { header: "BY", text: unitOfMeasure.added_by.name },
      ];
      if (unitOfMeasure.updated_by) {
        infos = infos.concat([
          { header: "UPDATED_AT", text: unitOfMeasure.updated_at, textDateTime: true },
          { header: "BY", text: unitOfMeasure.updated_by.name },
        ]);
      }
      data.infos = infos;
    }
    //Commons
    function created() {
      getUnitOfMeasures();
    }
    return {
      ...toRefs(data),
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      getUnitOfMeasures,
      onCreated,
      onUpdated,
      search,
      deleteUnitOfMeasure,
      onDeleteClicked,
    };
  },
};
</script>

<style lang="scss">
.unit-of-measuries-container {
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
