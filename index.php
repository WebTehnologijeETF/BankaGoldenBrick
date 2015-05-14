<!DOCTYPE html>
<html>
	<head>
		<title>GoldenBrick | Naslovnica</title>
		<meta charset="UTF-8">
<!--        <meta http-equiv="refresh" content="1"/> Automatskirefrešuje stranicu nakon promjena -->
		<link rel="stylesheet" type="text/css" href="static/css/stil.css">
		<link rel="icon" type="image/x-icon" href="static/images/icon.ico">
		<script src="static/js/meni.js"></script>
        <script src="static/js/loadAjax.js"></script>
        <script>
            function ucitaj(naziv){
                //console.log("MM");
                naziv = encodeURI(naziv);
                var div = document.getElementsByClassName("centar")[0];
                var ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == "4" && ajax.status == "200") {
                        div.innerHTML = ajax.responseText;
                    }
                    if (ajax.readyState == "4" && ajax.status == "400") {
                        div.innerHTML = "Pogrešni podaci!";
                    }
                    if (ajax.readyState == "4" && ajax.status == "404") {
                        div.innerHTML = "Stranica nije pronađena!";
                    }
                }
                ajax.open("POST", "ucitajNovost.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("naziv="+naziv);
            }
        </script>
	</head>
	<body>
		<div class="okvir">
			<div class="zaglavlje">
				<div class="meni">
					<ul>
						<li>
							<a href=""  class="logoSlika"></a>
						</li>
						<li>
							<a href=""  class="logo"><span class="spanLogo">G</span>olden<span class="spanLogo">B</span>rick</a>
						</li>
						<li class="fokus"><a href="">Naslovnica</a></li>
						<li class="padajuci"><a href="savings.html">Štednja</a>
							<ul>
								<li><a href="savings1.html" class="ajaxLoaderLink meniLink">A Vista</a></li>
								<li><a href="savings2.html" class="ajaxLoaderLink meniLink">Dječija štednja</a></li>
							</ul>
						</li>
                        <li><a href="services.html">Usluge</a></li>
						<li><a href="about.html">O nama</a></li>
						<li><a href="contact.php">Kontakt</a></li>
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
					<div class="kontakt_feed">
                        <h4>Radno vrijeme</h4>
                        <div class="feed_content clock">
                            <p><b>Sve poslovnice</b></p>
                            <br>
                            <p>Radni dan: od 8 do 16 sati</p>
                            <p>Subota: od 9 do 13 sati</p>
                        </div>
                    </div>
                </div>
                <div class="centar">
                <div class="uvod_centar"><h3>Novosti</h3></div>
                    <?php
                    if ($handle = opendir('novosti')) {
                        $clanci = array();
                        $k = 0;
                        while (false !== ($entry = readdir($handle))) {
                            if ($entry === "." || $entry === "..") {
                                continue;
                            }

                            $path = "novosti/" . $entry;
                            $novost = fopen($path, "r") or die("Greška u otvaranju fajla");
                            $clanci[$k]["naziv"] = explode(".", $entry)[0];
                            $clanci[$k]['vrijeme'] = fgets($novost);
                            $clanci[$k]['autor'] = fgets($novost);
                            $clanci[$k]['naslov'] = ucfirst(mb_strtolower(fgets($novost), 'UTF-8'));
                            $clanci[$k]['slikaURL'] = fgets($novost);
                            $tekst = array();
                            $i = 0;
                            $detaljnije = false;
                            while (!feof($novost)) {
                                $red = fgets($novost);
                                if ($red === "--\r\n") {
                                    $detaljnije = true;
                                    break;
                                }
                                $tekst[$i] = $red;
                                $i += 1;
                            }
                            $tekst = implode("<br>", $tekst);
                            $clanci[$k]['tekst'] = $tekst;
                            $clanci[$k]["detaljnije"] = $detaljnije;
                            $k++;
                        }
                        usort($clanci, function($a, $b) {
                            $a = $a['vrijeme'];
                            $b = $b['vrijeme'];
                            $datum1 = explode(" ", $a);
                            $datum2 = explode(" ", $b);
                            $vrijeme1 = strtotime($datum1[1]);
                            $vrijeme2 = strtotime($datum2[1]);
                            $vrijeme1 = date('H:i:s', $vrijeme1);
                            $vrijeme2 = date('H:i:s', $vrijeme2);
                            $datum1 = explode(".", $datum1[0]);
                            $datum2 = explode(".", $datum2[0]);
                            $dan1 = $datum1[0];
                            $mjesec1 = $datum1[1];
                            $godina1 = $datum1[2];
                            $dan2 = $datum2[0];
                            $mjesec2 = $datum2[1];
                            $godina2 = $datum2[2];
                            if($godina1===$godina2 and $mjesec1===$mjesec2 and $dan1===$dan2) {
                                return $vrijeme1 < $vrijeme2;
                            }elseif($godina1===$godina2 and $mjesec1===$mjesec2){
                                return $dan1 < $dan2;
                            }elseif($godina1===$godina2){
                                return $mjesec1 < $mjesec2;
                            }else{
                                return $godina1 < $godina2;
                            }
                        });
                        for($j = 0; $j < count($clanci); $j++){
                            echo "<div class='clanak'>";
                            echo "<h3>".$clanci[$j]['naslov']."</h3>";
                            echo "<br>";
                            echo "<div class='clanak_info'>
                                    <p class='autor'><img src='static/images/author.png' alt='autor' class='author'>
                                        <small>".$clanci[$j]['autor']."</small></p>
                                    <p class='vrijeme'><img src='static/images/date.png' alt='datum' class='datum'>
                                        <small>".$clanci[$j]['vrijeme']."</small></p>
					            </div>";
                            if($clanci[$j]["slikaURL"] !== "\r\n") {
                                echo "<img src='" . $clanci[$j]['slikaURL'] . "' alt='slika clanka'>";
                            }
                            echo "<p>".$clanci[$j]['tekst']."</p>";
                            if($clanci[$j]['detaljnije']){
                                echo "<a href='".$clanci[$j]["naziv"]."' class='detaljnije'><small>Detaljnije...</small></a>";
                            }
                            echo "</div>";
                        }
                        closedir($handle);
                    }
                    ?>
				<!--<div class="clanak">
					<h3>GoldenBrick e-pay</h3>
					<br>
					<div class="clanak_info">
						<p class="autor"><img src="static/images/author.png" alt="autor" class="author"><small>Muhamed Mujić</small></p>
						<p class="vrijeme"><img src="static/images/date.png" alt="datum" class="datum"><small>21. III 2015.</small></p>
					</div>
					<img src="static/images/clanak1.jpg" alt="slika clanka">
					<p>
						GoldenBrick e-pay je nova usluga GoldenBrick banke koja omogućava elektronsku trgovinu uz prihvat MasterCard i Visa kartica prema najvišim svjetskim standardima sigurnosti.
