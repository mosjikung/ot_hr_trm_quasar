<template>
  <q-card class="my-card q-pa-xl col-md-12">
    <div class="row justify-center">
      <div class="col-md-12">
        <q-card squarez class="my-card q-pa-md area_item card_size">
          <q-banner
            dense
            inline-actions
            rounded
            class="bg-black text-white banner_size"
          >
            <h6 class="q-mt-xs">Department Code</h6>
            <template v-slot:action> </template>
          </q-banner>
          <q-card-section>
            <div class="row">
              <div class="col-md-5"></div>
              <div class="col-md-2 q-px-md">
                <q-select
                  rounded
                  outlined
                  dense
                  v-model="value_emp_code"
                  :options="list_emp_code"
                  label="Chose Dept"
                />
              </div>
              <div class="col-md-1"></div>

              <div class="col-md-1"></div>

              <div class="col-md-1"></div>
              <div class="col-md-1"></div>
              <div class="col-md-1">
                <q-btn
                  class="q-px-md"
                  size="md"
                  color="orange-7"
                  label="Reset"
                  @click="reload"
                ></q-btn>
              </div>
            </div>

            <div class="row justify-center">
              <div class="col-md-12">
                <div class="q-pa-md">
                  <q-table
                    class="my-sticky-header-table table_size"
                    title-color="black"
                    :rows="list_data_chose"
                    :columns="columns_list_name_ot"
                    row-key="req_no"
                    selection="multiple"
                    :filter="filter"
                    v-model:selected="selected"
                    v-model:pagination="pagination"
                    :rows-per-page-options="[10]"
                  >
                    <template v-slot:top-left>
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
                    <template v-slot:top-right>
                      <q-btn
                        class="q-px-lg"
                        size="lg"
                        color="green-7"
                        text-color="white"
                        label="Save"
                        @click="Save_approve_ot"
                      >
                      </q-btn>
                    </template>
                    <template v-slot:body="props">
                      <q-tr class="my_table" :props="props">
                        <q-td key="req_no" :props="props">
                          <q-checkbox :props="props" v-model="props.selected" />
                        </q-td>
                        <q-td key="req_no" :props="props">
                          {{ props.row.req_no }}
                        </q-td>
                        <q-td key="req_date" :props="props">
                          {{ props.row.req_date }}
                        </q-td>
                        <q-td
                          key="start_time"
                          v-if="props.row.start_time == '05:30'"
                          :props="props"
                        >
                          กลางคืน
                        </q-td>
                        <q-td
                          key="start_time"
                          v-if="props.row.start_time == '17:00'"
                          :props="props"
                        >
                          กลางวัน
                        </q-td>
                        <q-td key="ot_code" :props="props">
                          {{ props.row.ot_code }}
                        </q-td>
                        <q-td key="dept_name" :props="props">
                          {{ props.row.dept_name }}
                        </q-td>
                        <q-td key="ot_type" :props="props">
                          {{ props.row.ot_type }}
                        </q-td>
                        <q-td key="req_no" :props="props">
                          <q-btn
                            color="orange-7"
                            label="Detail"
                            @click="open_detail_list_name(props.row)"
                          />
                        </q-td>
                      </q-tr>
                    </template>
                  </q-table>
                </div>
              </div>
            </div>
          </q-card-section>
        </q-card>
        <br />
        <q-dialog
          v-model="status_open_list_name_ot"
          transition-show="rotate"
          transition-hide="rotate"
        >
          <q-card style="min-width: 100%">
            <q-card-section>
              <div class="row">
                <div class="col-md-12">
                  <div class="bg-black text-white">
                    <q-toolbar>
                      <q-btn flat round dense />

                      <q-toolbar-title>Detail OT</q-toolbar-title>

                      <q-btn
                        icon="cancel"
                        flat
                        fixed-right
                        color="white"
                        v-close-popup
                      />
                    </q-toolbar>
                  </div>
                </div>
              </div>

              <Detaillistot
                :detaillistot="this.value_components"
              ></Detaillistot>
            </q-card-section>

            <div
              class="q-pt-md"
              style="margin-top: 0px; padding-top: 0px"
            ></div>
          </q-card>
        </q-dialog>

        <br />
      </div>
    </div>
    <div></div>
  </q-card>
</template>

