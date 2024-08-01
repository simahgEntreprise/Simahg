<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Sistema Urpi 2.0 </title>
    <script type="text/javascript"> base_url = '<?php echo base_url();?>'+'index.php/';</script>
    <script type="text/javascript"> base_url2 = '<?php echo base_url();?>'</script>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url();?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url();?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="<?php echo base_url();?>bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo base_url();?>dist/css/msgBoxLight.css" rel="stylesheet">
    <link href="<?php echo base_url();?>dist/css/fileinput.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="<?php echo base_url();?>js/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url();?>js/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxgrid.columnsresize.js"></script> 
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxgrid.filter.js"></script>        
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxpanel.js"></script>    
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqwidgets/jqxmenu.js"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header"> 
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url();?>index.php/home"><i> Sistema Urpi 2.0 </i></a>               
            </div>            
            <!-- /.navbar-top-links -->
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo base_url();?>index.php/" onclick="logout();" >
                        <i class="glyphicon glyphicon-log-out"></i>
                    </a>
                </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <?php echo $this->menu->getMenu($this->session->userdata('perfil')); ?>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Items</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">                                               
                        <div class="panel-body">                            
                            <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="document.getElementById('frmItem').reset();$('#files').fileinput('reset'); document.getElementById('list').innerHTML = [''].join(''); $('#tipo').val ('C');">Nuevo</button></p>
                            <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
                                    <div id="jqxgrid" style="width: 100%"></div>
                            </div>                                   
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
	
	<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Registros de Items</h4>
    </div>
    <div class="modal-body">
	<form role="form" id="frmItem" enctype="multipart/form-data">
            <div class="row">                
                <div class="col-md-6">
                    <div class="form-group">
                            <label>Codigo</label>
                            <input class="form-control" id="codItem" style="width:50%" size="10" maxlength="10">                                       
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                            <label>Nro Factura</label>
                            <input class="form-control" id="nroFac" style="width:50%" size="20" maxlength="20">
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-6">                                        
                    <div class="form-group">
                    <label>Nro. Serie Origen</label>
                    <input class="form-control" id="numSerie1" size="30" maxlength="30">
                    </div>                                                           
                </div>
                <div class="col-md-6">                    
                    <div class="form-group">
                    <label>Nro. Serie Dinamico</label>
                    <input class="form-control" id="numSerie2" size="30" maxlength="30">
                    </div>                                      
                </div>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="chkAct" value=""> Motor</label>
                <input class="form-control" id="motor" style="width:50%" size="10" maxlength="10">                                       
            </div>
            <div class="row">                
                <div class="col-md-6">
                    <div class="form-group">
                    <label>Modelo Origen</label>
                    <input class="form-control" id="modelo1" size="30" maxlength="30">
                    </div>                    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                    <label>Modelo Dinamico</label>
                    <input class="form-control" id="modelo2" size="30" maxlength="30">
                    </div>
                </div>
            </div>
            <div class="form-group">                
                    <label>Seleccione Archivo:</label>
                    <input type="file" id="files" name="files[]"  multiple=true data-show-preview="false"  />                    
                    <output id="list"></output>                
            </div>
            <div class="form-group">
                <label>Nombre del artículo</label>
                <input class="form-control" id="nombre" size="50" maxlength="50" required="required">
            </div>            
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                <label>Responsable</label>
                <input class="form-control" id="resp" size="100" maxlength="100" required="required">
                </div>            
                <div class="form-group">
                    
                </div>            
                
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label for="cboArea">Area</label>
                <select id="cboArea" class="form-control">                                         
                </select>                                            
                </div>            
                <div class="form-group">
                <label for="cboGrupo">Grupo</label>
                <select id="cboGrupo" class="form-control">                                         
                </select>                                            
                </div>                           
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-6">
                <div class="form-group">
                <label>Tecnico encargado</label>
                <input class="form-control" id="tec" size="100" maxlength="100" required="required">
                </div>            
                <div class="form-group">
                <label>Fecha de Ingreso</label>                                            
                <input class="form-control" type="date" id="fecIng" />	
                </div>            
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label for="cboAreaTec">Area</label>
                <select id="cboAreaTec" class="form-control">                                         
                </select>                                            
                </div>            
                <div class="form-group">
                <label>Fecha de Uso</label>
                <input class="form-control" type="datetime-local" id="fecUso" >
                </div>
                </div>
            </div>                      
            <div class="row"> 
                <div class="col-md-6">
                <label for="cboTipoMant">Tiempo Mantenimiento</label>
                <select id="cboTipoMant" class="form-control">
                </select>         
                </div>
                <div class="col-md-6">
                    <label>Cantidad</label>
                    <input class="form-control" id="cant" onKeyPress="return soloNumeros(event)" size="5" maxlength="5">
                    <input type="hidden" id="tipo" size="1" maxlength="1">
                    <input type="hidden" id="idItem" size="1" maxlength="1">
                    
                </div>
            </div>
            <div class="form-group">
                <label for="cboEstado">Estado</label>
                <select id="cboEstado" class="form-control">
                    <option value="A">Activo</option>
                    <option value="T">Retiro Temporal</option>                                        
                </select>                                            
            </div>
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCerrar">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGdr" onclick="ctrItem();">Guardar</button>
      </div>
    </div>
  </div>