<br><br>
						Usluga je namjenjena pravnim licima, poduzetnicima i klijentima omogućuje prihvat kartica na Internet prodajnom mjestu u sigurnom okruženju. Osim iznimne sigurnosti naplate za poduzetnika i transakcije za kupca, usluga nudi najpovoljniji omjer vrijednosti za uloženi novac.<br>

						GoldenBrick banka Bosna i Hercegovina je certificirana banka za prihvat kartica na internet prodajnim mjestima.
						<br>
						<b>Sa GoldenBrick e-pay uslugom, svaka roba ima svog kupca!</b>
					</p>
					<a href="#"><small>Detaljnije...</small></a>
				</div>
				<div class="clanak">
					<h3>GOTOVINSKI KREDITI GOLDENBRICK BANKE UZ FIKSNU KAMATNU STOPU OD 5,89%</h3>
					<br>
					<div class="clanak_info">
						<p class="autor"><img src="static/images/author.png" alt="autor" class="author"><small>Muhamed Mujić</small></p>
						<p class="vrijeme"><img src="static/images/date.png" alt="datum" class="datum"><small>19. III 2015.</small></p>
					</div>
					<img src="http://www.infobih.com/slike/201012141407350.1149584191_euro_frankfurt_banka_znak.jpg" alt="gotovniski kredit">
					<p>
						GoldenBrick banka je u okviru aktuelne kampanje pod nazivom &quot;Može još bolje&quot; pripremila posebnu ponudu gotovinskih kredita te kredita za refinansiranje postojećih zaduženja koja nisu obezbijeđena hipotekom u drugim finansijskim institucijama.<br>
						&quot;Zadovoljstvo nam je što smo ovaj put u prilici klijentima ponuditi gotovinske kredite pod povoljnijim uslovima. Posebna pogodnost ove ponude jeste kamatna stopa od 5,89%, koja je ujedno i fiksna tokom cijelog perioda otplate kredita. Ponuda je namijenjena postojećim i novim korisnicima naših paketa računa Trendi PLUS i Elegant&quot;, izjavio je <b>Pep Guardiola</b>, direktor GoldenBrick banke, te dodao da na ovaj način, GoldenBrick banka nastoji svojim klijentima sredstva finansiranja učiniti dostupnijim.<br>
						U okviru ove ponude, klijentima je ponuđena mogućnost kreditiranja do 50.000 KM, uz rok otplate do 5 godina, a kao jedan od instrumenata obezbjeđenja koristi se paket osiguranja, koji daje dodatnu sigurnost i pokriće za nastavak otplate kredita, u slučaju nemogućnosti otplate kredita zbog nastanka nesretnog slučaja, gubitka posla ili bolovanja.<br>
						Efektivna kamatna stopa za ovu ponudu gotovinskih kredita kreće se od 7,08% za Federaciju BiH i Brčko Distrikt, te 8,23% za Republiku Srpsku.<br>
						Zainteresovani za ove kredite sve dodatne informacije mogu dobiti u najbližoj poslovnici GoldenBrick banke, te na web stranici www.goldenbrickbank.ba.</p>
					<a href="#"><small>Detaljnije...</small></a>
				</div>
				<div class="clanak">
					<h3>NENAMJENSKI KREDIT GOLDENBRICK BANKE SA KAMATNOM STOPOM OD 6,00% I ROKOM OTPLATE DO 6 GODINA</h3>
					<br>
					<div class="clanak_info">
						<p class="autor"><img src="static/images/author.png" alt="autor" class="author"><small>Muhamed Mujić</small></p>
						<p class="vrijeme"><img src="static/images/date.png" alt="datum" class="datum"><small>16. III 2015.</small></p>
					</div>
					<p>
						<b>Otvorite prozor svojih mogućnosti uz nenamjenski kredit 6 na 6, je nova jesenja kampanja GoldenBrick Banke BiH. Banka je proširila svoju ponudu za klijente koji trebaju dodatna finansijska sredstva ove jeseni uz što niže troškove.</b>
