<template>
  <q-page padding>
    <q-toolbar>
      <q-toolbar-title>Mi comunidad</q-toolbar-title>
      <q-btn
        icon="message"
        label="Enviar mensaje"
        @click="dialogoMensaje"
        v-if="!esAdministrador"
      ></q-btn>
    </q-toolbar>
    <div class="row">
      <div class="col-auto">
        <q-card>
          <q-card-section>
            <div class="text-h6">Administrador</div>
          </q-card-section>
          <q-list>
            <q-item title="Nombre">
              <q-item-section avatar>
                <q-icon name="badge"></q-icon>
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{ administrador.nombre }} {{ administrador.apellidos }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item title="Teléfono">
              <q-item-section avatar>
                <q-icon name="phone"></q-icon>
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{ administrador.telefono }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item title="Correo electrónico">
              <q-item-section avatar>
                <q-icon name="email"></q-icon>
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{ administrador.email }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card>
      </div>
    </div>

    <q-dialog v-model="verDialogoMensaje">
      <q-card>
        <q-toolbar>
          <q-toolbar-title>Enviar un mensaje al administrador</q-toolbar-title>
        </q-toolbar>
        <q-card-section>
          <q-input
            label="Asunto"
            v-model="titulo"
            maxlength="100"
            counter
          ></q-input>
          <q-input label="Mensaje" autogrow v-model="mensaje"> </q-input>
        </q-card-section>
        <q-card-actions align="between">
          <q-btn label="Cancelar" v-close-popup icon="cancel"></q-btn>
          <q-btn
            label="Enviar"
            color="primary"
            icon="send"
            @click="enviarMensaje"
          ></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
import { defineComponent } from "vue";
import { useTFGStore } from "src/stores/tfg";
import { mapActions, mapState } from "pinia";

export default defineComponent({
  computed: {
    ...mapState(useTFGStore, ["administrador", "user"]),
    esAdministrador() {
      return this.user.roles.includes("administrador");
    },
  },
  created() {
    this.recuperaInfoPropietario();
  },
  data() {
    return { mensaje: "", titulo: "", verDialogoMensaje: false };
  },
  methods: {
    ...mapActions(useTFGStore, [
      "enviarMensajeAdmin",
      "recuperaInfoPropietario",
    ]),
    dialogoMensaje() {
      this.verDialogoMensaje = true;
    },
    enviarMensaje() {
      this.enviarMensajeAdmin(this.titulo, this.mensaje)
        .then(() => {
          this.$q.notify({ type: "positive", message: "Mensaje enviado" });
        })
        .catch((error) => {
          this.$q.notify({
            type: "negative",
            message: "No se ha podido enviar el mensaje",
          });
        });
      this.verDialogoMensaje = false;
      this.titulo = "";
      this.mensaje = "";
    },
  },
  name: "MiComunidadPage",
});
</script>
