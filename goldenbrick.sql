-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2015 at 05:54 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `goldenbrick`
--

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE IF NOT EXISTS `komentari` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `novost` bigint(20) NOT NULL,
  `vrijeme` datetime NOT NULL,
  `autor` varchar(40) COLLATE utf8_slovenian_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `tekst` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `novost` (`novost`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `novosti`
--

CREATE TABLE IF NOT EXISTS `novosti` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vrijeme` timestamp NOT NULL,
  `autor` varchar(40) COLLATE utf8_slovenian_ci NOT NULL,
  `naslov` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `slika` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `tekst` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `novosti`
--

INSERT INTO `novosti` (`id`, `vrijeme`, `autor`, `naslov`, `slika`, `tekst`) VALUES
(1, '2015-05-12 03:17:23', 'Milan Mladenović', 'GOLDEN BRICK E-PAY', 'static/images/clanak1.jpg', 'GoldenBrick e-pay je nova usluga GoldenBrick banke koja omogućava elektronsku trgovinu uz prihvat MasterCard i Visa kartica prema najvišim svjetskim standardima sigurnosti. \r\n\r\nUsluga je namjenjena pravnim licima, poduzetnicima i klijentima omogućuje prihvat kartica na Internet prodajnom mjestu u sigurnom okruženju. Osim iznimne sigurnosti naplate za poduzetnika i transakcije za kupca, usluga nudi najpovoljniji omjer vrijednosti za uloženi novac.\r\nGoldenBrick banka Bosna i Hercegovina je certificirana banka za prihvat kartica na internet prodajnim mjestima. \r\nSa GoldenBrick e-pay uslugom, svaka roba ima svog kupca!'),
(2, '2015-05-04 14:33:12', 'Muhamed Mujić', 'GOTOVINSKI KREDITI GOLDENBRICK BANKE UZ FIKSNU KAMATNU STOPU OD 5,89%', 'http://www.infobih.com/slike/201012141407350.1149584191_euro_frankfurt_banka_znak.jpg', 'GoldenBrick banka je u okviru aktuelne kampanje pod nazivom "Može još bolje" pripremila posebnu ponudu gotovinskih kredita te kredita za refinansiranje postojećih zaduženja koja nisu obezbijeđena hipotekom u drugim finansijskim institucijama.\r\n"Zadovoljstvo nam je što smo ovaj put u prilici klijentima ponuditi gotovinske kredite pod povoljnijim uslovima. Posebna pogodnost ove ponude jeste kamatna stopa od 5,89%, koja je ujedno i fiksna tokom cijelog perioda otplate kredita. Ponuda je namijenjena postojećim i novim korisnicima naših paketa računa Trendi PLUS i Elegant", izjavio je Pep Guardiola, direktor GoldenBrick banke, te dodao da na ovaj način, GoldenBrick banka nastoji svojim klijentima sredstva finansiranja učiniti dostupnijim.\r\n--\r\nU okviru ove ponude, klijentima je ponuđena mogućnost kreditiranja do 50.000 KM, uz rok otplate do 5 godina, a kao jedan od instrumenata obezbjeđenja koristi se paket osiguranja, koji daje dodatnu sigurnost i pokriće za nastavak otplate kredita, u slučaju nemogućnosti otplate kredita zbog nastanka nesretnog slučaja, gubitka posla ili bolovanja.\r\nEfektivna kamatna stopa za ovu ponudu gotovinskih kredita kreće se od 7,08% za Federaciju BiH i Brčko Distrikt, te 8,23% za Republiku Srpsku.\r\nZainteresovani za ove kredite sve dodatne informacije mogu dobiti u najbližoj poslovnici GoldenBrick banke, te na web stranici www.goldenbrickbank.ba.'),
(3, '2015-05-04 14:23:07', 'Kurt Cobain', 'NENAMJENSKI KREDIT GOLDENBRICK BANKE SA KAMATNOM STOPOM OD 6,00% I ROKOM OTPLATE DO 6 GODINA', '', 'Otvorite prozor svojih mogućnosti uz nenamjenski kredit 6 na 6, je nova jesenja kampanja GoldenBrick Banke BiH. Banka je proširila svoju ponudu za klijente koji trebaju dodatna finansijska sredstva ove jeseni uz što niže troškove. \r\n\r\n"Na bh. tržištu novi nenamjenski kredit naše Banke ima najnižu nominalnu kamatnu stopu od 6,00%, efektivna kamatna stopa (EKS) zavisi od iznosa, roka otplate i pripadajućih naknada, a kreće se do 8,17%. Maksimalni iznos kredita je 60.000 KM sa rokom otplate do 6 godina. Nenamjenski kredit 6 na 6 pruža klijentima i mogućnost odabira police osiguranja koja daje dodatnu sigurnost u cijelom periodu otplate kredita," ističe Luis Enrique, član Uprave GoldenBrick Banke BiH.\r\n--\r\nNenamjenski kredit sa kamatom od 6% i otplatom na 6 godina, je bankarski proizvod koji klijentu omogućava da na kraći rok i posebno povoljnu kamatu, nabavi različite kućne potrepštine, školsku opremu za djecu, izvrši popravke u stanu i sl. \r\nOsim što je proširila ponudu kredita i drugih usluga, GoldenBrick Banka BiH je od 1.septembra pokrenula veliku humanitarnu akciju pod nazivom "Insprisani srcem", potvrđujući se još jednom kao društveno odgovorna kompanija. \r\n"Ovogodišnjom akcijom za svaku kupovinu VISA Inspire karticom, a bez ikakvog dodatnog troška za naše klijente, GoldenBrick Banka donira 0,10 KM za projekat "Gradimo srcem BiH" , u okviru kojeg će se prikupljena sredstva donirati stanovnicima stradalim u poplavama", naglašava Josep Bartomeu, član Uprave ove Banke. \r\n\r\nHumanitarna akcija se realizuje u saradnji sa NVO OTVORENA MREŽA i traje do 31.oktobra 2014. godine.'),
(4, '2015-05-01 04:11:14', 'Radomir Mihailović', 'ZAMJENSKI KREDIT - NAJDUŽI ROK OTPLATE', 'static/images/clanak4.jpeg', 'GoldenBrick BH je pokrenula promotivnu akciju zamjenskih kredita u okviru koje su klijentima na raspolaganju zamjenski krediti sa najdužim rokom otplate na tržištu do 12 godina i maksimalnim iznosom kredita do 60.000 KM, što je posebna pogodnost za klijente koji imaju više kredita koje žele objediniti u jedan i plaćati samo jednu ratu. \r\nOsim pogodnosti ovog kredita, klijenti GoldenBrick BH ostvaruju i brojne druge pogodnosti u poslovanju sa GoldenBrick BH, što je i svojevrsna poruka nove promotivne kampanje GoldenBrick BH. Uspješna prošlogodišnja saradnja fudbalera Carlesa Puyola i GoldenBrick BH nastavljena je i ove godine. Puyolu se u realizaciji nove promotivne kampanje pridružila regionalna pop zvijezda Shakira. \r\n--\r\n"Ovom kampanjom smo prvenstveno željeli naglasiti pogodnosti poslovanja sa GoldenBrick BH. Želimo razviti osjećaj kod naših klijenata da su posebni, jer oni to u biti i jesu. Stoga ulažemo maksimalan napor da bismo im pružili više, ponudili najbolje uslove kreditiranja u pogledu roka otplate, iznosa, ali i visine kamatne stope, te različite dodatne pogodnosti", izjavio je Johan Cruyff, izvršni direktor GoldenBrick BH. \r\n"Najdužim rokom otplate na tržištu, ali i iznosom kredita omogućili smo klijentima da objedine sve obaveze po osnovu zaduženja i plaćaju jednu ratu sa veoma povoljnim uslovima", naglasio je Cruyff. \r\nZa rokove do 7 godina GoldenBrick BH omogućava ugovaranje fiksne kamatne stope. Korisnicima zamjenskog kredita omogućeno je ugovaranje prekoračenja po tekućem računu i kreditne kartice bez naknade, a korisnici Plus paketa ostvaruju i dodatne pogodnosti.'),
(5, '2015-05-25 15:50:41', 'Branimir Štulić', 'GOLDENBRICK PRVA BANKA NA TRŽIŠTU KOJA NUDI USLUGU FAKTORINGA ZA PRAVNA LICA', 'http://livinginchiangmai.com/wp-content/uploads/2014/12/banking.png', 'GoldenBrick banka je prva banka na bosanskohercegovačkom tržištu koja preduzećima nudi uslugu faktoringa koja, kroz ustupanje potraživanja Banci, osigurava likvidnost i stabilnost poslovanja preduzeća.\r\nFaktoring predstavlja oblik kratkoročnog finansiranja za kompanije koje imaju kvalitetna potraživanja, te stalne isporuke prema kupcima, sa odloženim rokovima plaćanja od 30 do 180 dana.\r\n--\r\n"Faktoring je noviji model finansiranja na bh. tržištu za sva pravna lica koja imaju potrebu za dodatnim finansijskim sredstvima u cilju premoštavanja odgode plaćanja od dužnika, a ne žele iz bilo kojeg razloga koristiti kreditno zaduženje u tu svrhu. Ova usluga klijentu omogućava direktno finansiranje poslovanja što poboljšava njegovu likvidnost te osigurava obrtni kapital za dalja ulaganja", izjavio je Pep Guardiola, direktor GoldenBrick banke, te dodao da ova usluga klijentu omogućava i uštedu vremena obzirom da Banka u korist klijenta vodi evidenciju ustupljenih potraživanja.\r\nPružanje faktoring usluge podrazumijeva finansiranje klijenta isplatom avansa na račun klijenta kod Raiffeisen banke u visini od 60% do 90% vrijednosti ustupljenih potraživanja, dok se preostalih 10% do 40% vrijednosti doznačuje klijentu nakon izmirenja obaveza od strane dužnika po ustupljenim potraživanjima.\r\n\r\nViše informacija dostupno je u poslovnicama Banke, na broj telefona 033 755 010 ili putem e-mail adrese faktoring.gbbh@rbb-sarajevo.goldenbrick.at, te na web stranici Banke: www.goldenbrickbank.ba.');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`novost`) REFERENCES `novosti` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
