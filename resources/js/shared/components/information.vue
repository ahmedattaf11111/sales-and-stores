<template>
  <!-- Modal -->
  <div
    class="modal fade"
    id="info"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary" id="exampleModalLabel">
            {{ $t("INFORMATION") }}
          </h5>
          <button
            type="button"
            class="btn-close"
            data-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div
            class="row"
            v-for="(info, index) in infos"
            :key="index"
            :class="{ 'border-bottom': index + 1 != infos.length }"
          >
            <div class="text-bold col-6">
              <b>{{ $t(info.header) }}</b>
            </div>
            <div class="col-6">
              {{ getText(info) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { useI18n } from "vue-i18n";
export default {
  setup() {
    const { t, locale } = useI18n({ useScope: "global" });
    //Methods
    function getText(info) {
      if (info.textTranslated) return t(info.text);
      else if (info.textDateTime)
        return info.text.replace("PM", t("PM")).replace("AM", t("AM"));
      else return info.text;
    }
    return { getText };
  },
  props: ["infos"],
};
</script>

<style scoped lang="scss">
.modal-header {
  border-color: #e9ecef !important;
}
.modal-body {
  .border-bottom {
    border-color: #e9ecef !important;
  }
}
</style>
