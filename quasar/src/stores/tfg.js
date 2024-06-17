import { defineStore } from "pinia";
import { api } from "boot/axios";
import { date } from "quasar";
const { extractDate } = date;

function procesaBloques({ bloques, usuarios }) {
  const response = {};
  for (const bloque of bloques) {
    response[bloque.id] = {
      ...bloque,
      presidente: usuarios[bloque.presidente_id],
      plantas: {},
    };
  }
  return response;
}

function procesaMensajes({ mensajes }) {
  const response = {};
  for (const mensaje of mensajes) {
    if (!response[mensaje.usuario_id]) {
      response[mensaje.usuario_id] = [];
    }
    response[mensaje.usuario_id].push(mensaje);
  }

  for (const usuario_id in response) {
    response[usuario_id].sort((a, b) => a.fecha.localeCompare(b.fecha));
  }

  return response;
}

function procesaViviendas({ viviendas, bloques }) {
  const response = {};
  for (const vivienda of viviendas) {
    const bloque = bloques[vivienda.bloque_id];
    const planta = vivienda.planta;
    if (!bloque.plantas[planta]) {
      bloque.plantas[planta] = { viviendas: {} };
    }
    bloque.plantas[planta].viviendas[vivienda.id] = vivienda;
    response[vivienda.id] = {
      ...vivienda,
      bloque,
    };
  }
  return response;
}

function procesaRecibos({ recibos }) {
  const response = {};
  for (const recibo of recibos) {
    response[recibo.id] = {
      ...recibo,
      fecha_limite: extractDate(recibo.fecha_limite, "YYYY-MM-DD"),
    };
  }
  return response;
}

export const useTFGStore = defineStore("tfg", {
  state: () => ({
    propietario: {
      viviendas: [],
      viviendasOrganizadas: {},
      usuarios: [],
    },

    user: null,
    info: {},
    bloques: {},
    viviendas: {},
    usuarios: {},
    recibosImpagados: {},
    mensajes: {},
  }),

  getters: {
    administrador() {
      return this.usuarios[this.info["admin user"]];
    },
  },

  actions: {
    login({ username, password }) {
      return new Promise((resolve, reject) => {
        const data = new FormData();
        data.append("username", username);
        data.append("password", password);
        api
          .post("/login.php", data)
          .then((response) => {
            if (response.data?.id) {
              this.user = response.data;

              for (const propiedad of this.user.propiedades) {
                propiedad.fecha_compra = extractDate(
                  propiedad.fecha_compra,
                  "YYYY-MM-DD"
                );
              }

              if (this.user.roles.includes("administrador")) {
                this.recuperaInfoAdministrador();
              } else if (this.user.roles.includes("propietario")) {
                this.recuperaInfoPropietario();
              }

              resolve(response.data);
            } else {
              this.user = null;
              reject({ message: "Nombre de usuario o contraseña incorrecto" });
            }
          })
          .catch((error) => {
            reject(error);
          });
      });
    },

    logout() {
      return new Promise((resolve, reject) => {
        api
          .post("logout.php")
          .then(() => {
            this.$reset();
            resolve();
          })
          .catch((error) => reject(error));
      });
    },

    leerMensaje(id) {
      return new Promise((resolve, reject) => {
        api
          .post("mensajes.php", { id })
          .then((response) => {
            this.recuperaMensajes();
            resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    enviarMensajeAdmin(titulo, mensaje) {
      return new Promise((resolve, reject) => {
        api
          .post("/mensajes.php", { titulo, mensaje })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },

    recuperaInfoAdministrador() {
      return new Promise((resolve, reject) => {
        api
          .get("/administrador.php")
          .then((response) => {
            const data = response.data;

            this.info = {};
            data.info.forEach((registro) => {
              this.info[registro.clave] = registro.valor;
            });

            this.usuarios = {};
            data.usuarios.forEach((usuario) => {
              this.usuarios[usuario.id] = usuario;
            });

            this.bloques = procesaBloques({
              bloques: data.bloques,
              usuarios: this.usuarios,
            });

            this.viviendas = procesaViviendas({
              viviendas: data.viviendas,
              bloques: this.bloques,
            });

            this.recibosImpagados = procesaRecibos({
              recibos: data.recibosImpagados,
            });

            resolve();
          })
          .catch((error) => reject(error));
      });
    },

    recuperaMensajes() {
      return new Promise((resolve, reject) => {
        api
          .get("/mensajes.php")
          .then((response) => {
            this.mensajes = procesaMensajes({
              mensajes: response.data.mensajes,
            });
            resolve();
          })
          .catch((error) => reject(error));
      });
    },

    recuperaInfoPropietario() {
      return new Promise((resolve, reject) => {
        api
          .get("/propietario.php")
          .then((response) => {
            const data = response.data;

            this.info = {};
            data.info.forEach((registro) => {
              this.info[registro.clave] = registro.valor;
            });

            this.usuarios = {};
            data.usuarios.forEach((usuario) => {
              this.usuarios[usuario.id] = usuario;
            });

            this.bloques = procesaBloques({
              bloques: data.bloques,
              usuarios: this.usuarios,
            });

            this.viviendas = procesaViviendas({
              viviendas: data.viviendas,
              bloques: this.bloques,
            });

            this.recibosImpagados = procesaRecibos({
              recibos: data.recibosImpagados,
            });

            resolve();
          })
          .catch((error) => reject(error));
      });
    },

    generaRecibosComunidad() {
      return new Promise((resolve, reject) => {
        api
          .post("/recibos.php")
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
  },
});
