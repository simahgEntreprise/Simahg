# 🧪 GUÍA DE PRUEBAS - SIMAHG
## Sistema de Solicitudes de EPPs

**Fecha:** 22 de noviembre de 2025  
**Versión:** 1.0

---

## ✅ VERIFICACIÓN: TODO ESTÁ LISTO

### Base de Datos:
- ✅ Tabla `solicitudes_epp` creada
- ✅ Tabla `historial_entregas_epp` creada
- ✅ Tabla `notificaciones` creada
- ✅ 4 EPPs disponibles en inventario
- ✅ 4 usuarios con diferentes roles

### Archivos:
- ✅ `config.php` - Configuración centralizada
- ✅ `Auth.php` - Sistema de roles
- ✅ `solicitudes_epp.php` - Módulo de solicitudes
- ✅ `dashboard.php` - Dashboard mejorado (activado)
- ✅ `dashboard_backup_original.php` - Respaldo del dashboard anterior

---

## 🧪 PLAN DE PRUEBAS

### PRUEBA 1: Login y Dashboard
**Objetivo:** Verificar que el login funciona y el dashboard muestra KPIs

**Pasos:**
1. Abrir navegador: `http://localhost/simahg`
2. Login con usuario **admin** / contraseña: **123456**
3. ✅ Verificar que aparece el dashboard con:
   - Mensaje de bienvenida con rol
   - KPIs (equipos, mantenimientos, EPPs disponibles, stock bajo)
   - Alertas (si hay)
   - Últimas solicitudes
   - Accesos rápidos

**Resultado esperado:** Dashboard cargado con estadísticas

---

### PRUEBA 2: Crear Solicitud de EPP (Como TRABAJADOR)
**Objetivo:** Verificar que un trabajador puede solicitar EPPs

**Pasos:**
1. Cerrar sesión (logout)
2. Login con **prodriguez** / **123456** (rol: Usuario/Trabajador)
3. En el menú, click en **"Solicitudes EPP"**
4. Click en botón **"Nueva Solicitud"**
5. Llenar formulario:
   - Seleccionar un EPP del catálogo
   - Cantidad: 2
   - Justificación: "Necesito cascos nuevos para obra"
6. Click en **"Enviar Solicitud"**

**Resultado esperado:**
- ✅ Mensaje: "Solicitud creada exitosamente"
- ✅ La solicitud aparece en la tabla con estado **PENDIENTE**
- ✅ Estadísticas actualizadas (1 pendiente)

---

### PRUEBA 3: Aprobar Solicitud (Como SUPERVISOR)
**Objetivo:** Verificar flujo de aprobación

**Pasos:**
1. Cerrar sesión
2. Login con **jperez** / **123456** (rol: Supervisor)
3. Ir a **"Solicitudes EPP"**
4. ✅ Verificar que aparece alerta en dashboard: "Tienes X solicitudes pendientes"
5. En la tabla, localizar la solicitud pendiente
6. Click en botón verde **"Aprobar"** (✓)
7. Confirmar aprobación

**Resultado esperado:**
- ✅ Mensaje: "Solicitud aprobada exitosamente"
- ✅ Estado cambia a **APROBADA**
- ✅ Aparece botón azul **"Entregar"**

---

### PRUEBA 4: Entregar EPP (Como SUPERVISOR)
**Objetivo:** Verificar entrega y descuento de inventario

**Pasos:**
1. Con la misma sesión de supervisor
2. En la solicitud APROBADA, click en **"Entregar"**
3. Confirmar entrega

**Resultado esperado:**
- ✅ Mensaje: "EPP entregado y descontado del inventario"
- ✅ Estado cambia a **ENTREGADA**
- ✅ El stock del EPP disminuye en 2 unidades
- ✅ Se registra en historial de entregas

---

### PRUEBA 5: Ver Detalle de Solicitud
**Objetivo:** Verificar modal de detalles

**Pasos:**
1. En cualquier solicitud, click en botón **"👁️"** (ojo)
2. Ver información completa en modal

**Resultado esperado:**
- ✅ Modal muestra:
  - ID, EPP, código, categoría
  - Cantidad, estado
  - Solicitante
  - Fechas (solicitud, aprobación, entrega)
  - Justificación

---

### PRUEBA 6: Rechazar Solicitud
**Objetivo:** Verificar flujo de rechazo

**Pasos:**
1. Como trabajador (prodriguez), crear otra solicitud
2. Como supervisor (jperez), ir a solicitudes
3. Click en botón rojo **"✗"** (rechazar)
4. Escribir motivo: "Stock insuficiente"
5. Confirmar rechazo

**Resultado esperado:**
- ✅ Mensaje: "Solicitud rechazada"
- ✅ Estado cambia a **RECHAZADA**
- ✅ Se guarda el motivo del rechazo
- ✅ Trabajador puede ver el motivo al abrir detalles

---

### PRUEBA 7: Alertas en Dashboard
**Objetivo:** Verificar sistema de alertas

**Pasos:**
1. Login como supervisor o admin
2. Ver dashboard
3. Verificar alertas mostradas

