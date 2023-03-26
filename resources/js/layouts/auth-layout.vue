<template>
  <div class="mt-5 mb-2">
    <div class="col-lg-4 col-md-6 m-auto">
      <Lang />
    </div>
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
<style scoped></style>
