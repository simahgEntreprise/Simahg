# 🔗 Guía para Arreglar las Relaciones de la Base de Datos

## 🚨 **Problema Detectado:**

En el Diseñador de phpMyAdmin (`http://localhost/phpmyadmin/index.php?route=/database/designer&db=simahg_db`) **NO se ven las relaciones** entre las tablas.

Esto significa que las **FOREIGN KEYS** (claves foráneas) no están creadas correctamente.

---

## ❓ **¿Por qué es importante tener relaciones?**

### **Ventajas de las FOREIGN KEYS:**
✅ **Integridad Referencial**: No puedes eliminar un perfil si hay usuarios asignados  
✅ **Cascada Automática**: Al actualizar un ID, se actualizan automáticamente las referencias  
✅ **Documentación Visual**: En el Diseñador puedes ver cómo se relacionan las tablas  
✅ **Prevención de Errores**: La BD rechaza datos inconsistentes  
✅ **Mejor Rendimiento**: MySQL optimiza las consultas con JOINs  

### **Sin FOREIGN KEYS:**
❌ Puedes eliminar un perfil y dejar usuarios "huérfanos"  
❌ Puedes insertar usuarios con `id_perfil` que no existe  
❌ Datos inconsistentes e inválidos  
❌ Más difícil de mantener  

---

## 🔧 **Solución: 3 Pasos Simples**

### **PASO 1: Verificar el Estado Actual** 🔍

1. Abre phpMyAdmin: `http://localhost/phpmyadmin/`
2. Selecciona la base de datos: `simahg_db`
3. Click en pestaña **"SQL"**
4. Copia y pega el contenido de: **`verificar_relaciones.sql`**
5. Click **"Continuar"**

**Resultado esperado:**
- Verás cuántas relaciones existen actualmente
- Verás qué tablas tienen motor InnoDB
- Diagnóstico: ❌ o ✅

---

### **PASO 2: Ejecutar el Script de Corrección** 🛠️

1. En phpMyAdmin, con `simahg_db` seleccionada
2. Click en pestaña **"SQL"**
3. Copia y pega el contenido de: **`fix_relaciones.sql`**
4. Click **"Continuar"**

**Resultado esperado:**
- ✅ "Relaciones recreadas exitosamente!"
- Tabla con las 4 relaciones creadas

---

### **PASO 3: Verificar en el Diseñador** 👀

1. Ve al Diseñador: `http://localhost/phpmyadmin/index.php?route=/database/designer&db=simahg_db`
2. **Ahora SÍ deberías ver:**
   - Líneas conectando las tablas
   - `usuarios` → `perfiles`
   - `menu` → `modulos`
   - `permisos` → `perfiles`
   - `permisos` → `menu`

3. **Si NO ves las líneas:**
   - Click en el botón **"Importar/Exportar coordenadas"**
   - Click en **"Organizar Diseño"** (o "Auto Layout")
   - Las tablas se organizarán y verás las relaciones

---

## 📊 **Relaciones que DEBEN existir:**

```
┌──────────────┐
│   perfiles   │
│     (id)     │
└──────┬───────┘
       │
       │ id_perfil
       │
┌──────▼───────┐         ┌──────────────┐
│   usuarios   │         │   modulos    │
│              │         │     (id)     │
└──────────────┘         └──────┬───────┘
                                │
                                │ id_modulo
                                │
       ┌────────────────────────▼────┐
       │         menu                │
       │         (id)                │
       └────┬────────────────────────┘
            │
            │ id_menu
            │
┌───────────▼──────┐     ┌──────────────┐
│    permisos      │────▶│   perfiles   │
│                  │     │     (id)     │
│  id_perfil       │     └──────────────┘
│  id_menu         │
└──────────────────┘
```

---

## 🎯 **Relaciones Específicas:**

