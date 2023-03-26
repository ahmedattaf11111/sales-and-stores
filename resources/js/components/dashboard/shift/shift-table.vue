<template>
  <div class="shift-container">
    <ShiftForm @created="onCreated" />
    <Information :infos="infos" />
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("SHIFTS") }}</span>
      </div>
    </div>
    <div class="px-4">
      <div class="table-container">
        <div class="controls">
          <div class="actions">
            <span data-toggle="tooltip" data-placement="top" :title="$t('ADD')">
              <button
                :disabled="currentShift ? true : false"
                @click="onAddClicked()"
                data-toggle="modal"
                data-target="#shiftFormModal"
                class="border text-secondary"
              >
                <i class="fa fa-plus" aria-hidden="true"></i>
              </button>
            </span>
            <span data-toggle="tooltip" data-placement="top" :title="$t('DETAILS')">
              <button
                @click="onGeneralInfoClicked"
                data-toggle="modal"
                data-target="#info"
                class="border text-secondary"
              >
                <i class="fa fa-info" aria-hidden="true"></i>
              </button>
            </span>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">{{ $t("SHIFT_NUMBER") }}</th>
                <th scope="col">{{ $t("ACTIONS") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="shift in shifts" :key="shift.id">
                <td>
                  {{
                    `${shift.id} ${
                      currentShift && currentShift.id == shift.id
                        ? `(${$t("CURRENT_SHIFT")})`
                        : ""
                    }`
                  }}
                </td>
                <td>
                  <div class="actions">
                    <span
                      data-toggle="tooltip"
                      data-placement="top"
                      :title="$t('DETAILS')"
                    >
                      <button
                        @click="onItemInfoClicked(shift)"
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
            :clickHandler="getShifts"
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
import shiftClient from "../../../shared/http-clients/shift-client";
import Paginate from "vuejs-paginate-next";
import ShiftForm from "./shift-form.vue";
import Information from "../../../shared/components/information.vue";
import shiftStore from "./shift-store";
import { inject, provide, reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  components: {
    Paginate,
    ShiftForm,
    Information,
  },
  setup() {
    const data = reactive({
      pageSize: 6,
      page: 1,
      shifts: [],
      pageCounts: 0,
      timeout: null,
      currentShift: null,
      infos: [],
    });
    const toast = inject("toast");
    const { t, locale } = useI18n({ useScope: "global" });
    provide("shift_store", shiftStore);
    created();
    //Methods
    function onGeneralInfoClicked() {
      data.infos = data.currentShift
        ? [
            {
              header: "CURRENT_SHIFT_NUMBER",
              text: data.currentShift.id,
            },
            {
              header: "SHIFT_OPENED_AT",
              text: data.currentShift.created_at,
              textDateTime: true,
            },
            { header: "TREASURY", text: data.currentShift.treasury.name },
          ]
        : [
            {
              header: "CURRENT_SHIFT_NUMBER",
              text: "NO_SHIFT_FOUND",
              textTranslated: true,
            },
          ];
    }
    function onAddClicked() {
      setTimeout(() => {
        shiftStore.onFormShow = !shiftStore.onFormShow;
      }, 1);
    }
    function getShifts() {
      shiftClient
        .getShifts(data.page, data.pageSize)
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
      data.currentShift = event;
      data.shifts.unshift(event);
    }
    function onItemInfoClicked(shift) {
      data.infos = [
        { header: "SHIFT_OPENED_AT", text: shift.created_at, textDateTime: true },
        { header: "BY", text: shift.admin.name },
        { header: "TREASURY", text: shift.treasury.name },
        {
          header: "SHIFT_OPEN",
          text: shift.is_finished ? "NO" : "YES",
          textTranslated: true,
        },
      ];
    }
    //Commons
    function created() {
      getShifts();
      getCurrentShift();
    }
    return {
      ...toRefs(data),
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
      justify-content: flex-end;
      @media (max-width: 500px) {
        flex-direction: column;
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
