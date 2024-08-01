$('#submit').click( function() {    
    // Initiate Variables With Form Content
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
});

function ingresar(e) {
    if (e.keyCode === 13) {
        $('#submit').click();        
    }
}

function logout (){
   $.ajax({
        type: "POST",
        url:base_url + "login/logout_ci",
        datatype:'json',        
        success : function(text){            
                 window.location.href = base_url;            
        }
    });
}