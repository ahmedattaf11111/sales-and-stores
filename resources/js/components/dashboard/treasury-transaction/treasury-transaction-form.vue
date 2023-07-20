<template>
  <div class="treasury-transaction-form">
    <div
      class="modal fade"
      id="treasuryTransactionFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("COLLECT_EXCHANGE_MONEY") }}
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
                    <label for="exampleInputEmail1">{{ $t("TYPE") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.type.$model"
                      :class="{
                        'is-invalid': v$.type.$error,
                      }"
                    >
                      <option value="COLLECT">{{ $t("COLLECT") }}</option>
                      <option value="EXCHANGE">{{ $t("EXCHANGE") }}</option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.type.$errors" :key="error">
                        {{ $t("TYPE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("ACCOUNT") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.account_id.$model"
                      :class="{
                        'is-invalid': v$.account_id.$error,
                      }"
                    >
                      <option
                        v-for="account in accounts"
                        :key="account.id"
                        :value="account.id"
                      >
                        {{ account.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.account_id.$errors" :key="error">
                        {{ $t("ACCOUNT") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("AMOUNT_DUE")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.amount.$model"
                      :class="{
                        'is-invalid': v$.amount.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.amount.$errors" :key="error">
                        {{
                          $t("AMOUNT_DUE") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("NOTE") }}</label>
                    <textarea rows="3" class="form-control" v-model="note">
                    </textarea>
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
import treasuryTransactionClient from "../../../shared/http-clients/treasury-transaction-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const treasury_transaction_store = inject("treasury_transaction_store");
    const toast = inject("toast");
    const swal = inject("swal");
    const data = reactive({
      accounts: [],
    });
    const form = reactive({
      type: "",
      account_id: null,
      amount: 0,
      note: "",
    });
    const rules = {
      type: { required },
      account_id: { required },
      amount: { required, minValue: minValue(0) },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      treasuryTransactionClient.getAccounts().then((response) => {
        data.accounts = response.data;
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
      treasuryTransactionClient
        .create(getForm())
        .then((response) => {
          swal({
            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
            confirmButtonText: t("OK"),
          });
          context.emit("created");
          $("#treasuryTransactionFormModal").modal("hide");
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
        type: form.type,
        account_id: form.account_id,
        amount: form.amount,
        note: form.note,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.type = "";
      form.account_id = null;
      form.amount = 0;
      form.note = "";
    }
    //Watchers
    watch(
      () => {
        treasury_transaction_store.onFormShow;
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
.treasury-transaction-form {
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
}
</style>
