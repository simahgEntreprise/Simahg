<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SIMAHG - Sistema de Gestión Empresarial">
    <meta name="author" content="SIMAHG Team">

    <title>SIMAHG - Sistema de Gestión</title> 

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            min-height: 500px;
            display: flex;
        }
        
        .login-image {
            background: linear-gradient(45deg, #667eea, #764ba2);
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
            padding: 40px;
        }
        
        .login-image h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .login-image p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        
        .login-form {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form h3 {
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .login-form p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-control {
            border: none;
            border-bottom: 2px solid #e1e1e1;
            border-radius: 0;
            padding: 15px 50px 15px 15px;
            font-size: 16px;
            background: transparent;
            box-shadow: none;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-bottom-color: #667eea;
            box-shadow: none;
            background: transparent;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            color: #999;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            color: white;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            margin-top: 15px;
            text-align: center;
            display: block;
            transition: all 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #5a67d8;
            text-decoration: none;
        }
        
        .logo-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
            }
            
            .login-image {
                padding: 30px 20px;
                min-height: 200px;
            }
            
            .login-form {
                padding: 40px 30px;
            }
            
            .login-image h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Lado izquierdo con imagen/branding -->
        <div class="login-image">
            <i class="fa fa-shield logo-icon"></i>
            <h2>SIMAHG</h2>
            <p>Sistema Integral de Gestión Empresarial</p>
            <div style="margin-top: 40px;">
                <div style="margin-bottom: 15px;"><i class="fa fa-check-circle" style="margin-right: 10px;"></i> Seguro y Confiable</div>
                <div style="margin-bottom: 15px;"><i class="fa fa-users" style="margin-right: 10px;"></i> Multi-usuario</div>
                <div><i class="fa fa-mobile" style="margin-right: 10px;"></i> Responsive Design</div>
            </div>
        </div>
        
        <!-- Lado derecho con formulario -->
        <div class="login-form">
            <h3>Iniciar Sesión</h3>
            <p>Ingresa tus credenciales para acceder al sistema</p>
            
            <div id="mensaje-error" class="alert alert-danger alert-custom" style="display: none;">
                <i class="fa fa-exclamation-triangle"></i> <span id="texto-error"></span>
            </div>
            
            <form id="loginForm" role="form">
                <div class="form-group">
                    <input class="form-control" placeholder="Usuario" id="email" name="usuario" type="text" autofocus>
                    <i class="fa fa-user input-icon"></i>
                </div>
                
                <div class="form-group">
                    <input class="form-control" placeholder="Contraseña" id="password" name="password" type="password">
                    <i class="fa fa-lock input-icon"></i>
                </div>
                
                <button type="submit" class="btn btn-login btn-block">
                    <span class="loading-spinner">
                        <i class="fa fa-spinner fa-spin"></i>
                    </span>
                    Ingresar
                </button>
                
                <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <script>
        var base_url = "<?php echo base_url(); ?>";
        
        // Función mejorada para el login
        function login() {
            var usuario = $("#email").val().trim();
            var password = $("#password").val();
            
            // Validaciones
            if (!usuario || !password) {
                mostrarError("Por favor, completa todos los campos");
                return false;
            }
            
            // Mostrar loading
            $('.loading-spinner').show();
            $('.btn-login').prop('disabled', true);
            $('#mensaje-error').hide();
            
            $.ajax({
                type: "POST",
                url: base_url + "login/getLogin",
                dataType: 'json',
                data: {
                    log: usuario,
                    pass: password
                },
                success: function(data) {
                    if (data.authenticated === true) {
                        // Login exitoso
                        mostrarExito("¡Bienvenido! Redirigiendo...");
                        setTimeout(function() {
                            window.location.href = base_url + "home";
                        }, 1500);
                    } else {
                        mostrarError(data.message || "Usuario y/o contraseña incorrectos");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en login:", error);
                    mostrarError("Error de conexión. Por favor, intenta nuevamente.");
                },
                complete: function() {
                    // Ocultar loading
                    $('.loading-spinner').hide();
                    $('.btn-login').prop('disabled', false);
                }
            });
            
            return false;
        }
        
        function mostrarError(mensaje) {
            $('#texto-error').text(mensaje);
            $('#mensaje-error').removeClass('alert-success').addClass('alert-danger').show();
        }
        
        function mostrarExito(mensaje) {
            $('#texto-error').text(mensaje);
            $('#mensaje-error').removeClass('alert-danger').addClass('alert-success').show();
        }
        
        // Event listeners
        $(document).ready(function() {
            // Submit del formulario
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                login();
            });
            
            // Enter key
            $(document).on('keypress', function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    login();
                }
            });
            
            // Focus en el primer campo
            $('#email').focus();
            
            // Limpiar errores al escribir
            $('#email, #password').on('input', function() {
                $('#mensaje-error').fadeOut();
            });
        });
    </script>
</body>
</html>
