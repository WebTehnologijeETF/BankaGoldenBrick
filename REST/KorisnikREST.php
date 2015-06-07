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
    //Autentikacija
    if(!isset($_SESSION['username'])){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    $array = explode("/", $request);
    $priv = $array[count($array)-2];
    $tmp = $array[count($array)-1];
    $username = htmlentities($tmp);
    $klasa = htmlentities($priv);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    if($klasa==="admin"){
        if($username==="all"){
            $admini = $veza->query(
                "SELECT username, registrovan, email FROM korisnici WHERE admin='1'"
            );
            print "{ \"admini\": " . json_encode($admini->fetchAll()) . "}";
        }else{
            $admini = $veza->prepare(
                "SELECT username, registrovan, email FROM korisnici WHERE admin='1' AND username=?"
            );
            $admini->execute(
                array($username)
            );
            print "{ \"admin\": " . json_encode($admini->fetchAll()) . "}";
        }
    }

}
function rest_post($request, $data) {
    //Autentikacija
    if(!isset($_SESSION['username']) or $_SESSION['tip']!=="admin"){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    $klasa = explode("/", $request);
    $klasa = $klasa[count($klasa)-1];

    if($klasa==="admini"){
        $user = json_decode($data['admin'], true);
        $admin = 1;
    }else{
        $user = json_decode($data['korisnik'], true);
        $admin = 0;
    }

    $username = htmlentities($user['username']);
    $password = htmlentities($user['password']);
    $email = htmlentities($user['email']);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $upit = $veza->prepare(
        "INSERT INTO korisnici (username, password, email, admin) VALUES (:uname, md5(:pass), :mail, :admin)"
    );
    $upit->execute(
        array(
            ':uname'=>$username,
            ':pass'=>$password,
            ':mail'=>$email,
            ':admin'=>$admin
        )
    );
    $upit = $veza->prepare(
        "SELECT username, email FROM korisnici where username=:uname LIMIT 1"
    );
    $upit->execute(
        array(
            ':uname'=>$username
        )
    );
    print "{ \"korisnik\": " . json_encode($upit->fetch()) . "}";
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
    $username = htmlentities($tmp);
    $upit = $veza->prepare("DELETE FROM korisnici WHERE username=:username");
    $upit->bindParam(':username', $username);
    $upit->execute();
    print "Admin deleted";
    die();
}
function rest_put($request, $data) {
    //Autentikacija
    if(!isset($_SESSION['username']) or $_SESSION['tip']!=="admin"){
        header("Location: http://" . $_SERVER["HTTP_HOST"]. "/error.html");
    }
    $uri = explode("/", $request);
    $oldUsername = $uri[count($uri)-1];
    $klasa = $uri[count($uri)-2];

    if($klasa==="admini"){
        $user = json_decode($data['admin'], true);
        $admin = 1;
    }else{
        $user = json_decode($data['korisnik'], true);
        $admin = 0;
    }
    $oldUsername = htmlentities($oldUsername);
    $username = htmlentities($user['username']);
    $password = htmlentities($user['password']);
    $email = htmlentities($user['email']);
    try{
        $veza = connect();
    }catch (PDOException $ex){
        echo $ex->getMessage();
        die();
    }
    $upit = $veza->prepare(
        "UPDATE korisnici SET username=:uname, password=md5(:pass), email=:mail, admin=:admin WHERE username=:old"
    );
    $upit->execute(
        array(
            ':uname'=>$username,
            ':pass'=>$password,
            ':mail'=>$email,
            ':admin'=>$admin,
            ':old'=>$oldUsername
        )
    );
    $upit = $veza->prepare(
        "SELECT username, email FROM korisnici where username=:uname LIMIT 1"
    );
    $upit->execute(
        array(
            ':uname'=>$username
        )
    );
    print "{ \"korisnik\": " . json_encode($upit->fetch()) . "}";
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