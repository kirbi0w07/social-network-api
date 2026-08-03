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

El backend implementa un sistema de solicitudes de amistad 


---

## 🔔 Sistema de notificaciones

Se utiliza el sistema de **Notifications de Laravel** para almacenar las notificaciones en la base de datos.
---

## ⚡ Comunicación en tiempo real

El proyecto utiliza **Laravel Reverb** y Broadcasting para emitir eventos en tiempo real.



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
---

## ⚙️ Instalación

Clonar el proyecto:

```bash
git clone https://github.com/kirbi0w07/social-network-api

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
`https://github.com/kirbi0w07/social-network`

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
