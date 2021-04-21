<template>
  <aside class="app-sidebar" :class="computedClass" :style="computedStyle">
    <ul class="app-sidebar__menu">
      <template v-for="(item, key) in items">
        <app-sidebar-link-group
          :key="key"
          :minimized="minimized"
          :icon="item.meta && item.meta.iconClass"
          v-if="item.children"
          :title="$t(item.displayName)"
          :children="item.children"
          :active-by-default="hasActiveByDefault(item)"
        >
          <app-sidebar-link
            v-for="(subMenuItem, key) in item.children"
            :key="key"
            :to="{ name: subMenuItem.name }"
            :title="$t(subMenuItem.displayName)"
          />
        </app-sidebar-link-group>
        <app-sidebar-link
          v-else
          :key="key"
          :minimized="minimized"
          :active-by-default="item.name === $route.name"
          :icon="item.meta && item.meta.iconClass"
          :to="{ name: item.name }"
          :title="$t(item.displayName)"
        />
      </template>
    </ul>
  </aside>
</template>

<script>
import { navigationRoutesAdmin } from "./NavigationRoutesAdmin";
import { navigationRoutesSupervisor } from "./NavigationRoutesSupervisor";
import { navigationRoutesFull } from "./NavigationRoutesFull";
import { navigationRoutesOficial } from "./NavigationRoutesOficial";
import { navigationRoutesConcesionario } from "./NavigationRoutesConcesionario";
import { navigationRoutesConcesionarioCalculadora } from "./NavigationRoutesConcesionarioCalculadora";
import { navigationRoutesCobradoresHN } from "./NavigationRoutesCobradoresHN";
import { navigationRoutesSoloCalculadoraHN } from "./NavigationRoutesSoloCalculadoraHN";
import AppSidebarLink from "./components/AppSidebarLink";
import AppSidebarLinkGroup from "./components/AppSidebarLinkGroup";
import { ColorThemeMixin } from "../../../services/vuestic-ui";
import { mapState } from "vuex";

export default {
  name: "app-sidebar",
  inject: ["contextConfig"],
  components: {
    AppSidebarLink,
    AppSidebarLinkGroup,
  },
  mixins: [ColorThemeMixin],
  props: {
    minimized: {
      type: Boolean,
      required: true,
    },
    color: {
      type: String,
      default: "secondary",
    },
  },
  data() {
    return {
      items: [],
      itemsAdmin: navigationRoutesAdmin.routes,
      itemsFull: navigationRoutesFull.routes,
      itemsSupervisor: navigationRoutesSupervisor.routes,
      itemsOficial: navigationRoutesOficial.routes,
      itemsCE: navigationRoutesConcesionario.routes,
      itemsCECalculadora: navigationRoutesConcesionarioCalculadora.routes,
      itemsCobradores: navigationRoutesCobradoresHN.routes,
      itemsSoloCalculadoraHN: navigationRoutesSoloCalculadoraHN.routes,
    };
  },

  mounted() {
    this.setNavigationRoutes();
  },
  computed: {
    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
      "perfilUsuario",
      "verCalculadora",
    ]),

    setNavigationRoutes() {
      if (
        (this.esConcesionario || this.esVinculo) &&
        this.perfilUsuario != "7"
      ) {
        if (this.verCalculadora) {
          this.items = this.itemsCECalculadora;
        } else {
          this.items = this.itemsCE;
        }
      } else {
        switch (this.perfilUsuario) {
          case 3:
            this.items = this.itemsOficial;
            break;
          case 4:
            this.items = this.itemsSupervisor;
            break;
          case 5:
            this.items = this.itemsAdmin;
            break;
          case 6:
            this.items = this.itemsFull;
            break;
          case 7:
            this.items = this.itemsCobradores;
            break;
          case 8:
            this.items = this.itemsSoloCalculadoraHN;
            break;
        }
      }
    },

    computedClass() {
      return {
        "app-sidebar--minimized": this.minimized,
      };
    },
    computedStyle() {
      return {
        backgroundColor: this.contextConfig.invertedColor
          ? "white"
          : this.colorComputed,
      };
    },
  },
  methods: {
    hasActiveByDefault(item) {
      return item.children.some((child) => child.name === this.$route.name);
    },
  },
};
</script>

<style lang="scss">
.app-sidebar {
  overflow: auto;
  display: flex;
  max-height: 100%;
  flex: 0 0 16rem;

  @include media-breakpoint-down(sm) {
    flex: 0 0 100%;
  }

  &--minimized {
    flex: 0 0 3.25rem;
  }

  &__menu {
    margin-bottom: 0;
    padding-top: 2.5625rem;
    padding-bottom: 2.5rem;
    list-style: none;
    padding-left: 0;
    width: 100%;
  }
}
</style>