<br><br>
						&quot;Na bh. tržištu novi nenamjenski kredit naše Banke ima najnižu nominalnu kamatnu stopu od 6,00%, efektivna kamatna stopa (EKS) zavisi od iznosa, roka otplate i pripadajućih naknada, a kreće se do 8,17%. Maksimalni iznos kredita je 60.000 KM  sa rokom otplate do 6 godina. Nenamjenski kredit 6 na 6 pruža klijentima i mogućnost odabira police osiguranja koja daje dodatnu sigurnost u cijelom periodu otplate kredita,&quot; ističe Luis Enrique, član Uprave GoldenBrick Banke BiH.<br><br> 

						Nenamjenski kredit sa kamatom od 6% i otplatom na 6 godina, je bankarski proizvod koji klijentu omogućava da na kraći rok i posebno povoljnu kamatu, nabavi različite kućne potrepštine, školsku opremu za djecu, izvrši popravke u stanu i sl. 
<br>
						Osim što je proširila ponudu kredita i drugih usluga, GoldenBrick Banka  BiH je od 1.septembra pokrenula veliku humanitarnu akciju pod nazivom &quot;Insprisani srcem&quot;, potvrđujući se još jednom kao društveno odgovorna kompanija.
<br>
						&quot;Ovogodišnjom akcijom  za svaku kupovinu VISA Inspire karticom, a bez ikakvog dodatnog troška za naše klijente, GoldenBrick Banka donira 0,10 KM za projekat &quot;Gradimo srcem BiH&quot; , u okviru kojeg će se prikupljena sredstva donirati stanovnicima stradalim u poplavama&quot;, naglašava  Josep Bartomeu, član Uprave ove Banke.
