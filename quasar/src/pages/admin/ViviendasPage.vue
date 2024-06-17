<template>
  <div class="row q-gutter-md">
    <div class="col-auto" v-for="bloque in bloques" :key="bloque.id">
      <q-card>
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
                  {{ vivienda.superficie_exterior }} m<sup>2</sup></q-item-label
                >
              </q-item-section>
              <q-item-section side top>
                <q-item-label caption>
                  {{ vivienda.numero_habitaciones }}
                  habitaci{{ vivienda.numero_habitaciones > 1 ? "ones" : "ón" }}
                </q-item-label>
                <q-icon name="star" color="yellow" />
              </q-item-section>
            </q-item>
          </template>
        </q-list>
      </q-card>
    </div>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { useTFGStore } from "stores/tfg";
import { mapState } from "pinia";

export default defineComponent({
  computed: { ...mapState(useTFGStore, ["bloques"]) },
  name: "adminViviendas",
});
</script>
