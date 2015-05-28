<?php
$vijestId = htmlentities($_REQUEST['id']);
try{
    $veza = new PDO("mysql:dbname=goldenbrick;host:localhost;charset:utf8", "goldenbrickDB", "shawshank");
}catch(PDOException $ex){
    echo "MYSQL greška: ".$ex->errorInfo();
    die();
}
$veza->exec("set names utf8");
$rezultat = $veza->prepare("SELECT * FROM novosti WHERE id=:id");
$rezultat->bindParam(':id', $vijestId);
$rezultat->execute();
if(!$rezultat){
    $greska = $veza->errorInfo();
    print "SQL greška: ".$greska;
    exit();
}
$novost = $rezultat->fetch();
$vrijeme = $novost['vrijeme'];
$autor = $novost['autor'];
$naslov = ucfirst(mb_strtolower($novost['naslov'], 'UTF-8'));
$slikaURL = $novost['slika'];
$tekst = nl2br(str_replace("--\r\n", "", $novost['tekst']));
$string = "";
$string .= "<div class='clanak'>";
$string .= "<h3>$naslov</h3>";
$string .= "<br>";
$string .= "<div class='clanak_info'>
                                    <p class='autor'><img src='static/images/author.png' alt='autor' class='author'>
                                        <small>$autor</small></p>
                                    <p class='vrijeme'><img src='static/images/date.png' alt='datum' class='datum'>
                                        <small>$vrijeme</small></p>
					            </div><div class='tekst-clanka'>";
if($slikaURL !== "") {
    $string .= "<img src='$slikaURL' alt='slika clanka' style='width: 50%;'>";
}
$string .= "<p style='font-size: 105%;'>$tekst</p></div>";
$string .= "</div>";
echo $string;
?>