# 📘 GUÍA DE USO DEL SISTEMA SIMAHG

## 🚀 Inicio Rápido

### Acceso al Sistema
1. **URL:** `http://localhost/simahg/`
2. **Login:** `http://localhost/simahg/login.php`
3. **Usuarios de prueba:**
   - **Admin:** usuario: `admin` / password: `admin123`
   - **Supervisor:** usuario: `supervisor` / password: `super123`
   - **Operador:** usuario: `operador` / password: `oper123`

---

## 👥 MÓDULO: GESTIÓN DE USUARIOS

### Crear Nuevo Usuario
1. Ir a **Usuarios** en el menú lateral
2. Clic en botón **"+ Nuevo Usuario"**
3. Completar formulario:
   - Nombre y apellidos
   - Email (debe ser único)
   - Usuario (debe ser único)
   - Contraseña
   - Seleccionar perfil (Administrador/Supervisor/Operador)
4. Clic en **"Guardar"**

### Editar Usuario
1. En la lista de usuarios, clic en botón **"Editar"**
2. Modificar datos necesarios
3. Clic en **"Guardar Cambios"**

### Desactivar Usuario
1. En la lista de usuarios, clic en botón **"Desactivar"**
2. Confirmar acción
3. El usuario quedará inactivo (no podrá iniciar sesión)

**Nota:** Solo los Administradores pueden gestionar usuarios.

---

## 📦 MÓDULO: GESTIÓN DE EPP

### Crear Categoría de EPP
1. Ir a **Gestión EPP** → pestaña **"Categorías"**
2. Clic en **"+ Nueva Categoría"**
3. Completar:
   - Nombre de categoría
   - Descripción
   - Vida útil en días
4. Clic en **"Guardar"**

### Crear Item de EPP
1. Ir a **Gestión EPP** → pestaña **"Items EPP"**
2. Clic en **"+ Nuevo Item"**
3. Completar formulario:
   - **Código:** único (ej: CASC-001)
   - **Nombre:** descripción del item
   - **Categoría:** seleccionar de lista
   - **Stock:** actual, mínimo, máximo
   - **Marca, modelo, talla** (opcional)
   - **Costo unitario**
   - **Proveedor**
   - **Estado:** activo, descontinuado, agotado
4. Clic en **"Guardar"**

### Alertas de Stock
- El sistema muestra alertas automáticas cuando:
  - Stock actual < Stock mínimo (⚠️ Alerta amarilla)
  - Stock actual = 0 (🔴 Alerta roja - Agotado)

**Permisos:** Administrador y Supervisor

---

## 📋 MÓDULO: SOLICITUDES EPP

### Crear Nueva Solicitud
1. Ir a **Solicitudes EPP**
2. Clic en **"+ Nueva Solicitud"**
3. Completar:
   - **Categoría EPP:** seleccionar de lista
   - **Cantidad:** número de items solicitados
   - **Justificación:** motivo de la solicitud
   - **Prioridad:** baja, media, alta, urgente
4. Clic en **"Enviar Solicitud"**

**Estados de solicitud:**
- 🟡 **Pendiente:** recién creada, esperando aprobación
- 🟢 **Aprobada:** autorizada por supervisor/admin
- 🔴 **Rechazada:** no autorizada
- 🔵 **Entregada:** EPP entregado al solicitante
- ⚫ **Cancelada:** anulada por el usuario

### Aprobar/Rechazar Solicitud (Admin/Supervisor)
1. En la lista de solicitudes, filtrar por **"Pendientes"**
2. Clic en botón **"Aprobar"** o **"Rechazar"**
3. (Opcional) Agregar observaciones
4. Confirmar acción

### Registrar Entrega
1. En solicitud **"Aprobada"**, clic en **"Registrar Entrega"**
2. Verificar datos:
   - Usuario solicitante
   - Cantidad a entregar
   - Item EPP disponible
3. Confirmar entrega
4. **El stock se descuenta automáticamente**

**Permisos:** Todos pueden crear solicitudes, solo Admin/Supervisor aprueban.

---

## 🏭 MÓDULO: GESTIÓN DE EQUIPOS

### Registrar Nuevo Equipo
1. Ir a **Equipos**
2. Clic en **"+ Nuevo Equipo"**
3. Completar:
   - **Código:** único (ej: TRAC-001)
   - **Nombre:** descripción del equipo
   - **Marca y modelo**
   - **Número de serie**
   - **Fecha de adquisición**
   - **Estado:** operativo, en_mantenimiento, reparacion, baja
   - **Ubicación:** lugar donde se encuentra
   - **Responsable:** usuario asignado
4. Clic en **"Guardar"**

### Cambiar Estado de Equipo
1. En la lista de equipos, clic en **"Editar"**
2. Cambiar estado según corresponda:
   - **Operativo:** funcionando normalmente
   - **En mantenimiento:** en proceso de mantenimiento
   - **Reparación:** requiere reparación
   - **Baja:** dado de baja permanentemente
3. (Opcional) Agregar observaciones
4. Clic en **"Guardar"**

**Permisos:** Administrador y Supervisor

---

## 🔧 MÓDULO: MANTENIMIENTOS

### Programar Mantenimiento Preventivo
1. Ir a **Mantenimientos**
2. Clic en **"+ Nuevo Mantenimiento"**
3. Completar:
   - **Equipo:** seleccionar de lista
   - **Tipo:** preventivo o correctivo
   - **Fecha programada**
   - **Descripción:** actividades a realizar
   - **Técnico responsable**
   - **Costo estimado** (opcional)
4. Clic en **"Programar"**

### Registrar Mantenimiento Realizado
1. En mantenimiento **"Programado"**, clic en **"Iniciar"**
2. Estado cambia a **"En proceso"**
3. Al finalizar, clic en **"Completar"**
4. Completar:
   - Fecha de realización
   - Observaciones
   - Costo real
   - Repuestos utilizados
