# 📊 ANÁLISIS DE BRECHAS (GAP ANALYSIS) - SIMAHG
## Sistema de Mantenimiento y Administración de Hidrogas

**Fecha de análisis:** 22 de noviembre de 2025  
**Versión:** Web 1.0  
**Estado:** En desarrollo

---

## 🎯 RESUMEN EJECUTIVO

Este documento identifica las brechas entre los requerimientos funcionales del proyecto SIMAHG (según documento oficial) y la implementación actual del sistema web.

---

## ✅ MÓDULOS IMPLEMENTADOS

### 1. ✓ Autenticación y Login (HU-01, HU-02)
- **Estado:** ✅ COMPLETO
- **Archivos:** `login.php`, `login_process.php`
- **Funcionalidades:**
  - Registro de usuarios
  - Inicio de sesión con validación
  - Gestión de sesiones
  - Mensajes de error por credenciales incorrectas

### 2. ✓ Gestión de Usuarios (HU-03, Sprints 3-4)
- **Estado:** ✅ COMPLETO
- **Archivos:** `usuarios.php`
- **Funcionalidades:**
  - Listar usuarios
  - Crear usuarios
  - Actualizar datos de usuarios
  - Activar/desactivar usuarios
  - Control por roles

### 3. ⚠️ Dashboard (Parcialmente implementado)
- **Estado:** ⚠️ PARCIAL
- **Archivos:** `dashboard.php`
- **Lo que falta:**
  - Estadísticas de EPPs disponibles por categoría
  - Alertas de mantenimientos próximos
  - Gráficos de tendencias
  - Indicadores de solicitudes pendientes

---

## ❌ MÓDULOS FALTANTES O INCOMPLETOS

### 1. ❌ Gestión de Equipos de Construcción (HU-03)
**Prioridad:** ALTA  
**Sprint:** 1  
**Requerimientos según documento:**
- Registro detallado de equipos con características técnicas
- Historial de mantenimientos
- Ubicación y estado actual
- Certificaciones y vencimientos

**Estado actual:** 
- Existe `equipos.php` pero necesita:
  - Campos adicionales (ubicación, certificaciones, vencimientos)
  - Historial de mantenimientos visible
  - Alertas de certificaciones próximas a vencer

---

### 2. ❌ Gestión de Mantenimientos (HU-04)
**Prioridad:** ALTA  
**Sprint:** 1  
**Requerimientos:**
- Registro de mantenimientos preventivos
- Registro de mantenimientos correctivos
- Alertas automáticas de mantenimientos próximos
- Historial completo por equipo
- Observaciones técnicas

**Estado actual:**
- Existe `mantenimientos.php` pero falta:
  - Sistema de alertas automáticas
  - Programación de mantenimientos preventivos
  - Diferenciación clara entre preventivo/correctivo
  - Notificaciones por email/dashboard

---

### 3. ❌ Gestión de EPPs (HU-05)
**Prioridad:** ALTA  
**Sprint:** 1, 5  
**Requerimientos completos:**
- **Catálogo de EPPs** con categorías (cascos, guantes, botas, etc.)
- **Control de inventario** en tiempo real
- **Solicitud de EPPs** por trabajadores
- **Flujo de aprobación** (Trabajador → Supervisor → Almacenero)
- **Acta de entrega digital** con firma/confirmación
- **Devolución y disposición final**
- **Alertas de stock bajo**

**Estado actual:**
- Existe `epp_gestion.php` básico
- **FALTA IMPLEMENTAR:**
  - ❌ Módulo de solicitudes de EPPs (HU-05)
  - ❌ Flujo de aprobación
  - ❌ Acta de entrega digital
  - ❌ Control de stock con alertas
  - ❌ Categorización completa

---

### 4. ❌ Sistema de Roles y Permisos (Alcance del proyecto)
**Prioridad:** ALTA  
**Requerimientos:**
- **Administrador:** Acceso total
- **Técnico:** Registrar mantenimientos, consultar equipos
- **Trabajador:** Solicitar EPPs, ver sus solicitudes
- **Supervisor:** Aprobar solicitudes, ver reportes

**Estado actual:**
- Existe tabla `perfiles` en BD
- **FALTA:**
  - Control de acceso granular por módulo
  - Restricción de funcionalidades según rol
  - Middleware de autorización

