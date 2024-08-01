<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAHG - Login Simplificado</title>
    <link href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { 
            max-width: 400px; 
            margin: 100px auto; 
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .alert { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">SIMAHG - Sistema de Gestión</h2>
            
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo base_url('login/authenticate'); ?>">
                <div class="form-group">
                    <label for="log">Usuario:</label>
                    <input type="text" class="form-control" id="log" name="log" value="admin" required>
                </div>
                
                <div class="form-group">
                    <label for="pass">Contraseña:</label>
                    <input type="password" class="form-control" id="pass" name="pass" value="123456" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                </div>
            </form>
            
            <hr>
            <p class="text-center">
                <small>
                    <a href="<?php echo base_url('login'); ?>">Usar login con AJAX</a> |
                    <a href="<?php echo base_url('login/test'); ?>">Test de Login</a>
                </small>
            </p>
        </div>
    </div>
</body>
</html>
