<template>
  <Usuarios v-if="autenticado" :usuario="usuarioActual" @logout="cerrarSesion" />
  <Login v-else @login-exitoso="iniciarSesion" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Login from './Login.vue'
import Usuarios from './Usuarios.vue'

const autenticado = ref(false)
const usuarioActual = ref(null)

function configurarAxios(token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

function iniciarSesion(token, usuario) {
  localStorage.setItem('token', token)
  localStorage.setItem('usuario', JSON.stringify(usuario))
  configurarAxios(token)
  usuarioActual.value = usuario
  autenticado.value = true
}

function cerrarSesion() {
  localStorage.removeItem('token')
  localStorage.removeItem('usuario')
  delete axios.defaults.headers.common['Authorization']
  usuarioActual.value = null
  autenticado.value = false
}

onMounted(() => {
  const tokenGuardado = localStorage.getItem('token')
  const usuarioGuardado = localStorage.getItem('usuario')

  if (tokenGuardado) {
    configurarAxios(tokenGuardado)
    autenticado.value = true

    if (usuarioGuardado && usuarioGuardado !== 'undefined') {
      try {
        usuarioActual.value = JSON.parse(usuarioGuardado)
      } catch (e) {
        usuarioActual.value = null
      }
    }
  }
})
</script>