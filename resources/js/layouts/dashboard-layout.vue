<template>
  <div class="dashboard-layout">
    <sidebar-menu
      theme="white-theme"
      :collapsed="collapsed"
      widthCollapsed="65px"
      width="290px"
      :rtl="dir == 'rtl' ? true : false"
      :menu="menu"
      :hideToggle="hideToggle"
      @update:collapsed="collapsed = !collapsed"
    />
    <div
      :class="{ expandedMargin: !collapsed, collapsedMargin: collapsed }"
      class="content"
    >
      <div class="simple-nav border-bottom">
        <Lang />
        <avatar class="avatar" />
      </div>
      <router-view />
    </div>
  </div>
</template>
<script>
import Avatar from "../shared/components/avatar.vue";
import Lang from "../shared/components/lang.vue";
import { SidebarMenu } from "vue-sidebar-menu";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
export default {
  components: {
    Lang,
    Avatar,
    SidebarMenu,
  },
  data() {
    return {
      collapsed: false,
      hideToggle: false,
      menu: [],
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
        this.menu = [
          {
            header: "Main Navigation",
            hiddenOnCollapse: true,
          },
          {
            href: "/home",
            title: this.$t("HOME"),
            icon: "fa fa-home",
          },
          {
            href: "/hellos",
            title: this.$t("HELLO"),
            icon: "fa fa-user",
          },
          {
            title: "Charts",
            icon: "fa fa-chart-area",
            child: [
              {
                href: "/charts/sublink",
                title: "Sub Link",
              },
            ],
          },
        ];
      },
      immediate: true,
    },
  },
  created() {
    this.myEventHandler("init");
    window.addEventListener("resize", this.myEventHandler);
  },
  methods: {
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
  .avatar {
    margin-bottom: 5px;
  }
  body[dir="ltr"] & {
    .v-sidebar-menu {
      border-right: 1px solid #dee2e6;
    }
    .expandedMargin {
      margin-left: 290px;
    }
    .collapsedMargin {
      margin-left: 65px;
    }
  }
  body[dir="rtl"] & {
    .expandedMargin {
      margin-right: 290px;
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
      padding: 10px;
      display: flex;
      justify-content: flex-end;
    }
  }
}
</style>