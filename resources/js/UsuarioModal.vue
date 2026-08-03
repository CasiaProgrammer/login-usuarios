<template>
  <div class="modal fade" tabindex="-1" ref="modalRef">
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-semibold">
            <i class="bi" :class="esEdicion ? 'bi-pencil-square' : 'bi-person-plus'"></i>
            {{ esEdicion ? 'Editar Usuario' : 'Crear Usuario' }}
          </h5>
          <button type="button" class="btn-close" @click="cerrar"></button>
        </div>
        <form @submit.prevent="guardar">
          <div class="modal-body pt-2">

            <div v-if="errorMensaje" class="alert alert-danger py-2 small">
              <i class="bi bi-exclamation-triangle-fill me-1"></i>
              {{ errorMensaje }}
            </div>

            <div class="row g-3">
              <div class="col-6">
                <label class="form-label small fw-semibold">Nombre</label>
                <input v-model="form.nombre" type="text" class="form-control" required />
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold">Apellido</label>
                <input v-model="form.apellido" type="text" class="form-control" required />
              </div>
            </div>

            <div class="mt-3">
              <label class="form-label small fw-semibold">DPI</label>
              <input
                v-model="form.dpi"
                type="text"
                class="form-control"
                maxlength="13"
                inputmode="numeric"
                @input="form.dpi = form.dpi.replace(/\D/g, '').slice(0, 13)"
                required
              />
              <small class="text-muted">{{ form.dpi.length }}/13 dígitos</small>
            </div>

            <div class="mt-3">
              <label class="form-label small fw-semibold">Correo Electrónico</label>
              <input v-model="form.correo_electronico" type="email" class="form-control" required />
            </div>

            <div class="mt-3">
              <label class="form-label small fw-semibold">
                Contraseña
                <span v-if="esEdicion" class="text-muted fw-normal">(dejar en blanco para no cambiar)</span>
              </label>
              <div class="input-group">
                <input
                  v-model="form.password"
                  :type="mostrarPassword ? 'text' : 'password'"
                  class="form-control"
                  :required="!esEdicion"
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
          </div>
          <div class="modal-footer border-0 justify-content-between pt-0">
            <div>
              <button type="button" class="btn btn-light" @click="cerrar">Cancelar</button>
              <button v-if="esEdicion" type="button" class="btn btn-outline-danger ms-2" @click="darDeBaja">
                <i class="bi bi-trash"></i> Dar de baja
              </button>
            </div>
            <button type="submit" class="btn btn-primary px-4">
              {{ esEdicion ? 'Guardar' : 'Crear' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { Modal } from 'bootstrap'

const props = defineProps({
  usuario: { type: Object, default: null }
})

const emit = defineEmits(['guardado', 'dado-de-baja'])

const modalRef = ref(null)
let modalInstance = null
const errorMensaje = ref('')
const mostrarPassword = ref(false)

const form = ref({
  nombre: '',
  apellido: '',
  dpi: '',
  correo_electronico: '',
  password: ''
})

const esEdicion = computed(() => !!props.usuario)

watch(() => props.usuario, (nuevoUsuario) => {
  errorMensaje.value = ''
  mostrarPassword.value = false
  if (nuevoUsuario) {
    form.value = {
      nombre: nuevoUsuario.nombre,
      apellido: nuevoUsuario.apellido,
      dpi: nuevoUsuario.dpi,
      correo_electronico: nuevoUsuario.correo_electronico,
      password: ''
    }
  } else {
    form.value = { nombre: '', apellido: '', dpi: '', correo_electronico: '', password: '' }
  }
}, { immediate: true })

function abrir() {
  errorMensaje.value = ''
  if (!props.usuario) {
    form.value = { nombre: '', apellido: '', dpi: '', correo_electronico: '', password: '' }
  }
  if (!modalInstance) {
    modalInstance = new Modal(modalRef.value)
  }
  modalInstance.show()
}

function cerrar() {
  modalInstance?.hide()
}

function guardar() {
  errorMensaje.value = ''

  if (form.value.dpi.length !== 13) {
    errorMensaje.value = `El DPI debe tener 13 dígitos (faltan ${13 - form.value.dpi.length}).`
    return
  }

  const datos = { ...form.value }
  if (esEdicion.value && !datos.password) {
    delete datos.password
  }
  emit('guardado', { id: props.usuario?.id, datos })
  cerrar()
}

function darDeBaja() {
  emit('dado-de-baja', props.usuario.id)
  cerrar()
}

defineExpose({ abrir })
</script>