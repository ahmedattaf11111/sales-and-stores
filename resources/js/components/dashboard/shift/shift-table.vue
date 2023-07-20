<template>
  <div class="shift-container">
    <ShiftForm @created="onCreated" />
    <div v-if="!shifts.length" class="no-data-found text-center">
      <div>
        {{ $t("NO_DATE_FOUND") }}
      </div>
      <button
        :disabled="
          currentShift ? true : false || !hasPermission('create shift')
        "
        @click="onAddClicked"
        data-toggle="modal"
        data-target="#shiftFormModal"
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
            <div class="header">{{ $t("SHIFTS_TABLE") }}</div>
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
                  style="display: inline-block"
                  :disabled="
                    currentShift
                      ? true
                      : false || !hasPermission('create shift')
                  "
                  @click="onAddClicked()"
                  data-toggle="modal"
                  data-target="#shiftFormModal"
                  class="add text-secondary"
                >
                  {{ $t("ADD_NEW") }}
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="table-responsive">
          <div class="d-flex">
            <div
              v-if="currentShift"
              class="dropdown commands"
              style="margin-top: 6px; display: inline-block"
            >
              <button
                style="padding: 5px; border: none"
                class="btn dropdown-toggle"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              ></button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <div class="text border-bottom" style="padding-bottom: 10px">
                  <button
                    v-if="currentShift"
                    @click="onGeneralInfoClicked"
                    data-toggle="modal"
                    data-target="#info"
                    class="text-secondary"
                  >
                    <span>{{ $t("INFORMATION") }}</span>
                    <i class="fa fa-info" style="color: #2bd27f"></i>
                  </button>
                </div>
                <div style="padding-top: 10px">
                  <button
                    :disabled="!hasPermission('close shift')"
                    @click="closeShift"
                    class="text-secondary"
                  >
                    <span>{{ $t("CLOSE_SHIFT") }}</span>
                    <i class="fa fa-minus text-danger"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <table class="table">
            <thead>
              <tr class="head">
                <th scope="col">
                  <span>{{ $t("SHIFT_NUMBER") }}</span>
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
                  <span>{{ $t("CURRENT_SHIFT") }}</span>
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
                  <span>{{ $t("SHIFT_OPEN") }}</span>
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
                  <span>{{ $t("SHIFT_OPENED_AT") }}</span>
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
                  <span>{{ $t("SHIFT_CLOSED_AT") }}</span>
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
                  <span>{{ $t("BY") }}</span>
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
                  <span>{{ $t("TREASURY") }}</span>
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
              </tr>
            </thead>
            <tbody>
              <tr class="value" v-for="shift in shifts" :key="shift.id">
                <td>
                  {{ shift.id }}
                </td>
                <td>
                  <span class="active">{{
                    currentShift && currentShift.id == shift.id
                      ? $t("YES")
                      : $t("NO")
                  }}</span>
                </td>
                <td>
                  <span class="active">{{
                    shift.is_finished == 1 ? $t("NO") : $t("YES")
                  }}</span>
                </td>
                <td>
                  {{
                    $i18n.locale == "ar"
                      ? shift.ar_created_at
                      : shift.en_created_at
                  }}
                </td>
                <td>
                  {{
                    shift.ar_closed_at
                      ? $i18n.locale == "ar"
                        ? shift.ar_closed_at
                        : shift.en_closed_at
                      : "-"
                  }}
                </td>
                <td>{{ shift.admin.name }}</td>
                <td>{{ shift.treasury.name }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 d-flex justify-content-end">
          <paginate
            v-model="page"
            :pageCount="pageCounts"
            :clickHandler="getShifts"
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
import shiftClient from "../../../shared/http-clients/shift-client";
import Paginate from "vuejs-paginate-next";
import ShiftForm from "./shift-form.vue";
import shiftStore from "./shift-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
import authClient from "../../../shared/http-clients/auth-client";

export default {
  components: {
    Paginate,
    ShiftForm,
  },
  setup() {
    const data = reactive({
      pageSize: 20,
      page: 1,
      currentPermissions: [],
      shifts: [],
      pageCounts: 0,
      timeout: null,
      currentShift: null,
      infos: [],
      text: "",
    });
    const toast = inject("toast");
    const swal = inject("swal");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("shift_store", shiftStore);
    created();
    //Methods
    function back() {
      data.text = "";
      search();
    }

    function search() {
      data.page = 1;
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getShifts();
      }, 500);
    }

    function hasPermission(permission) {
      let filterResult = data.currentPermissions.filter(
        (perm) => perm.name == permission || perm.name == "super admin"
      );
      return filterResult.length > 0 ? true : false;
    }

    function closeShift() {
      shiftClient
        .closeShift()
        .then((response) => {
          data.page = 1;
          data.currentShift = null;
          getShifts();
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function onGeneralInfoClicked() {
      swal({
        icon: "warning",
        confirmButtonText: t("OK"),
        title: t("LOG"),
        text: `${t("CURRENT_SHIFT_NUMBER")} ${data.currentShift.id} | ${t(
          "SHIFT_OPENED_AT"
        )} ${
          locale.value == "ar"
            ? data.currentShift.ar_created_at
            : data.currentShift.en_created_at
        }`,
      });
    }
            
    function onAddClicked() {
      setTimeout(() => {
        shiftStore.onFormShow = !shiftStore.onFormShow;
      }, 1);
    }

    function getShifts() {
      shiftClient
        .getShifts(data.page, data.pageSize, data.text)
        .then((response) => {
          data.shifts = response.data.data;
          data.pageCounts = Math.ceil(response.data.total / data.pageSize);
        })
        .catch((error) => {
          console.log(error.response);
        });
    }

    function getCurrentShift() {
      shiftClient
        .getCurrentShift()
        .then((response) => {
          data.currentShift = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function onCreated(event) {
      getShifts();
      getCurrentShift();
    }
    function onItemInfoClicked(shift) {
      let infos = [
        {
          header: "SHIFT_OPENED_AT",
          text: shift.created_at,
          textDateTime: true,
        },
      ];
      if (shift.closed_at) {
        infos.push({
          header: "SHIFT_CLOSED_AT",
          text: shift.closed_at,
          textDateTime: true,
        });
      }
      infos = infos.concat([
        { header: "BY", text: shift.admin.name },
        { header: "TREASURY", text: shift.treasury.name },
        {
          header: "SHIFT_OPEN",
          text: shift.is_finished ? "NO" : "YES",
          textTranslated: true,
        },
      ]);
      data.infos = infos;
    }
    //Commons
    function created() {
      getShifts();
      getCurrentShift();
      authClient.currentPermissions().then((res) => {
        data.currentPermissions = res.data;
        console.log(data.currentPermissions);
      });
    }
    return {
      ...toRefs(data),
      back,
      search,
      hasPermission,
      closeShift,
      onGeneralInfoClicked,
      onItemInfoClicked,
      onAddClicked,
      getShifts,
      onCreated,
    };
  },
};
</script>

<style lang="scss">
.shift-container {
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
  .shift-details {
    margin: 0 15px;
  }
  .close-shift,
  .shift-details {
    color: #2bd27f !important;
    position: relative;
    top: 4px;
    background: none;
    padding: 0;
    border: 0px solid #2bd27f !important;
    margin-bottom: 14px;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    display: flex;
    width: 22px;
    height: 22px;
    i {
      font-size: 12px !important;
    }
  }
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
