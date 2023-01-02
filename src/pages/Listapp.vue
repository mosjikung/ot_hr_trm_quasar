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
            <div class="row justify-center">
              <div class="col-md-12">
                <div class="q-pa-md">
                  <q-table
                    class="my-sticky-header-table table_size"
                    title-color="black"
                    :rows="list_data_chose"
                    :columns="columns_list_name_ot"
                    row-key="req_no"
                    :filter="filter"
                    :v-model:pagination="pagination"
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
                        color="green-8"
                        text-color="white"
                        label="Add"
                        icon="add"
                        @click="Add_List"
                      >
                      </q-btn>
                    </template>
                    <template v-slot:body="props">
                      <q-tr class="my_table" :props="props">
                        <!--  <q-td key="req_no" :props="props">
                          <q-checkbox :props="props" v-model="props.selected" />
                        </q-td> -->

                        <q-td key="dept_code" :props="props">
                          <q-btn
                            round
                            dense
                            color="warning"
                            size="md"
                            icon="edit_document"
                            @click="edit_dept_list(props.row)"
                          />
                        </q-td>
                        <q-td key="dept_code" :props="props">
                          {{ props.row.dept_code }}
                        </q-td>
                        <q-td key="dept_name" :props="props">
                          {{ props.row.dept_name }}
                        </q-td>
                        <q-td key="dept_emp_app" :props="props">
                          {{ props.row.dept_emp_app }}
                        </q-td>

                        <q-td key="divison_emp_app" :props="props">
                          {{ props.row.divison_emp_app }}
                        </q-td>
                        <q-td key="shift" :props="props">
                          {{ props.row.shift }}
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
          v-model="status_open_add_list"
          transition-show="rotate"
          transition-hide="rotate"
        >
          <q-card style="min-width: 75%">
            <q-card-section>
              <div class="row">
                <div class="col-md-12">
                  <div class="bg-black text-white">
                    <q-toolbar>
                      <q-btn flat round dense />

                      <q-toolbar-title>Insert List Approve</q-toolbar-title>

                      <q-btn
                        icon="cancel"
                        flat
                        fixed-right
                        color="white"
                        v-close-popup
                      />
                    </q-toolbar>

                    <q-table
                      class="my-sticky-header-table table_size_detail"
                      title-color="black"
                      :rows="list_data_insert"
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
                          label="Save"
                          @click="Save_add(list_data_insert)"
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
              <br />
            </q-card-section>

            <div
              class="q-pt-md"
              style="margin-top: 0px; padding-top: 0px"
            ></div>
          </q-card>
        </q-dialog>

        <q-dialog
          v-model="status_open_edit_dept"
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

                      <q-toolbar-title>Edit Department Approve</q-toolbar-title>

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
              <Editdept
                :Editdept1="this.list_for_update.dept_code"
                :Editdept2="this.list_for_update.dept_name"
                :Editdept3="this.list_for_update.dept_emp_app"
                :Editdept4="this.list_for_update.divison_emp_app"
                :Editdept5="this.list_for_update.shift"
                @show="getshow"
              ></Editdept>
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
import Editdept from "../components/Edit_dept.vue";
import { ref } from "vue";
import { onBeforeUnmount } from "vue";
import { Cookies } from "quasar";
import moment from "moment";
const $q = useQuasar();
const rows = [];
export default {
  data() {
    return {
      list_data_chose: [],
      list_emp_code: [],
      filter: "",
      pagination: "",
      list_for_update: [],
      status_open_add_list: false,
      status_open_edit_dept: false,
      selected: ref([]),
      select_shift: ["", "sun", "night"],

      columns_list_name_ot: [
        {
          name: "edit_dept",
          required: true,
          label: "Edit",
          align: "center",
          field: "edit_dept",
          sortable: true,
        },
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
      list_data_insert: [
        {
          dept_code: "",
          dept_name: "",
          dept_emp_app: "",
          divison_emp_app: "",
          shift: "",
        },
      ],
    };
  },
  methods: {
    Add_List() {
      this.status_open_add_list = true;
    },

    async Save_add(index) {
      ///check DEPT_CODE ว่ามีอยู่ใน DB_DEPARTMENT หรือไม่

      const params = new FormData();
      params.append("dept_code", index[0].dept_code);
      params.append("dept_name", index[0].dept_name);
      params.append("dept_emp_app", index[0].dept_emp_app);
      params.append("divison_emp_app", index[0].divison_emp_app);
      params.append("shift", index[0].shift);

      await axios({
        method: "post",
        url: this.$api_url + "/list_app.php/check_item_db_department",
        data: params,
      })
        .then((resp) => {
          if (resp.data.data.length > 0) {
            axios({
              method: "post",
              url: this.$api_url + "/list_app.php/insert_dept",
              data: params,
            })
              .then((resp) => {
                console.log(resp.data);
                this.refresh_data();
                this.$q.notify({
                  message: "Insert Department Success",
                  position: "center",
                  timeout: 2000,
                  icon: "announcement",
                  color: "green-7",
                });
              })
              .catch((error) => {
                console.log(error);
                this.$q.notify({
                  message: "Failed Insert Data",
                  position: "center",
                  timeout: 2000,
                  icon: "announcement",
                  color: "red-9",
                });
              });
          } else {
            this.$q.notify({
              message: "Not found this DEPT_CODE in DB",
              position: "center",
              timeout: 2000,
              icon: "announcement",
              color: "red-9",
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getshow(value) {
      if (value == "true") {
        this.refresh_data();
      }
    },

    async refresh_data() {
      this.list_data_chose = [];
      await axios({
        method: "get",
        url: this.$api_url + "/list_app.php/get_list",
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            resp.data.data.forEach((e) => {
              this.list_data_chose.push({
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
    edit_dept_list(index) {
      this.list_for_update = index;

      this.status_open_edit_dept = true;
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

    await axios({
      method: "get",
      url: this.$api_url + "/list_app.php/get_list",
    })
      .then((resp) => {
        console.log(resp.data);
        if (resp.data.data.length > 0) {
          resp.data.data.forEach((e) => {
            this.list_data_chose.push({
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
  components: {
    Editdept,
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
.table_size_detail {
  min-height: 300px;
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
