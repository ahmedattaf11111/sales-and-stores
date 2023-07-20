<template>
  <div class="store-form">
    <div
      class="modal fade"
      id="storeFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("STORES") }}
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
                <div class="col-md-4 mt-2">
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
                <div class="col-md-4 mt-2">
                  <div class="form-group">
                    <label class="labels">{{ $t("MOBILE_NUMBER") }}</label
                    ><input
                      v-model="v$.phone.$model"
                      type="text"
                      class="form-control"
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
                <div class="col-md-4 mt-2 mb-3">
                  <div class="form-group">
                    <label class="labels">{{ $t("ADDRESS") }}</label
                    ><input
                      v-model="v$.address.$model"
                      :class="{
                        'is-invalid': v$.address.$error,
                      }"
                      type="text"
                      class="form-control"
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
import storeClient from "../../../shared/http-clients/store-client";
import { inject, reactive, toRefs, watch } from "vue-demi";
import phoneValidator from "../../../shared/validators/phone-validator";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const store_store = inject("store_store");
    const toast = inject("toast");
    const swal = inject("swal");
    const data = reactive({
      nameExist: false,
    });
    const form = reactive({
      name: "",
      phone: "",
      address: "",
    });
    const rules = {
      name: { required },
      address: { required },
      phone: { required, phoneValidator },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedStore) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      data.nameExist = false;
      storeClient
        .create(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),

            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
          });
          context.emit("created");
          $("#storeFormModal").modal("hide");
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
      storeClient
        .update(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),

            icon: "success",
            title: t("SUCCESS"),
            text: t("UPDATED_SUCCESSFULLY"),
          });
          context.emit("updated");
          $("#storeFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function getForm() {
      return {
        id: props.selectedStore ? props.selectedStore.id : null,
        name: form.name,
        address: form.address,
        phone: form.phone,
      };
    }
    function setForm() {
      v$.value.$reset();
      data.nameExist = false;
      form.name = props.selectedStore ? props.selectedStore.name : "";
      form.address = props.selectedStore ? props.selectedStore.address : "";
      form.phone = props.selectedStore ? props.selectedStore.phone : "";
    }
    //Watchers
    watch(
      () => {
        store_store.onFormShow;
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
  props: ["selectedStore"],
};
</script>

<style scoped lang="scss">
.submit {
  background: #373063 !important;
  color: #fff !important;
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

.store-form {
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
      background-color: #373063 !important;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #373063 !important;
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
    border-radius: 5px !important;
    border-color: #e7e7e7;
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
