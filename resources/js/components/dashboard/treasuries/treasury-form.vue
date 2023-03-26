<template>
  <div class="treasury-form">
    <div
      class="modal fade"
      id="treasuryFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("TREASURIES") }}
              </h5>
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="active col-12">
                  <label class="switch">
                    <input v-model="active" type="checkbox" :checked="active" />
                    <span class="slider round"></span>
                  </label>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("NAME") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.name.$model"
                      :class="{
                        'is-invalid': v$.name.$error || nameExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name.$errors" :key="error">
                        {{ $t("NAME") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name.$invalid && nameExist">
                        {{ $t("NAME") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("LAST_COLLECTION_RECEIPT")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.last_collection_receipt.$model"
                      :class="{
                        'is-invalid': v$.last_collection_receipt.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.last_collection_receipt.$errors"
                        :key="error"
                      >
                        {{
                          $t("LAST_COLLECTION_RECEIPT") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("LAST_EXCHANGE_RECEIPT")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.last_exchange_receipt.$model"
                      :class="{
                        'is-invalid': v$.last_exchange_receipt.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.last_exchange_receipt.$errors" :key="error">
                        {{
                          $t("LAST_EXCHANGE_RECEIPT") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input
                      type="checkbox"
                      v-model="is_master"
                      class="form-check-input"
                      id="exampleCheck1"
                    />
                    <label class="form-check-label" for="exampleCheck1">{{
                      $t("MASTER")
                    }}</label>
                    <span v-if="isMasterExist" class="master text-danger">
                      {{ $t("MASTER") + " " + $t("EXIST") }}
                    </span>
                  </div>
                </div>
                <!--list-->
                <div v-if="is_master" class="col-12">
                  <div class="multi-select mb-2">
                    <label>{{ $t("TREASURIES") }}</label>
                    <div class="select border p-2">
                      <div
                        v-for="(treasury, index) in allTreasuries"
                        :key="treasury.id"
                        class="form-check"
                      >
                        <input
                          class="form-check-input"
                          type="checkbox"
                          @change="toggleTreasurySelection(treasury)"
                          :id="index"
                          :checked="treasury.selected"
                        />
                        <label class="form-check-label" for="flexCheckChecked">
                          {{ treasury.name }}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">
                {{ $t("SUBMIT") }}
              </button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t("CLOSE") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required, minValue, helpers } from "@vuelidate/validators";
import treasuryClient from "../../../shared/http-clients/treasury-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const treasury_store = inject("treasury_store");
    const toast = inject("toast");
    const data = reactive({
      nameExist: false,
      isMasterExist: false,
      allTreasuries: [],
    });
    const form = reactive({
      name: "",
      last_collection_receipt: 0,
      last_exchange_receipt: 0,
      active: true,
      is_master: false,
      treasuries: [],
    });
    const rules = {
      name: { required },
      last_collection_receipt: { required, minValue: minValue(0) },
      last_exchange_receipt: { required, minValue: minValue(0) },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      treasuryClient.getAllTreasuries().then((response) => {
        data.allTreasuries = response.data;
      });
    });
    //Methods
    function toggleTreasurySelection(treasury) {
      treasury.selected = !treasury.selected;
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedTreasury) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function getSelectedTreasuries() {
      return data.allTreasuries.filter((treasury) => {
        return treasury.selected;
      });
    }
    function getSelectedTreasuriesIds() {
      return data.allTreasuries
        .filter((treasury) => {
          return treasury.selected;
        })
        .map((treasury) => treasury.id);
    }
    function setSelectedTreasuries() {
      if (props.selectedTreasury) {
        data.allTreasuries.forEach((treasury) => {
          treasury.selected = props.selectedTreasury.sub_treasuries
            .map((_treasury) => (_treasury.id ? _treasury.id : _treasury))
            .includes(treasury.id);
        });
      } else {
        data.allTreasuries.forEach((treasury) => (treasury.selected = false));
      }
    }
    function store() {
      data.nameExist = false;
      data.isMasterExist = false;
      treasuryClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.treasury,
            sub_treasuries: getSelectedTreasuries(),
            added_by: response.data.user,
          });
          $("#treasuryFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
          data.isMasterExist = error.response.data.errors.is_master ? true : false;
        });
    }
    function update() {
      data.nameExist = false;
      data.isMasterExist = false;
      treasuryClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.treasury,
            added_by: props.selectedTreasury.added_by,
            sub_treasuries: getSelectedTreasuries(),
            updated_by: response.data.user,
          });
          $("#treasuryFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
          data.isMasterExist = error.response.data.errors.is_master ? true : false;
        });
    }
    function getForm() {
      return {
        id: props.selectedTreasury ? props.selectedTreasury.id : null,
        name: form.name,
        last_collection_receipt: form.last_collection_receipt,
        last_exchange_receipt: form.last_exchange_receipt,
        active: form.active,
        is_master: form.is_master,
        treasuries_ids: getSelectedTreasuriesIds(),
      };
    }
    function setForm() {
      v$.value.$reset();
      data.nameExist = false;
      data.isMasterExist = false;
      form.name = props.selectedTreasury ? props.selectedTreasury.name : "";
      form.last_collection_receipt = props.selectedTreasury
        ? props.selectedTreasury.last_collection_receipt
        : 0;
      form.last_exchange_receipt = props.selectedTreasury
        ? props.selectedTreasury.last_exchange_receipt
        : 0;
      form.active = props.selectedTreasury
        ? Boolean(props.selectedTreasury.active)
        : true;
      form.is_master = props.selectedTreasury
        ? Boolean(props.selectedTreasury.is_master)
        : false;
      setSelectedTreasuries();
    }
    //Watchers
    watch(
      () => {
        treasury_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      toggleTreasurySelection,
      ...toRefs(data),
      ...toRefs(form),
      v$,
      save,
    };
  },
  props: ["selectedTreasury"],
};
</script>

<style scoped lang="scss">
.treasury-form {
  .master {
    position: relative;
    bottom: 4px;
    margin: 10px;
  }
  .multi-select {
    margin-top: -13px;
    .select {
      height: 90px;
      overflow: auto;
    }
  }
  .active {
    display: flex;
    justify-content: flex-end;
  }
  .modal-header {
    border-color: #e9ecef !important;
  }
  .modal-footer {
    border: none !important;
  }
  .form-check-label {
    position: relative;
    bottom: 4px;
  }
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: 0.4s;
      transition: 0.4s;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: 0.4s;
      transition: 0.4s;
    }
    input:checked + .slider {
      background-color: #6d85fb;
    }
    input:focus + .slider {
      box-shadow: 0 0 1px #6d85fb;
    }
    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
  }
  label {
    margin-bottom: 5px;
  }
  .form-group {
    margin-bottom: 10px;
    .form-control {
      background-color: transparent;
      padding: 10px;
    }
  }
  input {
    border-color: #e7e7e7;
    border-radius: 0 !important;
    &:checked {
      background: #6d85fb;
      border-color: #6d85fb !important;
    }
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
  .increments {
    width: 35px;
    height: 34px;
    text-align: center;
    border-radius: 5px;
    background: none;
    i {
      font-size: 10px !important;
    }
  }
}
</style>
