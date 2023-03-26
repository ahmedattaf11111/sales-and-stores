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
          <div class="row">
            <div class="col-md-6">
              <div class="search form-group">
                <label for="exampleInputEmail1">{{ $t("ACCOUNT_TYPE") }}</label>
                <select
                  @change="onSelectType"
                  v-model="account_type_id"
                  class="form-control"
                >
                  <option value="">-</option>
                  <option v-for="type in types" :key="type.id" :value="type.id">
                    {{ type.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="search form-group">
                <label for="exampleInputEmail1">{{ $t("IS_PARENT_ACCOUNT") }}</label>
                <select @change="onSelectMaster" class="form-control" v-model="is_master">
                  <option value="">-</option>
                  <option value="1">{{ $t("YES") }}</option>
                  <option value="0">{{ $t("NO") }}</option>
                </select>
              </div>
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
      account_type_id: "",
      is_master: "",
    });
    function onSelectType() {
      context.emit("onSelectType", data.account_type_id);
    }
    function onSelectMaster() {
      context.emit("onSelectMaster", data.is_master);
    }
    return { onSelectType, onSelectMaster, ...toRefs(data) };
  },
  props: ["types"],
};
</script>

<style scoped lang="scss">
.modal-header {
  border-color: #e9ecef !important;
}
.modal-footer {
  border: none !important;
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
  border-radius: 0 !important;
}
</style>
