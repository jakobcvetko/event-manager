  <?php
 
	$user_id = $_SESSION['login_id'];
 
  $napaka = false;
  	if(isset($_POST['dodaj_dogodek'])) {
		
		$naziv = mysql_real_escape_string($_POST['naziv_dog']);
		
		$query = "SELECT `naziv` FROM `dogodki` WHERE `organizator_id` = '$user_id'";
		$sql = mysql_query($query);
		
		while($row = mysql_fetch_assoc($sql)) {
			if($naziv == $row['naziv']) {
				$napaka = true;
				break;
			}
		}
		
		if(isset($_POST['tip_dog']) && $napaka == false) {
			
			//Osnovni podatki o dogodku
			$kraj = mysql_real_escape_string($_POST['kraj_dog']);
			$drzava = mysql_real_escape_string($_POST['drzava_dog']);
			
			//Tip dogodka
			$tip = mysql_real_escape_string($_POST['tip_dog']);
			if($tip == 1) { //Enkratni dogodek
				$datum = mysql_real_escape_string($_POST['datum_dog']); //01.01.2012
				$ura = mysql_real_escape_string($_POST['ura_dog']);
				
				if(isdate($datum) && istime($ura)) {
					//V bazo vnesemo enkratni dogodek
					
					$query = "INSERT INTO `dogodki` (`organizator_id`, `naziv`, `kraj`, `drzava`) VALUES ('$user_id', '$naziv', '$kraj', '$drzava')";
					$sql = mysql_query($query) or die(mysql_error());
					$dogodek_id = mysql_insert_id();
					
					$datum = explode(".", mysql_real_escape_string($_POST['datum_dog'])); //01.01.2012
					$ura = $ura.":00"; //Dodamo sekunde, da lahko zapišemo v bazo
					
					$query = "INSERT INTO `termini_dog` (`dogodek_id`, `datum`, `cas`) VALUES ('$dogodek_id', FROM_UNIXTIME('".mktime(0, 0, 0, $datum[1], $datum[0], $datum[2])."'), '$ura')";
					$sql = mysql_query($query) or die(mysql_error());
					
				} else {
					$napaka = true;
				}
				
			} else if($tip == 2) { //Konstantno periodični dogodek
				$datum_zac = mysql_real_escape_string($_POST['datum_zacetka_per_dog']);
				$datum_kon = mysql_real_escape_string($_POST['datum_konca_per_dog']);
				
				if(isdate($datum_zac) && isdate($datum_kon)) {
					
					$query = "INSERT INTO `dogodki` (`organizator_id`, `naziv`, `kraj`, `drzava`) VALUES ('$user_id', '$naziv', '$kraj', '$drzava')";
					$sql = mysql_query($query) or die(mysql_error());
					$dogodek_id = mysql_insert_id();
					
					$datum_zac = explode(".", $datum_zac);
					$datum_kon = explode(".", $datum_kon);
					
					$d_z = mktime(0, 0, 0, $datum_zac[1], $datum_zac[0], $datum_zac[2]);
					$d_k = mktime(0, 0, 0, $datum_kon[1], $datum_kon[0], $datum_kon[2]);
					
					if(isset($_POST['wdan'])) { //Obkljukan vsaj 1 dan
					
						$dnevi = $_POST['wdan'];
						
						for($i=0; $d_k > $d_z; $i++) {
							$d_z = mktime(0, 0, 0, $datum_zac[1], $datum_zac[0]+$i, $datum_zac[2]);
							$day = date("N", $d_z);
							if(in_array($day, $dnevi)) { //Ce smo obkljukali ta dan
								$ura = $_POST[$day.'_ura'];
								if(istime($ura)) {
									$ura = $ura.":00";
									
									$query = "INSERT INTO `termini_dog` (`dogodek_id`, `datum`, `cas`) VALUES ('$dogodek_id', FROM_UNIXTIME('".$d_z."'), '$ura')";
									$sql = mysql_query($query) or die(mysql_error());
								} else {
									$napaka = true;
								}
							}
						}
						
					} else { //Izbrana Perioda
						
						$perioda = mysql_real_escape_string($_POST['per_dog']);
						$ura = mysql_real_escape_string($_POST['per_dog_ura']);
						if(istime($ura)) {
							$ura = $ura.":00";
							
							for($i=0; $d_k > $d_z; $i++) {
								$d_z = mktime(0, 0, 0, $datum_zac[1], $datum_zac[0]+($i*$perioda), $datum_zac[2]);
								
								$query = "INSERT INTO `termini_dog` (`dogodek_id`, `datum`, `cas`) VALUES ('$dogodek_id', FROM_UNIXTIME('".$d_z."'), '$ura')";
								$sql = mysql_query($query) or die(mysql_error());
								
							}
						} else {
							$napaka = true;
						}
					}
				} else {
					$napaka = true;
				}
				
				
			} else if($tip == 3) { //Raznoliki periodični dogodek
				$n = 0;
				for($i = 1; isset($_POST['termin_'.$i.'_datum']); $i++) {
					if(isdate($_POST['termin_'.$i.'_datum']) && istime($_POST['termin_'.$i.'_ura'])) {
						$termini[$n]['datum'] = explode(".", $_POST['termin_'.$i.'_datum']);
						$termini[$n]['ura'] = $_POST['termin_'.$i.'_ura'];
						$n++;
					}
				}
				if($n > 0) {
					
					$query = "INSERT INTO `dogodki` (`organizator_id`, `naziv`, `kraj`, `drzava`) VALUES ('$user_id', '$naziv', '$kraj', '$drzava')";
					$sql = mysql_query($query) or die(mysql_error());
					$dogodek_id = mysql_insert_id();
					
					foreach($termini as $termin) {
						$dan = mktime(0, 0, 0, $termin['datum'][1], $termin['datum'][0], $termin['datum'][2]);
						$ura = $termin['ura'].":00";
						
						$query = "INSERT INTO `termini_dog` (`dogodek_id`, `datum`, `cas`) VALUES ('$dogodek_id', FROM_UNIXTIME('".$dan."'), '$ura')";
						$sql = mysql_query($query) or die(mysql_error());
					}
				} else {
					$napaka = true;
				}
			} else {
				$napaka = true;
			}
		} else {
			$napaka = true;
		}
	}
  
  ?>
  <div class="mainbar">
    <div class="article">
      <h2><span>Ustvarjanje Dogodka</span></h2>
      <div class="clr"></div>
      <img id="dogodek_visual" class="" src="images/dogodki/none.jpg" width="560px" height="200px" alt="Dogodek Visual" />
      	<form action="vnos_dogodka" method="post" class="form_style_x">
        <table id="vnos_dogodka" class="dogodek_table" style="padding-left: 50px;">
            <tr>
                <th>Naziv:</th>
                <td><input name="naziv_dog" type="text" class="dynamic_input empty" value="Naziv"  /></td>
            </tr>
            <tr>
                <th>Tip dogodka:</th>
                <td>
                	<select id="tip_dog" name="tip_dog" style="width: 100%;">
                    	<option selected="selected" disabled="disabled">Izberite</option>
                        <option value="1">Enkratni dogodek</option>
                    	<option value="2">Periodični dogodek s konstantno periodo</option>
                    	<option value="3">Periodični dogodek z raznolikimi termini</option>
                    </select>
                </td>
            </tr>
            <!-- Enkratni tip dogodka -->
            <tr class="enkratni_dog tip_dogodka">
                <th>Datum:</th>
                <td><input name="datum_dog" type="text" value="Kliknite tukaj za datum" class="dynamic_input empty datum_dog" /></td>
            </tr>
            <tr class="enkratni_dog tip_dogodka">
                <th>Ura:</th>
                <td><input name="ura_dog" type="text" class="dynamic_input empty" value="00:00"  /></td>
            </tr>
            <!------------------------->
            <!-- Konstantni periodični tip dogodka -->
            <tr class="per_dog tip_dogodka">
                <th>Datum začetka:</th>
                <td><input id="per_dog_datum_zacetka" class="datum_dog dynamic_input empty" name="datum_zacetka_per_dog" type="text" value="Kliknite za datum..." /></td>
            </tr>
            <tr class="per_dog tip_dogodka">
                <th>Datum konca:</th>
                <td><input id="per_dog_datum_konca" class="datum_dog dynamic_input empty" name="datum_konca_per_dog" type="text" value="Kliknite za datum..." /></td>
            </tr>
            <tr class="per_dog tip_dogodka">
                <th>Perioda(v dnevih):</th>
                <td><input name="per_dog" type="text" value="perioda" class="dynamic_input empty" /></td>
            </tr>
            <tr class="per_dog tip_dogodka">
                <th>Ura:</th>
                <td><input name="per_dog_ura" type="text" value="00:00" class="dynamic_input empty" /></td>
            </tr>
            <tr class="per_dog tip_dogodka">
                <th>Perioda(v tednu):<br /><br />Upoštevana bo samo<br />ena vrsta periode!</th>
                <td>
                	PO <input name="wdan[]" value="1" type="checkbox" /><input type="text" name="1_ura" value="00:00" class="dynamic_input empty" /><br />
                    TO <input name="wdan[]" value="2" type="checkbox" /><input type="text" name="2_ura" value="00:00" class="dynamic_input empty" /><br />
                    SR <input name="wdan[]" value="3" type="checkbox" /><input type="text" name="3_ura" value="00:00" class="dynamic_input empty" /><br />
                    ČE <input name="wdan[]" value="4" type="checkbox" /><input type="text" name="4_ura" value="00:00" class="dynamic_input empty" /><br />
                    PE <input name="wdan[]" value="5" type="checkbox" /><input type="text" name="5_ura" value="00:00" class="dynamic_input empty" /><br />
                    SO <input name="wdan[]" value="6" type="checkbox" /><input type="text" name="6_ura" value="00:00" class="dynamic_input empty" /><br />
                    NE <input name="wdan[]" value="7" type="checkbox" /><input type="text" name="7_ura" value="00:00" class="dynamic_input empty" /><br />
                </td>
            </tr>
            <!------------------------->
            <!-- Periodični dogodek z raznolikimi termini -->
            <tr class="rper_dog tip_dogodka">
                <th>Termin 1:</th>
                <td><input name="termin_1_datum" type="text" class="dynamic_input empty datum_dog" value="kliknite za datum"  /><br />
                	<input name="termin_1_ura" type="text" class="dynamic_input empty" value="00:00"  />
                </td>
            </tr>
            <tr class="rper_dog tip_dogodka">
                <th>Termin 2:</th>
                <td><input name="termin_2_datum" type="text" class="dynamic_input empty datum_dog" value="kliknite za datum"  /><br />
                	<input name="termin_2_ura" type="text" class="dynamic_input empty" value="00:00"  />
                </td>
            </tr>
            <tr class="rper_dog tip_dogodka">
                <th>Termin 3:</th>
                <td><input name="termin_3_datum" type="text" class="dynamic_input empty datum_dog" value="kliknite za datum"  /><br />
                	<input name="termin_3_ura" type="text" class="dynamic_input empty" value="00:00"  />
                </td>
            </tr>
            <tr class="rper_dog tip_dogodka">
                <td colspan="2"><input id="dodaj_termin" type="button" value="Dodaj Termin" /></td>
            </tr>
            <!---------------------------------------------->
            <tr>
                <th>Kraj:</th>
                <td><input name="kraj_dog" type="text" class="dynamic_input empty" value="Kraj"  /></td>
            </tr>
            <tr>
                <th>Država:</th>
                <td><input name="drzava_dog" type="text" class="dynamic_input empty" value="Drzava"  /></td>
            </tr>
            <tr>
            	<td colspan="2" align="center"><input name="dodaj_dogodek" type="submit" value="Dodaj Dogodek" /></td>
            </tr>
        </table>
        </form>
      <?php if(isset($napaka) && $napaka == true) { ?>
		  <h3><font color="red">Prišlo je do napake!</font></h3>
	  <?php } ?>
    </div>
    
  </div>
  
  
  <script type="text/javascript">
  		$("#kat_dog").click(function() {
			$(this).removeClass("empty");
		});
  		$("#kat_dog").change(function(e) {
			var img = $("#kat_dog option:selected").val();
			$("#dogodek_visual").attr("src", "images/dogodki/"+img+".jpg");
		});
		$(".datum_dog").datepicker({
			dateFormat: 'dd.mm.yy',
			minDate: 0
		}).change(function() {
			$(this).removeClass("empty");
		});
  		$("#tip_dog").change(function(e) {
			var tip = $("#tip_dog option:selected").val();
			//alert(tip);
			if(tip == 1) { //Enolicni dogodek
				$(".tip_dogodka").hide();
				$(".enkratni_dog").show();
			} else if(tip == 2) { //Konstantni periodični dogodek
				$(".tip_dogodka").hide();
				$(".per_dog").show();
			} else { //Raznoliki periodični dogodek
				$(".tip_dogodka").hide();
				$(".rper_dog").show();
			}
		});
		$("#per_dog_datum_zacetka").change(function() {
			var date = $(this).val().split(".");
			//alert(date);
			$( "#per_dog_datum_konca" ).datepicker( "option", "minDate", new Date(parseInt(date[2]), parseInt(date[1])-1, parseInt(date[0])+1) );
		});
		
		var c = 4;
		$("#dodaj_termin").click(function(){
			var row = "<tr class='rper_dog tip_dogodka'> \
							<th>Termin "+c+":</th> \
							<td><input name='termin_"+c+"_datum' type='text' class='dynamic_input empty datum_dog' value='kliknite za datum'  /><br /> \
								<input name='termin_"+c+"_ura' type='text' class='dynamic_input empty' value='00:00'  /> \
							</td> \
						</tr>";
			$("#vnos_dogodka tr:last").prev("tr").prev("tr").prev("tr").before(row);
			$(".rper_dog").show();			
			$(".datum_dog").datepicker({
				dateFormat: 'dd.mm.yy',
				minDate: 0
			}).change(function() {
				$(this).removeClass("empty");
			});
			c++;
		});
  </script>
  