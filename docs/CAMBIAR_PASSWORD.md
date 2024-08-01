# 🔐 Guía: Cambiar Contraseña desde el Perfil de Usuario

## 📋 Descripción General

La funcionalidad de **Cambiar Contraseña** permite a cualquier usuario autenticado cambiar su contraseña desde su perfil, sin necesidad de intervención del administrador.

## 🎯 Ubicación y Acceso

### Desde el Navbar
1. Inicia sesión en SIMAHG
2. Haz clic en tu nombre de usuario en la esquina superior derecha
3. Se desplegará un menú con las siguientes opciones:
   - ✅ **Mi Perfil** (si aplica para el rol)
   - 🛡️ **Mis Solicitudes** (si aplica para el rol)
   - 🔑 **Cambiar Contraseña** ← **ESTA ES LA NUEVA OPCIÓN**
   - 🚪 **Cerrar Sesión**

4. Haz clic en **"Cambiar Contraseña"**

### URL Directa
También puedes acceder directamente a:
```
http://localhost/simahg/cambiar_password.php
```

## 🔒 Seguridad Implementada

### Verificaciones de Seguridad
1. **Sesión Activa**: Solo usuarios autenticados pueden acceder
2. **Verificación de Contraseña Actual**: Debes conocer tu contraseña actual
3. **Validación de Nueva Contraseña**: 
   - Mínimo 6 caracteres
   - Debe coincidir con la confirmación
4. **Hash SHA1**: Las contraseñas se almacenan cifradas
5. **Protección contra Ataques**: El sistema valida la sesión en cada paso

## 📝 Proceso de Cambio de Contraseña

### Paso 1: Acceder al Formulario
- Desde el dropdown del usuario, selecciona "Cambiar Contraseña"
- Serás redirigido a `cambiar_password.php`

### Paso 2: Completar el Formulario
El formulario solicita:

1. **Contraseña Actual** 🔑
   - Ingresa tu contraseña actual
   - Esto verifica tu identidad
   - Puedes ver/ocultar con el ícono del ojo

2. **Nueva Contraseña** 🆕
   - Mínimo 6 caracteres
   - Elige una contraseña segura
   - Puedes ver/ocultar con el ícono del ojo

3. **Confirmar Nueva Contraseña** ✅
   - Repite la nueva contraseña
   - Debe coincidir exactamente
   - Puedes ver/ocultar con el ícono del ojo

### Paso 3: Enviar el Formulario
- Haz clic en **"Cambiar Contraseña"**
- El sistema validará:
  ✓ Que la contraseña actual sea correcta
  ✓ Que la nueva contraseña cumpla los requisitos
  ✓ Que ambas nuevas contraseñas coincidan

### Paso 4: Confirmación
Si todo es correcto:
- ✅ Verás un mensaje de éxito
- 🔐 Tu contraseña ha sido actualizada
- 🔄 Puedes volver al dashboard

## 🛡️ Mensajes de Error Comunes

### Error: "La contraseña actual es incorrecta"
- **Causa**: La contraseña actual que ingresaste no coincide
- **Solución**: Verifica que estés usando tu contraseña actual correcta
- **Alternativa**: Usa "¿Olvidaste tu contraseña?" en el login

### Error: "La nueva contraseña debe tener al menos 6 caracteres"
- **Causa**: La nueva contraseña es muy corta
- **Solución**: Elige una contraseña de al menos 6 caracteres

### Error: "Las contraseñas nuevas no coinciden"
- **Causa**: La nueva contraseña y su confirmación son diferentes
- **Solución**: Asegúrate de escribir exactamente la misma contraseña en ambos campos

## 💾 Archivos del Sistema

### Frontend
- **cambiar_password.php**: Formulario para cambiar contraseña
  - Validación de sesión
  - Interfaz amigable con toggle de visibilidad
  - Diseño moderno y responsivo

### Backend
- **cambiar_password_process.php**: Procesa el cambio
  - Valida contraseña actual
  - Valida nueva contraseña (mínimo 6 caracteres)
  - Verifica coincidencia
  - Actualiza en BD con SHA1
  - Retorna JSON con resultado

### Configuración
- **includes/config_common.php**: Navbar con opción "Cambiar Contraseña"
  - Disponible para todos los roles
  - Ubicado en el dropdown del usuario

## 🎨 Características de Interfaz

### Diseño
- ✨ Formulario limpio y moderno
- 📱 Responsivo (funciona en móviles)
- 🎨 Gradientes púrpura coherentes con el sistema
- 👁️ Toggle para mostrar/ocultar contraseñas

