# 📚 Sistema de Gestión de Biblioteca CIF

Sistema web moderno y completo para la gestión de bibliotecas, desarrollado con PHP, MySQL y arquitectura MVC.

![Version](https://img.shields.io/badge/version-2.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1)

## ✨ Características

- 🔐 **Sistema de autenticación** completo con roles de administrador
- 📖 **Gestión de libros** - CRUD completo con búsqueda avanzada
- 👥 **Gestión de usuarios** - Registro y control de usuarios
- 📊 **Dashboard interactivo** con estadísticas en tiempo real
- 🎨 **Diseño moderno** con animaciones y efectos glassmorphism
- 📱 **Responsive** - Funciona en dispositivos móviles y escritorio
- 🔍 **Búsqueda avanzada** por título, autor, ISBN, nombre o email
- 📈 **Tarjetas de estadísticas** interactivas y animadas

## 🚀 Instalación

### Requisitos

- PHP 8.0 o superior
- MySQL 8.0 o superior
- Servidor web (Apache, Nginx) o Laragon/XAMPP
- Extensiones PHP: PDO, pdo_mysql

### Paso 1: Clonar el repositorio
```bash
git clone https://github.com/rafarmk/biblioteca-cif.git
cd biblioteca-cif
```

### Paso 2: Configurar la base de datos

**Opción A: Línea de comandos**
```bash
mysql -u root -p < database/schema.sql
```

**Opción B: phpMyAdmin**

1. Accede a phpMyAdmin
2. La base de datos se creará automáticamente al importar
3. Importa el archivo `database/schema.sql`

### Paso 3: Configurar la conexión (si es necesario)

El archivo `config/Database.php` ya está configurado con valores por defecto:
```php
private $host = 'localhost';
private $db_name = 'bibloteca_cif';
private $username = 'root';
private $password = '';
```

Si necesitas cambiar las credenciales, edita `config/Database.php`.

### Paso 4: Ejecutar el proyecto

**Opción A: Con Laragon (Windows)**

1. Coloca el proyecto en `C:\laragon\www\biblioteca-cif`
2. Inicia Laragon (Apache y MySQL)
3. Accede a: `http://localhost/biblioteca-cif`

**Opción B: Con PHP Built-in Server**
```bash
php -S localhost:8080
```

Accede a: `http://localhost:8080`

**Opción C: Con XAMPP**

1. Coloca el proyecto en `C:\xampp\htdocs\biblioteca-cif`
2. Inicia Apache y MySQL desde XAMPP
3. Accede a: `http://localhost/biblioteca-cif`

## 🔑 Credenciales por Defecto

- **Email:** `admin@cif.edu.sv`
- **Contraseña:** `admin123`

⚠️ **IMPORTANTE:** Cambia estas credenciales en producción por seguridad.

## 📁 Estructura del Proyecto
```
biblioteca-cif/
├── config/
│   ├── Database.php              # Clase de conexión PDO
│   ├── conexion.php              # Conexión antigua (legacy)
│   └── conexion.example.php      # Template de configuración
├── controllers/
│   ├── AuthController.php        # Autenticación y sesiones
│   ├── HomeController.php        # Dashboard principal
│   ├── LibroController.php       # CRUD de libros
│   └── UsuarioController.php     # CRUD de usuarios
├── models/
│   ├── Libro.php                 # Modelo de libros
│   └── Usuario.php               # Modelo de usuarios
├── core/
│   └── models/
│       └── Administrador.php     # Modelo de administradores
├── views/
│   ├── auth/
│   │   └── login.php             # Vista de login
│   ├── layouts/
│   │   └── navbar.php            # Navbar moderno
│   ├── libros/
│   │   ├── index.php             # Listado de libros
│   │   ├── crear.php             # Crear libro
│   │   └── editar.php            # Editar libro
│   ├── usuarios/
│   │   ├── index.php             # Listado de usuarios
│   │   ├── crear.php             # Crear usuario
│   │   └── editar.php            # Editar usuario
│   ├── home.php                  # Dashboard
│   └── landing.php               # Página principal
├── database/
│   └── schema.sql                # Esquema completo de BD
├── index.php                     # Punto de entrada (Router)
├── .gitignore
└── README.md
```

## 🎨 Capturas de Pantalla

### Landing Page
Diseño moderno con gradientes y animaciones

### Dashboard
Tarjetas interactivas con estadísticas en tiempo real

### Catálogo de Libros
Sistema completo de gestión con búsqueda avanzada

### Gestión de Usuarios
CRUD completo con interfaz intuitiva

## 🔧 Uso del Sistema

### Gestión de Libros

1. Accede a **Libros** desde el navbar
2. Ver estadísticas: Total de libros, copias disponibles, categorías
3. **Buscar** por título, autor o ISBN
4. **Crear** nuevos libros con toda la información
5. **Editar** o **Eliminar** libros existentes

### Gestión de Usuarios

1. Accede a **Usuarios** desde el navbar
2. Ver estadísticas: Total de usuarios, nuevos hoy
3. **Buscar** por nombre o email
4. **Registrar** nuevos usuarios
5. **Editar** o **Eliminar** usuarios

### Dashboard

- Visualiza estadísticas generales del sistema
- Acceso rápido a todas las secciones
- Tabla de libros recientemente agregados

## 🛡️ Seguridad

- ✅ Contraseñas hasheadas con `password_hash()`
- ✅ Consultas preparadas (PDO) para prevenir SQL Injection
- ✅ Validación de sesiones en rutas protegidas
- ✅ Sanitización de entradas con `htmlspecialchars()`
- ✅ Archivo de configuración fuera del repositorio

## 🚧 Características Próximamente

- 📚 Sistema completo de préstamos
- 📧 Notificaciones por email
- 📊 Reportes y estadísticas avanzadas
- 🔔 Alertas de devoluciones pendientes
- 📱 App móvil

## 👨‍💻 Desarrollo

### Tecnologías Utilizadas

- **Backend:** PHP 8+ con PDO
- **Base de Datos:** MySQL 8+
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5
- **Iconos:** Font Awesome 6
- **Arquitectura:** MVC (Model-View-Controller)

### Contribuir

1. Fork el proyecto
2. Crea una rama: `git checkout -b feature/nueva-funcionalidad`
3. Commit: `git commit -m 'Agregar nueva funcionalidad'`
4. Push: `git push origin feature/nueva-funcionalidad`
5. Abre un Pull Request

## 📝 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 📧 Contacto

- **Desarrollador:** Rafael
- **GitHub:** [@rafarmk](https://github.com/rafarmk)
- **Proyecto:** [biblioteca-cif](https://github.com/rafarmk/biblioteca-cif)

## 🙏 Agradecimientos

Proyecto desarrollado para la **Biblioteca del CIF** (Centro de Investigación Forense) de El Salvador.

---

⭐ Si te gusta este proyecto, dale una estrella en GitHub!