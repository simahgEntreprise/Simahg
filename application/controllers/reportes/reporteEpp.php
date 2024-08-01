<?php
require_once APPPATH.'controllers/BaseController.php';
class reporteEpp extends BaseController{
    public function __construct() {
	parent::__construct();	
//        if($this->session->userdata('is_logued_in') != TRUE) {
//        redirect('login');
//        }
    }
    function index(){
        $this->load->view('reporte/vRepHojaVida');
    }
    
    function printReport(){
//        try {
            $this->load->library('pdfEpp');
        // create new PDF document
$pdf = new pdfEpp(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SimaHg');
$pdf->SetTitle('reporte'.$this->input->get('id'));

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 011', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

// column titles
//$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
$header=array('TRABAJADOR','CARGO','DNI','IMPLEMENTO','FECHA');   

            $id = $this->input->get('id');
         $this->load->model('mapper/epp/mReporte','mprRep');
            
            $contr = array();
        
            $contr['id'] =  $id ;

            $dmnItem= $this->mprRep->finder($contr);                        

                    $dmnRep1 =  $dmnItem->getResults()[0];

//            $pdf=new PdfTable();
//            $pdf->AddPage();
            $pdf->Ln(15);            
            $pdf->Cell(60,5,"",0,0);            
            $pdf->Cell(30,5,"MES:","LRTB",0);            
           $mes = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SETIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");           
            $pdf->Cell(35,5,$mes[date("m") -1 ],"LRTB",0);            
            $pdf->Cell(35,5,"FECHA:","LRTB",0);
            $pdf->Cell(25,5,Date("d-m-Y"),"LRTB",0);
            $pdf->Ln(5);
            $pdf->Cell(60,5,"AREA / OBRA / PROYECTO:","LRTB",0);            
            $pdf->Cell(125,5,utf8_decode($dmnRep1->getResponsable()),"LRTB",0);
            $pdf->ln(10);
            
            
            $pdf->ln(5);
            $pdf->SetFont("");
      
//$data= $this->mprRep->finder($contr);
$datarr = array();
$i = 0;
foreach ($dmnItem->getResults() as $row) {
    $i++;
$arr = array();
$arr['trabajador'] = utf8_decode($row->getNombre())." ".utf8_decode($row->getApellido());
$arr['cargo'] = utf8_decode($row->getCargoTrab());
$arr['dni'] = $row->getDniTrab();
$arr['implemento'] = utf8_decode($row->getTipo());
$arr['fecha'] = $row->getFecingreso();



$datarr[$i] = $arr;

}

// print colored table
$pdf->ColoredTable($header, $datarr);
$pdf->ln(10);
$pdf->Cell(100,5,"RESUMEN GENERAL","LRTB",0,"C");            
$pdf->ln(5);
$header1=array('IMPLEMENTO','CANTIDAD');   
$summary = array();
foreach($dmnItem->getResults() as $key => $row){    
    $this->findInSummary($row->getTipo(),$summary);
}
$cant=0;
foreach ($summary as $key => $row){
    $cant += $row['cantidad'];
}
$pdf->tablaResumen($header1, $summary);
$pdf->ln(1);
$pdf->cell(60,5, "TOTAL","LRB",0,"C");
$pdf->cell(40,5, $cant, "LRB", 0);
// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('example_011.pdf', 'I');

        
    }
    
    function findInSummary($implemento,&$summary){
    foreach($summary as $key => $row){
      if($row['implemento'] == $implemento){
          $cantidad = $row['cantidad'] + 1;
          $summary[$key]['cantidad'] = $cantidad;
          return;
      }
    }
    $summary[] = array(
      'implemento' => $implemento,
      'cantidad' => 1
    );

}
}
