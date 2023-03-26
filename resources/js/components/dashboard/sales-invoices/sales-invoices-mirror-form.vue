<template>
  <div class="sale-invoice-form">
    <div
      class="modal fade"
      id="saleInvoiceMirrorFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <form enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("INVOICE_MIRROR") }}
              </h5>
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <!--Invoice items from-->
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("STORE") }}</label>
                    <select
                      @change="setBatches"
                      class="form-control"
                      v-model="v$.invoice_item.store_id.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.store_id.$error,
                      }"
                    >
                      <option v-for="store in stores" :key="store.id" :value="store.id">
                        {{ store.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice_item.store_id.$errors" :key="error">
                        {{ $t("STORE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("ITEM") }}</label>
                    <select
                      @change="setSelectedItem()"
                      class="form-control"
                      v-model="v$.invoice_item.item_id.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.item_id.$error,
                      }"
                    >
                      <option v-for="item in items" :key="item.id" :value="item.id">
                        {{ item.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice_item.item_id.$errors" :key="error">
                        {{ $t("ITEM") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("UNIT_OF_MEASURE") }}</label>
                    <select
                      @change="setSelectedUnitOfMeasure()"
                      class="form-control"
                      v-model="v$.invoice_item.unit_of_measure_id.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.unit_of_measure_id.$error,
                      }"
                    >
                      <option
                        v-if="selectedItem && selectedItem.main_unit_of_measure"
                        :key="selectedItem.main_unit_of_measure.id"
                        :value="selectedItem.main_unit_of_measure.id"
                      >
                        {{
                          `${selectedItem.main_unit_of_measure.name} (${$t("MAIN_UNIT")})`
                        }}
                      </option>
                      <option
                        v-if="selectedItem && selectedItem.sub_unit_of_measure"
                        :key="selectedItem.sub_unit_of_measure.id"
                        :value="selectedItem.sub_unit_of_measure.id"
                      >
                        {{
                          `${selectedItem.sub_unit_of_measure.name} (${$t("SUB_UNIT")})`
                        }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice_item.unit_of_measure_id.$errors"
                        :key="error"
                      >
                        {{ $t("UNIT_OF_MEASURE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("SALE_TYPE") }}</label>
                    <select
                      @change="setSalePrice()"
                      class="form-control"
                      v-model="v$.invoice_item.sale_type.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.sale_type.$error,
                      }"
                    >
                      <option value="RETAIL">{{ $t("RETAIL") }}</option>
                      <option value="WHOLESALE">{{ $t("WHOLESALE") }}</option>
                      <option value="HALF_WHOLESALE">{{ $t("HALF_WHOLESALE") }}</option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice_item.sale_type.$errors"
                        :key="error"
                      >
                        {{ $t("SALE_TYPE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("IS_SALE_NORMAL") }}</label>
                    <select
                      @change="setSalePrice()"
                      class="form-control"
                      v-model="v$.invoice_item.is_sale_normal.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.is_sale_normal.$error,
                      }"
                    >
                      <option value="NORMAL">{{ $t("NORMAL") }}</option>
                      <option value="BONUS">{{ $t("BONUS") }}</option>
                      <option value="ADVERTISING">{{ $t("ADVERTISING") }}</option>
                      <option value="STALE">{{ $t("STALE") }}</option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice_item.is_sale_normal.$errors"
                        :key="error"
                      >
                        {{ $t("IS_SALE_NORMAL") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("SALE_PRICE") }}</label
                    ><input
                      v-model="v$.invoice_item.sale_price.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.sale_price.$error,
                      }"
                      type="text"
                      class="form-control"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice_item.sale_price.$errors"
                        :key="error"
                      >
                        {{ $t("SALE_PRICE") + " " + $t(error.$validator, { value: 0 }) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("BATCH") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.invoice_item.batch_id.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.batch_id.$error,
                      }"
                    >
                      <option v-for="batch in batches" :key="batch.id" :value="batch.id">
                        {{
                          $t("BATCH_DESCRIPTION", {
                            quantity: getQuantity(batch),
                            unit: selectedUnitOfMeasure?.name,
                            price: getPurchasePrice(batch),
                          })
                        }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice_item.batch_id.$errors" :key="error">
                        {{ $t("BATCH") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("RECEIVED_QUANTITY") }}</label
                    ><input
                      v-model="v$.invoice_item.quantity.$model"
                      :class="{
                        'is-invalid': v$.invoice_item.quantity.$error,
                      }"
                      type="text"
                      class="form-control"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice_item.quantity.$errors" :key="error">
                        {{
                          $t("RECEIVED_QUANTITY") +
                          " " +
                          $t(error.$validator, { value: 0 })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div>
                  <span
                    data-toggle="tooltip"
                    data-placement="top"
                    :title="$t('ADD_ITEM_TO_INVOICE')"
                  >
                    <button
                      type="button"
                      @click="addItemToInvoice"
                      class="action border text-secondary"
                    >
                      <i class="fas fa-plus"></i>
                    </button>
                  </span>
                </div>
              </div>
              <!--Invoice items form-->
              
              <!--Invoice items table-->
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">{{ $t("STORE") }}</th>
                      <th scope="col">{{ $t("SALE_TYPE") }}</th>
                      <th scope="col">{{ $t("ITEM") }}</th>
                      <th scope="col">{{ $t("UNIT_OF_MEASURE") }}</th>
                      <th scope="col">{{ $t("SALE_PRICE") }}</th>
                      <th scope="col">{{ $t("RECEIVED_QUANTITY") }}</th>
                      <th scope="col">{{ $t("TOTAL_AMOUNT") }}</th>
                      <th scope="col">{{ $t("DELETE") }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in invoiceItems" :key="item.id">
                      <td>{{ item.store.name }}</td>
                      <td>{{ $t(item.sale_type) }}</td>
                      <td>{{ item.item.name }}</td>
                      <td>{{ item.unit.name }}</td>
                      <td>{{ item.sale_price }}</td>
                      <td>{{ item.quantity }}</td>
                      <td>{{ item.total }}</td>
                      <td>
                        <button
                          @click="deleteInvoiceItem(index)"
                          class="border text-secondary action"
                        >
                          <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr/>
              <form>
                <div class="row">
                  <div class="col-lg-6">
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
                  <div class="col-lg-6">
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
                          {{
                            $t("PAID_AMOUNT") + " " + $t(error.$validator, { value: 0 })
                          }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div class="row">
                <div class="col-lg-6">
                  <b>{{ $t("TOTAL_AMOUNT") }}</b> :
                  {{ getTotalAmount() }}
                </div>
                <div class="col-lg-6">
                  <b>{{ $t("TOTAL_AMOUNT_BEFORE_DISCOUNT") }}</b> :
                  {{ getTotalBeforeDiscount() }}
                </div>
                <div class="col-lg-6">
                  <b>{{ $t("TOTAL_AMOUNT_AFTER_DISCOUNT") }}</b> :
                  {{ getTotalAfterDiscount() }}
                </div>
                <div v-if="is_deferred" class="col-lg-6">
                  <b>{{ $t("REMAINING_AMOUNT") }}</b> :
                  {{ getTotalAfterDiscount() - paid_amount }}
                </div>
              </div>
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
import saleInvoiceClient from "../../../shared/http-clients/sale-invoice/sale-invoice-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
export default {
  setup(props, context) {
    const sale_invoice_store = inject("sale_invoice_store");
    const data = reactive({
      items: [],
      selectedItem: null,
      selectedUnitOfMeasure: null,
      stores: [],
      batches: [],
      invoiceItems: [],
    });
    const form = reactive({
      invoice_item: {
        store_id: null,
        item_id: null,
        batch_id: null,
        sale_type: "RETAIL",
        unit_of_measure_id: null,
        is_sale_normal: "NORMAL",
        quantity: 0,
        sale_price: 0,
      },
      tax: 0,
      is_tax_percent: true,
      discount: 0,
      is_discount_percent: true,
      is_deferred: false,
      paid_amount: 0,
    });
    const rules = {
      invoice_item: {
        store_id: { required },
        item_id: { required },
        batch_id: { required },
        unit_of_measure_id: { required },
        sale_type: { required },
        is_sale_normal: { required },
        quantity: { required, minValue: minValue(0) },
        sale_price: { required, minValue: minValue(0) },
      },
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
          if (form.is_deferred) return value <= getTotalAfterDiscount();
          return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getItems();
      getStores();
    });
    //Methods
    function getTotalAmount() {
      let total = 0;
      data.invoiceItems.forEach((item) => {
        total += item.total;
      });
      return total;
    }
    function getTotalBeforeDiscount() {
      let total = getTotalAmount();
      return total + Number.parseFloat(getTaxValue(total));
    }
    function getTotalAfterDiscount() {
      let total = getTotalAmount();
      return (
        total +
        Number.parseFloat(getTaxValue(total)) -
        Number.parseFloat(getDiscountValue(total))
      );
    }
    function deleteInvoiceItem(index) {
      data.invoiceItems.splice(index, 1);
    }
    function addItemToInvoice() {
      if (v$.value.invoice_item.$invalid) {
        v$.value.invoice_item.$touch();
        return;
      }
      let invoiceItem = {
        store: getStore(),
        sale_type: form.invoice_item.sale_type,
        item: getItem(),
        unit: data.selectedUnitOfMeasure,
        sale_price: form.invoice_item.sale_price,
        quantity: form.invoice_item.quantity,
        total: form.invoice_item.quantity * form.invoice_item.sale_price,
      };
      this.invoiceItems.push(invoiceItem);
      //Reset form
      v$.value.$reset();
      form.invoice_item.store_id = null;
      form.invoice_date = "";
      form.invoice_item.item_id = null;
      form.invoice_item.sale_type = "RETAIL";
      form.invoice_item.unit_of_measure_id = null;
      form.invoice_item.is_sale_normal = "NORMAL";
      form.invoice_item.quantity = 0;
      form.invoice_item.sale_price = 0;
      form.invoice_item.batch_id = null;
    }
    function getQuantity(batch) {
      return data.selectedUnitOfMeasure.is_master
        ? batch.quantity
        : data.selectedItem.sub_to_main_quantity * batch.quantity;
    }
    function getPurchasePrice(batch) {
      return data.selectedUnitOfMeasure.is_master
        ? batch.purchase_price
        : batch.purchase_price / data.selectedItem.sub_to_main_quantity;
    }
    function setSelectedItem() {
      data.selectedItem = getSelectedItem();
      form.invoice_item.unit_of_measure_id = null;
      form.selectedUnitOfMeasure = null;
      form.invoice_item.batch_id = null;
      data.batches = [];
      setSalePrice();
    }
    function setSelectedUnitOfMeasure() {
      data.selectedUnitOfMeasure = getSelectedUnitOfMeasure();
      setSalePrice();
      form.invoice_item.batch_id = null;
      setBatches();
    }
    function setSalePrice() {
      if (
        data.selectedItem &&
        data.selectedUnitOfMeasure &&
        form.invoice_item.unit_of_measure_id &&
        form.invoice_item.is_sale_normal === "NORMAL"
      )
        return (form.invoice_item.sale_price = getSalePrice());
      form.invoice_item.sale_price = 0;
    }
    function setBatches() {
      if (
        form.invoice_item.item_id &&
        form.invoice_item.store_id &&
        form.invoice_item.unit_of_measure_id
      ) {
        data.batches = data.selectedItem.batches.filter((batch) => {
          return batch.store_id == form.invoice_item.store_id;
        });
      }
    }
    //Commons
    function getTaxValue(totalAmount) {
      return form.is_tax_percent ? (form.tax / 100) * totalAmount : form.tax;
    }
    function getDiscountValue(totalAmount) {
      return form.is_discount_percent
        ? (form.discount / 100) * totalAmount
        : form.discount;
    }
    function getStore() {
      let store = null;
      data.stores.forEach((_store) => {
        if (_store.id == form.invoice_item.store_id) {
          store = _store;
          return;
        }
      });
      return store;
    }
    function getItem() {
      let item = null;
      data.items.forEach((_item) => {
        if (_item.id == form.invoice_item.item_id) {
          item = _item;
          return;
        }
      });
      return item;
    }

    function getSalePrice() {
      if (data.selectedUnitOfMeasure.is_master) {
        if (form.invoice_item.sale_type === "RETAIL")
          return data.selectedItem.main_unit_retail_price;
        if (form.invoice_item.sale_type === "WHOLESALE")
          return data.selectedItem.main_unit_wholesale_price;
        if (form.invoice_item.sale_type === "HALF_WHOLESALE")
          return data.selectedItem.main_unit_half_wholesale_price;
      } else {
        if (form.invoice_item.sale_type === "RETAIL")
          return data.selectedItem.sub_unit_retail_price;
        if (form.invoice_item.sale_type === "WHOLESALE")
          return data.selectedItem.sub_unit_wholesale_price;
        if (form.invoice_item.sale_type === "HALF_WHOLESALE")
          return data.selectedItem.sub_unit_half_wholesale_price;
      }
    }
    function getSelectedItem() {
      let selectedItem = null;
      data.items.forEach((item) => {
        if (item.id == form.invoice_item.item_id) {
          return (selectedItem = item);
        }
      });
      return selectedItem;
    }
    function getSelectedUnitOfMeasure() {
      if (
        data.selectedItem.main_unit_of_measure.id == form.invoice_item.unit_of_measure_id
      ) {
        return data.selectedItem.main_unit_of_measure;
      } else {
        return data.selectedItem.sub_unit_of_measure;
      }
    }
    function getItems() {
      saleInvoiceClient.getItems().then((response) => {
        data.items = response.data;
      });
    }
    function getStores() {
      saleInvoiceClient.getStores().then((response) => {
        data.stores = response.data;
      });
    }
    function setForm() {
      v$.value.$reset();
      data.invoiceItems = [];
      form.invoice_item.store_id = null;
      form.invoice_item.item_id = null;
      form.invoice_item.batch_id = null;
      form.invoice_item.sale_type = "RETAIL";
      form.invoice_item.unit_of_measure_id = null;
      form.invoice_item.is_sale_normal = "NORMAL";
      form.invoice_item.quantity = 0;
      form.invoice_item.sale_price = 0;
      form.tax = 0;
      form.is_tax_percent = true;
      form.discount = 0;
      form.is_discount_percent = true;
      form.is_deferred = false;
      form.paid_amount = 0;
    }
    //Watchers
    watch(
      () => {
        sale_invoice_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      getTotalAmount,
      getTotalBeforeDiscount,
      getTotalAfterDiscount,
      deleteInvoiceItem,
      addItemToInvoice,
      setSelectedItem,
      setSalePrice,
      setSelectedUnitOfMeasure,
      getQuantity,
      getPurchasePrice,
      v$,
      setBatches,
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
.sale-invoice-form {
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
  select {
    border-radius: 0 !important;
    border-color: #e7e7e7;
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
  .action {
    margin: 0 !important;
    width: 34px;
    height: 34px;
    background: none;
    margin: 3px 5px;
    border-radius: 5px;
  }
}
</style>
