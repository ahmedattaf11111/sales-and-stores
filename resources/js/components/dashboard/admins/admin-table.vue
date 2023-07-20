<template>
  <div class="admins-container">
    <AdminForm
      @created="onCreated"
      @updated="onUpdated"
      :selectedAdmin="selectedAdmin"
    />
    <div v-if="!admins.length" class="no-data-found text-center">
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
      <button
        :disabled="!hasPermission('create admin')"
        @click="onAddClicked"
        data-toggle="modal"
        data-target="#adminFormModal"
        class="btn submit mt-3"
      >
        {{ $t("ADD_NEW") }}
      </button>
      <button v-if="text" @click="back" class="btn submit mt-3">
        {{ $t("BACK") }}
      </button>
    </div>
    <div v-else class="px-4">
      <div class="table-container">
        <div class="row">
          <div class="col-md-6">
            <div class="header">{{ $t("STORES_TABLE") }}</div>
          </div>
          <div class="col-md-6 sec-sec">
            <div class="row">
              <div class="col-md-8">
                <div class="controls mb-2">
                  <div class="search">
                    <div class="icon">
                      <i class="fa fa-search"></i>
                      <span class="vert-line"></span>
                    </div>
                    <input
                      @keyup="search"
                      v-model="text"
                      type="text"
                      :placeholder="$t('SEARCH')"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <button
                  :disabled="!hasPermission('create admin')"
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#adminFormModal"
                  class="add text-secondary"
                >
                  {{ $t("ADD_NEW") }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr class="head">
                <th scope="col">
                  <span>{{ $t("NAME") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("EMAIL") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("TREASURIES_COUNT") }}</span>
                  <span
                    :style="
                      $i18n.locale == 'en'
                        ? 'margin-left: 10px'
                        : 'margin-right: 10px'
                    "
                  >
                    <i
                      class="fa fa-arrow-up"
                      style="font-size: 11px !important"
                    ></i>
                    <i
                      class="fa fa-arrow-down"
                      style="font-size: 11px !important; color: #c2c2c2"
                    ></i>
                  </span>
                </th>
                <th scope="col">
                  <span>{{ $t("ACTIONS") }}</span>
                </th>
                <th class="text-center">{{ $t("INFORMATION") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="value"
                v-for="(admin, index) in admins"
                :key="admin.id"
              >
                <td>{{ admin.name }}</td>
                <td>{{ admin.email }}</td>
                <td>{{ admin.treasuries.length }}</td>
                <td>
                  <div class="dropdown commands">
                    <button
                      class="btn dropdown-toggle"
                      type="button"
                      id="dropdownMenuButton"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      {{ $t("COMMANDS") }}
                    </button>
                    <div
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton"
                    >
                      <div class="text">
                        <button
                          :disabled="!hasPermission('update admin')"
                          @click="onEditClicked(admin, index)"
                          data-toggle="modal"
                          data-target="#adminFormModal"
                          class="text-secondary"
                        >
                          <span>{{ $t("EDIT") }}</span>
                          <i class="fa fa-edit" style="color: #2bd27f"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <button
                    @click="onItemInfoClicked(admin)"
                    data-toggle="modal"
                    data-target="#info"
                    class="info"
                  >
                    <i class="fa fa-info"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 d-flex justify-content-end">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getAdmins"
            :prevText="`<i class='fa fa-arrow-${
              $i18n.locale == 'en' ? 'left' : 'right'
            }'></i>`"
            :nextText="`<i class='fa fa-arrow-${
              $i18n.locale == 'en' ? 'right' : 'left'
            }'></i>`"
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
import authClient from "../../../shared/http-clients/auth-client";

export default {
  components: {
    Paginate,
    AdminForm,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      currentPermissions: [],
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
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("admin_store", adminStore);
    created();
    //Methods
    function back() {
      data.text = "";
      search();
    }

    function hasPermission(permission) {
      let filterResult = data.currentPermissions.filter(
        (perm) => perm.name == permission || perm.name == "super admin"
      );
      return filterResult.length > 0 ? true : false;
    }

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
      data.page = 1;
      getAdmins();
    }
    function onUpdated(event) {
      getAdmins();
    }
    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getAdmins();
      }, 500);
    }
    function onItemInfoClicked(element) {
      swal({
        icon: "warning",
        confirmButtonText: t("OK"),
        title: t("LOG"),
        text: `${t("CREATED")} ${
          locale.value == "ar" ? element.ar_created_at : element.en_created_at
        } ${t("BY")} ${element.added_by.name} ${
          element.updated_by
            ? `| ${t("UPDATED")} ${
                locale.value == "ar"
                  ? element.ar_updated_at
                  : element.en_updated_at
              } ${t("BY")} ${element.updated_by.name}`
            : ""
        }`,
      });
    }

    //Commons
    function created() {
      getAdmins();
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
    }
    return {
      ...toRefs(data),
      back,
      hasPermission,
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
  .no-data-found {
    margin-top: 200px;
    i {
      font-size: 40px !important;
      margin-bottom: 20px;
    }
    .submit {
      border: 1px solid #373063 !important;
      color: #373063 !important;
      margin-top: 25px !important;
      width: 90px;
      margin: 0 5px;
    }
  }

  padding: 30px 0;
  .filter {
    margin-top: 8px;
    border: unset !important;
    background: none !important;
    i {
      color: #373757 !important;
      font-size: 20px !important;
    }
  }
  .info {
    border-radius: 50%;
    border: unset !important;
    width: 24px;
    height: 23px;
    background: #e7fbf0;
    color: #2bd27f !important;
    i {
      font-size: 11px !important;
    }
  }
  .table-container {
    .data-table {
      margin-bottom: 30px;
      font-size: 15px !important;
    }
    .header {
      font-size: 15px !important;
    }

    input::placeholder {
      font-size: 14px;
      color: #b9b9b9;
      position: relative;
      top: 2px;
    }
    background: #ffffff;
    padding: 30px;
    .top-sec {
      display: flex;
      justify-content: space-between;
    }
    .sec-sec {
      margin-bottom: 33px;
    }
    .controls {
      .search {
        display: flex;
        align-items: center;
        body[dir="ltr"] & {
          .icon {
            .vert-line {
              margin-left: 17px;
              height: 23px;
              border-right: 1px solid #091023 !important;
            }
            display: flex;
            align-items: center;
            padding: 6px 0px 9px 22px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-left: 1px solid #091023 !important;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
          }
          input {
            width: 100%;
            padding: 8px 15px;
            border: 1px solid #dee2e6 !important;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-right: 1px solid #091023 !important;
            border-left: none !important;
            width: 100%;
          }
        }
        body[dir="rtl"] & {
          .icon {
            .vert-line {
              margin-right: 17px;
              height: 23px;
              border-left: 1px solid #091023 !important;
            }
            display: flex;
            align-items: center;
            padding: 6px 22px 9px 0px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-right: 1px solid #091023 !important;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
          }
          input {
            width: 100%;
            padding: 8px 15px;
            border: 1px solid #dee2e6 !important;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            border-top: 1px solid #091023 !important;
            border-bottom: 1px solid #091023 !important;
            border-left: 1px solid #091023 !important;
            border-right: none !important;
            width: 100%;
          }
        }
      }
    }
    .add {
      color: #fff !important;
      padding: 8px 25px;
      border-radius: 50px;
      font-size: 13px !important;
      background: #373063 !important;
      border: 1px solid #373063 !important;
    }

    a:hover {
      cursor: pointer;
    }
    .pagination {
      .active {
        a {
          color: #fff !important;
          background-color: #00d82c !important;
          border-color: #dbdbdb !important;
          box-shadow: 0 5px 10px rgba(0, 216, 44, 0.3) !important;
        }
      }
      .page-item .page-link {
        border-radius: 4px;
        border: unset;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #373757;
      }
    }
    table {
      .active {
        font-weight: 300;
        width: 85px;
        color: #2bd27f !important;
        background: #e7fbf0;
        border-radius: 2px;
        font-size: 12px !important;
        text-align: center;
        display: inline-block;
        padding-top: 1px;
      }
      td,
      th {
        vertical-align: middle;
        white-space: nowrap;
      }
      tr.head {
        border: 1px solid #f9f9f9 !important;
      }
      tr.value td:not(.first) {
        border-bottom: 13px solid #fff;
      }
      tr.value {
        background: #f9f9f9;
      }
      td {
        padding: 8px 12px !important;
      }
      th {
        padding: 12px !important;
      }
    }
  }
  .commands {
    .dropdown-toggle {
      padding: 0;
      &:hover {
        border: unset !important;
      }
    }
    .dropdown-menu {
      .text:first {
        padding-bottom: 8px;
      }
      button {
        display: flex;
        width: 100%;
        background: none;
        border: unset !important;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
      }
    }
  }
}
</style>
