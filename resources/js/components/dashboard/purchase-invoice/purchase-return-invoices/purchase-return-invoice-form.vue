<template>
  <div class="purchase-invoice-form">
    <div
      class="modal fade"
      id="purchaseReturnInvoiceFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("GENERAL_PURCHASE_RETURN_INVOICES") }}
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
                    <label for="exampleInputEmail1">{{ $t("INVOICE_DATE") }}</label>
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
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("STORE") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.store_id.$model"
                      :class="{
                        'is-invalid': v$.store_id.$error,
                      }"
                    >
                      <option v-for="store in stores" :key="store.id" :value="store.id">
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
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import purchaseReturnInvoiceClient from "../../../../shared/http-clients/purchase-invoice/purchase-return-invoice-client";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const purchase_invoice_store = inject("purchase_invoice_store");
    const toast = inject("toast");
    const data = reactive({});
    const form = reactive({
      date: "",
      supplier_id: null,
      store_id: null,
      note: "",
    });
    const rules = {
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
      purchaseReturnInvoiceClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.purchase_return_invoice,
            added_by: response.data.user,
          });
          $("#purchaseReturnInvoiceFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function update() {
      purchaseReturnInvoiceClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.purchase_return_invoice,
            added_by: props.selectedPurchaseInvoice.added_by,
            updated_by: response.data.user,
          });
          $("#purchaseReturnInvoiceFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function getForm() {
      return {
        id: props.selectedPurchaseInvoice ? props.selectedPurchaseInvoice.id : null,
        date: form.date,
        supplier_id: form.supplier_id,
        store_id: form.store_id,
        note: form.note,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.date = props.selectedPurchaseInvoice ? props.selectedPurchaseInvoice.date : "";
      form.store_id = props.selectedPurchaseInvoice
        ? props.selectedPurchaseInvoice.store_id
        : null;
      form.supplier_id = props.selectedPurchaseInvoice
        ? props.selectedPurchaseInvoice.supplier_id
        : null;
      form.note = props.selectedPurchaseInvoice ? props.selectedPurchaseInvoice.note : "";
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
   input:checked {
    background: #6d85fb;
    border-color: #6d85fb !important;
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
