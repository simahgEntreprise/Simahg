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
    <script>
        function ingreso(e) {
            if (e.keyCode === 13) {
                var name = $("#email").val();
    var email = $("#password").val();
 
    $.ajax({
        type: "POST",
        url:base_url + "login/getLogin",
        datatype:'json',
        data: "log=" + name + "&pass=" + email,
        success : function(text){
            var data = $.parseJSON(text);
            if(data.authenticated === true){
                 window.location.href = base_url+ "home";
            }else{
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Usuario y/o Contraseña incorrectos",
                    type:"info"
                });
                $("#email").val("");
                $("#password").val("");
            }
        }
    });  
            }
        }
        
    </script>
    

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">                    
                    <div class="panel-body">
                        <form role="form" id="flogin">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="<?php echo base_url();?>images/logo.png" width="100%" >
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Usuario" id="email" type="email" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Contraseña" id="password" type="password" value="" onkeypress="return ingreso(event);">
                                    </div>                                
                                    <input class="form-control" placeholder="token" id="token" type="hidden" value="">
                                    <!-- Change this to a button or input when using this as a form -->
                                    <input type="button" value="Ingresar" id="submit" class="btn btn-lg btn-success btn-block" onclick="" />
                                </fieldset>
                                </div>
                            </div>                             
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript"> base_url = '<?php echo base_url();?>'+'index.php/';</script>
    <script type="text/javascript"> base_url2 = '<?php echo base_url();?>';</script>
    <!-- jQuery -->
    <script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery.msgbox.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url();?>bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url();?>dist/js/sb-admin-2.js"></script>   
    <script type="text/javascript" src="<?php echo base_url();?>js/login.js"></script>

</body>

</html>
