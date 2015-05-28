<?php
function ucitajZaglavlje(){
    echo '<div class="zaglavlje">
				<div class="meni">
					<ul>
						<li>
							<a href="index.php"  class="logoSlika"></a>
						</li>
						<li>
							<a href="index.php"  class="logo"><span class="spanLogo">G</span>olden<span class="spanLogo">B</span>rick</a>
						</li>
						<li class="fokus"><a href="">Naslovnica</a></li>
						<li class="padajuci"><a href="savings.html">Štednja</a>
							<ul>
								<li><a href="savings1.html" class="ajaxLoaderLink meniLink">A Vista</a></li>
								<li><a href="savings2.html" class="ajaxLoaderLink meniLink">Dječija štednja</a></li>
							</ul>
						</li>
                        <li><a href="services.html">Usluge</a></li>
						<li><a href="about.html">O nama</a></li>
						<li><a href="contact.php">Kontakt</a></li>
						<li id="search">
							<form method="get">
								<input type="text" name="search_text" id="search_text" placeholder="Pretraži"/>
								<input type="button" name="search_button" id="search_button" value=" ">
							</form>
						</li>
					</ul>
				</div>
            </div>';
}
function ucitajPodnozje($uname){
    echo '<div class="podnozje">
                <label class="label-footer">&copy; 2015 GoldenBricks</label>
                <div id="login-div">
                    <a href="index.php" class="anchor-login">Home</a>
                    <label>|</label>
                    <a href="savings.html" class="anchor-login">Savings</a>
                    <label>|</label>
                    <a href="services.html" class="anchor-login">Services</a>
                    <label>|</label>
                    <a href="about.html" class="anchor-login">About us</a>
                    <label>|</label>
                    <a href="contact.php" class="anchor-login">Contact us</a>';
    if($uname==="Anoniman") {
        echo '<label>|</label>
            <a href="login.php" class="anchor-login">Login</a>';
    }
    echo '</div>
                <label class="label-footer">Sva prava pridržana.</label>
        </div>';
}
function ucitajHead(){
    echo '<head>
		<title>GoldenBrick | Naslovnica</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="static/css/stil.css">
		<link rel="icon" type="image/x-icon" href="static/images/icon.ico">
		<script src="static/js/meni.js"></script>
        <script src="static/js/loadAjax.js"></script>
	</head>';
}
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
function posaljiMail($mailTo, $mailFrom, $subject, $tekst){
    require("sendgrid-php/sendgrid-php.php");
    $sendgrid = new SendGrid("muhamed", "mijenjam45bravu");
    $email    = new SendGrid\Email();
    $email->addTo($mailTo)
        ->setFrom($mailFrom)
        ->setSubject($subject)
        ->setHeaders(array('Content-Type' => 'text/html',  'charset'=>'UTF-8'))
        ->setText($tekst);
    $sendgrid->send($email);
    return true;
}
?>