<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title> TFG </q-toolbar-title>
        <q-btn-dropdown :label="nombre" flat autoclose>
          <q-list>
            <q-item clickable @click="cerrarSesion">
              <q-item-section>
                <q-item-label>Cerrar sesión</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered v-if="user?.id">
      <q-list>
        <template v-for="link in essentialLinks" :key="link.title">
          <EssentialLink v-bind="link" v-if="permiso(link)" />
        </template>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import EssentialLink from "components/EssentialLink.vue";
import { useTFGStore } from "stores/tfg";
import { mapActions, mapState } from "pinia";

export default defineComponent({
  name: "MainLayout",

  components: {
    EssentialLink,
  },

  computed: {
    ...mapState(useTFGStore, ["user"]),
    essentialLinks() {
      return [
        {
          title: "Administración",
          caption: "Acciones como administrador",
          icon: "manage_accounts",
          link: { name: "adminViviendas" },
          roles: ["administrador"],
        },
        {
          title:
            this.user?.propiedades?.length > 1
              ? "Mis viviendas"
              : "Mi vivienda",
          caption: "como propietario",
          icon: "home",
          link: { name: "PropietarioPage" },
          roles: ["propietario"],
        },
        {
          title: "Mi comunidad",
          caption: "Información general",
          icon: "home",
          link: { name: "MiComunidadPage" },
          roles: ["propietario", "arrendatario"],
        },
      ];
    },
    nombre() {
      return this.user?.id ? this.user.nombre : "Comunidades de vecinos";
    },
  },

  methods: {
    ...mapActions(useTFGStore, ["logout"]),
    cerrarSesion() {
      this.logout().then(() => {
        this.$router.push({ name: "loginPage" });
      });
    },
    permiso(link) {
      for (const rol of this.user.roles) {
        if (link.roles.includes(rol)) {
          return true;
        }
      }
      return false;
    },
  },

  setup() {
    const leftDrawerOpen = ref(false);

    return {
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
});
</script>
