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
    $array = explode("/", $request);
    $tmp = explode("?", $array[count($array)-1]);
    $tmp = $tmp[0];
    $id = htmlentities($tmp);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    if(isset($data['timestamp'])) {
        $timestamp = htmlentities($data['timestamp']);
        $timestamp = date("Y-m-d H:i:s", strtotime($timestamp));
        $upit = $veza->prepare(
            "SELECT n.id, n.vrijeme, n.autor, n.naslov, n.slika, n.tekst, ".
            "(SELECT COUNT(k.id) FROM komentari k WHERE k.novost = n.id) AS 'broj_komentara' ".
            "FROM novosti n WHERE n.vrijeme > :tms ORDER BY n.vrijeme"
        );
        $upit->bindParam(':tms', $timestamp);
        $upit->execute();
        if(!$upit){
            http_response_code(404);
            die();
        }
        if($upit->rowCount()==0){
            http_response_code(300);
            die();
        }
        $novosti = $upit->fetchAll();
        if(isset($_SESSION['username']) and $_SESSION['tip']==="admin"){
            print "{ \"novostiAdmin\": " . json_encode($novosti) . "}";
        }else {
            print "{ \"novosti\": " . json_encode($novosti) . "}";
        }
        die();
    }

    if($id==="all"){
        $novosti = $veza->query(
            "SELECT n.id, n.vrijeme, n.autor, n.naslov, n.slika, n.tekst, COUNT(k.id) as 'broj_komentara' ".
            "FROM novosti n LEFT JOIN komentari k ON n.id=k.novost GROUP BY n.id ORDER BY vrijeme DESC"
        );
        if(isset($_SESSION['username']) and $_SESSION['tip']==="admin"){
            print "{ \"novostiAdmin\": " . json_encode($novosti->fetchAll()) . "}";
        }else {
            print "{ \"novosti\": " . json_encode($novosti->fetchAll()) . "}";
        }
    }else{
        $id = intval($id);
        $upit = $veza->prepare(
            "SELECT n.id, n.vrijeme, n.autor, n.naslov, n.slika, n.tekst, ".
            "(SELECT COUNT(k.id) FROM komentari k WHERE k.novost = n.id) AS 'broj_komentara' ".
            "FROM novosti n WHERE n.id = :id LIMIT 1"
        );
        $upit->bindParam(':id', $id, PDO::PARAM_INT);
        $upit->execute();
        $novost = $upit->fetch();
        print "{ \"novost\": " . json_encode($novost) . "}";
    }
}
function rest_post($request, $data) {
    //Autentikacija
    if(!isset($_SESSION['username']) or $_SESSION['tip']!=="admin"){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    $novost = json_decode($data['novost'], true);
    $autor = htmlentities($novost['autor']);
    $naslov = htmlentities($novost['naslov']);
    $slika = htmlentities($novost['slika']);
    $tekst = htmlentities($novost['tekst']);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $upit = $veza->prepare("INSERT INTO novosti (autor, naslov, slika, tekst) VALUES (:autor, :naslov, :slika, :tekst)");
    $upit->execute(array(':autor'=>$autor,
                        ':naslov'=>$naslov,
                        ':slika'=>$slika,
                        ':tekst'=>$tekst));
    $string = "SELECT * FROM novosti where autor=:autor AND naslov=:naslov AND slika=:slika"
             ." AND tekst=:tekst ORDER BY vrijeme DESC LIMIT 1";
    $upit = $veza->prepare($string);
    $upit->execute(array(':autor'=>$autor,
        ':naslov'=>$naslov,
        ':slika'=>$slika,
        ':tekst'=>$tekst));
    print "{ \"novosti\": " . json_encode($upit->fetch()) . "}";
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
    $upit = $veza->prepare("DELETE FROM novosti WHERE id=:id");
    $upit->bindParam(':id', $id, PDO::PARAM_INT);
    $upit->execute();
    print "News deleted";
    die();
}
function rest_put($request, $data) {
    //Autentikacija
    if(!isset($_SESSION['username']) or $_SESSION['tip']!=="admin"){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    $tmp = explode("/", $request);
    $tmp = $tmp[count($tmp)-1];
    $id = intval(htmlentities($tmp));
    $novost = json_decode($data['novost'], true);
    $autor = htmlentities($novost['autor']);
    $naslov = htmlentities($novost['naslov']);
    $slika = htmlentities($novost['slika']);
    $tekst = htmlentities($novost['tekst']);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $upit = $veza->prepare(
        "UPDATE novosti SET autor=:autor, naslov=:naslov, slika=:slika, tekst=:tekst WHERE id=:id"
    );
    $upit->execute(
        array(
            ':autor'=>$autor,
            ':naslov'=>$naslov,
            ':slika'=>$slika,
            ':tekst'=>$tekst,
            ':id'=>$id)
    );
    $string = "SELECT * FROM novosti where id=:id LIMIT 1";
    $upit = $veza->prepare($string);
    $upit->execute(array(':id'=>$id));
    print "{ \"novost\": " . json_encode($upit->fetch()) . "}";
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