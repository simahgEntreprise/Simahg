<?php
require_once APPPATH.'controllers/BaseController.php';
class rptAsignacionEpp extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    function index(){
        $this->load->view('epp/vReporteEpp');
    }
    function getList(){
        $this->load->model('mapper/epp/mReporte','mprReporte');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprReporte->finder($contr);        
        foreach($dmnResponse->getResults() as $dmnRep){
            $segundos=  strtotime($dmnRep->getFecfin()) - strtotime('now');        
            $diferencia_dias=intval($segundos/60/60/8);

            if ($diferencia_dias<=5){
                        $dmnRep->setValor("1");
            }elseif ($diferencia_dias<=15){
                        $dmnRep->setValor("2");                    
            }else{
                        $dmnRep->setValor("3");
            }
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerRptEpp',$data);
    }
    
    function printReport(){
//        try {
            $this->load->library('pdf');
        // create new PDF document
$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SimaSg');
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
$header=array('FECHA','MANTENIMIENTO','LUGAR','DETALLE DEL MANTENIMIENTO','TECNICO');   

            $id = $this->input->get('id');
         $this->load->model('mapper/proceso/mItem','mprItem');
            $this->load->model('mapper/reportes/mHojaVida','mprHoja');
            $contr = array();
        
            $contr['id'] =  $id ;
            
            $dmnItem= $this->mprItem->find($contr);            
            $dmnItem->Mapper()->getIdarea();
            $dmnItem->Mapper()->getGrupo();
            $dmnItem->Mapper()->getAreaTec();
                    
                       
//            $pdf=new PdfTable();
//            $pdf->AddPage();
            $pdf->Ln(15);
//            $pdf->SetFont("Arial","",9);            
            $pdf->Cell(90,5,"RESPONSABLE DE EQUIPO:".utf8_decode($dmnItem->getResponsable()),"LRT",0);
            $pdf->cell(10,5,"",0);
            $pdf->Cell(90,5,"TECNICO ENCARGADO:".utf8_decode($dmnItem->getTecnico()),"LRT",0);
            $pdf->Ln(5);            
            $pdf->Cell(45,5,"AREA:".($dmnItem->getIdarea() != null ? $dmnItem->getIdarea()->getNombre() : ''),"LRLT",0);
            $pdf->Cell(45,5,"GRUPO:".($dmnItem->getGrupo() != null ? $dmnItem->getGrupo()->getNombre() : ''),"LRLT",0);
            $pdf->cell(10,5,"",0);
            $pdf->Cell(90,5,"AREA:".($dmnItem->getAreaTec() != null ? $dmnItem->getAreaTec()->getNombre() : ''),"LRLT",0);
            $pdf->Ln(5);
            $pdf->Cell(90,5,"EQUIPO:".$dmnItem->getNombre(),"LRLT",0);
            $pdf->cell(10,5,"",0);
            $pdf->Cell(90,5,"MOTOR:".($dmnItem->getMotor() == "" ? "NO" : $dmnItem->getMotor()),"LRLT",0);
            $pdf->Ln(5);            
            $pdf->Cell(90,5,"NUMERO DE SERIE:".($dmnItem->getNumserie() == "" ? "NO" :$dmnItem->getNumserie()) ,"LRLT",0);
            $pdf->cell(10,5,"",0);
            $pdf->Cell(90,5,"NUMERO DE SERIE:".($dmnItem->getNumserie2() == "" ? "NO" :$dmnItem->getNumserie2()),"LRLT",0);
            $pdf->Ln(5);
            $pdf->Cell(90,5,"MODELO:".($dmnItem->getModelo1() =="" ? "NO" : $dmnItem->getModelo1()),"LRLT",0);
            $pdf->cell(10,5,"",0);
            $pdf->Cell(90,5,"MODELO:".($dmnItem->getModelo2() =="" ? "NO" : $dmnItem->getModelo2()),"LRLTB",0);
            $pdf->Ln(5);
            $pdf->Cell(90,5,"CODIGO INTERNO:".$dmnItem->getCodigo(),"LRLTB",0);
  
            $arrImg = $dmnItem->getImagen();
            $arrImg2 = explode(']', $arrImg);                        
            if(count($arrImg2)<= 1) {
                $pdf->Ln(15);
                $pdf->cell(65,5,"",0);
            }else{
                $pdf->Ln(50);
                $pdf->cell(65,5,"",0);
            }
            switch (count($arrImg2)-1 ) {
                case 1:
                    $pdf->Image($arrImg2[0],90,75,40,40);
                    break;
                case 2:
                    $pdf->Image($arrImg2[0],40,75,40,40);
                    $pdf->Image($arrImg2[1],115,75,40,40);
                    break;
                case 3:
                    $pdf->Image($arrImg2[0],20,75,40,40);
                    $pdf->Image($arrImg2[1],80,75,40,40);
                    $pdf->Image($arrImg2[2],140,75,40,40);
                default:
                    break;
            }            
            
            $pdf->Cell(50,0,'INTERVENCIONES REALIZADAS AL EQUIPO', 0, false, 'C', 0);            
            $pdf->ln(5);
            $pdf->SetFont("");
      
$data= $this->mprHoja->finder($contr);
$datarr = array();
$i = 0;
foreach ($data->getResults() as $row) {
    $i++;
$arr = array();
$arr['fecha'] = $row->getFecha();
if($row->getTipo() !=null){
    $dmnTipo = $row->mapper()->getTipo();
    $arr['tipo'] = $dmnTipo->getNombre();
}else{
    $arr['tipo'] = "";
}
$arr['lugar'] = utf8_decode($row->getLugar());
$arr['obser'] = utf8_decode($row->getObservacion());
$arr['resp'] = utf8_decode($row->getResponsable());
$datarr[$i] = $arr;

}

// print colored table
$pdf->ColoredTable($header, $datarr);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('example_011.pdf', 'I');

        
    }
}
