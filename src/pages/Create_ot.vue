<template>
  <q-card class="my-card q-pa-xl col-md-12">
    <div class="row justify-center">
      <div class="col-md-12">
        <q-card class="my-card q-pa-md area_item card_size">
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
              <div class="col-md-1"></div>
              <div class="col-md-2 q-px-md">
                <q-select
                  class="padding_select"
                  label="Employee Type"
                  transition-show="flip-up"
                  transition-hide="flip-down"
                  label-color="black"
                  v-model="value_kind_ot"
                  :options="list_kind_ot"
                  :disable="isDisabled"
                />
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-2 q-px-md">
                <q-select
                  class="padding_select"
                  label="Shift"
                  transition-show="flip-up"
                  transition-hide="flip-down"
                  label-color="black"
                  v-model="value_shift"
                  :options="shift"
                  :disable="isDisabled"
                />
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-2 q-px-md">
                <q-select
                  class="padding_select"
                  label="OT TIME"
                  transition-show="flip-up"
                  transition-hide="flip-down"
                  label-color="black"
                  v-model="value_list_time_work"
                  :options="list_time_work"
                />
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-1 q-pt-lg">
                <q-btn
                  class="q-px-md"
                  color="orange-7"
                  size="md"
                  dense
                  label="Browse"
                  @click="open_list_name_ot()"
                >
                </q-btn>
              </div>
            </div>

            <div class="row justify-center">
              <div class="col-md-12">
                <div class="q-pa-md">
                  <q-table
                    class="my-sticky-header-table table_size"
                    title-color="black"
                    :rows="list_data_chose_test"
                    :columns="columns_list_name_ot"
                    row-key="name"
                    :v-model:pagination="pagination"
                    :rows-per-page-options="[10]"
                  >
                    <template v-slot:top-left>
                      <h5>
                        เลขที่ใบคำขอ OT :{{
                          req_no
                        }}
                        &nbsp;&nbsp;&nbsp;&nbsp;วันที่ : {{ Datexx }}
                      </h5>
                    </template>
                    <template v-slot:top-right>
                      <q-btn
                        class="q-px-lg"
                        size="lg"
                        color="green-6"
                        text-color="white"
                        label="Save"
                        @click="save_ot_data"
                      >
                      </q-btn>
                    </template>
                    <template v-slot:body="props">
                      <q-tr class="my_table" :props="props">
                        <q-td key="emp_code" :props="props">
                          <q-btn
                            z
                            color="red-9 text-white"
                            icon="highlight_off"
                            @click="onDelete(props.row)"
                          />
                        </q-td>
                        <q-td key="emp_code" :props="props">
                          {{ props.row.emp_code }}
                        </q-td>
                        <q-td key="emp_name" :props="props">
                          {{ props.row.emp_name }}
                        </q-td>
                        <q-td key="emp_last_name" :props="props">
                          {{ props.row.emp_last_name }}
                        </q-td>
                        <q-td key="Date" :props="props">
                          {{ props.row.Date }}
                        </q-td>
                        <q-td key="time_start" :props="props">
                          {{ props.row.time_start }}
                        </q-td>
                        <q-td key="time_end" :props="props">
                          {{ props.row.time_end }}
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

                    <q-toolbar-title>Insert OT List Name</q-toolbar-title>

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
            <Listnameot
              :listnameot="this.dept_code"
              :listnameot2="this.value_char_kind_out"
              :listnameot3="this.value_shift"
              :listnameot4="this.value_kind_ot"
              @show="getshow"
            ></Listnameot>
          </q-card-section>

          <div class="q-pt-md" style="margin-top: 0px; padding-top: 0px"></div>
        </q-card>
      </q-dialog>
    </div>
  </q-card>
</template>

<script>
import axios from "axios";
import Listnameot from "components/Listnameot.vue";
import { useQuasar, QSpinnerGears } from "quasar";
import { ref } from "vue";
import { onBeforeUnmount } from "vue";
import { Cookies } from "quasar";
import moment from "moment";
const $q = useQuasar();

