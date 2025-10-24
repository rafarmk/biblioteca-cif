# Sistema Biblioteca CIF 📚

Sistema de gestión de biblioteca para instituciones policiales, desarrollado en PHP con arquitectura MVC.

## 📋 Características

- 🔐 Sistema de autenticación (Login/Logout)
- 👥 Gestión completa de usuarios (CRUD)
  - Policías (con carnet policial)
  - Administrativos (con carnet administrativo)
  - Estudiantes/Visitantes (con token temporal)
- 📖 Gestión completa de libros (CRUD)
- 🛡️ Rutas protegidas por sesión
- 📱 Diseño responsivo
- 🔍 Búsqueda y filtrado de registros

## 🛠️ Requisitos

- PHP 8.0 o superior
- MySQL 5.7 o superior (o MariaDB)
- Servidor web (Apache/Nginx) o Laragon
- Navegador web moderno

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone <URL-de-tu-repositorio>
cd biblioteca-cif
```

### 2. Configurar la base de datos

#### Opción A: Usando MySQL desde línea de comandos

```bash
# Acceder a MySQL
mysql -u root -p

# Importar el esquema completo (crea la BD y las tablas)
SOURCE database/schema.sql;

# Salir
EXIT;
```

#### Opción B: Usando phpMyAdmin

1. Accede a phpMyAdmin
2. Crea una nueva base de datos llamada `biblioteca_cif_usuarios`
3. Selecciona cotejamiento `utf8mb4_unicode_ci`
4. Importa el archivo `database/schema.sql`

### 3. Configurar la conexión a la base de datos

```bash
# Copiar el archivo de ejemplo
cp config/conexion.example.php config/conexion.php
```

Edita `config/conexion.php` y ajusta las credenciales según tu entorno:

```php
define('DB_HOST', 'localhost');      // Host de tu base de datos
define('DB_NAME', 'biblioteca_cif_usuarios'); // Nombre de tu base de datos
define('DB_USER', 'root');           // Tu usuario de MySQL
define('DB_PASS', '');               // Tu contraseña de MySQL
```

⚠️ **IMPORTANTE**: El archivo `config/conexion.php` está en `.gitignore` y NO debe subirse al repositorio.

### 4. Iniciar el servidor

#### Opción A: Con Laragon (Recomendado para Windows)

1. Abre Laragon
2. Coloca el proyecto en `C:\laragon\www\biblioteca-cif`
3. Inicia Apache y MySQL
4. Accede a `http://localhost/biblioteca-cif`

#### Opción B: Con PHP Built-in Server

```bash
php -S localhost:8080
```

Accede a `http://localhost:8080`

## 🔑 Credenciales por defecto

- **Usuario:** admin
- **Contraseña:** password

⚠️ **IMPORTANTE:** Cambia estas credenciales después del primer inicio de sesión por seguridad.

## 📁 Estructura del Proyecto

```
biblioteca-cif/
├── config/
│   ├── conexion.php           # Configuración de BD (no se sube a Git)
│   └── conexion.example.php   # Template de configuración
├── controllers/
│   ├── LoginController.php    # Controlador de autenticación
│   ├── LibroController.php    # Controlador de libros
│   └── UsuarioController.php  # Controlador de usuarios
├── models/
│   ├── Libro.php              # Modelo de libros
│   └── Usuario.php            # Modelo de usuarios
├── views/
│   ├── login.php              # Vista de login
│   ├── home.php               # Dashboard principal
│   ├── libros/                # Vistas de gestión de libros
│   │   ├── index.php
│   │   ├── crear.php
│   │   └── editar.php
│   └── usuarios/              # Vistas de gestión de usuarios
│       ├── index.php
│       ├── crear.php
│       └── editar.php
├── assets/
│   ├── css/                   # Estilos CSS
│   └── js/                    # Scripts JavaScript
├── database/
│   └── schema.sql             # Esquema de la base de datos
├── includes/
│   └── navbar.php             # Barra de navegación
├── index.php                  # Punto de entrada (Front Controller)
├── .gitignore
└── README.md
```

## 🔧 Uso del Sistema

### Gestión de Usuarios

1. Inicia sesión con las credenciales de administrador
2. Navega a la sección "Usuarios"
3. Puedes crear, editar, ver y eliminar usuarios
4. Los tipos de usuario son:
   - **Policía**: Requiere carnet policial (formato: POL-YYYY-XXX)
   - **Administrativo**: Requiere carnet administrativo (formato: PHXXXXX)
   - **Estudiante/Visitante**: Se genera un token temporal automáticamente

### Gestión de Libros

1. Navega a la sección "Libros"
2. Puedes crear, editar, ver y eliminar libros
3. Campos disponibles:
   - Título, Autor, ISBN
   - Editorial, Año de publicación
   - Categoría, Cantidad disponible
   - Ubicación física

## 🐛 Solución de Problemas

### Error: "PDOException: SQLSTATE[HY000] [1049] Unknown database"

- Verifica que hayas ejecutado el archivo `database/schema.sql`
- Confirma el nombre de la BD en `config/conexion.php`

### Error: "Access denied for user"

- Verifica tus credenciales en `config/conexion.php`
- Asegúrate de que el usuario MySQL tenga permisos

### Página en blanco o errores 500

- Verifica los logs de error de PHP
- Asegúrate de tener PHP 8.0 o superior
- Revisa que todos los archivos tengan permisos adecuados

### Error: "No se puede encontrar conexion.php"

- Asegúrate de haber copiado `conexion.example.php` a `conexion.php`
- Verifica que esté en la carpeta `config/`

### Caracteres extraños (ñ, á, é, etc.)

- Verifica que tu base de datos use `utf8mb4_unicode_ci`
- Ejecuta: `ALTER DATABASE biblioteca_cif_usuarios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto fue desarrollado para uso institucional policial.

## 📧 Contacto

Para reportar problemas o sugerencias, crea un issue en el repositorio.

---

**Desarrollado con ❤️ para la Biblioteca CIF**