<template>
  <div class="admins-container">
    <AdminForm @created="onCreated" @updated="onUpdated" :selectedAdmin="selectedAdmin" />
    <Information :infos="infos" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("ADMINS") }}</span>
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
                data-target="#adminFormModal"
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
              <tr v-for="(admin, index) in admins" :key="admin.id">
                <td>{{ admin.name }}</td>
                <td>
                  <div class="actions">
                    <span data-toggle="tooltip" data-placement="top" :title="$t('EDIT')">
                      <button
                        @click="onEditClicked(admin, index)"
                        data-toggle="modal"
                        data-target="#adminFormModal"
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
                        @click="onItemInfoClicked(admin)"
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
            :clickHandler="getAdmins"
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
import adminClient from "../../../shared/http-clients/admin-client";
import Paginate from "vuejs-paginate-next";
import AdminForm from "./admin-form.vue";
import adminStore from "./admin-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import Information from "../../../shared/components/information.vue";
export default {
  components: {
    Paginate,
    AdminForm,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      admins: [],
      text: "",
      pageCounts: 0,
      timeout: null,
      selectedAdmin: null,
      selectedAdminIndex: 0,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("admin_store", adminStore);
    created();
    //Methods
    function onAddClicked() {
      data.selectedAdmin = null;
      //Make little delay to ensure that watcher that found in admin form component
      // catch selectedAdmin input prop
      setTimeout(() => {
        adminStore.onFormShow = !adminStore.onFormShow;
      }, 1);
    }
    function onEditClicked(admin, index) {
      data.selectedAdmin = admin;
      data.selectedAdminIndex = index;
      //Make little delay to ensure that watcher catch selectedAdmin input prop
      //in admin form component
      setTimeout(() => {
        adminStore.onFormShow = !adminStore.onFormShow;
      }, 1);
    }
    function getAdmins() {
      adminClient
        .getAdmins(data.page, data.pageSize, data.text)
        .then((response) => {
          data.admins = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      data.admins.unshift(event);
    }
    function onUpdated(event) {
      data.admins[data.selectedAdminIndex] = event;
      data.selectedAdmin = null;
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getAdmins();
      }, 500);
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
      getAdmins();
    }
    return {
      ...toRefs(data),
      onItemInfoClicked,
      onAddClicked,
      onEditClicked,
      getAdmins,
      onCreated,
      onUpdated,
      search,
    };
  },
};
</script>

<style lang="scss">
.admins-container {
  padding-bottom: 50px;
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
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
