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
      username: "",
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
      console.log(this.selected);
      console.log(this.status_emp);
      if (this.status_emp == "dept_for_app") {
        for (var ax = 0; ax < this.selected.length; ax++) {
          const params = new FormData();
          params.append("req_no", this.selected[ax].req_no);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/update_data_for_dept",
            data: params,
          })
            .then((resp) => {
              console.log(resp.data);
              this.$q.notify({
                message: "Update" + this.selected[ax].req_no + "Complete",
                position: "center",
                icon: "announcement",
                color: "green-7",
              });
            })
            .catch((error) => {
              console.log(error);
            });
        }
        this.refresh_data();
      } else if (this.status_emp == "div_for_app") {
        for (var ax = 0; ax < this.selected.length; ax++) {
          const params = new FormData();
          params.append("req_no", this.selected[ax].req_no);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/update_data_for_div",
            data: params,
          })
            .then((resp) => {
              console.log(resp.data);
              this.$q.notify({
                message: "Update" + this.selected[ax].req_no + "Complete",
                position: "center",
                icon: "announcement",
                color: "orange-7",
              });
            })
            .catch((error) => {
              console.log(error);
            });
          this.refresh_data();
        }
      }
    },
    push_data(index) {
      console.log(index);
    },

    reload() {
      location.reload();
    },

    open_detail_list_name(index) {
      this.status_open_list_name_ot = true;
      this.value_components = index.req_no;
      console.log(this.value_components);
    },
    async search_data_from_input() {
      if (this.status_emp == "dept_for_app") {
        if (this.fix_username1 == this.dept_app_emp) {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("username", this.username);
          params.append("dept_code", this.value_emp_code);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/chose_data_dept_day",
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
              } else {
                console.log(this.dept_code);
                if (this.dept_code == "xxxxx") {
                  axios({
                    method: "post",
                    url:
                      this.$api_url +
                      "/find_data.php/chose_data_dept_day_null_shift",
                    data: params,
                  }).then((resp) => {
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
                  });
                } else {
                  axios({
                    method: "post",
                    url:
                      this.$api_url + "/find_data.php/chose_data_dept_day_null",
                    data: params,
                  }).then((resp) => {
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
                  });
                }
              }
            })
            .catch((error) => {
              console.log(error);
            });
        } else if (this.fix_username2 == this.dept_app_emp) {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("username", this.username);
          params.append("dept_code", this.value_emp_code);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/chose_data_dept_night",
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
              } else {
                if (this.dept_code == "xxxxx") {
                  axios({
                    method: "post",
                    url:
                      this.$api_url +
                      "/find_data.php/chose_data_dept_night_null_shift",
                    data: params,
                  }).then((resp) => {
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
                  });
                } else {
                  axios({
                    method: "post",
                    url:
                      this.$api_url +
                      "/find_data.php/chose_data_dept_night_null",
                    data: params,
                  }).then((resp) => {
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
                  });
                }
              }
            })
            .catch((error) => {
              console.log(error);
            });
        } else {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("username", this.username);
          params.append("dept_code", this.value_emp_code);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/chose_data_dept",
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
        }
      } else if (this.status_emp == "div_for_app") {
        this.list_data_chose = [];
        const params = new FormData();
        params.append("username", this.username);
        params.append("dept_code", this.value_emp_code);

        await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/value_div_input",
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
      }
    },
    async refresh_data() {
      if (this.status_emp == "dept_for_app") {
        if (this.fix_username1 == this.dept_app_emp) {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("emp_code", this.emp_code_input);
          let dept_code = this.$q.localStorage.getItem("dept_code");
          params.append("dept_code", dept_code);
          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/approve_data_for_dept_day",
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
        } else if (this.fix_username2 == this.dept_app_emp) {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("emp_code", this.emp_code_input);
          let dept_code = this.$q.localStorage.getItem("dept_code");
          params.append("dept_code", dept_code);
          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/approve_data_for_dept_night",
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
        } else {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("emp_code", this.emp_code_input);
          let dept_code = this.$q.localStorage.getItem("dept_code");
          params.append("dept_code", dept_code);
          params.append("username", this.username);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/approve_data_for_dept",
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
        }
      } else if (this.status_emp == "div_for_app") {
        console.log(this.value_emp_code);
        if (this.value_emp_code == "ALL") {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("username", this.username);
          params.append("dept_code", this.value_emp_code);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/value_div_input",
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
        } else {
          this.list_data_chose = [];
          const params = new FormData();
          params.append("emp_code", this.emp_code_input);
          let dept_code = this.$q.localStorage.getItem("dept_code");
          params.append("dept_code", dept_code);
          params.append("username", this.username);

          await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/approve_data_for_div",
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
        }
      }
    },
    find_new_data() {
      console.log("Hi");
      if (this.value_emp_code != "") {
        const params = new FormData();
        params.append("dept_code", this.value_components);
      }
    },
  },
  components: {
    Detaillistot,
  },
  async mounted() {
    let dept_code = this.$q.localStorage.getItem("dept_code");
    this.dept_code = this.$q.localStorage.getItem("dept_code");
    let username = this.$q.localStorage.getItem("username");
    this.username = this.$q.localStorage.getItem("username");
    let dept_app_emp = this.$q.localStorage.getItem("dept_emp_app");
    this.dept_app_emp = this.$q.localStorage.getItem("dept_emp_app");
    let divison_app_emp = this.$q.localStorage.getItem("divison_emp_app");
    if (username == null) {
      this.$router.push({
        path: "/",
      });
    }

    this.fix_username1 = "xxxxxx"; //เงื่อนไขพิเศษ
    this.fix_username2 = "xxxxxx";
    if (dept_code == "xxxxx") {
      if (
        username != this.fix_username1 &&
        username != this.fix_username2 &&
        username != divison_app_emp
      ) {
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
    } else {
      if (username != dept_app_emp && username != divison_app_emp) {
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
    }

    if (dept_code == "xxxxx" && username == this.fix_username1) {
      this.list_data_chose = [];
      this.status_emp = "dept_for_app";
      const params = new FormData();
      params.append("emp_code", this.emp_code_input);
      params.append("dept_code", dept_code);
      params.append("username", this.username);

      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/approve_data_for_dept_day",
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

      const params3 = new FormData();
      params3.append("username", this.username);
      params3.append("dept_code", this.dept_code);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/list_for_dept_app_day",
        data: params3,
      }).then((resp) => {
        console.log(resp.data);

        var k = 0;

        resp.data.data.forEach((e) => {
          this.list_emp_code[k] = e.DEPT_CODE;
          k++;
        });
      });
    } else if (dept_code == "xxxxx" && this.fix_username2) {
      this.list_data_chose = [];
      this.status_emp = "dept_for_app";
      const params = new FormData();
      params.append("emp_code", this.emp_code_input);
      params.append("dept_code", dept_code);
      params.append("username", this.username);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/approve_data_for_dept_night",
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
      const params3 = new FormData();
      params3.append("username", this.username);
      params3.append("dept_code", this.dept_code);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/list_for_dept_app_night",
        data: params3,
      }).then((resp) => {
        console.log(resp.data);
        var k = 0;

        resp.data.data.forEach((e) => {
          this.list_emp_code[k] = e.DEPT_CODE;
          k++;
        });
      });
    } else {
      this.dept_code = this.$q.localStorage.getItem("dept_code");
      console.log(this.dept_code);

      const params2 = new FormData();
      if (username == dept_app_emp) {
        this.status_emp = "dept_for_app";
        this.list_data_chose = [];
        params2.append("emp_code", this.emp_code_input);
        params2.append("dept_code", dept_code);
        params2.append("username", this.username);
        await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/approve_data_for_dept",
          data: params2,
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
      }
      const params3 = new FormData();
      params3.append("username", this.username);
      params3.append("dept_code", this.dept_code);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/list_for_dept_app",
        data: params3,
      }).then((resp) => {
        console.log(resp.data);
        var k = 0;

        resp.data.data.forEach((e) => {
          this.list_emp_code[k] = e.DEPT_CODE;
          k++;
        });
      });
    }

    if (username == divison_app_emp) {
      this.list_data_chose = [];
      const params5 = new FormData();
      this.status_emp = "div_for_app";
      params5.append("emp_code", this.emp_code_input);
      params5.append("dept_code", dept_code);
      params5.append("username", this.username);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/approve_data_for_div",
        data: params5,
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

      console.log(this.list_emp_code);
      const params2x = new FormData();
      params2x.append("username", this.username);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/list_for_div_app",
        data: params2x,
      }).then((resp) => {
        console.log(resp.data);
        var k = 1;

        resp.data.data.forEach((e) => {
          this.list_emp_code[k] = e.DEPT_CODE;
          k++;
        });
      });

      this.list_emp_code.unshift("ALL");
    }
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
