<?php

session_start();
$user_id = $_SESSION['login_id'];
include("../includes/mysql_connect.php");

ob_start(); ?>
<style type="text/css">
	h1 {
		color: red;

	}
	table {
		width:600px;
	}
	table tr th, table tr td {
		border: none;
		text-align: left;
	}
	em {
		color: #aaa;
	}
</style>
<?php

	$sql_dog = mysql_query("SELECT * FROM `dogodki` WHERE organizator_id = '$user_id'");
	if(mysql_num_rows($sql_dog) == 0) { ?>
		<h2>Ni dogodkov za prikaz.</h2>
	<?php } else {
	while($dog = mysql_fetch_assoc($sql_dog)) { ?>
    	<h2><?php echo $dog['naziv'].", ".$dog['kraj'].", ".$dog['drzava']; ?></h2>
		<table>
        	<?php
			$termini_sql = mysql_query("SELECT *, UNIX_TIMESTAMP(datum) AS 'phpdate' FROM termini_dog WHERE dogodek_id = '".$dog['id']."'");
			if(mysql_num_rows($termini_sql) == 0) { ?>
                <tr>
                    <td colspan="3"><em>Ni najdenih terminov za dogodek.</em></td>
                </tr>
            <?php } else {
				for($i=1; $ter = mysql_fetch_assoc($termini_sql); $i++) {	?>
                    <tr>
                        <td colspan="3">Termin <?php echo $i; ?>: <?php echo date("d. M Y", $ter['phpdate']); ?> ob <?php echo substr($ter['cas'], 0, 5); ?></td>
                    </tr>
                    <?php
					$sql_os=mysql_query("SELECT o.* FROM osebe o, udelezba u WHERE u.termin_id = '".$ter['id']."' AND u.oseba_id = o.id");
                    if(mysql_num_rows($sql_os) == 0) { ?>
                        <tr>
                            <td></td>
                            <td colspan="2"><em>Ni prijavljenih na termin.</em></td>
                        </tr>
                    <?php } else {
                    while($os = mysql_fetch_assoc($sql_os)) { ?>
                        <tr>
                            <td></td>
                            <td><ul><li><?php echo $os['ime']." ".$os['priimek']; ?></li></ul></td>
                            <?php
							$sql_rez=mysql_query("SELECT * FROM rezultati WHERE termin_id = '".$ter['id']."' AND oseba_id = '".$os['id']."'");
							if(mysql_num_rows($sql_rez) == 0) { ?>
								<td><em>Ni rezultatov</em></td>
							</tr>
							<?php } else {
							$first = true;
							while($rez = mysql_fetch_assoc($sql_rez)) {
									if($first) { ?>
									<td><?php echo $rez['naziv']; ?>: <strong><?php echo $rez['rezultat']; ?></strong></td>
								</tr>
								<?php $first = false;
								} else { ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $rez['naziv']; ?>: <strong><?php echo $rez['rezultat']; ?></strong></td>
                                    </tr>
                               <?php  }
                              }
                           }
	             		}
				  	 } ?>

                	<tr>
                    	<td colspan="3" height="20px"></td>
                    </tr>
				  <?php }
			} ?>
		</table>
    <?php }
	}

$test= ob_get_contents();
ob_end_clean();

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2010-08-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("event_manager.png", 70, "EVENTMANAGER", "FOR EVERY NEED");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(15, 35, 15);
$pdf->SetHeaderMargin(15);
$pdf->SetFooterMargin(15);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();


// Set some content to print
$html = $test;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+