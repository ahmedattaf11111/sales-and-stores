<template>
  <div class="sale-invoice-form">
    <div
      class="modal fade"
      id="saleInvoiceFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-secondary" id="exampleModalLabel">
              {{ $t("SALES_INVOICES") }}
            </h5>
            <button
              type="button"
              class="btn-close"
              aria-label="Close"
              data-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("INVOICE_DATE") }}</label
                    ><input
                      v-model="v$.invoice.date.$model"
                      :class="{
                        'is-invalid': v$.invoice.date.$error,
                      }"
                      type="date"
                      class="form-control"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice.date.$errors" :key="error">
                        {{ $t("INVOICE_DATE") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("INVOICE_NUMBER") }}</label
                    ><input
                      v-model="v$.invoice.invoice_number.$model"
                      :class="{
                        'is-invalid':
                          v$.invoice.invoice_number.$error || invoiceNumberExist,
                      }"
                      type="text"
                      class="form-control"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice.invoice_number.$errors"
                        :key="error"
                      >
                        {{
                          $t("INVOICE_NUMBER") + " " + $t(error.$validator, { value: 0 })
                        }}
                      </div>
                      <div
                        v-if="!v$.invoice.invoice_number.$invalid && invoiceNumberExist"
                      >
                        {{ $t("INVOICE_NUMBER") + " " + $t("EXIST") }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("INVOICE_CATEGORY") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.invoice.invoice_category_id.$model"
                      :class="{
                        'is-invalid': v$.invoice.invoice_category_id.$error,
                      }"
                    >
                      <option
                        v-for="invoiceCategory in invoiceCategories"
                        :key="invoiceCategory.id"
                        :value="invoiceCategory.id"
                      >
                        {{ invoiceCategory.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice.invoice_category_id.$errors"
                        :key="error"
                      >
                        {{ $t("INVOICE_CATEGORY") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("CUSTOMER") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.invoice.customer_id.$model"
                      :class="{
                        'is-invalid': v$.invoice.customer_id.$error,
                      }"
                    >
                      <option
                        v-for="customer in customers"
                        :key="customer.id"
                        :value="customer.id"
                      >
                        {{ customer.account.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice.customer_id.$errors" :key="error">
                        {{ $t("CUSTOMER") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("DELEGATE") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.invoice.delegate_id.$model"
                      :class="{
                        'is-invalid': v$.invoice.delegate_id.$error,
                      }"
                    >
                      <option
                        v-for="delegate in delegates"
                        :key="delegate.id"
                        :value="delegate.id"
                      >
                        {{ delegate.account.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.invoice.delegate_id.$errors" :key="error">
                        {{ $t("DELEGATE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="labels">{{ $t("SALE_PRICE") }}</label>
                    <select
                      class="form-control"
                      v-model="v$.invoice.sale_price_type.$model"
                      :class="{
                        'is-invalid': v$.invoice.sale_price_type.$error,
                      }"
                    >
                      <option value="RETAIL">{{ $t("RETAIL") }}</option>
                      <option value="WHOLESALE">{{ $t("WHOLESALE") }}</option>
                      <option value="HALF_WHOLESALE">{{ $t("HALF_WHOLESALE") }}</option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.invoice.sale_price_type.$errors"
                        :key="error"
                      >
                        {{ $t("SALE_PRICE") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <template v-if="invoiceCreated">
              <hr />
              <form>
                <!--Invoice items from-->
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{ $t("STORE") }}</label>
                      <select
                      @change="getBatches"
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
                        <div
                          v-for="error in v$.invoice_item.store_id.$errors"
                          :key="error"
                        >
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
                        <div
                          v-for="error in v$.invoice_item.item_id.$errors"
                          :key="error"
                        >
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
                      <label class="labels">{{ $t("SALE_PRICE") }}</label>
                      <select
                        @change="setSalePrice()"
                        class="form-control"
                        v-model="v$.invoice_item.sale_price_type.$model"
                        :class="{
                          'is-invalid': v$.invoice_item.sale_price_type.$error,
                        }"
                      >
                        <option value="RETAIL">{{ $t("RETAIL") }}</option>
                        <option value="WHOLESALE">{{ $t("WHOLESALE") }}</option>
                        <option value="HALF_WHOLESALE">{{ $t("HALF_WHOLESALE") }}</option>
                      </select>
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.invoice_item.sale_price_type.$errors"
                          :key="error"
                        >
                          {{ $t("SALE_PRICE") + " " + $t(error.$validator) }}
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
                        v-model="v$.invoice_item.is_sale_price_normal.$model"
                        :class="{
                          'is-invalid': v$.invoice_item.is_sale_price_normal.$error,
                        }"
                      >
                        <option value="NORMAL">{{ $t("NORMAL") }}</option>
                        <option value="BONUS">{{ $t("BONUS") }}</option>
                        <option value="ADVERTISING">{{ $t("ADVERTISING") }}</option>
                        <option value="STALE">{{ $t("STALE") }}</option>
                      </select>
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.invoice_item.is_sale_price_normal.$errors"
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
                          {{
                            $t("SALE_PRICE") + " " + $t(error.$validator, { value: 0 })
                          }}
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
                        <option
                          v-for="batch in batches"
                          :key="batch.id"
                          :value="batch.id"
                        >
                          <template v-if="batch.production_date && batch.expiration_date">
                            {{
                              $t("EXPIRE_BATCH_DESCRIPTION", {
                                quantity: getQuantity(batch),
                                unit: selectedUnitOfMeasure?.name,
                                price: getPurchasePrice(batch),
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
                                price: getPurchasePrice(batch),
                              })
                            }}
                          </template>
                        </option>
                      </select>
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.invoice_item.batch_id.$errors"
                          :key="error"
                        >
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
                        <div
                          v-for="error in v$.invoice_item.quantity.$errors"
                          :key="error"
                        >
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
              </form>
              <!--Invoice items table-->
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">{{ $t("STORE") }}</th>
                      <th scope="col">{{ $t("SALE_PRICE") }}</th>
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
                      <td>{{ $t(item.sale_price_type) }}</td>
                      <td>{{ item.item.name }}</td>
                      <td>{{ item.unit_of_measure.name }}</td>
                      <td>{{ item.sale_price }}</td>
                      <td>{{ item.quantity }}</td>
                      <td>{{ item.total_sale_price }}</td>
                      <td>
                        <button
                          @click="deleteInvoiceItem(item, index)"
                          class="border text-secondary action"
                        >
                          <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr />
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
            </template>
          </div>
          <div class="modal-footer">
            <button @click.prevent="save" type="submit" class="btn btn-danger">
              {{ invoiceCreated ? $t("APPROVE") : $t("SUBMIT") }}
            </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              {{ $t("CLOSE") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { minValue, required } from "@vuelidate/validators";
import saleInvoiceClient from "../../../shared/http-clients/sale-invoice/sale-invoice-client";
import saleInvoiceItemClient from "../../../shared/http-clients/sale-invoice/sale-invoice-item-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const sale_invoice_store = inject("sale_invoice_store");
    const { t, locale } = useI18n({ useScope: "global" });
    const toast = inject("toast");
    const data = reactive({
      customers: [],
      delegates: [],
      items: [],
      selectedItem: null,
      selectedUnitOfMeasure: null,
      stores: [],
      batches: [],
      invoiceItems: [],
      invoiceCreated: false,
      invoiceCategories: [],
      added_by: null,
      invoiceNumberExist: false,
    });
    const form = reactive({
      tax: 0,
      is_tax_percent: true,
      discount: 0,
      is_discount_percent: true,
      is_deferred: false,
      paid_amount: 0,
      invoice: {
        date: "",
        customer_id: null,
        invoice_category_id: null,
        invoice_number: 0,
        delegate_id: null,
        sale_price_type: "RETAIL",
      },
      invoice_item: {
        sale_invoice_id: null,
        store_id: null,
        item_id: null,
        batch_id: null,
        sale_price_type: "RETAIL",
        unit_of_measure_id: null,
        is_sale_price_normal: "NORMAL",
        quantity: 0,
        sale_price: 0,
      },
    });
    const rules = {
      invoice: {
        customer_id: { required },
        delegate_id: { required },
        invoice_number: { required, minValue: minValue(0) },
        invoice_category_id: { required, minValue: minValue(0) },
        date: { required },
        sale_price_type: { required },
      },
      invoice_item: {
        store_id: { required },
        item_id: { required },
        batch_id: { required },
        unit_of_measure_id: { required },
        sale_price_type: { required },
        is_sale_price_normal: { required },
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
      getCustomers();
      getDelegates();
      getItems();
      getStores();
      getInvoiceCategories();
    });
    //Methods
    function updateTax() {
      if (v$.value.tax.$invalid) {
        v$.value.tax.$touch();
        return;
      }
      saleInvoiceClient
        .updateTax({
          id: form.invoice_item.sale_invoice_id,
          is_tax_percent: form.is_tax_percent,
          tax: form.tax,
        })
        .then(() => {
          props.selectedSaleInvoice.tax = form.tax;
          props.selectedSaleInvoice.is_tax_percent = form.is_tax_percent ? 1 : 0;
        });
    }
    function updateDiscount() {
      if (v$.value.discount.$invalid) {
        v$.value.discount.$touch();
        return;
      }
      saleInvoiceClient
        .updateDiscount({
          id: form.invoice_item.sale_invoice_id,
          is_discount_percent: form.is_discount_percent,
          discount: form.discount,
        })
        .then(() => {
          props.selectedSaleInvoice.discount = form.discount;
          props.selectedSaleInvoice.is_discount_percent = form.is_discount_percent
            ? 1
            : 0;
        });
    }
    function getTotalAmount() {
      let total = 0;
      data.invoiceItems.forEach((item) => {
        total += Number.parseFloat(item.total_sale_price);
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
    function save() {
      if (!data.invoiceCreated) {
        if (v$.value.invoice.$invalid) {
          v$.value.invoice.$touch();
          return;
        }
        createSaleInvoice();
      } else {
        v$.value.invoice.$touch();
        v$.value.discount.$touch();
        v$.value.tax.$touch();
        v$.value.paid_amount.$touch();
        if (
          v$.value.invoice.$invalid ||
          v$.value.discount.$invalid ||
          v$.value.tax.$invalid ||
          v$.value.paid_amount.$invalid
        ) {
          return;
        }
        updateSaleInvoice();
      }
    }

    function getCustomers() {
      saleInvoiceClient.getCustomers().then((response) => {
        data.customers = response.data;
      });
    }
    function getDelegates() {
      saleInvoiceClient.getDelegates().then((response) => {
        data.delegates = response.data;
      });
    }
    function deleteInvoiceItem(item, index) {
      data.invoiceItems.splice(index, 1);
      saleInvoiceItemClient
        .deleteItem({
          store_id: item.store_id,
          item_id: item.item_id,
          unit_of_measure_id: item.unit_of_measure_id,
          quantity: item.quantity,
          batch_id: item.batch_id,
          sale_price: item.sale_price,
          sale_invoice_id: item.sale_invoice_id,
        })
        .then(() => {
          getInvoiceItems();
        });
    }
    function addItemToInvoice() {
      if (v$.value.invoice_item.$invalid) {
        v$.value.invoice_item.$touch();
        return;
      }
      saleInvoiceItemClient.addItemToInvoice(form.invoice_item).then(() => {
        getInvoiceItems();
        getItems();
        //Reset form
        v$.value.invoice_item.$reset();
        form.invoice_item.store_id = null;
        form.invoice_item.item_id = null;
        form.invoice_item.sale_price_type = "RETAIL";
        form.invoice_item.unit_of_measure_id = null;
        form.invoice_item.is_sale_price_normal = "NORMAL";
        form.invoice_item.quantity = 0;
        form.invoice_item.sale_price = 0;
        form.invoice_item.batch_id = null;
      });
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
      getBatches();
    }
    function setSelectedUnitOfMeasure() {
      data.selectedUnitOfMeasure = getSelectedUnitOfMeasure();
      setSalePrice();
      form.invoice_item.batch_id = null;
      getBatches();
    }
    function setSalePrice() {
      if (
        data.selectedItem &&
        data.selectedUnitOfMeasure &&
        form.invoice_item.unit_of_measure_id &&
        form.invoice_item.is_sale_price_normal === "NORMAL"
      )
        return (form.invoice_item.sale_price = getSalePrice());
      form.invoice_item.sale_price = 0;
    }
    function getBatches() {
      if (
        form.invoice_item.item_id &&
        form.invoice_item.store_id &&
        form.invoice_item.unit_of_measure_id
      ) {
        saleInvoiceItemClient
          .getBatches(form.invoice_item.store_id, form.invoice_item.item_id)
          .then((res) => {
            data.batches = res.data;
          });
      }
    }
    //Commons
    function updateSaleInvoice() {
      data.invoiceNumberExist = false;
      saleInvoiceClient
        .update({
          id: form.invoice_item.sale_invoice_id,
          ...form.invoice,
          discount: form.discount,
          is_discount_percent: form.is_discount_percent,
          tax: form.tax,
          is_tax_percent: form.is_tax_percent,
          is_deferred: form.is_deferred,
          paid_amount: form.paid_amount,
        })
        .then((response) => {
          toast.success(t("INVOICE_APPROVED"));
          context.emit("updated", {
            ...response.data.sale_invoice,
            customer: getCustomer(),
            invoice_category: getInvoiceCategory(),
            added_by: data.added_by,
            approved_by: response.data.user,
          });
          $("#saleInvoiceFormModal").modal("hide");
        })
        .catch((error) => {
          data.invoiceNumberExist = error.response.data.errors.invoice_number
            ? true
            : false;
        });
    }
    function getTaxValue(totalAmount) {
      return form.is_tax_percent ? (form.tax / 100) * totalAmount : form.tax;
    }
    function getDiscountValue(totalAmount) {
      return form.is_discount_percent
        ? (form.discount / 100) * totalAmount
        : form.discount;
    }
    function getInvoiceItems() {
      saleInvoiceItemClient
        .getInvoiceItems(form.invoice_item.sale_invoice_id)
        .then((response) => {
          data.invoiceItems = response.data;
        });
    }
    function createSaleInvoice() {
      data.invoiceNumberExist = false;
      saleInvoiceClient
        .createSaleInvoice(form.invoice)
        .then((response) => {
          data.invoiceCreated = true;
          context.emit("created", {
            ...response.data.sale_invoice,
            customer: getCustomer(),
            invoice_category: getInvoiceCategory(),
            added_by: response.data.user,
          });
          form.invoice_item.sale_invoice_id = response.data.sale_invoice.id;
          data.added_by = response.data.user;
          toast.success(t("CREATED_SUCCESSFULLY"));
        })
        .catch((error) => {
          data.invoiceNumberExist = error.response.data.errors.invoice_number
            ? true
            : false;
        });
    }
    function setForm() {
      data.invoiceNumberExist = false;
      v$.value.invoice.$reset();
      data.added_by = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.added_by
        : null;
      form.invoice.date = props.selectedSaleInvoice ? props.selectedSaleInvoice.date : "";
      form.invoice.customer_id = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.customer_id
        : null;
      form.invoice.delegate_id = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.delegate_id
        : null;
      form.invoice.invoice_number = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.invoice_number
        : null;
      form.invoice.invoice_category_id = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.invoice_category_id
        : null;
      form.invoice_item.sale_invoice_id = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.id
        : null;
      data.invoiceCreated = props.selectedSaleInvoice ? true : false;
      if (form.invoice_item.sale_invoice_id) {
        getInvoiceItems();
      }
      form.tax = props.selectedSaleInvoice ? props.selectedSaleInvoice.tax : 0;
      form.is_tax_percent = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.is_tax_percent == 1
          ? true
          : false
        : true;
      form.discount = props.selectedSaleInvoice ? props.selectedSaleInvoice.discount : 0;
      form.is_discount_percent = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.is_discount_percent == 1
          ? true
          : false
        : true;
      form.paid_amount = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.paid_amount
        : 0;
      form.is_deferred = props.selectedSaleInvoice
        ? props.selectedSaleInvoice.is_deferred == 1
          ? true
          : false
        : true;
      //Reset invoice item form
      v$.value.$reset();
      form.invoice_item.store_id = null;
      form.invoice_date = "";
      form.invoice_item.item_id = null;
      form.invoice_item.sale_price_type = "RETAIL";
      form.invoice_item.unit_of_measure_id = null;
      form.invoice_item.is_sale_price_normal = "NORMAL";
      form.invoice_item.quantity = 0;
      form.invoice_item.sale_price = 0;
      form.invoice_item.batch_id = null;
    }
    function getCustomer() {
      let customer = null;
      data.customers.forEach((_customer) => {
        if (_customer.id == form.invoice.customer_id) {
          customer = _customer;
          return;
        }
      });
      return customer;
    }
    function getInvoiceCategory() {
      let invoice_category = null;
      data.invoiceCategories.forEach((_invoice_category) => {
        if (_invoice_category.id == form.invoice.invoice_category_id) {
          invoice_category = _invoice_category;
          return;
        }
      });
      return invoice_category;
    }
    function getSalePrice() {
      if (data.selectedUnitOfMeasure.is_master) {
        if (form.invoice_item.sale_price_type === "RETAIL")
          return data.selectedItem.main_unit_retail_price;
        if (form.invoice_item.sale_price_type === "WHOLESALE")
          return data.selectedItem.main_unit_wholesale_price;
        if (form.invoice_item.sale_price_type === "HALF_WHOLESALE")
          return data.selectedItem.main_unit_half_wholesale_price;
      } else {
        if (form.invoice_item.sale_price_type === "RETAIL")
          return data.selectedItem.sub_unit_retail_price;
        if (form.invoice_item.sale_price_type === "WHOLESALE")
          return data.selectedItem.sub_unit_wholesale_price;
        if (form.invoice_item.sale_price_type === "HALF_WHOLESALE")
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
    function getInvoiceCategories() {
      saleInvoiceClient.getInvoiceCategories().then((response) => {
        data.invoiceCategories = response.data;
      });
    }
    //Watchers
    watch(
      () => {
        sale_invoice_store.onInvoiceShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      save,
      v$,
      updateTax,
      updateDiscount,
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
      getBatches,
    };
  },
  props: ["selectedSaleInvoice"],
};
</script>

<style scoped lang="scss">
.modal-header {
  border-color: #e9ecef !important;
}

.modal-footer {
  border: none !important;
  button {
    width: 80px;
  }
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
