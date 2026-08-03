<template>
  <div class="login-wrapper d-flex justify-content-center align-items-center">
    <div class="login-card">
      <div class="text-center mb-4">
        <div class="login-icon mb-3">
          <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4 class="fw-semibold mb-1">Bienvenido</h4>
        <p class="text-muted small">Ingresá con tu DPI y contraseña</p>
        <p class="text-muted small">Usuario de Prueba</p>
        <p class="text-muted small">DPI: 5934820714908 Contraseña: 12345678</p>
      </div>

      <div v-if="error" class="alert alert-danger py-2 small">{{ error }}</div>

      <form @submit.prevent="login">
        <div class="mb-3">
          <label class="form-label small fw-semibold">DPI</label>
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
            <input v-model="form.dpi" type="text" class="form-control" required />
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label small fw-semibold">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
            <input
              v-model="form.password"
              :type="mostrarPassword ? 'text' : 'password'"
              class="form-control"
              required
            />
            <button
              type="button"
              class="btn btn-outline-secondary"
              @click="mostrarPassword = !mostrarPassword"
              tabindex="-1"
            >
              <i class="bi" :class="mostrarPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" :disabled="cargando">
          <span v-if="cargando" class="spinner-border spinner-border-sm me-2"></span>
          {{ cargando ? 'Ingresando...' : 'Ingresar' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['login-exitoso'])

const form = ref({ dpi: '', password: '' })
const error = ref('')
const cargando = ref(false)
const mostrarPassword = ref(false)

async function login() {
  error.value = ''
  cargando.value = true
  try {
    const res = await axios.post('/api/login', form.value)
    emit('login-exitoso', res.data.token, res.data.usuario)
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al iniciar sesión'
  } finally {
    cargando.value = false
  }
}
</script>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.login-card {
  background: white;
  border-radius: 16px;
  padding: 2.5rem;
  width: 380px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}
.login-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: #eef0ff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  font-size: 28px;
  color: #667eea;
}
</style>