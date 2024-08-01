<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ATUQTECH - Control de Mantenimientos</title>
    
    <script type="text/javascript"> base_url2 = '<?php echo base_url();?>'</script>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>dist/css/msgBoxLight.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url();?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo base_url();?>dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url();?>bower_components/morrisjs/morris.css" rel="stylesheet">

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
            <!-- /.navbar-top-links -->
            <div class="navbar-header">                
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url();?>index.php/home"><i>Atuq Technologies E.I.R.L</i></a>               
            </div>            
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo base_url();?>index.php/" onclick="logout();" >
                        <i class="glyphicon glyphicon-log-out"></i>
                    </a>
                </li>
            </ul>
            <div class="navbar-default sidebar"  role="navigation">                
                <div class="sidebar-nav navbar-collapse">                     
                    <?php echo $this->menu->getMenu($this->session->userdata('perfil')); ?>
                 </div>
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Control de Mantenimiento</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="panel-body">
                            <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
                                    <div id="jqxgrid" style="width: 100%"></div>
                            </div>                                   
                        </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <input type="hidden" id="idItem" size="1" maxlength="1">
    <input type="hidden" id="tipo" size="1" maxlength="1">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick ="limpiar();"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Registro de Mantenimientos</h4>
    </div>
    <div class="modal-body">
	<form role="form" id="frmItem">
            <div class="form-group">
                    <label for="cboElement">Elemento</label>
                    <select id="cboElement" class="form-control"></select>                                            
            </div>
            <div class="form-group">
                    <label for="cboTipoMant">Tipo</label>
                    <select id="cboTipoMant" class="form-control"></select>                                            
            </div>
            
            <div class="form-group">
                <label>Responsable</label>
                <input class="form-control" id="respon">                   
            </div>
            
            <div class="form-group">
                <label for="cboEmpresa">Empresa</label>
                <select id="cboEmpresa" class="form-control"></select>                                            
            </div>
            <div class="row">
                <div class="col-md-6">
                <label>Fecha de Ingreso</label>
                <input class="form-control" type="date" id="fecIng" />	
                </div>
                <div class="col-md-6">
                <label>Fecha de Salida</label>		
                <input class="form-control" type="date" id="fecSal" />	
                </div>
            </div>
            <div class="form-group">
                <label>Lugar</label>
                <input class="form-control" id="lugar" size="100" maxlength="100">
            </div>
            <div class="form-group">
                <label>Horas de Trabajo</label>
                <input class="form-control" id="horTrb">                
            </div>
            <div class="form-group">
                <label>Observacion</label>
                <textarea class="form-control" id ="obs">                </textarea>
            </div>
            <div class="form-group">                                            
                <label for="cboEstado">Estado</label>
                <select id="cboEstado" class="form-control">
                    <option value="P">Pendiente</option>
                    <option value="T">Terminado</option>
                    <option value="E">Eliminado</option>
                </select>                                            
            </div>                      
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id ="btnCerrar" onclick ="limpiar();">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="create();">Guardar</button>
      </div>
    </div>
  </div>
</div>


    <!-- jQuery -->    
    <script type="text/javascript"> base_url = '<?php echo base_url();?>'+'index.php/';</script>
    <script src="<?php echo base_url();?>js/jquery.msgbox.min.js"></script>        
    
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url();?>bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url();?>dist/js/sb-admin-2.js"></script>    
    <!-- DataTables JavaScript -->
    <script src="<?php echo base_url();?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>    
    <script type="text/javascript" src="<?php echo base_url();?>js/menu.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/login.js"></script>
    <script>        
    $(document).ready(function() {
        cargaData();
        
        $.ajax({
            type: "POST",
            datatype:'json',                
            url:base_url+"configuracion/tipoMant/getList",
            success : function(text){
                var data = $.parseJSON(text);         
                var valor = data.data;                            
                var option='<option value="0">--Seleccione--</option>'
                for(var i=0;i< valor.length;i++){
                    option=option+"<option value="+valor[i].id+">"+valor[i].nombre+"</option>";
                }
                $('#cboTipoMant').html(option);
            }
        });
    
        $.ajax({
                type: "POST",
                datatype:'json',                
                url:base_url+"proceso/item/getListItem",
                success : function(text){
                    var data = $.parseJSON(text);         
                    var valor = data.data;                            
                    var option='<option value="0">--Seleccione--</option>'
                    for(var i=0;i< valor.length;i++){
                        option=option+"<option value="+valor[i].id+">"+valor[i].nombre+"</option>";
                    }
                    $('#cboElement').html(option);
                }
        });
    
        $.ajax({
                type: "POST",
                datatype:'json',                
                url:base_url+"configuracion/empresa/getList",
                success : function(text){
                    var data = $.parseJSON(text);         
                    var valor = data.data;                            
                    var option='<option value="0" selectec >--Seleccione--</option>'
                    for(var i=0;i< valor.length;i++){
                        option=option+"<option value="+valor[i].id+">"+valor[i].nombre+"</option>";
                    }
                    $('#cboEmpresa').html(option);
                }
        });
    });	
