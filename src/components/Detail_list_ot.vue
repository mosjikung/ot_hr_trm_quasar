<template>
  <div class="row-justify-center">
    <div class="col-md-12">
      <div class="q-pa-md">
        <q-table
          class="my-sticky-header-table fix-height"
          title-color="black"
          :rows="row_list_name"
          :columns="columns_list_name"
          :filter="filter"
          row-key="emp_code"
          v-model:pagination="pagination"
          :rows-per-page-options="[10]"
        >
          <template v-slot:top-right>
            <q-input
              rounded
              outlined
              dense
              debounce="300"
              v-model="filter"
              placeholder="Search"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
        </q-table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useQuasar } from "quasar";
import { ref } from "vue";
import { onBeforeUnmount } from "vue";
import { Cookies } from "quasar";
import moment from "moment";
const $q = useQuasar();

const rows = [];
export default {
  data() {
    return {
      row_list_name: [],
      selected: ref([]),
      filter: "",
      pagination: "",
      columns_list_name: [
        {
          name: "emp_code",
          required: true,
          label: "รหัสพนักงาน",
          align: "center",
          field: "emp_code",
          sortable: true,
        },
        {
          name: "emp_name",
          align: "center",
          label: "ชื่อ",
          field: "emp_name",
          sortable: true,
        },
        {
          name: "start_date",
          align: "center",
          label: "START DATE",
          field: "start_date",
          sortable: true,
        },
        {
          name: "ot_hour",
          align: "center",
          label: "OT Hour",
          field: "ot_hour",
          sortable: true,
        },
        {
          name: "start_time",
          align: "center",
          label: "OT START",
          field: "start_time",
          sortable: true,
        },
        {
          name: "end_time",
          align: "center",
          label: "OT END",
          field: "end_time",
          sortable: true,
        },
      ],
    };
  },
  props: {
    detaillistot: {
      type: String,
    },
  },
  methods: {
    check() {
      this.$emit("show", this.selected);
    },
  },
  async mounted() {
    console.log(this.detaillistot);

    const params = new FormData();
    params.append("req_no", this.detaillistot);

    await axios({
      method: "post",
      url: this.$api_url + "/find_data.php/get_user_approve",
      data: params,
    })
      .then((resp) => {
        console.log(resp.data);
        if (resp.data.data.length > 0) {
          resp.data.data.forEach((e) => {
            this.row_list_name.push({
              emp_code: e.EMP_CODE,
              emp_name: e.EMP_NAME,
              ot_hour: parseFloat(e.OT_HOUR).toFixed(1),
              ot_start: e.OT_START,
              start_date: e.START_DATE,
              start_time: e.START_TIME,
              end_time: e.END_TIME,
            });
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });
  },
  getSelectedString() {
    return selected.value.length === 0
      ? ""
      : `${selected.value.length} record${
          selected.value.length > 1 ? "s" : ""
        } selected of ${rows.length}`;
  },
};
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  height: 400px

  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #FFFFFF

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
<style>
.fix-height {
  min-height: 800px;
}
</style>
