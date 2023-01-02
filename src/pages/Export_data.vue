<template>
  <q-card class="my-card q-pa-xl col-md-12">
    <div class="row justify-center">
      <div class="col-md-12">
        <q-card class="my-card q-pa-md area_item card_size fix-height">
          <q-banner
            dense
            inline-actions
            rounded
            class="bg-black text-white banner_size"
          >
            <h6 class="q-mt-xs">Export Data</h6>
            <template v-slot:action> </template>
          </q-banner>
          <q-card-section>
            <div class="row justify-center">
              <q-input class="q-px-xl" filled v-model="start">
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      ref="qDateProxy"
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date v-model="start" mask="DD/MM/YYYY">
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Close"
                            color="primary"
                            dense
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
              <q-input class="q-px-xl" filled v-model="end">
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      ref="qDateProxy"
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date v-model="end" mask="DD/MM/YYYY">
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Close"
                            color="primary"
                            dense
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
              <div class="col-1"></div>
              <q-select
                class="q-pm-xl"
                filled
                v-model="chose_dept"
                :options="options"
                label="Chose Dept"
                style="width: 200px"
              />

              <div class="col-1"></div>

              <q-btn
                size="md"
                dense
                class="q-px-xl q-py-xs"
                color="orange-7"
                label="Export"
                @click="exportexcel()"
              />
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
          </q-card-section>

          <div class="q-pt-md" style="margin-top: 0px; padding-top: 0px"></div>
        </q-card>
      </q-dialog>
    </div>
  </q-card>
</template>

<script>
import { ref } from "vue";
import { date } from "quasar";
import axios from "axios";
import { useQuasar, QSpinnerGears } from "quasar";
import { onBeforeUnmount } from "vue";
import * as Excel from "exceljs";
import { saveAs } from "file-saver";
export default {
  data() {
    return {
      options: [],
      chose_dept: "",
      start: "",
      end: "",
      start_date: "",
      end_date: "",
      row_export: [],
      column_main: [
        {
          name_col: "A",
        },
        {
          name_col: "B",
        },
        {
          name_col: "C",
        },
        {
          name_col: "D",
        },
        {
          name_col: "E",
        },
        {
          name_col: "F",
        },
        {
          name_col: "G",
        },
        {
          name_col: "H",
        },
        {
          name_col: "I",
        },
      ],
    };
  },
  mounted() {
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
    if ((this.dept_code = "23000")) {
      this.options.push("ALL");
      this.chose_dept = "ALL";
    }

    const params = new FormData(); //check ว่าเป็นผู้ approve หรือไม่

    console.log(this.dept_code);
  },
  methods: {
    async exportexcel() {
      this.$q.loading.show({
        spinner: QSpinnerGears,
        spinnerColor: "wthite",
        spinnerSize: 180,
        backgroundColor: "black",
      });
      const workbook = new Excel.Workbook();
      workbook.creator = "Nanyang";
      workbook.lastModifiedBy = "Admin";
      const worksheet = workbook.addWorksheet("Data_OT");

      worksheet.columns = [
        { key: "A", width: 21 },
        { key: "B", width: 27 },
        { key: "C", width: 20 },
        { key: "D", width: 20 },
        { key: "E", width: 20 },
        { key: "F", width: 17 },
        { key: "G", width: 17 },
        { key: "H", width: 27 },
        { key: "I", width: 27 },
      ];

      worksheet.getCell("A1").value = "รหัสพนักงาน";

      worksheet.getCell("B1").value = "ชื่อ และ นามสกุล";

      worksheet.getCell("C1").value = "รหัสของแผนก";

      worksheet.getCell("D1").value = "ชื่อแผนก";

      worksheet.getCell("E1").value = "Date";

      worksheet.getCell("F1").value = "Time Start";

      worksheet.getCell("G1").value = "Time Up";

      worksheet.getCell("H1").value = "Dept App Name";

      worksheet.getCell("I1").value = "Division App Name";

      const params = new FormData();
      params.append("start", this.start_date);
      params.append("end", this.end_date);

      console.log(this.start_date);
      console.log(this.end_date);

      await axios({
        method: "post",
        url: this.$api_url + "/export_data.php/export_data_hr_all",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.row_export.push({
                emp_code: e.EMP_CODE,
                emp_name: e.PREN_CODE + "  " + e.EMP_NAME,
                dept_code: e.DEPT_CODE,
                dept_name: e.DEPT_NAME,
                ot_date: e.OT_DATE,
                start_time: e.START_TIME,
                end_time: e.END_TIME,
                dept_app_name: e.DEPT_APP_NAME,
                division_app_name: e.DIVISION_APP_NAME,
              });
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });

      for (var ax = 0; ax < this.row_export.length; ax++) {
        worksheet.getCell("A" + [ax + 2]).value = this.row_export[ax].emp_code;

        worksheet.getCell("B" + [ax + 2]).value = this.row_export[ax].emp_name;

        worksheet.getCell("C" + [ax + 2]).value = this.row_export[ax].dept_code;

        worksheet.getCell("D" + [ax + 2]).value = this.row_export[ax].dept_name;

        worksheet.getCell("E" + [ax + 2]).value = this.row_export[ax].ot_date;

        worksheet.getCell("F" + [ax + 2]).value =
          this.row_export[ax].start_time;

        worksheet.getCell("G" + [ax + 2]).value = this.row_export[ax].end_time;

        worksheet.getCell("H" + [ax + 2]).value =
          this.row_export[ax].dept_app_name;

        worksheet.getCell("I" + [ax + 2]).value =
          this.row_export[ax].division_app_name;
      }

      for (var az = 0; az < this.column_main.length; az++) {
        for (var ay = 0; ay < this.row_export.length + 1; ay++) {
          worksheet.getCell(this.column_main[az].name_col + ay).font = {
            name: "Angsana New",
            color: { argb: "FF000000" },
            family: 4,
            size: 14,
            bold: false,
          };

          worksheet.getCell(this.column_main[az].name_col + ay).alignment = {
            horizontal: "center",
            vertical: "middle",
          };

          worksheet.getCell(this.column_main[az].name_col + ay).border = {
            top: { style: "thin" },
            left: { style: "thin" },
            bottom: { style: "thin" },
            right: { style: "thin" },
          };
        }
      }
      this.$q.loading.hide();
      workbook.xlsx.writeBuffer().then((data) => {
        const blob = new Blob([data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8",
        });
        saveAs(blob, "OT_DATA.xlsx");
      });
    },
  },

  watch: {
    start(val) {
      let day = val.substring(0, 2);
      let month = val.substring(3, 5);
      let year = val.substring(6, 10);
      this.start_date = year + "/" + month + "/" + day;
    },
    end(val) {
      let day = val.substring(0, 2);
      let month = val.substring(3, 5);
      let year = val.substring(6, 10);
      this.end_date = year + "/" + month + "/" + day;
    },
  },
};
</script>

<style>
.fix-height {
  min-height: 800px;
}
</style>
