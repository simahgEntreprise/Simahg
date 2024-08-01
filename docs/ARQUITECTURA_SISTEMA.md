# 🏗️ Arquitectura del Sistema SIMAHG

## 📊 Diagrama de Arquitectura

```
┌─────────────────────────────────────────────────────────────────┐
│                    SISTEMA SIMAHG - FRONTEND                    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      login.php (Entrada)                        │
│  • Validación de credenciales                                   │
│  • Inicio de sesión                                             │
│  • Asignación de rol                                            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                   includes/config_common.php                    │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ 🔐 CONTROL DE ROLES                                       │ │
│  │  • esAdmin()                                              │ │
│  │  • esSupervisor()                                         │ │
│  │  • esOperador()                                           │ │
│  │  • puedeGestionar()                                       │ │
│  ├───────────────────────────────────────────────────────────┤ │
│  │ 🎨 RENDERIZADO                                            │ │
│  │  • renderNavbar($pagina) - Navbar dinámico               │ │
│  │  • renderEstilosComunes() - CSS moderno                  │ │
│  ├───────────────────────────────────────────────────────────┤ │
│  │ 🗄️ BASE DE DATOS                                          │ │
│  │  • getDBConnection() - PDO MySQL                         │ │
│  ├───────────────────────────────────────────────────────────┤ │
│  │ 🛠️ UTILIDADES                                             │ │
│  │  • sanitizar()                                            │ │
│  │  • formatearFecha()                                       │ │
│  │  • mostrarAlerta()                                        │ │
│  └───────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        MÓDULOS DEL SISTEMA                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐  │
│  │  dashboard.php │  │solicitudes_epp │  │  reportes.php  │  │
│  │                │  │     .php       │  │                │  │
│  │ 👤 Todos       │  │ 👤 Todos       │  │ 👨‍💼 Admin+Sup │  │
│  └────────────────┘  └────────────────┘  └────────────────┘  │
│                                                                 │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐  │
│  │  equipos.php   │  │mantenimientos  │  │ epp_gestion    │  │
│  │                │  │     .php       │  │     .php       │  │
│  │ 👨‍💼 Admin+Sup  │  │ 👨‍💼 Admin+Sup  │  │ 👨‍💼 Admin+Sup │  │
│  └────────────────┘  └────────────────┘  └────────────────┘  │
│                                                                 │
│  ┌────────────────┐                                            │
│  │ usuarios.php   │                                            │
│  │                │                                            │
│  │ 🔧 Solo Admin  │                                            │
│  └────────────────┘                                            │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BASE DE DATOS - MySQL                        │
├─────────────────────────────────────────────────────────────────┤
│  • usuarios                    • solicitudes_epp                │
│  • perfiles                    • equipos                        │
│  • categorias_epp              • mantenimientos                 │
│  • epp_items                   • categorias_equipos             │
│  • epp_entregas                • tipos_mantenimiento            │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Flujo de Navegación

### 1️⃣ Usuario Operador
```
Login → Dashboard → Solicitudes EPP (solo ver propias)
```

### 2️⃣ Usuario Supervisor
```
Login → Dashboard → [Solicitudes EPP | Equipos | Mantenimientos | 
                     Inventario EPP | Reportes]
```

### 3️⃣ Usuario Administrador
```
Login → Dashboard → [Solicitudes EPP | Equipos | Mantenimientos | 
                     Inventario EPP | Reportes | Usuarios]
```

---

## 🎯 Estructura de Archivos

```
/htdocs/simahg/
│
├── 🔐 AUTENTICACIÓN
│   ├── login.php
│   └── logout.php
│
├── 📦 CONFIGURACIÓN COMÚN
│   └── includes/
│       └── config_common.php
│
├── 🏠 MÓDULOS PRINCIPALES
│   ├── dashboard.php
│   ├── solicitudes_epp.php
│   ├── reportes.php
│   ├── equipos.php
│   ├── mantenimientos.php
│   ├── epp_gestion.php
│   └── usuarios.php
│
├── 🎨 RECURSOS
│   ├── bower_components/
│   │   ├── bootstrap/
│   │   ├── font-awesome/
│   │   ├── jquery/
│   │   └── datatables/
│   ├── js/
│   └── images/
│
├── 🗄️ BASE DE DATOS
│   └── database/
│       └── simahg_db.sql
│
└── 📚 DOCUMENTACIÓN
    ├── ALINEACION_COMPLETA.md
    ├── ARQUITECTURA_SISTEMA.md
    ├── PLAN_ACTUALIZACION.md
    └── RUTAS_UNIFICADAS.md
