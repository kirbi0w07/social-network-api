# ⚙️ Social Network — Backend API

API REST desarrollada con **Laravel** para una aplicación de red social.

El backend proporciona autenticación, gestión de usuarios, perfiles, publicaciones, contenido multimedia, comentarios, reacciones, solicitudes de amistad y notificaciones.

Además, utiliza **Laravel Reverb** para comunicación en tiempo real entre usuarios.

---

## 🚀 Tecnologías

* **Laravel 13**
* **PHP**
* **PostgreSQL**
* **Laravel Sanctum**
* **Laravel Reverb**
* **Laravel Notifications**
* **Laravel Broadcasting**
* **Eloquent ORM**
* **REST API**

---

## ✨ Funcionalidades

### 🔐 Autenticación

* Registro de usuarios
* Inicio de sesión
* Cierre de sesión
* Autenticación mediante Laravel Sanctum
* Protección de endpoints
* Identificación del usuario autenticado

---

### 👤 Usuarios y perfiles

* Consulta de usuarios
* Búsqueda por nombre
* Búsqueda por apellido
* Búsqueda por nombre completo
* Búsqueda por username
* Gestión de perfiles
* Fotografías de perfil
* Fotografía de portada

---

### 📝 Publicaciones

* Creación de publicaciones
* Publicaciones con contenido multimedia
* Imágenes
* Videos
* Validación de archivos
* Asociación de publicaciones con usuarios

---

### 💬 Interacciones

* Comentarios
* Reacciones
* Gestión de relaciones entre usuarios

---

### 🤝 Sistema de amistades

El backend implementa un sistema de solicitudes de amistad con diferentes estados:

```text
add
  │
  ▼
pending
  │
  ├──────────────► rejected
  │
  ▼
accepted
```

Permite:

* Enviar solicitudes
* Consultar estado de una relación
* Aceptar solicitudes
* Rechazar solicitudes
* Evitar solicitudes duplicadas
* Evitar enviarse una solicitud a sí mismo

El backend determina el estado de la relación entre dos usuarios y lo devuelve al frontend para que la interfaz pueda mostrar la acción correspondiente.

---

## 🔔 Sistema de notificaciones

Se utiliza el sistema de **Notifications de Laravel** para almacenar las notificaciones en la base de datos.

Por ejemplo, cuando un usuario recibe una solicitud de amistad:

```text
Usuario A
   │
   │ solicita amistad
   ▼
FriendController
   │
   ├── Crea Friend
   │
   ├── Crea Notification
   │
   └── Dispatch Event
          │
          ▼
   FriendRequestCreated
```

Las notificaciones pueden consultarse posteriormente desde la API.

---

## ⚡ Comunicación en tiempo real

El proyecto utiliza **Laravel Reverb** y Broadcasting para emitir eventos en tiempo real.

Por ejemplo:

```text
FriendRequestCreated
FriendRequestAccepted
```

Los eventos son enviados a un canal privado asociado al usuario receptor:

```text
notifications.{userId}
```

### Ejemplo

Cuando el usuario `2` recibe una solicitud:

```text
notifications.2
```

El frontend permanece escuchando ese canal mediante Laravel Echo.

Cuando Laravel emite:

```text
friend.request.created
```

el frontend recibe el evento inmediatamente.

---

## 🔄 Flujo de un evento en tiempo real

```text
                 FRONTEND
                    │
                    │ POST /friend
                    ▼
              Laravel API
                    │
                    ├── Crear Friend
                    │
                    ├── Crear Notification
                    │
                    └── Dispatch Event
                           │
                           ▼
                  FriendRequestCreated
                           │
                           ▼
                    Laravel Reverb
                           │
                           ▼
                 notifications.{id}
                           │
                           ▼
                 Laravel Echo / Vue
                           │
                           ▼
                Notificación visual
```

Este mecanismo permite agregar posteriormente otros eventos en tiempo real, como:

* Mensajes
* Mensaje recibido
* Usuario escribiendo
* Solicitud aceptada
* Nuevas interacciones
* Actualizaciones de estado

---

## 🧱 Arquitectura

El backend utiliza una estructura basada en las responsabilidades principales de Laravel:

```text
app/
├── Events/
│   ├── FriendRequestCreated.php
│   └── FriendRequestAccepted.php
│
├── Notifications/
│   ├── FriendRequestNotification.php
│   └── FriendRequestAcceptNotification.php
│
├── Models/
│   ├── User.php
│   ├── Friend.php
│   └── ...
│
├── Http/
│   └── Controllers/
│
└── ...
```

### Events

Los eventos representan acciones que pueden ser transmitidas en tiempo real.

### Notifications

Las notificaciones representan información persistente asociada al usuario y almacenada en la base de datos.

### Controllers

Los controladores reciben las peticiones del frontend, validan los datos y ejecutan las operaciones correspondientes.

### Models

Los modelos Eloquent representan las entidades y relaciones utilizadas por la aplicación.

---

## 🗄️ Base de datos

El proyecto utiliza **PostgreSQL**.

Entre las entidades principales se encuentran:

```text
User
 │
 ├── Profile
 │
 ├── Posts
 │
 ├── Comments
 │
 ├── Notifications
 │
 └── Friendships
```

La información relacionada con las solicitudes de amistad se gestiona mediante una relación entre usuarios utilizando los campos:

```text
sender_id
receiver_id
status
```

---

## ⚙️ Instalación

Clonar el proyecto:

```bash
git clone https://github.com/TU-USUARIO/social-network-api.git

cd social-network-api
```

Instalar dependencias:

```bash
composer install
```

Copiar el archivo de configuración:

```bash
cp .env.example .env
```

Generar la clave de aplicación:

```bash
php artisan key:generate
```

Configurar la conexión a PostgreSQL en `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=social_network_api
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

Ejecutar migraciones:

```bash
php artisan migrate
```

---

## 🔥 Laravel Reverb

Configurar las variables de Reverb:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret

REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

Iniciar Reverb:

```bash
php artisan reverb:start
```

Iniciar el servidor Laravel:

```bash
php artisan serve
```

---

## 🔗 Frontend

Este backend trabaja junto con el frontend desarrollado en Vue 3.

**Frontend:**
`https://github.com/TU-USUARIO/social-network-frontend`

> Reemplaza el enlace anterior por la URL real de tu repositorio.

---

## 📌 Estado del proyecto

🚧 **En desarrollo**

Actualmente cuenta con:

* Autenticación
* Usuarios y perfiles
* Publicaciones
* Multimedia
* Comentarios
* Reacciones
* Solicitudes de amistad
* Notificaciones persistentes
* Eventos en tiempo real
* Laravel Reverb
* Laravel Sanctum

El siguiente paso importante del proyecto es implementar el sistema de **mensajería en tiempo real**.

---

## 👨‍💻 Autor

**José Luis Barbosa Cepeda**

Desarrollador Web Full Stack especializado en **Vue.js + Laravel**.

Proyecto desarrollado como parte de mi portafolio profesional para demostrar experiencia en desarrollo de APIs REST, Laravel, PostgreSQL, autenticación, relaciones entre modelos, notificaciones y comunicación en tiempo real.
