<template>
    <div class="mt-5 mb-2 text-center">
      <Lang />
    </div>
    <router-view />
</template>
<script>
import Lang from "../shared/components/lang.vue";
export default {
  components: {
    Lang,
  },
  data() {
    return {};
  },
};
</script>
<style scoped>
</style>