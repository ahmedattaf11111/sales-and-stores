<template>
  <div class="delegate-form">
    <div
      class="modal fade modal-lg"
      id="delegateFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("DELEGATES") }}
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
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("NAME") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.name.$model"
                      :class="{
                        'is-invalid': v$.name.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name.$errors" :key="error">
                        {{ $t("NAME") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("START_BALANCE")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.start_balance.$model"
                      :class="{
                        'is-invalid': v$.start_balance.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.start_balance.$errors"
                        :key="error"
                      >
                        {{ $t("START_BALANCE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("MOBILE_NUMBER")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.phone.$model"
                      :class="{
                        'is-invalid': v$.phone.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.phone.$errors" :key="error">
                        {{ $t("MOBILE_NUMBER") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("ADDRESS") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.address.$model"
                      :class="{
                        'is-invalid': v$.address.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.address.$errors" :key="error">
                        {{ $t("ADDRESS") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label>{{ $t("PERCENT") }}</label>
                    <select v-model="percent_type" class="form-control">
                      <option :value="true">{{ $t("YES") }}</option>
                      <option :value="false">{{ $t("NO") }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("PERCENT_COLLECT_COMMISSION")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.percent_collect_commission.$model"
                      :class="{
                        'is-invalid': v$.percent_collect_commission.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.percent_collect_commission.$errors"
                        :key="error"
                      >
                        {{
                          $t("DISCOUNT") +
                          " " +
                          $t(error.$validator, {
                            value: error.$validator == "minValue" ? 0 : 100,
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("PERCENT_HALFSALES_COMMISSION")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.percent_halfsale_commission.$model"
                      :class="{
                        'is-invalid': v$.percent_halfsale_commission.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.percent_halfsale_commission.$errors"
                        :key="error"
                      >
                        {{
                          $t("PERCENT_HALFSALES_COMMISSION") +
                          " " +
                          $t(error.$validator, {
                            value: error.$validator == "minValue" ? 0 : 100,
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("PERCENT_WHOLESALES_COMMISSION")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.percent_wholesale_commission.$model"
                      :class="{
                        'is-invalid': v$.percent_wholesale_commission.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.percent_wholesale_commission.$errors"
                        :key="error"
                      >
                        {{
                          $t("PERCENT_WHOLESALES_COMMISSION") +
                          " " +
                          $t(error.$validator, {
                            value: error.$validator == "minValue" ? 0 : 100,
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("PERCENT_SALES_RETAIL_COMMISSION")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.percent_sales_retail_commission.$model"
                      :class="{
                        'is-invalid': v$.percent_sales_retail_commission.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.percent_sales_retail_commission
                          .$errors"
                        :key="error"
                      >
                        {{
                          $t("PERCENT_SALES_RETAIL_COMMISSION") +
                          " " +
                          $t(error.$validator, {
                            value: error.$validator == "minValue" ? 0 : 100,
                          })
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
import { minValue, required } from "@vuelidate/validators";
import delegateClient from "../../../shared/http-clients/delegate-client";
import { inject, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import phoneValidator from "../../../shared/validators/phone-validator";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const delegate_store = inject("delegate_store");
    const toast = inject("toast");
    const swal = inject("swal");
    const form = reactive({
      name: "",
      start_balance: 0,
      address: "",
      phone: "",
      percent_type: true,
      percent_collect_commission: 0,
      percent_sales_retail_commission: 0,
      percent_halfsale_commission: 0,
      percent_wholesale_commission: 0,
    });
    const rules = {
      name: { required },
      start_balance: { required, minValue: minValue(0) },
      address: { required },
      phone: { required, phoneValidator },
      percent_collect_commission: {
        required,
        minValue: minValue(0),
        maxValue: (value) => {
          if (form.percent_type) return value <= 100;
          return true;
        },
      },
      percent_sales_retail_commission: {
        required,
        minValue: minValue(0),
        maxValue: (value) => {
          if (form.percent_type) return value <= 100;
          return true;
        },
      },
      percent_halfsale_commission: {
        required,
        minValue: minValue(0),
        maxValue: (value) => {
          if (form.percent_type) return value <= 100;
          return true;
        },
      },
      percent_wholesale_commission: {
        required,
        minValue: minValue(0),
        maxValue: (value) => {
          if (form.percent_type) return value <= 100;
          return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedDelegate) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      delegateClient
        .create(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
          });
          context.emit("created");
          $("#delegateFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function update() {
      delegateClient
        .update(getForm())
        .then((response) => {
          swal({
            icon: "success",
            title: t("SUCCESS"),
            text: t("UPDATED_SUCCESSFULLY"),
          });
          context.emit("updated");
          $("#delegateFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function getForm() {
      return {
        id: props.selectedDelegate ? props.selectedDelegate.id : null,
        name: form.name,
        start_balance: form.start_balance,
        phone: form.phone,
        address: form.address,
        percent_type: form.percent_type,
        percent_collect_commission: form.percent_collect_commission,
        percent_sales_retail_commission: form.percent_sales_retail_commission,
        percent_halfsale_commission: form.percent_halfsale_commission,
        percent_wholesale_commission: form.percent_wholesale_commission,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedDelegate
        ? props.selectedDelegate.account.name
        : "";
      form.phone = props.selectedDelegate ? props.selectedDelegate.phone : "";
      form.address = props.selectedDelegate
        ? props.selectedDelegate.address
        : "";
      form.percent_type = props.selectedDelegate
        ? Boolean(props.selectedDelegate.percent_type)
        : true;
      form.start_balance = props.selectedDelegate
        ? props.selectedDelegate.account.start_balance
        : 0;
      form.percent_collect_commission = props.selectedDelegate
        ? props.selectedDelegate.percent_collect_commission
        : "";
      form.percent_sales_retail_commission = props.selectedDelegate
        ? props.selectedDelegate.percent_sales_retail_commission
        : "";
      form.percent_halfsale_commission = props.selectedDelegate
        ? props.selectedDelegate.percent_halfsale_commission
        : "";
      form.percent_wholesale_commission = props.selectedDelegate
        ? props.selectedDelegate.percent_wholesale_commission
        : "";
    }
    //Watchers
    watch(
      () => {
        delegate_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(form),
      v$,
      save,
    };
  },
  props: ["selectedDelegate"],
};
</script>

<style scoped lang="scss">
.delegate-form {
  .submit {
    background: #373063 !important;
    color: #fff !important;
  }
  .form-check-label {
    position: relative;
    top: 0px;
  }
  .modal-header {
    border-color: #e9ecef !important;
  }
  .modal-footer {
    border: none !important;
  }
  .active {
    display: flex;
    justify-content: flex-end;
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
  .form-group {
    margin-bottom: 10px;
    .form-control {
      background-color: transparent;
      padding: 10px;
    }
  }
  input,
  select,
  textarea {
    border-color: #e7e7e7;
    border-radius: 5px !important;
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
  .increments {
    width: 38px;
    height: 37px;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 5px;
  }
  hr {
    color: gray;
  }
  .master {
    margin-top: 8px;
    margin-bottom: -4px;
  }
  input:checked {
    background: #373063;
    border-color: #373063 !important;
  }
}
</style>
