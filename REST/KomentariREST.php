<?php
session_start();
//Funkcija zaglavlje (Postavke headera)
function zag() {
    header("{$_SERVER['SERVER_PROTOCOL']} 200 OK");
    header('Content-Type: text/html');
    header('Access-Control-Allow-Origin: *');
}
//REST funkcije za manipulaciju podacima
function rest_get($request, $data) {
    $userType = "Anoniman";
    if(isset($_SESSION['username']) and $_SESSION['tip']==="admin"){
        $userType = "Administrator";
    }
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    if(isset($data['vijestID'])){
        $vijestId = intval(htmlentities($data['vijestID']));
        $upit = $veza->prepare("SELECT * FROM komentari WHERE novost=:id");
        $upit->bindParam(':id', $vijestId, PDO::PARAM_INT);
        $upit->execute();
        $komentar = $upit->fetchAll();
        if($userType==="Anoniman") {
            print "{ \"komentari\": " . json_encode($komentar) . "}";
        }else{
            print "{ \"komentariAdmin\": " . json_encode($komentar) . "}";
        }
    }else{
        $id = htmlentities($data['id']);
        $id = intval($id);
        $upit = $veza->prepare("SELECT * FROM komentari WHERE id=:id LIMIT 1");
        $upit->bindParam(':id', $id, PDO::PARAM_INT);
        $upit->execute();
        $komentar = $upit->fetch();
        print "{ \"komentar\": " . json_encode($komentar) . "}";
    }
}
function rest_post($request, $data) {
    $userType = "Anoniman";
    if(isset($_SESSION['username']) and $_SESSION['tip']==="admin"){
        $userType = "Administrator";
    }
    $autor = "anoniman";
    $email = "";
    if(isset($_SESSION['username'])){
        $autor = $_SESSION['username'];
        $email = $_SESSION['email'];
    }
    $komentar = json_decode($data['komentar'], true);
    $novost = htmlentities($komentar['novostID']);
    $tekst = htmlentities($komentar['tekst']);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $upit = $veza->prepare("INSERT INTO komentari (novost, autor, email, tekst) VALUES (:novost, :autor, :email, :tekst)");
    $upit->execute(array(':novost'=>$novost,
        ':autor'=>$autor,
        ':email'=>$email,
        ':tekst'=>$tekst));
    if(!$upit){
        $greska = array('greska'=>'Komentar nije spremljen.');
        print "{ \"greska\": ".json_encode($greska)."}";
        die();
    }
    $string = "SELECT * FROM komentari where novost=:novost AND autor=:autor AND email=:email"
        ." AND tekst=:tekst ORDER BY vrijeme DESC LIMIT 1";
    $upit = $veza->prepare($string);
    $upit->execute(array(':novost'=>$novost,
        ':autor'=>$autor,
        ':email'=>$email,
        ':tekst'=>$tekst));
    if(!$upit){
        $greska = array('greska'=>'Komentar nije spremljen.');
        print "{ \"greska\": ".json_encode($greska)."}";
        die();
    }
    if($userType==="Anoniman") {
        print "{ \"komentar\": " . json_encode($upit->fetch()) . "}";
    }else{
        print "{ \"komentarAdmin\": " . json_encode($upit->fetch()) . "}";
    }

}
function rest_delete($request) {
    //Autentikacija
    if(!isset($_SESSION['username']) or $_SESSION['tip']!=="admin"){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $tmp = explode("/", $request);
    $tmp = $tmp[count($tmp)-1];
    $id = intval(htmlentities($tmp));
    $upit = $veza->prepare("DELETE FROM komentari WHERE id=:id");
    $upit->bindParam(':id', $id, PDO::PARAM_INT);
    $upit->execute();
    print "Comment deleted";
    die();
}
function rest_put($request, $data) {
    //Autentikacija
    if(!isset($_SESSION['username']) or $_SESSION['tip']!=="admin"){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    $id = intval(htmlentities($data['id']));
    $komentar = json_decode($data['novost']);
    $novost = htmlentities($komentar['novostID']);
    $autor = htmlentities($komentar['autor']);
    $email = htmlentities($komentar['email']);
    $tekst = htmlentities($komentar['tekst']);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $upit = $veza->prepare("UPDATE komentari SET novost=:novost, autor=:autor, email=:email, tekst=:tekst WHERE id=:id");
    $upit->execute(array(':novost'=>$novost,
        ':autor'=>$autor,
        ':email'=>$email,
        ':tekst'=>$tekst));
    $string = "SELECT * FROM koemntari where id=:id LIMIT 1";
    $upit = $veza->prepare($string);
    $upit->execute(array(':id'=>$id));
    print "{ \"komentar\": " . json_encode($upit->fetch()) . "}";
}
function rest_error($request) {
    $greska = array('greska'=>"Vrsta greške");
    $json = "{ \"Greška\": ".json_encode($greska)."}";
    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    print $json;
}

$method  = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];
require("../includes/funkcije.php");

switch($method) {
    case 'PUT':
        parse_str(file_get_contents('php://input'), $put_vars);
        zag(); $data = $put_vars; rest_put($request, $data); break;
    case 'POST':
        zag(); $data = $_POST; rest_post($request, $data); break;
    case 'GET':
        zag(); $data = $_GET; rest_get($request, $data); break;
    case 'DELETE':
        zag(); rest_delete($request); break;
    default:
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        rest_error($request); break;
}
?>