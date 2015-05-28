<?php
session_start();
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
    header("Location: index.php");
}
$greska = false;
$tekstGreske = "";
if(isset($_REQUEST['dodajNovost'])){
    if(empty($_REQUEST['autor']) || empty($_REQUEST['naslov']) || empty($_REQUEST['tekst'])){
        $greska = true;
    }else{
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        }catch(PDOException $ex){
            echo "MYSQL greška: ".$ex->errorInfo();
            die();
        }
        $upit = $veza->prepare("INSERT INTO novosti (autor, naslov, slika, tekst) VALUES (:autor, :naslov, :slika, :tekst)");
        $autor = htmlentities($_REQUEST['autor']);
        $naslov = htmlentities($_REQUEST['naslov']);
        $slika = htmlentities($_REQUEST['slika']);
        $tekst = htmlentities($_REQUEST['tekst']);
        $upit->execute(array(':autor'=>$autor,
                            ':naslov'=>$naslov,
                            ':slika'=>$slika,
                            ':tekst'=>$tekst));
        if(!$upit){
            echo "Greška prilikom dodavanja novosti.";
        }
    }
}
if(isset($_REQUEST['dodajAdmina'])){
    if(empty($_REQUEST['username']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])){
        $greska = true;
        $tekstGreske = "Polja nisu popunjena.";
    }else{
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        }catch(PDOException $ex){
            echo "MYSQL greška: ".$ex->errorInfo();
            die();
        }
        $uname = htmlentities($_REQUEST['username']);
        $upit = $veza->prepare("SELECT * FROM korisnici WHERE username=:uname");
        $upit->execute(array(':uname'=>$uname));
        if($upit->rowCount()===1){
            $tekstGreske = "Korisničko ime je u upotrebi.";
            $greska = true;
        }else {
            $upit = $veza->prepare("INSERT INTO korisnici (username, password, email) VALUES (:uname, :pass, :email)");
            $pass = htmlentities($_REQUEST['password']);
            $pass = md5($pass);
            $email = htmlentities($_REQUEST['email']);
            $upit->execute(array(':uname' => $uname,
                ':pass' => $pass,
                ':email'=>$email));
            if (!$upit) {
                echo "Greška prilikom dodavanja administratora.";
            }
        }
    }
}
if(isset($_REQUEST['promijeniAdmina'])){
    if(empty($_REQUEST['username']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])){
        $greska = true;
        $tekstGreske = "Polja nisu popunjena.";
    }elseif(!isset($_REQUEST['admin'])) {
        $greska = true;
        $tekstGreske = "Administrator nije izabran.";
    }else{
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        }catch(PDOException $ex){
            echo "MYSQL greška: ".$ex->errorInfo();
            die();
        }
        $uname = htmlentities($_REQUEST['username']);
        $old = htmlentities($_REQUEST['admin']);
        $different = true;
        if($uname===$old)
            $different = false;
        $upit = $veza->prepare("SELECT * FROM korisnici WHERE username=:uname");
        $upit->execute(array(':uname'=>$uname));
        if($different and $upit->rowCount()===1){
            $tekstGreske = "Korisničko ime je u upotrebi.";
            $greska = true;
        }else {
            $upit = $veza->prepare("UPDATE korisnici SET username=:uname, password=:pass, email=:email WHERE username=:old");
            $pass = htmlentities($_REQUEST['password']);
            $pass = md5($pass);
            $email = htmlentities($_REQUEST['email']);
            $upit->execute(array(':uname' => $uname,
                ':pass' => $pass,
                ':email'=>$email,
                ':old'=>$old));
            if (!$upit) {
                echo "Greška prilikom dodavanja administratora.";
            }
        }
    }
}
if(isset($_REQUEST['obrisiAdmina'])){
    if(!isset($_REQUEST['admin'])) {
        $greska = true;
        $tekstGreske = "Administrator nije izabran.";
    }else {
        $admin = htmlentities($_REQUEST['admin']);
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        } catch (PDOException $ex) {
            echo "MYSQL greška: " . $ex->errorInfo();
            die();
        }
        $upit = $veza->query("SELECT * FROM korisnici");
        if($upit->rowCount()===1){
            $greska = true;
            $tekstGreske = "Nije dozvoljeno brisati jedinog administratora.";
        }else {
            $upit = $veza->prepare("DELETE FROM korisnici WHERE username=:uname");
            $upit->bindParam(':uname', $admin);
            $upit->execute();
            if (!$upit) {
                echo "Greška prilikom brisanja administratora.";
                echo "MYSQL: " . $upit->errorInfo();
                die();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    require("includes/funkcije.php");
    ucitajHead();
    ?>
    <script>
        function popuniUsername(){
            //console.log("MMM");
            var drop = document.izmjenaAdmina.admin;
            var ime = drop.options[drop.selectedIndex].value;
            document.izmjenaAdmina.username.removeAttribute("value");
            document.izmjenaAdmina.username.setAttribute("value", ime);
        }
    </script>
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
    ucitajZaglavlje();
    echo "<div class='sjena'></div>\r\n";
    echo "<div class='sadrzaj' style='text-align: center;'>\r\n";
    echo "<form method='post' action='index.php' style='text-align: right;margin: 10px; width: 98%'>\r\n"
        ."<label>Ulogovani ste kao: <span class='gold'>".$username."</span></label>"
        ."<input type='submit' name='logout' value='Odjavi se'"
        ."class='submit-komentar' style='margin-left:10px;'>\r\n"
        ."</form>\r\n";
    //echo "<br><br><br>";
    echo "<div id='adminMeni'>";
    echo "<a href='admin.php?create=news' class='anchor-admin'>Dodavanje novosti</a>";
    echo "<label>|</label>";
    echo "<a href='admin.php?create=admin' class='anchor-admin'>Dodavanje administratora</a>";
    echo "<label>|</label>";
    echo "<a href='admin.php?update=admin' class='anchor-admin'>Izmjena administratora</a>";
    echo "<label>|</label>";
    echo "<a href='admin.php?delete=admin' class='anchor-admin'>Brisanje administratora</a>";
    echo "</div>";
    if(isset($_REQUEST['create']) and $_REQUEST['create']=="news"){
        unosNovosti($greska);
    }
    if(isset($_REQUEST['create']) and $_REQUEST['create']=="admin"){
        unosAdmina($greska, $tekstGreske);
    }
    if(isset($_REQUEST['update']) and $_REQUEST['update']=="admin"){
        promjenaAdmina($greska, $tekstGreske);
    }
    if(isset($_REQUEST['delete']) and $_REQUEST['delete']=="admin"){
        obrisiAdmina($greska, $tekstGreske);
    }
    echo "</div>";
    ucitajPodnozje();
    function unosNovosti($greska){

        echo "<div class='dodavanje'>"
            ."<h2>Dodavanje novosti</h2><br><br>"
            ."<form method='post' action='admin.php?create=news'>"
            ."<label>Autor</label><br>"
            ."<input type='text' name='autor' class='ulaz'><br>"
            ."<label>Naslov</label><br>"
            ."<input type='text' name='naslov' class='ulaz'><br>"
            ."<label>URL slike</label><br>"
            ."<input type='text' name='slika' class='ulaz'><br>"
            ."<label>Tekst</label><br>"
            ."<textarea rows=25 cols=80 name='tekst' class='ulaz'></textarea><br>"
            ."<input type='submit' name='dodajNovost' value='Dodaj novost' class='submit-komentar'>";
        if($greska)
            echo "<br><br><label style='color:red; font-weight: bold'>Pogrešni podaci</label>";
        echo "</form></div>";
    }
    function unosAdmina($greska, $tekstGreske){
        echo "<div class='dodavanje'>"
            ."<h2>Dodavanje administratora</h2><br><br>"
            ."<form method='post' action='admin.php?create=admin'>"
            ."<label>Korisničko ime</label><br>"
            ."<input type='text' name='username' class='ulaz'><br>"
            ."<label>Šifra</label><br>"
            ."<input type='text' name='password' class='ulaz'><br>"
            ."<label>Email</label><br>"
            ."<input type='text' name='email' class='ulaz'><br>"
            ."<input type='submit' name='dodajAdmina' value='Dodaj administratora' class='submit-komentar'>";
        if($greska)
            echo "<br><br><label style='color:red; font-weight: bold'>$tekstGreske</label>";
        echo "</form></div>";
    }
    function promjenaAdmina($greska, $tekstGreske){
        echo "<div class='dodavanje'>"
            ."<h2>Promjena administratora</h2><br>";
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        }catch(PDOException $ex){
            echo "MYSQL greška: ".$ex->errorInfo();
            die();
        }
        echo "<form name='izmjenaAdmina' method='post' action='admin.php?update=admin'>";
        $upit = $veza->query("SELECT * FROM korisnici");
        if(!$upit or $upit->rowCount()===0){
            echo "Nema unesenih korisnika.";
            return;
        }else {
            echo "<select name='admin' required onchange='popuniUsername();'>";
            echo "<option value='placeholder' disabled selected>Izaberite administratora</option>";
            foreach($upit as $korisnik){
                echo "<option value='".$korisnik['username']."'>".$korisnik['username']."</option>";
            }
            echo "</select><br><br>";
        }

        echo"<label>Novo korisničko ime</label><br>"
            ."<input type='text' name='username' class='ulaz'><br>"
            ."<label>Nova šifra</label><br>"
            ."<input type='text' name='password' class='ulaz'><br>"
            ."<label>Novi email</label><br>"
            ."<input type='text' name='email' class='ulaz'><br>"
            ."<input type='submit' name='promijeniAdmina' value='Promijeni administratora' class='submit-komentar'>";
        if($greska)
            echo "<br><br><label style='color:red; font-weight: bold'>$tekstGreske</label>";
        echo "</form></div>";
    }
    function obrisiAdmina($greska, $tekstGreske){
        echo "<div class='dodavanje'>"
            ."<h2>Brisanje administratora</h2><br>";
        try {
            $veza = new PDO("mysql:dbname=goldenbrick;host=localhost;charset=utf8", "goldenbrickDB", "shawshank");
        }catch(PDOException $ex){
            echo "MYSQL greška: ".$ex->errorInfo();
            die();
        }
        echo "<form name='brisanjeAdmina' method='post' action='admin.php?delete=admin'>";
        $upit = $veza->query("SELECT * FROM korisnici");
        if(!$upit or $upit->rowCount()===0){
            echo "Nema unesenih korisnika.";
            return;
        }else {
            echo "<select name='admin' onchange='popuniUsername();'>";
            echo "<option value='placeholder' disabled selected>Izaberite administratora</option>";
            foreach($upit as $korisnik){
                echo "<option value='".$korisnik['username']."'>".$korisnik['username']."</option>";
            }
            echo "</select><br><br>";
            echo "<input type='submit' name='obrisiAdmina' value='Obriši administratora' class='submit-komentar'>";
            if($greska)
                echo "<br><br><label style='color:red; font-weight: bold'>$tekstGreske</label>";
        }
        echo "</form></div>";
    }
    ?>
</div>
</body>
</html>