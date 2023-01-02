<template>
  <q-card class="my-card q-pa-xl col-md-12">
    <div class="row justify-center">
      <div class="col-md-12">
        <q-card square="true" class="my-card q-pa-md area_item card_size">
          <q-banner
            dense
            inline-actions
            rounded
            class="bg-black text-white banner_size"
          >
            <h6 class="q-mt-xs">List Hr Confirm Today</h6>
            <template v-slot:action> </template>
          </q-banner>
          <q-card-section>
            <div class="row">
              <div class="col-md-3"></div>
              <!-- <div class="col-md-2 q-px-md">
                <q-input
                  standout="bg-orange-7 text-white"
                  v-model="emp_code_input"
                  dense
                  label="Input Employee Code"
                />
              </div>
              <div class="col-md-1"></div> -->

              <!-- <div class="col-md-2">
                <q-input dense filled v-model="start">
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy
                        ref="qDateProxy"
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="start"
                          color="orange"
                          label="Chose Date"
                          mask="DD/MM/YYYY"
                        >
                          <div class="row items-center justify-end">
                            <q-btn
                              v-close-popup
                              label="Close"
                              color="orange"
                              flat
                            />
                          </div>
                        </q-date>
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div> -->
              <div class="col-md-1"></div>
              <!--  <div class="col-md-2 q-pt-xs q-px-md">
                <q-btn
                  color="orange-7"
                  dense
                  size="md"
                  label="Search"
                  @click="search_data"
                >
                </q-btn>
              </div> -->
              <div class="col-md-1"></div>
            </div>

            <div class="row justify-center">
              <div class="col-md-12">
                <div class="q-pa-md">
                  <q-table
                    class="my-sticky-header-table table_size"
                    title-color="black"
                    :rows="list_data_chose"
                    :columns="columns_list_name_ot"
                    row-key="name"
                    :filter="filter"
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
                    <template v-slot:body="props">
                      <q-tr class="my_table" :props="props">
                        <q-td key="req_no" :props="props">
                          {{ props.row.req_no }}
                        </q-td>
                        <q-td key="req_date" :props="props">
                          {{ props.row.req_date }}
                        </q-td>
                        <q-td
                          key="start_time"
                          v-if="props.row.start_time == '05:00'"
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

                        <q-td key="dept_name" :props="props">
                          {{ props.row.dept_name }}
                        </q-td>
                        <q-td key="ot_type" :props="props">
                          {{ props.row.ot_type }}
                        </q-td>

                        <q-td key="ref_doc_no" :props="props">
                          {{ props.row.ref_doc_no }}
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

        <br />
      </div>
    </div>
    <div>
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

            <Detaillistot :detaillistot="this.value_components"></Detaillistot>
          </q-card-section>

          <div class="q-pt-md" style="margin-top: 0px; padding-top: 0px"></div>
        </q-card>
      </q-dialog>
    </div>
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
      emp_code_input: "",
      dept_code_input: "",
      list_data_chose: [],
      value_components: "",
      filter: "",
      pagination: "",
      status_open_list_name_ot: false,
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
          name: "ref_doc_no",
          align: "center",
          label: "REF DOC NO",
          field: "ref_doc_no",
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
    async search_data() {
      let dept_code = this.$q.localStorage.getItem("dept_code"); //storage dept_code
      const params = new FormData();
      params.append("emp_code", this.emp_code_input);
      params.append("dept_code", dept_code);
      params.append("date", this.start);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/search_data",
        data: params,
      })
        .then((resp) => {
          this.list_data_chose = [];
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.list_data_chose.push({
                emp_code: e.EMP_CODE,
                emp_name: e.EMP_NAME,
                ot_hour: e.OT_HOUR,
                Date: e.OT_DATE,
                time_start: e.START_TIME,
                time_end: e.END_TIME,
                dept_app: e.DEPT_APP,
                ref_doc_no: e.REF_DOC_NO,
              });
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    open_detail_list_name(index) {
      this.status_open_list_name_ot = true;
      this.value_components = index.req_no;
      console.log(this.value_components);
    },
    reload() {
      location.reload();
    },
    onDelete(index) {
      console.log(this.list_data_chose.length);
      for (var i = 0; i < this.list_data_chose.length; i++)
        if (this.list_data_chose[i].req_no == index.req_no) {
          this.list_data_chose.splice(i, 1);
        }
    },
  },
  async mounted() {
    let dept_code = this.$q.localStorage.getItem("dept_code");
    let username = this.$q.localStorage.getItem("username");

    if (username == null) {
      this.$router.push({
        path: "/",
      });
    }
    this.time_now = new Date();
    this.time_now_use = moment(this.time_now).format("YYYY/MM/DD");
    const params = new FormData();
    params.append("emp_code", this.emp_code_input);
    params.append("dept_code", dept_code);
    params.append("username", username);
    params.append("date", this.time_now_use);
    console.log(this.start);
    await axios({
      method: "post",
      url: this.$api_url + "/find_data.php/search_data_confirm",
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
              dept_app: e.DEPT_APP,
              dept_name: e.DEPT_NAME,
              ot_type: e.OT_TYPE,
              start_time: e.START_TIME,
              ref_doc_no: e.REF_DOC_NO,
            });
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });

    console.log(this.list_data_chose);
  },
  components: {
    Detaillistot,
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
  height: 900px;
}
.table_size {
  min-height: 700px;
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
