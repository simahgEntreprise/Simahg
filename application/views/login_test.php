<!DOCTYPE html>
<html>
<head>
    <title>Login Test Simple</title>
    <script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <h1>Login Test Simple</h1>
    
    <form id="loginForm">
        <p>Usuario: <input type="text" id="usuario" value="admin"></p>
        <p>Contraseña: <input type="password" id="password" value="123456"></p>
        <p><button type="submit">Login</button></p>
    </form>
    
    <div id="resultado"></div>
    <div id="debug"></div>
    
    <script>
        var base_url = "<?php echo base_url(); ?>";
        console.log("Base URL:", base_url);
        
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            
            var usuario = $('#usuario').val();
            var password = $('#password').val();
            
            $('#resultado').html('Enviando...');
            $('#debug').html('');
            
            console.log("Enviando datos:", {log: usuario, pass: password});
            
            $.ajax({
                type: "POST",
                url: base_url + "login/getLogin",
                dataType: 'json',
                data: {
                    log: usuario,
                    pass: password
                },
                success: function(data) {
                    console.log("Respuesta:", data);
                    $('#debug').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
                    
                    if (data.authenticated === true) {
                        $('#resultado').html('✅ Login exitoso! Redirigiendo...');
                        setTimeout(function() {
                            console.log("Redirigiendo a:", base_url + "home");
                            window.location.href = base_url + "home";
                        }, 2000);
                    } else {
                        $('#resultado').html('❌ Error: ' + data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", error);
                    console.error("Status:", status);
                    console.error("Response Text:", xhr.responseText);
                    $('#resultado').html('❌ Error de conexión');
                    $('#debug').html('<pre>Error: ' + error + '\nStatus: ' + status + '\nResponse: ' + xhr.responseText + '</pre>');
                }
            });
        });
    </script>
</body>
</html>
