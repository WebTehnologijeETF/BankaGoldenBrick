<!DOCTYPE html>
<html>
	<head>
		<title>GoldenBrick | Kontakt</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="static/css/stil.css">
        <link rel="icon" type="image/x-icon" href="static/images/icon.ico">
        <script src="static/js/validacija.js"></script>
		<script src="static/js/meni.js"></script>
        <script src="static/js/loadAjax.js"></script>
        <script type="text/javascript">
            function resetuj(){
                var ulazi = document.kontaktForma.getElementsByTagName("input");
                for(var i = 0; i < ulazi.length; i++){
                    if(ulazi[i].getAttribute("type") == "submit" || ulazi[i].getAttribute("type") == "reset"
                    || ulazi[i].getAttribute("type") == "button" || ulazi[i].getAttribute("type") == "range")
                        continue;
                    ulazi[i].removeAttribute("value");
                }
                document.getElementsByName("poruka")[0].innerHTML = "";
                document.kontaktForma.usluga.setAttribute("value", "1");
            }
            function posaljiMail() {
                document.kontaktForma.slanje.setAttribute("value", "1");
                document.kontaktForma.submit();
            }
        </script>
	</head>
	<body>
		<div class="okvir">
			<div class="zaglavlje">
				<div class="meni">
					<ul>
						<li>
							<a href="index.php"  class="logoSlika"></a>
						</li>
						<li>
							<a href="index.php"  class="logo"><span class="spanLogo">G</span>olden<span class="spanLogo">B</span>rick</a>
						</li>
						<li><a href="index.php">Naslovnica</a></li>
						<li class="padajuci"><a href="savings.html">Štednja</a>
							<ul>
								<li><a href="savings1.html" class="ajaxLoaderLink meniLink">A Vista</a></li>
								<li><a href="savings2.html" class="ajaxLoaderLink meniLink">Dječija štednja</a></li>
							</ul>
						</li>
                        <li><a href="services.html">Usluge</a></li>
						<li><a href="about.html">O nama</a></li>
						<li class="fokus"><a href="contact.php">Kontakt</a></li>
						<li id="search">
							<form method="get">
								<input type="text" name="search_text" id="search_text" placeholder="Pretraži"/>
								<input type="button" name="search_button" id="search_button" value=" ">
							</form>
						</li>
					</ul>
				</div>
			</div>
            <div class="sjena"></div>
			<div class="sadrzaj">
                <div class="lijevo">
                    <div class="social_feed">
                        <h4>Pratite nas</h4>
                        <div class="social_feed_content">
                            <a href="http://facebook.com" target="_blank"><img src="static/images/facebook.png" alt="facebook"></a>
                            <a href="http://twitter.com" target="_blank"><img src="static/images/twitter.png" alt="twitter"></a>
                            <a href="http://plus.google.com" target="_blank"><img src="static/images/google+.png" alt="google plus"></a>
                            <a href="http://youtube.com" target="_blank"><img src="static/images/youtube.png" alt="youtube"></a>
                            <a href="http://pinterest.com" target="_blank"><img src="static/images/pinterest.png" alt="pinterest"></a>
                        </div>
                    </div>
                    <div class="kontakt_feed">
                        <h4>Kontakt</h4>
                        <div class="feed_content">
                            <p><b>GoldenBrick Banka</b></p>
                            <p><b>Bosna i Hercegovina</b></p>
                            <br>
                            <p>Zmaja od Bosne, 4</p>
                            <p>71 000 Sarajevo</p>
                            <p>Bosna i Hercegovina</p>
                            <br>
                            <p><b>GoldenBrick direct info</b></p>
                            <br>
                            <p><b>+387 33 00 01 11</b></p>
                            <br>
                            <p><b>E-mail</b></p>
                            <p><span class="p_minimize">info.gbbih@goldenbrick.ba</span></p>
                            <br>
                            <p><b>Faks</b></p>
                            <p><b>+387 33 00 01 12</b></p>
                        </div>
                    </div>
                </div>
                <?php
                function ispisiFormuSaGreskom($greska){
                    echo '<div class="kontakt">'."\r\n"
                        .'<h3>&nbsp;Kontaktirajte nas</h3>'."\r\n"
                        .'<form name="kontaktForma" onsubmit="return(validacija());" method="POST" action="contact.php">'."\r\n";
                    ispisiIme($greska);
                    ispisiPrezime($greska);
                    echo '<label class="ispred">Općina</label>
                            <input type="text" name="opcina">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="opcina_error">
                            <label class="iza" id="opcina_greska"></label>
                        <label class="ispred">Mjesto</label>
                            <input type="text" name="mjesto">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="mjesto_error">
                            <label class="iza" id="mjesto_greska"></label>';
                    ispisiAdresu($greska);
                    ispisiEmail($greska);
                    ispisiPotvrdniEmail($greska);
                    ispisiTelefon($greska);
                    echo '<label class="ispred">Ocjena usluge (min-max)</label>
                            <input type="range" min="1" value="1" max="10" step="1" name="usluga">';
                    ispisiPoruku($greska);
                    echo '<label id="upozorenje">&#42;&nbsp;Polja označena zvjezdicom su obavezna.</label>
                        <label class="ispred">&nbsp;</label><input type="submit" name="posalji" value="Pošalji">';
                    echo '</form></div>';
                }
                function ispisiFormuBezGreske($greska="Bez greške"){
                    echo '<form name="kontaktForma" onsubmit="return(validacija());" method="POST" action="contact.php">'."\r\n";
                    ispisiIme($greska);
                    ispisiPrezime($greska);
                    echo '<label class="ispred">Općina</label>
                            <input type="text" name="opcina">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="opcina_error">
                            <label class="iza" id="opcina_greska"></label>
                        <label class="ispred">Mjesto</label>
                            <input type="text" name="mjesto">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="mjesto_error">
                            <label class="iza" id="mjesto_greska"></label>';
                    ispisiAdresu($greska);
                    ispisiEmail($greska);
                    ispisiPotvrdniEmail($greska);
                    ispisiTelefon($greska);
                    echo '<label class="ispred">Ocjena usluge (min-max)</label>
                            <input type="range" min="1" value="';
                    if(isset($_POST["usluga"])) echo htmlentities($_POST["usluga"]);
                    echo '" max="10" step="1" name="usluga">';
                    ispisiPoruku($greska);
                    echo '<label id="upozorenje">&#42;&nbsp;Polja označena zvjezdicom su obavezna.</label>
                        <label class="ispred">&nbsp;</label><input type="submit" name="posalji" value="Pošalji">';
                    echo '<input type="reset" name="reset" value="Reset" onclick="resetuj();">';
                    echo '</form>';
                }
                function ispisiIme($greska){
                    echo '<label class="ispred">Ime<span class="obavezno">&#42;</span></label>'."\r\n"
                            .'<input type="text" name="ime" class="obavezan" value="';
                    if(isset($_POST["ime"]))echo htmlentities($_POST["ime"]);
                    if($greska !== "ime") {
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img" id="ime_error">
							        <label class="iza" id="ime_greska"></label>';
                    }else{
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img"
                                id="ime_error" style="display:block;">
                                <label class="iza" id="ime_greska">Pogrešan unos</label>';
                    }
                    echo '<input type="hidden" name="slanje" value="0">';
                }
                function ispisiPrezime($greska){
                    echo '<label class="ispred">Prezime<span class="obavezno">&#42;</span></label>'."\r\n"
                        .'<input type="text" name="prezime" class="obavezan" value="';
                    if(isset($_POST["prezime"]))echo htmlentities($_POST["prezime"]);
                    if($greska !== "prezime") {
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img" id="prezime_error">
							        <label class="iza" id="prezime_greska"></label>';
                    }else{
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img"
                                id="prezime_error" style="display:block;">
                                <label class="iza" id="prezime_greska">Pogrešan unos</label>';
                    }
                }
                function ispisiAdresu($greska){
                    echo '<label class="ispred">Adresa</label>
                            <input type="text" name="adresa" value="';
                    if(isset($_POST["adresa"]))echo htmlentities($_POST["adresa"]);
                    if($greska !== "adresa"){
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img" id="adresa_error">
							        <label class="iza" id="adresa_greska"></label>';
                    }else{
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img"
                                id="adresa_error" style="display:block;">
                                <label class="iza" id="adresa_greska">Pogrešan unos</label>';
                    }
                }
                function ispisiEmail($greska){
                    echo '<label class="ispred">Email<span class="obavezno">&#42;</span></label>
                            <input type="email" name="email" class="obavezan" id="emailID" value="';
                    if(isset($_POST["email"]))echo htmlentities($_POST["email"]);
                    if($greska !== "email") {
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img" id="email_error">
							        <label class="iza" id="email_greska"></label>';
                    }else{
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img"
                                id="email_error" style="display:block;">
                                <label class="iza" id="email_greska">Pogrešan unos</label>';
                    }
                }
                function ispisiPotvrdniEmail($greska){
                    echo ' <label class="ispred">Potvrdite email<span class="obavezno">&#42;</span></label>
                            <input type="email" name="emailPotvrda" class="obavezan" id="emailPotvrdaID" value="';
                    if(isset($_POST["emailPotvrda"]))echo htmlentities($_POST["emailPotvrda"]);
                    if($greska !== "emailPotvrda") {
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img" id="emailPotvrda_error">
							        <label class="iza" id="emailPotvrda_greska"></label>';
                    }else{
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img"
                                id="emailPotvrda_error" style="display:block;">
                                <label class="iza" id="emailPotvrda_greska">Pogrešan unos</label>';
                    }
                }
                function ispisiTelefon($greska){
                    echo '<label class="ispred">Telefon</label>
                            <input type="tel" name="telefon" value="';
                    if(isset($_POST["telefon"]))echo htmlentities($_POST["telefon"]);
                    if($greska !== "telefon"){
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img" id="telefon_error">
							        <label class="iza" id="telefon_greska"></label>';
                    }else{
                        echo '"><img src="static/images/error.png" alt="greska" class="error_img"
                                id="telefon_error" style="display:block;">
                                <label class="iza" id="telefon_greska">Pogrešan unos</label>';
                    }
                }
                function ispisiPoruku($greska){
                    echo '<label class="ispred">Poruka<span class="obavezno">&#42;</span></label>
                            <textarea cols="25" rows="5" placeholder=" Unesite Vašu poruku..." class="obavezan" name="poruka">';
                    if(isset($_POST["poruka"]))echo htmlentities($_POST["poruka"]);
                    if($greska !== "poruka"){
                        echo '</textarea><img src="static/images/error.png" alt="greska" class="error_img" id="poruka_error">
							        <label class="iza" id="poruka_greska"></label>';
                    }else{
                        echo '</textarea><img src="static/images/error.png" alt="greska" class="error_img"
                                id="poruka_error" style="display:block;">
                                <label class="iza" id="poruka_greska">Pogrešan unos</label>';
                    }
                }
                function validacija(){
                    $ulaz = $_POST["ime"];
                    $reg = '/^[a-zščćđž]{3,}$/i';
                    $regEmpty = '/^\s*$/';
                    if($ulaz === null or $ulaz === "" or preg_match($regEmpty, $ulaz) or !preg_match($reg, $ulaz)){
                        ispisiFormuSaGreskom("ime");
                        return;
                    }
                    $ulaz = $_POST["prezime"];
                    if($ulaz === null or $ulaz === "" or preg_match($regEmpty, $ulaz) or !preg_match($reg, $ulaz)){
                        ispisiFormuSaGreskom("prezime");
                        return;
                    }
                    $ulaz = $_POST["adresa"];
                    $reg = '/^[a-zćčđšž0-9\s,.]{4,}$/i';
                    if($ulaz !== null and $ulaz !== "" and !preg_match($regEmpty, $ulaz) and !preg_match($reg, $ulaz)){
                        ispisiFormuSaGreskom("adresa");
                        return;
                    }
                    $ulaz = $_POST["email"];
                    $reg = '/\S+@\S+\.\S{2,}/';
                    if($ulaz === null or $ulaz === "" or preg_match($regEmpty, $ulaz) or !preg_match($reg, $ulaz)){
                        ispisiFormuSaGreskom("email");
                        return;
                    }
                    $potvrda = $_POST["emailPotvrda"];
                    if($potvrda === null or $potvrda === "" or preg_match($regEmpty, $potvrda) or
                        !preg_match($reg, $potvrda) or $potvrda !== $ulaz){
                        ispisiFormuSaGreskom("emailPotvrda");
                        return;
                    }
                    $ulaz = $_POST["telefon"];
                    $reg = '/^\+\d{3}\s\d\d\s\d{3}\-\d{3}$/';
                    if($ulaz !== null and $ulaz !== "" and !preg_match($regEmpty, $ulaz) and !preg_match($reg, $ulaz)){
                        ispisiFormuSaGreskom("telefon");
                        return;
                    }
                    $ulaz = $_POST["poruka"];
                    if($ulaz === null or $ulaz === "" or preg_match($regEmpty, $ulaz)){
                        ispisiFormuSaGreskom("poruka");
                        return;
                    }

                }
                if(isset($_POST["slanje"]) and $_POST["slanje"] === "1"){
                    echo "<div class='kontakt'><br><br><br>"
                        ."<h4 style='text-align: center;'>Zahvaljujemo se što ste nas kontaktirali</h4></div>";
                    $to = "mmujic1@etf.unsa.ba";
                    $subject = "Poruka poslana sa kontakt forme";
                    $txt = "Ime: ". $_POST["ime"]."\r\n"
                          ."Prezime: ". $_POST["prezime"]."\r\n";
                    if(!empty($_POST["opcina"]))
                        $txt .= "Općina: ". $_POST["opcina"]."\r\n";
                    if(!empty($_POST["mjesto"]))
                        $txt .= "Mjesto: ". $_POST["mjesto"]."\r\n";
                    if(!empty($_POST["adresa"]))
                        $txt .= "Adresa: ". $_POST["adresa"]."\r\n";
                    $txt .= "Email: ". $_POST["email"]."\r\n";
                    if(!empty($_POST["telefon"]))
                        $txt .= "Telefon: ". $_POST["telefon"]."\r\n";
                    $txt .= "Usluga: ". $_POST["usluga"]."\r\n"
                           ."Poruka: ". $_POST["poruka"]."\r\n";
                    $txt = wordwrap($txt, 70, "\r\n");
                    $headers = "From: webmaster@example.com" . "\r\n" .
                        'Reply-To: '.$_POST["email"] . "'" . "\r\n" .
                        "CC: vljubovic@etf.unsa.ba";

                    mail($to,$subject,$txt,$headers);
                }elseif(isset($_POST["posalji"])){
                    validacija();
                    echo '<div class="kontakt">';
                    echo '<h4>Provjerite da li ste ispravno popunili kontakt formu</h4>';
                    echo '<table id="stednja" class="servicesTable">'
                        ."<tr><td>Ime:</td><td>".$_POST["ime"]."</td></tr>"
                        ."<tr><td>Prezime:</td><td>".$_POST["prezime"]."</td></tr>"
                        ."<tr><td>Općina:</td><td>".$_POST["opcina"]."</td></tr>"
                        ."<tr><td>Mjesto:</td><td>".$_POST["mjesto"]."</td></tr>"
                        ."<tr><td>Adresa:</td><td>".$_POST["adresa"]."</td></tr>"
                        ."<tr><td>Email:</td><td>".$_POST["email"]."</td></tr>"
                        ."<tr><td>Potvrdni email:</td><td>".$_POST["emailPotvrda"]."</td></tr>"
                        ."<tr><td>Telefon:</td><td>".$_POST["telefon"]."</td></tr>"
                        ."<tr><td>Ocjena usluge:</td><td>".$_POST["usluga"]."</td></tr>"
                        ."<tr><td>Poruka:</td><td>".$_POST["poruka"]."</td></tr>"
                        .'</table>';
                    echo '<br><h5 style="display:inline;">Da li ste sigurni da želite poslati ove podatke?&nbsp;&nbsp;</h5>'
                        .'<input type="button" style="display:inline;" name="siguran" value="Siguran sam" onclick="posaljiMail();">';
                    echo '<h4>Ako ste pogrešno popunili formu, možete ispod prepraviti unesene podatke</h4>';
                    ispisiFormuBezGreske();
                    echo '</div>';
                }else{
                echo '<div class="kontakt">
                        <h3>&nbsp;Kontaktirajte nas</h3>
                        <form name="kontaktForma" onsubmit="return(validacija());" method="POST" action="contact.php">
						<label class="ispred">Ime<span class="obavezno">&#42;</span></label>
                            <input type="text" name="ime" class="obavezan">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="ime_error">
							<label class="iza" id="ime_greska"></label>
						<label class="ispred">Prezime<span class="obavezno">&#42;</span></label>
                            <input type="text" name="prezime" class="obavezan">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="prezime_error">
							<label class="iza" id="prezime_greska"></label>
                       <label class="ispred">Općina</label>
                            <input type="text" name="opcina">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="opcina_error">
                            <label class="iza" id="opcina_greska"></label>
                        <label class="ispred">Mjesto</label>
                            <input type="text" name="mjesto">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="mjesto_error">
                            <label class="iza" id="mjesto_greska"></label>
                        <label class="ispred">Adresa</label>
                            <input type="text" name="adresa">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="adresa_error">  
							<label class="iza" id="adresa_greska"></label>
						<label class="ispred">Email<span class="obavezno">&#42;</span></label>
                            <input type="email" name="email" class="obavezan" id="emailID">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="email_error">
                            <label class="iza" id="email_greska"></label>
                        <label class="ispred">Potvrdite email<span class="obavezno">&#42;</span></label>
                            <input type="email" name="emailPotvrda" class="obavezan" id="emailPotvrdaID">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="emailPotvrda_error">
                            <label class="iza" id="emailPotvrda_greska"></label>
                        <label class="ispred">Telefon</label>
                            <input type="tel" name="telefon">
                            <img src="static/images/error.png" alt="greska" class="error_img" id="telefon_error">
							<label class="iza" id="telefon_greska"></label>
                        <label class="ispred">Ocjena usluge (min-max)</label>
                            <input type="range" min="1" value="1" max="10" step="1" name="usluga">
						<label class="ispred">Poruka<span class="obavezno">&#42;</span></label>
                            <textarea cols="25" rows="5" placeholder=" Unesite Vašu poruku..." class="obavezan" name="poruka"></textarea>
                            <img src="static/images/error.png" alt="greska" class="error_img" id="poruka_error">
							<label class="iza" id="poruka_greska"></label>
						<label id="upozorenje">&#42;&nbsp;Polja označena zvjezdicom su obavezna.</label>
                        <label class="ispred">&nbsp;</label><input type="submit" name="posalji" value="Pošalji">
                        </form>
				</div>';
                }
                ?>
                <div class="kontakt_desno">
                    <div class="kontakt_feed_desno">
                        <h4>Radno vrijeme</h4>
                        <div class="feed_content clock">
                            <p><b>Sve poslovnice</b></p>
                            <br>
                            <p>Radni dan: od 8 do 16 sati</p>
                            <p>Subota: od 9 do 13 sati</p>
                        </div>
                    </div>
                    <div class="kontakt_feed_desno">
                        <h4>Poslovnica</h4>
                        <div class="feed_content">
                            <p><b>Filijala Sarajevo </b></p>
                            <br>
                            <p>Trg djece Sarajeva, 11</p>
                            <p>71 000 Sarajevo</p>
                            <p>Bosna i Hercegovina</p>
                            <br>
                            <p><b>GoldenBrick direct info</b></p>
                            <br>
                            <p><b>+387 33 00 01 13</b></p>
                            <br>
                            <p><b>E-mail</b></p>
                            <p><span class="p_minimize">sarajevo@goldenbrick.ba</span></p>
                            <br>
                            <p><b>Faks</b></p>
                            <p><b>+387 33 00 01 14</b></p>
                        </div>
                    </div>
                </div>
			</div>
			<div class="podnozje">
				<p>&copy; 2015 GoldenBricks, Sva prava pridržana.</p>
			</div>
		</div>
		<div class="vrh">
			<a href="#"><img src="static/images/vrh.png" alt="Idi na vrh"></a>
		</div>
	</body>
</html>