<script>
import axios from "axios";
import { useQuasar } from "quasar";
import Detaillistot from "../components/Detail_list_ot.vue";
import { ref } from "vue";
import { onBeforeUnmount } from "vue";
import { Cookies } from "quasar";
import moment from "moment";
const $q = useQuasar();
const rows = [];
export default {
  data() {
    return {
      status_emp: "",
      list_data_chose: [],
      list_emp_code: [],
      value_emp_code: "",
      value_components: "",
      filter: "",
      pagination: "",
      arr_value_update: [],
      status_open_list_name_ot: false,
      selected: ref([]),

      columns_list_name_ot: [
        {
          name: "req_no",
          required: true,
          label: "รหัสใบขอOT",
          align: "center",
          field: "req_no",
          sortable: true,
        },

        {
          name: "req_date",
          required: true,
          label: "REQ_DATE",
          align: "center",
          field: "req_date",
          sortable: true,
        },

        {
          name: "start_time",
          align: "center",
          label: "Shift Type",
          field: "start_time",
          sortable: true,
        },

        {
          name: "dept_name",
          align: "center",
          label: "Dept Name",
          field: "dept_name",
          sortable: true,
        },
        {
          name: "ot_type",
          align: "center",
          label: "OT TYPE",
          field: "ot_type",
          sortable: true,
        },

        {
          name: "list_name",
          align: "center",
          label: "List_name",
          field: "list_name",
        },
      ],
    };
  },
  methods: {
    async Save_approve_ot() {
      for (var ax = 0; ax < this.selected.length; ax++) {
        const params = new FormData();
        let username = this.$q.localStorage.getItem("username");
        params.append("req_no", this.selected[ax].req_no);
        params.append("username", username);

        await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/check_before_update_data_for_hr",
          data: params,
        }).then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            this.$q.notify({
              message: "Can't Update" + "This Req.No Has been Approve",
              position: "center",
              icon: "announcement",
              color: "red-9",
            });
            this.refresh_data();
          } else {
            axios({
              method: "post",
              url: this.$api_url + "/find_data.php/update_data_for_hr",
              data: params,
            }).then((resp) => {
              console.log(resp.data);

              if (resp.data.status == true) {
                this.$q.notify({
                  message: "Update Complete",
                  position: "center",
                  icon: "announcement",
                  color: "orange-7",
                });

                axios({
                  method: "post",
                  url:
                    this.$api_url +
                    "/find_data.php/check_n_y_update_data_for_hr",
                  data: params,
                }).then((resp) => {
                  if (resp.data.data.length > 0) {
                    axios({
                      method: "post",
                      url: this.$api_url + "/find_data.php/excute_pd",
                      data: params,
                    })
                      .then((resp) => {
                        console.log(resp.data);
                      })
                      .catch((error) => {
                        console.log(error);
                      });
                  }
                });
                this.refresh_data();
              }
            });
          }
        });
      }
    },
    push_data(index) {
      console.log(index);
    },
    open_detail_list_name(index) {
      this.status_open_list_name_ot = true;
      this.value_components = index.req_no;
      console.log(this.value_components);
    },
    async refresh_data() {
      this.list_data_chose = [];
      const params = new FormData();
      params.append("emp_code", this.emp_code_input);
      let dept_code = this.$q.localStorage.getItem("dept_code");
      params.append("dept_code", dept_code);

      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/approve_data_for_hr",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.list_data_chose.push({
                req_no: e.REQ_NO,
                req_date: e.REQ_DATE,
                ot_code: e.OT_CODE,
                dept_name: e.DEPT_NAME,
                ot_type: e.OT_TYPE,
                start_time: e.START_TIME,
              });
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async search_data_from_input() {
      this.list_data_chose = [];
      const params = new FormData();

      params.append("dept_code", this.value_emp_code);

      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/chose_data_hr",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.list_data_chose.push({
                req_no: e.REQ_NO,
                req_date: e.REQ_DATE,
                ot_code: e.OT_CODE,
                dept_name: e.DEPT_NAME,
                ot_type: e.OT_TYPE,
                start_time: e.START_TIME,
              });
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  components: {
    Detaillistot,
  },
  async mounted() {
    let dept_code = this.$q.localStorage.getItem("dept_code");
    let username = this.$q.localStorage.getItem("username");

    if (username == null) {
      this.$router.push({
        path: "/",
      });
    }

    if (dept_code != "23000") {
      this.$q.notify({
        message: "You No have permission for this page",
        position: "center",
        timeout: 2000,
        icon: "announcement",
        color: "red-9",
      });
      this.$router.push({
        path: "/main",
      });
    }

    const params = new FormData();

    await axios({
      method: "post",
      url: this.$api_url + "/find_data.php/approve_data_for_hr",
      data: params,
    })
      .then((resp) => {
        console.log(resp.data);
        if (resp.data.data.length > 0) {
          resp.data.data.forEach((e) => {
            this.list_data_chose.push({
              req_no: e.REQ_NO,
              req_date: e.REQ_DATE,
              ot_code: e.OT_CODE,
              dept_name: e.DEPT_NAME,
              ot_type: e.OT_TYPE,
              start_time: e.START_TIME,
            });
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });

    await axios({
      method: "post",
      url: this.$api_url + "/find_data.php/list_for_hr_app",
    }).then((resp) => {
      console.log(resp.data);
      var k = 0;

      resp.data.data.forEach((e) => {
        this.list_emp_code[k] = e.DEPT_CODE;
        k++;
      });
    });
  },

  getSelectedString() {
    return selected.value.length === 0
      ? ""
      : `${selected.value.length} record${
          selected.value.length > 1 ? "s" : ""
        } selected of ${rows.length}`;
  },
  watch: {
    value_emp_code: function () {
      this.search_data_from_input();
    },
  },
};
</script>

<style>
.area_item {
  min-height: 100px;
}
.padding_select {
  padding-top: 5%;
}
.my_table:hover {
  background-color: #f5efe6;
}
.banner_size {
  height: 60px;
}
.card_size {
  height: 990px;
}
.table_size {
  min-height: 800px;
}
</style>

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
