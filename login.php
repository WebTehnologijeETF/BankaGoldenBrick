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
    $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
    $veza->exec("set names utf8");
    $sql = "SELECT * FROM korisnici WHERE username=:user AND password=md5(:pass)";
    $q = $veza->prepare($sql);
    $q->execute(array(':user'=>$username, ':pass'=>$pass));
    if($q->rowCount()==1){
        $_SESSION['username'] = $username;
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
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        }catch(PDOException $ex){
            echo "MYSQL greška: ".$ex->errorInfo();
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
            $randPass = randomPassword();
            $hash = md5($randPass);
            $upit = $veza->prepare("UPDATE korisnici SET password=:pass WHERE username=:uname");
            $upit->execute(array(':uname'=>$uname,
                                 ':pass'=>$hash));
            if(!$upit){
                echo "Database error.";
                die();
            }
            $tekst = "Uvaženi,\r\n\r\nVaša šifra je resetovana.\r\nKorisničko ime: $uname\r\nNova šifra: $randPass.\r\n\r\n"
                    ."Srdačan pozdrav,\r\nVaša GoldenBrick banka";
            posaljiPearMail("goldenbrick@mail.com", $user['email'], "Reset passworda", $tekst);
            //$poslan = posaljiMail($user['email'], "goldenbrick@mail.com", "Reset šifre", $tekst);
            //if($poslan){
                header("Location: login.php");
            //}
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