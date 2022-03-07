export const navigationRoutesSupervisor = {
  root: {
    name: "/",
    displayName: "navigationRoutesSupervisor.home"
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
      name: "parameters",
      displayName: "Parámetros",
      meta: {
        iconClass: "vuestic-iconset vuestic-iconset-settings"
      },
      children: [
        {
          name: "oficiales",
          displayName: "Oficiales"
        },
        {
          name: "importardatos",
          displayName: "Importación de Datos"
        },
        {
          name: "importarhn",
          displayName: "Importación de HN"
        }
      ]
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
        {
          name: "asignaciondatos",
          displayName: "Asignación de Datos"
        }, 
        {
          name: "datosweb",
          displayName: "Datos Web"
        },
        {
          name: "calendario",
          displayName: "Calendario"
        },
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
        },
        {
          name: "reporteasignaciones",
          displayName: "Reporte Asig. por Período"
        },
        {
          name: "reporteaobservaciones",
          displayName: "Reporte Obs. por Oficial"
        },

        {
          name: "reportecomprasobjetivos",
          displayName: "Reporte Compras"
        },

        {
          name: "reportecaidas",
          displayName: "Reporte Caidas"
        },

        {
          name: "reportecomisiones",
          displayName: "Reporte Comisiones"
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
