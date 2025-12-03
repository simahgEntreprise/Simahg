<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Código - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .verify-container { 
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
        .code-input {
            text-align: center;
            font-size: 32px;
            letter-spacing: 10px;
            font-weight: bold;
            border-radius: 10px;
            border: 2px solid #ddd;
            padding: 20px;
        }
        .code-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .timer {
            text-align: center;
            font-size: 18px;
            color: #667eea;
            font-weight: bold;
            margin: 20px 0;
        }
        .resend-link {
            text-align: center;
            margin-top: 20px;
        }
        .resend-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .resend-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verify-container">
            <?php
            session_start();
            
            // Verificar que haya una sesión de recuperación activa
            if (!isset($_SESSION['recovery_user_id'])) {
                header('Location: recuperar_password.php');
                exit();
            }
            ?>
            
            <div class="logo">
                <!-- Logo SIMAHG SVG -->
                <svg width="75" height="75" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 10px;">
                    <defs>
                        <linearGradient id="gradVerify" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="48" fill="url(#gradVerify)"/>
                    <path d="M 35 30 Q 35 25, 40 25 L 60 25 Q 65 25, 65 30 Q 65 35, 60 35 L 45 35 Q 40 35, 40 40 Q 40 45, 45 45 L 55 45 Q 65 45, 65 55 Q 65 65, 55 65 L 40 65 Q 35 65, 35 60" 
                          fill="none" stroke="white" stroke-width="4" stroke-linecap="round"/>
                </svg>
                <h2>Verificar Código</h2>
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
            
            if (isset($_SESSION['warning'])) {
                echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> ' . $_SESSION['warning'] . '</div>';
                unset($_SESSION['warning']);
            }
            
            $metodo = $_SESSION['recovery_metodo'] ?? 'email';
            $icono = $metodo === 'email' ? 'fa-envelope' : 'fa-mobile';
            $texto = $metodo === 'email' ? 'correo electrónico' : 'SMS';
            ?>
            
            <div class="alert alert-info text-center">
                <i class="fa <?php echo $icono; ?> fa-2x"></i>
                <p style="margin: 10px 0 0 0;">
                    Hemos enviado un código de 6 dígitos a tu <strong><?php echo $texto; ?></strong>
                </p>
            </div>
            
            <form method="post" action="verificar_codigo_process.php">
                <div class="form-group">
                    <input type="text" class="form-control code-input" name="codigo" 
                           placeholder="000000" maxlength="6" required autocomplete="off" 
                           pattern="[0-9]{6}" title="Debe ser un código de 6 dígitos">
                </div>
                
                <div class="timer">
                    ⏱️ Código expira en: <span id="countdown">15:00</span>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-verify btn-block">
                        <i class="fa fa-check"></i> Verificar Código
                    </button>
                </div>
            </form>
            
            <div class="resend-link">
                <a href="solicitar_nuevo_codigo.php">
                    <i class="fa fa-refresh"></i> Solicitar nuevo código
                </a>
                <br><br>
                <a href="login.php">
                    <i class="fa fa-arrow-left"></i> Volver al Login
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Countdown timer de 15 minutos
        let timeLeft = 15 * 60; // 15 minutos en segundos
        
        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            document.getElementById('countdown').textContent = 
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            
            if (timeLeft <= 0) {
                document.getElementById('countdown').textContent = 'EXPIRADO';
                document.getElementById('countdown').style.color = 'red';
                return;
            }
            
            // Cambiar a rojo cuando quedan menos de 2 minutos
            if (timeLeft <= 120) {
                document.getElementById('countdown').style.color = 'red';
            }
            
            timeLeft--;
            setTimeout(updateCountdown, 1000);
        }
        
        // Iniciar countdown
        updateCountdown();
        
        // Auto-focus en el input del código
        document.querySelector('.code-input').focus();
    </script>
</body>
</html>