//    setInterval("cargaData()", 3000);
    function cargaData(){
        var source =
            {                               
                url: base_url+"proceso/control/getControl",
                datatype: "json",
                root: 'data',
                datafields: [
                    { name: 'accion2' },
                    { name: 'codigo' },                   
                    { name: 'nombre' },                   
                    { name: 'numserie' },                   
                    { name: 'area', map:'area>nombre' },                   
                    { name: 'fecusoitem' },                   
                    { name: 'responsable' },
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
//                leftButton.jqxButton({ theme: theme });
                var rightButton = $("<div style='padding: 0px; margin: 0px 3px; float: left;'><div style='margin-left: 9px; width: 16px; height: 16px;'></div></div>");
                rightButton.find('div').addClass('jqx-icon-arrow-right');
                rightButton.width(36);
//                rightButton.jqxButton({ theme: theme });
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
            };
            $("#jqxgrid").on('pagechanged', function () {
                var datainfo = $("#jqxgrid").jqxGrid('getdatainformation');
                var paginginfo = datainfo.paginginformation;
            });
            $("#jqxgrid").jqxGrid(
            {
                width: '980px',
		height: "100%",                
                source: dataAdapter,
                columnsresize: true,
                selectionmode: 'multiplerowsextended',
                pagerrenderer: pagerrenderer,
                sortable: true,
                pageable: true,                
                columns: [
                  { text: '', dataField: 'accion2', width: '5%', cellsrenderer: function (row, column, value) {
                        var data = value.split("-");                        
                        var img='';
                        if (data[1]==="3"){
                          img='<button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#myModal" onclick="view('+data[0]+')"></button>';
                        }else if(data[1]==="2"){
                          img='<button type="button" data-toggle="modal" class="btn btn-warning btn-circle" data-target="#myModal" onclick="view('+data[0]+')"></button>';
                        }else{
                          img='<button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#myModal" onclick="view('+data[0]+')"></button>';
                        }
                      return img;                        
                    }
                  },
                  { text: 'Codigo', dataField: 'codigo', width: '10%' },
                  { text: 'Nombre', dataField: 'nombre', width: '20%' },                  
                  { text: 'Numero Serie', dataField: 'numserie', width: '20%' },
                  { text: 'Area', dataField: 'area', width: '15%' },                  
                  { text: 'Fecha Caducidad', dataField: 'fecusoitem', width: '15%' },
                  { text: 'Responsable', dataField: 'responsable', width: '15%' },                  
                ]
            });                                      
    }
    
    
    function view(id){    
    $('#tipo').val("C");    
    $('#cboElement').val(id);    
    $('#cboElement').prop("disabled","disabled");
    }
    
    function create(){                
        var idMant = $('#idItem').val()
            var idEle = $('#cboElement').val();
            var tipMant = $('#cboTipoMant').val();
            var resp = $('#respon').val();
            var emp = $('#cboEmpresa').val();
            var fecIng = $('#fecIng').val();
            var fecSal = $('#fecSal').val();
            var hora = $('#horTrb').val();
            var obser = $('#obs').val();
            var est = $('#cboEstado').val();
            var tipo = $('#tipo').val();
            var lug = $('#lugar').val();
            
            if (idEle ==='0' ) {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Seleccione un elemento",
                    type:"info" 
                });
                return false;
            } 
            if (tipMant ==='0' ) {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Seleccione un tipo de mantenimiento",
                    type:"info" 
                });
                return false;
            }     
            if (fecIng ==='' ) {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Seleccione una fecha de ingreso",
                    type:"info" 
                });
                return false;
            }
            if (fecSal ==='' ) {
                $.msgBox({
                    title:"Atenci&#243;n",
                    content:"Seleccione una fecha de salida",
                    type:"info" 
                });
                return false;
            }
            jsShowWindowLoad('Espere');
                var data = new FormData();                
                data.append('idItem',idEle);
                data.append('estado',est);
                data.append('fecIng',fecIng);
                data.append('fecSal',fecSal);                
                data.append('horas', hora);
                data.append('id',idMant);
                data.append('idEmp',emp);
                data.append('idTipo',tipMant);
                data.append('observacion',obser);
                data.append('responsable',resp);
                data.append('lugar',lug);
                data.append('tipo',tipo);
                
                $.ajax({
                type: "POST",
                contentType:false,
                processData:false,
                cache:false,
                data: data,
                url:base_url+"proceso/mantenimiento/create",
                success : function(text){
                    console.log(text);
                    var data = $.parseJSON(text);                                        
                    if (data.success){
                        $('#btnCerrar').click();
                        $.msgBox({
                            title:"",
                            content:data.msg,
                            type:"info"
                        });
                        $('#dtgItem').DataTable().ajax.reload();
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
        function limpiar(){
            $('#frmItem')[0].reset();
        }
    </script>        

</body>

</html>