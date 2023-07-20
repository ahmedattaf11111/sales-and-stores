<template>
  <div class="purchase-invoice-form">
    <div
      class="modal fade"
      id="purchaseInvoiceFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("PURCHASE_INVOICES") }}
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
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("INVOICE_DATE")
                    }}</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="v$.date.$model"
                      :class="{
                        'is-invalid': v$.date.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.date.$errors" :key="error">
                        {{ $t("INVOICE_DATE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("INVOICE_NUMBER")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.invoice_number.$model"
                      :class="{
                        'is-invalid':
                          v$.invoice_number.$error || invoiceNumberExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice_number.$errors"
                        :key="error"
                      >
                        {{ $t("INVOICE_NUMBER") + " " + $t(error.$validator) }}
                      </div>
                      <div
                        v-if="!v$.invoice_number.$invalid && invoiceNumberExist"
                      >
                        {{ $t("INVOICE_NUMBER") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("SUPPLIER") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.supplier_id.$model"
                      :class="{
                        'is-invalid': v$.supplier_id.$error,
                      }"
                    >
                      <option
                        v-for="supplier in suppliers"
                        :key="supplier.id"
                        :value="supplier.id"
                      >
                        {{ supplier.account.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.supplier_id.$errors" :key="error">
                        {{ $t("SUPPLIER") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("STORE") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.store_id.$model"
                      :class="{
                        'is-invalid': v$.store_id.$error,
                      }"
                    >
                      <option
                        v-for="store in stores"
                        :key="store.id"
                        :value="store.id"
                      >
                        {{ store.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.store_id.$errors" :key="error">
                        {{ $t("STORE") + " " + $t(error.$validator) }}
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
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import purchaseInvoiceClient from "../../../../shared/http-clients/purchase-invoice/purchase-invoice-client";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const purchase_invoice_store = inject("purchase_invoice_store");
    const toast = inject("toast");
    const swal = inject("swal");
    const data = reactive({
      invoiceNumberExist: false,
    });
    const form = reactive({
      date: "",
      invoice_number: "",
      supplier_id: null,
      store_id: null,
    });
    const rules = {
      invoice_number: { required },
      date: { required },
      supplier_id: { required },
      store_id: { required },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {});
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedPurchaseInvoice) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function store() {
      data.invoiceNumberExist = false;
      purchaseInvoiceClient
        .create(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
          });
          context.emit("created");
          $("#purchaseInvoiceFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
          data.invoiceNumberExist = error.response.data.errors.invoice_number
            ? true
            : false;
        });
    }
    function update() {
      data.invoiceNumberExist = false;
      purchaseInvoiceClient
        .update(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("UPDATED_SUCCESSFULLY"),
          });
          context.emit("updated");
          $("#purchaseInvoiceFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
          data.invoiceNumberExist = error.response.data.errors.invoice_number
            ? true
            : false;
        });
    }
    function getForm() {
      return {
        id: props.selectedPurchaseInvoice
          ? props.selectedPurchaseInvoice.id
          : null,
        invoice_number: form.invoice_number,
        date: form.date,
        supplier_id: form.supplier_id,
        store_id: form.store_id,
      };
    }
    function setForm() {
      console.log(props.selectedPurchaseInvoice);
      v$.value.$reset();
      data.invoiceNumberExist = false;
      form.invoice_number = props.selectedPurchaseInvoice
        ? props.selectedPurchaseInvoice.invoice_number
        : "";
      form.date = props.selectedPurchaseInvoice
        ? props.selectedPurchaseInvoice.date
        : "";
      form.store_id = props.selectedPurchaseInvoice
        ? props.selectedPurchaseInvoice.store_id
        : null;
      form.supplier_id = props.selectedPurchaseInvoice
        ? props.selectedPurchaseInvoice.supplier_id
        : null;
    }
    //Watchers
    watch(
      () => {
        purchase_invoice_store.onFormShow;
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
  props: ["selectedPurchaseInvoice", "suppliers", "stores"],
};
</script>

<style scoped lang="scss">
.purchase-invoice-form {
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
  .submit {
    background: #373063 !important;
    color: #fff !important;
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
