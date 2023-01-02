<template>
  <div class="row-justify-center">
    <div class="col-md-12">
      <div class="q-pa-md">
        <q-table
          class="my-sticky-header-table table_size_detail"
          title-color="black"
          :rows="list_prepare_update"
          :columns="columns_list_name_dept"
          row-key="req_no"
          :filter="filter"
          :v-model:pagination="pagination"
          :rows-per-page-options="[10]"
        >
          <template v-slot:top-right>
            <q-btn
              class="q-px-lg"
              size="lg"
              color="green-8"
              text-color="white"
              label="Update"
              @click="Update_data(list_prepare_update)"
              v-close-popup
            >
            </q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr class="my_table" :props="props">
              <!--  <q-td key="req_no" :props="props">
                          <q-checkbox :props="props" v-model="props.selected" />
                        </q-td> -->

              <q-td key="dept_code" :props="props">
                <q-input
                  v-model="props.row.dept_code"
                  input-class="text-center"
                  type="number"
                />
              </q-td>
              <q-td key="dept_name" :props="props">
                <q-input
                  v-model="props.row.dept_name"
                  input-class="text-center"
                />
              </q-td>
              <q-td key="dept_emp_app" :props="props">
                <q-input
                  v-model="props.row.dept_emp_app"
                  input-class="text-center"
                  type="number"
                />
              </q-td>

              <q-td key="divison_emp_app" :props="props">
                <q-input
                  v-model="props.row.divison_emp_app"
                  input-class="text-center"
                  type="number"
                />
              </q-td>
              <q-select
                class="q-px-xl q-py-md"
                outlined
                size="xl"
                v-model="props.row.shift"
                :options="select_shift"
                label="Shift"
              />
            </q-tr>
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
      shift: "",
      pagination: "",
      status_send: false,
      list_prepare_update: [],
      select_shift: ["", "sun", "night"],
      columns_list_name_dept: [
        {
          name: "dept_code",
          required: true,
          label: "DEPT CODE",
          align: "center",
          field: "dept_code",
          sortable: true,
        },

        {
          name: "dept_name",
          required: true,
          label: "DEPT NAME",
          align: "center",
          field: "dept_name",
          sortable: true,
        },

        {
          name: "dept_emp_app",
          align: "center",
          label: "DEPT EMP APP",
          field: "dept_emp_app",
          sortable: true,
        },

        {
          name: "divison_emp_app",
          align: "center",
          label: "DIVISON EMP APP",
          field: "divison_emp_app",
          sortable: true,
        },
        {
          name: "shift",
          align: "center",
          label: "SHIFT",
          field: "shift",
          sortable: true,
        },
      ],
    };
  },
  props: {
    Editdept1: {
      type: String,
      required: false,
    },
    Editdept2: {
      type: String,
      required: false,
    },
    Editdept3: {
      type: String,
      required: false,
    },
    Editdept4: {
      type: String,
      required: false,
    },
    Editdept5: {
      type: String,
      required: false,
    },
  },
  methods: {
    check() {
      this.$emit("show", "true");
    },
    async Update_data(val) {
      this.$emit("show", "true");
      const params = new FormData();
      params.append("dept_code", val[0].dept_code);
      params.append("dept_name", val[0].dept_name);
      params.append("dept_emp_app", val[0].dept_emp_app);
      params.append("divison_emp_app", val[0].divison_emp_app);
      params.append("shift", val[0].shift);
      params.append("dept_code_old", this.Editdept1);
      params.append("dept_name_old", this.Editdept2);
      params.append("dept_emp_app_old", this.Editdept3_change);
      params.append("divison_emp_app_old", this.Editdept4_change);
      params.append("shift_old", this.Editdept5_change);
      for (var pair of params.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }

      await axios({
        method: "post",
        url: this.$api_url + "/list_app.php/update_value_department",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          this.$q.notify({
            message: "Update Department Success",
            position: "center",
            timeout: 2000,
            icon: "announcement",
            color: "green-7",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  async mounted() {
    const params = new FormData();
    params.append("dept_code", this.Editdept1);
    params.append("dept_name", this.Editdept2);
    if (this.Editdept3 == null || this.Editdept3 == "") {
      this.Editdept3_change = "is null";
    } else {
      this.Editdept3_change = this.Editdept3;
    }
    params.append("dept_emp_app", this.Editdept3_change);

    if (this.Editdept4 == null || this.Editdept4 == "") {
      this.Editdept4_change = "is null";
    } else {
      this.Editdept4_change = this.Editdept4;
    }

    params.append("divison_emp_app", this.Editdept4_change);
    if (this.Editdept5 == null || this.Editdept5 == "") {
      this.Editdept5_change = "is null";
    } else {
      this.Editdept5_change = this.Editdept5;
    }
    params.append("shift", this.Editdept5_change);

    await axios({
      method: "post",
      url: this.$api_url + "/list_app.php/check_value_prepare_update",
      data: params,
    })
      .then((resp) => {
        console.log(resp.data);
        if (resp.data.data.length > 0) {
          resp.data.data.forEach((e) => {
            this.list_prepare_update.push({
              dept_code: e.DEPT_CODE,
              dept_name: e.DEPT_NAME,
              dept_emp_app: e.DEPT_EMP_APP,
              divison_emp_app: e.DIVISON_EMP_APP,
              shift: e.SHIFT,
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
