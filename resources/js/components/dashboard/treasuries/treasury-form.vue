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
                <div class="col-lg-4">
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
                <div class="col-lg-4">
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
                <div class="col-lg-4">
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
                      <div
                        v-for="error in v$.last_exchange_receipt.$errors"
                        :key="error"
                      >
                        {{
                          $t("LAST_EXCHANGE_RECEIPT") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn submit">
                {{ $t("SUBMIT") }}
              </button>
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
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
    const swal = inject("swal");
    const data = reactive({
      nameExist: false,
      allTreasuries: [],
    });
    const form = reactive({
      name: "",
      last_collection_receipt: 0,
      last_exchange_receipt: 0,
      treasuries: [],
    });
    const rules = {
      name: { required },
      last_collection_receipt: { required, minValue: minValue(0) },
      last_exchange_receipt: { required, minValue: minValue(0) },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {});
    //Methods
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
    function store() {
      data.nameExist = false;
      treasuryClient
        .create(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),

            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
          });
          context.emit("created");
          $("#treasuryFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }

          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function update() {
      data.nameExist = false;
      treasuryClient
        .update(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("UPDATED_SUCCESSFULLY"),
          });
          context.emit("updated");
          $("#treasuryFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }

          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function getForm() {
      return {
        id: props.selectedTreasury ? props.selectedTreasury.id : null,
        name: form.name,
        last_collection_receipt: form.last_collection_receipt,
        last_exchange_receipt: form.last_exchange_receipt,
      };
    }
    function setForm() {
      v$.value.$reset();
      data.nameExist = false;
      form.name = props.selectedTreasury ? props.selectedTreasury.name : "";
      form.last_collection_receipt = props.selectedTreasury
        ? props.selectedTreasury.last_collection_receipt
        : 0;
      form.last_exchange_receipt = props.selectedTreasury
        ? props.selectedTreasury.last_exchange_receipt
        : 0;
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
  .submit {
    background: #373063 !important;
    color: #fff !important;
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
      background-color: #373063;
    }
    input:focus + .slider {
      box-shadow: 0 0 1px #373063;
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
    border-radius: 5px !important;
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
