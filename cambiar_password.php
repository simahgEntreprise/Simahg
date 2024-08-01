<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Incluir configuración común
require_once 'includes/config_common.php';

$page_title = "Cambiar Contraseña";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .password-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .page-header h2 {
            color: #667eea;
            font-weight: 700;
            margin: 10px 0;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-cambiar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-cambiar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .btn-volver {
            background: #6c757d;
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            transition: all 0.3s;
        }
        .btn-volver:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
        .password-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 10;
        }
        .toggle-password:hover {
            color: #667eea;
        }
        .password-strength {
            margin-top: 10px;
            font-size: 14px;
            font-weight: 600;
        }
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
        .requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .requirements ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .requirements li {
            margin: 5px 0;
        }
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .user-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #2196F3;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="password-container">
            <div class="page-header">
                <i class="fa fa-key fa-3x" style="color: #667eea;"></i>
                <h2>Cambiar Contraseña</h2>
            </div>
            
            <div class="user-info">
                <strong><i class="fa fa-user"></i> Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?><br>
                <strong><i class="fa fa-envelope"></i> Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?>
            </div>
            
            <?php
            // Mostrar mensajes
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>
            
            <div class="requirements">
                <strong><i class="fa fa-info-circle"></i> Requisitos de contraseña:</strong>
                <ul>
                    <li>Mínimo 6 caracteres</li>
                    <li>Se recomienda usar letras, números y símbolos</li>
                    <li>Evita usar información personal</li>
                    <li>Debe ser diferente a tu contraseña actual</li>
                </ul>
            </div>
            
            <form method="post" action="cambiar_password_process.php" id="changePasswordForm">
                <div class="form-group">
                    <label for="current_password">
                        <i class="fa fa-lock"></i> Contraseña Actual:
                    </label>
                    <div class="password-wrapper">
                        <input type="password" 
                               class="form-control" 
                               id="current_password" 
                               name="current_password" 
                               placeholder="Ingresa tu contraseña actual" 
                               required 
                               autocomplete="current-password">
                        <span class="toggle-password" onclick="togglePasswordVisibility('current_password', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_password">
                        <i class="fa fa-key"></i> Nueva Contraseña:
                    </label>
                    <div class="password-wrapper">
                        <input type="password" 
                               class="form-control" 
                               id="new_password" 
                               name="new_password" 
                               placeholder="Ingresa tu nueva contraseña" 
                               required 
                               minlength="6"
                               autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePasswordVisibility('new_password', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                    <div id="strengthIndicator" class="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fa fa-check"></i> Confirmar Nueva Contraseña:
                    </label>
                    <div class="password-wrapper">
                        <input type="password" 
                               class="form-control" 
                               id="confirm_password" 
                               name="confirm_password" 
                               placeholder="Confirma tu nueva contraseña" 
                               required 
                               minlength="6"
                               autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                    <div id="matchIndicator" class="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-cambiar" id="submitBtn">
                        <i class="fa fa-save"></i> Cambiar Contraseña
                    </button>
                </div>
            </form>
            
            <div class="text-center">
                <a href="dashboard.php" class="btn-volver">
                    <i class="fa fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        // Mostrar/ocultar contraseña
        function togglePasswordVisibility(fieldId, icon) {
            const field = document.getElementById(fieldId);
            const iconElement = icon.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                iconElement.classList.remove('fa-eye');
                iconElement.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                iconElement.classList.remove('fa-eye-slash');
                iconElement.classList.add('fa-eye');
            }
        }
        
        // Verificar fortaleza de contraseña
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const indicator = document.getElementById('strengthIndicator');
            
            if (password.length === 0) {
                indicator.innerHTML = '';
                return;
            }
            
            let strength = 0;
            
            // Longitud
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (password.length >= 10) strength++;
            
            // Mayúsculas
            if (/[A-Z]/.test(password)) strength++;
            
            // Números
            if (/[0-9]/.test(password)) strength++;
            
            // Símbolos
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength <= 2) {
                indicator.innerHTML = '<i class="fa fa-exclamation-circle"></i> Contraseña débil';
                indicator.className = 'password-strength strength-weak';
            } else if (strength <= 4) {
                indicator.innerHTML = '<i class="fa fa-check-circle"></i> Contraseña media';
                indicator.className = 'password-strength strength-medium';
            } else {
                indicator.innerHTML = '<i class="fa fa-check-circle"></i> Contraseña fuerte';
                indicator.className = 'password-strength strength-strong';
            }
            
            checkPasswordMatch();
        });
        
        // Verificar que las contraseñas coincidan
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;
            const indicator = document.getElementById('matchIndicator');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirm.length === 0) {
                indicator.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }
            
            if (newPassword === confirm) {
                indicator.innerHTML = '<i class="fa fa-check-circle"></i> Las contraseñas coinciden';
                indicator.className = 'password-strength strength-strong';
                submitBtn.disabled = false;
            } else {
                indicator.innerHTML = '<i class="fa fa-times-circle"></i> Las contraseñas no coinciden';
                indicator.className = 'password-strength strength-weak';
                submitBtn.disabled = true;
            }
        }
        
        // Validar antes de enviar
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });
    </script>
</body>
</html>
