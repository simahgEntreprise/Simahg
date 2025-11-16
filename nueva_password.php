<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .password-container { 
            max-width: 450px; 
            margin: 0 auto; 
            padding: 40px;
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .logo { 
            text-align: center; 
            margin-bottom: 30px;
        }
        .logo h2 {
            color: #333;
            font-weight: 700;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 15px;
            font-size: 16px;
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .password-strength {
            margin-top: 10px;
            font-size: 14px;
        }
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
        .show-password {
            cursor: pointer;
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        .password-wrapper {
            position: relative;
        }
        .requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            font-size: 14px;
        }
        .requirements ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .requirements li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="password-container">
            <?php
            session_start();
            
            // Verificar que el código haya sido verificado
            if (!isset($_SESSION['password_reset_verified']) || !isset($_SESSION['recovery_user_id'])) {
                header('Location: recuperar_password.php');
                exit();
            }
            ?>
            
            <div class="logo">
                <!-- Logo SIMAHG SVG -->
                <svg width="75" height="75" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 10px;">
                    <defs>
                        <linearGradient id="gradNew" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="48" fill="url(#gradNew)"/>
                    <path d="M 35 30 Q 35 25, 40 25 L 60 25 Q 65 25, 65 30 Q 65 35, 60 35 L 45 35 Q 40 35, 40 40 Q 40 45, 45 45 L 55 45 Q 65 45, 65 55 Q 65 65, 55 65 L 40 65 Q 35 65, 35 60" 
                          fill="none" stroke="white" stroke-width="4" stroke-linecap="round"/>
                </svg>
                <h2>Nueva Contraseña</h2>
            </div>
            
            <?php
            // Mostrar mensajes
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $_SESSION['success'] . '</div>';
            }
            ?>
            
            <div class="requirements">
                <strong><i class="fa fa-info-circle"></i> Requisitos de contraseña:</strong>
                <ul>
                    <li>Mínimo 6 caracteres</li>
                    <li>Se recomienda usar letras, números y símbolos</li>
                    <li>Evita usar información personal</li>
                </ul>
            </div>
            
            <form method="post" action="nueva_password_process.php" id="passwordForm">
                <div class="form-group password-wrapper">
                    <label>Nueva Contraseña:</label>
                    <input type="password" class="form-control" name="password" id="password" 
                           placeholder="🔒 Nueva contraseña" required minlength="6" autocomplete="off">
                    <span class="show-password" onclick="togglePassword('password', this)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                
                <div id="strengthIndicator" class="password-strength"></div>
                
                <div class="form-group password-wrapper">
                    <label>Confirmar Contraseña:</label>
                    <input type="password" class="form-control" name="password_confirm" id="password_confirm" 
                           placeholder="🔒 Confirmar contraseña" required minlength="6" autocomplete="off">
                    <span class="show-password" onclick="togglePassword('password_confirm', this)">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                
                <div id="matchIndicator" class="password-strength"></div>
                
                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-save btn-block" id="submitBtn">
                        <i class="fa fa-save"></i> Guardar Nueva Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Mostrar/ocultar contraseña
        function togglePassword(fieldId, icon) {
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
        document.getElementById('password').addEventListener('input', function() {
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
                indicator.innerHTML = '<i class="fa fa-exclamation-circle strength-weak"></i> Contraseña débil';
                indicator.className = 'password-strength strength-weak';
            } else if (strength <= 4) {
                indicator.innerHTML = '<i class="fa fa-check-circle strength-medium"></i> Contraseña media';
                indicator.className = 'password-strength strength-medium';
            } else {
                indicator.innerHTML = '<i class="fa fa-check-circle strength-strong"></i> Contraseña fuerte';
                indicator.className = 'password-strength strength-strong';
            }
            
            checkPasswordMatch();
        });
        
        // Verificar que las contraseñas coincidan
        document.getElementById('password_confirm').addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const indicator = document.getElementById('matchIndicator');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirm.length === 0) {
                indicator.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }
            
            if (password === confirm) {
                indicator.innerHTML = '<i class="fa fa-check-circle strength-strong"></i> Las contraseñas coinciden';
                indicator.className = 'password-strength strength-strong';
                submitBtn.disabled = false;
            } else {
                indicator.innerHTML = '<i class="fa fa-times-circle strength-weak"></i> Las contraseñas no coinciden';
                indicator.className = 'password-strength strength-weak';
                submitBtn.disabled = true;
            }
        }
        
        // Validar antes de enviar
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });
    </script>
</body>
</html>
# Update 1764801947