const rows = [];
export default {
  data() {
    return {
      filter: ref(""),
      req_no: "",
      pagination: "",
      list_dept_code: [11000, 11200, 13000, 13100],
      value_dept_code: "",
      shift: ["กลางวัน", "กลางคืน"],
      value_shift: "",
      list_time_work: [
        "30 mins",
        "1 hour",
        "1.30 hours",
        "2 hours",
        "2.30 hours",
        "3 hours",
      ],
      value_list_time_work: "",
      list_kind_ot: [],
      list_kind_ot_change_name: [],
      value_kind_ot: "",
      value_char_kind_out: "",
      list_for_check_duplicate_date: [],
      value_char_kind_code: "",
      list_check_duplicate: [],
      last_number_row: [],
      status_open_list_name_ot: false,
      list_approve: [],
      time_now_use2: "",
      status_save: true,
      status_for_save_ot: true,
      time_start: "17:00:00",
      time_start_night: "05:30:00",
      check_duplicate_value: true,
      row_list_name: [],
      isDisabled: false,
      status_create_req_no: true,
      dept_code: "",
      Datexx: "",
      list_data_chose: [],
      list_data_chose_test: [],

      columns_list_name_ot: [
        {
          name: "delete",
          required: true,
          label: "Delete",
          align: "center",
          field: "Delete",
          sortable: true,
        },
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
          name: "emp_last_name",
          align: "center",
          label: "นามสกุล",
          field: "emp_last_name",
          sortable: true,
        },
        {
          name: "Date",
          align: "center",
          label: "Date",
          field: "Date",
        },
        {
          name: "time_start",
          align: "center",
          label: "Time Start",
          field: "time_start",
          sortable: true,
        },
        {
          name: "time_end",
          align: "center",
          label: "Time up",
          field: "time_end",
          sortable: true,
        },
      ],
    };
  },
  components: {
    Listnameot,
  },
  async mounted() {
    (this.status_create_req_no = true),
      (this.username = this.$q.localStorage.getItem("username"));
    console.log(this.username);
    if (this.username == null) {
      this.$router.push({
        path: "/",
      });
    }
    this.dept_code = this.$q.localStorage.getItem("dept_code");
    //params.append("");
    this.list_kind_ot = [];
    this.list_kind_ot_change_name = [];
    await axios
      .get(this.$api_url + "/find_data.php/get_shift")

      .then((resp) => {
        var i = 0;
        if (resp.data.data.length > 0) {
          resp.data.data.forEach((e) => {
            (this.list_kind_ot[i] = e.OT_TYPE), i++;
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });
    console.log(this.list_kind_ot);

    this.time_now_use = new Date();
    this.time_now_use = moment(this.time_now).format("DD-MM-YYYY");
  },

  methods: {
    async check_employee_detail() {
      this.row_list_name = [];
      const params = new FormData();

      if (this.value_kind_ot !== "") {
        if (this.value_kind_ot == "DAY") {
          this.value_char_kind_out = "D";
        }

        if (this.value_kind_ot == "MONTH") {
          this.value_char_kind_out = "M";
        }
      }
      console.log(this.value_char_kind_out);
      params.append("value_kind_ot", this.value_char_kind_out);
      params.append("value_list_time_work", this.value_list_time_work);
      params.append("value_shift", this.value_shift);
      params.append("dept_code", this.dept_code);
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
                dept_code: e.DEPT_CODE,
                dept_name: e.DEPT_NAME,
              });
            });
          }
          console.log(this.row_list_name);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async getshow(value) {
      console.log(value);
      this.check_duplicate_value = true;
      this.count = 0;
      this.time_now2 = new Date();
      this.time_now_use2 = moment(this.time_now2).format("YYYY/MM/DD");
      const params = new FormData();

      for (var ax = 0; ax < value.length; ax++) {
        if (this.value_shift == "กลางวัน") {
          this.value_shift_change = "17:00";
        } else {
          this.value_shift_change = "05:00";
        }
        params.append("value_shift", this.value_shift_change);
        params.append("date", this.time_now_use2);
        params.append("emp_code", value[ax].emp_code);
        for (var pair of params.entries()) {
          console.log(pair[0] + ", " + pair[1]);
        }

        await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/check_user_dup",
          data: params,
        })
          .then((resp) => {
            console.log(resp.data);
            if (resp.data.data.length > 0) {
              this.$q.notify({
                message:
                  "ข้อมูลของคุณ" + resp.data.data[0].EMP_NAME + " " + "ซ้ำ",
                position: "center",
                icon: "announcement",
                color: "red-7",
              });
              this.count++;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
      for (var ax = 0; ax < this.list_data_chose_test.length; ax++) {
        for (var ay = 0; ay < value.length; ay++) {
          if (this.list_data_chose_test[ax].emp_code == value[ay].emp_code) {
            this.$q.notify({
              message: "ข้อมูลของคุณ" + value[ay].emp_name + " " + "ซ้ำ",
              position: "center",
              icon: "announcement",
              color: "red-9",
            });
            this.check_duplicate_value = false;
          }
        }
      }
      function cal_time(val1, val2) {
        if (val2 == "30 mins") {
          var durationInMinutes = "30";
          var endTime = moment(val1, "HH:mm:ss")
            .add(durationInMinutes, "minutes")
            .format("HH:mm");
        } else if (val2 == "1 hour") {
          var durationInMinutes = "60";
          var endTime = moment(val1, "HH:mm:ss")
            .add(durationInMinutes, "minutes")
            .format("HH:mm");
        } else if (val2 == "1.30 hours") {
          var durationInMinutes = "90";
          var endTime = moment(val1, "HH:mm:ss")
            .add(durationInMinutes, "minutes")
            .format("HH:mm");
        } else if (val2 == "2 hours") {
          var durationInMinutes = "120";
          var endTime = moment(val1, "HH:mm:ss")
            .add(durationInMinutes, "minutes")
            .format("HH:mm");
        } else if (val2 == "2.30 hours") {
          var durationInMinutes = "150";
          var endTime = moment(val1, "HH:mm:ss")
            .add(durationInMinutes, "minutes")
            .format("HH:mm");
        } else if (val2 == "3 hours") {
          var durationInMinutes = "180";
          var endTime = moment(val1, "HH:mm:ss")
            .add(durationInMinutes, "minutes")
            .format("HH:mm");
        }
        return endTime;
      }
      this.change_Date(); //method แก้ไข Format วันที่

      if (this.value_shift == "กลางวัน") {
        this.chose_cal_time = cal_time(
          this.time_start,
          this.value_list_time_work
        );

        this.chose_date_start_ot = this.time_now_change;
        this.chose_time_start = this.time_start_format;
        this.chose_time_start_format = this.time_now_check;
      } else {
        this.chose_cal_time = cal_time(
          this.time_start_night,
          this.value_list_time_work
        );
        this.chose_date_start_ot = this.time_now_change_night;
        this.chose_time_start = this.time_start_format_night;
        this.chose_time_start_format = this.time_now_check_night;
      }

      if (this.check_duplicate_value == true && this.count == 0) {
        value.forEach((e) => {
          this.list_data_chose_test.push({
            emp_code: e.emp_code,
            pren_code: e.pren_code,
            emp_name: e.emp_name,
            emp_last_name: e.emp_last_name,
            Date: this.chose_date_start_ot,
            Date_format: this.chose_time_start_format,
            ot_time: this.shift,
            ot_hour: this.value_list_time_work,
            time_start: this.chose_time_start,
            time_end: this.chose_cal_time,
          });
        });
      }
    },
    onDelete(index) {
      for (var i = 0; i < this.list_data_chose_test.length; i++)
        if (this.list_data_chose_test[i].emp_code == index.emp_code) {
          this.list_data_chose_test.splice(i, 1);
        }
    },
    change_Date() {
      this.time_now = new Date();
      this.time_now_check = moment(this.time_now).format("YYYY/MM/DD");
      this.time_now_change = moment(this.time_now).format("DD-MM-YYYY"); //วันปัจจุบัน
      this.time_now_change_night = moment(this.time_now)
        .add(1, "day")
        .format("DD-MM-YYYY");
      this.time_now_check_night = moment(this.time_now)
        .add(1, "day")
        .format("YYYY/MM/DD");
      this.time_now_replace = moment(this.time_now).format("YYMMDD");
      this.time_start_format = this.time_start.slice(0, 5);
      this.time_start_format_night = this.time_start_night.slice(0, 5);
    },

    open_list_name_ot() {
      if (this.value_kind_ot != "" && this.value_shift != "") {
        this.isDisabled = true;
      }

      if (this.value_kind_ot == "") {
        this.$q.notify({
          message: "Please Chose Employee Type",
          position: "center",
          icon: "announcement",
          color: "red-9",
        });
      }

      if (this.value_shift == "") {
        this.$q.notify({
          message: "Please Chose Shift",
          position: "center",
          icon: "announcement",
          color: "red-9",
        });
      }

      if (this.value_list_time_work == "") {
        this.$q.notify({
          message: "Please Chose OT time",
          position: "center",
          icon: "announcement",
          color: "red-9",
        });
      }

      if (
        this.value_kind_ot != "" &&
        this.value_shift != "" &&
        this.value_list_time_work != ""
      ) {
        this.status_open_list_name_ot = true;
      }
    },

    async save_ot_data() {
      this.$q.loading.show({
        spinner: QSpinnerGears,
        spinnerColor: "wthite",
        spinnerSize: 180,
        backgroundColor: "black",
      });
      this.list_approve = [];
      let dept_code = this.$q.localStorage.getItem("dept_code"); //storage dept_code
      this.change_Date(); //เรียกข้อมูล Setting เวลา

      const params3 = new FormData(); //หา Dept_code sup กับ Mgr
      params3.append("dept_code", dept_code);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/find_approver",
        data: params3,
      }).then((resp) => {
        console.log(resp.data);
        if (resp.data.data.length > 0) {
          resp.data.data.forEach((e) => {
            this.list_approve.push({
              DEPT_CODE: e.DEPT_CODE,
              DEPT_EMP_APP: e.DEPT_EMP_APP,
              DIVISON_EMP_APP: e.DIVISON_EMP_APP,
            });
          });
        }
      });

      if (this.status_create_req_no == true) {
        const paramsx = new FormData(); //หา REQ_ROW ล่าสุดเพื่อนำไป +1
        paramsx.append("date", this.time_now_check);
        await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/check_last_data_row",
          data: paramsx,
        })
          .then((resp) => {
            console.log(resp.data);
            this.last_number_row = [];
            if (resp.data.data.length > 0) {
              resp.data.data.forEach((e) => {
                this.last_number_row.push({
                  REQ_NO: e.MAX_REQ_NO,
                });
              });
            } else {
              this.last_number_row.push({
                REQ_NO: "RQ" + this.time_now_replace + "000",
              });
            }
          })
          .catch((error) => {
            console.log(error);
          });

        if (this.last_number_row[0].REQ_NO == null) {
          this.last_number_row[0].REQ_NO = "RQ" + this.time_now_replace + "000";
        }
        function cal_add_row(val) {
          var last_number_row_slice = val.slice(8, 11);
          var add_last_number_row = Number(last_number_row_slice) + 1;
          add_last_number_row = add_last_number_row.toString();
          var pad = "000";
          return (
            pad.substring(0, pad.length - add_last_number_row.length) +
            add_last_number_row
          );
        }

        this.last_number_pad = cal_add_row(this.last_number_row[0].REQ_NO);

        this.req_no = "RQ" + this.time_now_replace + this.last_number_pad; //เลข REQ_NO
      }
      this.status_create_req_no = false;

      if (this.value_kind_ot == "DAY") {
        //ประเภทการลา
        this.value_char_kind_code = "001";
      }
      if (this.value_kind_ot == "MONTH") {
        this.value_char_kind_code = "011";
      }

      if (
        this.list_approve[0].DEPT_EMP_APP != null &&
        this.list_approve[0].DIVISON_EMP_APP != null
      ) {
        this.dept_emp_app = "N";
        this.dept_app_emp = this.list_approve[0].DEPT_EMP_APP;
        this.divison_emp_app = "N";
        this.div_app_emp = this.list_approve[0].DIVISON_EMP_APP;
      } else if (
        this.list_approve[0].DEPT_EMP_APP != null &&
        this.list_approve[0].DIVISON_EMP_APP == null
      ) {
        this.dept_emp_app = "N";
        this.dept_app_emp = this.list_approve[0].DEPT_EMP_APP;
        this.divison_emp_app = "Y";
        this.div_app_emp = this.list_approve[0].DEPT_EMP_APP;
      } else if (
        this.list_approve[0].DEPT_EMP_APP == null &&
        this.list_approve[0].DIVISON_EMP_APP != null
      ) {
        this.dept_emp_app = "Y";
        this.dept_app_emp = this.list_approve[0].DIVISON_EMP_APP;
        this.divison_emp_app = "N";
        this.div_app_emp = this.list_approve[0].DIVISON_EMP_APP;
      } else if (
        this.list_approve[0].DEPT_EMP_APP == null &&
        this.list_approve[0].DIVISON_EMP_APP == null
      ) {
        this.dept_emp_app = "Y";
        this.divison_emp_app = "Y";
      }
      console.log(this.list_approve[0].DEPT_EMP_APP);
      console.log(this.dept_app_emp);

      const params2 = new FormData();
      this.Datexx = this.time_now_change;
      params2.append("req_no", this.req_no);
      params2.append("req_date", this.time_now_check);
      params2.append("username", this.username);
      params2.append("ot_code", this.value_char_kind_code);
      params2.append("dept_code", this.dept_code);
      params2.append("dept_app", this.dept_emp_app);
      params2.append("dept_app_emp", this.dept_app_emp);
      params2.append("div_dept_app", this.divison_emp_app);
      params2.append("div_app_emp", this.div_app_emp);
      params2.append("ot_type", this.value_kind_ot);

      for (var pair of params2.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }

      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/insert_ot_data_h",
        data: params2,
      }).then((resp) => {
        console.log(resp.data);

        this.$q.notify({
          message: "Save Succes" + this.req_no,
          position: "center",
          icon: "announcement",
          color: "green-7",
        });
      });

      this.username = this.$q.localStorage.getItem("username");
      const params4 = new FormData();
      const params5 = new FormData();

      console.log(this.list_data_chose_test);

      for (var av = 0; av < this.list_data_chose_test.length; av++) {
        this.status_save = true;
        params5.append("req_no", this.req_no);
        params5.append("emp_code", this.list_data_chose_test[av].emp_code);
        params5.append(
          "emp_name",
          this.list_data_chose_test[av].emp_name +
            "  " +
            this.list_data_chose_test[av].emp_last_name
        );
        params5.append("date", this.list_data_chose_test[av].Date_format);
        for (var pair of params5.entries()) {
          console.log(pair[0] + ", " + pair[1]);
        }

        /* await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/check_before_insert",
          data: params5,
        })
          .then((resp) => {
            console.log(resp.data);
            if (resp.data.data.length > 0) {
              this.$q.notify({
                message: "ข้อมูลของคุณ" + resp.data.data[0].EMP_NAME + "ซ้ำ",
                position: "center",
                icon: "announcement",
                color: "red-7",
              });
            } else {
              this.status_save = false;
            }
          })
          .catch((error) => {
            console.log(error);
          }); */
        console.log(this.status_save);
      }

      for (var xx = 0; xx < this.list_data_chose_test.length; xx++) {
        params4.append("req_no", this.req_no);
        params4.append("emp_code", this.list_data_chose_test[xx].emp_code);
        params4.append(
          "emp_name",
          this.list_data_chose_test[xx].emp_name +
            "  " +
            this.list_data_chose_test[xx].emp_last_name
        );

        if (this.list_data_chose_test[xx].ot_hour == "30 mins") {
          this.list_data_chose_test[xx].ot_hour = "0.5";
        } else if (this.list_data_chose_test[xx].ot_hour == "1 hour") {
          this.list_data_chose_test[xx].ot_hour = "1";
        } else if (this.list_data_chose_test[xx].ot_hour == "1.30 hours") {
          this.list_data_chose_test[xx].ot_hour = "1.5";
        } else if (this.list_data_chose_test[xx].ot_hour == "2 hours") {
          this.list_data_chose_test[xx].ot_hour = "2";
        } else if (this.list_data_chose_test[xx].ot_hour == "2.30 hours") {
          this.list_data_chose_test[xx].ot_hour = "2.5";
        } else if (this.list_data_chose_test[xx].ot_hour == "3 hours") {
          this.list_data_chose_test[xx].ot_hour = "3";
        }

        params4.append("ot_hour", this.list_data_chose_test[xx].ot_hour);
        params4.append("ot_start", this.list_data_chose_test[xx].time_start);
        params4.append("date", this.list_data_chose_test[xx].Date_format);
        params4.append("start_time", this.list_data_chose_test[xx].time_start);
        params4.append("end_date", this.list_data_chose_test[xx].Date_format);
        params4.append("end_time", this.list_data_chose_test[xx].time_end);
        params4.append("entry_by", this.username);
        for (var pair of params4.entries()) {
          console.log(pair[0] + ", " + pair[1]);
        }

        //if (this.status_save == true) {}
        await axios({
          method: "post",
          url: this.$api_url + "/find_data.php/insert_ot_data_d",
          data: params4,
        })
          .then((resp) => {
            console.log(resp.data);
          })
          .catch((error) => {
            console.log(error);
          });

        /*if (this.status_save == false) {
           await axios({
            method: "post",
            url: this.$api_url + "/find_data.php/update_ot_data_d",
            data: params4,
          })
            .then((resp) => {
              console.log(resp.data);
            })
            .catch((error) => {
              console.log(error);
            });
        }*/
      }
      this.$q.loading.hide();
    },
  },
  watch: {
    value_kind_ot: function (value) {
      this.check_employee_detail(value);
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
