const routes = [
  {
    path: "/main",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      { path: "", component: () => import("pages/Create_ot.vue") },
      { path: "test", component: () => import("pages/Test.vue") },
      { path: "search", component: () => import("pages/Search.vue") },
      { path: "app", component: () => import("pages/Approve.vue") },
      { path: "app-hr", component: () => import("pages/Approve_hr.vue") },
      { path: "app-cf", component: () => import("pages/Hr_confirm.vue") },
      { path: "download", component: () => import("pages/Download.vue") },
      { path: "list-app", component: () => import("pages/Listapp.vue") },
      { path: "ex-data", component: () => import("pages/Export_data.vue") },
    ],
  },
  {
    path: "/",
    component: () => import("pages/Login.vue"),
    children: [
      { path: "", component: () => import("pages/Login.vue") },
      { path: "/login", component: () => import("pages/Login.vue") },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes;
