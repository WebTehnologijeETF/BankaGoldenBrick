-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2015 at 09:48 PM
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
  `vrijeme` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `novost` bigint(20) NOT NULL,
  `autor` varchar(40) COLLATE utf8_slovenian_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `tekst` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `novost` (`novost`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`id`, `vrijeme`, `novost`, `autor`, `email`, `tekst`) VALUES
(12, '2015-05-26 23:33:36', 4, 'Muhamed Mujić', 'mujic-m@hotmail.com', 'Ovo je moj prvi komentar.'),
(15, '2015-05-26 23:41:44', 1, 'Ime i Prezime', 'adresa@email.com', 'Tekst komentara.'),
(17, '2015-05-27 22:31:54', 4, 'Osoba 1', '', 'Komentar osobe 1.'),
(18, '2015-05-27 23:05:27', 2, 'Muhamed Mujić', 'mujic-m@hotmail.com', 'Tekst komentara.'),
(19, '2015-05-27 23:21:45', 10, 'Osoba 2', 'osoba2@mail.eu', 'Komentar osobe 2.'),
(21, '2015-05-27 23:26:53', 10, 'Osoba 1', '', 'Zabranjeno je pu&scaron;enje u kretanju.\r\nNe služimo pijane goste.'),
(22, '2015-05-27 23:37:58', 2, 'haker', '', '&lt;script&gt;alert(&quot;hakovan&quot;);&lt;/script&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE IF NOT EXISTS `korisnici` (
  `username` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_slovenian_ci NOT NULL,
  `registrovan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`username`, `password`, `registrovan`, `email`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '2015-05-28 17:58:53', 'admin@mail.com'),
('hacker', 'bb2b6c88b4147a5f7ad18195027d359a', '2015-05-28 19:39:32', 'hacker@live.com'),
('muhamed', '923352284767451ab158a387a283df26', '2015-05-28 19:38:50', 'mujic-m@hotmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `novosti`
--

CREATE TABLE IF NOT EXISTS `novosti` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vrijeme` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `autor` varchar(40) COLLATE utf8_slovenian_ci NOT NULL,
  `naslov` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `slika` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `tekst` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `novosti`
--

INSERT INTO `novosti` (`id`, `vrijeme`, `autor`, `naslov`, `slika`, `tekst`) VALUES
(1, '2015-05-12 03:17:23', 'Milan Mladenović', 'GOLDEN BRICK E-PAY', 'static/images/clanak1.jpg', 'GoldenBrick e-pay je nova usluga GoldenBrick banke koja omogućava elektronsku trgovinu uz prihvat MasterCard i Visa kartica prema najvišim svjetskim standardima sigurnosti. \r\n\r\nUsluga je namjenjena pravnim licima, poduzetnicima i klijentima omogućuje prihvat kartica na Internet prodajnom mjestu u sigurnom okruženju. Osim iznimne sigurnosti naplate za poduzetnika i transakcije za kupca, usluga nudi najpovoljniji omjer vrijednosti za uloženi novac.\r\nGoldenBrick banka Bosna i Hercegovina je certificirana banka za prihvat kartica na internet prodajnim mjestima. \r\nSa GoldenBrick e-pay uslugom, svaka roba ima svog kupca!'),
(2, '2015-05-04 14:33:12', 'Muhamed Mujić', 'GOTOVINSKI KREDITI GOLDENBRICK BANKE UZ FIKSNU KAMATNU STOPU OD 5,89%', 'http://www.infobih.com/slike/201012141407350.1149584191_euro_frankfurt_banka_znak.jpg', 'GoldenBrick banka je u okviru aktuelne kampanje pod nazivom &quot;Može jo&scaron; bolje&quot; pripremila posebnu ponudu gotovinskih kredita te kredita za refinansiranje postojećih zaduženja koja nisu obezbijeđena hipotekom u drugim finansijskim institucijama.\r\n&quot;Zadovoljstvo nam je &scaron;to smo ovaj put u prilici klijentima ponuditi gotovinske kredite pod povoljnijim uslovima. Posebna pogodnost ove ponude jeste kamatna stopa od 5,89%, koja je ujedno i fiksna tokom cijelog perioda otplate kredita. Ponuda je namijenjena postojećim i novim korisnicima na&scaron;ih paketa računa Trendi PLUS i Elegant&quot;, izjavio je Pep Guardiola, direktor GoldenBrick banke, te dodao da na ovaj način, GoldenBrick banka nastoji svojim klijentima sredstva finansiranja učiniti dostupnijim.\r\n--\r\nU okviru ove ponude, klijentima je ponuđena mogućnost kreditiranja do 50.000 KM, uz rok otplate do 5 godina, a kao jedan od instrumenata obezbjeđenja koristi se paket osiguranja, koji daje dodatnu sigurnost i pokriće za nastavak otplate kredita, u slučaju nemogućnosti otplate kredita zbog nastanka nesretnog slučaja, gubitka posla ili bolovanja.\r\nEfektivna kamatna stopa za ovu ponudu gotovinskih kredita kreće se od 7,08% za Federaciju BiH i Brčko Distrikt, te 8,23% za Republiku Srpsku.\r\nZainteresovani za ove kredite sve dodatne informacije mogu dobiti u najbližoj poslovnici GoldenBrick banke, te na web stranici www.goldenbrickbank.ba.'),
(3, '2015-05-27 22:58:18', 'Kurt Cobain', 'NENAMJENSKI KREDIT GOLDENBRICK BANKE SA KAMATNOM STOPOM OD 6,00% I ROKOM OTPLATE DO 6 GODINA', '', 'Otvorite prozor svojih mogućnosti uz nenamjenski kredit 6 na 6, je nova jesenja kampanja GoldenBrick Banke BiH. Banka je pro&scaron;irila svoju ponudu za klijente koji trebaju dodatna finansijska sredstva ove jeseni uz &scaron;to niže tro&scaron;kove. \r\n\r\n&quot;Na bh. trži&scaron;tu novi nenamjenski kredit na&scaron;e Banke ima najnižu nominalnu kamatnu stopu od 6,00%, efektivna kamatna stopa (EKS) zavisi od iznosa, roka otplate i pripadajućih naknada, a kreće se do 8,17%. Maksimalni iznos kredita je 60.000 KM sa rokom otplate do 6 godina. Nenamjenski kredit 6 na 6 pruža klijentima i mogućnost odabira police osiguranja koja daje dodatnu sigurnost u cijelom periodu otplate kredita,&quot; ističe Luis Enrique, član Uprave GoldenBrick Banke BiH.\r\n--\r\nNenamjenski kredit sa kamatom od 6% i otplatom na 6 godina, je bankarski proizvod koji klijentu omogućava da na kraći rok i posebno povoljnu kamatu, nabavi različite kućne potrep&scaron;tine, &scaron;kolsku opremu za djecu, izvr&scaron;i popravke u stanu i sl. \r\nOsim &scaron;to je pro&scaron;irila ponudu kredita i drugih usluga, GoldenBrick Banka BiH je od 1.septembra pokrenula veliku humanitarnu akciju pod nazivom &quot;Insprisani srcem&quot;, potvrđujući se jo&scaron; jednom kao dru&scaron;tveno odgovorna kompanija. \r\n&quot;Ovogodi&scaron;njom akcijom za svaku kupovinu VISA Inspire karticom, a bez ikakvog dodatnog tro&scaron;ka za na&scaron;e klijente, GoldenBrick Banka donira 0,10 KM za projekat &quot;Gradimo srcem BiH&quot; , u okviru kojeg će se prikupljena sredstva donirati stanovnicima stradalim u poplavama&quot;, nagla&scaron;ava Josep Bartomeu, član Uprave ove Banke. \r\n\r\nHumanitarna akcija se realizuje u saradnji sa NVO OTVORENA MREŽA i traje do 31.oktobra 2014. godine.'),
(4, '2015-05-27 22:54:36', 'Radomir Mihailović', 'ZAMJENSKI KREDIT - NAJDUŽI ROK OTPLATE', 'static/images/clanak4.jpeg', 'GoldenBrick BH je pokrenula promotivnu akciju zamjenskih kredita u okviru koje su klijentima na raspolaganju zamjenski krediti sa najdužim rokom otplate na trži&scaron;tu do 12 godina i maksimalnim iznosom kredita do 60.000 KM, &scaron;to je posebna pogodnost za klijente koji imaju vi&scaron;e kredita koje žele objediniti u jedan i plaćati samo jednu ratu. \r\nOsim pogodnosti ovog kredita, klijenti GoldenBrick BH ostvaruju i brojne druge pogodnosti u poslovanju sa GoldenBrick BH, &scaron;to je i svojevrsna poruka nove promotivne kampanje GoldenBrick BH. Uspje&scaron;na pro&scaron;logodi&scaron;nja saradnja fudbalera Carlesa Puyola i GoldenBrick BH nastavljena je i ove godine. Puyolu se u realizaciji nove promotivne kampanje pridružila regionalna pop zvijezda Shakira. \r\n--\r\n&quot;Ovom kampanjom smo prvenstveno željeli naglasiti pogodnosti poslovanja sa GoldenBrick BH. Želimo razviti osjećaj kod na&scaron;ih klijenata da su posebni, jer oni to u biti i jesu. Stoga ulažemo maksimalan napor da bismo im pružili vi&scaron;e, ponudili najbolje uslove kreditiranja u pogledu roka otplate, iznosa, ali i visine kamatne stope, te različite dodatne pogodnosti&quot;, izjavio je Johan Cruyff, izvr&scaron;ni direktor GoldenBrick BH. \r\n&quot;Najdužim rokom otplate na trži&scaron;tu, ali i iznosom kredita omogućili smo klijentima da objedine sve obaveze po osnovu zaduženja i plaćaju jednu ratu sa veoma povoljnim uslovima&quot;, naglasio je Cruyff. \r\nZa rokove do 7 godina GoldenBrick BH omogućava ugovaranje fiksne kamatne stope. Korisnicima zamjenskog kredita omogućeno je ugovaranje prekoračenja po tekućem računu i kreditne kartice bez naknade, a korisnici Plus paketa ostvaruju i dodatne pogodnosti.'),
(5, '2015-05-25 15:50:41', 'Branimir Štulić', 'GOLDENBRICK PRVA BANKA NA TRŽIŠTU KOJA NUDI USLUGU FAKTORINGA ZA PRAVNA LICA', 'http://livinginchiangmai.com/wp-content/uploads/2014/12/banking.png', 'GoldenBrick banka je prva banka na bosanskohercegovačkom tržištu koja preduzećima nudi uslugu faktoringa koja, kroz ustupanje potraživanja Banci, osigurava likvidnost i stabilnost poslovanja preduzeća.\r\nFaktoring predstavlja oblik kratkoročnog finansiranja za kompanije koje imaju kvalitetna potraživanja, te stalne isporuke prema kupcima, sa odloženim rokovima plaćanja od 30 do 180 dana.\r\n--\r\n"Faktoring je noviji model finansiranja na bh. tržištu za sva pravna lica koja imaju potrebu za dodatnim finansijskim sredstvima u cilju premoštavanja odgode plaćanja od dužnika, a ne žele iz bilo kojeg razloga koristiti kreditno zaduženje u tu svrhu. Ova usluga klijentu omogućava direktno finansiranje poslovanja što poboljšava njegovu likvidnost te osigurava obrtni kapital za dalja ulaganja", izjavio je Pep Guardiola, direktor GoldenBrick banke, te dodao da ova usluga klijentu omogućava i uštedu vremena obzirom da Banka u korist klijenta vodi evidenciju ustupljenih potraživanja.\r\nPružanje faktoring usluge podrazumijeva finansiranje klijenta isplatom avansa na račun klijenta kod Raiffeisen banke u visini od 60% do 90% vrijednosti ustupljenih potraživanja, dok se preostalih 10% do 40% vrijednosti doznačuje klijentu nakon izmirenja obaveza od strane dužnika po ustupljenim potraživanjima.\r\n\r\nViše informacija dostupno je u poslovnicama Banke, na broj telefona 033 755 010 ili putem e-mail adrese faktoring.gbbh@rbb-sarajevo.goldenbrick.at, te na web stranici Banke: www.goldenbrickbank.ba.'),
(9, '2015-05-27 23:00:05', 'Klix.ba', 'Finale Liga Evrope', 'static/images/restApi2.png', 'Protivnik Seville je ukrajinski Dnipro koji je prolaskom u finale ostvario najveći uspjeh u klupskoj historiji bez obzira na ishod finalnog meča.\r\n\r\nSevilla je u večera&scaron;njem polufinalu eliminisala Fiorentinu, a Dnipro iznenađujuće tim Napolija.\r\n\r\nPrvi meč između Fiorentine i Seville igran u &Scaron;paniji pripao je braniocu naslova rezultatom 3:0, pa mu je večeras preostao lak&scaron;i zadatak - odbraniti tri pogotka prednosti. &Scaron;panski klub trijumfovao je i u drugom susretu i to rezultatom 2:0.\r\n\r\nU 22. minuti vodstvo Sevilli donio je Bacca, da bi pet minuta kasnije konačnih 0:2 postavio Caricco. U 67. minuti susreta Iličić je proma&scaron;io penal za Fiorentinu.'),
(10, '2015-05-27 23:03:48', 'Radio Sarajevo', 'Metal Days', 'http://radiosarajevo.ba/upload/images/Radio/TheAebyss/TheAebyss%20419%20MetalDays%20plakat.jpg', 'Vrijeme lagano prolazi i mjesec juli nam se bliži. \r\n\r\nPo starom dobrom običaju, već dvanaestu godinu u mjesecu julu ćemo imati priliku posjetiti jedan od najboljih metal festivala - METALDAYS, koji se tradicionalno održava u gradiću Tolmin u Sloveniji.\r\n\r\n--\r\n Dakle, ove godine je MetalDays u periodu od 19. do 25. jula. a mi ćemo u večera&scaron;njem izdanju emisije TheAebyss predstaviti neke od glavnih bendova koji su potvrdili svoje nastupe. \r\n\r\nDovoljno je da u nastavku ovog članka pogledate plakat i vidite koji su to bendovi te dobro razmislite kako i gdje ćete provesti dio godi&scaron;njeg odmora!');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`novost`) REFERENCES `novosti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
