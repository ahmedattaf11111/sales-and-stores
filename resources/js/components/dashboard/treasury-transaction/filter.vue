<template>
  <!-- Modal -->
  <div
    class="modal fade"
    id="filter"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary" id="exampleModalLabel">
            {{ $t("FILTER") }}
          </h5>
          <button
            type="button"
            class="btn-close"
            data-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ $t("TYPE") }}</label>
              <select @change="onSelectType" class="form-control" v-model="type">
                <option value="">-</option>
                <option value="COLLECT">{{ $t("COLLECT") }}</option>
                <option value="EXCHANGE">{{ $t("EXCHANGE") }}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ $t("CLOSE") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { reactive, toRefs } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const data = reactive({
      type: "",
    });
    function onSelectType() {
      context.emit("onTypeSelected", data.type);
    }
    return { onSelectType, ...toRefs(data) };
  },
};
</script>

<style scoped lang="scss">
.modal-header {
  border-color: #e9ecef !important;
}
.modal-footer{
    border:none !important;
    button {
      width: 80px;
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
  select,
  textarea {
    border-color: #e7e7e7 !important;
    border-radius: 5px !important;
  }
</style>
