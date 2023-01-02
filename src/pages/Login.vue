<template>
  <q-layout view="hHh Lpr lFf">
    <q-page-container class="background-main">
      <div class="row justify-center">
        <div class="col-md-8">
          <q-page padding class="row items-center">
            <div class="row full-width">
              <div class="col-md-4 offset-md-4">
                <q-card square class="color-bg-login">
                  <div class="row">
                    <div class="col-md-12 col-xs-12">
                      <div class="q-pa-md">
                        <div
                          class="text-h6 text-indigo-7 q-pb-md text-center text-weight-bolder"
                        >
                          Login OT
                        </div>
                        <q-form
                          @submit="onSubmit"
                          @reset="onReset"
                          class="q-gutter-md"
                        >
                          <q-input
                            filled
                            bg-color="white"
                            color="indigo-8"
                            v-model="username"
                            input-class="color-font-login"
                            label="Emp Code"
                            label-color="indigo-8"
                            lazy-rules
                            :rules="[
                              (val) =>
                                (val && val.length > 0) ||
                                'Please type Username',
                            ]"
                          />

                          <!--  <q-input
                            filled
                            color="indigo-8"
                            bg-color="white"
                            type="password"
                            input-class="color-font-login"
                            v-model="password"
                            label="Password"
                            label-color="indigo-8"
                            lazy-rules
                            :rules="[
                              (val) =>
                                (val && val.length > 0) ||
                                'Please type Password',
                            ]"
                          /> -->

                          <q-input
                            filled
                            type="number"
                            color="indigo-8"
                            bg-color="white"
                            input-class="color-font-login"
                            v-model="department_code"
                            label="Department Code"
                            label-color="indigo-8"
                            :rules="[
                              (val) =>
                                (val && val.length > 0) ||
                                'Please type Department Code',
                            ]"
                          />
                          <br />

                          <div>
                            <q-btn
                              label="Login"
                              type="submit"
                              justify-center
                              color="indigo-8"
                            />

                            <q-btn
                              label="Reset"
                              type="reset"
                              color="primary"
                              flat
                              class="q-ml-sm"
                            />
                          </div>
                        </q-form>
                      </div>
                    </div>
                  </div>
                </q-card>
              </div>
            </div>
          </q-page>
        </div>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import axios from "axios";
import { useQuasar } from "quasar";
import { ref } from "vue";
import { onBeforeUnmount } from "vue";
import { Cookies } from "quasar";
const $q = useQuasar();
export default {
  data() {
    return {
      department_code: "",
      username: ref(""),
      password: ref(""),
      check_status_login_last: false,
      list_dept_code: [],
      check_status_login_1: ref(false),
      check_status_login_2: ref(false),
    };
  },
  methods: {
    async onSubmit() {
      this.check_status_login_1 = false;
      this.check_status_login_2 = false;

      const params = new FormData();
      params.append("dept_code", this.department_code);
      params.append("username", this.username);
      //params.append("password", this.password);
      for (var pair of params.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }
      await axios({
        method: "post",
        url: this.$api_url + "/login.php/login_1",
        data: params,
      })
        .then((resp) => {
          console.log(resp.data);
          if (resp.data.status == false) {
            this.$q.localStorage.set("username", this.username);
            this.$q.localStorage.set("dept_code", this.department_code);

            const params3 = new FormData();
            params3.append("username", this.username);
            params3.append("dept_code", this.department_code);

            axios({
              method: "post",
              url: this.$api_url + "/login.php/login_3",
              data: params3,
            }).then((resp) => {
              console.log(resp.data);
              if (resp.data.data.length > 0) {
                axios({
                  method: "post",
                  url: this.$api_url + "/login.php/login_2",
                  data: params3,
                })
                  .then((resp) => {
                    console.log(resp.data);
                    if (resp.data.data.length > 0) {
                      this.$q.localStorage.set(
                        "dept_emp_app",
                        resp.data.data[0].DEPT_EMP_APP
                      );

                      this.$q.localStorage.set(
                        "divison_emp_app",
                        resp.data.data[0].DIVISON_EMP_APP
                      );
                    } else {
                      const params4 = new FormData();
                      params4.append("username", this.username);
                      axios({
                        method: "post",
                        url: this.$api_url + "/login.php/check_login_user_dept",
                        data: params4,
                      }).then((resp) => {
                        console.log(resp.data);
                        if (resp.data.data.length > 0) {
                          this.$q.localStorage.set(
                            "dept_emp_app",
                            resp.data.data[0].DEPT_EMP_APP
                          );

                          this.$q.localStorage.set(
                            "divison_emp_app",
                            resp.data.data[0].DIVISON_EMP_APP
                          );
                        }
                      });
                    }
                  })
                  .catch((error) => {
                    console.log(error);
                  });

                const option = {
                  secure: true,
                  expires: "2h", // in 15 minutes, 10 seconds
                };

                this.$router.push({
                  path: "/main",
                });
              } else {
                this.$q.notify({
                  message: "Username or password incorrect",
                  position: "center",
                  icon: "announcement",
                  color: "red-9",
                });
              }
            });
          } else {
            console.log(this.username);
            this.$q.localStorage.set("username", this.username);
            this.$q.localStorage.set("dept_code", this.department_code);
            const params3 = new FormData();
            params3.append("username", this.username);
            params3.append("dept_code", this.department_code);
            axios({
              method: "post",
              url: this.$api_url + "/login.php/check_login_user_dept",
              data: params3,
            })
              .then((resp) => {
                console.log(resp.data);
                if (resp.data.data.length > 0) {
                  this.$q.localStorage.set(
                    "dept_emp_app",
                    resp.data.data[0].DEPT_EMP_APP
                  );

                  this.$q.localStorage.set(
                    "divison_emp_app",
                    resp.data.data[0].DIVISON_EMP_APP
                  );
                }
              })
              .catch((error) => {
                console.log(error);
              });

            const option = {
              secure: true,
              expires: "2h", // in 15 minutes, 10 seconds
            };

            this.$router.push({
              path: "/main",
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
.background-login {
  background-image: url("../assets/2650452.jpg");
  background-size: cover;
  opacity: 1;
}
.background-main {
  background-image: url("../assets/5291450.jpg");
  background-size: cover;
}
.padding-login-window {
  padding-top: 10%;
  padding-bottom: 10%;
}
.color-font-login {
  color: #256d85;
}
.color-bg-login {
  background-color: #c2f0e6;
}
.area-pic {
  width: 237.73px;
  height: 320.92px;
}
</style>
0
