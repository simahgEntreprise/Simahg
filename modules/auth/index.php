<?php
/**
 * MÓDULO DE AUTENTICACIÓN
 * 
 * Archivos:
 * - login.php                    -> Página de inicio de sesión
 * - login_process.php            -> Procesa el login
 * - login_success.php            -> Redirección exitosa
 * - logout.php                   -> Cerrar sesión
 * - recuperar_password.php       -> Recuperación de contraseña
 * - recuperar_password_process.php -> Procesa recuperación
 * - verificar_codigo.php         -> Verificar código de recuperación
 * - verificar_codigo_process.php -> Procesa verificación
 * - nueva_password.php           -> Establecer nueva contraseña
 * - nueva_password_process.php   -> Procesa nueva contraseña
 * - cambiar_password.php         -> Cambiar contraseña (usuario logueado)
 * - cambiar_password_process.php -> Procesa cambio
 */

// Redireccionar a login
header('Location: login.php');
exit();
?>
