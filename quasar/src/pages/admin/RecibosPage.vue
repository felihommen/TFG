<template>
  <q-card>
    <q-card-section>
      <div class="text-h6">Recibos pendientes</div>
    </q-card-section>

    <q-card-section>
      <q-markup-table>
        <thead>
          <tr>
            <th class="text-left">Nombre</th>
            <th class="text-left">Vivienda</th>
            <th class="text-right">Importe</th>
            <th class="text-left">Fecha límite</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="recibo in recibosImpagados" :key="recibo.id">
            <td>{{ nombreUsuario(recibo) }}</td>
            <td>{{ nombreVivienda(recibo) }}</td>
            <td class="text-right">{{ recibo.importe }} €</td>
            <td class="{'text-danger': recibo.fecha_limite < hoy}">
              {{ fecha(recibo.fecha_limite) }}
            </td>
          </tr>
        </tbody>
      </q-markup-table>
    </q-card-section>
  </q-card>
</template>
<script>
import { defineComponent } from "vue";
import { useTFGStore } from "stores/tfg";
import { mapState } from "pinia";

export default defineComponent({
  computed: {
    ...mapState(useTFGStore, ["recibosImpagados", "usuarios", "viviendas"]),
    hoy() {
      return new Date();
    },
  },
  methods: {
    fecha(fecha) {
      return fecha.toLocaleDateString();
    },
    nombreUsuario(recibo) {
      const usuario = this.usuarios[recibo.usuario_id];
      if (usuario) {
        return `${usuario.nombre} ${usuario.apellidos}`;
      } else {
        return "aaa";
      }
    },
    nombreVivienda(recibo) {
      const vivienda = this.viviendas[recibo.vivienda_id];
      return `Portal ${vivienda.bloque.portal}, ${vivienda.planta}º ${vivienda.puerta}`;
    },
  },
  name: "RecibosPage",
});
</script>
