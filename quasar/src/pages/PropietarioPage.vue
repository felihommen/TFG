<template>
  <q-page padding>
    <q-toolbar>
      <q-toolbar-title>{{ titulo }}</q-toolbar-title>
    </q-toolbar>
    <div class="row q-gutter-md">
      <div class="col-auto" v-for="bloque in bloques" :key="bloque.id">
        <q-card v-if="Object.keys(bloque.plantas).length > 0">
          <q-item>
            <q-item-section>
              <q-item-label>Portal {{ bloque.portal }}</q-item-label>
              <q-item-label caption>
                Presidente: {{ bloque.presidente.nombre }}
                {{ bloque.presidente.apellidos }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-list>
            <template v-for="(planta, p) in bloque.plantas" :key="p">
              <q-separator />
              <q-item-label header>Planta {{ p }}</q-item-label>
              <q-item v-for="vivienda in planta.viviendas" :key="vivienda.id">
                <q-item-section>
                  <q-item-label>Puerta {{ vivienda.puerta }}</q-item-label>
                  <q-item-label caption
                    >{{ vivienda.superficie_interior }} /
                    {{ vivienda.superficie_exterior }} m<sup
                      >2</sup
                    ></q-item-label
                  >
                </q-item-section>
                <q-item-section side top>
                  <q-item-label caption>
                    {{ vivienda.numero_habitaciones }}
                    habitaci{{
                      vivienda.numero_habitaciones > 1 ? "ones" : "ón"
                    }}
                  </q-item-label>
                  <q-icon name="star" color="yellow" />
                </q-item-section>
              </q-item>
            </template>
            <q-item>
              <q-item-section>
                <q-item-label header>Recibos impagados</q-item-label>
                <q-item-label
                  v-for="recibo in recibosImpagados"
                  :key="recibo.id"
                >
                  {{ recibo.importe }} € - {{ fecha(recibo.fecha_limite) }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card>
      </div>
    </div>
  </q-page>
</template>
<script>
import { defineComponent } from "vue";
import { useTFGStore } from "src/stores/tfg";
import { mapActions, mapState } from "pinia";

export default defineComponent({
  created() {
    this.recuperaInfoPropietario();
  },
  computed: {
    ...mapState(useTFGStore, [
      "bloques",
      "recibosImpagados",
      "user",
      "viviendas",
    ]),
    misViviendas() {
      return this.user.propiedades.map(
        (propiedad) => this.viviendas[propiedad.vivienda_id]
      );
    },
    titulo() {
      return this.user.propiedades.length > 1
        ? "Mis propiedades"
        : "Mi propiedad";
    },
  },
  methods: {
    ...mapActions(useTFGStore, ["recuperaInfoPropietario"]),
    fecha(value) {
      return value.toLocaleDateString();
    },
  },
  name: "PropietarioPage",
});
</script>
