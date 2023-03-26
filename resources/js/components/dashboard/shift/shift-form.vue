<template>
  <div class="shift-form">
    <div
      class="modal fade"
      id="shiftFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("SHIFTS") }}
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
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("TREASURY") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.treasury_id.$model"
                      :class="{
                        'is-invalid': v$.treasury_id.$error,
                      }"
                    >
                      <option
                        :disabled="treasury.open_shift"
                        v-for="treasury in treasuries"
                        :key="treasury.id"
                        :value="treasury.id"
                      >
                        {{
                          `${treasury.name} ${
                            treasury.open_shift
                              ? `(${$t("RESERVED_BY")} ${treasury.open_shift.admin.name})`
                              : ""
                          }`
                        }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.treasury_id.$errors" :key="error">
                        {{ $t("TREASURY") + " " + $t(error.$validator) }}
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
import shiftClient from "../../../shared/http-clients/shift-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const shift_store = inject("shift_store");
    const toast = inject("toast");
    const data = reactive({
      treasuries: [],
    });
    const form = reactive({
      treasury_id: null,
    });
    const rules = {
      treasury_id: { required },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      shiftClient.getAdminTreasuries().then((response) => {
        data.treasuries = response.data;
      });
    });
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      store();
    }
    //Commons
    function store() {
      shiftClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.shift,
            admin: response.data.user,
            treasury:getSelectedTreasury()
          });
          $("#shiftFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function getForm() {
      return {
        treasury_id: form.treasury_id,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.treasury_id = null;
    }
    function getSelectedTreasury() {
      let filteredList = data.treasuries.filter(
        (treasury) => treasury.id == form.treasury_id
      );
      return filteredList.length > 0 ? filteredList[0] : null;
    }
    //Watchers
    watch(
      () => {
        shift_store.onFormShow;
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
};
</script>

<style scoped lang="scss">
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
.shift-form {
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
