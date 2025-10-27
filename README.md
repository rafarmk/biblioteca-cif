#  Sistema de Biblioteca CIF

Sistema moderno de gestión bibliotecaria desarrollado con PHP y MySQL, utilizando arquitectura MVC.

![Landing Page](screenshots/landing.png)

##  Características

###  Funcionalidades Principales
- **Gestión de Libros**: CRUD completo con información detallada (ISBN, autor, editorial, etc.)
- **Gestión de Usuarios**: Registro y control de estudiantes y personal
- **Sistema de Préstamos**: Control de préstamos y devoluciones con alertas de vencimiento
- **Dashboard Interactivo**: Estadísticas en tiempo real y actividad reciente
- **4 Temas Visuales**: Light, Dark, Original y Premium

###  Diseño Moderno
- Interfaz responsive y moderna
- Animaciones suaves y transiciones fluidas
- Diseño adaptable a todos los dispositivos
- Cambio de tema en tiempo real

###  Seguridad
- Sistema de autenticación robusto
- Sesiones seguras
- Validación de datos
- Protección contra inyección SQL

##  Tecnologías Utilizadas

- **Backend**: PHP 8.x
- **Base de Datos**: MySQL 8.x
- **Frontend**: 
  - HTML5, CSS3, JavaScript
  - Bootstrap 5
  - Font Awesome 6
- **Arquitectura**: MVC (Modelo-Vista-Controlador)

##  Instalación

### Requisitos Previos
- XAMPP/WAMP/Laragon con PHP 8.x
- MySQL 8.x
- Navegador web moderno

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/tuusuario/biblioteca-cif.git
cd biblioteca-cif
```

2. **Importar la base de datos**
```bash
# Abrir phpMyAdmin
# Crear base de datos: biblioteca_cif
# Importar el archivo: database/biblioteca_cif.sql
```

3. **Configurar la conexión**
```php
// Editar config/Database.php
private $host = "localhost";
private $db_name = "biblioteca_cif";
private $username = "root";
private $password = "";
```

4. **Iniciar el servidor**
```bash
# Con Laragon: Simplemente inicia Laragon
# Con XAMPP: Inicia Apache y MySQL
# Accede a: http://localhost/biblioteca-cif
```

##  Credenciales de Acceso

**Administrador:**
- Usuario: `admin@biblioteca.com`
- Contraseña: `admin123`

##  Capturas del Sistema

### Landing Page
![Landing](screenshots/landing.png)

### Dashboard
![Dashboard](screenshots/dashboard.png)

### Gestión de Libros
![Libros](screenshots/libros.png)

### Gestión de Usuarios
![Usuarios](screenshots/usuarios.png)

### Gestión de Préstamos
![Préstamos](screenshots/prestamos.png)

### Temas
| Light | Dark | Original | Premium |
|-------|------|----------|---------|
| ![Light](screenshots/tema-light.png) | ![Dark](screenshots/tema-dark.png) | ![Original](screenshots/tema-original.png) | ![Premium](screenshots/tema-premium.png) |

##  Estructura del Proyecto
```
biblioteca-cif/
 config/
    Database.php          # Configuración de BD
 controllers/
    LibroController.php
    UsuarioController.php
    PrestamoController.php
 models/
    Libro.php
    Usuario.php
    Prestamo.php
 views/
    layouts/
       navbar.php
       footer.php
    libros/
    usuarios/
    prestamos/
 assets/
    css/
    js/
    images/
 database/
    biblioteca_cif.sql
 index.php
```

##  Características Técnicas

### Arquitectura MVC
- **Modelos**: Gestión de datos y lógica de negocio
- **Vistas**: Presentación e interfaz de usuario
- **Controladores**: Coordinación entre modelos y vistas

### Base de Datos
- Diseño normalizado
- Relaciones optimizadas
- Integridad referencial
- Consultas optimizadas con PDO

### Frontend
- Diseño responsive (Mobile First)
- Componentes reutilizables
- Animaciones CSS3
- JavaScript vanilla para interactividad

##  Funcionalidades Detalladas

###  Gestión de Libros
- Agregar nuevos libros con información completa
- Editar información existente
- Eliminar libros (con validación de préstamos activos)
- Búsqueda y filtrado
- Control de inventario (cantidad total vs disponible)

###  Gestión de Usuarios
- Registro de estudiantes y personal
- Información de contacto completa
- Historial de préstamos por usuario
- Estados: Activo/Inactivo

###  Sistema de Préstamos
- Registro de préstamos con fecha estimada de devolución
- Control de devoluciones
- Alertas de préstamos vencidos
- Historial completo de transacciones
- Estados: Activo/Devuelto/Atrasado

###  Dashboard
- Estadísticas en tiempo real:
  - Total de libros
  - Usuarios registrados
  - Préstamos activos
  - Préstamos atrasados
  - Libros devueltos
- Actividad reciente
- Gráficos visuales

##  Temas Disponibles

1. **Modo Claro**: Diseño limpio y profesional
2. **Modo Oscuro**: Perfecto para trabajo nocturno
3. **Modo Original**: Colores clásicos de biblioteca
4. **Modo Premium**: Diseño moderno con efectos brillantes

##  Contribuciones

Las contribuciones son bienvenidas. Para cambios importantes:

1. Fork el proyecto
2. Crea una rama (`git checkout -b feature/mejora`)
3. Commit tus cambios (`git commit -m 'Agregar mejora'`)
4. Push a la rama (`git push origin feature/mejora`)
5. Abre un Pull Request

##  Licencia

Este proyecto está bajo la Licencia MIT.

##  Autor

**Tu Nombre**
- GitHub: [@tuusuario](https://github.com/tuusuario)
- Email: tuemail@ejemplo.com

##  Agradecimientos

- Font Awesome por los iconos
- Bootstrap por el framework CSS
- Google Fonts por la tipografía Poppins

---

 Si te gustó este proyecto, dale una estrella en GitHub!
