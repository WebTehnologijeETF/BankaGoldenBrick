<?php
$prazniUnosi = false;
$vijestGreska = 0;
if(isset($_REQUEST['komentarisi'])){
    try{
        $veza = new PDO("mysql:dbname=goldenbrick;host:localhost;charset:utf8", "goldenbrickDB", "shawshank");
    }catch (PDOException $ex){
        echo "MYSQL greška: ".$ex->errorInfo();
        die();
    }
    $veza->exec("set names utf8");
    $novost = htmlentities($_REQUEST['novostID']);
    $autor = htmlentities($_REQUEST['autor']);
    $tekst = htmlentities($_REQUEST['tekstKomentara']);
    if(isset($_REQUEST['email']) and !empty($_REQUEST['email']) and !empty($_REQUEST['autor']) and !empty($_REQUEST['tekstKomentara'])){
        $email = htmlentities($_REQUEST['email']);
        $upit = $veza->prepare("INSERT INTO komentari (novost, autor, email, tekst) VALUES (:novost, :autor, :email, :tekst)");
        $upit->execute(array(':novost'=>$novost,
                              ':autor'=>$autor,
                              ':email'=>$email,
                              ':tekst'=>$tekst));
        if(!$upit){
            echo "Greška prilikom spremanja komentara.";
            die();
        }
    }elseif(!empty($_REQUEST['autor']) and !empty($_REQUEST['tekstKomentara'])){
        $upit = $veza->prepare("INSERT INTO komentari (novost, autor, tekst) VALUES (:novost, :autor, :tekst)");
        $upit->execute(array(':novost'=>$novost,
            ':autor'=>$autor,
            ':tekst'=>$tekst));
        if(!$upit){
            echo "Greška prilikom spremanja komentara.";
            die();
        }
    }else{
        $prazniUnosi=true;
        $vijestGreska = $_REQUEST['novostID'];
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>GoldenBrick | Naslovnica</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="static/css/stil.css">
		<link rel="icon" type="image/x-icon" href="static/images/icon.ico">
		<script src="static/js/meni.js"></script>
        <script src="static/js/loadAjax.js"></script>
        <script>
            function ucitaj(id){
                //console.log("MM");
                id = encodeURI(id);
                var div = document.getElementsByClassName("centar")[0];
                var ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == "4" && ajax.status == "200") {
                        div.innerHTML = ajax.responseText;
                        scroll(-200,0);
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
                ajax.send("id="+id);
            }
            function prikazKomentara(anchor){
                if(anchor.classList.contains("zatvoreno")){
                    anchor.classList.remove("zatvoreno");
                    anchor.classList.add("otvoreno");
                    document.getElementById("div"+anchor.getAttribute("href")).style.display = "block";
                }else{
                    anchor.classList.remove("otvoreno");
                    anchor.classList.add("zatvoreno");
                    document.getElementById("div"+anchor.getAttribute("href")).style.display = "none";
                }
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
                    $clanci = array();
                    try{
                        $veza = new PDO("mysql:dbname=goldenbrick;host:localhost;charset:utf8", "goldenbrickDB", "shawshank");
                    }catch (PDOException $ex){
                        echo "MYSQL greška: ".$ex->errorInfo();
                        die();
                    }
                    $veza->exec("set names utf8");
                    $rezultat = $veza->query("SELECT * FROM novosti");
                    if(!$rezultat){
                        $greska = $veza->errorInfo();
                        print "SQL greška: ".$greska;
                        exit();
                    }
                    foreach($rezultat as $vijest){
                        if (strpos($vijest['tekst'],"--\r\n") !== false) {
                            $vijest["detaljnije"] = true;
                        }else{
                            $vijest["detaljnije"] = false;
                        }
                        $vijest['tekst'] = str_replace("--\r\n", "", $vijest['tekst']);
                        $vijest['tekst'] = nl2br($vijest['tekst']);
                        array_push($clanci, $vijest);
                    }
                    usort($clanci, function($a, $b) {
                        $vr1 = new DateTime($a['vrijeme']);
                        $vr2 = new DateTime($b['vrijeme']);
                        return $vr1 > $vr2;
                    });
                    for($j = 0; $j < count($clanci); $j++){
                        echo "<div class='clanak'>\r\n";
                        echo "<h3>".$clanci[$j]['naslov']."</h3>\r\n";
                        echo "<br>";
                        echo "<div class='clanak_info'>\r\n"
                                ."<p class='autor'><img src='static/images/author.png' alt='autor' class='author'>\r\n"
                                    ."<small>".$clanci[$j]['autor']."</small></p>\r\n"
                               ."<p class='vrijeme'><img src='static/images/date.png' alt='datum' class='datum'>\r\n"
                                    ."<small>".$clanci[$j]['vrijeme']."</small></p>\r\n"
                           . "</div>\r\n";
                        echo "<div class='tekst-clanka'>\r\n";
                        if($clanci[$j]["slika"] !== "") {
                            echo "<img src='" . $clanci[$j]['slika'] . "' alt='slika clanka'>\r\n";
                        }
                        echo "<p>".$clanci[$j]['tekst']."</p>\r\n</div>\r\n";
                        if($clanci[$j]['detaljnije']){
                            echo "<a href='".$clanci[$j]["id"]."' class='detaljnije'><small>Detaljnije...</small></a>\r\n";
                        }
                        $vijestID = intval(htmlentities($clanci[$j]['id']));
                        $upit = $veza->prepare("SELECT * FROM komentari WHERE novost=:vijestID ORDER BY vrijeme ASC");
                        $upit->bindParam(":vijestID", $vijestID, PDO::PARAM_INT);
                        $upit->execute();
                        if(!$upit){
                            echo "Greška u dobavljanju komentara";
                            die();
                        }
                        if($upit->rowCount()===0){
                            $brojKomentara = "Nema";
                        }else {
                            $brojKomentara = $upit->rowCount();
                        }
                        echo "<a href='" . $clanci[$j]['id'] . "' class='anchor-komentari lijevo-pozicija zatvoreno'>"
                            . $brojKomentara . " komentara</a>\r\n";
                        echo "<br><br><div id='div".$clanci[$j]['id']."'class='div-komentari'>"
                            ."<p class='border-siva'></p>\r\n"
                            ."<form method='POST' action='index.php'>\r\n"
                            ."<input type='hidden' name='novostID' value='".$clanci[$j]['id']."'>\r\n"
                            ."<input type='text' name='autor' placeholder='Ime i prezime' class='ulaz lijevo'>\r\n"
                            ."<input type='text' name='email' placeholder='E-mail adresa (opcionalno)' class='ulaz lijevo'>\r\n"
                            ."<textarea name='tekstKomentara' rows=5 cols=69 class='ulaz' placeholder='Komentar'>"
                            ."</textarea>\r\n";
                        if($prazniUnosi and $vijestGreska==$clanci[$j]['id'])
                            echo "<label id='labelPogresanUnos' style='color: red; font-weight: bold;'>Pogrešan unos.</label>";
                        echo "<input type='submit' name='komentarisi' value='Komentariši' class='submit-komentar desno-pozicija'>\r\n"
                        ."</form><br><br>\r\n";
                        if($brojKomentara!=="Nema") {
                            echo "<h3 class='border-komentari velika-slova'>Komentari</h3>\r\n";
                            foreach($upit as $komentar) {
                                echo "<div class='div-komentar'>\r\n";
                                if($komentar['email'] !== "") {
                                    echo "<a href='mailto:".$komentar['email']."' class='anchor-ime bold'>"
                                    .$komentar['autor']."</a><br>\r\n";
                                }else{
                                    echo "<label class='anchor-ime bold not-underlined'>".$komentar['autor']."</label><br>\r\n";
                                }
                                $datetime = explode(" ", $komentar['vrijeme']);
                                $date = $datetime[0];
                                $time = $datetime[1];
                                    echo "<label class='label-vrijeme'>".$date." u ".$time."</label>\r\n"
                                    . "<p class='p-komentar'>".$komentar['tekst']."</p>\r\n"
                                    . "</div>";
                            }
                        }
                        echo "</div>\r\n</div>\r\n\r\n";
                    }

                    ?>
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