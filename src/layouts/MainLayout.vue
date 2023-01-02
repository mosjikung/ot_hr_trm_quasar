<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <div class="bg-dark text-white">
        <q-toolbar>
          <q-btn
            v-if="this.level == 'create'"
            flat
            dense
            round
            color="orange-7"
            icon="menu"
            aria-label="Menu"
            @click="toggleLeftDrawer"
          />
          <q-btn
            v-if="this.level == 'hr'"
            flat
            dense
            round
            color="orange-7"
            icon="menu"
            aria-label="Menu"
            @click="toggleLeftDrawer2"
          />
          <q-btn
            v-if="this.level == 'app'"
            flat
            dense
            round
            color="orange-7"
            icon="menu"
            aria-label="Menu"
            @click="toggleLeftDrawer3"
          />

          <q-toolbar-title> Create OT </q-toolbar-title>
          <q-btn icon="logout" size="md" @click="log_out()"> </q-btn>
        </q-toolbar>
      </div>
    </q-header>

    <q-drawer v-if="this.level == 'create'" v-model="leftDrawerOpen">
      <q-list>
        <q-item-label header> Internal Link </q-item-label>

        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-drawer v-if="this.level == 'hr'" v-model="leftDrawerOpen2">
      <q-list>
        <q-item-label header> Internal Link </q-item-label>

        <EssentialLink2
          v-for="link in essentialLinks2"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-drawer v-if="this.level == 'app'" v-model="leftDrawerOpen3">
      <q-list>
        <q-item-label header> Internal Link </q-item-label>

        <EssentialLink3
          v-for="link in essentialLinks3"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from "components/EssentialLink.vue";
import EssentialLink2 from "components/EssentialLink2.vue";
import EssentialLink3 from "components/EssentialLink3.vue";
import axios from "axios";

var internal = "http://localhost:8080/#/";
var external = "172.16.6.90/ot_hr_trm/#/";
const linksList = [
  {
    title: "Create OT",
    icon: "create",
    link: "#/main",
  },
  {
    title: "Search",
    icon: "search",
    link: "#/main/search",
  },
  {
    title: "Approve",
    icon: "chat",
    link: "#/main/app",
  },
  {
    title: "Hr Approve",
    icon: "assignment_ind",
    link: "#/main/app-hr",
  },
];
const linksListcreate = [
  {
    title: "Create OT",
    icon: "create",
    link: "#/main",
  },
  {
    title: "Search",
    icon: "search",
    link: "#/main/search",
  },
];
const linksListapp = [
  {
    title: "Create OT",
    icon: "create",
    link: "#/main",
  },
  {
    title: "Search",
    icon: "search",
    link: "#/main/search",
  },
  {
    title: "Approve",
    icon: "chat",
    link: "#/main/app",
  },
];

const linksListapphr = [
  {
    title: "Create OT",
    icon: "create",
    link: "#/main",
  },
  {
    title: "Search",
    icon: "search",
    link: "#/main/search",
  },
  {
    title: "Approve",
    icon: "chat",
    link: "#/main/app",
  },

  {
    title: "Hr Approve",
    icon: "assignment_ind",
    link: "#/main/app-hr",
  },
  {
    title: "Hr Check Confirm",
    icon: "assignment_ind",
    link: "#/main/app-cf",
  },
  {
    title: "List Approve Manage",
    icon: "assignment_ind",
    link: "#/main/list-app",
  },

  {
    title: "Export Data",
    icon: "description",
    link: "#/main/ex-data",
  },
];

import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "MainLayout",

  components: {
    EssentialLink,
    EssentialLink2,
    EssentialLink3,
  },

  data() {
    const leftDrawerOpen = ref(false);
    const leftDrawerOpen2 = ref(false);
    const leftDrawerOpen3 = ref(false);

    return {
      essentialLinks: linksListcreate,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
      essentialLinks2: linksListapphr,
      leftDrawerOpen2,
      toggleLeftDrawer2() {
        leftDrawerOpen2.value = !leftDrawerOpen2.value;
      },
      essentialLinks3: linksListapp,
      leftDrawerOpen3,
      toggleLeftDrawer3() {
        leftDrawerOpen3.value = !leftDrawerOpen3.value;
      },
      level: "",
    };
  },

  async mounted() {
    let dept_code = this.$q.localStorage.getItem("dept_code");
    let username = this.$q.localStorage.getItem("username");
    this.username = this.$q.localStorage.getItem("username");
    let dept_app_emp = this.$q.localStorage.getItem("dept_emp_app");
    this.dept_app_emp = this.$q.localStorage.getItem("dept_emp_app");
    let divison_app_emp = this.$q.localStorage.getItem("divison_emp_app");
    const params = new FormData();

    if (dept_code == "23000") {
      this.level = "hr";
    } else {
      params.append("dept_code", dept_code);
      params.append("username", username);
      await axios({
        method: "post",
        url: this.$api_url + "/find_data.php/main_draw",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.data.length > 0) {
            this.level = "app";
          } else {
            this.level = "create";
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
    console.log(this.level);
  },
  methods: {
    log_out() {
      this.$router.push("/login");
      this.$q.localStorage.clear();
    },
  },
});
</script>