<br><br>
						Humanitarna akcija se realizuje u saradnji sa NVO OTVORENA MREŽA i traje do 31.oktobra 2014. godine.
					</p>
					<a href="#"><small>Detaljnije...</small></a>
				</div>
				<div class="clanak">
					<h3>Zamjenski kredit - najduži rok otplate</h3>
					<br>
					<div class="clanak_info">
						<p class="autor"><img src="static/images/author.png" alt="autor" class="author"><small>Muhamed Mujić</small></p>
						<p class="vrijeme"><img src="static/images/date.png" alt="datum" class="datum"><small>14. III 2015.</small></p>
					</div>
					<img src="http://www.rb-laa.at/eBusiness/services/resources/media/1018580211793-1020372206860_866716176737154511-674873145785629777-1-30-NA.jpeg" alt="zamjenski krediti">
					<p>
						GoldenBrick BH je pokrenula promotivnu akciju zamjenskih kredita u okviru koje su klijentima na raspolaganju zamjenski krediti sa najdužim rokom otplate na tržištu do 12 godina i maksimalnim iznosom kredita do 60.000 KM, što je posebna pogodnost za klijente koji imaju više kredita koje žele objediniti u jedan i plaćati samo jednu ratu.
						<br>
						Osim pogodnosti ovog kredita, klijenti GoldenBrick BH ostvaruju i brojne druge pogodnosti u poslovanju sa GoldenBrick BH,  što je i svojevrsna poruka nove promotivne kampanje GoldenBrick BH. Uspješna prošlogodišnja saradnja fudbalera Carlesa Puyola i GoldenBrick BH nastavljena je i ove godine. Puyolu se u realizaciji nove promotivne kampanje pridružila regionalna pop zvijezda Shakira.
						<br>
						&quot;Ovom kampanjom smo prvenstveno željeli naglasiti pogodnosti poslovanja sa GoldenBrick BH. Želimo razviti osjećaj kod naših klijenata da su posebni, jer oni to u biti i jesu. Stoga ulažemo maksimalan napor da bismo im pružili više, ponudili najbolje uslove kreditiranja u pogledu roka otplate, iznosa, ali i visine kamatne stope, te različite dodatne pogodnosti&quot;, izjavio je Johan Cruyff, izvršni direktor GoldenBrick BH. 
						<br>
						&quot;Najdužim rokom otplate na tržištu, ali i iznosom kredita omogućili smo klijentima da objedine sve obaveze po osnovu zaduženja i plaćaju jednu ratu sa veoma povoljnim uslovima&quot;, naglasio je Cruyff.
						<br>
						Za rokove do 7 godina GoldenBrick BH omogućava ugovaranje fiksne kamatne stope. Korisnicima zamjenskog kredita omogućeno je ugovaranje prekoračenja po tekućem računu i kreditne kartice bez naknade, a korisnici Plus paketa ostvaruju i dodatne pogodnosti.
					</p>
					<a href="#"><small>Detaljnije...</small></a>
				</div>
				<div class="clanak">
					<h3>GOLDENBRICK PRVA BANKA NA TRŽIŠTU KOJA NUDI USLUGU FAKTORINGA ZA PRAVNA LICA</h3>
					<br>
					<div class="clanak_info">
						<p class="autor"><img src="static/images/author.png" alt="autor" class="author"><small>Muhamed Mujić</small></p>
						<p class="vrijeme"><img src="static/images/date.png" alt="datum" class="datum"><small>10. II 2015.</small></p>
					</div>
					<img src="http://livinginchiangmai.com/wp-content/uploads/2014/12/banking.png" alt="faktoring">
					<p>
						GoldenBrick banka je prva banka na bosanskohercegovačkom tržištu koja preduzećima nudi uslugu faktoringa koja, kroz ustupanje potraživanja Banci, osigurava likvidnost i stabilnost poslovanja preduzeća.<br>
						Faktoring predstavlja oblik kratkoročnog finansiranja za kompanije koje imaju kvalitetna potraživanja, te stalne isporuke prema kupcima, sa odloženim rokovima plaćanja od 30 do 180 dana.<br>
						 &quot;Faktoring je noviji model finansiranja na bh. tržištu za sva pravna lica koja imaju potrebu za dodatnim finansijskim sredstvima u cilju premoštavanja odgode plaćanja od dužnika, a ne žele iz bilo kojeg razloga koristiti kreditno zaduženje u tu svrhu. Ova usluga klijentu omogućava direktno finansiranje poslovanja što poboljšava njegovu likvidnost te osigurava obrtni kapital za dalja ulaganja&quot;, izjavio je <b><i>Pep Guardiola</i></b>, direktor GoldenBrick banke, te dodao da ova usluga klijentu omogućava i uštedu vremena obzirom da Banka u korist klijenta vodi evidenciju ustupljenih potraživanja.<br>
						Pružanje faktoring usluge podrazumijeva finansiranje klijenta isplatom avansa na račun klijenta kod Raiffeisen banke u visini od 60% do 90% vrijednosti ustupljenih potraživanja, dok se preostalih 10% do 40% vrijednosti doznačuje klijentu nakon izmirenja obaveza od strane dužnika po ustupljenim potraživanjima.<br><br>
						Više informacija dostupno je u poslovnicama Banke, na broj telefona 033 755 010 ili putem e-mail adrese faktoring.gbbh@rbb-sarajevo.goldenbrick.at, te na web stranici Banke: www.goldenbrickbank.ba.
					</p>
					<a href="#"><small>Detaljnije...</small></a>
				</div> -->
			</div>
            <div class="desno">
				<div class="feed">
                    <img class="img_feed" src="static/images/feed4.jpg" alt="feed image 4">
                    <p><span>Najniže kamatne stope<br>na stambene kredite</span></p>
                </div>
                <div class="feed">
                    <img class="img_feed" src="static/images/feed2.jpg" alt="feed image 2">
                    <p><span>Vaša banka na <br>Vašem radnom mjestu</span></p>
                </div>
				<div class="feed">
                    <img class="img_feed" src="static/images/feed3.jpg" alt="feed image 3">
                    <p><span>Proljetna akcija <br>kreditnog bankarstva</span></p>
                </div>
				<div class="feed">
                    <img class="img_feed" src="static/images/feed1.jpg" alt="feed image 1">
                    <p class="img_p"><span>Zimska akcija štednje</span></p>
                </div>
            </div>
            <div style="clear:both;"></div>
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