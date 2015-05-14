<?php
$entry = $_POST["naziv"].".txt";
$path = "novosti/" . $entry;
$novost = fopen($path, "r") or die("Greška u otvaranju fajla");
$naziv = explode(".", $entry)[0];
$vrijeme = fgets($novost);
$autor = fgets($novost);
$naslov = ucfirst(mb_strtolower(fgets($novost), 'UTF-8'));
$slikaURL = fgets($novost);
$tekst = array();
$i = 0;
while (!feof($novost)) {
    $red = fgets($novost);
    if ($red === "--\r\n") {
        continue;
    }
    $tekst[$i] = $red;
    $i += 1;
}
fclose($novost);
$tekst = implode("<br>", $tekst);
$string = "";
$string .= "<div class='clanak'>";
$string .= "<h3>$naslov</h3>";
$string .= "<br>";
$string .= "<div class='clanak_info'>
                                    <p class='autor'><img src='static/images/author.png' alt='autor' class='author'>
                                        <small>$autor</small></p>
                                    <p class='vrijeme'><img src='static/images/date.png' alt='datum' class='datum'>
                                        <small>$vrijeme</small></p>
					            </div>";
if($slikaURL !== "\r\n") {
    $string .= "<img src='$slikaURL' alt='slika clanka' style='width: 50%;'>";
}
$string .= "<p style='font-size: 105%;'>$tekst</p>";
$string .= "</div>";
echo $string;
?>