<?php
// retrieve token
if (isset($_GET["token"]) && preg_match('/^[0-9A-F]{40}$/i', $_GET["token"])) {
    $token = $_GET["token"];
}
else {
    throw new Exception("Valid token not provided.");
}

// verify token
require("includes/funkcije.php");
try{
    $veza = connect();
}catch (PDOException $ex){
    echo $ex->getMessage();
    die();
}
$query = $veza->prepare("SELECT username, tstamp FROM priv_linkovi WHERE token = ?");
$query->execute(array($token));
$row = $query->fetch(PDO::FETCH_ASSOC);

if ($row) {
    extract($row);
}
else {
    throw new Exception("Valid token not provided.");
}
$delta = 86400;

if ($_SERVER["REQUEST_TIME"] - $tstamp > $delta) {
    throw new Exception("Token has expired.");
}
//Reset šifre
$upit = $veza->prepare("SELECT * FROM korisnici WHERE username=:uname");
$upit->execute(array(':uname'=>$username));
$user = $upit->fetch();
$randPass = randomPassword();
$hash = md5($randPass);
$upit = $veza->prepare("UPDATE korisnici SET password=:pass WHERE username=:uname");
$upit->execute(array(':uname'=>$username,
                     ':pass'=>$hash));
if(!$upit){
    echo "Database error.";
    die();
}
$tekst = "Uvaženi,\r\n\r\nVaša šifra je resetovana.\r\nKorisničko ime: $uname\r\nNova šifra: $randPass.\r\n\r\n"
        ."Srdačan pozdrav,\r\nVaša GoldenBrick banka";
posaljiPearMail("goldenbrick@mail.com", $user['email'], "Reset passworda", $tekst);
//$poslan = posaljiMail($user['email'], "goldenbrick@mail.com", "Reset šifre", $tekst);
header("Location: login.php");


$query = $db->prepare(
    "DELETE FROM priv_linkovi WHERE username = ? AND token = ? AND tstamp = ?"
);
$query->execute(
    array(
        $username,
        $token,
        $tstamp
    )
);



?>