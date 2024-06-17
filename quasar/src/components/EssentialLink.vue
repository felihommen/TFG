<template>
  <q-item
    clickable
    tag="a"
    :to="link"
    v-if="user.roles.filter((rol) => roles.includes(rol))"
  >
    <q-item-section v-if="icon" avatar>
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label>{{ title }}</q-item-label>
      <q-item-label caption>{{ caption }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
import { defineComponent } from "vue";
import { useTFGStore } from "stores/tfg";
import { mapState } from "pinia";

export default defineComponent({
  computed: { ...mapState(useTFGStore, ["user"]) },
  name: "EssentialLink",
  props: {
    title: {
      type: String,
      required: true,
    },

    caption: {
      type: String,
      default: "",
    },

    link: {
      default: "#",
    },

    icon: {
      type: String,
      default: "",
    },
    roles: {
      type: Array,
      default: () => [],
    },
  },
});
</script>
