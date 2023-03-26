<template>
  <div class="approve-invoice-form">
    <div
      class="modal fade"
      id="approveInvoiceFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("APPROVE_PURCHASE_INVOICE") }}
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
                <div>
                  <b>{{ $t("AMOUNT_DUE") }}</b> : {{ getTotalAmountAfterDiscount() }}
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("DISCOUNT") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.discount.$model"
                      :class="{
                        'is-invalid': v$.discount.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.discount.$errors" :key="error">
                        {{
                          $t("DISCOUNT") +
                          " " +
                          $t(error.$validator, {
                            value: error.$validator == "minValue" ? 0 : 100,
                          })
                        }}
                      </div>
                    </div>
                    <div class="form-check mt-1">
                      <input
                        type="checkbox"
                        v-model="is_discount_percent"
                        class="form-check-input"
                        id="exampleCheck1"
                      />
                      <label class="form-check-label" for="exampleCheck1">{{
                        $t("PERCENT")
                      }}</label>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("TAX") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.tax.$model"
                      :class="{
                        'is-invalid': v$.tax.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.tax.$errors" :key="error">
                        {{
                          $t("TAX") +
                          " " +
                          $t(error.$validator, {
                            value: error.$validator == "minValue" ? 0 : 100,
                          })
                        }}
                      </div>
                    </div>
                    <div class="form-check mt-1">
                      <input
                        type="checkbox"
                        v-model="is_tax_percent"
                        class="form-check-input"
                        id="exampleCheck1"
                      />
                      <label class="form-check-label" for="exampleCheck1">{{
                        $t("PERCENT")
                      }}</label>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input
                      type="checkbox"
                      v-model="is_deferred"
                      class="form-check-input"
                      id="exampleCheck1"
                    />
                    <label class="form-check-label" for="exampleCheck1">{{
                      $t("DEFERRED")
                    }}</label>
                  </div>
                </div>
                <div v-if="is_deferred" class="col-12 mb-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("PAID_AMOUNT") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.paid_amount.$model"
                      :class="{ 'is-invalid': v$.paid_amount.$error }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.paid_amount.$errors" :key="error">
                        {{ $t("PAID_AMOUNT") + " " + $t(error.$validator, { value: 0 }) }}
                      </div>
                    </div>
                  </div>
                  <div>
                    <b>{{ $t("REMAINING_AMOUNT") }}</b> :
                    {{ getTotalAmountAfterDiscount() - paid_amount }}
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
import { minValue, required } from "@vuelidate/validators";
import { inject, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import purchaseInvoiceClient from "../../../../shared/http-clients/purchase-invoice/purchase-invoice-client";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const purchase_invoice_store = inject("purchase_invoice_store");
    const toast = inject("toast");
    const form = reactive({
      discount: 0,
      is_discount_percent: true,
      tax: 0,
      is_tax_percent: true,
      is_deferred: false,
      paid_amount: 0,
    });
    const rules = {
      discount: {
        required,
        minValue: minValue(0),
        maxValue: (value) => {
          if (form.is_discount_percent) return value <= 100;
          return true;
        },
      },
      tax: {
        required,
        minValue: minValue(0),
        maxValue: (value) => {
          if (form.is_tax_percent) return value <= 100;
          return true;
        },
      },
      paid_amount: {
        required: (value) => {
          if (form.is_deferred) return value || value === 0;
          return true;
        },
        minValue: (value) => {
          if (form.is_deferred) return value >= 0;
          return true;
        },
        exceedAmountDue: (value) => {
          if (form.is_deferred) return value <= getTotalAmountAfterDiscount();
          return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function getTotalAmountAfterDiscount() {
      if (props.selectedPurchaseInvoice) {
        return (
          Number.parseFloat(props.selectedPurchaseInvoice.total_purchase_price) +
          Number.parseFloat(getTaxValue(props.selectedPurchaseInvoice)) -
          Number.parseFloat(getDiscountValue(props.selectedPurchaseInvoice))
        );
      }
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      approve();
    }
    //Commons
    function getTaxValue(purchaseInvoice) {
      return form.is_tax_percent
        ? (form.tax / 100) * purchaseInvoice.total_purchase_price
        : form.tax;
    }
    function getDiscountValue(purchaseInvoice) {
      return form.is_discount_percent
        ? (form.discount / 100) * purchaseInvoice.total_purchase_price
        : form.discount;
    }
    function approve() {
      purchaseInvoiceClient
        .approve(getForm())
        .then((response) => {
          toast.success(t("INVOICE_APPROVED"));
          context.emit("onApproved", {
            ...response.data.purchase_invoice,
            approved_by: response.data.user,
            updated_by: props.selectedPurchaseInvoice.updated_by,
            added_by: props.selectedPurchaseInvoice.added_by,
            total_amount: response.data.total_amount,
          });
          $("#approveInvoiceFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function getForm() {
      return {
        id: props.selectedPurchaseInvoice.id,
        discount: form.discount,
        is_discount_percent: form.is_discount_percent,
        tax: form.tax,
        is_tax_percent: form.is_tax_percent,
        is_deferred: form.is_deferred,
        paid_amount: form.paid_amount,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.discount = 0;
      form.is_discount_percent = true;
      form.tax = 0;
      form.is_tax_percent = true;
      form.is_deferred = false;
      form.paid_amount = 0;
    }
    //Watchers
    watch(
      () => {
        purchase_invoice_store.onApproveFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(form),
      getTotalAmountAfterDiscount,
      v$,
      save,
    };
  },
  props: ["selectedPurchaseInvoice"],
};
</script>

<style scoped lang="scss">
.approve-invoice-form {
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
