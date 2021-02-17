export const navigationRoutesCobradoresHN = {
  root: {
    name: "/",
    displayName: "navigationRoutesCobradoresHN.home"
  },

  routes: [
    {
      name: "dashboard",
      displayName: "Inicio",
      position: "start",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-files"
      }
    },

    {
      name: "user",
      displayName: "Usuario",
      position: "end",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-user"
      },
      children: [
        {
          name: "changepass",
          displayName: "Cambiar Contraseña"
        },

        {
          name: "logout",
          redirectTo: "login",
          displayName: "Cerrar Sesión"
        }
      ]
    }
  ]
};