```

---

## 🔑 Roles y Permisos

### Tabla de Accesos

| Funcionalidad | Administrador | Supervisor | Operador |
|--------------|---------------|------------|----------|
| **Dashboard** | ✅ Todas las estadísticas | ✅ Todas las estadísticas | ✅ Solo propias |
| **Solicitudes EPP - Ver** | ✅ Todas | ✅ Todas | ✅ Solo propias |
| **Solicitudes EPP - Crear** | ✅ | ✅ | ✅ |
| **Solicitudes EPP - Aprobar** | ✅ | ✅ | ❌ |
| **Equipos - Ver** | ✅ | ✅ | ❌ |
| **Equipos - CRUD** | ✅ | ✅ | ❌ |
| **Mantenimientos - Ver** | ✅ | ✅ | ❌ |
| **Mantenimientos - CRUD** | ✅ | ✅ | ❌ |
| **Inventario EPP - Ver** | ✅ | ✅ | ❌ |
| **Inventario EPP - CRUD** | ✅ | ✅ | ❌ |
| **Reportes - Ver** | ✅ | ✅ | ❌ |
| **Reportes - Exportar** | ✅ | ✅ | ❌ |
| **Usuarios - Ver** | ✅ | ❌ | ❌ |
| **Usuarios - CRUD** | ✅ | ❌ | ❌ |

---

## 🛡️ Capas de Seguridad

### Nivel 1: Sesión
```php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}
```

### Nivel 2: Archivo Común
```php
require_once 'includes/config_common.php';
// Verifica automáticamente la sesión
```

### Nivel 3: Control de Roles
```php
if (!puedeGestionar()) {
    $_SESSION['error'] = 'No tienes permisos...';
    header('Location: dashboard.php');
    exit();
}
```

### Nivel 4: Navbar Dinámico
```php
// Solo muestra opciones según el rol del usuario
renderNavbar('pagina_actual');
```

### Nivel 5: Validación en BD
```php
// Filtrar datos por usuario si es operador
if (esOperador()) {
    $sql .= " WHERE id_usuario = ?";
    $params[] = $_SESSION['user_id'];
}
```

---

## 📈 Ventajas de esta Arquitectura

### ✅ Centralización
- Una sola fuente de verdad para roles
- Estilos y navbar reutilizables
- Fácil actualización masiva

### ✅ Seguridad
- Validación en múltiples capas
- Control de acceso granular
- Mensajes de error claros

### ✅ Escalabilidad
- Agregar módulos es simple
- Modificar el navbar afecta a todos los módulos
- Nuevos roles se integran fácilmente

### ✅ Mantenibilidad
- Código limpio y organizado
- Sin duplicación de lógica
- Fácil debugging

### ✅ UX/UI
- Experiencia consistente
- Diseño moderno
- Navegación intuitiva

---

## 🚀 Flujo de Datos

```
1. Usuario → Login → Validación
                        ↓
2. Sesión iniciada (perfil asignado)
                        ↓
3. Acceso a módulo → Carga config_common.php
                        ↓
4. Verificación de rol → Renderiza navbar dinámico
                        ↓
5. Consulta a BD → Filtros según rol
                        ↓
6. Muestra datos según permisos
```

---

## 🎨 Componentes Visuales

### Navbar
- Gradiente morado (667eea → 764ba2)
- Enlaces blancos
- Dropdown de usuario
- Opciones dinámicas según rol

### Cards
- Border-radius: 15px
- Box-shadow suave
- Gradientes modernos

### Botones
- Gradientes personalizados
- Iconos FontAwesome
- Efectos hover

### Tablas
- DataTables integrado
- Responsive design
- Acciones por fila

---

## 📊 Estadísticas del Sistema

### Antes de la Alineación
- 7 archivos con navbar diferente
- 3 archivos sin control de roles
- 5 archivos con estilos duplicados
- 0 archivos usando configuración común

### Después de la Alineación
- ✅ 1 navbar centralizado
- ✅ 7 archivos con control de roles
- ✅ 1 archivo de estilos común
- ✅ 7 archivos usando `config_common.php`

---

## 🎯 Próximos Pasos Recomendados

1. ✅ **Sistema alineado y funcionando**
2. 🔜 Implementar logs de auditoría
3. 🔜 Agregar notificaciones push
4. 🔜 Dashboard con gráficos en tiempo real
5. 🔜 Exportación de reportes a PDF/Excel
6. 🔜 Sistema de backups automáticos

---

**Sistema:** SIMAHG v2.0  
**Fecha:** 2025  
**Estado:** ✅ PRODUCCIÓN  
**Arquitectura:** ✅ CENTRALIZADA Y ESCALABLE
