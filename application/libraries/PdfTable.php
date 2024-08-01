<?php
//require APPPATH.'libraries/fpdf.php';
require_once APPPATH."/third_party/fpdf.php";
class PdfTable extends FPDF
{

    function vcell($c_width,$c_height,$x_axis,$text){
        $w_w=$c_height/3;
        $w_w_1=$w_w+2;
        $w_w1=$w_w+$w_w+$w_w+3;
        $len=strlen($text);// check the length of the cell and splits the text into 7 character each and saves in a array 
        if($len>35){
        $w_text=str_split($text,7);
        $this->SetX($x_axis);
        $this->Cell($c_width,$w_w_1,$w_text[0],'','','');
        $this->SetX($x_axis);
        $this->Cell($c_width,$w_w1,$w_text[1],'','','');
        $this->SetX($x_axis);
        $this->Cell($c_width,$c_height,'','LTRB',0,'L',0);
        }
        else{
            $this->SetX($x_axis);
            $this->Cell($c_width,$c_height,$text,'LTRB',0,'L',0);}
    }
 

	function FancyTable($header,$data,$w)
	{		
		//Cabecera
		$this->SetFont('Arial','B',10);
                $x_axis=$this->getx();
                for($i=0;$i<count($header);$i++){
                    $this->vcell($w[$i],7,$x_axis,$header[$i]);// pass all values inside the cell 
                    $x_axis=$this->getx();// now get current pdf x axis value
                }
//			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		$x_axis=$this->getx();
		$this->SetFont('Arial','',10);
		//Datos
		$fill=false;                
                
		foreach($data->getResults() as $row)	{
//                    $this->Cell(20,5,$row->getFecha(),'LR',0,'L');
//                    $this->MultiCell(20,5,$row->getFecha(),0);
                    $this->vcell(20,10,$x_axis,$row->getFecha());
                    $x_axis=$this->getx();

                    if($row->getTipo() !=null){
                        $dmnTipo = $row->mapper()->getTipo();
//                        $this->Cell(35,5,$dmnTipo->getNombre(),'LR',0,'L');
//                        $this->MultiCell(35,5,$dmnTipo->getNombre(),0);
                        $this->vcell(35,10,$x_axis,$dmnTipo->getNombre());
                        $x_axis=$this->getx();

                    }else{
//                        $this->Cell(35,5,'','LR',0,'L');
                        $this->vcell(35,10,$x_axis,'');
                        $x_axis=$this->getx();
                    }
                    
                    
//                    $this->Cell(30,5,$row->getLugar(),'LR',0,'L');
//                    $this->MultiCell( 30, 5, $row->getLugar(), 0);
                    $this->vcell(30,10,$x_axis,$row->getLugar());
                    $x_axis=$this->getx();
//                    $this->Cell(80,5,  utf8_decode($row->getObservacion()),'LR',0,'L');
//                    $this->MultiCell( 80, 5, utf8_decode($row->getObservacion()), 0);
                    $this->vcell(80,10,$x_axis,utf8_decode($row->getObservacion()));
                    $x_axis=$this->getx();
//                    $this->Cell(30,5,utf8_decode($row->getResponsable()),'LR',0,'L');
//                    $this->MultiCell(30,5,utf8_decode($row->getResponsable()),0);
                    $this->vcell(30,10,$x_axis,utf8_decode($row->getResponsable()));
                    $x_axis=$this->getx();
			$this->Ln(10);
			$fill=!$fill;
		}
                $this->Ln();
		$this->Cell(array_sum($w),0,'','T');
                
	}
        
                
        function header(){
            $this->Image('images/logo.jpg',10,8,20,13,'JPG');
            $this->SetFont("Arial","",15);
            $this->cell(25,10,"",0);
            $this->cell(125,10,"FORMATO DE HOJA DE VIDA DE EQUIPOS",0,0);
            $this->SetFont("Arial","",7);
            $this->cell(40,5,"CODIGO: HDG-FR-111",0,0);
            $this->ln(5);
            $this->cell(150,5,"",0);
            $this->cell(40,5,"FECHA DE VIGENCIA: 17/11/2015",0,0);
            $this->ln(5);
            $this->cell(150,5,"",0);
            $this->cell(40,5,"VERSION: 00",0,0);
            $this->SetFont("");
        }                   
}