<template>
  <aside class="app-topbar" :style="computedStyles">
    <ul class="app-topbar__menu">
      <template v-for="(item, key) in items">
        <app-topbar-link-group
          class="app-topbar__menu-group"
          v-if="item.children"
          :key="key"
          :is-active="hasActiveByDefault(item)"
          :icon="[
            'sidebar-menu-item-icon vuestic-iconset',
            item.meta.iconClass,
          ]"
          :title="$t(item.displayName)"
          :is-multi-row="item.children.length > 10"
        >
          <app-topbar-link-group-item
            class="app-topbar__menu-group-item"
            :class="{
              'app-topbar__menu-group-item--multi-row':
                item.children.length > 10,
            }"
            v-for="(subMenuItem, key) in item.children"
            :key="key"
            :to="{ name: subMenuItem.name }"
            :is-active="subMenuItem.name === $route.name"
            :title="$t(subMenuItem.displayName)"
          />
        </app-topbar-link-group>
        <app-topbar-link
          class="app-topbar__link"
          v-else
          :key="key"
          :is-active="item.name === $route.name"
          :icon="[
            'sidebar-menu-item-icon vuestic-iconset',
            item.meta.iconClass,
          ]"
          :to="{ name: item.name }"
          >{{ $t(item.displayName) }}</app-topbar-link
        >
      </template>
    </ul>
  </aside>
</template>

<script>
import AppTopbarLink from "./components/AppTopbarLink";
import AppTopbarLinkGroup from "./components/AppTopbarLinkGroup";
import AppTopbarLinkGroupItem from "./components/AppTopbarLinkGroupItem";
import { navigationRoutes } from "../app-sidebar/NavigationRoutes";
import { navigationRoutesAdmin } from "../app-sidebar/NavigationRoutesAdmin";
import { navigationRoutesFull } from "../app-sidebar/NavigationRoutesFull";
import { navigationRoutesSupervisor } from "../app-sidebar/NavigationRoutesSupervisor";
import { navigationRoutesOficial } from "../app-sidebar/NavigationRoutesOficial";
import { navigationRoutesConcesionario } from "../app-sidebar/NavigationRoutesConcesionario";
import { navigationRoutesConcesionarioCalculadora } from "../app-sidebar/NavigationRoutesConcesionarioCalculadora";
import { navigationRoutesCobradoresHN } from "../app-sidebar/NavigationRoutesCobradoresHN";
import { navigationRoutesSoloCalculadoraHN } from "../app-sidebar/NavigationRoutesSoloCalculadoraHN";
import { ColorThemeMixin } from "../../../services/vuestic-ui";
import { mapState } from "vuex";

export default {
  name: "app-topbar",
  mixins: [ColorThemeMixin],
  inject: ["contextConfig"],
  components: {
    AppTopbarLink,
    AppTopbarLinkGroup,
    AppTopbarLinkGroupItem,
  },
  props: {},
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

    computedStyles() {
      if (this.contextConfig.invertedColor) {
        return {
          backgroundColor: "white",
          boxShadow: "0 2px 3px 0 rgba(52, 56, 85, 0.25)",
        };
      }

      return {
        backgroundColor: this.$themes.secondary,
      };
    },
  },

  mounted() {
    this.setNavigationRoutes();
  },

  data() {
    /*
    return {
      items: navigationRoutes.routes
    };
    */
    return {
      items: [],
      itemsAdmin: navigationRoutesAdmin.routes,
      itemsSupervisor: navigationRoutesSupervisor.routes,
      itemsFull: navigationRoutesFull.routes,
      itemsOficial: navigationRoutesOficial.routes,
      itemsCE: navigationRoutesConcesionario.routes,
      itemsCECalculadora: navigationRoutesConcesionarioCalculadora.routes,
      itemsCobradores: navigationRoutesCobradoresHN.routes,
      itemsSoloCalculadoraHN: navigationRoutesSoloCalculadoraHN.routes,
    };
  },
  methods: {
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
        var perfil;
        perfil = parseInt(this.perfilUsuario);
        switch (perfil) {
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

    hasActiveByDefault(item) {
      return item.children.some((child) => child.name === this.$route.name);
    },
  },
};
</script>

<style lang="scss">
.app-topbar {
  transition: all 0.3s ease;
  width: 100%;
  display: flex;

  &__menu {
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    min-height: 4rem;
    margin: 0 auto;

    &-group {
      @include media-breakpoint-down(sm) {
        flex-grow: 1;
      }

      padding-right: 10px;
    }

    &-group-item {
      display: inline-flex;
      flex-wrap: wrap;
      width: 100%;
      white-space: nowrap;
      color: inherit;

      &--multi-row {
        width: 33.33%;
      }
    }
  }

  & + .content-wrap {
    margin-left: 0;
    padding-left: 2.5rem;
    padding-right: 2.5rem;
  }

  @include media-breakpoint-down(sm) {
    top: $sidebar-mobile-top;

    &__menu {
      max-width: 100%;
      padding: 0 1rem;
    }
  }

  &--minimized {
    left: 0;

    .va-topbar-link-group {
      .va-topbar-link__content {
        padding-right: 0;
      }
    }

    & + .content-wrap {
      margin-left: $sidebar-width--hidden !important;
    }
  }
}
</style>
