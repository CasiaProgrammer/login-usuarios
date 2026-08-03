<template>
  <div class="page-wrapper">

    <Header :usuario="usuario" @logout="$emit('logout')" />

    <div class="container py-4" style="max-width: 800px;">

      <div class="title-box mb-4">
        <h2 class="fw-semibold mb-0">Mantenimiento de Usuario</h2>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="input-group" style="max-width: 320px;">
              <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
              <input
                v-model="buscar"
                @input="buscarUsuarios"
                type="text"
                class="form-control border-start-0"
                placeholder="Buscar por nombre, apellido o DPI"
              />
            </div>
            <button class="btn btn-primary" @click="abrirCrear">
              <i class="bi bi-plus-lg"></i> Nuevo usuario
            </button>
          </div>

          <div class="table-scroll-wrapper">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr class="text-muted small text-uppercase">
                  <th class="border-0 text-center">Usuario</th>
                  <th class="border-0 text-center">DPI</th>
                  <th class="border-0 text-center">Correo</th>
                  <th class="border-0 text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="usuario in usuarios" :key="usuario.id">
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="avatar-circle">{{ usuario.nombre[0] }}{{ usuario.apellido[0] }}</div>
                      <span>{{ usuario.nombre }} {{ usuario.apellido }}</span>
                    </div>
                  </td>
                  <td class="text-muted">{{ usuario.dpi }}</td>
                  <td class="text-muted">{{ usuario.correo_electronico }}</td>
                  <td class="text-end">
                    <a href="#" @click.prevent="abrirEditar(usuario)" class="edit-icon" title="Editar">
                      <i class="bi bi-pencil-fill"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <Footer />

    <UsuarioModal
      ref="modalRef"
      :usuario="usuarioSeleccionado"
      @guardado="guardarUsuario"
      @dado-de-baja="darDeBaja"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import UsuarioModal from './UsuarioModal.vue'
import Header from './Header.vue'
import Footer from './Footer.vue'

const props = defineProps({
  usuario: { type: Object, default: null }
})
defineEmits(['logout'])

const usuarios = ref([])
const buscar = ref('')
const usuarioSeleccionado = ref(null)
const modalRef = ref(null)

async function cargarUsuarios() {
  const res = await axios.get('/api/usuarios', {
    params: buscar.value ? { buscar: buscar.value } : {}
  })
  usuarios.value = res.data
}

let debounceTimer = null
function buscarUsuarios() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(cargarUsuarios, 400)
}

function abrirCrear() {
  usuarioSeleccionado.value = null
  modalRef.value.abrir()
}

function abrirEditar(usuario) {
  usuarioSeleccionado.value = usuario
  modalRef.value.abrir()
}

async function guardarUsuario({ id, datos }) {
  if (id) {
    await axios.put(`/api/usuarios/${id}`, datos)
  } else {
    await axios.post('/api/usuarios', datos)
  }
  cargarUsuarios()
}

async function darDeBaja(id) {
  await axios.delete(`/api/usuarios/${id}`)
  cargarUsuarios()
}

onMounted(() => {
  cargarUsuarios()
})
</script>

<style scoped>
.page-wrapper {
  min-height: 100vh;
  background: #f5f6fa;
  display: flex;
  flex-direction: column;
}

.page-wrapper > .container {
  flex: 1;
}
.title-box {
  background: #e9ecef;
  border-radius: 12px;
  padding: 1.5rem;
  text-align: center;
}
.avatar-circle {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #eef0ff;
  color: #667eea;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 600;
}
.edit-icon {
  color: #667eea;
  font-size: 16px;
}
.edit-icon:hover {
  color: #4c4faa;
}
.table-scroll-wrapper {
  max-height: 400px;
  overflow-y: auto;
}
.table-scroll-wrapper thead th {
  position: sticky;
  top: 0;
  background: white;
  z-index: 1;
}
</style>