**Alertas que deberían aparecer:**
- 🟡 **EPPs con stock bajo** (si stock_actual <= stock_minimo)
- 🔵 **Solicitudes pendientes** (si hay solicitudes sin aprobar)
- 🟠 **Mantenimientos próximos** (si hay mantenimientos en 7 días)

**Resultado esperado:**
- ✅ Alertas visibles con colores
- ✅ Click en "Ver" lleva al módulo correspondiente

---

### PRUEBA 8: Control de Permisos
**Objetivo:** Verificar que cada rol ve solo lo que debe

**Como TRABAJADOR (prodriguez):**
- ✅ VE: Dashboard, Solicitudes EPP (sus propias)
- ❌ NO VE: Equipos, Mantenimientos, Inventario EPP, Usuarios, Reportes

**Como SUPERVISOR (jperez):**
- ✅ VE: Dashboard, Equipos, Mantenimientos, Solicitudes EPP (todas), Inventario EPP, Reportes
- ❌ NO VE: Usuarios (solo admin)

**Como ADMINISTRADOR (admin):**
- ✅ VE TODO: Todos los módulos sin restricción

---

## 📊 VERIFICACIÓN DE BASE DE DATOS

### Ver solicitudes creadas:
```sql
USE simahg_db;
SELECT s.*, 
       u.nombre as solicitante, 
       e.nombre as epp_nombre,
       a.nombre as aprobador
FROM solicitudes_epp s
LEFT JOIN usuarios u ON s.id_usuario = u.id
LEFT JOIN epp_items e ON s.id_epp = e.id
LEFT JOIN usuarios a ON s.id_aprobador = a.id;
```

### Ver stock de EPPs:
```sql
SELECT id, codigo, nombre, stock_actual, stock_minimo, estado
FROM epp_items
WHERE estado = 'activo';
```

### Ver historial de entregas:
```sql
SELECT * FROM historial_entregas_epp;
```

---

## 🐛 RESOLUCIÓN DE PROBLEMAS

### Problema 1: "Error de conexión a BD"
**Solución:**
- Verificar que XAMPP está corriendo
- Verificar puerto 3307 en `config.php`
- Ejecutar: `sudo /Applications/XAMPP/xamppfiles/bin/mysql.server status`

### Problema 2: "No aparecen EPPs en el catálogo"
**Solución:**
```bash
mysql -u root -P 3307 --protocol=TCP simahg_db
SELECT * FROM epp_items WHERE estado = 'activo';
```
Si no hay datos, insertar EPPs de prueba.

### Problema 3: "No puedo aprobar solicitudes"
**Solución:**
- Verificar que estás logueado como Supervisor o Admin
- Verificar en consola del navegador si hay errores JS
- Verificar que la solicitud esté en estado PENDIENTE

### Problema 4: "El stock no se descuenta"
**Solución:**
- Verificar que la solicitud esté APROBADA antes de entregar
- Verificar que existe el EPP con el id correcto
- Revisar tabla `epp_items` para confirmar campo `stock_actual`

---

## ✅ CHECKLIST FINAL

Antes de dar por terminada la prueba, verificar:

- [ ] Login funciona con todos los usuarios
- [ ] Dashboard muestra KPIs correctos
- [ ] Trabajador puede crear solicitudes
- [ ] Supervisor puede aprobar solicitudes
- [ ] Supervisor puede rechazar solicitudes
- [ ] Entrega de EPP descuenta del stock
- [ ] Alertas se muestran correctamente
- [ ] Control de permisos funciona por rol
- [ ] Menú se adapta según rol del usuario
- [ ] Estados de solicitudes cambian correctamente
- [ ] Modal de detalles muestra información completa

---

## 📝 USUARIOS DE PRUEBA

| Usuario | Contraseña | Rol | ID Perfil |
|---------|-----------|-----|-----------|
| admin | 123456 | Administrador | 1 |
| jperez | 123456 | Supervisor | 2 |
| mgarcia | 123456 | Operador/Técnico | 3 |
| prodriguez | 123456 | Usuario/Trabajador | 4 |

**Nota:** Todas las contraseñas están encriptadas con SHA1

---

## 🎯 SIGUIENTES PASOS DESPUÉS DE PRUEBAS

Si todo funciona correctamente:

1. ✅ **Integrar Auth.php** con módulos existentes (equipos, mantenimientos, usuarios)
2. ✅ **Completar módulo de Mantenimientos** con alertas
3. ✅ **Mejorar Gestión de Equipos** con certificaciones
4. ✅ **Implementar Reportes** con exportación PDF/Excel
5. ✅ **Agregar Recuperación de contraseña**

---

## 📞 SOPORTE

Si encuentras errores:
1. Revisar logs de PHP: `/Applications/XAMPP/xamppfiles/logs/php_error_log`
2. Revisar logs de MySQL: `/Applications/XAMPP/xamppfiles/var/mysql/[hostname].err`
3. Consola del navegador (F12) para errores JS

---

**¡Buena suerte con las pruebas!** 🚀

*Última actualización: 22 de noviembre de 2025*
