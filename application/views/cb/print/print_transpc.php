<?php

		
			require('fpdf/tanpapage.php');
					include_once( APPPATH."libraries/translate_currency.php"); 
			extract(PopulateForm());
			$pdf=new PDF('P','mm','A4');
			
						///'$data = $this->db->query("sp_cetakrecvoucherall2 '".$id."'")
							// ->result();
							 
							// 	$rows = $this->db->query("sp_cetakrecvoucherall '".$id."'")
							// ->row();
			//var_dump($data);
			
			$session_id = $this->UserLogin->isLogin();
			$pt = $session_id['id_pt'];
			$data_pt = $this->mstmodel->get_nama_pt($pt);
			$nama_pt = $data_pt['ket'];
							 
			$pdf->SetMargins(2,10,2);
			$pdf->AliasNbPages();	
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',12);
			$pdf->setFillColor(222,222,222);
			#HEAD
			#HEADER CONTENT
			//$pdf->Image(site_url().'assets/img/thewave.png',4,8,20);	
				
			#CETAK TANGGAL
				#$tgl  = date("d-m-Y");
			#TANGGAL CETAK
				
			#	$pdf->Cell(10,4,$tgl,0,0,'L');
			
				#Header
			#$pdf->Image(site_url().'assets/img/thewave.png',10,10,40);	
			$pdf->SetX(50);
				
			// Start diatas tabel
			
			
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(100,5,'PT. Bakrie Swasakti Utama',10,0,'L');
			//$pdf->SetFont('Arial','',7);
			//$pdf->Cell(20,5,'Voucher No.',10,0,'L');
			//$pdf->Cell(2,5,':',10,0,'L');
			//$pdf->Cell(30,5.5,'$rows->doc_no',10,0,'L');
			$pdf->Ln(4);
			
			$pdf->SetFont('Arial','',7);
			$pdf->SetX(50);
			$pdf->Cell(100,5,'Epiwalk Suites Lt. 6',10,0,'L');
			//$pdf->SetFont('Arial','',7);
			//$pdf->Cell(20,5,'Voucher Date',10,0,'L');
			//$pdf->Cell(2,5,':',10,0,'L');
			//$pdf->Cell(30,5.5,'indo_date($rows->doc_date)',10,0,'L');
			$pdf->Ln(4);
			$pdf->SetFont('Arial','',7);
			$pdf->SetX(50);
			$pdf->Cell(100,5,'Gedung Epiwalk Rasuna Epicentrum',10,0,'L');
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(25,5,'',10,0,'L');
			$pdf->Cell(2,5,'',10,0,'L');
			$pdf->Cell(30,5,'',10,0,'L');
			$pdf->Ln(4);
			
			$pdf->SetX(50);
			$pdf->Cell(100,5,'',10,0,'L');
			$pdf->SetFont('Arial','',7);
				
			
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetX(85);
			$pdf->Cell(110,5,'PETTY CASH',10,0,'L');
			//$pdf->SetFont('Arial','',6);
			// $pdf->Cell(25,5,'AP No.',10,0,'L');
			// $pdf->Cell(2,5,':',10,0,'L');
			// $pdf->SetFont('Arial','B',6);
			// $pdf->Cell(30,4,$rows->inv_no,1,0,'L');
			$pdf->Ln(15);
			
			$pdf->SetFont('Arial','',6);
			
			$pdf->SetX(10);
			$pdf->Cell(20,5,'No Voucher',10,0,'L');
			$pdf->Cell(4,5,':',10,0,'L');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(55,4,$pettycash_row->sub_claim_no,1,0,'L');
			$pdf->Ln(5);
			
			$pdf->SetX(10);
			$pdf->Cell(20,5,'BANK',10,0,'L');
			$pdf->Cell(4,5,':',10,0,'L');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(55,4,'Bank Pengelola EPRJ - 1',1,0,'L');
			//$pdf->Cell(42,4,'',10,0,'L');
			//$pdf->Cell(25,5,'No. Cheque',10,0,'L');
			//$pdf->Cell(2,5,':',10,0,'L');
			//$pdf->SetFont('Arial','',6);
			//$pdf->Cell(30,4,'$rows->slipno',1,0,'L');
			$pdf->Ln(5);
			
			//$pdf->SetX(10);
			//$pdf->SetFont('Arial','',6);
			//$pdf->Cell(20,5,'Paid to',10,0,'L');
			//$pdf->Cell(4,5,':',10,0,'L');
			//$pdf->SetFont('Arial','B',6);
			//$pdf->Cell(59,4,'$rows->from',1,0,'L');
			// $pdf->SetFont('Arial','',6);
			// $pdf->Cell(42,4,'',10,0,'L');
			// $pdf->Cell(25,5,'AP Due Date',10,0,'L');
			// $pdf->Cell(2,5,':',10,0,'L');
			// $pdf->SetFont('Arial','',6);
			// $pdf->Cell(30,4,$rows->doc_date,1,0,'L');
			// $pdf->Ln(5);
			
			// $pdf->SetX(10);
			// $pdf->SetFont('Arial','',6);
			// $pdf->Cell(20,5,'Address',10,0,'L');
			// $pdf->Cell(4,5,':',10,0,'L');
			// $pdf->Cell(158,12,$rows->alamat,1,0,'L');
			// $pdf->Ln(13);
			
			$totdb_h = 0;
			$totcr_h = 0;;
			
			foreach($pettycash as $row){	
			
				$totdb_h = $totdb_h + $row->debet;
				$totcr_h = $totcr_h + $row->credit;
			
			}
			
			$pdf->SetX(10);
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(20,5,'Amount (IDR)',10,0,'L');
			$pdf->Cell(4,5,':',10,0,'L');
			$pdf->Cell(55,5,number_format($totcr_h),1,0,'L');
			$pdf->Ln(6);
			
			//$t = number_format($rows->trx_amt);
			
			$pdf->SetX(10);
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(20,5,'Says',10,0,'L');
			$pdf->Cell(4,5,':',10,0,'L');
			$pdf->Cell(158,12,ucwords(toRupiah($totcr_h)).' Rupiah',1,0,'L');
			$pdf->Ln(15);
			
			//$pdf->SetX(10);
			//$pdf->SetFont('Arial','',6);
			//$pdf->Cell(20,5,'Remark',10,0,'L');
			//$pdf->Cell(4,5,':',10,0,'L');
			//$pdf->Cell(158,12,'',1,0,'L');
			//	$pdf->Ln(17);
			
			
			
			
			
			
			
			#start Tabel
			$pdf->SetX(10);
		
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(8,4,'No',1,0,'C',1);
			$pdf->Cell(26,4,'Tanggal',1,0,'C',1);
			$pdf->Cell(113,4,'Transaksi',1,0,'C',1);
			//$pdf->Cell(35,4,'DEBET',1,0,'C',1);
			$pdf->Cell(35,4,'CREDIT',1,0,'C',1);
			$pdf->Ln(4);
			$no = 1;
			$totdb = 0;
            $totcr = 0;
			
			//for($i = 1;$i <= 27; $i++){
			foreach($pettycash as $row){	
			$totdb = $totdb + $row->debet;
            $totcr = $totcr + $row->credit;
			
			
			$pdf->SetX(10);
			$pdf->Cell(8,4,$no,1,0,'C',0);
			$pdf->Cell(26,4,indo_date($row->claim_date),1,0,'L',0);
			$pdf->Cell(113,4,$row->petty_desc,1,0,'L',0);
			//$pdf->Cell(35,4,number_format($row->credit),1,0,'R',0);
			$pdf->Cell(35,4,number_format($row->credit),1,0,'R',0);
			$pdf->Ln(4);	
			$no++;	
				
			}	
			
			$pdf->SetX(10);
					$pdf->Cell(8,4,'',10,0,'C',0);
        			$pdf->Cell(26,4,'',10,0,'L',0);
                    $pdf->SetFont('Arial','B',6);
        			$pdf->Cell(113,4,'Total',10,0,'R',0);
        			//$pdf->Cell(35,4,number_format($totcr),1,0,'R',0);
        			$pdf->Cell(35,4,number_format($totcr),1,0,'R',0);
			
			$pdf->Ln(20);		
			$pdf->SetX(15);
			$pdf->SetFont('Arial','',9);
		
			
			$pdf->SetX(10);
			$pdf->Cell(60.6,10,'Prepared By',1,0,'C',0);
			$pdf->Cell(60.6,5,'Checked By',1,0,'C',0);
			$pdf->Cell(60.6,5,'Approved By',1,0,'C',0);
			
			$pdf->Ln(5);
			
			$pdf->SetX(10);
			$pdf->Cell(60.6,10,'',10,0,'C',0);
			$pdf->Cell(60.6,5,'Staff Finance',1,0,'C',0);
			$pdf->Cell(60.6,5,'Building Manager',1,0,'C',0);
			/*
			$pdf->Cell(36.4,10,'',10,0,'C',0);
			$pdf->Cell(36.4,5,'FINANCE',1,0,'C',0);
			$pdf->Cell(36.4,5,'ACCOUNTING',1,0,'C',0);
			$pdf->Cell(36.4,10,'',10,0,'C',0);		
			$pdf->Cell(36.4,10,'',10,0,'C',0);
			*/
			$pdf->Ln(5);
			
			$pdf->SetX(10);
			$pdf->Cell(60.6,30,'',1,0,'C',0);
			$pdf->Cell(60.6,30,'',1,0,'C',0);
			$pdf->Cell(60.6,30,'',1,0,'C',0);
			
			$pdf->Ln(30);
			
			$pdf->SetX(10);
			$pdf->Cell(60.6,5,'Date. ',1,0,'L',0);
			$pdf->Cell(60.6,5,'Date. ',1,0,'L',0);
			$pdf->Cell(60.6,5,'Date. ',1,0,'L',0);
					
			$pdf->Ln(25);
		
		
	
	
	
				$pdf->SetFont('Arial','',6);
				$pdf->SetX(180);
				$pdf->Cell(10,4,'Print Date',0,0,'L',0);	
				$pdf->Cell(2,4,':',4,0,'L');
				$pdf->Cell(2,4,date("d-m-Y"),4,0,'L');
			$pdf->Output("hasil.pdf","I");
			
			
			// $pdf->Ln(7);		
			// $pdf->SetX(15);
			// $pdf->SetFont('Arial','',5);
		
			
			// $pdf->SetX(10);
			// $pdf->Cell(36.4,8,'Prepared By',1,0,'C',0);
			// $pdf->Cell(72.8,4,'Checked By',1,0,'C',0);
			// $pdf->Cell(72.8,8,'Verified By',1,0,'C',0);
			
			// $pdf->Ln(4);
			
			// $pdf->SetX(10);
			// $pdf->Cell(36.4,8,'',10,0,'C',0);
			// $pdf->Cell(36.4,4,'ACCOUNTING',1,0,'C',0);
			// $pdf->Cell(36.4,4,'BUDGET CONTROL',1,0,'C',0);
			// $pdf->Cell(36.4,8,'',10,0,'C',0);		
			// $pdf->Cell(36.4,8,'',10,0,'C',0);
			// $pdf->Ln(4);
			
			// $pdf->SetX(10);
			// $pdf->Cell(36.4,15,'',1,0,'C',0);
			// $pdf->Cell(36.4,15,'',1,0,'C',0);
			// $pdf->Cell(36.4,15,'',1,0,'C',0);
			// $pdf->Cell(36.4,15,'',1,0,'C',0);		
			// $pdf->Cell(36.4,15,'',1,0,'C',0);
			// $pdf->Ln(15);
			
			// $pdf->SetX(10);
			// $pdf->Cell(36.4,4,'Date. ',1,0,'L',0);
			// $pdf->Cell(36.4,4,'Date. ',1,0,'L',0);
			// $pdf->Cell(36.4,4,'Date. ',1,0,'L',0);
			// $pdf->Cell(36.4,4,'Date. ',1,0,'L',0);		
			// $pdf->Cell(36.4,4,'Date. ',1,0,'L',0);			
			// $pdf->Ln(25);
	
	
	
	
				// $pdf->SetFont('Arial','',6);
				// $pdf->SetX(180);
				// $pdf->Cell(10,4,'Print Date',0,0,'L',0);	
				// $pdf->Cell(2,4,':',4,0,'L');
				// $pdf->Cell(2,4,date("Y-m-d"),4,0,'L');
			// $pdf->Output("hasil.pdf","I");

