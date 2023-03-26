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
            <div class="col-lg-6">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ $t("STORE") }}</label>
                <select @change="onSelectStore" class="form-control" v-model="store_id">
                  <option value="">-</option>
                  <option v-for="store in stores" :key="store.id" :value="store.id">
                    {{ store.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ $t("SUPPLIER") }}</label>
                <select
                  @change="onSelectSupplier"
                  class="form-control"
                  v-model="supplier_id"
                >
                  <option value="">-</option>
                  <option
                    v-for="supplier in suppliers"
                    :key="supplier.id"
                    :value="supplier.id"
                  >
                    {{ supplier.account.name }}
                  </option>
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
      store_id: "",
      supplier_id: "",
    });
    function onSelectStore() {
      context.emit("onStoreSelected", data.store_id);
    }
    function onSelectSupplier() {
      context.emit("onSupplierSelected", data.supplier_id);
    }
    return { onSelectStore, onSelectSupplier, ...toRefs(data) };
  },
  props: ["stores", "suppliers"],
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
