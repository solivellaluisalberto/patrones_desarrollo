# Aplicación PHP con Patrón MVC

Esta es una aplicación de ejemplo que implementa el patrón **Modelo-Vista-Controlador (MVC)** en PHP puro, sin frameworks externos. La aplicación gestiona un catálogo de productos con operaciones CRUD completas.

## 🏗️ Estructura del Proyecto

```
mvc/
├── app/
│   ├── controllers/           # 🎮 Controladores
│   │   ├── Controller.php     # Controlador base
│   │   ├── HomeController.php # Controlador de inicio
│   │   └── ProductController.php # Controlador de productos
│   ├── models/               # 📊 Modelos
│   │   ├── Database.php      # Simulador de base de datos
│   │   ├── Model.php         # Modelo base
│   │   └── Product.php       # Modelo de producto
│   └── views/                # 👁️ Vistas
│       ├── layout.php        # Layout principal
│       ├── home/
│       │   └── index.php     # Página de inicio
│       └── products/         # Vistas de productos
│           ├── index.php     # Lista de productos
│           ├── create.php    # Crear producto
│           ├── show.php      # Ver producto
│           └── edit.php      # Editar producto
├── config/                   # ⚙️ Configuración
│   ├── autoload.php         # Autoloader de clases
│   ├── Router.php           # Sistema de enrutamiento
│   └── routes.php           # Definición de rutas
├── public/                   # 🌐 Punto de entrada público
│   ├── index.php            # Archivo principal
│   └── .htaccess            # Configuración Apache
├── assets/                   # 🎨 Recursos estáticos
│   ├── css/
│   │   └── style.css        # Estilos personalizados
│   └── js/
│       └── app.js           # JavaScript de la aplicación
└── storage/                  # 💾 Almacenamiento (futuro)
```

## 🎯 Patrón MVC Implementado

### 📊 **Modelo (Model)**
- **Responsabilidad**: Maneja la lógica de datos y reglas de negocio
- **Archivos**: `Database.php`, `Model.php`, `Product.php`
- **Funciones**:
  - Operaciones CRUD (Create, Read, Update, Delete)
  - Validaciones de datos
  - Lógica de negocio específica
  - Simulación de base de datos en memoria

### 👁️ **Vista (View)**
- **Responsabilidad**: Presenta la información al usuario
- **Archivos**: Todos los archivos en `app/views/`
- **Características**:
  - Templates HTML con PHP embebido
  - Layout reutilizable
  - Separación completa de la lógica de negocio
  - Interfaz responsive con Bootstrap

### 🎮 **Controlador (Controller)**
- **Responsabilidad**: Coordina entre Modelo y Vista
- **Archivos**: `Controller.php`, `HomeController.php`, `ProductController.php`
- **Funciones**:
  - Procesa peticiones HTTP
  - Valida datos de entrada
  - Coordina con los modelos
  - Selecciona y renderiza vistas

## 🚀 Funcionalidades

### ✅ Gestión de Productos
- **Crear productos** con validación completa
- **Listar productos** con información detallada
- **Ver detalles** de productos individuales
- **Editar productos** existentes
- **Eliminar productos** con confirmación
- **Validaciones** de datos en tiempo real

### 🎨 Interfaz de Usuario
- **Diseño responsive** con Bootstrap 5
- **Iconos** con Font Awesome
- **Notificaciones** de éxito y error
- **Modales** de confirmación
- **Formularios** con validación visual

### 🔧 Características Técnicas
- **Enrutamiento** personalizado con parámetros
- **Autoloader** automático de clases
- **Sesiones** para mensajes flash
- **Validación** tanto en cliente como servidor
- **Manejo de errores** robusto

## 🌐 Rutas Disponibles

| Método | Ruta | Controlador | Descripción |
|--------|------|-------------|-------------|
| GET | `/` | `Home@index` | Página de inicio |
| GET | `/products` | `Product@index` | Lista de productos |
| GET | `/products/create` | `Product@create` | Formulario crear producto |
| POST | `/products/store` | `Product@store` | Guardar nuevo producto |
| GET | `/products/show/{id}` | `Product@show` | Ver producto específico |
| GET | `/products/edit/{id}` | `Product@edit` | Formulario editar producto |
| PUT | `/products/update/{id}` | `Product@update` | Actualizar producto |
| DELETE | `/products/delete/{id}` | `Product@delete` | Eliminar producto |
| GET | `/api/products` | `Product@api` | API JSON de productos |

## 🛠️ Configuración e Instalación

### Requisitos
- PHP 7.4 o superior
- Servidor web (Apache/Nginx) con mod_rewrite
- Navegador web moderno

### Instalación
1. **Clonar o descargar** el proyecto en tu servidor web
2. **Configurar el servidor** para servir desde la carpeta `public/`
3. **Habilitar mod_rewrite** en Apache
4. **Acceder** a la aplicación desde el navegador

### URL de Acceso
```
http://localhost/LEARN/PATRONES/mvc/public/
```

## 📝 Ejemplos de Uso

### Crear un Producto
1. Ir a "Nuevo Producto" en el menú
2. Llenar el formulario con:
   - Nombre: "Laptop Gaming"
   - Descripción: "Laptop para gaming de alta gama"
   - Precio: 1299.99
   - Categoría: "Electrónicos"
   - Stock: 15
3. Hacer clic en "Crear Producto"

### Validaciones Implementadas
- **Nombre**: Requerido, mínimo 3 caracteres, máximo 100
- **Descripción**: Requerida, mínimo 10 caracteres, máximo 500
- **Precio**: Requerido, numérico, no negativo
- **Categoría**: Requerida, mínimo 3 caracteres, máximo 50
- **Stock**: Requerido, numérico, no negativo

## 🎓 Conceptos Demostrados

### Separación de Responsabilidades
- **Modelo**: Solo maneja datos y lógica de negocio
- **Vista**: Solo presenta información
- **Controlador**: Solo coordina y procesa peticiones

### Principios SOLID
- **Single Responsibility**: Cada clase tiene una responsabilidad específica
- **Open/Closed**: Fácil de extender sin modificar código existente
- **Dependency Inversion**: Los controladores dependen de abstracciones

### Patrones de Diseño
- **MVC**: Patrón arquitectónico principal
- **Singleton**: Para la conexión de base de datos
- **Template Method**: En el controlador base
- **Front Controller**: Punto de entrada único

## 🔧 Extensibilidad

### Agregar Nuevos Modelos
```php
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description'];
}
```

### Agregar Nuevos Controladores
```php
class CategoryController extends Controller
{
    public function index()
    {
        $categories = (new Category())->all();
        $this->view('categories.index', compact('categories'));
    }
}
```

### Agregar Nuevas Rutas
```php
$router->get('/categories', 'Category@index');
$router->post('/categories/store', 'Category@store');
```

## 🚀 Ventajas del Patrón MVC

1. **Mantenibilidad**: Código organizado y fácil de mantener
2. **Escalabilidad**: Fácil agregar nuevas funcionalidades
3. **Testabilidad**: Cada componente se puede testear independientemente
4. **Reutilización**: Componentes reutilizables
5. **Separación de Concerns**: Cada capa tiene su responsabilidad específica
6. **Trabajo en Equipo**: Diferentes desarrolladores pueden trabajar en diferentes capas

## 📚 Recursos Adicionales

- [Documentación del Patrón MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.1/)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [PHP Documentation](https://www.php.net/docs.php)

---

**Nota**: Esta aplicación usa almacenamiento en memoria para simplicidad. En una aplicación real, implementarías una conexión real a base de datos (MySQL, PostgreSQL, etc.).
