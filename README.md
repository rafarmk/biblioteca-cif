#  Sistema de Gestión de Biblioteca CIF

Sistema web para la gestión de una biblioteca académica, desarrollado con PHP y MySQL usando arquitectura MVC.

##  Características

-  Gestión de libros (CRUD completo)
-  Gestión de usuarios (CRUD completo)
-  Sistema de préstamos
-  Control de disponibilidad de libros
-  Búsqueda y filtrado
-  Arquitectura MVC

##  Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache, Nginx) o PHP built-in server
- Extensiones PHP: mysqli, pdo_mysql

##  Instalación

### Paso 1: Clonar el repositorio
```bash

@"
#  Sistema de Gestión de Biblioteca CIF

Sistema web para la gestión de una biblioteca académica, desarrollado con PHP y MySQL usando arquitectura MVC.

##  Características

-  Gestión de libros (CRUD completo)
-  Gestión de usuarios (CRUD completo)
-  Sistema de préstamos
-  Control de disponibilidad de libros
-  Búsqueda y filtrado
-  Arquitectura MVC

##  Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache, Nginx) o PHP built-in server
- Extensiones PHP: mysqli, pdo_mysql

##  Instalación

### Paso 1: Clonar el repositorio
```bash
git clone https://github.com/rafarmk/biblioteca-cif.git
cd biblioteca-cif
```

### Paso 2: Configurar la base de datos

#### Opción A: Línea de comandos
```bash
mysql -u root -p < database/schema.sql
```

#### Opción B: phpMyAdmin

1. Accede a phpMyAdmin
2. Crea una nueva base de datos: `bibloteca_cif`
3. Importa el archivo `database/schema.sql`

### Paso 3: Configurar la conexión
```bash
# Copiar el archivo de ejemplo
copy config\conexion.example.php config\conexion.php

# Editar con tus credenciales
notepad config\conexion.php
```

Configura tus credenciales de MySQL:
```php
$host = 'localhost';
$usuario = 'root';
$contrasena = ''; // Tu contraseña de MySQL
$base = 'bibloteca_cif';
```

### Paso 4: Ejecutar el proyecto

#### Con Laragon (Windows)

1. Coloca el proyecto en `C:\laragon\www\biblioteca-cif`
2. Inicia Laragon
3. Accede a: `http://localhost/biblioteca-cif`

#### Con PHP built-in server
```bash
php -S localhost:8000
```

Accede a: `http://localhost:8000`

#### Con XAMPP

1. Coloca el proyecto en `C:\xampp\htdocs\biblioteca-cif`
2. Inicia Apache y MySQL desde el panel de XAMPP
3. Accede a: `http://localhost/biblioteca-cif`

##  Estructura del Proyecto
```
biblioteca-cif/
 config/
    conexion.php           # Configuración de BD (ignorado en Git)
    conexion.example.php   # Plantilla de configuración
 controladores/
    LibroController.php    # Controlador de libros
 modelos/
    Libro.php              # Modelo de libros
    Usuario.php            # Modelo de usuarios
 views/
    Libro_form.php         # Vistas de libros
 assets/
    css/                   # Estilos CSS
    js/                    # Scripts JavaScript
 database/
    schema.sql             # Esquema de la base de datos
 index.php                  # Punto de entrada
 .gitignore
 README.md
```

##  Uso del Sistema

### Gestión de Libros
```
http://localhost:8000/index.php?ruta=libros
```

### Gestión de Usuarios
```
http://localhost:8000/index.php?ruta=usuarios
```

### Gestión de Préstamos
```
http://localhost:8000/index.php?ruta=prestamos
```

##  Seguridad

- El archivo `config/conexion.php` está en `.gitignore` y NO debe subirse al repositorio
- Usa siempre `conexion.example.php` como plantilla
- Cambia las credenciales por defecto en producción

##  Desarrollo

### Agregar nuevas funcionalidades

1. Crea un modelo en `modelos/`
2. Crea un controlador en `controladores/`
3. Crea las vistas en `views/`
4. Actualiza `index.php` con las nuevas rutas

### Contribuir

1. Fork el proyecto
2. Crea una rama: `git checkout -b feature/nueva-funcionalidad`
3. Commit: `git commit -m 'Agregar nueva funcionalidad'`
4. Push: `git push origin feature/nueva-funcionalidad`
5. Abre un Pull Request

##  Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

##  Contacto

- Desarrollador: [Tu Nombre]
- Email: [tu-email@example.com]
- GitHub: [@rafarmk](https://github.com/rafarmk)

##  Agradecimientos

Proyecto desarrollado para la Biblioteca del CIF (Centro de Investigación Forense).
