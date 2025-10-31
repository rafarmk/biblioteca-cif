# 🐳 Ejecutar Biblioteca CIF con Docker

## Requisitos previos
- Docker Desktop instalado y funcionando
- WSL 2 actualizado (para Windows)

## 🚀 Iniciar el proyecto

### 1. Construir y levantar los contenedores
```bash
docker-compose up -d --build
```

### 2. Acceder a la aplicación
- **Aplicación web**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

### 3. Credenciales por defecto
- **Usuario admin**: admin@biblioteca.com
- **Password**: password

## 📋 Comandos útiles

### Ver logs de los contenedores
```bash
docker-compose logs -f
```

### Detener los contenedores
```bash
docker-compose down
```

### Detener y eliminar todo (incluida la BD)
```bash
docker-compose down -v
```

### Reiniciar un servicio específico
```bash
docker-compose restart web
docker-compose restart db
```

### Acceder al contenedor de PHP
```bash
docker exec -it biblioteca-cif-web bash
```

### Acceder al contenedor de MySQL
```bash
docker exec -it biblioteca-cif-db mysql -u root -prootpassword biblioteca_cif
```

## 🔧 Solución de problemas

### El puerto 8080 está ocupado
Edita `docker-compose.yml` y cambia el puerto:
```yaml
ports:
  - "8090:80"  # Cambiar 8080 por otro puerto
```

### Problemas con la base de datos
```bash
# Eliminar volúmenes y recrear
docker-compose down -v
docker-compose up -d --build
```

### Ver estado de los contenedores
```bash
docker-compose ps
```