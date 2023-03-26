<template>
  <div class="item-form">
    <div
      class="modal fade"
      id="itemFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("ITEMS") }}
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
                <div class="col-lg-4 mb-3">
                  <div class="image">
                    <img v-if="previewImage" class="img-fluid" :src="previewImage" />
                    <img v-else class="img-fluid" src="/assets/images/empty.jpg" />
                    <div class="image-upload">
                      <label class="icon" for="image">
                        <i class="fa fa-camera"></i>
                      </label>
                      <label
                        @click="deleteImage"
                        v-if="uploadedImage"
                        class="icon text-secondary px-2"
                      >
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                      </label>
                      <input
                        @change="uploadImage"
                        accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp"
                        type="file"
                        id="image"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="row">
                    <div class="active col-12">
                      <label class="switch">
                        <input v-model="active" type="checkbox" :checked="active" />
                        <span class="slider round"></span>
                      </label>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("NAME") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.name.$model"
                          :class="{
                            'is-invalid': v$.name.$error || nameExist,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.name.$errors" :key="error">
                            {{ $t("NAME") + " " + $t(error.$validator) }}
                          </div>
                          <div v-if="!v$.name.$invalid && nameExist">
                            {{ $t("NAME") + " " + $t("EXIST") }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("CATEGORY") }}</label>
                        <select
                          class="form-control"
                          v-model="v$.item_category_id.$model"
                          :class="{
                            'is-invalid': v$.item_category_id.$error,
                          }"
                        >
                          <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                        <div class="invalid-feedback">
                          <div v-for="error in v$.item_category_id.$errors" :key="error">
                            {{ $t("CATEGORY") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("TYPE") }}</label>
                        <select
                          class="form-control"
                          v-model="v$.type.$model"
                          :class="{
                            'is-invalid': v$.type.$error,
                          }"
                        >
                          <option value="STORE">{{ $t("STORE") }}</option>
                          <option value="HAS_EXPIRATION_DATE">
                            {{ $t("HAS_EXPIRATION_DATE") }}
                          </option>
                          <option value="CUSTODY">{{ $t("CUSTODY") }}</option>
                        </select>
                        <div class="invalid-feedback">
                          <div v-for="error in v$.type.$errors" :key="error">
                            {{ $t("TYPE") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("MAIN_UNIT") }}</label>
                        <select
                          class="form-control"
                          v-model="v$.main_unit_of_measure_id.$model"
                          :class="{
                            'is-invalid': v$.main_unit_of_measure_id.$error,
                          }"
                        >
                          <template :key="unit.id" v-for="unit in unit_of_measures">
                            <option v-if="unit.is_master" :value="unit.id">
                              {{ unit.name }}
                            </option>
                          </template>
                        </select>
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.main_unit_of_measure_id.$errors"
                            :key="error"
                          >
                            {{ $t("MAIN_UNIT") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("HALF_WHOLESALE_PRICE")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.main_unit_half_wholesale_price.$model"
                          :class="{
                            'is-invalid': v$.main_unit_half_wholesale_price.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.main_unit_half_wholesale_price.$errors"
                            :key="error"
                          >
                            {{
                              $t("HALF_WHOLESALE_PRICE") +
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
                          $t("WHOLESALE_PRICE")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.main_unit_wholesale_price.$model"
                          :class="{
                            'is-invalid': v$.main_unit_wholesale_price.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.main_unit_wholesale_price.$errors"
                            :key="error"
                          >
                            {{
                              $t("WHOLESALE_PRICE") +
                              " " +
                              $t(error.$validator, { value: 0 })
                            }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("RETAIL_PRICE") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.main_unit_retail_price.$model"
                          :class="{
                            'is-invalid': v$.main_unit_retail_price.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.main_unit_retail_price.$errors"
                            :key="error"
                          >
                            {{
                              $t("RETAIL_PRICE") +
                              " " +
                              $t(error.$validator, { value: 0 })
                            }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          v-model="has_fixed_price"
                          class="form-check-input"
                          id="exampleCheck1"
                        />
                        <label class="form-check-label" for="exampleCheck1">{{
                          $t("HAS_FIXED_PRICE")
                        }}</label>
                      </div>
                      <div class="form-check has-sub-unit">
                        <input
                          type="checkbox"
                          v-model="has_sub_unit"
                          class="form-check-input"
                          id="exampleCheck1"
                        />
                        <label class="form-check-label" for="exampleCheck1">{{
                          $t("HAS_SUB_UNIT")
                        }}</label>
                      </div>
                    </div>

                    <template v-if="has_sub_unit">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="exampleInputEmail1">{{ $t("SUB_UNIT") }}</label>
                          <select
                            class="form-control"
                            v-model="v$.sub_unit_of_measure_id.$model"
                            :class="{
                              'is-invalid': v$.sub_unit_of_measure_id.$error,
                            }"
                          >
                            <template :key="unit.id" v-for="unit in unit_of_measures">
                              <option v-if="!unit.is_master" :value="unit.id">
                                {{ unit.name }}
                              </option>
                            </template>
                          </select>
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.sub_unit_of_measure_id.$errors"
                              :key="error"
                            >
                              {{ $t("SUB_UNIT") + " " + $t(error.$validator) }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="exampleInputEmail1">{{
                            $t("SUB_TO_MAIN_QUANTITY")
                          }}</label>
                          <input
                            type="text"
                            class="form-control"
                            v-model="v$.sub_to_main_quantity.$model"
                            :class="{
                              'is-invalid': v$.sub_to_main_quantity.$error,
                            }"
                          />
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.sub_to_main_quantity.$errors"
                              :key="error"
                            >
                              {{
                                $t("SUB_TO_MAIN_QUANTITY") +
                                " " +
                                $t(error.$validator, { value: 2 })
                              }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="exampleInputEmail1">{{
                            $t("HALF_WHOLESALE_PRICE")
                          }}</label>
                          <input
                            type="text"
                            class="form-control"
                            v-model="v$.sub_unit_half_wholesale_price.$model"
                            :class="{
                              'is-invalid': v$.sub_unit_half_wholesale_price.$error,
                            }"
                          />
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.sub_unit_half_wholesale_price.$errors"
                              :key="error"
                            >
                              {{
                                $t("HALF_WHOLESALE_PRICE") +
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
                            $t("WHOLESALE_PRICE")
                          }}</label>
                          <input
                            type="text"
                            class="form-control"
                            v-model="v$.sub_unit_wholesale_price.$model"
                            :class="{
                              'is-invalid': v$.sub_unit_wholesale_price.$error,
                            }"
                          />
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.sub_unit_wholesale_price.$errors"
                              :key="error"
                            >
                              {{
                                $t("WHOLESALE_PRICE") +
                                " " +
                                $t(error.$validator, { value: 0 })
                              }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="exampleInputEmail1">{{ $t("RETAIL_PRICE") }}</label>
                          <input
                            type="text"
                            class="form-control"
                            v-model="v$.sub_unit_retail_price.$model"
                            :class="{
                              'is-invalid': v$.sub_unit_retail_price.$error,
                            }"
                          />
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.sub_unit_retail_price.$errors"
                              :key="error"
                            >
                              {{
                                $t("RETAIL_PRICE") +
                                " " +
                                $t(error.$validator, { value: 0 })
                              }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
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
import itemClient from "../../../shared/http-clients/item-client";
import { inject, onMounted, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const item_store = inject("item_store");
    const toast = inject("toast");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
      nameExist: false,
      unit_of_measures: [],
    });
    const form = reactive({
      name: "",
      item_category_id: null,
      type: "",
      active: true,
      has_fixed_price: true,
      main_unit_of_measure_id: null,
      main_unit_half_wholesale_price: 0,
      main_unit_wholesale_price: 0,
      main_unit_retail_price: 0,
      has_sub_unit: false,
      sub_to_main_quantity: 2,
      sub_unit_of_measure_id: null,
      sub_unit_half_wholesale_price: 0,
      sub_unit_wholesale_price: 0,
      sub_unit_retail_price: 0,
    });
    const rules = {
      name: { required },
      item_category_id: { required },
      type: { required },
      main_unit_of_measure_id: { required },
      main_unit_half_wholesale_price: { required, minValue: minValue(0) },
      main_unit_wholesale_price: { required, minValue: minValue(0) },
      main_unit_retail_price: { required, minValue: minValue(0) },
      sub_unit_of_measure_id: {
        required: (value) => {
          if (form.has_sub_unit) return value || value === 0;
          else return true;
        },
      },
      sub_to_main_quantity: {
        required: (value) => {
          if (form.has_sub_unit) return value || value === 0;
          else return true;
        },
        minValue: (value) => {
          if (form.has_sub_unit && value) return value >= 2;
          else return true;
        },
      },
      sub_unit_half_wholesale_price: {
        required: (value) => {
          if (form.has_sub_unit) return value || value === 0;
          else return true;
        },
        minValue: (value) => {
          if (form.has_sub_unit && value) return value >= 0;
          else return true;
        },
      },
      sub_unit_wholesale_price: {
        required: (value) => {
          if (form.has_sub_unit) return value || value === 0;
          else return true;
        },
        minValue: (value) => {
          if (form.has_sub_unit && value) return value >= 0;
          else return true;
        },
      },
      sub_unit_retail_price: {
        required: (value) => {
          if (form.has_sub_unit) return value || value === 0;
          else return true;
        },
        minValue: (value) => {
          if (form.has_sub_unit && value) return value >= 0;
          else return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getUnitOfMeasures();
    });
    //Methods
    function uploadImage(e) {
      const image = e.target.files[0];
      data.uploadedImage = image;
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        data.previewImage = e.target.result;
      };
    }
    function deleteImage() {
      data.uploadedImage = null;
      data.previewImage =
        props.selectedItem && props.selectedItem.image ? props.selectedItem.image : "";
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedItem) store();
      else update();
    }
    //Commons
    function getUnitOfMeasures() {
      itemClient.getUnitOfMeasures().then((response) => {
        data.unit_of_measures = response.data;
      });
    }
    function store() {
      data.nameExist = false;
      itemClient
        .create(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", {
            ...response.data.item,
            added_by: response.data.user,
          });
          $("#itemFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function update() {
      data.nameExist = false;
      itemClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", {
            ...response.data.item,
            added_by: props.selectedItem.added_by,
            updated_by: response.data.user,
          });
          $("#itemFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
        });
    }
    function getForm() {
      let formData = new FormData();
      formData.append("name", form.name);
      formData.append("item_category_id", form.item_category_id);
      formData.append("type", form.type);
      formData.append("active", form.active ? 1 : 0);
      formData.append("has_fixed_price", form.has_fixed_price ? 1 : 0);
      //Main unit
      formData.append("main_unit_of_measure_id", form.main_unit_of_measure_id);
      formData.append(
        "main_unit_half_wholesale_price",
        form.main_unit_half_wholesale_price
      );
      formData.append("main_unit_wholesale_price", form.main_unit_wholesale_price);
      formData.append("main_unit_retail_price", form.main_unit_retail_price);
      //Sub unit
      formData.append("has_sub_unit", form.has_sub_unit ? 1 : 0);
      appendField(formData, "sub_to_main_quantity", form.sub_to_main_quantity);
      appendField(formData, "sub_unit_of_measure_id", form.sub_unit_of_measure_id);
      appendField(formData, "sub_unit_of_measure_id", form.sub_unit_of_measure_id);
      appendField(
        formData,
        "sub_unit_half_wholesale_price",
        form.sub_unit_half_wholesale_price
      );
      appendField(formData, "sub_unit_wholesale_price", form.sub_unit_wholesale_price);
      appendField(formData, "sub_unit_retail_price", form.sub_unit_retail_price);
      appendField(formData, "id", props.selectedItem ? props.selectedItem.id : null);
      appendField(formData, "image", data.uploadedImage);
      return formData;
    }
    function appendField(formData, fieldName, fieldValue) {
      if (fieldValue) {
        formData.append(fieldName, fieldValue);
      }
    }
    function setForm() {
      v$.value.$reset();
      data.previewImage = props.selectedItem ? props.selectedItem.image : "";
      form.name = props.selectedItem ? props.selectedItem.name : "";
      form.item_category_id = props.selectedItem
        ? props.selectedItem.item_category_id
        : null;
      form.type = props.selectedItem ? props.selectedItem.type : "";
      form.active = props.selectedItem ? toBoolean(props.selectedItem.active) : true;
      form.has_fixed_price = props.selectedItem
        ? toBoolean(props.selectedItem.has_fixed_price)
        : true;
      //Main unit
      form.main_unit_of_measure_id = props.selectedItem
        ? props.selectedItem.main_unit_of_measure_id
        : null;
      form.main_unit_half_wholesale_price = props.selectedItem
        ? props.selectedItem.main_unit_half_wholesale_price
        : 0;
      form.main_unit_wholesale_price = props.selectedItem
        ? props.selectedItem.main_unit_wholesale_price
        : 0;
      form.main_unit_retail_price = props.selectedItem
        ? props.selectedItem.main_unit_retail_price
        : 0;
      form.has_sub_unit = props.selectedItem
        ? toBoolean(props.selectedItem.has_sub_unit)
        : false;
      //Sub unit
      form.sub_unit_of_measure_id = props.selectedItem
        ? props.selectedItem.sub_unit_of_measure_id
        : null;
      form.sub_to_main_quantity = props.selectedItem
        ? props.selectedItem.sub_to_main_quantity
        : 2;
      form.sub_unit_half_wholesale_price = props.selectedItem
        ? props.selectedItem.sub_unit_half_wholesale_price
        : 0;
      form.sub_unit_wholesale_price = props.selectedItem
        ? props.selectedItem.sub_unit_wholesale_price
        : 0;
      form.sub_unit_retail_price = props.selectedItem
        ? props.selectedItem.sub_unit_retail_price
        : 0;
      data.uploadedImage = null;
      data.nameExist = false;
    }
    function toBoolean(value) {
      return value == 1 ? true : false;
    }
    //Watchers
    watch(
      () => {
        item_store.onFormShow;
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
      uploadImage,
      deleteImage,
      save,
    };
  },
  props: ["selectedItem", "categories"],
};
</script>

<style scoped lang="scss">
.item-form {
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
  .form-group {
    margin-bottom: 10px;
    .form-control {
      background-color: transparent;
      padding: 10px;
    }
  }
  input,
  select {
    border-color: #e7e7e7;
    border-radius: 0 !important;
  }
  input:checked {
    background: #6d85fb;
    border-color: #6d85fb !important;
  }
  .icons {
    i {
      font-size: 20px;
    }
    i:hover {
      cursor: pointer;
    }
  }
  .image {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 45px;
    box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
    .image-upload {
      i {
        margin-top: 7px;
        color: #888888;
      }
      .icon {
        &:hover {
          cursor: pointer !important;
        }
        i {
          font-size: 14px;
          position: relative;
        }
      }
      text-align: center;
      input[type="file"] {
        display: none;
      }
    }
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
  .form-check-label {
    position: relative;
    bottom: 4px;
  }
  .form-check.has-sub-unit {
    margin-bottom: -6px;
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
}
</style>
