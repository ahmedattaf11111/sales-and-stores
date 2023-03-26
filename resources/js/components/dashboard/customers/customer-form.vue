<template>
  <div class="customer-form">
    <div
      class="modal fade"
      id="customerFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("CUSTOMERS") }}
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
                <div class="col-lg-6">
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
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("MOBILE_NUMBER") }}</label>
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
                <div class="col-lg-6">
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
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("START_BALANCE") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.start_balance.$model"
                      :class="{
                        'is-invalid': v$.start_balance.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.start_balance.$errors" :key="error">
                        {{ $t("START_BALANCE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("NOTE") }}</label>
                    <textarea rows="3" class="form-control" v-model="note"> </textarea>
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
import { required } from "@vuelidate/validators";
import customerClient from "../../../shared/http-clients/customer-client";
import { inject, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import phoneValidator from "../../../shared/validators/phone-validator";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const customer_store = inject("customer_store");
    const toast = inject("toast");
    const form = reactive({
      name: "",
      active: true,
      note: "",
      start_balance: 0,
      address: "",
      phone: "",
    });
    const rules = {
      name: { required },
      start_balance: { required },
      phone: { required, phoneValidator },
      address: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedCustomer) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      customerClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.customer,
            account: { ...response.data.account, added_by: response.data.user },
          });
          $("#customerFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function update() {
      customerClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.customer,
            account: {
              ...response.data.account,
              added_by: props.selectedCustomer.account.added_by,
              updated_by: response.data.user,
            },
          });
          $("#customerFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function getForm() {
      return {
        id: props.selectedCustomer ? props.selectedCustomer.id : null,
        name: form.name,
        start_balance: form.start_balance,
        phone: form.phone,
        address: form.address,
        note: form.note,
        active: form.active,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedCustomer ? props.selectedCustomer.account.name : "";
      form.phone = props.selectedCustomer ? props.selectedCustomer.phone : "";
      form.address = props.selectedCustomer ? props.selectedCustomer.address : "";
      form.active = props.selectedCustomer
        ? Boolean(props.selectedCustomer.account.active)
        : true;
      form.start_balance = props.selectedCustomer
        ? props.selectedCustomer.account.start_balance
        : 0;
      form.note = props.selectedCustomer ? props.selectedCustomer.account.note : "";
    }
    //Watchers
    watch(
      () => {
        customer_store.onFormShow;
      },
      (value) => {
        setForm();
        console.log(props.selectedCustomer);
      },
      { deep: true }
    );
    return {
      ...toRefs(form),
      v$,
      save,
    };
  },
  props: ["selectedCustomer"],
};
</script>

<style scoped lang="scss">
.customer-form {
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
    border-radius: 0 !important;
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
}
</style>
