# Aplicación PHP con Arquitectura Hexagonal

Esta es una aplicación de ejemplo que implementa la arquitectura hexagonal (también conocida como Ports and Adapters) para gestionar usuarios.

## Estructura del Proyecto

```
hexagonal/
├── src/
│   ├── Domain/                 # Capa de Dominio
│   │   ├── Entity/            # Entidades del dominio
│   │   │   └── User.php
│   │   └── Port/              # Puertos (interfaces)
│   │       ├── UserRepositoryInterface.php
│   │       └── UserServiceInterface.php
│   ├── Application/           # Capa de Aplicación
│   │   └── UseCase/          # Casos de uso
│   │       └── UserService.php
│   ├── Infrastructure/        # Capa de Infraestructura
│   │   └── Adapter/          # Adaptadores
│   │       ├── Repository/   # Adaptadores de persistencia
│   │       │   └── InMemoryUserRepository.php
│   │       └── Web/          # Adaptadores web
│   │           └── UserController.php
│   └── Config/               # Configuración
│       ├── Autoloader.php
│       └── Container.php
└── public/                   # Punto de entrada web
    ├── index.php
    └── .htaccess
```

## Arquitectura Hexagonal

### Capas:

1. **Dominio (Domain)**: Contiene la lógica de negocio pura
   - **Entidades**: Objetos que representan conceptos del dominio
   - **Puertos**: Interfaces que definen contratos

2. **Aplicación (Application)**: Orquesta los casos de uso
   - **Casos de Uso**: Implementan la lógica de aplicación

3. **Infraestructura (Infrastructure)**: Implementa los detalles técnicos
   - **Adaptadores**: Implementaciones concretas de los puertos

## API Endpoints

### Crear Usuario
```http
POST /users
Content-Type: application/json

{
    "name": "Juan Pérez",
    "email": "juan@example.com"
}
```

### Obtener Usuario por ID
```http
GET /users/{id}
```

### Obtener Todos los Usuarios
```http
GET /users
```

### Actualizar Usuario
```http
PUT /users/{id}
Content-Type: application/json

{
    "name": "Juan Carlos Pérez",
    "email": "juancarlos@example.com"
}
```

### Eliminar Usuario
```http
DELETE /users/{id}
```

## Configuración

1. Asegúrate de que tu servidor web (Apache/Nginx) esté configurado para servir desde la carpeta `public/`
2. Habilita mod_rewrite en Apache para que funcione el enrutamiento
3. La aplicación usa un repositorio en memoria, por lo que los datos se pierden al reiniciar

## Ejemplos de Uso

### Crear un usuario:
```bash
curl -X POST http://localhost/hexagonal/public/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Ana García","email":"ana@example.com"}'
```

### Obtener todos los usuarios:
```bash
curl http://localhost/hexagonal/public/users
```

### Obtener un usuario específico:
```bash
curl http://localhost/hexagonal/public/users/1
```

## Ventajas de la Arquitectura Hexagonal

1. **Separación de responsabilidades**: Cada capa tiene una responsabilidad específica
2. **Testabilidad**: Fácil de testear al poder mockear las dependencias
3. **Flexibilidad**: Fácil cambiar implementaciones (ej: de memoria a base de datos)
4. **Independencia de frameworks**: El dominio no depende de tecnologías específicas
5. **Mantenibilidad**: Código más organizado y fácil de mantener
