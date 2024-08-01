<?php
// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');
require_once APPPATH."/third_party/tcpdf/tcpdf.php";

// extend TCPF with custom functions
class pdf extends TCPDF {
    
    public function Header() {          
        $this->Image('images/logo.jpg',10,8,20,13,'JPG');
            $this->SetFont("","",15);
            $this->cell(25,10,"",0);
            $this->cell(125,10,"FORMATO DE HOJA DE VIDA DE EQUIPOS",0,0);
            $this->SetFont("","",7);
            $this->cell(40,5,"CODIGO: HDG-FR-111",0,0);
            $this->ln(5);
            $this->cell(150,5,"",0);
            $this->cell(40,5,"FECHA DE VIGENCIA: 17/11/2015",0,0);
            $this->ln(5);
            $this->cell(150,5,"",0);
            $this->cell(40,5,"VERSION: 00",0,0);
            $this->SetFont("");
    }

	// Colored table
	public function ColoredTable($header,$data) {
		 // Colors, line width and bold font
//        $this->SetFillColor(255, 0, 0);
//        $this->SetTextColor(255);
//        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.1);
        $this->SetFont('', 'B', 10);
        // Header
        $w=array(25,30,40,60,30);  
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 0);
        }
        $this->Ln();
        // Color and font restoration
//        $this->SetFillColor(224, 235, 255);
//        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
         
        $fill = 0;
         $w=array("fecha" => 25,"tipo" =>30,"lugar"=>40,"obser" => 60,"resp" =>30);
        foreach($data as $row) {
             
            $cellcount = array();
            //write text first
            $startX = $this->GetX();
            $startY = $this->GetY();
            //draw cells and record maximum cellcount
            //cell height is 6 and width is 80            
            foreach ($row as $key => $column):                
                 $cellcount[] = $this->MultiCell($w[$key],6,  utf8_encode($column),0,'L',0,0);
            endforeach;
         
            $this->SetXY($startX,$startY);
  
            //now do borders and fill
            //cell height is 6 times the max number of cells
         
            $maxnocells = max($cellcount);
         
            foreach ($row as $key => $column):
                 $this->MultiCell($w[$key],$maxnocells * 6,'','LRT','L',0,0);
            endforeach;
     
        $this->Ln();
            // fill equals not fill (flip/flop)
            $fill=!$fill;
         if (($startY + 6) >= 250) {
            $this->AddPage();
            $startY = 35; // should be your top margin
        }		
    
        }
         
        // draw bottom row border
        $this->Cell(array_sum($w), 0, '', 'T');

	}
}