---

### 5. ❌ Generación de Reportes (HU-06)
**Prioridad:** MEDIA  
**Sprint:** 1, 4  
**Requerimientos:**
- Dashboard con KPIs
- Reportes personalizados
- Exportación a PDF/Excel
- Reportes de:
  - Estado de mantenimientos
  - Uso de EPPs por trabajador
  - Niveles de stock
  - Equipos próximos a mantenimiento

**Estado actual:**
- Existe `reportes.php` básico
- **FALTA:**
  - Exportación a PDF/Excel
  - Reportes específicos por módulo
  - Filtros avanzados
  - Gráficos estadísticos

---

### 6. ❌ Recuperación de Contraseña (Sprint 2)
**Prioridad:** MEDIA  
**Sprint:** 2  
**Requerimientos:**
- Formulario de recuperación
- Envío de email con token
- Cambio de contraseña seguro

**Estado actual:**
- ❌ NO IMPLEMENTADO

---

### 7. ❌ Sistema de Notificaciones y Alertas
**Prioridad:** ALTA  
**Requerimientos:**
- Alertas de mantenimientos próximos
- Alertas de certificaciones vencidas
- Notificaciones de solicitudes pendientes
- Stock bajo de EPPs

**Estado actual:**
- ❌ NO IMPLEMENTADO

---

## 📋 PRIORIZACIÓN DE DESARROLLO

### FASE 1 - CRÍTICO (Semana 1-2)
1. **Completar módulo de Solicitudes de EPPs** (HU-05)
   - Formulario de solicitud
   - Flujo de aprobación
   - Notificaciones
2. **Sistema de Roles y Permisos**
   - Middleware de autorización
   - Control de acceso por rol
3. **Mejorar Dashboard**
   - KPIs de EPPs
   - Alertas visibles

### FASE 2 - IMPORTANTE (Semana 3)
4. **Sistema de Alertas Automáticas**
   - Mantenimientos próximos
   - Stock bajo
5. **Completar Gestión de Equipos**
   - Certificaciones
   - Ubicación
   - Historial visible
6. **Mejorar Mantenimientos**
   - Preventivo vs Correctivo
   - Programación automática

### FASE 3 - COMPLEMENTARIO (Semana 4)
7. **Reportes avanzados**
   - Exportación PDF/Excel
   - Filtros personalizados
8. **Recuperación de contraseña**
9. **Acta de entrega digital**

---

## 📊 MÉTRICAS DE CUMPLIMIENTO

| Módulo | Requerido | Implementado | % Cumplimiento |
|--------|-----------|--------------|----------------|
| Login y Registro | ✓ | ✓ | 100% |
| Gestión Usuarios | ✓ | ✓ | 100% |
| Dashboard | ✓ | ⚠️ | 40% |
| Gestión Equipos | ✓ | ⚠️ | 50% |
| Mantenimientos | ✓ | ⚠️ | 40% |
| **Solicitudes EPPs** | ✓ | ❌ | 0% |
| **Roles y Permisos** | ✓ | ⚠️ | 30% |
| **Reportes** | ✓ | ⚠️ | 30% |
| **Alertas** | ✓ | ❌ | 0% |
| Recuperación pwd | ✓ | ❌ | 0% |

**Cumplimiento General: 39%**

---

## 🎯 OBJETIVO

**Alcanzar 90%+ de cumplimiento funcional en la versión web antes de iniciar desarrollo móvil.**

---

## 📝 NOTAS TÉCNICAS

### Tecnologías actuales:
- PHP puro (sin framework)
- MySQL (puerto 3307)
- Bootstrap 3
- jQuery
- Font Awesome

### Recomendaciones:
1. Crear archivo `config.php` centralizado para BD
2. Implementar clase `Auth.php` para manejo de permisos
3. Crear clase `Notificaciones.php` para alertas
4. Usar AJAX para operaciones CRUD sin recargar página
5. Implementar librería FPDF o mPDF para exportación PDF

---

**Próximos pasos:**
1. Implementar módulo de Solicitudes de EPPs completo
2. Crear sistema de roles y permisos
3. Mejorar dashboard con KPIs
4. Implementar alertas automáticas