</div>
	

    <!-- jQuery -->    
    <script src="<?php echo base_url();?>js/jquery.msgbox.min.js"></script>        
    
    
    <script type="text/javascript" src="<?php echo base_url();?>js/menu.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/login.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url();?>bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="<?php echo base_url();?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url();?>dist/js/sb-admin-2.js"></script>
    <script src="<?php echo base_url();?>js/fileinput.min.js"></script>        
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {             
        $("#motor").prop('disabled',true);
        $("#modelo1").prop('disabled',true);
        $("#modelo2").prop('disabled',true);
        
        var source =
            {                               
                url: base_url+"proceso/item/getListItem",
                datatype: "json",
                root: 'data',
                datafields: [
                    { name: 'id' },
                    { name: 'codigo' },                   
                    { name: 'nombre' },                   
                    { name: 'responsable' },                   
                    { name: 'fecingreso' },                   
                    { name: 'accion', type:'string' },
                ],
                pager: function (pagenum, pagesize, oldpagenum) {
                    // callback called when a page or page size is changed.
                }
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            var self = this;
            var pagerrenderer = function () {
                var element = $("<div style='margin-left: 10px; margin-top: 5px; width: 100%; height: 100%;'></div>");
                var datainfo = $("#jqxgrid").jqxGrid('getdatainformation');
                var paginginfo = datainfo.paginginformation;
                var leftButton = $("<div style='padding: 0px; float: left;'><div style='margin-left: 9px; width: 16px; height: 16px;'></div></div>");
                leftButton.find('div').addClass('jqx-icon-arrow-left');
                leftButton.width(36);
                var rightButton = $("<div style='padding: 0px; margin: 0px 3px; float: left;'><div style='margin-left: 9px; width: 16px; height: 16px;'></div></div>");
                rightButton.find('div').addClass('jqx-icon-arrow-right');
                rightButton.width(36);
                leftButton.appendTo(element);
                rightButton.appendTo(element);
                var label = $("<div style='font-size: 11px; margin: 2px 3px; font-weight: bold; float: left;'></div>");
                label.text("1-" + paginginfo.pagesize + ' de ' + datainfo.rowscount);
                label.appendTo(element);
                self.label = label;
                // update buttons states.
                var handleStates = function (event, button, className, add) {
                    button.on(event, function () {
                        if (add == true) {
                            button.find('div').addClass(className);
                        }
                        else button.find('div').removeClass(className);
                    });
                }
               
                rightButton.click(function () {
                    $("#jqxgrid").jqxGrid('gotonextpage');
                });
                leftButton.click(function () {
                    $("#jqxgrid").jqxGrid('gotoprevpage');
                });
                return element;
            }
            $("#jqxgrid").on('pagechanged', function () {
                var datainfo = $("#jqxgrid").jqxGrid('getdatainformation');
                var paginginfo = datainfo.paginginformation;
                self.label.text(1 + paginginfo.pagenum * paginginfo.pagesize + "-" + Math.min(datainfo.rowscount, (paginginfo.pagenum + 1) * paginginfo.pagesize) + ' de ' + datainfo.rowscount);
            });
            $("#jqxgrid").jqxGrid(
            {
                width: '950px',
		height: "100%",                
                source: dataAdapter,
                columnsresize: true,
                selectionmode: 'multiplerowsextended',
                pagerrenderer: pagerrenderer,
                sortable: true,
                pageable: true,                
                columns: [
                  { text: 'Id', dataField: 'id', width: '10%', hidden: true},
                  { text: 'Codigo', dataField: 'codigo', width: '10%' },
                  { text: 'Nombre', dataField: 'nombre', width: '30%' },                  
                  { text: 'Responsable', dataField: 'responsable', width: '30%' },                  
                  { text: 'Fecha de Ingreso', dataField: 'fecingreso', width: '20%' },                  
                  { text: 'Accion', dataField: 'accion', width: '10%', cellsrenderer: function (row, column, value) {
                        return '<button type="button" class="btn btn-info btn-circle" data-toggle="modal" data-target="#myModal" onclick="viewItem('+value+')"><i class="fa fa-edit"></i></button>';
                    }
                  }
                ]
            });                              
        
        $.ajax({
            type: "POST",
            datatype:'json',                
            url:base_url+"configuracion/area/getList",
            success : function(text){
                var data = $.parseJSON(text);         
                var valor = data.data;                            
                var option='<option value="0">--Seleccione--</option>'
                for(var i=0;i< valor.length;i++){
                    option=option+"<option value="+valor[i].id+">"+valor[i].nombre+"</option>";
                }
                $('#cboArea').html(option);
                $('#cboAreaTec').html(option);
            }
        });
        
        $.ajax({
            type: "POST",
            datatype:'json',                
            url:base_url+"configuracion/grupo/getList",
            success : function(text){
                var data = $.parseJSON(text);         
                var valor = data.data;                            
                var option='<option value="0">--Seleccione--</option>'
                for(var i=0;i< valor.length;i++){
                    option=option+"<option value="+valor[i].id+">"+valor[i].nombre+"</option>";
                }
                $('#cboGrupo').html(option);                
            }
        });
        
        $.ajax({
            type: "POST",
            datatype:'json',                
            url:base_url+"configuracion/tiempoMant/getList",
            success : function(text){
                var data = $.parseJSON(text);         
                var valor = data.data;
                var option='<option value="0">--Seleccione--</option>'
                for(var i=0;i<valor.length;i++){
                    option=option+"<option value="+valor[i].id+">"+valor[i].nombre+"</option>";
                }                                      
                $('#cboTipoMant').html(option);
            }
        });
    });	
    
    $('#files').fileinput({
    minFileCount:1,
    maxFileCount:3,
    allowedFileExtensions: ["jpg", "png", "gif"],
    showUpload:false    
    });
    $('#files').on('filecleared', function(event) {
    document.getElementById("list").innerHTML = [' '].join('');
        
    });
