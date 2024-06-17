<template>
  <q-page padding>
    <q-toolbar>
      <q-toolbar-title>Administrador</q-toolbar-title>
      <q-tabs v-model="tab" shrink>
        <q-route-tab
          :to="{ name: 'adminViviendas' }"
          label="Viviendas"
        ></q-route-tab>
        <q-route-tab
          :to="{ name: 'adminRecibos' }"
          label="Recibos"
        ></q-route-tab>
        <q-route-tab
          :to="{ name: 'adminMensajes' }"
          label="Mensajes"
        ></q-route-tab>
      </q-tabs>
    </q-toolbar>
    <router-view />
  </q-page>
</template>
<script>
import { defineComponent } from "vue";
import { useTFGStore } from "stores/tfg";
import { mapActions } from "pinia";

export default defineComponent({
  created() {
    this.recuperaInfoAdministrador();
    this.generaRecibosComunidad();
  },
  data() {
    return { tab: "viviendas" };
  },
  methods: {
    ...mapActions(useTFGStore, [
      "recuperaInfoAdministrador",
      "generaRecibosComunidad",
    ]),
  },
  name: "administradorPage",
});
</script>
