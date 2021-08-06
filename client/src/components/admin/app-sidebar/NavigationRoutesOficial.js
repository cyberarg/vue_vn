export const navigationRoutesOficial = {
  root: {
    name: "/",
    displayName: "navigationRoutesOficial.home"
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
      name: "dashboard",
      displayName: "CRM",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-dashboard"
      },
      disabled: true,
      children: [
        {
          name: "gestiondatos",
          displayName: "Gestión de Datos"
        },
        {
          name: "estadogestion",
          displayName: "Estado de la Gestión"
        }, 
        /*
        {
          name: "datosweb",
          displayName: "Datos Web"
        },
        */
      ]
    },
    {
      name: "gestioncompras",
      displayName: "Gestión Compras",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-forms"
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
