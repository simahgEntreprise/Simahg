# 🚀 ACTUALIZACIÓN MASIVA - PLAN DE ACCIÓN

## 📋 SITUACIÓN ACTUAL

✅ **Módulos Actualizados (Con nuevo diseño y control de roles):**
- `solicitudes_epp.php` - ✅ COMPLETO
- `reportes.php` - ✅ COMPLETO  
- `dashboard.php` - ✅ COMPLETO

❌ **Módulos Pendientes (Versión antigua):**
- `equipos.php` - ⚠️ REQUIERE ACTUALIZACIÓN
- `mantenimientos.php` - ⚠️ REQUIERE ACTUALIZACIÓN
- `epp_gestion.php` - ⚠️ REQUIERE ACTUALIZACIÓN
- `usuarios.php` - 🔄 ACTUALIZACIÓN INICIADA

---

## 🎯 SOLUCIÓN RÁPIDA

He creado un **archivo de configuración común** (`includes/config_common.php`) que contiene:

1. ✅ Funciones de control de roles (esAdmin, esSupervisor, esOperador, puedeGestionar)
2. ✅ Función para renderizar el navbar unificado
3. ✅ Estilos CSS comunes
4. ✅ Conexión a base de datos
5. ✅ Funciones útiles (sanitizar, formatearFecha, mostrarAlerta)

---

## 📝 PASOS PARA ACTUALIZAR CADA MÓDULO

### Para cada archivo (`equipos.php`, `mantenimientos.php`, `epp_gestion.php`):

#### 1. **Reemplazar el inicio del archivo:**

**ANTES:**
```php
<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
// Configuración BD...
```

**DESPUÉS:**
```php
<?php
session_start();
require_once 'includes/config_common.php';

// VERIFICAR PERMISO (si aplica)
if (!puedeGestionar()) {
    $_SESSION['error'] = 'No tienes permisos...';
    header('Location: dashboard.php');
    exit();
}

$pdo = getDBConnection();
```

#### 2. **Reemplazar el navbar:**

**ANTES:**
```html
<nav class="navbar">
    <div class="container">
        <a href="dashboard.php">SIMAHG</a>
        ...
    </div>
</nav>
```

**DESPUÉS:**
```php
<?php renderNavbar('nombre_pagina'); ?>
```

Donde `nombre_pagina` puede ser:
- `'equipos'` para equipos.php
- `'mantenimientos'` para mantenimientos.php
- `'epp_gestion'` para epp_gestion.php
- `'usuarios'` para usuarios.php

#### 3. **Agregar estilos comunes:**

**En el `<head>`:**
```php
<?php renderEstilosComunes(); ?>
```

#### 4. **Usar funciones comunes:**

```php
// En lugar de:
$userRole = $_SESSION['perfil_nombre'];
if ($userRole == 'Administrador') { ... }

// Usar:
if (esAdmin()) { ... }
if (esSupervisor()) { ... }
if (puedeGestionar()) { ... }
```

---

## 🔥 SOLUCIÓN AUTOMÁTICA (RECOMENDADO)

En lugar de actualizar manualmente cada archivo, puedo crear **versiones completamente nuevas** de cada módulo con todo incluido.

### ¿Qué necesitas decidir?

**Opción A - Actualización Manual:**  
Te guío paso a paso para actualizar cada archivo (más lento pero tienes control total)

**Opción B - Actualización Automática:**  
Creo versiones nuevas de cada módulo con:
- ✅ Navbar unificado
- ✅ Control de roles completo
- ✅ Diseño moderno
- ✅ Validaciones
- ✅ Funcionalidad CRUD completa

---

## 📊 TIEMPO ESTIMADO

- **Manual:** 2-3 horas por módulo = 8-12 horas total
- **Automática:** 30-45 minutos para los 4 módulos

---

## 🎯 MI RECOMENDACIÓN

**Opción B** - Te creo versiones completamente nuevas de:

1. **equipos.php** - Gestión de equipos con CRUD completo
2. **mantenimientos.php** - Registro y seguimiento de mantenimientos
3. **epp_gestion.php** - Inventario y catálogo de EPPs
4. **usuarios.php** - Administración de usuarios (ya iniciado)

Cada uno tendrá:
- ✅ Mismo diseño que solicitudes y reportes
- ✅ Control de roles robusto
- ✅ Navbar consistente
- ✅ Botón de cerrar sesión
- ✅ Validaciones
- ✅ CRUD funcional
- ✅ URLs limpias

---

## 🚀 ¿QUÉ PREFIERES?

**Dime:**
1. ¿Quieres que actualice automáticamente todos los módulos?
2. ¿O prefieres hacerlo manual paso a paso?
3. ¿Hay algún módulo en particular que quieras priorizar?

---

**Nota:** El archivo `includes/config_common.php` ya está creado y listo para usar. Solo necesitamos actualizar los 4 módulos restantes.

