\# 📚 Sistema de Biblioteca CIF - Gestión de Préstamos



Sistema de gestión de biblioteca escolar desarrollado en PHP puro con arquitectura MVC, que permite administrar libros, usuarios y préstamos.



!\[PHP](https://img.shields.io/badge/PHP-8.3-blue?style=flat-square\&logo=php)

!\[MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat-square\&logo=mysql)

!\[MVC](https://img.shields.io/badge/Architecture-MVC-green?style=flat-square)



---



\## 📸 Capturas de Pantalla



\### Página Principal

!\[Home](docs/screenshots/home.png)



\### Catálogo de Libros

!\[Libros](docs/screenshots/libros.png)



\### Gestión de Préstamos

!\[Prestamos](docs/screenshots/prestamos.png)



---



\## ✨ Características



\- ✅ \*\*Gestión de Libros\*\*: CRUD completo del catálogo

\- ✅ \*\*Gestión de Usuarios\*\*: Estudiantes, docentes y administrativos

\- ✅ \*\*Control de Préstamos\*\*: Registro y devolución

\- ✅ \*\*Búsqueda\*\*: Buscar libros por título, autor o categoría

\- ✅ \*\*Disponibilidad\*\*: Verificación en tiempo real

\- ✅ \*\*Historial\*\*: Registro completo de préstamos

\- ✅ \*\*Reportes\*\*: Estadísticas básicas



---



\## 🛠️ Tecnologías Utilizadas



\- \*\*Backend\*\*: PHP 8.3 Puro (sin frameworks)

\- \*\*Arquitectura\*\*: MVC (Model-View-Controller)

\- \*\*Base de Datos\*\*: MySQL 8.0 / MariaDB

\- \*\*Frontend\*\*: HTML5 + CSS3 + JavaScript

\- \*\*Server\*\*: Apache (vía Laragon/XAMPP/LAMP)



---



\## 📋 Requisitos Previos



Antes de instalar, asegúrate de tener:



\- ✅ PHP 8.0 o superior

\- ✅ MySQL 5.7+ o MariaDB 10.3+

\- ✅ Servidor Apache

\- ✅ Git



\*\*Entornos recomendados:\*\*

\- \*\*Windows\*\*: Laragon o XAMPP

\- \*\*Mac\*\*: MAMP o Laragon

\- \*\*Linux\*\*: LAMP Stack



\### Verificar Versiones



```bash

php -v          # Debe ser 8.0+

mysql --version # Debe ser 5.7+ o MariaDB 10.3+

```



---



\## 🚀 Instalación Paso a Paso



\### 1️⃣ Clonar el Repositorio



```bash

git clone https://github.com/rafarmk/biblioteca-cif.git

cd biblioteca-cif

```



\### 2️⃣ Configurar Base de Datos



\#### A. Crear la Base de Datos



```bash

mysql -u root -p

```



Dentro de MySQL:



```sql

CREATE DATABASE biblioteca\_cif CHARACTER SET utf8mb4 COLLATE utf8mb4\_unicode\_ci;

EXIT;

```



O desde la terminal directamente:



```bash

mysql -u root -p -e "CREATE DATABASE biblioteca\_cif CHARACTER SET utf8mb4 COLLATE utf8mb4\_unicode\_ci;"

```



\#### B. Importar las Tablas



```bash

mysql -u root -p biblioteca\_cif < database.sql

```



\*\*⚠️ Importante\*\*: Reemplaza `root` con tu usuario de MySQL si es diferente.



\#### C. Verificar que se Crearon las Tablas



```bash

mysql -u root -p biblioteca\_cif -e "SHOW TABLES;"

```



\*\*Deberías ver:\*\*

```

+---------------------------+

| Tables\_in\_biblioteca\_cif  |

+---------------------------+

| libros                    |

| prestamos                 |

| usuarios                  |

+---------------------------+

```



\### 3️⃣ Configurar Conexión a Base de Datos



Edita el archivo `config/conexion.php`:



```bash

\# Windows

notepad config/conexion.php



\# Linux/Mac

nano config/conexion.php

```



Verifica que los datos de conexión sean correctos:



```php

private $host = "localhost";

private $usuario = "root";        // Tu usuario MySQL

private $password = "";            // Tu contraseña MySQL

private $baseDatos = "biblioteca\_cif";

```



\*\*Guarda los cambios.\*\*



\### 4️⃣ Configurar el Servidor



\#### Opción A: Servidor PHP Built-in (Desarrollo)



```bash

php -S localhost:8000

```



\*\*Accede a:\*\* `http://localhost:8000`



\#### Opción B: Apache (Laragon/XAMPP)



1\. \*\*Copia el proyecto\*\* a la carpeta web:

&nbsp;  - Laragon: `C:\\laragon\\www\\biblioteca-cif`

&nbsp;  - XAMPP: `C:\\xampp\\htdocs\\biblioteca-cif`



2\. \*\*Inicia Apache\*\* desde Laragon/XAMPP



3\. \*\*Accede a:\*\* `http://localhost/biblioteca-cif`



\#### Opción C: Configurar Virtual Host (Avanzado)



\*\*Para Laragon:\*\*



Laragon detecta automáticamente. Accede a:

```

http://biblioteca-cif.test

```



\*\*Para Apache manual:\*\*



Edita `httpd-vhosts.conf`:



```apache

<VirtualHost \*:80>

&nbsp;   ServerName biblioteca-cif.local

&nbsp;   DocumentRoot "C:/ruta/a/biblioteca-cif"

&nbsp;   <Directory "C:/ruta/a/biblioteca-cif">

&nbsp;       Options Indexes FollowSymLinks

&nbsp;       AllowOverride All

&nbsp;       Require all granted

&nbsp;   </Directory>

</VirtualHost>

```



Agrega a `hosts` (como administrador):

```

127.0.0.1  biblioteca-cif.local

```



---



\## 🎯 Uso del Sistema



\### Gestionar Libros



\#### Listar Libros

```

http://localhost:8000/index.php?ruta=libros

```



\#### Agregar Nuevo Libro

1\. Click en "Agregar Nuevo Libro"

2\. Llena el formulario:

&nbsp;  - \*\*Título\*\*: Nombre del libro

&nbsp;  - \*\*Autor\*\*: Autor del libro

&nbsp;  - \*\*Categoría\*\*: Novela, Ciencia, Historia, etc.

&nbsp;  - \*\*Año\*\*: Año de publicación

3\. Click en "Guardar"



\#### Editar Libro

1\. En la lista, click en "✏️ Editar"

2\. Modifica los campos necesarios

3\. Click en "Actualizar"



\#### Eliminar Libro

1\. Click en "🗑️ Eliminar"

2\. Confirma la acción

3\. \*\*Nota\*\*: No se puede eliminar si tiene préstamos activos



---



\### Gestionar Usuarios



\#### Registrar Usuario

```

http://localhost:8000/index.php?ruta=usuarios/crear

```



Tipos de usuario:

\- 👨‍🎓 \*\*Estudiante\*\*

\- 👨‍🏫 \*\*Docente\*\*

\- 👔 \*\*Administrativo\*\*



---



\### Gestionar Préstamos



\#### Registrar Préstamo

1\. Selecciona un libro disponible

2\. Selecciona el usuario

3\. Define fecha de préstamo

4\. Click en "Registrar Préstamo"

5\. El libro se marca automáticamente como "No Disponible"



\#### Registrar Devolución

1\. En lista de préstamos, encuentra el préstamo activo

2\. Click en "Devolver"

3\. El libro se marca automáticamente como "Disponible"



---



\## 📂 Estructura del Proyecto



```

biblioteca-cif/

├── config/

│   ├── conexion.php                # Conexión a base de datos

│   └── config.php                  # Configuraciones generales

├── controllers/

│   ├── LibroController.php         # Lógica de libros

│   ├── UsuarioController.php       # Lógica de usuarios

│   └── PrestamoController.php      # Lógica de préstamos

├── models/

│   ├── Libro.php                   # Modelo de libros

│   ├── Usuario.php                 # Modelo de usuarios

│   └── Prestamo.php                # Modelo de préstamos

├── views/

│   ├── libros/

│   │   ├── index.php               # Lista de libros

│   │   ├── crear.php               # Formulario nuevo libro

│   │   └── editar.php              # Formulario editar libro

│   ├── usuarios/

│   │   ├── index.php               # Lista de usuarios

│   │   └── crear.php               # Formulario nuevo usuario

│   ├── prestamos/

│   │   ├── index.php               # Lista de préstamos

│   │   └── crear.php               # Formulario nuevo préstamo

│   └── home.php                    # Página principal

├── public/

│   ├── css/                        # Hojas de estilo

│   ├── js/                         # JavaScript

│   └── images/                     # Imágenes

├── core/

│   ├── Router.php                  # Enrutador

│   └── Database.php                # Gestor de BD

├── database.sql                    # Script de base de datos

├── index.php                       # Punto de entrada

├── .htaccess                       # Configuración Apache

└── README.md                       # Este archivo

```



---



\## 🗄️ Modelo de Base de Datos



\### Tabla: libros



| Campo | Tipo | Descripción |

|-------|------|-------------|

| id | INT | ID único (auto-increment) |

| titulo | VARCHAR(255) | Título del libro |

| autor | VARCHAR(255) | Autor del libro |

| categoria | VARCHAR(100) | Categoría |

| anio | INT | Año de publicación |

| disponible | BOOLEAN | TRUE = disponible, FALSE = prestado |

| fecha\_registro | TIMESTAMP | Fecha de registro |



\*\*Ejemplo de datos:\*\*

```sql

INSERT INTO libros (titulo, autor, categoria, anio, disponible) VALUES

('Cien Años de Soledad', 'Gabriel García Márquez', 'Novela', 1967, TRUE),

('El Principito', 'Antoine de Saint-Exupéry', 'Infantil', 1943, TRUE),

('1984', 'George Orwell', 'Ciencia Ficción', 1949, FALSE);

```



---



\### Tabla: usuarios



| Campo | Tipo | Descripción |

|-------|------|-------------|

| id | INT | ID único |

| nombre | VARCHAR(255) | Nombre completo |

| documento | VARCHAR(50) | Cédula/DNI (único) |

| tipo | ENUM | estudiante, docente, administrativo |

| fecha\_registro | TIMESTAMP | Fecha de registro |



\*\*Ejemplo de datos:\*\*

```sql

INSERT INTO usuarios (nombre, documento, tipo) VALUES

('Juan Pérez', '12345678', 'estudiante'),

('María González', '87654321', 'docente'),

('Carlos Rodríguez', '45678912', 'administrativo');

```



---



\### Tabla: prestamos



| Campo | Tipo | Descripción |

|-------|------|-------------|

| id | INT | ID único |

| libro\_id | INT | ID del libro (FK) |

| usuario\_id | INT | ID del usuario (FK) |

| fecha\_prestamo | DATE | Fecha del préstamo |

| fecha\_devolucion | DATE | Fecha de devolución (NULL si activo) |

| estado | ENUM | activo, devuelto |



\*\*Relaciones:\*\*

```

prestamos.libro\_id → libros.id

prestamos.usuario\_id → usuarios.id

```



---



\## 🔍 Consultas SQL Útiles



\### Ver libros disponibles

```sql

SELECT \* FROM libros WHERE disponible = TRUE;

```



\### Ver préstamos activos

```sql

SELECT 

&nbsp;   p.id,

&nbsp;   l.titulo AS libro,

&nbsp;   u.nombre AS usuario,

&nbsp;   p.fecha\_prestamo,

&nbsp;   DATEDIFF(CURDATE(), p.fecha\_prestamo) AS dias\_transcurridos

FROM prestamos p

INNER JOIN libros l ON p.libro\_id = l.id

INNER JOIN usuarios u ON p.usuario\_id = u.id

WHERE p.estado = 'activo';

```



\### Libros más prestados

```sql

SELECT 

&nbsp;   l.titulo,

&nbsp;   l.autor,

&nbsp;   COUNT(p.id) AS total\_prestamos

FROM libros l

INNER JOIN prestamos p ON l.id = p.libro\_id

GROUP BY l.id

ORDER BY total\_prestamos DESC

LIMIT 10;

```



---



\## 🔧 Arquitectura MVC Explicada



\### ¿Qué es MVC?



\*\*MVC\*\* divide el código en 3 capas:



```

┌──────────────┐

│    VISTA     │ ← Lo que el usuario ve (HTML)

│  (View)      │

└──────┬───────┘

&nbsp;      │

&nbsp;      ↓

┌──────────────┐

│ CONTROLADOR  │ ← Procesa peticiones y decide qué hacer

│ (Controller) │

└──────┬───────┘

&nbsp;      │

&nbsp;      ↓

┌──────────────┐

│   MODELO     │ ← Se comunica con la base de datos

│  (Model)     │

└──────────────┘

```



\### Ejemplo de Flujo:



\*\*Usuario quiere ver lista de libros:\*\*



1\. \*\*Usuario\*\* accede a: `index.php?ruta=libros`

2\. \*\*index.php\*\* (Enrutador) identifica: controlador=libros, accion=index

3\. \*\*LibroController->index()\*\* se ejecuta

4\. \*\*Controlador\*\* llama a: `Libro->obtenerTodos()`

5\. \*\*Modelo Libro\*\* ejecuta: `SELECT \* FROM libros`

6\. \*\*Modelo\*\* devuelve array de libros

7\. \*\*Controlador\*\* pasa datos a la vista

8\. \*\*Vista\*\* (libros/index.php) muestra tabla HTML

9\. \*\*Usuario\*\* ve la lista de libros en su navegador



---



\## ⚠️ Solución de Problemas



\### Error: "Access denied for user"



\*\*Causa:\*\* Credenciales incorrectas en `config/conexion.php`



\*\*Solución:\*\*

```php

private $usuario = "root";      // Verifica tu usuario

private $password = "";          // Verifica tu contraseña

```



---



\### Error: "Unknown database 'biblioteca\_cif'"



\*\*Causa:\*\* La base de datos no existe



\*\*Solución:\*\*

```bash

mysql -u root -p -e "CREATE DATABASE biblioteca\_cif;"

mysql -u root -p biblioteca\_cif < database.sql

```



---



\### Error: "Call to undefined function mysqli\_connect()"



\*\*Causa:\*\* Extensión mysqli no habilitada



\*\*Solución en php.ini:\*\*

```ini

extension=mysqli

```



Reinicia Apache después del cambio.



---



\### Error: "No such file or directory"



\*\*Causa:\*\* Ruta incorrecta



\*\*Solución:\*\* Verifica que estés en la carpeta correcta:

```bash

pwd  # o cd en Windows

```



---



\### Página en blanco



\*\*Causa:\*\* Error de PHP no mostrado



\*\*Solución:\*\* Activa errores temporalmente:

```php

// Al inicio de index.php

error\_reporting(E\_ALL);

ini\_set('display\_errors', 1);

```



---



\## 🔒 Seguridad



\### Implementado:



✅ \*\*Prepared Statements\*\*: Previene SQL Injection

✅ \*\*htmlspecialchars()\*\*: Previene XSS

✅ \*\*Validación de datos\*\*: En todos los formularios

✅ \*\*Transacciones\*\*: Para operaciones críticas



\### Por Implementar:



\- \[ ] Sistema de autenticación (login)

\- \[ ] Tokens CSRF en formularios

\- \[ ] Limitación de tasa (rate limiting)

\- \[ ] Logs de auditoría

\- \[ ] Encriptación de datos sensibles



---



\## 📊 Mejoras Futuras



\### Funcionalidades Sugeridas:



1\. \*\*Sistema de Login\*\*

&nbsp;  - Autenticación de usuarios

&nbsp;  - Roles y permisos



2\. \*\*Búsqueda Avanzada\*\*

&nbsp;  - Filtros múltiples

&nbsp;  - Autocompletado



3\. \*\*Sistema de Multas\*\*

&nbsp;  - Calcular retrasos

&nbsp;  - Cobro automático



4\. \*\*Notificaciones\*\*

&nbsp;  - Email de recordatorio

&nbsp;  - SMS de vencimiento



5\. \*\*Reportes Avanzados\*\*

&nbsp;  - Gráficas estadísticas

&nbsp;  - Exportar a PDF/Excel



6\. \*\*Reservas\*\*

&nbsp;  - Reservar libros prestados

&nbsp;  - Cola de espera



---



\## 🧪 Testing



\### Datos de Prueba



Para insertar datos de prueba:



```sql

-- Libros de prueba

INSERT INTO libros (titulo, autor, categoria, anio) VALUES

('Don Quijote de la Mancha', 'Miguel de Cervantes', 'Clásico', 1605),

('Harry Potter', 'J.K. Rowling', 'Fantasía', 1997),

('El Código Da Vinci', 'Dan Brown', 'Misterio', 2003);



-- Usuarios de prueba

INSERT INTO usuarios (nombre, documento, tipo) VALUES

('Ana Martínez', '11111111', 'estudiante'),

('Pedro López', '22222222', 'docente');

```



---



\## 🤝 Contribuir



1\. Fork el proyecto

2\. Crea una rama: `git checkout -b feature/nueva-funcionalidad`

3\. Commit: `git commit -m 'Agregar nueva funcionalidad'`

4\. Push: `git push origin feature/nueva-funcionalidad`

5\. Abre un Pull Request



---



\## 📝 Changelog



\### Version 1.0.0 (2025-10-12)

\- ✅ Sistema CRUD de libros

\- ✅ Sistema CRUD de usuarios

\- ✅ Gestión de préstamos y devoluciones

\- ✅ Verificación de disponibilidad

\- ✅ Arquitectura MVC implementada

\- ✅ Prepared statements para seguridad



---



\## 👨‍💻 Autor



\*\*José Raphael Ernesto Pérez Hernández\*\*



\- GitHub: \[@rafarmk](https://github.com/rafarmk)

\- Email: rafstefp@gmail.com



---



\## 📄 Licencia



Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.



---



\## 🙏 Agradecimientos



\- Comunidad PHP

\- MySQL/MariaDB Team

\- Estudiantes del CIF



---



\## 📞 Soporte



Si tienes problemas o preguntas:



1\. Revisa la sección de \[Solución de Problemas](#️-solución-de-problemas)

2\. Busca en \[Issues](https://github.com/rafarmk/biblioteca-cif/issues)

3\. Crea un nuevo Issue si no encuentras solución



---



\## 📚 Recursos Educativos



Este proyecto es ideal para aprender:

\- ✅ PHP básico e intermedio

\- ✅ Arquitectura MVC

\- ✅ MySQL y consultas SQL

\- ✅ Prepared Statements

\- ✅ Seguridad web básica

\- ✅ Diseño de bases de datos



---



\*\*⭐ Si te gustó el proyecto, dale una estrella en GitHub!\*\*



\*\*📖 Sistema desarrollado con fines educativos para la gestión de bibliotecas escolares.\*\*

