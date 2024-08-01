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
                    <h1 class="page-header">Reporte Hoja de Vida de Equipos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">                                               
                        <div class="panel-body">
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
                url: base_url+"proceso/item/getListItem",
                datatype: "json",
                root: 'data',
                datafields: [
                    { name: 'id' },                    
                    { name: "codigo"},
                    { name: "nombre"},
                    { name: "responsable" },
                    { name: "fecingreso" },                    
                    { name: 'accion', type:'string' },
                    { name: "lugar" }
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
                  { text: 'Elemento', dataField: 'nombre', width: '20%' },
                  { text: 'Responsable', dataField: 'responsable', width: '20%' },
                  { text: 'Fecha Ingreso', dataField: 'fecingreso', width: '20%' },                  
                  { text: 'Ubicacion', dataField: 'lugar', width: '20%' },                  
                  { text: 'Accion', dataField: 'accion', width: '10%', cellsrenderer: function (row, column, value) {
                        return '<button type="button" class="btn btn-info btn-circle" data-toggle="modal" data-target="#myModal" onclick="viewItem('+value+')"><i class="fa fa-edit"></i></button>';
                    }
                  }
                ]
            });                                              
    });
    
    function viewItem(id){
        window.open("<?php echo base_url();?>index.php/reportes/hojaVidaItem/printReport?id="+id);
        
    }
    
    </script>
</body>
</html>
