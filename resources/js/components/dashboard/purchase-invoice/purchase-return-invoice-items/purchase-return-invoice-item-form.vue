<template>
  <div class="purchase-invoice-item-form">
    <div
      class="modal fade"
      id="purchaseInvoiceItemFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("RETURNS_INVOICE_ITEMS") }}
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
                    <label for="exampleInputEmail1">{{ $t("ITEM") }}</label>
                    <select
                      @change="setSelectedItem()"
                      class="form-control"
                      v-model="v$.item_id.$model"
                      :class="{
                        'is-invalid': v$.item_id.$error,
                      }"
                    >
                      <option
                        v-for="item in items"
                        :key="item.id"
                        :value="item.id"
                      >
                        {{ item.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.item_id.$errors" :key="error">
                        {{ $t("ITEM") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("UNIT_OF_MEASURE")
                    }}</label>
                    <select
                      @change="setSelectedUnitOfMeasure"
                      class="form-control"
                      v-model="v$.unit_of_measure_id.$model"
                      :class="{
                        'is-invalid': v$.unit_of_measure_id.$error,
                      }"
                    >
                      <option
                        v-if="selectedItem && selectedItem.main_unit_of_measure"
                        :key="selectedItem.main_unit_of_measure.id"
                        :value="selectedItem.main_unit_of_measure.id"
                      >
                        {{
                          `${selectedItem.main_unit_of_measure.name} (${$t(
                            "MAIN_UNIT"
                          )})`
                        }}
                      </option>
                      <option
                        v-if="selectedItem && selectedItem.sub_unit_of_measure"
                        :key="selectedItem.sub_unit_of_measure.id"
                        :value="selectedItem.sub_unit_of_measure.id"
                      >
                        {{
                          `${selectedItem.sub_unit_of_measure.name} (${$t(
                            "SUB_UNIT"
                          )})`
                        }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.unit_of_measure_id.$errors"
                        :key="error"
                      >
                        {{ $t("UNIT_OF_MEASURE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("BATCH") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.batch_id.$model"
                      :class="{
                        'is-invalid': v$.batch_id.$error,
                      }"
                    >
                      <template v-if="unit_of_measure_id">
                        <option
                          v-for="batch in batches"
                          :key="batch.id"
                          :value="batch.id"
                        >
                          <template
                            v-if="
                              batch.production_date && batch.expiration_date
                            "
                          >
                            {{
                              $t("EXPIRE_BATCH_DESCRIPTION", {
                                quantity: getQuantity(batch),
                                unit: selectedUnitOfMeasure?.name,
                                price: getPrice(batch),
                                from: batch.production_date,
                                to: batch.expiration_date,
                              })
                            }}
                          </template>
                          <template v-else>
                            {{
                              $t("BATCH_DESCRIPTION", {
                                quantity: getQuantity(batch),
                                unit: selectedUnitOfMeasure?.name,
                                price: getPrice(batch),
                              })
                            }}
                          </template>
                        </option>
                      </template>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.batch_id.$errors" :key="error">
                        {{ $t("BATCH") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("RECEIVED_QUANTITY")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.quantity.$model"
                      :class="{
                        'is-invalid': v$.quantity.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.quantity.$errors" :key="error">
                        {{
                          $t("RECEIVED_QUANTITY") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("PRICE_UNIT")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.purchase_price.$model"
                      :class="{
                        'is-invalid': v$.purchase_price.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.purchase_price.$errors"
                        :key="error"
                      >
                        {{
                          $t("PRICE_UNIT") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
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
import { minValue, required } from "@vuelidate/validators";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
import purchaseInvoiceItemClient from "../../../../shared/http-clients/purchase-invoice/purchase-return-invoice-item-client";
import { useRoute } from "vue-router";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const purchase_invoice_item_store = inject("purchase_invoice_item_store");
    const toast = inject("toast");
    const data = reactive({
      items: [],
      batches: [],
      selectedItem: null,
      selectedUnitOfMeasure: null,
    });
    const form = reactive({
      item_id: null,
      unit_of_measure_id: null,
      quantity: 0,
      purchase_price: 0,
      batch_id: null,
    });
    const rules = {
      item_id: { required },
      batch_id: { required },
      unit_of_measure_id: { required },
      quantity: { required, minValue: minValue(0) },
      purchase_price: { required, minValue: minValue(0) },
    };
    const v$ = useVuelidate(rules, form);
    const route = useRoute();
    onMounted(() => {
      getItems();
    });
    //Methods
    function getQuantity(batch) {
      return data.selectedUnitOfMeasure.is_master
        ? batch.quantity
        : data.selectedItem.sub_to_main_quantity * batch.quantity;
    }
    function getPrice(batch) {
      return data.selectedUnitOfMeasure.is_master
        ? batch.purchase_price
        : batch.purchase_price / data.selectedItem.sub_to_main_quantity;
    }
    function setSelectedUnitOfMeasure() {
      data.selectedUnitOfMeasure = getSelectedUnitOfMeasure();
    }
    function getSelectedUnitOfMeasure() {
      if (!data.selectedItem) return;
      if (
        data.selectedItem.main_unit_of_measure.id == form.unit_of_measure_id
      ) {
        return data.selectedItem.main_unit_of_measure;
      } else {
        return data.selectedItem.sub_unit_of_measure;
      }
    }
    function getBatches() {
      purchaseInvoiceItemClient
        .getBatches(route.params.purchaseInvoiceId, form.item_id)
        .then((response) => {
          data.batches = response.data;
        });
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedPurchaseInvoiceItem) {
        store();
      } else {
        update();
      }
    }
    function setSelectedItem() {
      data.selectedItem = getSelectedItem();
      form.unit_of_measure_id = null;
      getBatches();
    }
    //Commons
    function getItems() {
      purchaseInvoiceItemClient.getItems().then((response) => {
        data.items = response.data;
      });
    }
    function store() {
      purchaseInvoiceItemClient
        .create(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
          });
          context.emit("created");

          $("#purchaseInvoiceItemFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
        });
    }
    function update() {
      purchaseInvoiceItemClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.purchase_invoice_item,
            item: data.selectedItem,
            added_by: props.selectedPurchaseInvoiceItem.added_by,
            updated_by: response.data.user,
          });
          $("#purchaseInvoiceItemFormModal").modal("hide");
        })
        .catch((error) => {});
    }
    function getForm() {
      return {
        id: props.selectedPurchaseInvoiceItem
          ? props.selectedPurchaseInvoiceItem.id
          : null,
        item_id: form.item_id,
        unit_of_measure_id: form.unit_of_measure_id,
        quantity: form.quantity,
        purchase_price: form.purchase_price,
        purchase_invoice_id: route.params.purchaseInvoiceId,
        batch_id: form.batch_id,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.item_id = props.selectedPurchaseInvoiceItem
        ? props.selectedPurchaseInvoiceItem.item_id
        : null;
      data.selectedItem = getSelectedItem();
      form.unit_of_measure_id = props.selectedPurchaseInvoiceItem
        ? props.selectedPurchaseInvoiceItem.unit_of_measure_id
        : null;
      data.selectedUnitOfMeasure = getSelectedUnitOfMeasure();
      form.quantity = props.selectedPurchaseInvoiceItem
        ? props.selectedPurchaseInvoiceItem.quantity
        : 0;
      form.purchase_price = props.selectedPurchaseInvoiceItem
        ? props.selectedPurchaseInvoiceItem.purchase_price
        : 0;
      getBatches();
      form.batch_id = props.selectedPurchaseInvoiceItem
        ? props.selectedPurchaseInvoiceItem.batch_id
        : null;
    }
    function getSelectedItem() {
      let selectedItem = null;
      data.items.forEach((item) => {
        if (item.id == form.item_id) {
          return (selectedItem = item);
        }
      });
      return selectedItem;
    }
    //Watchers
    watch(
      () => {
        purchase_invoice_item_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      getBatches,
      v$,
      save,
      setSelectedItem,
      getQuantity,
      getPrice,
      setSelectedUnitOfMeasure,
    };
  },
  props: ["selectedPurchaseInvoiceItem"],
};
</script>

<style scoped lang="scss">
.purchase-invoice-item-form {
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
      background-color: #2196f3;
    }
    input:focus + .slider {
      box-shadow: 0 0 1px #2196f3;
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
