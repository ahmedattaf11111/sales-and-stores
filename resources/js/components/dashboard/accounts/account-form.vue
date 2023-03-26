<template>
  <div class="account-form">
    <div
      class="modal fade"
      id="accountFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("ACCOUNTS") }}
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
                    <label for="exampleInputEmail1">{{ $t("ACCOUNT_TYPE") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.account_type_id.$model"
                      :class="{
                        'is-invalid': v$.account_type_id.$error,
                      }"
                    >
                      <option v-for="type in types" :key="type.id" :value="type.id">
                        {{ type.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.account_type_id.$errors" :key="error">
                        {{ $t("ACCOUNT_TYPE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="master col-12">
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
                  </div>
                </div>
                <div v-if="!is_master" class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("PARENT_ACCOUNT") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.parent_id.$model"
                      :class="{
                        'is-invalid': v$.parent_id.$error,
                      }"
                    >
                      <option
                        v-for="account in allAccounts"
                        :key="account.id"
                        :value="account.id"
                      >
                        {{ account.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.parent_id.$errors" :key="error">
                        {{ $t("PARENT_ACCOUNT") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
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
import accountClient from "../../../shared/http-clients/account-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const account_store = inject("account_store");
    const toast = inject("toast");
    const data = reactive({
      allAccounts: [],
      types: [],
    });
    const form = reactive({
      name: "",
      account_type_id: null,
      active: true,
      parent_id: null,
      note: "",
      start_balance: 0,
      is_master: true,
    });
    const rules = {
      name: { required },
      account_type_id: { required },
      start_balance: { required },
      parent_id: {
        required: (value) => {
          if (!form.is_master) return value;
          return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      accountClient.getAllAccounts().then((response) => {
        data.allAccounts = response.data;
      });
    });
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedAccount) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      accountClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.account,
            added_by: response.data.user,
          });
          $("#accountFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function update() {
      accountClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.account,
            added_by: props.selectedAccount.added_by,
            updated_by: response.data.user,
          });
          $("#accountFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function getForm() {
      return {
        id: props.selectedAccount ? props.selectedAccount.id : null,
        name: form.name,
        account_type_id: form.account_type_id,
        is_master: form.is_master,
        parent_id: form.parent_id,
        start_balance: form.start_balance,
        note: form.note,
        active: form.active,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedAccount ? props.selectedAccount.name : "";
      form.account_type_id = props.selectedAccount
        ? props.selectedAccount.account_type_id
        : null;
      form.active = props.selectedAccount ? Boolean(props.selectedAccount.active) : true;
      form.is_master = props.selectedAccount
        ? Boolean(props.selectedAccount.is_master)
        : true;
      form.parent_id = props.selectedAccount ? props.selectedAccount.parent_id : null;
      form.start_balance = props.selectedAccount
        ? props.selectedAccount.start_balance
        : 0;
      form.note = props.selectedAccount ? props.selectedAccount.note : "";
    }
    //Watchers
    watch(
      () => {
        account_store.onFormShow;
      },
      (value) => {
        setForm();
        console.log(props.selectedAccount);
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
  props: ["selectedAccount", "types"],
};
</script>

<style scoped lang="scss">
.account-form {
  .modal-header {
    border-color: #e9ecef !important;
  }
  .modal-footer {
    border: none !important;
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
  .active {
    display: flex;
    justify-content: flex-end;
  }
  input:checked {
    background: #6d85fb;
    border-color: #6d85fb !important;
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
    margin-top: 4px;
    margin-bottom: -8px;
  }
}
</style>
