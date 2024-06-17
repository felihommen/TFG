<template>
  <q-page padding class="con-fondo">
    <div class="row justify-center">
      <div class="col-auto">
        <q-form class="column q-gutter-md" @submit.prevent="submit">
          <q-input
            type="email"
            label="Correo electrónico"
            v-model.trim="username"
            autofocus
            list="usernames"
          >
            <datalist id="usernames">
              <option value="administrador@tfg.es"></option>
              <option value="propietario@tfg.es"></option>
            </datalist>
          </q-input>
          <q-input
            type="password"
            label="Contraseña"
            v-model="password"
            placeholder="1234"
          ></q-input>
          <q-btn
            type="submit"
            label="Entrar"
            color="primary"
            :disable="!btnActivo"
          ></q-btn>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent } from "vue";
import { useTFGStore } from "stores/tfg";
import { mapActions } from "pinia";

export default defineComponent({
  computed: {
    btnActivo() {
      return this.username != "" && this.password != "";
    },
  },
  data() {
    return { username: "", password: "" };
  },
  methods: {
    ...mapActions(useTFGStore, ["login"]),
    submit() {
      this.login({ username: this.username, password: this.password })
        .then(() => this.$router.push({ name: "home" }))
        .catch((error) => {
          this.$q.notify({ type: "negative", message: error.message });
        });
    },
  },
  name: "loginPage",
});
</script>
