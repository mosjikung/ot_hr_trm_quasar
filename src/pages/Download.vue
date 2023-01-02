<template>
  <q-card class="my-card q-pa-xl col-md-12">
    <div class="row justify-center">
      <div class="col-md-12">
        <q-card z class="my-card q-pa-md area_item card_size">
          <q-banner
            dense
            inline-actions
            rounded
            class="bg-black text-white banner_size"
          >
            <h6 class="q-mt-xs">Download</h6>
            <template v-slot:action> </template>
          </q-banner>
          <q-card-section>
            <div class="row">
              <div class="col-md-3"></div>

              <div class="col-md-2">
                <q-btn
                  size="md"
                  label="Download For Approve OT"
                  color="orange-7"
                  href="http://172.16.6.90/ot_hr_trm/api/find_data.php/find_pptx"
                  download="file"
                >
                </q-btn>
              </div>

              <div class="col-md-1"></div>
              <div class="col-md-2">
                <q-btn
                  size="md"
                  label="Download For Approve OT"
                  color="orange-7"
                  href="http://172.16.6.90/ot_hr_trm/api/find_data.php/find2_pptx"
                  download="file"
                ></q-btn>
              </div>
            </div>
          </q-card-section>
        </q-card>
        <br />

        <br />
      </div>
    </div>
    <div></div>
  </q-card>
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
      emp_code_input: "",
      dept_code_input: "",
      list_data_chose: [],
      value_components: "",
      filter: "",
      pagination: "",
      status_open_list_name_ot: false,
      columns_list_name_ot: [
        {
          name: "delete",
          required: true,
          label: "delete",
          align: "center",
          field: "delete",
          sortable: true,
        },
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
    async click_download() {
      await axios({
        url: this.$api_url + "/find_data.php/find_pptx",
        method: "GET",
      }).then((resp) => {
        console.log(resp.data);
      });
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
  height: 700px;
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
