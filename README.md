# Sistema de Mantenimiento de Usuarios

CRUD de usuarios desarrollado con Laravel 13 (backend/API) y Vue 3 + Bootstrap (frontend), con autenticación por DPI y borrado lógico (Soft Delete).

## Tecnologías

- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** Vue 3 (Composition API), Bootstrap 5, Bootstrap Icons
- **Base de datos:** MySQL
- **Autenticación:** Laravel Sanctum (tokens)

## Características

- CRUD completo de usuarios (crear, editar, buscar, dar de baja)
- Borrado lógico (Soft Delete) — los usuarios "eliminados" no se borran físicamente
- Login mediante **DPI** (no email) con contraseña encriptada (bcrypt)
- Búsqueda en tiempo real por nombre, apellido o DPI
- Interfaz con modal para crear/editar usuarios
- Seeder con 15 usuarios de prueba

## Requisitos previos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- MySQL

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/CasiaProgrammer/login-usuarios.git
cd login-usuarios
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar el entorno

El archivo `.env` ya está incluido en este repositorio con la configuración usada durante el desarrollo. Verificá que los datos de conexión a MySQL coincidan con tu entorno:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=login_usuarios
DB_USERNAME=root
DB_PASSWORD=

Si necesitás generar una nueva clave de aplicación:

```bash
php artisan key:generate
```

### 4. Crear la base de datos

```bash
mysql -u root -e "CREATE DATABASE login_usuarios;"
```

### 5. Ejecutar migraciones y seeder

Esto crea las tablas y las llena con 15 usuarios de prueba (contraseña `12345678` para todos):

```bash
php artisan migrate:fresh --seed
```

### 6. Levantar los servidores

En una terminal:
```bash
php artisan serve
```

En otra terminal (en paralelo):
```bash
npm run dev
```

### 7. Abrir la aplicación

http://127.0.0.1:8000

## Uso

1. Iniciá sesión con el **DPI** de cualquier usuario del seeder y la contraseña `12345678`
2. Desde la pantalla principal podés:
   - Buscar usuarios por nombre, apellido o DPI
   - Crear un nuevo usuario (botón "Nuevo usuario")
   - Editar un usuario existente (ícono de lápiz en la tabla)
   - Dar de baja a un usuario (botón "Dar de baja" dentro del modal de edición)

## Notas técnicas

- El **DPI** debe tener exactamente 13 dígitos numéricos.
- El campo `password` nunca se expone en las respuestas de la API (oculto mediante `$hidden` en el modelo).
- Un usuario dado de baja (soft delete) no puede iniciar sesión, ya que Eloquent lo excluye automáticamente de las consultas.
- Para ver usuarios dados de baja directamente en la base de datos:
```bash
  mysql -u root -e "SELECT id, nombre, apellido, dpi, deleted_at FROM login_usuarios.usuarios;"
```

## Autor

Jonathan Casia