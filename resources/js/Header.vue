<template>
  <header class="top-header d-flex justify-content-between align-items-center px-4">
    <span class="fw-semibold text-white">Sistema de Usuarios</span>

    <div class="d-flex align-items-center gap-3">
      <span class="text-white small" v-if="usuario">
        <i class="bi bi-person-circle me-1"></i>
        {{ usuario.nombre }} {{ usuario.apellido }}
      </span>
      <span class="text-white small">
        <i class="bi bi-clock me-1"></i>
        {{ horaActual }}
      </span>
      <button class="btn btn-sm btn-light" @click="$emit('logout')">
        <i class="bi bi-box-arrow-right"></i> Salir
      </button>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineProps({
  usuario: { type: Object, default: null }
})
defineEmits(['logout'])

const horaActual = ref('')
let intervalo = null

function actualizarHora() {
  horaActual.value = new Date().toLocaleTimeString('es-GT', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

onMounted(() => {
  actualizarHora()
  intervalo = setInterval(actualizarHora, 1000)
})

onUnmounted(() => {
  clearInterval(intervalo)
})
</script>

<style scoped>
.top-header {
  background: #0d1b3e;
  height: 56px;
}
</style>