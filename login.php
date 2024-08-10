<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAHG - Sistema de Gestión</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container { 
            max-width: 400px; 
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
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="logo">
                <!-- Logo SIMAHG SVG -->
                <svg width="90" height="90" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 15px;">
                    <!-- Fondo circular con gradiente -->
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                        <filter id="shadow">
                            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.3"/>
                        </filter>
                    </defs>
                    <circle cx="50" cy="50" r="48" fill="url(#grad1)" filter="url(#shadow)"/>
                    
                    <!-- Letra S estilizada -->
                    <path d="M 35 30 Q 35 25, 40 25 L 60 25 Q 65 25, 65 30 Q 65 35, 60 35 L 45 35 Q 40 35, 40 40 Q 40 45, 45 45 L 55 45 Q 65 45, 65 55 Q 65 65, 55 65 L 40 65 Q 35 65, 35 60" 
                          fill="none" stroke="white" stroke-width="4" stroke-linecap="round"/>
                    
                    <!-- Engranaje pequeño (símbolo de gestión) -->
                    <g transform="translate(68, 68) scale(0.25)">
                        <circle cx="0" cy="0" r="12" fill="white"/>
                        <circle cx="0" cy="0" r="6" fill="url(#grad1)"/>
                        <rect x="-2" y="-18" width="4" height="8" fill="white" rx="1"/>
                        <rect x="-2" y="10" width="4" height="8" fill="white" rx="1"/>
                        <rect x="-18" y="-2" width="8" height="4" fill="white" rx="1"/>
                        <rect x="10" y="-2" width="8" height="4" fill="white" rx="1"/>
                    </g>
                </svg>
                <h2 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: 2px;">SIMAHG</h2>
            </div>
            
            <?php
            session_start();
            
            // Mostrar mensajes
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>
            
            <form method="post" action="login_process.php" autocomplete="off">
                <div class="form-group">
                    <input type="text" 
                           class="form-control" 
                           name="usuario" 
                           placeholder="👤 Usuario" 
                           autocomplete="off" 
                           required 
                           autofocus>
                </div>
                
                <div class="form-group">
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           placeholder="🔒 Contraseña" 
                           autocomplete="new-password" 
                           required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-login btn-block">
                        <i class="fa fa-sign-in"></i> Iniciar Sesión
                    </button>
                </div>
                
                <div class="text-center" style="margin-top: 15px;">
                    <a href="recuperar_password.php" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        <i class="fa fa-key"></i> ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</body>
</html>
# Update 1764801941
