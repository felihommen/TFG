const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/IndexPage.vue"),
        name: "home",
      },
      {
        path: "login",
        component: () => import("pages/LoginPage.vue"),
        name: "loginPage",
      },
      {
        path: "admin",
        component: () => import("pages/AdminPage.vue"),
        name: "adminPage",
        children: [
          {
            path: "viviendas",
            component: () => import("pages/admin/ViviendasPage.vue"),
            name: "adminViviendas",
          },
          {
            path: "recibos",
            component: () => import("pages/admin/RecibosPage.vue"),
            name: "adminRecibos",
          },
          {
            path: "mensajes",
            component: () => import("pages/admin/MensajesPage.vue"),
            name: "adminMensajes",
          },
        ],
      },
      {
        path: "propietario",
        component: () => import("pages/PropietarioPage.vue"),
        name: "PropietarioPage",
      },
      {
        path: "comunidad",
        component: () => import("pages/MiComunidadPage.vue"),
        name: "MiComunidadPage",
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
