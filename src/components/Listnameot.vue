<template>
  <div class="row-justify-center">
    <div class="col-md-12">
      <div class="q-pa-md">
        <q-table
          class="my-sticky-header-table fix-height"
          title="List Name"
          title-color="black"
          :rows="row_list_name"
          :columns="columns_list_name"
          row-key="emp_code"
          selection="multiple"
          :filter="filter"
          v-model:selected="selected"
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
            <q-btn
              class="q-mx-md"
              color="secondary"
              label="Select"
              @click="check()"
              v-close-popup
            />
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
      selected: [],
      filter: "",
      pagination: "",
      shift_code: "",
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
          name: "pren_code",
          align: "center",
          label: "คำนำหน้า",
          field: "pren_code",
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
          name: "emp_last_name",
          align: "center",
          label: "นามสกุล",
          field: "emp_last_name",
          sortable: true,
        },
        {
          name: "dept_code",
          align: "center",
          label: "รหัสแผนก",
          field: "dept_code",
          sortable: true,
        },
        {
          name: "dept_name",
          align: "center",
          label: "ชื่อแผนก",
          field: "dept_name",
          sortable: true,
        },
      ],
    };
  },
  props: {
    listnameot: {
      default: null,
      required: false,
    },
    listnameot2: {
      default: null,
      required: false,
    },
    listnameot3: {
      default: null,
      required: false,
    },
  },
  methods: {
    async check() {
      this.$emit("show", this.selected);
    },
  },
  async mounted() {
    this.shift_code = "";
    console.log(this.listnameot);
    console.log(this.listnameot2);
    console.log(this.listnameot3);
    console.log(this.listnameot != "23200");

    if (this.listnameot == "23100" || this.listnameot == "23200") {
      const params = new FormData();
      this.time_now2 = new Date();
      this.time_now_use2 = moment(this.time_now2).format("YYYY/MM/DD");
      params.append("dept_code", this.listnameot);
      if (this.listnameot3 == "กลางวัน") {
        this.value_shift_time = "17:00";
      } else if (this.listnameot3 == "กลางคืน") {
        this.value_shift_time = "05:00";
      }

      params.append("start_time", this.value_shift_time);
      params.append("start_date", this.time_now_use2);
      params.append("emp_type", this.listnameot2);

      for (var pair of params.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }

      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/get_user_sec_home",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.row_list_name.push({
                emp_code: e.EMP_CODE,
                pren_code: e.PREN_CODE,
                emp_name: e.EMP_NAME,
                emp_last_name: e.EMP_LAST_NAME,
                dept_name: e.DEPT_NAME,
                dept_code: e.DEPT_CODE,
              });
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    } else {
      const params = new FormData();
      this.time_now2 = new Date();
      this.time_now_use2 = moment(this.time_now2).format("YYYY/MM/DD");
      params.append("dept_code", this.listnameot);
      if (this.listnameot3 == "กลางวัน") {
        this.value_shift_time = "17:00";
      } else if (this.listnameot3 == "กลางคืน") {
        this.value_shift_time = "05:00";
      }
      if (this.listnameot3 == "กลางวัน") {
        if (this.listnameot2 == "M") {
          this.shift_code = "038";
        } else if (this.listnameot2 == "D") {
          this.shift_code = "039";
        }
      } else {
        this.shift_code = "040";
      }
      params.append("start_time", this.value_shift_time);
      params.append("start_date", this.time_now_use2);
      params.append("emp_type", this.listnameot2);
      params.append("shift_code", this.shift_code);
      for (var pair of params.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }

      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/get_user",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.row_list_name.push({
                emp_code: e.EMP_CODE,
                pren_code: e.PREN_CODE,
                emp_name: e.EMP_NAME,
                emp_last_name: e.EMP_LAST_NAME,
                dept_name: e.DEPT_NAME,
                dept_code: e.DEPT_CODE,
              });
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
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