//    $('#files').on('change', function(evt) {
//        var f = this.files[0];
//         if (f.size > 8388608 || f.fileSize > 8388608)
//        {
//           //show an alert to the user
//           $.msgBox({
//                title:"Atenci&#243;n",
//                content:"Imagen muy pesada",
//                type:"info"
//            });
//
//           //reset file upload control
//           this.value = null;
//        }
//  });

    $('#chkAct').click(function(){
        var thischeck = $(this);
        if (thischeck.is(':checked')){
            $('#motor').prop('disabled',false) ;
            $('#modelo1').prop('disabled',false) ;
            $('#modelo2').prop('disabled',false) ;
        }else{
            $('#motor').prop('disabled',true) ;
            $('#modelo1').prop('disabled',true) ;
            $('#modelo2').prop('disabled',true) ;            
        }
    })
    function viewItem(id){
    $('#tipo').val("U");
    $('#idItem').val(id);
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "idItem=" + id,
            url:base_url+"proceso/item/getListItem",            
            success : function(text){
                var data = $.parseJSON(text);                            
                var datos = data.data[0];
                $('#codItem').val(datos.codigo);                
                $('#nroFac').val(datos.nroFactura);
                $('#numSerie1').val(datos.numserie);
                $('#nombre').val(datos.nombre);
                $('#cboArea').val(datos.area.id);
                $('#resp').val(datos.responsable);
                $('#fecIng').val(datos.fecingreso);                
                $('#fecUso').val(datos.fecusoitem);
                $('#cboTipoMant').val(datos.tiempmant.id);
                $('#cant').val(datos.cantmant);
                $('#numSerie2').val(datos.numseriealt);
                $('#modelo1').val(datos.modelo);
                $('#modelo2').val(datos.modeloalt);
                $('#cboGrupo').val(datos.grupo.id);
                $('#tec').val(datos.tecnico);
                $('#motor').val(datos.motor);
                $('#cboAreaTec').val(datos.areatec.id);
                $('#cboEstado').val(datos.estado);
                var arrImage = datos.imagen.split("]");
                
                var img ="";
                for (var i=0; i<arrImage.length-1;i++){
                    
                    img =img + '<img src="'+ base_url2 + arrImage[i]+' " class="file-preview-image">'
                }                
                document.getElementById("list").innerHTML = [img].join('');
                                
            }
        }); 
        
    }
    function deleteItem(id){
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "idItem=" + id,
            url:base_url+"proceso/item/delete",
            success : function(text){
                var data = $.parseJSON(text);            
                $.msgBox({
                        title:"",
                        content:data.msg,
                        type:"info"
                    });    
                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');                
            }
        });
    }
    function archivo(evt) {
                  var files = evt.target.files; // FileList object             
                  // Obtenemos la imagen del campo "file".
                  var img = "";
                  for (var i = 0; i<files.length;  i++) {
                      f = files[i]
                    //Solo admitimos imágenes.
                    if (!f.type.match('image.*')) {
                        continue;
                    }
             
                    var reader = new FileReader();
             
//                    reader.onload = (function(theFile) {
//                        return function(e) {
//                          // Insertamos la imagen
//                         document.getElementById("list").innerHTML = ['<img class="img-rounded" src="', e.target.result,'" title="', escape(theFile.name), '" width="230" height="100"/>'].join('');
//
//                        };
//                    })(f);
                    reader.readAsDataURL(f);                  
                    reader.onload = function(e){
                        var template = '<img class="img-rounded" src="'+ e.target.result+'" title="'+ escape(e.name)+ '" width="180" height="100"/>';
                            $('#list').append(template);
                    };
             

                  }
           }
        document.getElementById('files').addEventListener('change', archivo, false);
        
        
        function ctrItem(){
            var codItem = $('#codItem').val();     
            var nroFac = $('#nroFac').val();
            var num = $('#numSerie1').val();                                              
            var file = $('#files').val();
            var nom = $('#nombre').val();
            var cboAr = $('#cboArea').val();            
            var resp = $('#resp').val();
            var fecIng = $('#fecIng').val();
            var fecUso = $('#fecUso').val();
            var cboTM = $('#cboTipoMant').val();
            var cant = $('#cant').val();
            var num2 = $('#numSerie2').val();
            var modelo1 = $('#modelo1').val();
            var modelo2 = $('#modelo2').val();
            var grupo = $('#cboGrupo').val();
            var tec = $('#tec').val();
            var motor = $('#motor').val();
            var cboAr2 = $('#cboAreaTec').val();
            var tipo = $('#tipo').val();
            var id = $('#idItem').val();
            var est = $('#cboEstado').val();
            
            
            if (nom.trim()==='' || nroFac.trim() === '' || resp.trim()==='' || num.trim() === '' || fecIng.trim()==='' || fecUso.trim()==='' || cboTM==='' || cant <= 0) {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Ingrese informacion requerida",
                    type:"info"
                });
                return false;
            } else{
                jsShowWindowLoad('Espere');
                var data = new FormData();
                var inputFileImage = document.getElementById('files');
                var file = inputFileImage.files;
                var nfile;

                for (var i= 0; i < file.length; i++) {
                    nfile = file[i];                                        
                        data.append("archivo"+i, nfile);
                }
                
//                data.append("archivo", nfile);
                data.append('cod',codItem);
                data.append('nroFac',nroFac);
                data.append('id',id);
                data.append('numserie1',num);                                
                data.append('nom',nom);
                data.append('area',cboAr);                
                data.append('resp',resp);                
                data.append('fecing',fecIng);
                data.append('fecuso',fecUso);
                data.append('tmpmant',cboTM);
                data.append('cant',cant);
                data.append('motor',motor);
                data.append('numserie2',num2);                
                data.append('modelo1',modelo1);
                data.append('modelo2',modelo2);
                data.append('grupo',grupo);
                data.append('area2',cboAr2);
                data.append('tec',tec);
                data.append('tipo',tipo);
                data.append('est', est);
                                
                $.ajax({
                type: "POST",
                contentType:false,
                processData:false,
                cache:false,
                data: data,
                url:base_url+"proceso/item/ctrItem",
                xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
                },
                success : function(text){
                    var data = $.parseJSON(text);                                        
                    if (data.success){
                        $('#btnCerrar').click();
                        $.msgBox({
                            title:"",
                            content:data.msg,
                            type:"info"
                        });
                        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                        jsRemoveWindowLoad();
                    }else{
                        $.msgBox({
                            title:"",
                            content:data.msg,
                            type:"alert"
                        });
                        jsRemoveWindowLoad();
                    }
                        

                }
                })
            }            
        }               
        function soloNumeros(e){
            var key = window.Event ? e.which : e.keyCode
            return (key >= 48 && key <= 57)
        }
    </script>
</body>
</html>