5. Clic en **"Guardar"**

**Estados:**
- 🟡 **Programado:** pendiente de realizar
- 🔵 **En proceso:** se está ejecutando
- 🟢 **Completado:** finalizado exitosamente
- 🔴 **Cancelado:** no se realizó

**Permisos:** Administrador, Supervisor y Operador

---

## 📊 MÓDULO: REPORTES

### Generar Reporte de Solicitudes EPP
1. Ir a **Reportes**
2. Seleccionar **"Reporte de Solicitudes"**
3. Filtrar por:
   - Rango de fechas
   - Estado (pendiente, aprobada, entregada, etc.)
   - Usuario (opcional)
4. Clic en **"Generar Reporte"**
5. Ver resultados en pantalla

### Reporte de Stock de EPP
1. Ir a **Reportes**
2. Seleccionar **"Reporte de Stock"**
3. Ver:
   - Items con stock bajo (⚠️)
   - Items agotados (🔴)
   - Stock disponible por categoría
4. (Futuro) Exportar a Excel/PDF

### Reporte de Equipos
1. Ir a **Reportes**
2. Seleccionar **"Reporte de Equipos"**
3. Filtrar por:
   - Estado (operativo, mantenimiento, etc.)
   - Ubicación
   - Responsable
4. Ver listado detallado

**Permisos:** Administrador y Supervisor

---

## 🏠 MÓDULO: DASHBOARD

### Vista General
Al iniciar sesión, se muestra:
- **Indicadores principales:**
  - Solicitudes pendientes
  - Stock crítico (items con stock bajo)
  - Equipos en mantenimiento
  - Mantenimientos programados próximos

### Accesos Rápidos por Rol

**Administrador:**
- Gestión de usuarios
- Gestión de EPP
- Aprobar solicitudes
- Ver todos los reportes

**Supervisor:**
- Gestión de EPP
- Aprobar solicitudes
- Ver reportes
- Gestión de equipos

**Operador:**
- Crear solicitudes
- Ver mis solicitudes
- Registrar mantenimientos
- Ver equipos

---

## 🔐 SEGURIDAD Y MEJORES PRÁCTICAS

### Contraseñas
- Usar contraseñas seguras (mínimo 8 caracteres)
- Combinar letras, números y símbolos
- No compartir credenciales
- Cambiar contraseña periódicamente

### Sesiones
- Cerrar sesión al terminar
- No dejar sesión abierta en computadoras compartidas
- El sistema cierra sesión automáticamente tras inactividad

### Permisos
- Respetar los permisos asignados
- No intentar acceder a módulos restringidos
- Reportar intentos de acceso no autorizado

---

## ❓ PREGUNTAS FRECUENTES (FAQ)

### ¿Cómo recupero mi contraseña?
Actualmente debe contactar al Administrador del sistema para resetear su contraseña.

### ¿Por qué no puedo aprobar solicitudes?
Solo los usuarios con perfil Administrador o Supervisor pueden aprobar solicitudes. Verifique su perfil en el Dashboard.

### ¿Qué hago si un item EPP está agotado?
1. Verificar stock en **Gestión EPP**
2. Contactar al Supervisor/Administrador
3. Registrar reposición cuando llegue nuevo stock

### ¿Cómo cambio el estado de un equipo?
1. Ir a **Equipos**
2. Buscar el equipo
3. Clic en **"Editar"**
4. Cambiar estado y guardar

### ¿Puedo cancelar una solicitud ya aprobada?
No directamente. Debe contactar al Supervisor/Administrador para que gestione la cancelación.

### ¿Dónde veo el historial de mis solicitudes?
En **Solicitudes EPP**, el sistema muestra solo sus solicitudes si es Operador. Los Admin/Supervisor ven todas.

---

## 🆘 SOPORTE TÉCNICO

### Problemas Comunes

**Error al iniciar sesión:**
1. Verificar usuario y contraseña
2. Verificar que la cuenta esté activa
3. Contactar al Administrador

**No puedo crear una solicitud:**
1. Verificar que haya stock disponible
2. Verificar que la categoría exista
3. Completar todos los campos obligatorios

**Error al guardar datos:**
1. Verificar conexión a internet/red
2. Verificar que no haya campos duplicados (código, email, etc.)
3. Revisar que todos los campos requeridos estén completos

### Contacto
Para soporte técnico, contactar al Administrador del Sistema.

---

## 📱 ACCESO DESDE DISPOSITIVOS MÓVILES

El sistema es **responsive** y se adapta a:
- 📱 Smartphones
- 📱 Tablets
- 💻 Laptops
- 🖥️ Computadoras de escritorio

**Navegadores compatibles:**
- ✅ Chrome (recomendado)
- ✅ Firefox
- ✅ Safari
- ✅ Edge

---

## ✅ CHECKLIST DEL USUARIO

### Al iniciar turno:
- [ ] Iniciar sesión en el sistema
- [ ] Revisar solicitudes pendientes (si aplica)
- [ ] Verificar alertas de stock bajo (Admin/Supervisor)
- [ ] Revisar mantenimientos programados para hoy

### Al solicitar EPP:
- [ ] Verificar stock disponible
- [ ] Completar justificación detallada
- [ ] Seleccionar prioridad adecuada
- [ ] Confirmar envío de solicitud

### Al finalizar turno:
- [ ] Completar registros pendientes
- [ ] Cerrar sesión correctamente
- [ ] Reportar cualquier incidencia

---

**Sistema:** SIMAHG v2.0  
**Última actualización:** Enero 2024  
**Estado:** Operativo ✅

*Para más información técnica, consultar la documentación en la carpeta del proyecto.*
