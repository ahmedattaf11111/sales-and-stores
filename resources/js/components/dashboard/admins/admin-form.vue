<template>
  <div class="admin-form">
    <div
      class="modal fade"
      id="adminFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("ADMINS") }}
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
                    <label for="exampleInputEmail1">{{ $t("EMAIL") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.email.$model"
                      :class="{
                        'is-invalid': v$.email.$error || emailExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.email.$errors" :key="error">
                        {{ $t("EMAIL") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.email.$invalid && emailExist">
                        {{ $t("EMAIL") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-label">{{ $t("PASSWORD") }}</label>
                    <input
                      v-model="v$.password.$model"
                      :type="passwordHidden ? 'password' : 'text'"
                      class="form-control"
                      :class="{
                        'is-invalid': v$.password.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.password.$errors" :key="error">
                        {{ $t("PASSWORD") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-label">{{ $t("PASSWORD_CONFIRMATION") }}</label>
                    <input
                      v-model="v$.password_confirmation.$model"
                      :type="passwordHidden ? 'password' : 'text'"
                      class="form-control"
                      :class="{
                        'is-invalid': v$.password_confirmation.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.password_confirmation.$errors" :key="error">
                        {{ $t("PASSWORD_CONFIRMATION") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check mt-2">
                    <input
                      @change="passwordHidden = !passwordHidden"
                      class="form-check-input"
                      type="checkbox"
                      value=""
                      id="flexCheckDefault"
                    />
                    <label class="form-check-label" for="flexCheckDefault">
                      {{ $t("SHOW_PASSWORD") }}
                    </label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="multi-select mb-2">
                    <label>{{ $t("TREASURIES") }}</label>
                    <div class="select border p-2">
                      <div
                        v-for="(treasury, index) in treasuries"
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
                    <div class="invalid-feedback">
                      {{ $t("TREASURIES") + " " + $t("required") }}
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
import { required, email } from "@vuelidate/validators";
import strong from "../../../shared/validators/strong-password-validator";
import adminClient from "../../../shared/http-clients/admin-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";

export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const admin_store = inject("admin_store");
    const toast = inject("toast");
    const data = reactive({
      emailExist: false,
      passwordHidden: true,
      treasuries: [],
    });
    const form = reactive({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      active: true,
    });
    const rules = {
      name: { required },
      email: { required, email },
      password: {
        required: (value) => {
          if (!props.selectedAdmin) return value || value === 0;
          else return true;
        },
        strong,
      },
      password_confirmation: {
        required: (value) => {
          if (!props.selectedAdmin) return value || value === 0;
          else return true;
        },
        sameAsPassword(value) {
          if (!value) {
            return true;
          }
          return form.password == value;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      adminClient
        .getAllTreasuries(props.selectedAdmin ? props.selectedAdmin.id : "")
        .then((response) => {
          data.treasuries = response.data;
        });
    });
    //Methods
    function getSelectedTreasuriesIds() {
      return data.treasuries
        .filter((treasury) => {
          return treasury.selected;
        })
        .map((treasury) => treasury.id);
    }
    function toggleTreasurySelection(treasury) {
      treasury.selected = !treasury.selected;
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedAdmin) {
        store();
      } else {
        update();
      }
    }
    //Commons
    
    function store() {
      data.emailExist = false;
      adminClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.admin,
            treasuries:getSelectedTreasuries(),
            added_by: response.data.user,
          });
          $("#adminFormModal").modal("hide");
        })
        .catch((error) => {
          data.emailExist = error.response.data.errors.email ? true : false;
        });
    }
    function update() {
      data.emailExist = false;
      adminClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.admin,
            added_by: props.selectedAdmin.added_by,
            treasuries:getSelectedTreasuries(),
            updated_by: response.data.user,
          });
          $("#adminFormModal").modal("hide");
        })
        .catch((error) => {
          data.emailExist = error.response.data.errors.email ? true : false;
        });
    }
    function getSelectedTreasuries() {
      return data.treasuries
        .filter((treasury) => {
          return treasury.selected;
        });
    }
    function getForm() {
      return {
        id: props.selectedAdmin ? props.selectedAdmin.id : null,
        name: form.name,
        email: form.email,
        password: form.password,
        active: form.active,
        treasuries_ids: getSelectedTreasuriesIds(),
      };
    }
    function setForm() {
      v$.value.$reset();
      data.emailExist = false;
      form.name = props.selectedAdmin ? props.selectedAdmin.name : "";
      form.email = props.selectedAdmin ? props.selectedAdmin.email : "";
      form.active = props.selectedAdmin ? Boolean(props.selectedAdmin.active) : true;
      setSelectedTreasuries();
    }
    function setSelectedTreasuries() {
      if (props.selectedAdmin) {
        data.treasuries.forEach((treasury) => {
          treasury.selected = props.selectedAdmin.treasuries
            .map((_treasury) => (_treasury.id ? _treasury.id : _treasury))
            .includes(treasury.id);
        });
      } else {
        data.treasuries.forEach((treasury) => (treasury.selected = false));
      }
    }

    //Watchers
    watch(
      () => {
        admin_store.onFormShow;
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
      toggleTreasurySelection,
      getSelectedTreasuriesIds,
    };
  },
  props: ["selectedAdmin"],
};  
</script>

<style scoped lang="scss">
.admin-form {
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
  input,
  .select {
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
  .decrement {
    background: none;
    border: none;
    i {
      font-size: 10px !important;
    }
  }
}
</style>
