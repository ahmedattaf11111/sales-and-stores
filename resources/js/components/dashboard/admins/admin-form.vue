<template>
  <div class="admin-form">
    <div
      class="modal fade"
      id="adminFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("ADMINS") }}
              </h5>
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    id="profile-tab"
                    data-toggle="tab"
                    href="#profile"
                    role="tab"
                    aria-controls="profile"
                    aria-selected="false"
                    >{{ $t("DATA_ENTRY") }}</a
                  >
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="contact-tab"
                    data-toggle="tab"
                    href="#contact"
                    role="tab"
                    aria-controls="contact"
                    aria-selected="false"
                    >{{ $t("PERMISSINS") }}</a
                  >
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div
                  class="tab-pane fade show active"
                  id="profile"
                  role="tabpanel"
                  aria-labelledby="profile-tab"
                >
                  <div class="row p-3">
                    <div class="col-lg-8">
                      <div class="row">
                        <div class="col-lg-3 mb-2 p-0">
                          <div class="form-group">
                            <label for="exampleInputEmail1">{{
                              $t("NAME")
                            }}</label>
                            <input
                              type="text"
                              class="form-control"
                              v-model="v$.name.$model"
                              :class="{
                                'is-invalid': v$.name.$error,
                              }"
                            />
                            <div class="invalid-feedback">
                              <div
                                v-for="error in v$.name.$errors"
                                :key="error"
                              >
                                {{ $t("NAME") + " " + $t(error.$validator) }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-3 mb-2">
                          <div class="form-group">
                            <label for="exampleInputEmail1">{{
                              $t("EMAIL")
                            }}</label>
                            <input
                              type="text"
                              class="form-control"
                              v-model="v$.email.$model"
                              :class="{
                                'is-invalid': v$.email.$error || emailExist,
                              }"
                            />
                            <div class="invalid-feedback">
                              <div
                                v-for="error in v$.email.$errors"
                                :key="error"
                              >
                                {{ $t("EMAIL") + " " + $t(error.$validator) }}
                              </div>
                              <div v-if="!v$.email.$invalid && emailExist">
                                {{ $t("EMAIL") + " " + $t("EXIST") }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-3 mb-4">
                          <label>{{ $t("TREASURIES") }}</label>
                          <div class="multi-select">
                            <div
                              @click="treasuryClicked = !treasuryClicked"
                              class="header-sec"
                            >
                              {{
                                `${$t("TOTAL_SELECTED")}
                                                      (${
                                                        getSelectedTreasuriesIds()
                                                          .length
                                                      })`
                              }}
                            </div>
                            <div
                              v-if="treasuryClicked"
                              class="select border p-2"
                            >
                              <div
                                v-for="(treasury, index) in treasuries"
                                :key="treasury.id"
                                class="form-check"
                              >
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  @change="toggleTreasurySelection(treasury)"
                                  :id="index"
                                  :checked="treasury.selected"
                                />
                                <label
                                  class="form-check-label"
                                  for="flexCheckChecked"
                                >
                                  {{ treasury.name }}
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label>{{ $t("PASSWORD") }}</label>
                          <input
                            v-model="v$.password.$model"
                            :type="passwordHidden ? 'password' : 'text'"
                            class="form-control"
                            :class="{
                              'is-invalid': v$.password.$error,
                            }"
                          />
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.password.$errors"
                              :key="error"
                            >
                              {{ $t("PASSWORD") + " " + $t(error.$validator) }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label>{{ $t("PASSWORD_CONFIRMATION") }} </label>
                          <input
                            v-model="v$.password_confirmation.$model"
                            :type="passwordHidden ? 'password' : 'text'"
                            class="form-control"
                            :class="{
                              'is-invalid': v$.password_confirmation.$error,
                            }"
                          />
                          <div class="invalid-feedback">
                            <div
                              v-for="error in v$.password_confirmation.$errors"
                              :key="error"
                            >
                              {{
                                $t("PASSWORD_CONFIRMATION") +
                                " " +
                                $t(error.$validator)
                              }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-check">
                          <input
                            @change="passwordHidden = !passwordHidden"
                            class="form-check-input"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                          <label
                            class="form-check-label"
                            for="flexCheckDefault"
                          >
                            {{ $t("SHOW_PASSWORD") }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="tab-pane fade permissions"
                  id="contact"
                  role="tabpanel"
                  aria-labelledby="contact-tab"
                >
                  <div class="row mt-3">
                    <div class="col-lg-3">
                      <sidebar-menu
                        v-if="reset"
                        @item-click="onItemClicked"
                        theme="white-theme"
                        :rtl="dir == 'rtl' ? true : false"
                        :menu="menu"
                        :hideToggle="true"
                      />
                    </div>
                    <div class="col-lg-9">
                      <div class="perm-container row">
                        <div
                          class="col-lg-3 mb-4"
                          v-for="permission in screenPermissions"
                        >
                          <div class="permission-item border">
                            <span class="border-bottom">
                              {{
                                $i18n.locale == "ar"
                                  ? permission.name
                                  : permission.name_e
                              }}
                            </span>
                            <div class="perm active">
                              <label class="switch">
                                <input
                                  v-model="permission.selected"
                                  type="checkbox"
                                  :checked="permission.selected"
                                />
                                <span class="slider round"></span>
                              </label>
                            </div>
                          </div>
                        </div>
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
import { SidebarMenu } from "vue-sidebar-menu";
import useVuelidate from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import strong from "../../../shared/validators/strong-password-validator";
import adminClient from "../../../shared/http-clients/admin-client";
import {
  handleError,
  inject,
  nextTick,
  onMounted,
  reactive,
  toRefs,
  watch,
} from "vue-demi";
import { useI18n } from "vue-i18n";
import VueMultiselect from "vue-multiselect";
export default {
  components: {
    SidebarMenu,
    VueMultiselect,
  },
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const admin_store = inject("admin_store");
    const _store = inject("store");
    const toast = inject("toast");
    const swal = inject("swal");
    const data = reactive({
      emailExist: false,
      passwordHidden: true,
      treasuryClicked: false,
      currentScreen: t("GENERAL_SETTINGS"),
      menu: [],
      treasuries: [],
      prevItem: null,
      reset: true,
      permissions: getPermissions(),
      screenPermissions: [],
    });
    const form = reactive({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    });
    const rules = {
      name: { required },
      email: { required, email },
      password: {
        required: (value) => {
          if (!props.selectedAdmin) return value || value === 0;
          else return true;
        },
        strong,
      },
      password_confirmation: {
        required: (value) => {
          if (!props.selectedAdmin) return value || value === 0;
          else return true;
        },
        sameAsPassword(value) {
          if (!value) {
            return true;
          }
          return form.password == value;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      adminClient
        .getAllTreasuries(props.selectedAdmin ? props.selectedAdmin.id : "")
        .then((response) => {
          data.treasuries = response.data;
        });
    });
    //Methods
    function getPermissions() {
      return [
        //general setting
        {
          selected: false,
          value: "update general_setting",
          name: "تعديل",
          name_e: "Edit",
        },
        //store
        {
          selected: false,
          value: "create store",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update store",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete store",
          name: "حذف",
          name_e: "Delete",
        },
        { selected: false, value: "view store", name: "عرض", name_e: "View" },
        //item category
        {
          selected: false,
          value: "create item_category",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update item_category",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete item_category",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view item_category",
          name: "عرض",
          name_e: "View",
        },
        //Unit of measure
        {
          selected: false,
          value: "create unit_of_measure",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update unit_of_measure",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete unit_of_measure",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view unit_of_measure",
          name: "عرض",
          name_e: "View",
        },
        //Item
        { selected: false, value: "create item", name: "اضافة", name_e: "Add" },
        {
          selected: false,
          value: "update item",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete item",
          name: "حذف",
          name_e: "Delete",
        },
        { selected: false, value: "view item", name: "عرض", name_e: "View" },
        //all_account
        {
          selected: false,
          value: "create all_account",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update all_account",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete all_account",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view all_account",
          name: "عرض",
          name_e: "View",
        },
        //customer accounts
        {
          selected: false,
          value: "create customer_account",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update customer_account",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete customer_account",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view customer_account",
          name: "عرض",
          name_e: "View",
        },
        //delegate accounts
        {
          selected: false,
          value: "create delegate_account",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update delegate_account",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete delegate_account",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view delegate_account",
          name: "عرض",
          name_e: "View",
        },
        //supplier accounts
        {
          selected: false,
          value: "create supplier_account",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update supplier_account",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete supplier_account",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view supplier_account",
          name: "عرض",
          name_e: "View",
        },
        //supplier categories
        {
          selected: false,
          value: "create supplier_category",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update supplier_category",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete supplier_category",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view supplier_category",
          name: "عرض",
          name_e: "View",
        },
        //treasury
        {
          selected: false,
          value: "create treasury",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update treasury",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete treasury",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view treasury",
          name: "عرض",
          name_e: "View",
        },
        //shift
        {
          selected: false,
          value: "create shift",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update shift",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete shift",
          name: "حذف",
          name_e: "Delete",
        },
        { selected: false, value: "view shift", name: "عرض", name_e: "View" },
        { selected: false, value: "close shift", name: "غلق", name_e: "close" },
        //collect exchange money
        {
          selected: false,
          value: "create collect_exchange_money",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update collect_exchange_money",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete collect_exchange_money",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view collect_exchange_money",
          name: "عرض",
          name_e: "View",
        },
        //purchase invoice
        {
          selected: false,
          value: "create purchase_invoice",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update purchase_invoice",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete purchase_invoice",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view purchase_invoice",
          name: "عرض",
          name_e: "View",
        },
        {
          selected: false,
          value: "approve purchase_invoice",
          name: "اعتماد",
          name_e: "approve",
        },
        //purchase return invoice
        {
          selected: false,
          value: "create purchase_return_invoice",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update purchase_return_invoice",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete purchase_return_invoice",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view purchase_return_invoice",
          name: "عرض",
          name_e: "View",
        },
        {
          selected: false,
          value: "approve purchase_return_invoice",
          name: "اعتماد",
          name_e: "approve",
        },
        //sale invoice
        {
          selected: false,
          value: "create sale_invoice",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update sale_invoice",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete sale_invoice",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view sale_invoice",
          name: "عرض",
          name_e: "View",
        },
        {
          selected: false,
          value: "approve sale_invoice",
          name: "اعتماد",
          name_e: "approve",
        },
        //sale return invoice
        {
          selected: false,
          value: "create sale_return_invoice",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update sale_return_invoice",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete sale_return_invoice",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view sale_return_invoice",
          name: "عرض",
          name_e: "View",
        },
        {
          selected: false,
          value: "approve sale_return_invoice",
          name: "اعتماد",
          name_e: "approve",
        },
        //supplier_account_statement
        {
          selected: false,
          value: "view supplier_account_statement",
          name: "عرض",
          name_e: "View",
        },
        //invoice category
        {
          selected: false,
          value: "create invoice_category",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update invoice_category",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete invoice_category",
          name: "حذف",
          name_e: "Delete",
        },
        {
          selected: false,
          value: "view invoice_category",
          name: "عرض",
          name_e: "View",
        },
        //admin
        {
          selected: false,
          value: "create admin",
          name: "اضافة",
          name_e: "Add",
        },
        {
          selected: false,
          value: "update admin",
          name: "تعديل",
          name_e: "Edit",
        },
        {
          selected: false,
          value: "delete admin",
          name: "حذف",
          name_e: "Delete",
        },
        { selected: false, value: "view admin", name: "عرض", name_e: "View" },
      ];
    }
    async function resetMenu() {
      let menu = [...data.menu];
      menu.forEach((levOneEl) => {
        levOneEl.disabled = levOneEl.name == "general_setting" ? true : false;
        if (levOneEl.child) {
          levOneEl.child.forEach((secLevEl) => {
            secLevEl.disabled = false;
            if (secLevEl.child) {
              secLevEl.child.forEach((thirdLevEl) => {
                thirdLevEl.disabled = false;
              });
            }
          });
        }
      });
      data.menu = [...menu];
      data.reset = false;
      await nextTick();
      data.reset = true;
    }
    function isPermissionExist(permissions, permission) {
      let filter = permissions.filter((perm) => perm.name == permission.value);
      return filter.length > 0;
    }
    function setSelectedPermissions() {
      console.log(props.selectedAdmin ? props.selectedAdmin.permissions : []);
      data.permissions = data.permissions.map((perm) => {
        return {
          ...perm,
          selected: isPermissionExist(
            props.selectedAdmin ? props.selectedAdmin.permissions : [],
            perm
          ),
        };
      });
    }
    function getSelectedPermissions() {
      return data.permissions
        .filter((perm) => perm.selected)
        .map((perm) => perm.value);
    }
    function getScreenPermissions(screenName) {
      return data.permissions.filter((permission) => {
        return (
          permission.value
            .replace("create ", "")
            .replace("view ", "")
            .replace("delete ", "")
            .replace("update ", "")
            .replace("close ", "")
            .replace("approve ", "") == screenName
        );
      });
    }
    function toggleTreasurySelection(treasury) {
      treasury.selected = !treasury.selected;
    }
    function getSelectedTreasuries() {
      return data.treasuries.filter((treasury) => {
        return treasury.selected;
      });
    }
    function getSelectedTreasuriesIds() {
      return data.treasuries
        .filter((treasury) => {
          return treasury.selected;
        })
        .map((treasury) => treasury.id);
    }
    function setSelectedTreasuries() {
      if (props.selectedAdmin) {
        data.treasuries.forEach((treasury) => {
          treasury.selected = props.selectedAdmin.treasuries
            .map((_treasury) => (_treasury.id ? _treasury.id : _treasury))
            .includes(treasury.id);
        });
      } else {
        data.treasuries.forEach((treasury) => (treasury.selected = false));
      }
    }

    function onItemClicked(event, item) {
      if (item.type == "item") {
        data.screenPermissions = getScreenPermissions(item.name);
        data.currentScreen = item.title;
        data.menu.forEach((levOneEl) => {
          levOneEl.disabled = item.name == levOneEl.name;
          if (levOneEl.child) {
            levOneEl.child.forEach((secLevEl) => {
              secLevEl.disabled = item.name == secLevEl.name;
              if (secLevEl.child) {
                secLevEl.child.forEach((thirdLevEl) => {
                  thirdLevEl.disabled = item.name == thirdLevEl.name;
                });
              }
            });
          }
        });
      }
    }
    function setMenu() {
      data.menu = [
        {
          id: 1,
          title: t("GENERAL_SETTINGS"),
          disabled: true,
          type: "item",
          name: "general_setting",
        },
        {
          id: 2,
          title: t("STORES_SETTING"),
          child: [
            {
              id: 3,
              type: "item",
              name: "store",
              title: t("STORES"),
            },
            {
              id: 4,
              title: t("ITEMS"),

              child: [
                {
                  id: 5,
                  type: "item",
                  name: "item_category",
                  title: t("ITEM_CATEGORIES"),
                },
                {
                  id: 6,
                  type: "item",
                  name: "unit_of_measure",
                  title: t("UNIT_OF_MEASURES"),
                },
                {
                  id: 7,
                  type: "item",
                  name: "item",
                  title: t("ITEMS"),
                },
              ],
            },
          ],
        },
        {
          id: 23,
          title: t("REPORTS"),
          child: [
            {
              id: 24,
              type: "item",
              name: "supplier_account_statement",
              title: t("supplier_account_statement"),
            },
          ],
        },
        {
          id: 8,
          title: t("ACCOUNTS"),
          child: [
            {
              id: 9,

              type: "item",
              name: "all_account",
              title: t("ALL_ACCOUNTS"),
            },
            {
              id: 10,

              type: "item",
              name: "customer_account",
              title: t("CUSTOMER_ACCOUNTS"),
            },
            {
              id: 11,

              type: "item",
              name: "delegate_account",
              title: t("DELEGATE_ACCOUNTS"),
            },
            {
              id: 12,

              title: t("SUPPLIERS"),

              child: [
                {
                  id: 13,

                  type: "item",
                  name: "supplier_category",
                  title: t("SUPPLIER_CATEGORIES"),
                },
                {
                  id: 14,

                  type: "item",
                  name: "supplier_account",
                  title: t("SUPPLIER_ACCOUNTS"),
                },
              ],
            },
          ],
        },
        {
          id: 15,

          title: t("CASH"),
          child: [
            {
              id: 16,
              type: "item",
              name: "treasury",
              title: t("TREASURIES"),
            },
            {
              id: 17,
              type: "item",
              name: "shift",
              title: t("SHIFTS"),
            },
            {
              id: 18,
              type: "item",
              name: "collect_exchange_money",
              title: t("COLLECT_EXCHANGE_MONEY"),
            },
          ],
        },
        {
          id: 19,
          title: t("INVOICES"),
          child: [
            {
              id: 20,
              title: t("PURCHASES"),

              child: [
                {
                  id: 21,
                  type: "item",
                  name: "purchase_invoice",
                  title: t("PURCHASE_INVOICES"),
                },
                {
                  id: 22,
                  type: "item",
                  name: "purchase_return_invoice",
                  title: t("GENERAL_PURCHASE_RETURN_INVOICES"),
                },
              ],
            },
            {
              id: 23,
              title: t("SALES"),

              child: [
                {
                  id: 24,

                  type: "item",
                  name: "invoice_category",
                  title: t("INVOICE_CATEGORIES"),
                },
                {
                  id: 25,

                  type: "item",
                  name: "sale_invoice",
                  title: t("SALES_INVOICES"),
                },
                {
                  id: 26,
                  type: "item",
                  name: "sale_return_invoice",
                  title: t("GENERAL_PURCHASE_RETURN_INVOICES"),
                },
              ],
            },
          ],
        },
        {
          id: 26,

          title: t("ADMINS"),
          type: "item",
          name: "admin",
          disabled: false,
        },
      ];
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedAdmin) {
        store();
      } else {
        update();
      }
    }
    //Commons

    function store() {
      data.emailExist = false;
      adminClient
        .create(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("CREATED_SUCCESSFULLY"),
          });
          context.emit("created");
          $("#adminFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
          data.emailExist = error.response.data.errors.email ? true : false;
        });
    }
    function update() {
      data.emailExist = false;
      adminClient
        .update(getForm())
        .then((response) => {
          swal({
            confirmButtonText: t("OK"),
            icon: "success",
            title: t("SUCCESS"),
            text: t("UPDATED_SUCCESSFULLY"),
          });
          context.emit("updated");
          $("#adminFormModal").modal("hide");
        })
        .catch((error) => {
          if (error.response.status == 403) {
            toast.error(t("DONT_HAVE_THIS_PERMISSION"));
            return;
          }
          data.emailExist = error.response.data.errors.email ? true : false;
        });
    }
    function getForm() {
      return {
        id: props.selectedAdmin ? props.selectedAdmin.id : null,
        name: form.name,
        email: form.email,
        password: form.password,
        treasuries_ids: getSelectedTreasuriesIds(),
        permissions: getSelectedPermissions(),
      };
    }
    async function setForm() {
      v$.value.$reset();
      data.screenPermissions = [];
      data.emailExist = false;
      form.name = props.selectedAdmin ? props.selectedAdmin.name : "";
      form.email = props.selectedAdmin ? props.selectedAdmin.email : "";
      form.password = "";
      form.password_confirmation = "";
      data.treasuryClicked = false;
      setSelectedTreasuries();
      setSelectedPermissions();
      resetMenu();
      onItemClicked(null, data.menu[0]);
    }
    //Watchers
    watch(
      () => {
        admin_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    watch(
      () => {
        _store.dir;
      },
      (value) => {
        setMenu();
      },
      { deep: true, immediate: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      ...toRefs(_store),
      toggleTreasurySelection,
      getSelectedTreasuriesIds,
      onItemClicked,
      v$,
      save,
    };
  },
  props: ["selectedAdmin"],
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style lang="scss">
.admin-form {
  .permissions {
    font-size: 13px !important;
  }
  .permission-item {
    border-radius: 5px;
    span {
      color: gray !important;
      padding: 11px;
      text-align: center;
      display: inline-block;
      width: 100%;
      border-radius: 0px;
    }
  }
  .submit {
    background: #373063 !important;
    color: #fff !important;
  }
  .nav-tabs {
    border-bottom-style: none !important;
    margin-bottom: 20px;
    .nav-link {
      color: #373063;
    }
  }
  .vsm--link .vsm--title span {
    font-size: 13px !important;
  }
  .vsm--link .vsm--title .vsm--arrow_default {
    font-size: 13px !important;
  }
  .card-header {
    text-align: center;
    background: none !important;
  }

  .btn-link {
    text-decoration: none !important;
    color: inherit !important;
    font-weight: bold !important;
    text-transform: uppercase;
  }
  @media (min-width: 992px) {
    .v-sidebar-menu {
      overflow: auto !important;
      height: 300px !important;
    }
  }
  .v-sidebar-menu,
  .v-sidebar-menu .vsm--dropdown,
  .v-sidebar-menu .vsm--toggle-bt .vsm--toggle-btn {
    background: #fff !important;
  }

  .v-sidebar-menu {
    .vsm--header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.175);
      padding: 10px;
    }

    .vsm--menu {
      width: unset !important;
    }

    position: unset !important;
    max-width: unset !important;

    i.vsm--icon {
      background: none !important;
    }

    .vsm--header {
      text-align: center;
    }

    *,
    .vsm--toggle-btn {
      color: inherit !important;
    }

    .vsm--link_open,
    .vsm--link:hover {
      background: #fff !important;
      * {
        color: #373063 !important;
      }
    }

    .vsm--link_active {
      * {
        color: #fff !important;
      }
    }
  }

  .avatar {
    margin-bottom: 5px;
  }
  .v-sidebar-menu {
    border-left: unset !important;
  }
  body[dir="ltr"] & {
    .expandedMargin {
      margin-left: 250px;
    }

    .collapsedMargin {
      margin-left: 65px;
    }
  }

  body[dir="rtl"] & {
    .expandedMargin {
      margin-right: 250px;
    }

    .collapsedMargin {
      margin-right: 65px;
    }
  }

  .card-body {
    padding: 0 !important;
  }
  .form-check-label {
    position: relative;
    top: 0px;
  }

  .multi-select {
    .header-sec {
      border-radius: 5px;
      border: 1px solid #e7e7e7 !important;
      text-align: center;
      padding: 9px !important;
      &:hover {
        cursor: pointer;
      }
    }

    .select {
      border-top: none !important;
      height: 120px;
      overflow: auto;
    }
  }

  .card {
    border-color: #e9ecef !important;
    border-radius: 0 !important;
  }

  .user-perms {
    .user-perm {
      padding: 6px 13px;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      justify-content: space-between;
    }
  }

  .permissions-header {
    margin-top: 12px !important;
    display: flex;
    justify-content: space-between;

    .permissions-line {
      color: #b9b9b9;
      margin-top: 17px;
      width: 100%;
    }

    .permissions-title {
      padding: 0 37px;
      background-color: #f1f1f1;
      border-radius: 4px;
    }
  }

  .active {
    display: flex;
    justify-content: flex-end;
  }
  .perm-container {
  }
  .perm {
    width: 100%;
    margin: 25px 0;
    display: flex;
    justify-content: center;
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
      background-color: #373063;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #373063;
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

  label {
    margin-bottom: 5px;
  }

  .form-group {
    margin-bottom: 10px;

    .form-control {
      background-color: transparent;
      padding: 10px;
    }
  }

  .modal-footer {
    button {
      width: 80px;
    }
  }

  .increments {
    width: 35px;
    height: 34px;
    text-align: center;
    border-radius: 5px;
    background: none;

    i {
      font-size: 10px !important;
    }
  }

  .decrement {
    background: none;
    border: none;

    i {
      font-size: 10px !important;
    }
  }

  .vsm--link_disabled {
    opacity: 1 !important;
    background: #f9f9f9;
  }
  input,
  .select {
    border-color: #e7e7e7 !important;
    border-radius: 5px !important;

    &:checked {
      background-color: #373063 !important;
      border-color: #373063 !important;
    }
  }
}
</style>