<template>
  <div class="supplier-category-form">
    <div
      class="modal fade"
      id="supplierCategoryFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("SUPPLIER_CATEGORIES") }}
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
                <div class="col-12 mb-2">
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
import supplierCategoryClient from "../../../../shared/http-clients/supplier/supplier-category-client";
import { inject, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const supplier_category_store = inject("supplier_category_store");
    const toast = inject("toast");
    const data = reactive({
      nameExist: false,
    });
    const form = reactive({
      name: "",
      active: true,
    });
    const rules = {
      name: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedSupplierCategory) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      data.nameExist = false;
      supplierCategoryClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.supplier_category,
            added_by: response.data.user,
          });
          $("#supplierCategoryFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function update() {
      data.nameExist = false;
      supplierCategoryClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.supplier_category,
            added_by: props.selectedSupplierCategory.added_by,
            updated_by: response.data.user,
          });
          $("#supplierCategoryFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function getForm() {
      return {
        id: props.selectedSupplierCategory ? props.selectedSupplierCategory.id : null,
        name: form.name,
        active: form.active,
      };
    }
    function setForm() {
      console.log(props.selectedSupplierCategory);
      v$.value.$reset();
      data.nameExist = false;
      form.name = props.selectedSupplierCategory
        ? props.selectedSupplierCategory.name
        : "";
      form.active = props.selectedSupplierCategory
        ? Boolean(props.selectedSupplierCategory.active)
        : true;
    }
    //Watchers
    watch(
      () => {
        supplier_category_store.onFormShow;
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
  props: ["selectedSupplierCategory"],
};
</script>

<style scoped lang="scss">
.active {
  display: flex;
  justify-content: flex-end;
}
.supplier-category-form {
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
}
</style>
