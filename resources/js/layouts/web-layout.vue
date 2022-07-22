<template>
  <div>
    <div class="simple-nav">
      <Lang />
      <Avatar v-if="token" />
    </div>
    <router-view />
  </div>
</template>
<script>
import Lang from "../shared/components/lang.vue";
import Avatar from "../shared/components/avatar.vue";
import TokenUtil from "../shared/utils/token-util";
export default {
  components: {
    Lang,
    Avatar,
  },
  data() {
    return {
      token: TokenUtil.get(),
    };
  },
};
</script>
<style scoped>
.simple-nav {
  padding: 10px;
  display: flex;
  justify-content: flex-end;
}
</style>