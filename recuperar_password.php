<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .recovery-container { 
            max-width: 500px; 
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
        .btn-recovery {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        .btn-recovery:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .recovery-method {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        .recovery-method:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        .recovery-method.active {
            border-color: #667eea;
            background: #e7e9fc;
        }
        .recovery-method input[type="radio"] {
            margin-right: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="recovery-container">
            <div class="logo">
                <!-- Logo SIMAHG SVG -->
                <svg width="75" height="75" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 10px;">
                    <defs>
                        <linearGradient id="gradRecovery" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="48" fill="url(#gradRecovery)"/>
                    <path d="M 35 30 Q 35 25, 40 25 L 60 25 Q 65 25, 65 30 Q 65 35, 60 35 L 45 35 Q 40 35, 40 40 Q 40 45, 45 45 L 55 45 Q 65 45, 65 55 Q 65 65, 55 65 L 40 65 Q 35 65, 35 60" 
                          fill="none" stroke="white" stroke-width="4" stroke-linecap="round"/>
                </svg>
                <h2>Recuperar Contraseña</h2>
            </div>
            
            <?php
            session_start();
            
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
            
            <form method="post" action="recuperar_password_process.php" id="recoveryForm">
                <h4 class="text-center mb-4">Selecciona el método de recuperación</h4>
                
                <!-- Método 1: Email -->
                <div class="recovery-method" onclick="selectMethod('email')">
                    <label style="cursor: pointer; width: 100%; margin: 0;">
                        <input type="radio" name="metodo" value="email" id="metodo_email" required>
                        <i class="fa fa-envelope fa-2x" style="color: #667eea; vertical-align: middle;"></i>
                        <strong style="font-size: 18px;">Correo Electrónico</strong>
                        <p class="text-muted" style="margin: 10px 0 0 35px;">
                            Enviaremos un código de verificación a tu correo registrado
                        </p>
                    </label>
                </div>
                
                <!-- Método 2: SMS -->
                <div class="recovery-method" onclick="selectMethod('sms')">
                    <label style="cursor: pointer; width: 100%; margin: 0;">
                        <input type="radio" name="metodo" value="sms" id="metodo_sms" required>
                        <i class="fa fa-mobile fa-2x" style="color: #667eea; vertical-align: middle;"></i>
                        <strong style="font-size: 18px;">SMS al Celular</strong>
                        <p class="text-muted" style="margin: 10px 0 0 35px;">
                            Enviaremos un código de verificación a tu número registrado
                        </p>
                    </label>
                </div>
                
                <div class="form-group" style="margin-top: 30px;">
                    <label>Ingresa tu nombre de usuario:</label>
                    <input type="text" class="form-control" name="usuario" placeholder="👤 Usuario" required autocomplete="off">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-recovery btn-block">
                        <i class="fa fa-paper-plane"></i> Enviar Código de Recuperación
                    </button>
                </div>
            </form>
            
            <div class="back-link">
                <a href="login.php">
                    <i class="fa fa-arrow-left"></i> Volver al Login
                </a>
            </div>
        </div>
    </div>
    
    <script>
        function selectMethod(metodo) {
            // Remover clase active de todos
            document.querySelectorAll('.recovery-method').forEach(function(el) {
                el.classList.remove('active');
            });
            
            // Activar el seleccionado
            if (metodo === 'email') {
                document.getElementById('metodo_email').checked = true;
                document.getElementById('metodo_email').parentElement.parentElement.classList.add('active');
            } else {
                document.getElementById('metodo_sms').checked = true;
                document.getElementById('metodo_sms').parentElement.parentElement.classList.add('active');
            }
        }
    </script>
</body>
</html>
