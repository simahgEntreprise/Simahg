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
                    <h1 class="page-header">Perfil</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">                                               
                        <div class="panel-body">                            
                            <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="document.getElementById('frmItem').reset();limpiar();  $('#tipo').val('C');">Nuevo</button></p>
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
        <h4 class="modal-title" id="myModalLabel">Perfil</h4>
    </div>
    <div class="modal-body">
	<form role="form" id="frmItem" enctype="multipart/form-data">
            <div class="form-group">
                <label>nombre</label>
                <input class="form-control" id="nom" style="width:50%" size="50" maxlength="50">
            </div>
            <div class="checkbox"><label><input type="checkbox" id="chkflgMovil"><strong>Permitir Movil</strong></label></div>
            <div class="form-group">
                <div class="well">
                    <h4>Configuracion de Accesos</h4>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox"><label><input type="checkbox" id="chkConf"><strong>Configuracion</strong></label></div>
                        <div class="checkbox"><label><input type="checkbox" id="chkPerfil" >Perfil</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkArea" >Area</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkCargo" >Cargo</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkTipoMant" >Tipo Mantenimiento</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkUsuario" >Usuario</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkTiempoMant" >Tiempo de Mantenimiento</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkEmpresa" >Empresa</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkGrupo" >Grupo</div>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox"><label><input type="checkbox" id="chkPrc" ><strong>Proceso</strong></label></div>
                        <div class="checkbox"><label><input type="checkbox" id="chkItems" >Items</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkMnt" >Registro de Mantenimiento</div>
                        <div class="checkbox"><label><input type="checkbox" id="chkRge" >Registro de Equipos</div>                        
                        <br>
                        <div class="checkbox"><label><input type="checkbox" id="chkRep" ><strong>Reportes</strong></label></div>
                        <div class="checkbox"><label><input type="checkbox" id="chkHojaVida" >Reporte Hoja de Vida</div>
                    </div>
                    </div> 
                    <div class="row">
                        <div class="checkbox" id="divAccMov"><label><input type="checkbox" id="chkAccMov" ><strong>Acceso Movil</strong></label></div>
                        <div class="checkbox" id="divRegEpp"><label><input type="checkbox" id="chkRegEpp" >Registro de Epp</div>
                        <div class="checkbox" id="divRegTra"><label><input type="checkbox" id="chkRegTra" >Registro de Trabajador</div>
                        <div class="checkbox" id="divRegPro"><label><input type="checkbox" id="chkRegPro" >Registro de Proyecto</div>
                        <div class="checkbox" id="divEppCar"><label><input type="checkbox" id="chkEppCar" >Enlace Epp - Cargo</div>
                        <div class="checkbox" id="divRepAsg"><label><input type="checkbox" id="chkRepAsg" >Reporte de Asignacion de Epp</div>
                    </div>
                </div>
            </div>            
        </form>
    </div>
      <div class="modal-footer">
          <input type="hidden" id="idItem" size="1" maxlength="1">
          <input type="hidden" id="tipo" size="1" maxlength="1">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCerrar">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGdr" onclick="create();">Guardar</button>
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
    <script  type="text/javascript">
    $(document).ready(function() {               
        
        $('#divAccMov').hide();
        $('#divRegEpp').hide();
        $('#divRepAsg').hide();
        
        var source =
            {                               
                url: base_url+"configuracion/perfil/getList",
                datatype: "json",
                root: 'data',
                datafields: [
                    { name: 'id' },
                    { name: 'nombre' },    
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
                  { text: 'Id', dataField: 'id', width: '30%', hidden: true},
                  { text: 'Nombre', dataField: 'nombre', width: '80%' },
                  { text: 'Accion', dataField: 'accion', width: '20%', cellsrenderer: function (row, column, value) {
                        return '<button type="button" class="btn btn-info btn-circle" data-toggle="modal" data-target="#myModal" onclick="view('+value+')"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-warning btn-circle" onclick="deleteItem('+value+')"><i class="fa fa-times"></i></button>';
                    }
                  }
                ]
            });
            $('#chkflgMovil').change(function(){
                if($('#chkflgMovil').is(':checked')){
                    $('#divAccMov').show();
                    $('#divRegEpp').show();
                    $('#divRegTra').show();
                    $('#divRegPro').show();
                    $('#divRepAsg').show();
                    $('#divEppCar').show();
                    $('#chkAccMov').prop('checked',true);
                    $('#chkRegEpp').prop('checked',true);
                    $('#chkRepAsg').prop('checked',true);
                    $('#chkEppCar').prop('checked',true);
                    $('#chkRegTra').prop('checked',true);
                    $('#chkRegPro').prop('checked',true);
                }else{
                    $('#divAccMov').hide();
                    $('#divRegEpp').hide();
                    $('#divRegTra').hide();
                    $('#divRegPro').hide();
                    $('#divRepAsg').hide();
                    $('#divEppCar').hide();
                    $('#chkAccMov').prop('checked',false);
                    $('#chkRegEpp').prop('checked',false);
                    $('#chkRepAsg').prop('checked',false);
                    $('#chkEppCar').prop('checked',false);
                    $('#chkRegTra').prop('checked',false);
                    $('#chkRegPro').prop('checked',false);
                }
            });
    });	
    function limpiar(){
        $('#chkPerfil').prop('checked', false);  
        $('#chkArea').prop('checked', false);  
        $('#chkCargo').prop('checked', false);  
        $('#chkTipoMant').prop('checked', false);  
        $('#chkUsuario').prop('checked', false);  
        $('#chkTiempoMant').prop('checked', false);  
        $('#chkEmpresa').prop('checked', false);                  
        $('#chkItems').prop('checked', false);  
        $('#chkMnt').prop('checked', false);  
        $('#chkRge').prop('checked', false);
        $('#chkPrc').prop('checked', false);
        $('#chkConf').prop('checked', false);
        $('#chkGrupo').prop('checked', false);
        $('#chkRep').prop('checked', false);
        $('#chkHojaVida').prop('checked', false);
        $('#chkAccMov').prop('checked',false);
        $('#chkRegEpp').prop('checked',false);
        $('#chkRegTra').prop('checked',false);
        $('#chkRegPro').prop('checked',false);
        $('#chkRepAsg').prop('checked', false);
        $('#chkEppCar').prop('checked', false);
    }
    function view(id){
    $('#tipo').val("U");
    $('#idItem').val(id);
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "id=" + id,
            url:base_url+"configuracion/perfil/getList",
            success : function(text){
                var data = $.parseJSON(text);                            
                var datos = data.data[0];                
                $('#nom').val(datos.nombre);                
                if(datos.flgAccesoMovil ==='1'){
                $('#chkflgMovil').prop('checked',true);
                $('#divAccMov').show();
                $('#divRegEpp').show();
                $('#divRegTra').show();
                $('#divRegPro').show();
                $('#divRepAsg').show();
                $('#divEppCar').show();
                
                }else{
                $('#chkflgMovil').prop('checked',false);        
                $('#divAccMov').hide();
                $('#divRegEpp').hide();
                $('#divRepAsg').hide();
                $('#divEppCar').hide();
                $('#divRegTra').hide();
                $('#divRegPro').hide();
                }
                
            }
        }); 
        limpiar();
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "idPerfil=" + id,
            url:base_url+"configuracion/perfilAcceso/getList",
            success : function(text){
                var data = $.parseJSON(text);                
                
                for (var i=0; i< data.recordsTotal;i++){
                    var datos = data.data[i];                    
                    
                    if (datos.idapp==='1' && datos.idopc==='0' ){ $('#chkConf').prop('checked',true);}
                    if (datos.idapp==='1' && datos.idopc==='2' ){ $('#chkPerfil').prop('checked', true);}
                    if (datos.idapp==='1' && datos.idopc==='3' ){ $('#chkArea').prop('checked', true);}
                    if (datos.idapp==='1' && datos.idopc==='7' ){ $('#chkCargo').prop('checked', true);}
                    if (datos.idapp==='1' && datos.idopc==='5' ){ $('#chkTipoMant').prop('checked', true);  }
                    if (datos.idapp==='1' && datos.idopc==='1' ){ $('#chkUsuario').prop('checked', true);}
                    if (datos.idapp==='1' && datos.idopc==='4' ){ $('#chkTiempoMant').prop('checked', true);}
                    if (datos.idapp==='1' && datos.idopc==='6' ){ $('#chkEmpresa').prop('checked', true);}
                    if (datos.idapp==='2' && datos.idopc==='0' ){ $('#chkPrc').prop('checked', true);}
                    if (datos.idapp==='2' && datos.idopc==='1' ){ $('#chkItems').prop('checked', true);}
                    if (datos.idapp==='2' && datos.idopc==='2' ){ $('#chkMnt').prop('checked', true);}
                    if (datos.idapp==='2' && datos.idopc==='3' ){ $('#chkRge').prop('checked', true);}
                    if (datos.idapp==='1' && datos.idopc==='8' ){ $('#chkGrupo').prop('checked', true);}
                    if (datos.idapp==='3' && datos.idopc==='0' ){ $('#chkRep').prop('checked', true);}
                    if (datos.idapp==='3' && datos.idopc==='1' ){ $('#chkHojaVida').prop('checked', true);}
                    if (datos.idapp==='4' && datos.idopc==='0' ){ $('#chkAccMov').prop('checked', true);}
                    if (datos.idapp==='4' && datos.idopc==='1' ){ $('#chkRegEpp').prop('checked', true);}
                    if (datos.idapp==='4' && datos.idopc==='4' ){ $('#chkRegTra').prop('checked', true);}
                    if (datos.idapp==='4' && datos.idopc==='5' ){ $('#chkRegPro').prop('checked', true);}
                    if (datos.idapp==='4' && datos.idopc==='2' ){ $('#chkRepAsg').prop('checked', true);}
                    if (datos.idapp==='4' && datos.idopc==='3' ){ $('#chkEppCar').prop('checked', true);}
                    
                }
            }
        });
        
    }
    function deleteItem(id){
        $.ajax({
            type: "POST",
            datatype:'json',
            data: "id=" + id,
            url:base_url+"configuracion/perfil/delete",
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
        
        function create(){
            var nombre = $('#nom').val();
            var idVal = $('#idItem').val();
            var tipo = $('#tipo').val();                                                                   
            if (nombre.trim()==='' ) {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Ingrese informacion requerida",
                    type:"info" 
                });
                return false;
            } else{
                jsShowWindowLoad('Espere');
                var data = new FormData();                
                data.append('id',idVal);
                data.append('nombre',nombre);                
                data.append('flgAccMov',($('#chkflgMovil').is(':checked')) ? 1 : '');                
                data.append('tipo',tipo);
                data.append('aperfil',$('#chkPerfil').is(':checked'));
                data.append('aarea',$('#chkArea').is(':checked'));
                data.append('acargo', $('#chkCargo').is(':checked'));
                data.append('atipo', $('#chkTipoMant').is(':checked')); 
                data.append('auser',$('#chkUsuario').is(':checked')); 
                data.append('atmant',$('#chkTiempoMant').is(':checked'));
                data.append('aemp', $('#chkEmpresa').is(':checked'));
                data.append('aitem',$('#chkItems').is(':checked'));
                data.append('amnt',$('#chkMnt').is(':checked'));
                data.append('arge',$('#chkRge').is(':checked'));                  
                data.append('agrup',$('#chkGrupo').is(':checked'));                
                data.append('arep',$('#chkRep').is(':checked'));
                data.append('ahoja',$('#chkHojaVida').is(':checked'));
                data.append('aregepp',$('#chkRegEpp').is(':checked'));
                data.append('arepepp',$('#chkRepAsg').is(':checked'));
                data.append('aenlepp',$('#chkEppCar').is(':checked'));
                data.append('aregtra',$('#chkRegTra').is(':checked'));
                data.append('aregpro',$('#chkRegPro').is(':checked'));
                $.ajax({
                type: "POST",
                contentType:false,
                processData:false,
                cache:false,
                data: data,
                url:base_url+"configuracion/perfil/create",
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
    </script>
</body>
</html>
