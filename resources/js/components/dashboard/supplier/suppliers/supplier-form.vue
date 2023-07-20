<template>
  <div class="supplier-form">
    <div
      class="modal fade"
      id="supplierFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("SUPPLIERS") }}
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
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("CATEGORY") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.supplier_category_id.$model"
                      :class="{
                        'is-invalid': v$.supplier_category_id.$error,
                      }"
                    >
                      <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                      >
                        {{ category.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.supplier_category_id.$errors"
                        :key="error"
                      >
                        {{ $t("CATEGORY") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
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
                <div class="col-lg-4">
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
                <div class="col-lg-4">
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
import { required } from "@vuelidate/validators";
import supplierClient from "../../../../shared/http-clients/supplier/supplier-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import phoneValidator from "../../../../shared/validators/phone-validator";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const supplier_store = inject("supplier_store");
    const toast = inject("toast");
    const swal = inject("swal");
    const data = reactive({
      categories: [],
    });
    const form = reactive({
      name: "",
      supplier_category_id: null,
      start_balance: 0,
      address: "",
      phone: "",
    });
    const rules = {
      name: { required },
      supplier_category_id: { required },
      start_balance: { required },
      phone: { required, phoneValidator },
      address: { required },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      supplierClient.getCategories().then((response) => {
        data.categories = response.data;
      });
    });
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedSupplier) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      supplierClient
        .create(getForm())
        .then((response) => {
          swal({
            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
            confirmButtonText: t("OK"),
          });
          context.emit("created");
          $("#supplierFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function update() {
      supplierClient
        .update(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("UPDATED_SUCCESSFULLY"),
          });
          context.emit("updated");
          $("#supplierFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function getCategory() {
      let category = null;
      data.categories.forEach((_category) => {
        if (_category.id == form.supplier_category_id) {
          return (category = _category);
        }
      });
      return category;
    }
    function getForm() {
      return {
        id: props.selectedSupplier ? props.selectedSupplier.id : null,
        name: form.name,
        supplier_category_id: form.supplier_category_id,
        start_balance: form.start_balance,
        phone: form.phone,
        address: form.address,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedSupplier
        ? props.selectedSupplier.account.name
        : "";
      form.supplier_category_id = props.selectedSupplier
        ? props.selectedSupplier.supplier_category_id
        : null;
      form.phone = props.selectedSupplier ? props.selectedSupplier.phone : "";
      form.address = props.selectedSupplier
        ? props.selectedSupplier.address
        : "";
      form.start_balance = props.selectedSupplier
        ? props.selectedSupplier.account.start_balance
        : 0;
    }
    //Watchers
    watch(
      () => {
        supplier_store.onFormShow;
      },
      (value) => {
        setForm();
        console.log(props.selectedSupplier);
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
  props: ["selectedSupplier"],
};
</script>

<style scoped lang="scss">
.submit {
  background: #373063 !important;
  color: #fff !important;
}
.supplier-form {
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
}
</style>
