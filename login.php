<?php
session_start();
$greska = "NOT OK";
$user = "Anoniman";
require("includes/funkcije.php");
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $user = "admin";
    $greska = "OK";
}else if (isset($_REQUEST['username'])) {
    $username = $_REQUEST['username'];
    $pass = $_REQUEST['password'];
    $username = htmlentities($username);
    $pass = htmlentities($pass);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $sql = "SELECT * FROM korisnici WHERE username=:user AND password=md5(:pass)";
    $q = $veza->prepare($sql);
    $q->execute(array(':user'=>$username, ':pass'=>$pass));
    if($q->rowCount()==1){
        $usr = $q->fetch();
        $email = $usr['email'];
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        if($usr['admin'])
            $_SESSION['tip'] = "admin";
        else
            $_SESSION['tip'] = "korisnik";
        $greska = "OK";
    }else{
        unset($username);
        $greska = "Pogrešan username ili password";
    }
}
if(isset($_REQUEST['resetSifre'])){
    if(empty($_REQUEST['korisnickoIme'])){
        $greska = "Polje korisničko ime ne smije biti prazno.";
        $_REQUEST['reset'] = 1;
    }else {
        try{
            $veza = connect();
        }catch (PDOException $ex){
            echo $ex->getMessage();
            die();
        }
        $uname = htmlentities($_REQUEST['korisnickoIme']);
        $upit = $veza->prepare("SELECT * FROM korisnici WHERE username=:uname");
        $upit->execute(array(':uname'=>$uname));
        if($upit->rowCount()!==1){
            $greska = "Nije pronađen korisnik sa unesenim imenom.";
            $_REQUEST['reset'] = 1;
        }else{
            $user = $upit->fetch();
            $token = sha1(uniqid($uname, true));
            $query = $veza->prepare(
                "INSERT INTO priv_linkovi (username, token, tstamp) VALUES (?, ?, ?)"
            );
            $query->execute(
                array(
                    $uname,
                    $token,
                    $_SERVER["REQUEST_TIME"]
                )
            );
            $url = "http://" . $_SERVER["HTTP_HOST"]. "/reset.php?token=".$token;
            $tekst = "Uvaženi,\r\n\r\nKako biste resetovali Vašu šifru, otvorite sljedeći link:\r\n"
                ."$url\r\n"
                ."Srdačan pozdrav,\r\nVaša GoldenBrick banka";
            posaljiPearMail("goldenbrick@mail.com", $user['email'], "Potvrda resetovanja passworda", $tekst);
            header("Location: login.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
ucitajHead();
?>
    <style>
        .ulaz-login{
            height: 30px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid silver;
            margin-bottom: 15px;
            font-size: 105%;
            padding: 5px 10px;
        }
        .submit-komentar{
            padding: 10px;
        }
        #loginForma{
            margin-top: 100px;
            overflow: auto;
            position: relative;
        }
    </style>
</head>
<body>
<div class="okvir">
<?php
if($greska==="OK"){
    header("Location: index.php");
}
ucitajZaglavlje();
if(isset($_REQUEST['reset'])){
    ucitajResetFormu($greska);
}else {
    ucitajLoginFormu($greska);
}
ucitajPodnozje($user);
function ucitajLoginFormu($greska)
{
    echo "<div class='sjena'></div>\r\n";
    echo "<div class='sadrzaj' style='height: 100vh; text-align: center;'>\r\n";
    echo "<div id='loginForma'>\r\n"
        . "<form method='POST' action='login.php'>\r\n"
        . "<label>Korisničko ime</label><br>\r\n"
        . "<input type='text' name='username' class='ulaz-login' value='";
    if (isset($_REQUEST['username']))
        echo $_REQUEST['username'];
    echo "'><br>\r\n"
        . "<label>Šifra</label><br>\r\n"
        . "<input type='password' name='password' class='ulaz-login'><br>\r\n"
        . "<a href='login.php?reset=1' class='anchor-login'>Zaboravili ste šifru?</a><br><br>"
        . "<input type='submit' name='login' value='Prijavi se' class='submit-komentar'><br>\r\n";
    if ($greska !== "NOT OK") {
        echo "<br><label style='color: red;'>$greska</label>";
    }
    echo "</form>\r\n"
        . "</div>\r\n";
    echo "</div>\r\n";
}
function ucitajResetFormu($greska){
    echo "<div class='sjena'></div>\r\n";
    echo "<div class='sadrzaj' style='height: 100vh; text-align: center;'>\r\n";
    echo "<div id='loginForma'>\r\n"
        . "<form method='POST' action='login.php'>\r\n"
        . "<label>Korisničko ime</label><br>\r\n"
        . "<input type='text' name='korisnickoIme' class='ulaz-login' value='";
    if (isset($_REQUEST['korisnickoIme']))
        echo $_REQUEST['korisnickoIme'];
    echo "'><br>\r\n"
        . "<label>Slučajno generisana šifra će biti poslana na Vašu email adresu.</label><br><br>\r\n"
        . "<input type='submit' name='resetSifre' value='Resetuj šifru' class='submit-komentar'><br>\r\n";
    if ($greska !== "NOT OK") {
        echo "<br><label style='color: red;'>$greska</label>";
    }
    echo "</form>\r\n"
        . "</div>\r\n";
    echo "</div>\r\n";
}
?>
</div>
</body>
</html>