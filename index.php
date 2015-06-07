<?php
session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
    $username = "Anoniman";
}
if($username !== "Anoniman" && isset($_REQUEST['logout'])){
    session_destroy();
    $username = "Anoniman";
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
        <script src="static/js/rest.js"></script>
        <style>
            #adminMeni{
                padding: 10px;
                border-bottom: 3px solid lightseagreen;
                border-top: 3px solid lightseagreen;
                text-align: center;
                background-color: white;
            }
            .anchor-admin{
                text-decoration: none;
                padding: 5px 10px;
                color: #DD1144;
                /*text-transform: uppercase;*/
                font-weight: bold;
            }
            .anchor-admin:hover{
                /*text-decoration: underline;
                border-bottom: 2px solid #DD1144;*/
                background-color: #DD1144;
                color: white;
                border-radius: 5px;
            }
            .submit-komentar{
                color: #DD1144;
                margin-left: 10px;
            }
            .submit-komentar:hover{
                background-color: #DD1144;
                color: white;
            }
            .ulaz-login{
                height: 19px;
                width: 150px;
                border-radius: 5px;
                border: 1px solid silver;
                padding: 5px 5px;
            }
            select{
                width: 250px;
                height: 35px;
                font-size: 103%;
                font-family: 'Ubuntu', sans-serif;
            }
        </style>
	</head>
	<body>
		<div class="okvir">
            <?php
            require("includes/funkcije.php");
            ucitajZaglavlje();
            ?>
            <div class="sjena"></div>
			<div class="sadrzaj">
                    <?php
                    if($username!=="Anoniman") {
                        echo "<form method='post' action='index.php' style='text-align: right;margin: 10px; width: 98%'>\r\n"
                            . "<label>Prijavljeni ste kao: <span class='gold'>" . $username . "</span></label>"
                            . "<input type='submit' name='logout' value='Odjavi se'"
                            . "class='submit-komentar' style='margin-left:10px;'>\r\n"
                            . "</form>\r\n";
                        if($_SESSION['tip'] === "admin"){
                            echo "<div id='adminMeni'>";
                            echo "<a href='admin.php?create=news' class='anchor-admin'>Dodavanje novosti</a>";
                            echo "<label>|</label>";
                            echo "<a href='admin.php?create=admin' class='anchor-admin'>Dodavanje administratora</a>";
                            echo "<label>|</label>";
                            echo "<a href='admin.php?update=admin' class='anchor-admin'>Izmjena administratora</a>";
                            echo "<label>|</label>";
                            echo "<a href='admin.php?delete=admin' class='anchor-admin'>Brisanje administratora</a>";
                            echo "</div>";
                        }
                    }else{
                        echo "<form method='post' action='login.php' style='text-align: right;margin: 10px; width: 98%'>\r\n"
                            . "<input type='text' name='username' class='ulaz-login' placeholder='Username'>"
                            . "<input type='password' name='password' class='ulaz-login' placeholder='Password'>"
                            . "<input type='submit' name='login' value='Prijavi se'"
                            . "class='submit-komentar' style='margin-left:0px;'>"
                            . "</form>";
                    }

                    if(isset($_REQUEST['izmijeniVijest'])){
                        $vijestID = htmlentities($_REQUEST['vijestID']);
                        izmijeniNovost($vijestID, $greska);
                    }else {
                        lijeviSideBar();
                        echo "<div class='centar'></div>";
                        desniSideBar();
                    }
                    function izmijeniNovost($vijestID, $greska){
                        try{
                            $veza = connect();
                        }catch (PDOException $ex){
                            echo $ex->getMessage();
                            die();
                        }
                        $upit = $veza->prepare("SELECT * FROM novosti WHERE id=:id");
                        $upit->bindParam(':id', $vijestID);
                        $upit->execute();
                        if(!$upit){
                            echo "Greška prilikom rada sa bazom podataka.";
                            die();
                        }
                        $vijest = $upit->fetch();
                        echo "<div class='dodavanje'>"
                            ."<h2>Izmjena novosti</h2><br><br>"
                            ."<form method='post' action='index.php'>"
                            ."<input type='hidden' name='vijestID' value='$vijestID'>"
                            ."<label>Autor</label><br>"
                            ."<input type='text' name='autor' class='ulaz' value='".$vijest['autor']."'><br>"
                            ."<label>Naslov</label><br>"
                            ."<input type='text' name='naslov' class='ulaz' value='".$vijest['naslov']."'><br>"
                            ."<label>URL slike</label><br>"
                            ."<input type='text' name='slika' class='ulaz' value='".$vijest['slika']."'><br>"
                            ."<label>Tekst</label><br>"
                            ."<textarea rows=25 cols=80 name='tekst' class='ulaz'>".$vijest['tekst']."</textarea><br>"
                            ."<input type='submit' name='spremiIzmjenuNovosti' value='Spremi izmjenu novosti'"
                            ."class='submit-komentar'>";
                        if($greska)
                            echo "<br><br><label style='color:red; font-weight: bold'>Pogrešni podaci</label>";
                        echo "</form></div>";
                    }
                    function lijeviSideBar(){
                        echo '<div class="lijevo">
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
                        </div>';
                    }
                    function desniSideBar(){
                        echo '<div class="desno">
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
                            </div>';
                    }
                    ?>
            <div style="clear:both;"></div>
            </div>
            <?php
            ucitajPodnozje($username);
            ?>
		</div>
		<div class="vrh">
			<a href="#"><img src="static/images/vrh.png" alt="Idi na vrh"></a>
		</div>
	</body>
</html>