# 🧪 GUÍA RÁPIDA DE PRUEBA - SOLICITUDES DE EPPs

## ✅ Pre-requisitos
- ✅ XAMPP corriendo (Apache + MySQL en puerto 3307)
- ✅ Base de datos `simahg_db` activa
- ✅ Usuario con sesión iniciada

---

## 🚀 PASOS PARA PROBAR

### 1️⃣ **Acceder al Dashboard**
```
1. Abrir navegador
2. Ir a: http://localhost/simahg/login.php
3. Iniciar sesión con cualquier usuario
```

### 2️⃣ **Ir a Solicitudes de EPPs**
```
1. En el dashboard verás una tarjeta morada destacada
2. Título: "⭐ Solicitudes de EPPs - NUEVO"
3. Clic en "Ir a Solicitudes de EPPs"
```

O directo:
```
http://localhost/simahg/solicitudes_epp_v2.php
```

### 3️⃣ **Crear una Solicitud (Como Trabajador)**
```
1. Clic en "Nueva Solicitud" (botón azul)
2. Seleccionar un EPP del dropdown
   - Ejemplo: "Casco de Seguridad Blanco"
3. Ingresar cantidad: 2
4. Escribir justificación: "Para obra en construcción del edificio B"
5. Clic en "Enviar Solicitud"
```

**Resultado esperado:**
- ✅ Mensaje verde: "Solicitud creada exitosamente"
- ✅ Aparece en la tabla con estado "PENDIENTE"
- ✅ Badge amarillo

### 4️⃣ **Aprobar Solicitud (Como Supervisor/Admin)**
```
1. Iniciar sesión con usuario Supervisor o Administrador
2. Ver lista completa de solicitudes
3. Buscar solicitud PENDIENTE
4. Clic en botón verde ✓
5. Confirmar aprobación
```

**Resultado esperado:**
- ✅ Mensaje verde: "Solicitud aprobada exitosamente"
- ✅ Estado cambia a "APROBADA"
- ✅ Badge verde
- ✅ Aparece botón "Entregar"

### 5️⃣ **Entregar EPP (Como Supervisor/Admin)**
```
1. Buscar solicitud APROBADA
2. Clic en "Entregar"
3. Confirmar entrega
```

**Resultado esperado:**
- ✅ Mensaje verde: "EPP entregado y descontado del inventario"
- ✅ Estado cambia a "ENTREGADA"
- ✅ Badge azul
- ✅ Stock del EPP se reduce automáticamente

### 6️⃣ **Rechazar Solicitud (Opcional)**
```
1. Solicitud PENDIENTE
2. Clic en botón rojo ✗
3. Escribir motivo: "No hay stock suficiente"
4. Clic en "Rechazar"
```

**Resultado esperado:**
- ✅ Mensaje: "Solicitud rechazada"
- ✅ Estado cambia a "RECHAZADA"
- ✅ Badge rojo
- ✅ Motivo guardado en el detalle

### 7️⃣ **Ver Detalles**
```
1. Clic en botón azul 👁️ en cualquier solicitud
2. Se abre modal con información completa:
   - ID, EPP, Código, Categoría
   - Cantidad, Estado
   - Solicitante, Fecha
   - Justificación
   - Aprobador (si aplica)
   - Motivo de rechazo (si aplica)
```

---

## 📊 VERIFICACIÓN DE DATOS

### Consulta SQL para ver solicitudes:
```sql
SELECT 
    s.id,
    u.nombre AS solicitante,
    e.nombre AS epp,
    s.cantidad,
    s.estado,
    s.fecha_solicitud
FROM solicitudes_epp s
INNER JOIN usuarios u ON s.id_usuario = u.id
INNER JOIN epp_items e ON s.id_epp = e.id
ORDER BY s.fecha_solicitud DESC;
```

### Verificar descuento de inventario:
```sql
SELECT 
    id,
    nombre,
    stock_actual,
    stock_minimo
FROM epp_items
WHERE id = 1; -- ID del EPP solicitado
```

---

## 🎯 CASOS DE PRUEBA

