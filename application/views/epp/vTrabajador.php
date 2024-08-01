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
                    <h1 class="page-header">Trabajador</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">                                               
                        <div class="panel-body">                            
                            <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="document.getElementById('frmItem').reset();$('#files').fileinput('reset'); $('#tipo').val ('C');">Nuevo</button>                               
                            </p>
                            
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
        <h4 class="modal-title" id="myModalLabel">Registro de Trabajadores</h4>
    </div>
    <div class="modal-body">
	<form role="form" id="frmItem" enctype="multipart/form-data">            
            <input type="hidden" id="id" size="1" maxlength="1">
            <input type="hidden" id="tipo" size="1" maxlength="1">            
            <div class="row">                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombres</label>
                        <input class="form-control" id="txtNombre" size="50" maxlength="50" required="required">
                    </div>
                </div>
                <div class="col-md-6">
                        <div class="form-group">
                            <label>Apellidos</label>
                            <input class="form-control" id="txtApellido" size="50" maxlength="50" required="required">
                        </div>
                </div>
            </div>
            <div class="form-group">
                <label>Dni</label>
                <input class="form-control" id="txtDni" size="15" maxlength="15" required="required" onkeypress="return soloNumeros(event)">
            </div>                                          
            <div class="row">                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cboArea">Area</label>
                        <select id="cboArea" class="form-control">                                         
                        </select>                                            
                    </div>                              
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cboCargo">Cargo</label>
                        <select id="cboCargo" class="form-control">                                         
                        </select>                                            
                    </div>
                </div>
            </div>            
            <div class="form-group">
                <label for="cboEstado">Estado</label>
                <select id="cboEstado" class="form-control">
                    <option value="A">Activo</option>
                    <option value="D">No Activo</option>                                        
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
        var source =
            {                               
                url: base_url+"epp/trabajador/getList",
                datatype: "json",
                root: 'data',
                datafields: [
                    { name: 'id' },
                    { name: 'nombre'  },                   
                    { name: 'apellidos'},                                       
                    { name: 'cargo', map:'idcargo>nombre' },                             
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
                  { text: 'Nombre', dataField: 'nombre', width: '30%' },       
                  { text: 'apellidos', dataField: 'apellidos', width: '30%' },
                  { text: 'Cargo', dataField: 'cargo', width: '30%' },                  
                  { text: 'Accion', dataField: 'accion', width: '10%', cellsrenderer: function (row, column, value) {
                        return '<button type="button" class="btn btn-info btn-circle" data-toggle="modal" data-target="#myModal" onclick="viewItem('+value+')"><i class="fa fa-edit"></i></button>';
                    }
                  }
                ]
            });                                             
        
        $.ajax({
            type: "POST",
            datatype:'json',                
            url:base_url+"configuracion/cargo/getList",
            success : function(text){
                var data = $.parseJSON(text);         
                var valor = data.data;                            
                var option='<option value="0">--Seleccione--</option>'
                for(var i=0;i< valor.length;i++){
                    option=option+"<option value="+valor[i].id+">"+valor[i].nombre +"</option>";
                }                
                $('#cboCargo').html(option);                
            }
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
                    option=option+"<option value="+valor[i].id+">"+valor[i].nombre +"</option>";
                }                
                $('#cboArea').html(option);                
            }
        });
    });	

    function viewItem(id){
    $('#tipo').val("U");
    $('#id').val(id);
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "id=" + id,
            url:base_url+"epp/trabajador/getList",            
            success : function(text){
                var data = $.parseJSON(text);                            
                var datos = data.data[0];
                $('#id').val(datos.id);                
                $('#cboArea').val(datos.idarea.id);
                $('#cboCargo').val(datos.idcargo.id);
                $('#cboProyecto').val(datos.idproy.id);
                $('#nombre').val(datos.nombre);
                $('#apellidos').val(datos.apellidos);                
                $('#dni').val(datos.dni);                
                $('#cboEstado').val(datos.estado);                                
            }
        }); 
        
    }
    function deleteItem(id){
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "idItem=" + id,
            url:base_url+"epp/registroEpp/delete",
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
        
        function ctrItem(){            
            var id = $('#id').val();
            var tipo = $('#tipo').val();                        
            var area = $('#cboArea').val();
            var cargo= $('#cboCargo').val();
            var proy = $('#cboProyecto').val();
            var nom  = $('#txtNombre').val();
            var ape  = $('#txtApellido').val();
            var dni  = $('#txtDni').val();                
            var est  = $('#cboEstado').val();                                
                        
            if (area==='0' || cargo==='0' || proy==='0' || nom.trim()==='' || ape.trim() === '' || dni.trim() === '' || est === '0') {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Ingrese informacion requerida",
                    type:"info"
                });
                return false;
            } else{
                jsShowWindowLoad('Espere');
                var data = new FormData();                                                
                data.append('cargo',cargo);
                data.append('area',area);
                data.append('proy',proy);
                data.append('nombre',nom);           
                data.append('apellidos',ape);
                data.append('dni',dni);                           
                data.append('tipo',tipo);           
                data.append('id',id);                       
                data.append('est', est);                
                                
                $.ajax({
                type: "POST",
                contentType:false,
                processData:false,
                cache:false,
                data: data,
                url:base_url+"epp/trabajador/create",
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