### Experiencia de Usuario
- 📝 Instrucciones claras
- ⚠️ Alertas visuales para errores
- ✅ Confirmación de éxito
- 🔙 Botón para volver al dashboard

## 🔄 Flujo Completo

```
1. Usuario logueado
   ↓
2. Click en nombre de usuario (navbar)
   ↓
3. Click en "Cambiar Contraseña"
   ↓
4. Completa formulario:
   - Contraseña actual
   - Nueva contraseña
   - Confirmar nueva contraseña
   ↓
5. Click en "Cambiar Contraseña"
   ↓
6. Sistema valida:
   - ✓ Contraseña actual correcta
   - ✓ Nueva contraseña ≥ 6 caracteres
   - ✓ Confirmación coincide
   ↓
7. Sistema actualiza BD con SHA1
   ↓
8. Mensaje de éxito
   ↓
9. Usuario puede usar nueva contraseña
```

## 🧪 Cómo Probar

### Prueba Básica
1. Inicia sesión con cualquier usuario
2. Click en tu nombre → "Cambiar Contraseña"
3. Ingresa:
   - Contraseña actual: `[tu contraseña actual]`
   - Nueva contraseña: `nuevapass123`
   - Confirmar: `nuevapass123`
4. Click en "Cambiar Contraseña"
5. Deberías ver mensaje de éxito
6. Cierra sesión e intenta entrar con la nueva contraseña

### Prueba de Seguridad
1. **Sin sesión**: Intenta acceder directamente a `cambiar_password.php`
   - ✅ Debería redirigir a `login.php`

2. **Contraseña incorrecta**: Ingresa una contraseña actual incorrecta
   - ✅ Debería mostrar error

3. **Contraseña corta**: Intenta una nueva contraseña de menos de 6 caracteres
   - ✅ Debería mostrar error

4. **No coinciden**: Ingresa contraseñas diferentes en nueva y confirmación
   - ✅ Debería mostrar error

## 🔗 Relación con Recuperación de Contraseña

Esta funcionalidad es **complementaria** a la recuperación de contraseña:

| Cambiar Contraseña | Recuperar Contraseña |
|-------------------|---------------------|
| Usuario **conoce** su contraseña actual | Usuario **olvidó** su contraseña |
| Desde el perfil (logueado) | Desde el login (sin sesión) |
| Requiere contraseña actual | Requiere email/teléfono |
| Sin código de verificación | Con código de verificación |
| cambiar_password.php | recuperar_password.php |

## 📞 Soporte

Si tienes problemas:

1. **Olvidaste tu contraseña actual**: 
   - Usa el flujo de recuperación desde el login
   - "¿Olvidaste tu contraseña?"

2. **Error al cambiar**: 
   - Verifica que estés usando la contraseña actual correcta
   - Asegúrate de que la nueva contraseña tenga al menos 6 caracteres
   - Verifica que ambas contraseñas nuevas coincidan

3. **No ves la opción en el menú**:
   - Verifica que estés logueado
   - Actualiza la página (F5)
   - Limpia caché del navegador

## ✅ Estado Actual

🟢 **COMPLETAMENTE IMPLEMENTADO Y FUNCIONAL**

- ✅ Formulario creado (`cambiar_password.php`)
- ✅ Procesador backend (`cambiar_password_process.php`)
- ✅ Opción agregada al navbar (todos los roles)
- ✅ Validaciones de seguridad implementadas
- ✅ Hash SHA1 para contraseñas
- ✅ Interfaz moderna y responsiva
- ✅ Toggle de visibilidad de contraseñas
- ✅ Mensajes de error claros
- ✅ Integrado con el sistema existente

## 🎯 Próximos Pasos Sugeridos

1. **Probar la funcionalidad**:
   - Accede con un usuario de prueba
   - Cambia la contraseña desde el perfil
   - Verifica que puedas entrar con la nueva contraseña

2. **Política de Contraseñas** (opcional):
   - Actualmente: mínimo 6 caracteres
   - Podrías requerir: mayúsculas, números, símbolos
   - Configurar caducidad de contraseñas

3. **Historial de Contraseñas** (opcional):
   - Evitar reutilizar últimas N contraseñas
   - Guardar fecha de último cambio

4. **Notificaciones** (opcional):
   - Enviar email cuando se cambie contraseña
   - Alertas de seguridad

---

## 🚀 ¡Sistema Listo para Usar!

La funcionalidad de cambio de contraseña está **completamente implementada** y lista para usarse. Todos los usuarios autenticados pueden cambiar su contraseña de forma segura desde su perfil.

**Desarrollado para SIMAHG** 🛡️
