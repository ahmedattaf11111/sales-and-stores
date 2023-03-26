<template>
  <div class="admin-panel-settings-container">
    <div class="header">
      <h2 class="welcome">
        <b>{{ $t("HELLO") }}</b
        >, {{ $t("WELCOME_HERE") }}
      </h2>
      <div class="title">
        <router-link to="/admin-panel-settings">{{ $t("HOME") }}</router-link>
        /
        <span>{{ $t("GENERAL_SETTINGS") }}</span>
      </div>
    </div>
    <div class="form-container mb-5 px-4">
      <form enctype="multipart/form-data" @submit.prevent="update">
        <div class="row">
          <div class="col-md-4">
            <div class="image-container">
              <img v-if="previewImage" class="img-fluid" :src="previewImage" />
              <img class="img-fluid" v-else src="/assets/images/empty.jpg" />
              <div class="image-upload">
                <input
                  accept="image/png, image/gif, image/jpeg"
                  id="file"
                  type="file"
                  @change="uploadImage"
                />
                <label class="icon" for="file">
                  <i class="fas fa-camera text-secondary"></i>
                </label>
                <label
                  @click="deleteImage"
                  v-if="uploadedImage"
                  class="icon text-secondary px-2"
                >
                  <i class="fa fa-window-close" aria-hidden="true"></i>
                </label>
              </div>
            </div>
            <div v-if="updated_by" class="fs-12 text-muted text-center">
              {{
                `${$t("UPDATED_AT")} (${updated_at
                  .replace("PM", $t("PM"))
                  .replace("AM", $t("AM"))}) ${$t("BY")} ${updated_by.name}`
              }}
            </div>
          </div>
          <div class="col-md-8">
            <div class="row">
              <div class="col-12">
                <label class="labels">{{ $t("SYSTEM_NAME") }}</label
                ><input
                  v-model="v$.system_name.$model"
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': v$.system_name.$error,
                  }"
                />
                <div class="invalid-feedback">
                  <div v-for="error in v$.system_name.$errors" :key="error">
                    {{ $t("SYSTEM_NAME") + " " + $t(error.$validator) }}
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mt-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{
                    $t("CUSTOMER_PARENT_ACCOUNT")
                  }}</label>
                  <select
                    class="form-control"
                    v-model="v$.customer_parent_account_id.$model"
                    :class="{
                      'is-invalid': v$.customer_parent_account_id.$error,
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
                    <div
                      v-for="error in v$.customer_parent_account_id.$errors"
                      :key="error"
                    >
                      {{ $t("CUSTOMER_PARENT_ACCOUNT") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mt-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{
                    $t("SUPPLIER_PARENT_ACCOUNT")
                  }}</label>
                  <select
                    class="form-control"
                    v-model="v$.supplier_parent_account_id.$model"
                    :class="{
                      'is-invalid': v$.supplier_parent_account_id.$error,
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
                    <div
                      v-for="error in v$.supplier_parent_account_id.$errors"
                      :key="error"
                    >
                      {{ $t("SUPPLIER_PARENT_ACCOUNT") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mt-2">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{
                    $t("DELEGATE_PARENT_ACCOUNT")
                  }}</label>
                  <select
                    class="form-control"
                    v-model="v$.delegate_parent_account_id.$model"
                    :class="{
                      'is-invalid': v$.delegate_parent_account_id.$error,
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
                    <div
                      v-for="error in v$.delegate_parent_account_id.$errors"
                      :key="error"
                    >
                      {{ $t("SUPPLIER_PARENT_ACCOUNT") + " " + $t(error.$validator) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mt-2">
                <label class="labels">{{ $t("MOBILE_NUMBER") }}</label
                ><input
                  v-model="v$.phone.$model"
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': v$.phone.$error,
                  }"
                />
                <div class="invalid-feedback">
                  <div v-for="error in v$.phone.$errors" :key="error">
                    {{ $t("MOBILE_NUMBER") + " " + $t(error.$validator) }}
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mt-2">
                <label class="labels">{{ $t("ADDRESS") }}</label
                ><input
                  v-model="v$.address.$model"
                  :class="{
                    'is-invalid': v$.address.$error,
                  }"
                  type="text"
                  class="form-control"
                />
                <div class="invalid-feedback">
                  <div v-for="error in v$.address.$errors" :key="error">
                    {{ $t("ADDRESS") + " " + $t(error.$validator) }}
                  </div>
                </div>
              </div>
              <div class="col-md-12 mt-2">
                <label class="labels">{{ $t("GENERAL_ALERT") }}</label
                ><textarea
                  v-model="general_alert"
                  type="text"
                  class="form-control"
                  rows="4"
                >
                </textarea>
              </div>
            </div>
            <div class="mt-3">
              <button class="btn btn-danger" type="submit">
                {{ $t("SUBMIT") }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import phoneValidator from "../../shared/validators/phone-validator";
import useVuelidate from "@vuelidate/core";
import adminPanelSettingClient from "../../shared/http-clients/admin-panel-settings-client";
import { required } from "@vuelidate/validators";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      previewImage: "",
      system_name: "",
      phone: "",
      address: "",
      accounts: [],
      customer_parent_account_id: null,
      supplier_parent_account_id: null,
      delegate_parent_account_id: null,
      general_alert: "",
      uploadedImage: null,
      oldImage: "",
      updated_at: "",
      updated_by: null,
    };
  },
  methods: {
    uploadImage(e) {
      const image = e.target.files[0];
      this.uploadedImage = image;
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        this.previewImage = e.target.result;
      };
    },
    deleteImage() {
      this.uploadedImage = null;
      this.previewImage = this.oldImage ? this.oldImage : "";
    },
    update() {
      if (this.v$.$invalid) {
        this.v$.$touch();
        return;
      }
      adminPanelSettingClient
        .update(this.getForm())
        .then((response) => {
          this.$toast.success(this.$t("UPDATED_SUCCESSFULLY"));
          this.uploadedImage = null;
          this.updated_at = response.data.admin_panel_settings.updated_at;
          this.updated_by = response.data.user;
          this.oldImage = response.data.admin_panel_settings.image;
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    //Commons
    getForm() {
      let formData = new FormData();
      formData.append("system_name", this.system_name);
      formData.append("phone", this.phone);
      formData.append("address", this.address);
      formData.append("customer_parent_account_id", this.customer_parent_account_id);
      formData.append("supplier_parent_account_id", this.supplier_parent_account_id);
      formData.append("delegate_parent_account_id", this.delegate_parent_account_id);
      this.appendField(formData, "general_alert", this.general_alert);
      this.appendField(formData, "image", this.uploadedImage);
      return formData;
    },
    appendField(formData, fieldName, fieldValue) {
      if (fieldValue) {
        formData.append(fieldName, fieldValue);
      }
    },
    setForm() {
      adminPanelSettingClient
        .getAdminPanelSettings()
        .then((response) => {
          this.system_name = response.data.system_name;
          this.customer_parent_account_id = response.data.customer_parent_account_id;
          this.supplier_parent_account_id = response.data.supplier_parent_account_id;
          this.delegate_parent_account_id = response.data.delegate_parent_account_id;
          this.phone = response.data.phone;
          this.address = response.data.address;
          this.general_alert = response.data.general_alert;
          this.updated_at = response.data.updated_at;
          this.updated_by = response.data.updated_by;
          this.oldImage = this.previewImage = response.data.image;
        })
        .catch((error) => {});
    },
  },
  validations() {
    return {
      system_name: { required },
      customer_parent_account_id: { required },
      supplier_parent_account_id: { required },
      delegate_parent_account_id: { required },
      address: { required },
      phone: { required, phoneValidator },
    };
  },
  created() {
    this.setForm();
    adminPanelSettingClient.getGeneralAccounts().then((response) => {
      this.accounts = response.data;
    });
  },
  inject: ["store"],
};
</script>

<style scoped lang="scss">
.admin-panel-settings-container {
  .image-container {
    margin-top: 10px;
    box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
  }
  .form-container {
    padding: 0 10px;
    form {
      background: #ffffff;
      box-shadow: 0 5px 20px rgb(0 0 0 / 10%);
      padding: 30px;
    }
  }
  label {
    color: rgba(108, 117, 125);
  }
  .header {
    * {
      font-size: 17px !important;
    }
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 30px;
    .welcome {
      padding-top: 9px;
    }
    .title {
      * {
        color: #6c757d !important;
      }
      a {
        text-decoration: none;
        color: #868e96 !important;
        &:hover {
          color: #6c757d !important;
        }
      }
    }
  }
  .image-upload {
    display: flex;
    justify-content: center;
    input[type="file"] {
      display: none;
    }
    .icon {
      &:hover {
        cursor: pointer;
      }
      i {
        font-size: 14px;
        position: relative;
        top: 1px;
      }
    }
  }
  .form-control {
    padding: 12px;
    background: none;
    border-radius: 0;
    border-color: #e7e7e7;
  }
}
</style>
