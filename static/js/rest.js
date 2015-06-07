function ucitaj(id){
    id = encodeURI(id);
    var div = document.getElementById("clanak"+id);
    div = div.firstChild.nextSibling.nextSibling.nextSibling.lastChild;
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var json = JSON.parse(ajax.responseText);
            var news = json['novost'];
            var more = div.parentNode.nextSibling.firstChild;
            var reg = /--(\r\n|\n\r|\r|\n)/g;
            if(more.parentNode.classList.contains("more-opened")) {
                var txt = news.tekst.split(reg)[0];
                more.innerHTML = "Detaljnije...";
                more.parentNode.classList.remove("more-opened");
            }else{
                var txt = news.tekst.replace(reg, "");
                more.innerHTML = "Sakrij detaljnije";
                more.parentNode.classList.add("more-opened");
            }
            div.innerHTML = nl2br(txt);


        }
        if (ajax.readyState == "4" && ajax.status == "400") {
            div.innerHTML = "Pogrešni podaci!";
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            div.innerHTML = "Stranica nije pronađena!";
        }
    }
    ajax.open("GET", "REST/NovostiREST.php/novosti/"+id, true);
    ajax.send();
}
function prikazKomentara(anchor){
    var novostID = anchor.getAttribute("href");
    if(anchor.classList.contains("zatvoreno")){
        anchor.classList.remove("zatvoreno");
        anchor.classList.add("otvoreno");
        var divCom = document.getElementById("div"+novostID);
        divCom.style.display = "block";
        var brojKomentara = anchor.innerHTML.split(" ")[0];
        if(brojKomentara!="Nema") {
            var ajax = new XMLHttpRequest();
            ajax.onreadystatechange = function () {
                if (ajax.readyState == "4" && ajax.status == "200") {
                    var json = JSON.parse(ajax.responseText);
                    var admin = false;
                    if (typeof json['komentari'] != 'undefined') {
                        var komentari = json['komentari'];
                    } else {
                        var komentari = json['komentariAdmin'];
                        admin = true;
                    }
                    var com = "";
                    var komentar;
                    document.getElementById("h3"+novostID).style.display="block";
                    for (var j = 0; j < komentari.length; j++) {
                        komentar = komentari[j];
                        com += "<div class='div-komentar' id='divKomentar" + komentar.id + "'>";
                        if (komentar['email'] !== "") {
                            com += "<a href='mailto:" + komentar['email'] + "' class='anchor-ime bold'>"
                            + komentar['autor'] + "</a><br>";
                        } else {
                            com += "<label class='anchor-ime bold not-underlined'>" + komentar['autor'] + "</label><br>";
                        }
                        datetime = komentar['vrijeme'].split(" ");
                        date = datetime[0];
                        time = datetime[1];
                        com += "<label class='label-vrijeme'>" + date + " u " + time + "</label>"
                        + "<p class='p-komentar'>" + nl2br(komentar['tekst']) + "</p>";
                        if (admin) {
                            com += "<form action='index.php' method='post'>"
                            + "<input type='hidden' name='komentarID' value='" + komentar['id'] + "'>"
                            + "<input type='hidden' name='novostID' value='" + komentar['novost'] + "'>"
                            + "<input type='submit' name='izbrisiKomentar' value='Izbriši komentar'"
                            + "class='submit-komentar desno-pozicija' style='font-weight:normal; padding:3px 5px;'>"
                            + "</form>";
                        }
                        com += "</div>";

                    }
                    document.getElementById("comment"+novostID).innerHTML=com;
                    if(admin){
                        var dels = document.getElementById("comment"+novostID).getElementsByTagName("div");
                        for(var i= 0; i < dels.length; i++){
                            dels[i].lastChild.izbrisiKomentar.addEventListener("click", function (ev) {
                                ev.preventDefault();
                                deleteComment(this.parentNode.komentarID);
                            });
                        }
                    }
                }
            };
            ajax.open("GET", "REST/KomentariREST.php?vijestID=" + novostID, true);
            ajax.send();
        }

    }else{
        anchor.classList.remove("otvoreno");
        anchor.classList.add("zatvoreno");
        document.getElementById("div"+anchor.getAttribute("href")).style.display = "none";
    }
}
function deleteComment(comm){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var div = document.getElementById("divKomentar"+comm.parentNode.komentarID.value);
            div.parentNode.removeChild(div);

            var l = document.getElementById("clanak"+comm.parentNode.novostID.value);
            l = l.getElementsByClassName("anchor-komentari")[0];
            var br = l.innerHTML.split(" ")[0];
            if(br==1){
                br = "Nema";
                var h3 = document.getElementById("div"+comm.parentNode.novostID.value);
                h3 = h3.getElementsByClassName("border-komentari")[0];
                h3.parentNode.removeChild(h3);
                var commentsDiv = document.getElementById("comment"+comm.parentNode.novostID.value);
                commentsDiv.parentNode.removeChild(commentsDiv);
            }else{
                br = parseInt(br)-1;
            }
            l.innerHTML = br + " komentara";
            prikaziNotifikaciju("Komentar uspješno obrisan.", "#DC143C");
        }
    };
    var commID = comm.parentNode.komentarID.value;
    ajax.open("DELETE", "REST/KomentariREST.php/komentari/"+commID, true);
    ajax.send();
}
function deleteNews(btn){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var div = document.getElementById("clanak"+btn.parentNode.vijestID.value);
            div.parentNode.removeChild(div);
            prikaziNotifikaciju("Novost uspješno obrisana.", "#DC143C");
        }
    };
    var newsID = btn.parentNode.vijestID.value;
    ajax.open("DELETE", "REST/NovostiREST.php/novosti/"+newsID, true);
    ajax.send();
}
function nl2br (str) {
    var breakTag = '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}
function prikaziNotifikaciju(poruka, boja){
    var div = document.createElement('div');
    div.className = "message-box";
    div.innerHTML = poruka;
    div.style.backgroundColor = boja;
    document.body.appendChild(div);
    var ukloni = window.setTimeout(function(){
        var msg = document.getElementsByClassName("message-box")[0];
        msg.parentNode.removeChild(msg);
        window.clearTimeout(ukloni);
    }, 2000);
}
function komentarisi(input){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if(ajax.readyState=="4" && ajax.status=="200"){
            var json = JSON.parse(ajax.responseText);
            var admin = false;
            if(typeof json['komentar'] != 'undefined') {
                var comm = json['komentar'];
            }else{
                var comm = json['komentarAdmin'];
                admin = true;
            }
            var l = document.getElementById("clanak"+comm.novost);
            l = l.getElementsByClassName("anchor-komentari")[0];
            var br = l.innerHTML.split(" ")[0];
            var string = "";
            if(br=="Nema"){
                br = 1;
                var tmp = document.getElementById("div"+comm.novost);
                string += "<h3 class='border-komentari velika-slova'>Komentari</h3>";
                string += "<div class='all-comments' id='comment"+comm.novost+"'>";
            }else{
                br = parseInt(br)+1;
                var tmp = document.getElementById("comment"+comm.novost);
            }
            l.innerHTML = br + " komentara";
            string += "<div class='div-komentar' id='divKomentar"+comm.id+"'>";
            if (comm['email'] !== "") {
                string += "<a href='mailto:" + comm['email'] + "' class='anchor-ime bold'>"
                + comm['autor'] + "</a><br>";
            } else {
                string += "<label class='anchor-ime bold not-underlined'>" + comm['autor'] + "</label><br>";
            }
            datetime = comm['vrijeme'].split(" ");
            date = datetime[0];
            time = datetime[1];
            string += "<label class='label-vrijeme'>" + date + " u " + time + "</label>"
            + "<p class='p-komentar'>" + nl2br(comm['tekst']) + "</p>";
            if(admin) {
                string += "<form action='index.php' method='post'>"
                + "<input type='hidden' name='komentarID' value='"+comm['id']+"'>"
                + "<input type='hidden' name='novostID' value='"+comm['novost']+"'>"
                +  "<input type='submit' name='izbrisiKomentar' value='Izbriši komentar'"
                + "class='submit-komentar desno-pozicija' style='font-weight:normal; padding:3px 5px;'>"
                + "</form>";
            }
            string += "</div>";
            if(br==1) {
                string += "</div>";
            }
            tmp.innerHTML += string;
            if(br==1){
                var submit = tmp.getElementsByClassName("komentarisi")[0];
                submit.addEventListener("click", function(ev){
                    ev.preventDefault();
                    komentarisi(this);
                });
            }
            if(admin){
                var deleteComm = document.getElementById("comment"+comm.novost).getElementsByClassName("submit-komentar");
                for(var i=0; i<deleteComm.length; i++) {
                    deleteComm[i].addEventListener("click", function (ev) {
                        ev.preventDefault();
                        deleteComment(this);
                    });
                }
            }
            prikaziNotifikaciju("Komentar uspješno dodan.", "#20E693");
        }
    }
    var forma = input.parentNode;
    //Validacija
    //var autor = forma.autor.value;
    //var email = forma.email.value;
    var tekst = forma.tekstKomentara.value;
    var error = document.getElementById("labelPogresanUnos"+forma.novostID.value);
    var reg;
    /*if(autor==null || autor=="")
    {
        error.innerHTML = "Polje Ime i prezime ne smije biti prazno";
        return;
    }else{
        reg = /^[a-zA-ZćĆčČđĐšŠžŽ\s]*$/;
        if(!reg.test(autor)){
            error.innerHTML = "Ime i prezime smiju sadržavati samo slova.";
            return;
        }
        if(autor.length < 5){
            error.innerHTML = "Prekratko ime ili prezime.";
            return;
        }
    }
    if(email!=null && email!=""){
        reg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if(!reg.test(email)){
            error.innerHTML = "Email nije validan.";
            return;
        }
    }*/
    if(tekst==null || tekst==""){
        error.innerHTML = "Polje Komentar ne smije biti prazano.";
        return;
    }

    error.innerHTML="";
    var komentar = {};
    komentar['novostID'] = forma.novostID.value;
    //komentar['autor'] = forma.autor.value;
    //komentar['email'] = forma.email.value;
    komentar['tekst'] = forma.tekstKomentara.value;
    //forma.autor.value = "";
    //forma.autor.style.backgroundColor = "white";
    //forma.email.value = "";
    //forma.email.style.backgroundColor = "white";
    forma.tekstKomentara.value = "";
    komentar = JSON.stringify(komentar);
    ajax.open("POST", "REST/KomentariREST.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("komentar="+komentar);
}
function dodajEvente(){
    //Event za link detaljnije
    var aovi = document.getElementsByClassName("detaljnije");
    for(var i = 0; i < aovi.length; i++){
        aovi[i].addEventListener("click", function(ev){
            ev.preventDefault();
            ucitaj(this.getAttribute("href"));
        })
    }
    //Event za link X komentara
    var linkKomentar = document.getElementsByClassName("anchor-komentari");
    for(var i=0; i < linkKomentar.length; i++){
        linkKomentar[i].addEventListener("click", function(ev){
            ev.preventDefault();
            prikazKomentara(this);
        });
    }
    //Event za komentarisanje
    var komentSubmit = document.getElementsByClassName("komentarisi");
    for(var i=0; i < komentSubmit.length; i++){
        komentSubmit[i].addEventListener("click", function(ev){
            ev.preventDefault();
            komentarisi(this);
        });
    }
}
function saveNews(input, dodavanje){
    var forma = input.parentNode;
    var ID = forma.vijestID.value;
    var greska = document.getElementById("lblGreskaModify");

    var autor = forma.autor.value;
    var naslov = forma.naslov.value;
    var slika = forma.slika.value;
    var tekst = forma.tekst.value;

    //Validacija

    if(autor==null || autor == ""){
        greska.innerHTML = "Polje autor ne smije biti prazno.";
        return;
    }else{
        var reg = /^[a-zćčđšž\s]+$/i;
        if(!reg.test(autor)){
            greska.innerHTML = "Polje autor smije sadržavati slova.";
            return;
        }
        if(autor.length<5){
            greska.innerHTML = "Polje autor mora sadržavati najmanje 5 znakova.";
            return;
        }
    }

    if(naslov==null || naslov == ""){
        greska.innerHTML = "Polje autor ne smije biti prazno.";
        return;
    }else{
        if(naslov.length<2){
            greska.innerHTML = "Polje autor mora sadržavati najmanje 2 znakova.";
            return;
        }
    }

    if(slika!=null && slika!=""){
        var reg1 = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        var reg2 = /\.\.\//;
        var reg3 = /static\/images\/.+\.(png|jpg|jpeg|gif)/i;
        if(reg2.test(slika)){
            greska.innerHTML = "Polje slika ne smije sadržavati '../' iz sigurnosnih razloga.";
            return;
        }
        if(slika.length < 8){
            greska.innerHTML = "Prekratak URL slike.";
            return;
        }
        if(!reg1.test(slika) && !reg3.test(slika)){
            greska.innerHTML = "Neispravan URL slike.";
            return;
        }
    }
    if(tekst==null || tekst==""){
        greska.innerHTML = "Polje tekst ne smije biti prazno.";
        return;
    }

    //Izmjena
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            forma.autor.value = "";
            forma.naslov.value = "";
            forma.slika.value = "";
            forma.tekst.value = "";
            prikaziNotifikaciju("Novost uspješno spremljena.", "green");
            var redirect = window.setTimeout(function () {
                window.clearTimeout(redirect);
                window.location = "index.php";
            }, 2000);
        }
    };
    var obj = {};
    obj['autor'] = autor;
    obj['naslov'] = naslov;
    obj['slika'] = slika;
    obj['tekst'] = tekst;

    var jsonObj = JSON.stringify(obj);
    var escapedJSONObj = jsonObj;


    if(dodavanje){
        ajax.open("POST", "REST/NovostiREST.php/novosti", true);
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.send("novost=" + escapedJSONObj);
    }else {
        ajax.open("PUT", "REST/NovostiREST.php/novosti/" + ID, true);
        ajax.setRequestHeader("Content-type", "application/json");
        ajax.send("novost=" + escapedJSONObj);
    }
}
function addNews(){
    var dodavanje = document.getElementsByClassName("dodavanje")[0];
    var temp = "<h2>Dodavanje novosti</h2><br><br>"
    +"<form method='post' action='index.php'>"
    +"<input type='hidden' name='vijestID''>"
    +"<label>Autor</label><br>"
    +"<input type='text' name='autor' class='ulaz'><br>"
    +"<label>Naslov</label><br>"
    +"<input type='text' name='naslov' class='ulaz'><br>"
    +"<label>URL slike</label><br>"
    +"<input type='text' name='slika' class='ulaz'><br>"
    +"<label>Tekst</label><br>"
    +"<textarea rows=25 cols=80 name='tekst' class='ulaz'></textarea><br>"
    +"<input type='submit' name='dodajNovost' value='Spremi novost' class='submit-komentar'>"
    +"<br><br><label style='color:red; font-weight: bold' id='lblGreskaModify'></label>"
    +"</form>";

    dodavanje.innerHTML = temp;

    var input = document.getElementsByClassName("dodavanje")[0];
    input = input.firstChild.nextSibling.nextSibling.nextSibling.dodajNovost;
    input.addEventListener("click", function (ev) {
        ev.preventDefault();
        saveNews(this, true);
    });
}
function changeNews(input){
    var nID = input.parentNode.vijestID.value;
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var json = JSON.parse(ajax.responseText);
            vijest = json['novost'];
            var temp = "";
            temp += "<div class='dodavanje'>"
                +"<h2>Izmjena novosti</h2><br><br>"
                +"<form method='post' action='index.php'>"
                +"<input type='hidden' name='vijestID' value='"+nID+"'>"
                +"<label>Autor</label><br>"
                +"<input type='text' name='autor' class='ulaz' value='"+vijest['autor']+"'><br>"
                +"<label>Naslov</label><br>"
                +"<input type='text' name='naslov' class='ulaz' value='"+vijest['naslov']+"'><br>"
                +"<label>URL slike</label><br>"
                +"<input type='text' name='slika' class='ulaz' value='"+vijest['slika']+"'><br>"
                +"<label>Tekst</label><br>"
                +"<textarea rows=25 cols=80 name='tekst' class='ulaz'>"+vijest['tekst']+"</textarea><br>"
                +"<input type='submit' name='spremiIzmjenuNovosti' value='Spremi izmjenu novosti'"
                +"class='submit-komentar'>"
                +"<br><br><label style='color:red; font-weight: bold' id='lblGreskaModify'></label>"
                +"</form></div>";
            var div = document.getElementsByClassName("sadrzaj")[0];
            var lijevo = div.firstChild.nextSibling.nextSibling.nextSibling.nextSibling;
            var centar = lijevo.nextSibling;
            var desno = lijevo.nextSibling.nextSibling;
            div.removeChild(lijevo);
            div.removeChild(centar);
            div.removeChild(desno);
            div.innerHTML += temp;
            var input = document.getElementsByClassName("dodavanje")[0];
            input = input.firstChild.nextSibling.nextSibling.nextSibling.spremiIzmjenuNovosti;
            input.addEventListener("click", function (ev) {
                ev.preventDefault();
                saveNews(this, false);
            });
            var divAdmin = document.getElementById("adminMeni");
            if(typeof divAdmin != 'undefined'){
                var anchors = divAdmin.getElementsByTagName("a");
                for(var i=0; i < anchors.length; i++){
                    anchors[i].addEventListener("click", function (ev) {
                        ev.preventDefault();
                        administracija(this);
                    });
                }
            }
        }
    };
    ajax.open("GET", "REST/NovostiREST.php/novosti/"+nID, true);
    ajax.send();
}
function ucitajNovosti(){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function(){
        if(ajax.readyState=="4" && ajax.status=="200"){
            var json = JSON.parse(ajax.responseText);
            var admin=false;
            if(typeof json['novostiAdmin'] != 'undefined') {
                var novosti = json['novostiAdmin'];
                admin = true;
            } else
                var novosti = json['novosti'];
            var div = document.getElementsByClassName("centar")[0];
            div.innerHTML = "<div class='uvod_centar'><h3>Novosti</h3></div>";
            var novost = "";
            for(var i=0; i < novosti.length; i++){
                novost = "";
                novost += "<div class='clanak' id='clanak"+novosti[i].id+"'>";
                novost += "<h3>"+novosti[i].naslov+"</h3>";
                novost += "<br>";
                novost += "<div class='clanak_info'>"
                + "<p class='autor'><img src='static/images/author.png' alt='autor' class='author'>"
                + "<small>" + novosti[i].autor + "</small></p>"
                + "<p class='vrijeme'><img src='static/images/date.png' alt='datum' class='datum'>"
                + "<small>" + novosti[i].vrijeme + "</small></p>"
                + "</div>";
                novost += "<div class='tekst-clanka'>\r\n";
                if (novosti[i].slika !== "") {
                    novost += "<img src='" + novosti[i].slika + "' alt='slika clanka'>";
                }
                var reg = /--(\r\n|\n\r|\r|\n)/g;
                var txt = novosti[i].tekst;
                novost += "<p>" + nl2br(txt.split(reg)[0]) + "</p></div>";


                if (reg.test(txt)) {
                    novost += "<a href='" + novosti[i].id + "' class='detaljnije'>"
                    + "<small>Detaljnije...</small></a>";
                }


                var brojKomentara = novosti[i].broj_komentara;
                if(brojKomentara == 0){
                    brojKomentara = "Nema";
                }
                novost += "<a href='" + novosti[i].id + "' class='anchor-komentari lijevo-pozicija zatvoreno'>"
                + brojKomentara + " komentara</a>";
                if(admin){
                    novost += "<form method='post' action='index.php'"
                    +"style='width:83%;text-align:center;'>"
                    +"<input type='hidden' name='vijestID' value='"
                    +novosti[i].id+"'>"
                    +"<input type='submit' name='izmijeniVijest' value='Izmijeni' class='submit-komentar'>"
                    +"<input type='submit' name='obrisiVijest' value='Obriši' class='submit-komentar brisanje-novosti'>"
                    +"</form>";
                }

                novost += "<br><br><div id='div" + novosti[i].id + "'class='div-komentari'>"
                + "<p class='border-siva'></p>"
                + "<form method='POST' action='index.php' autocomplete='off'>"
                + "<input type='hidden' name='novostID' value='" + novosti[i].id + "'>"
                + "<textarea name='tekstKomentara' rows=5 cols=69 class='ulaz' placeholder='Komentar'>"
                + "</textarea>";
                novost += "<label id='labelPogresanUnos"+novosti[i].id+"' style='color: red; font-weight: bold;'></label>";
                novost += "<input type='submit' name='komentarisi' value='Komentariši'"
                +"class='submit-komentar komentarisi desno-pozicija'>"
                + "</form><br><br>";

                novost += "<h3 class='border-komentari velika-slova' id='h3"+novosti[i].id+"'" +
                "style='display:none;'>Komentari</h3>";
                novost += "<div class='all-comments' id='comment" + novosti[i].id + "'></div>";

                novost += "</div>";
                div.innerHTML += novost;

            }
            div.innerHTML += "</div>";
            timestamp = ajax.getResponseHeader('Date');
            if(admin){
                var obrisiVijest = document.getElementsByName("obrisiVijest");
                for(var i=0; i<obrisiVijest.length; i++){
                    obrisiVijest[i].addEventListener("click", function (ev) {
                        ev.preventDefault();
                        deleteNews(this);
                    });
                }
                var izmijeniVijest = document.getElementsByName("izmijeniVijest");
                for(var i=0; i<izmijeniVijest.length; i++){
                    izmijeniVijest[i].addEventListener("click", function (ev) {
                        ev.preventDefault();
                        changeNews(this);
                    });
                }
            }
        }
    };
    ajax.open("GET", "REST/NovostiREST.php/novosti/all", true);
    ajax.send();
}
function osvjeziNovosti(tms){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function(){
        if(ajax.readyState=="4" && ajax.status=="200"){
            var json = JSON.parse(ajax.responseText);
            var admin=false;
            if(typeof json['novostiAdmin'] != 'undefined') {
                var news = json['novostiAdmin'];
                admin = true;
            } else
                var news = json['novosti'];
            var div = document.getElementsByClassName("centar")[0];
            for(var i= 0; i<news.length; i++){
                var container = document.createElement('div');
                container.className = "clanak";
                container.id = "clanak"+news[i].id;

                var novost = "<h3>"+news[i].naslov+"</h3>";
                novost += "<br>";
                novost += "<div class='clanak_info'>"
                + "<p class='autor'><img src='static/images/author.png' alt='autor' class='author'>"
                + "<small>" + news[i].autor + "</small></p>"
                + "<p class='vrijeme'><img src='static/images/date.png' alt='datum' class='datum'>"
                + "<small>" + news[i].vrijeme + "</small></p>"
                + "</div>";
                novost += "<div class='tekst-clanka'>";
                if (news[i].slika !== "") {
                    novost += "<img src='" + news[i].slika + "' alt='slika clanka'>";
                }
                novost += "<p>" + nl2br(news[i].tekst.split("--\r\n")[0]) + "</p></div>";
                if (news[i].tekst.indexOf("--\r\n") != -1) {
                    novost += "<a href='" + news[i].id + "' class='detaljnije'>"
                    + "<small>Detaljnije...</small></a>";
                }
                var brojKomentara = news[i].broj_komentara;
                if(brojKomentara == 0){
                    brojKomentara = "Nema";
                }
                novost += "<a href='" + news[i].id + "' class='anchor-komentari lijevo-pozicija zatvoreno'>"
                + brojKomentara + " komentara</a>";
                if(admin){
                    novost += "<form method='post' action='index.php'"
                    +"style='width:83%;text-align:center;'>"
                    +"<input type='hidden' name='vijestID' value='"
                    +news[i].id+"'>"
                    +"<input type='submit' name='izmijeniVijest' value='Izmijeni' class='submit-komentar'>"
                    +"<input type='submit' name='obrisiVijest' value='Obriši' class='submit-komentar brisanje-novosti'>"
                    +"</form>";
                }

                novost += "<br><br><div id='div" + news[i].id + "'class='div-komentari'>"
                + "<p class='border-siva'></p>"
                + "<form method='POST' action='index.php' autocomplete='off'>"
                + "<input type='hidden' name='novostID' value='" + news[i].id + "'>"
                + "<textarea name='tekstKomentara' rows=5 cols=69 class='ulaz' placeholder='Komentar'>"
                + "</textarea>";
                novost += "<label id='labelPogresanUnos"+news[i].id+"' style='color: red; font-weight: bold;'></label>";
                novost += "<input type='submit' name='komentarisi' value='Komentariši'"
                +"class='submit-komentar komentarisi desno-pozicija'>"
                + "</form><br><br>";

                novost += "<h3 class='border-komentari velika-slova' id='h3"+news[i].id+"'" +
                "style='display:none;'>Komentari</h3>";
                novost += "<div class='all-comments' id='comment" + news[i].id + "'></div>";
                container.innerHTML = novost;
                var detaljnije = container.firstChild.nextSibling.nextSibling.nextSibling.nextSibling;
                var vidiKomentare = container.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling;
                detaljnije.addEventListener("click", function (ev) {
                    ev.preventDefault();
                    ucitaj(this.getAttribute("href"));
                });
                vidiKomentare.addEventListener("click", function (ev) {
                    ev.preventDefault();
                    prikazKomentara(this);
                });
                var comment = container.lastChild.firstChild.nextSibling.komentarisi;
                comment.addEventListener("click", function (ev) {
                    ev.preventDefault();
                    komentarisi(this);
                });
                div.insertBefore(container, div.firstChild.nextSibling);

            }
            timestamp = ajax.getResponseHeader('Date');

        }
    };
    ajax.open("GET", "REST/NovostiREST.php/novosti/new?timestamp="+timestamp, true);
    ajax.send();
}
function saveAdmin(input, dodavanje){
    var forma = input.parentNode;
    var greska = forma.lastChild;
    if(!dodavanje){
        if(forma.admin.selectedIndex==0){
            greska.innerHTML = "Administrator nije izabran.";
            return;
        }
        var oldUsername = forma.admin.options[forma.admin.selectedIndex].value;
    }

    var username = forma.username.value;
    var password = forma.password.value;
    var email = forma.email.value;

    //Validacija

    if(username==null || username == ""){
        greska.innerHTML = "Polje username ne smije biti prazno.";
        return;
    }else{
        var reg = /^[a-zćčđšž0-9]+$/i;
        if(!reg.test(username)){
            greska.innerHTML = "Polje username smije sadržavati samo slova i brojeve.";
            return;
        }
        if(username.length<5){
            greska.innerHTML = "Polje username mora sadržavati najmanje 5 znakova.";
            return;
        }
    }

    if(password==null || password == ""){
        greska.innerHTML = "Polje password ne smije biti prazno.";
        return;
    }else{
        if(password.length<6){
            greska.innerHTML = "Polje password mora sadržavati najmanje 5 znakova.";
            return;
        }
        var reg = /\s/gmi;
        if(reg.test(password)){
            greska.innerHTML = "Polje password ne smije sadržavati razmake.";
            return;
        }
    }

    if(email==null || email==""){
        greska.innerHTML = "Polje email ne smije biti prazno.";
        return;
    } else{
        var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
        if(email.length < 6){
            greska.innerHTML = "Prekratak email.";
            return;
        }
        if(!reg.test(email)){
            greska.innerHTML = "Neispravan email.";
            return;
        }
    }

    //Izmjena
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            if(!dodavanje){
                forma.admin.selectedIndex=0;
            }
            forma.username.value = "";
            forma.password.value = "";
            forma.email.value = "";
            prikaziNotifikaciju("Administrator uspješno spremljen.", "green");
            var redirect = window.setTimeout(function () {
                window.clearTimeout(redirect);
                //window.location = "index.php";
            }, 2000);
        }
    };
    var obj = {};
    obj['username'] = username;
    obj['password'] = password;
    obj['email'] = email;

    var jsonObj = JSON.stringify(obj);
    var escapedJSONObj = jsonObj;


    if(dodavanje){
        ajax.open("POST", "REST/KorisnikREST.php/admini", true);
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.send("admin=" + escapedJSONObj);
    }else {
        ajax.open("PUT", "REST/KorisnikREST.php/admini/" + oldUsername, true);
        ajax.setRequestHeader("Content-type", "application/json");
        ajax.send("admin=" + escapedJSONObj);
    }
}
function addAdmin(){
    var dodavanje = document.getElementsByClassName("dodavanje")[0];
    var temp = "<h2>Dodavanje administratora</h2><br><br>"
        +"<form method='post' action='admin.php?create=admin'>"
        +"<label>Korisničko ime</label><br>"
        +"<input type='text' name='username' class='ulaz'><br>"
        +"<label>Šifra</label><br>"
        +"<input type='text' name='password' class='ulaz' autocomplete='off'><br>"
        +"<label>Email</label><br>"
        +"<input type='text' name='email' class='ulaz'><br>"
        +"<input type='submit' name='dodajAdmina' value='Dodaj administratora' class='submit-komentar'>"
        +"<br><br><label style='color:red; font-weight: bold'></label>"
        +"</form>";

    dodavanje.innerHTML = temp;

    var input = document.getElementsByClassName("dodavanje")[0];
    input = input.firstChild.nextSibling.nextSibling.nextSibling.dodajAdmina;
    input.addEventListener("click", function (ev) {
        ev.preventDefault();
        saveAdmin(this, true);
    });
}
function popuniUsername(){
    var drop = document.izmjenaAdmina.admin;
    var ime = drop.options[drop.selectedIndex].value;
    document.izmjenaAdmina.username.removeAttribute("value");
    document.izmjenaAdmina.username.setAttribute("value", ime);
}
function modifyAdmin(){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var json = JSON.parse(ajax.responseText);
            admini = json['admini'];
            var temp = "<h2>Promjena administratora</h2><br>"
                    +"<form name='izmjenaAdmina' method='post' action='admin.php?update=admin'>"
                    +"<select name='admin' required onchange='popuniUsername();'>"
                    +"<option value='placeholder' disabled selected>Izaberite administratora</option>";
            for(var i=0; i < admini.length; i++){
                    temp += "<option value='"+admini[i].username+"'>"+admini[i].username+"</option>";
                }
            temp += "</select><br><br>";


            temp +="<label>Novo korisničko ime</label><br>"
                +"<input type='text' name='username' class='ulaz'><br>"
                +"<label>Nova šifra</label><br>"
                +"<input type='text' name='password' class='ulaz' autocomplete='off'><br>"
                +"<label>Novi email</label><br>"
                +"<input type='text' name='email' class='ulaz'><br>"
                +"<input type='submit' name='promijeniAdmina' value='Promijeni administratora' class='submit-komentar'>"
                +"<br><br><label style='color:red; font-weight: bold'></label>"
                +"</form>";


            var div = document.getElementsByClassName("dodavanje")[0];
            div.innerHTML += temp;
            var input = document.getElementsByClassName("dodavanje")[0];
            input = input.firstChild.nextSibling.nextSibling.promijeniAdmina;
            input.addEventListener("click", function (ev) {
                ev.preventDefault();
                saveAdmin(this, false);
            });
        }
    };
    ajax.open("GET", "REST/KorisnikREST.php/admin/all", true);
    ajax.send();
}
function izbrisiAdmina(input){
    var select = input.parentNode.admin;
    if(select.selectedIndex==0){
        input.parentNode.lastChild.innerHTML = "Administrator nije izabran.";
        return;
    }
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            select.selectedIndex = 0;
            prikaziNotifikaciju("Administrator uspješno obrisan.", "firebrick");
            var redirect = window.setInterval(function () {
                window.clearInterval(redirect);
                window.location = "index.php";
            },2000);
        }
    };
    var admin = select.options[select.selectedIndex].value;
    ajax.open("DELETE", "REST/KorisnikREST.php/admin/"+admin, true);
    ajax.send();
}
function deleteAdmin(){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var json = JSON.parse(ajax.responseText);
            admini = json['admini'];
            var temp = "<h2>Brisanje administratora</h2><br>"
                +"<form name='izmjenaAdmina' method='post' action='admin.php?update=admin'>"
                +"<select name='admin' required>"
                +"<option value='placeholder' disabled selected>Izaberite administratora</option>";
            for(var i=0; i < admini.length; i++){
                temp += "<option value='"+admini[i].username+"'>"+admini[i].username+"</option>";
            }
            temp += "</select><br><br>";


            temp += "<input type='submit' name='izbrisiAdmina' value='Izbriši administratora' class='submit-komentar'>"
            +"<br><br><label style='color:red; font-weight: bold' id='lblError'></label>"
            +"</form>";


            var div = document.getElementsByClassName("dodavanje")[0];
            div.innerHTML += temp;
            var input = document.getElementsByClassName("dodavanje")[0];
            input = input.firstChild.nextSibling.nextSibling.izbrisiAdmina;
            input.addEventListener("click", function (ev) {
                ev.preventDefault();
                izbrisiAdmina(this);
            });
        }
    };
    ajax.open("GET", "REST/KorisnikREST.php/admin/all", true);
    ajax.send();
}
function administracija(anchor){
    var div = document.getElementsByClassName("sadrzaj")[0];
    var lijevo = document.getElementsByClassName("lijevo")[0];
    var centar = document.getElementsByClassName("centar")[0];
    var desno = document.getElementsByClassName("desno")[0];

    if(lijevo && centar && desno) {
        div.removeChild(lijevo);
        div.removeChild(centar);
        div.removeChild(desno);
    }

    var dodavanje = document.getElementsByClassName("dodavanje")[0];
    if(dodavanje)
        div.removeChild(dodavanje);

    var frame = document.createElement('div');
    frame.className = "dodavanje";
    div.appendChild(frame);

    var tmp = anchor.getAttribute("href").split("?")[1];
    var action = tmp.split("=")[0];
    var klasa = tmp.split("=")[1];

    if(action=="create" && klasa=="news"){
        addNews();
    }else if(action=="create" && klasa=="admin"){
        addAdmin();
    }else if(action=="update" && klasa=="admin"){
        modifyAdmin();
    }else if(action=="delete" && klasa=="admin"){
        deleteAdmin();
    }




}
window.addEventListener("load", function() {
    timestamp = new Date();
    var divAdmin = document.getElementById("adminMeni");
    if(typeof divAdmin != 'undefined' && divAdmin!=null){
        var anchors = divAdmin.getElementsByTagName("a");
        for(var i=0; i < anchors.length; i++){
            anchors[i].addEventListener("click", function (ev) {
                ev.preventDefault();
                administracija(this);
            });
        }
    }
    ucitajNovosti();
    var eventi = window.setTimeout(function(){
        dodajEvente();
        window.clearTimeout(eventi);
    }, 1000);
    refresh = window.setInterval(function(){
        osvjeziNovosti(timestamp);
    },2000);

});