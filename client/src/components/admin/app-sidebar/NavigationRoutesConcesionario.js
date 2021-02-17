export const navigationRoutesConcesionario = {
  root: {
    name: "/",
    displayName: "navigationRoutesConcesionario.home"
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
        }
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
      name: "habernesnetos",
      displayName: "Haberes Netos",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-statistics"
      },
      disabled: true,
      children: [
        {
          name: "modelo",
          displayName: "Modelo"
        },

        {
          name: "haberesnetos",
          displayName: "HN"
        }
      ]
    },
    {
      name: "reportes",
      displayName: "Reportes",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-tables"
      },
      disabled: true,
      children: [
        {
          name: "reportecompras",
          displayName: "Reporte Cartera"
        }
      ]
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