| # | Caso | Usuario | Resultado Esperado |
|---|------|---------|-------------------|
| 1 | Crear solicitud | Trabajador | ✅ Solicitud creada con estado PENDIENTE |
| 2 | Ver mis solicitudes | Trabajador | ✅ Solo ve sus propias solicitudes |
| 3 | Ver todas las solicitudes | Supervisor | ✅ Ve todas las solicitudes del sistema |
| 4 | Aprobar solicitud | Supervisor | ✅ Estado cambia a APROBADA |
| 5 | Rechazar solicitud | Admin | ✅ Estado cambia a RECHAZADA |
| 6 | Entregar EPP | Supervisor | ✅ Stock descontado, estado ENTREGADA |
| 7 | Ver detalle | Cualquiera | ✅ Modal con información completa |

---

## 🐛 TROUBLESHOOTING

### ❌ Error: "Call to undefined function Auth::"
**Solución:** Asegúrate de usar `solicitudes_epp_v2.php`, NO `solicitudes_epp.php`

### ❌ Error: "Table 'solicitudes_epp' doesn't exist"
**Solución:** Ejecutar el script SQL:
```bash
mysql --port=3307 -u root simahg_db < database/simahg_solicitudes_epp.sql
```

### ❌ No aparece el botón "Nueva Solicitud"
**Solución:** Iniciar sesión con un usuario que tenga rol asignado

### ❌ Error de conexión a BD
**Solución:** Verificar que MySQL esté corriendo en puerto 3307

---

## ✨ CARACTERÍSTICAS A OBSERVAR

1. **Estadísticas en tiempo real**
   - Contadores de pendientes, aprobadas, entregadas, rechazadas
   - Actualización automática al cambiar estados

2. **Control de roles**
   - Trabajadores: botón "Nueva Solicitud" visible
   - Supervisores/Admin: botones aprobar/rechazar/entregar

3. **Validaciones**
   - Cantidad mínima: 1
   - Justificación obligatoria
   - Motivo de rechazo requerido

4. **Interfaz responsive**
   - Funciona en móviles, tablets y desktop
   - Modales Bootstrap
   - Iconos Font Awesome

5. **Mensajes de confirmación**
   - JavaScript confirma acciones críticas
   - Alertas verdes de éxito
   - Alertas rojas de error

---

## 📸 CAPTURAS ESPERADAS

### Dashboard:
```
┌─────────────────────────────────────┐
│  ⭐ Solicitudes de EPPs - NUEVO    │
│  Módulo completo para solicitar,   │
│  aprobar y entregar EPPs           │
│  [ Ir a Solicitudes de EPPs ] ──→  │
└─────────────────────────────────────┘
```

### Módulo:
```
┌────────────────────────────────────────┐
│  🛡️ Solicitudes de EPPs               │
│  [Nueva Solicitud]                     │
├────────────────────────────────────────┤
│  Pendientes: 3 | Aprobadas: 5          │
│  Entregadas: 12 | Rechazadas: 1        │
├────────────────────────────────────────┤
│  # │ Fecha │ EPP │ Estado │ Acciones  │
│  1 │ 21/12 │ Casco │ ⚠️ PENDIENTE │ 👁️ ✅ ❌ │
│  2 │ 20/12 │ Guantes │ ✅ APROBADA │ 👁️ 📦 │
│  3 │ 19/12 │ Botas │ 📦 ENTREGADA │ 👁️ │
└────────────────────────────────────────┘
```

---

## ✅ CHECKLIST DE PRUEBA

- [ ] Login funciona correctamente
- [ ] Dashboard muestra tarjeta de Solicitudes EPP
- [ ] Botón redirige a solicitudes_epp_v2.php
- [ ] Formulario de nueva solicitud se abre
- [ ] Se puede crear solicitud como trabajador
- [ ] Solicitud aparece con estado PENDIENTE
- [ ] Supervisor puede ver todas las solicitudes
- [ ] Botón de aprobar funciona
- [ ] Botón de rechazar pide motivo
- [ ] Botón de entregar descuenta stock
- [ ] Modal de detalle muestra info completa
- [ ] Estadísticas se actualizan correctamente
- [ ] Navbar es coherente con el resto del sistema

---

**🎉 Si todos los pasos funcionan, el módulo está 100% operativo!**

---

**Última actualización:** <?php echo date('d/m/Y H:i:s'); ?>
