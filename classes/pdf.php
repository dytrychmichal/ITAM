<?php

require('pdf/fpdf.php');
date_default_timezone_set('Europe/Prague');

class transferPdf
{
	
	public function getTransferPdf($hw)
	{
		
		ob_start();
		$shortW=20;
		$longW=50;
		$height=10;
		$spaceCenter=30;
		$pdf = new FPDF('L','mm','A4');
		
		for($i=0 ; $i< count($hw) ; $i+=4)
		{
			$pdf->AddPage();
		
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell($shortW+$longW,$height,'HW Transfer');
				$pdf->Cell($longW,$height,'No. ' . $hw[0]['no']);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+1, $hw))
			{
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell($shortW+$longW,$height,'HW Transfer');
				$pdf->Cell($longW,$height,'No. ' . $hw[1]['no']);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial', '',  10);
				$pdf->Cell($shortW+$longW,$height,$hw[0]['hwName'], 1, 0, 'C');
				$pdf->Cell($longW,$height,$hw[0]['inv'], 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+1, $hw))
			{
				$pdf->Cell($shortW+$longW,$height,$hw[1]['hwName'], 1, 0, 'C');
				$pdf->Cell($longW,$height,$hw[1]['inv'], 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell($shortW,$height,'', 1);
				$pdf->Cell($longW,$height,'FROM', 1, 0, 'C');
				$pdf->Cell($longW,$height,'TO', 1, 0, 'C');
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+1, $hw))
			{
				$pdf->Cell($shortW,$height,'', 1);
				$pdf->Cell($longW,$height,'FROM', 1, 0, 'C');
				$pdf->Cell($longW,$height,'TO', 1, 0, 'C');
			}
			$pdf->Ln();
			
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'Name:', 1);
				$pdf->Cell($longW,$height,$hw[0]['userOld'], 1);
				$pdf->Cell($longW,$height,$hw[0]['userNew'], 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+1, $hw))
			{
				$pdf->Cell($shortW,$height,'Name:', 1);
				$pdf->Cell($longW,$height,$hw[1]['userOld'], 1);
				$pdf->Cell($longW,$height,$hw[1]['userNew'], 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'SSO:', 1);
				$pdf->Cell($longW,$height,$hw[0]['ssoOld'], 1);
				$pdf->Cell($longW,$height,$hw[0]['ssoNew'], 1);
			}
				
			$pdf->Cell($spaceCenter, $height,'');	
			if(array_key_exists($i+1, $hw))
			{
				$pdf->Cell($shortW,$height,'SSO:', 1);
				$pdf->Cell($longW,$height,$hw[1]['ssoOld'], 1);
				$pdf->Cell($longW,$height,$hw[1]['ssoNew'], 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'Signature:', 1);
				$pdf->Cell($longW,$height,'', 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+1, $hw))
			{
				$pdf->Cell($shortW,$height,'Signature:', 1);
				$pdf->Cell($longW,$height,'', 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+0, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'Date:', 1);
				$pdf->Cell($longW,$height, date('d.m.Y', time()) , 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+1, $hw))
			{
				$pdf->Cell($shortW,$height,'Date:', 1);
				$pdf->Cell($longW,$height, date('d.m.Y', time()) , 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();

			//SECOND ROW----------------------------------------------------------------------------------
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell($shortW+$longW,$height,'HW Transfer');
				$pdf->Cell($longW,$height,'No. ' . $hw[2]['no']);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+3, $hw))
			{
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell($shortW+$longW,$height,'HW Transfer');
				$pdf->Cell($longW,$height,'No. ' . $hw[3]['no']);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial', '',  10);
				$pdf->Cell($shortW+$longW,$height,$hw[2]['hwName'], 1, 0, 'C');
				$pdf->Cell($longW,$height,$hw[2]['inv'], 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+3, $hw))
			{
				$pdf->Cell($shortW+$longW,$height,$hw[3]['hwName'], 1, 0, 'C');
				$pdf->Cell($longW,$height,$hw[3]['inv'], 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell($shortW,$height,'', 1);
				$pdf->Cell($longW,$height,'FROM', 1, 0, 'C');
				$pdf->Cell($longW,$height,'TO', 1, 0, 'C');
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+3, $hw))
			{
				$pdf->Cell($shortW,$height,'', 1);
				$pdf->Cell($longW,$height,'FROM', 1, 0, 'C');
				$pdf->Cell($longW,$height,'TO', 1, 0, 'C');
			}
			$pdf->Ln();
			
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'Name:', 1);
				$pdf->Cell($longW,$height,$hw[2]['userOld'], 1);
				$pdf->Cell($longW,$height,$hw[2]['userNew'], 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+3, $hw))
			{
				$pdf->Cell($shortW,$height,'Name:', 1);
				$pdf->Cell($longW,$height,$hw[3]['userOld'], 1);
				$pdf->Cell($longW,$height,$hw[3]['userNew'], 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'SSO:', 1);
				$pdf->Cell($longW,$height,$hw[2]['ssoOld'], 1);
				$pdf->Cell($longW,$height,$hw[2]['ssoNew'], 1);
			}
				
			$pdf->Cell($spaceCenter, $height,'');	
			if(array_key_exists($i+3, $hw))
			{
				$pdf->Cell($shortW,$height,'SSO:', 1);
				$pdf->Cell($longW,$height,$hw[3]['ssoOld'], 1);
				$pdf->Cell($longW,$height,$hw[3]['ssoNew'], 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'Signature:', 1);
				$pdf->Cell($longW,$height,'', 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+3, $hw))
			{
				$pdf->Cell($shortW,$height,'Signature:', 1);
				$pdf->Cell($longW,$height,'', 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			$pdf->Ln();
			
			if(array_key_exists($i+2, $hw))
			{
				$pdf->SetFont('Arial','',10);
				$pdf->Cell($shortW,$height,'Date:', 1);
				$pdf->Cell($longW,$height, date('d.m.Y', time()) , 1);
				$pdf->Cell($longW,$height,'', 1);
			}
			
			$pdf->Cell($spaceCenter, $height,'');
			if(array_key_exists($i+3, $hw))
			{
				$pdf->Cell($shortW,$height,'Date:', 1);
				$pdf->Cell($longW,$height, date('d.m.Y', time()) , 1);
				$pdf->Cell($longW,$height,'', 1);
			}
		}
		
		$pdf->Output();
		
		
		ob_end_flush(); 
	}
}
?>