| Tabla       | Columna      | Referencia      | Columna Ref | Acción Delete | Acción Update |
|-------------|--------------|-----------------|-------------|---------------|---------------|
| `usuarios`  | `id_perfil`  | `perfiles`      | `id`        | RESTRICT      | CASCADE       |
| `menu`      | `id_modulo`  | `modulos`       | `id`        | RESTRICT      | CASCADE       |
| `permisos`  | `id_perfil`  | `perfiles`      | `id`        | CASCADE       | CASCADE       |
| `permisos`  | `id_menu`    | `menu`          | `id`        | CASCADE       | CASCADE       |

### **¿Qué significa cada acción?**

- **RESTRICT**: No permite eliminar si existen dependencias
  - Ejemplo: No puedes eliminar un perfil si hay usuarios asignados
  
- **CASCADE**: Elimina/actualiza automáticamente las dependencias
  - Ejemplo: Si eliminas un perfil, se eliminan todos sus permisos

- **UPDATE CASCADE**: Si cambias un ID, actualiza automáticamente las referencias

---

## 🧪 **Prueba que las Relaciones Funcionan:**

Después de ejecutar el script, prueba esto en phpMyAdmin:

### **Prueba 1: Intentar eliminar un perfil con usuarios asignados**
```sql
-- Esto DEBE fallar con error de FOREIGN KEY
DELETE FROM perfiles WHERE id = 1;
```
**Resultado esperado**: ❌ Error (porque hay usuarios con ese perfil)

### **Prueba 2: Intentar insertar un usuario con perfil inexistente**
```sql
-- Esto DEBE fallar
INSERT INTO usuarios (nombre, apellidos, email, usuario, password, id_perfil, estado)
VALUES ('Test', 'User', 'test@test.com', 'testuser', '12345', 999, 1);
```
**Resultado esperado**: ❌ Error (porque el perfil 999 no existe)

### **Prueba 3: Actualizar correctamente**
```sql
-- Esto DEBE funcionar
UPDATE usuarios SET nombre = 'Juan Carlos Actualizado' WHERE id = 2;
```
**Resultado esperado**: ✅ Éxito

---

## 🔄 **Si algo sale mal:**

### **Error: "Cannot add foreign key constraint"**
**Causa**: Las tablas pueden tener datos inconsistentes  
**Solución**: 
1. Verificar que todos los `id_perfil` en `usuarios` existan en `perfiles`
2. Ejecutar:
```sql
-- Encontrar usuarios con perfil inexistente
SELECT u.* 
FROM usuarios u 
LEFT JOIN perfiles p ON u.id_perfil = p.id 
WHERE p.id IS NULL;
```

### **Error: "Table doesn't support foreign keys"**
**Causa**: La tabla no es InnoDB  
**Solución**:
```sql
ALTER TABLE usuarios ENGINE=InnoDB;
ALTER TABLE perfiles ENGINE=InnoDB;
ALTER TABLE menu ENGINE=InnoDB;
ALTER TABLE modulos ENGINE=InnoDB;
ALTER TABLE permisos ENGINE=InnoDB;
```

---

## ✅ **Checklist de Verificación:**

- [ ] Ejecuté `verificar_relaciones.sql`
- [ ] Vi que NO hay relaciones (o faltan algunas)
- [ ] Ejecuté `fix_relaciones.sql`
- [ ] Vi el mensaje "Relaciones recreadas exitosamente"
- [ ] Fui al Diseñador de phpMyAdmin
- [ ] Veo las líneas conectando las tablas
- [ ] Probé las relaciones con las consultas de prueba

---

## 📞 **Archivos Incluidos:**

1. **`verificar_relaciones.sql`**: Diagnóstico del estado actual
2. **`fix_relaciones.sql`**: Corrección automática de relaciones
3. **`ARREGLAR_RELACIONES.md`**: Esta guía

---

## 🎉 **Resultado Final:**

Después de seguir estos pasos, tu base de datos tendrá:

✅ **4 Foreign Keys activas**  
✅ **Integridad referencial garantizada**  
✅ **Diseñador de phpMyAdmin mostrando relaciones**  
✅ **Base de datos profesional y robusta**  

---

**Fecha de creación**: 22 de noviembre de 2025  
**Sistema**: SIMAHG v2.0
