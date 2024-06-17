<template>
  <q-card>
    <q-tabs v-model="tab">
      <q-tab
        :label="usuario.nombre + ' ' + usuario.apellidos"
        :name="usuario.id"
        v-for="usuario in interlocutores"
        :key="usuario.id"
      ></q-tab>
    </q-tabs>
    <q-list v-if="mensajesUsuario">
      <q-expansion-item
        expand-separator
        icon="message"
        :label="mensaje.titulo"
        :caption="fecha(mensaje.fecha)"
        v-for="mensaje in mensajesUsuario"
        :key="mensaje.id"
      >
        <q-card>
          <q-card-section>
            {{ mensaje.mensaje }}
          </q-card-section>
          <q-card-actions align="right">
            <q-btn
              label="Marcar como leído"
              color="primary"
              v-if="!mensaje.leido"
              @click="leerMensaje(mensaje.id)"
            ></q-btn>
            <q-chip v-else icon="mark_email_read">{{
              fecha(mensaje.leido)
            }}</q-chip>
          </q-card-actions>
        </q-card>
      </q-expansion-item>
    </q-list>
  </q-card>
</template>
<script>
import { defineComponent } from "vue";
import { useTFGStore } from "src/stores/tfg";
import { mapActions, mapState } from "pinia";
export default defineComponent({
  computed: {
    ...mapState(useTFGStore, ["administrador", "mensajes", "usuarios"]),
    interlocutores() {
      const response = [];
      for (const id in this.usuarios) {
        if (id != this.administrador.id) {
          response.push(this.usuarios[id]);
        }
      }
      return response;
    },
    mensajesUsuario() {
      return this.mensajes[this.tab];
    },
  },
  created() {
    this.recuperaMensajes();
  },
  data() {
    return { tab: "" };
  },
  methods: {
    ...mapActions(useTFGStore, ["leerMensaje", "recuperaMensajes"]),
    fecha(value) {
      return new Date(value).toLocaleDateString();
    },
  },
  name: "MensajesPage",
});
</script>
