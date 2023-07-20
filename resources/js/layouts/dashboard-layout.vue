<template>
  <div class="dashboard-layout">
    <sidebar-menu
      theme="white-theme"
      :collapsed="collapsed"
      widthCollapsed="65px"
      width="250px"
      :rtl="dir == 'rtl' ? true : false"
      :menu="menu"
      :hideToggle="hideToggle"
      @update:collapsed="onToggleCollapse"
    >
      <template v-slot:header>
        <div class="image">
          <img src="/images/logo.png" />
          <span class="name">{{ $t("LOGO_NAME") }}</span>
        </div>
      </template>
    </sidebar-menu>
    <div
      :class="{ expandedMargin: !collapsed, collapsedMargin: collapsed }"
      class="content"
    >
      <div class="simple-nav">
        <ul>
          <li><Lang /></li>
          <li><avatar class="avatar" /></li>
        </ul>
      </div>
      <router-view />
    </div>
  </div>
</template>
<script>
import authClient from "../shared/http-clients/auth-client";
import Avatar from "../shared/components/avatar.vue";
import { SidebarMenu } from "vue-sidebar-menu";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
import Lang from "../shared/components/lang.vue";
export default {
  components: {
    Avatar,
    Lang,
    SidebarMenu,
  },
  data() {
    return {
      collapsed: false,
      hideToggle: false,
      menu: [],
      currentPermissions: [],
    };
  },
  computed: {
    dir() {
      return this.store.dir;
    },
  },
  watch: {
    dir: {
      handler(value) {
        authClient.currentPermissions().then((res) => {
          this.currentPermissions = res.data;
          this.setMenu();
          this.setPermissionToMenu();
        });
      },
      immediate: true,
    },
  },
  created() {
    this.myEventHandler("init");
    window.addEventListener("resize", this.myEventHandler);
  },
  methods: {
    onToggleCollapse(collapsed) {
      this.collapsed = collapsed;
      $(".v-sidebar-menu .image").css("display",collapsed?"none":"block");
    },
    setMenu() {
      this.menu = [
        {
          icon: "fas fa-store",
          id: 2,
          title: this.$t("STORES_SETTING"),
          child: [
            {
              id: 3,
              type: "item",
              name: "store",
              title: this.$t("STORES"),
              href: "/stores",
            },
            {
              id: 4,
              title: this.$t("ITEMS"),
              child: [
                {
                  id: 5,
                  type: "item",
                  name: "item_category",
                  title: this.$t("ITEM_CATEGORIES"),
                  href: "/item-categories",
                },
                {
                  id: 6,
                  type: "item",
                  name: "unit_of_measure",
                  title: this.$t("UNIT_OF_MEASURES"),
                  href: "/unit-of-measures",
                },
                {
                  id: 7,
                  type: "item",
                  name: "item",
                  title: this.$t("ITEMS"),
                  href: "/items",
                },
              ],
            },
          ],
        },
        {
          icon: "fas fa-calculator",

          id: 15,

          title: this.$t("CASH"),
          child: [
            {
              id: 16,
              type: "item",
              name: "treasury",
              title: this.$t("TREASURIES"),
              href: "/treasuries",
            },
            {
              id: 17,
              type: "item",
              name: "shift",
              title: this.$t("SHIFTS"),
              href: "/shifts",
            },
            {
              id: 18,
              type: "item",
              name: "collect_exchange_money",
              title: this.$t("COLLECT_EXCHANGE_MONEY"),
              href: "/treasury-transactions",
            },
          ],
        },
        {
          icon: "fas fa-chart-bar",
          id: 19,
          title: this.$t("INVOICES"),
          child: [
            {
              id: 20,
              title: this.$t("PURCHASES"),

              child: [
                {
                  id: 21,
                  type: "item",
                  name: "purchase_invoice",
                  title: this.$t("PURCHASE_INVOICES"),
                  href: "/purchase-invoices",
                },
                {
                  id: 22,
                  type: "item",
                  name: "purchase_return_invoice",
                  title: this.$t("GENERAL_PURCHASE_RETURN_INVOICES"),
                  href: "/purchase-return-invoices",
                },
              ],
            },
            {
              id: 23,
              title: this.$t("SALES"),
              child: [
                {
                  id: 24,

                  type: "item",
                  name: "invoice_category",
                  title: this.$t("INVOICE_CATEGORIES"),
                  href: "/invoice-categories",
                },
                {
                  id: 25,

                  type: "item",
                  name: "sale_invoice",
                  title: this.$t("SALES_INVOICES"),
                  href: "/sales-invoices",
                },
                {
                  id: 25,
                  type: "item",
                  name: "sale_return_invoice",
                  title: this.$t("GENERAL_PURCHASE_RETURN_INVOICES"),
                  href: "/sales-returns-invoices",
                },
              ],
            },
          ],
        },
        {
          icon: "fas fa-chart-bar",
          id: 23,
          title: this.$t("REPORTS"),
          child: [
            {
              id: 24,
              type: "item",
              name: "supplier_account_statement",
              title: this.$t("supplier_account_statement"),
              href: "/supplier_account_statement",
            },
          ],
        },
        {
          icon: "fa fa-users",

          id: 8,
          title: this.$t("ACCOUNTS"),
          child: [
            {
              id: 9,
              href: "/accounts",
              type: "item",
              name: "all_account",
              title: this.$t("ALL_ACCOUNTS"),
            },
            {
              id: 10,
              href: "/customers",

              type: "item",
              name: "customer_account",
              title: this.$t("CUSTOMER_ACCOUNTS"),
            },
            {
              id: 11,
              href: "/delegates",

              type: "item",
              name: "delegate_account",
              title: this.$t("DELEGATE_ACCOUNTS"),
            },
            {
              id: 12,

              title: this.$t("SUPPLIERS"),

              child: [
                {
                  id: 13,
                  href: "/supplier-categories",

                  type: "item",
                  name: "supplier_category",
                  title: this.$t("SUPPLIER_CATEGORIES"),
                },
                {
                  id: 14,

                  type: "item",
                  name: "supplier_account",
                  title: this.$t("SUPPLIER_ACCOUNTS"),
                  href: "/suppliers",
                },
              ],
            },
          ],
        },
        {
          icon: "fas fa-lock",
          id: 26,
          href: "/admins",
          title: this.$t("ADMINS"),
          type: "item",
          name: "admin",
        },
        {
          icon: "fa fa-cog",
          id: 1,
          title: this.$t("GENERAL_SETTINGS"),
          type: "item",
          name: "general_setting",
          href: "/admin-panel-settings",
        },
      ];
    },
    setPermissionToMenu() {
      //Hide the all href items
      let menu = [...this.menu];
      menu.forEach((levOneEl) => {
        if (levOneEl.child) {
          levOneEl.child.forEach((secLevEl) => {
            if (secLevEl.child) {
              secLevEl.child.forEach((thirdLevEl) => {
                thirdLevEl.hidden = !this.hasPermission(
                  `view ${thirdLevEl.name}`
                );
              });
            } else {
              secLevEl.hidden = !this.hasPermission(`view ${secLevEl.name}`);
            }
          });
        } else if (!levOneEl.hiddenOnCollapse) {
          levOneEl.hidden =
            !this.hasPermission(`view ${levOneEl.name}`) &&
            levOneEl.name !== "general_setting";
        }
      });
      //Hide the second parent links
      menu.forEach((levOneEl) => {
        if (levOneEl.child) {
          levOneEl.child.forEach((secLevEl) => {
            if (secLevEl.child) {
              secLevEl.hidden =
                secLevEl.child.length ==
                secLevEl.child.filter((e) => e.hidden).length;
            }
          });
        }
      });
      //Hide the first parent links
      menu.forEach((levOneEl) => {
        if (levOneEl.child) {
          levOneEl.hidden =
            levOneEl.child.length ==
            levOneEl.child.filter((e) => e.hidden).length;
        }
      });
      this.menu = [...menu];
    },
    hasPermission(permission) {
      let filterResult = this.currentPermissions.filter(
        (perm) => perm.name == permission || perm.name == "super admin"
      );
      return filterResult.length > 0 ? true : false;
    },
    myEventHandler(e) {
      let element = e == "init" ? window : e.currentTarget;
      if (element.innerWidth <= 820) {
        this.collapsed = true;
        this.hideToggle = true;
      } else {
        this.collapsed = false;
        this.hideToggle = false;
      }
    },
  },
  inject: ["store"],
};
</script>
<style lang="scss">
.dashboard-layout {
  .v-sidebar-menu {
    i.vsm--icon {
      background: none !important;
      width: 20px !important;
      color: #fff !important;
    }
    .vsm--item {
      * {
        font-size: 16px !important;
      }
    }
    .image {
      background: #182444 !important;
      padding: 16px 20px;
      span.name {
        margin: 0 18px;
        position: relative;
        top: 3px;
        font-size: 25px !important;
      }
      img {
        width: 40px;
        height: 40px;
        position: relative;
        bottom: 5px;
      }
    }
    @media (max-width: "820px") {
      .image {
        display: none;
      }
    }
    *,
    .vsm--toggle-btn {
      color: #fff !important;
    }

    &,
    .vsm--toggle-btn {
      background: #0f1a34;
    }
    .vsm--dropdown {
      background: #091023 !important;
    }
    .vsm--link_open,
    .vsm--link:hover {
      background: #091023 !important;
      * {
        font-size: 16px;
        color: #fff !important;
      }
    }
    .vsm--link {
      padding: 10px 15px;
    }
    .vsm--link_active {
      .vsm--title span,
      i.vsm--icon {
        color: #acb6c4 !important;
      }
    }
  }
  .avatar {
    margin-bottom: 5px;
  }

  body[dir="ltr"] & {
    .v-sidebar-menu {
      border-right: 1px solid #dee2e6;
    }

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

    .v-sidebar-menu {
      border-left: 1px solid #dee2e6;
    }
  }

  .content {
    transition: 0.3s ease;

    .simple-nav {
      display: flex;
      justify-content: flex-end;
      background: #fff;
      z-index: 990;
      ul {
        list-style: none;
        margin: 0;
        padding: 15px;
        list-style: none;
        margin: 0;
        padding: 19px 15px 10px 15px;
        li {
          padding: 0 10px;
          display: inline-block;
        }
      }
    }
  }
}
</style>