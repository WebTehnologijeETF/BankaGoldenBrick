function validacija(modify){
    //Provjera da li su obavezna polja popunjena
    var elements = document.kontaktForma.getElementsByTagName("input");
    for (var i = 0; i < elements.length; i++){
        if(elements[i].getAttribute("type") != "submit" && elements[i].getAttribute("type") != "range"
            && elements[i].getAttribute("type") != "button"){
            elements[i].style.outline="none";
            elements[i].addEventListener("input", function(){
                if(validiraj(this, false))
                    this.style.boxShadow="0 0 1pt 1pt #69CF8E";
                else{
                    this.style.boxShadow="0 0 1pt 1pt #FF294D";
                    this.classList.add("nevaljao");
                }
            });
            elements[i].addEventListener("focusout", function(){
                if(validiraj(this, false)){
                    this.style.boxShadow="none";
                    this.style.border="1px solid #2d97af";
                }
            });
            elements[i].addEventListener("blur", function(){
                if(validiraj(this, false)){
                    this.style.boxShadow="none";
                    this.style.border="1px solid #2d97af";
                }
            });
            elements[i].addEventListener("focus", function(){
                if(!this.classList.contains("nevaljao")){
                    this.style.boxShadow="0 0 1pt 1pt dodgerblue";
                    this.style.border="none";
                }
            });
            var validno = validiraj(elements[i], true);
            if(!validno)
                return false;
        }
    }
    var message = document.kontaktForma.opis;
    message.style.outline="none";
    message.addEventListener("input", function(){
        if(validiraj(this, false))
            this.style.boxShadow="0 0 1pt 1.5pt #69CF8E";
        else{
            this.style.boxShadow="0 0 1pt 1pt #FF294D";
            this.classList.add("nevaljao");
        }
    });
    message.addEventListener("focusout", function(){
        if(validiraj(this, false)){
            this.style.boxShadow="none";
            this.style.border="1px solid #2d97af";
        }
    });
    message.addEventListener("blur", function(){
        if(validiraj(this, false)){
            this.style.boxShadow="none";
            this.style.border="1px solid #2d97af";
        }
    });
    message.addEventListener("focus", function(){
        if(!this.classList.contains("nevaljao")){
            this.style.boxShadow="0 0 1pt 1pt dodgerblue";
            this.style.border="none";
        }
    });
    if(!validiraj(message, true)) {
        return false;
    }
    if(modify=="not") {

        spremiUslugu("not");
    }
    else
        spremiUslugu(modify);

    return false;
}


function prikaziGresku(element, string){
    element.style.boxShadow="0 0 1pt 1pt #FF294D";
    element.style.border="none";
    element.classList.add("nevaljao");
    element.focus();
    var ime = element.getAttribute("name") + "_error";
    var slika = document.getElementById(ime);
    slika.style.display = "block";
    var temp = element.getAttribute("name") + "_greska";
    var labela = document.getElementById(temp);
    labela.innerHTML = string;
}

function validiraj(el, ispis){
    if(!el.classList.contains("obavezan") && (el.value==null || el.value=="")){
        var ime = el.getAttribute("name") + "_error";
        var slika = document.getElementById(ime);
        slika.style.display = "none";
        var temp = el.getAttribute("name") + "_greska";
        var labela = document.getElementById(temp);
        labela.innerHTML = "";
        return true;
    }
    else if(el.classList.contains("obavezan") && (el.value==null || el.value=="") && ispis){
        prikaziGresku(el, "Polje je obavezno!");
        return false;
    }
    else if(el.classList.contains("obavezan") && (el.value==null || el.value==""))
        return false;
    else{
        if(el.getAttribute("name")=="naziv"){
            var ulaz = document.kontaktForma.naziv;
            var reg = /^[a-zščćđž0-9\s,.-]{3,}$/i;
            if(!reg.test(ulaz.value)){
                if(ispis)
                    prikaziGresku(ulaz, "Pogrešan naziv!");
                return false;
            }
        }
        if(el.getAttribute("name")=="slika"){
            ulaz = document.kontaktForma.slika;
            reg = /^(\.\.\/)?\/?static\/images\/[a-zćčđšž\.\-_0-9]+\.(png|gif|jpg)$/i;
            if(!reg.test(ulaz.value)){
                if(ispis)
                    prikaziGresku(ulaz, "Pogrešna adresa slike!");
                return false;
            }
        }
        if(el.getAttribute("name")=="url"){
            ulaz = document.kontaktForma.url;
            reg = /^(https?:\/\/)?www\.(.+)\.(.+)$/i;
            if(!reg.test(ulaz.value)){
                if(ispis)
                    prikaziGresku(ulaz, "Pogrešan URL!");
                return false;
            }
        }
        if(el.getAttribute("name")=="cijena"){
            ulaz = document.kontaktForma.cijena;
            reg = /^[0-9]+$/i;
            if(!reg.test(ulaz.value)){
                if(ispis)
                    prikaziGresku(ulaz, "Pogrešan URL!");
                return false;
            }
        }
    }
    el.classList.remove("nevaljao");
    var ime = el.getAttribute("name") + "_error";
    var slika = document.getElementById(ime);
    slika.style.display = "none";
    var temp = el.getAttribute("name") + "_greska";
    var labela = document.getElementById(temp);
    labela.innerHTML = "";
    return true;
}

function spremiUslugu(modify){
    var elements = document.kontaktForma.getElementsByTagName("input");
    var temp=null;
    if(modify=="not") {
        modify = {};
        temp = true;
    }
    for(var i=0; i < elements.length; i++){
        if(elements[i].getAttribute("type") == "submit")
            continue;
        modify[elements[i].getAttribute("name")] = elements[i].value;
    }
    modify["opis"] = document.kontaktForma.opis.value;
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == "4" && ajax.status == "200") {
            //div.innerHTML = "Success!";
            var msg = document.getElementById("message");
            if(temp!=null)
                msg.innerHTML="<p>New service added.</p>";
            else
                msg.innerHTML="<p>Service modified.</p>";
            msg.style.backgroundColor = "green";
            msg.style.visibility="visible";
            msg.style.opacity = "1";
            var removeMsg=window.setTimeout(function(){
                window.clearTimeout(removeMsg);
                var msg = document.getElementById("message");
                msg.style.visibility="hidden";
                msg.style.opacity = "0";
                msg.style.transition = "opacity 0.5s linear";
            }, 1500);
            var redirect=window.setTimeout(function(){
                window.clearTimeout(redirect);
                //window.location = window.location.href;

                var a = document.getElementsByClassName("tabAnchor");
                if(temp!=null)
                    loadTab(a[1]);
                else
                    loadTab(a[2]);
            }, 2000);

        }
        if (ajax.readyState == "4" && ajax.status == "400") {
            //div.innerHTML = "Pogrešni podaci!";
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            //div.innerHTML = "Stranica nije pronađena!";
        }
    }
    ajax.open("POST", "http://zamger.etf.unsa.ba/wt/proizvodi.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    if(temp!=null)
        ajax.send("brindexa=16416&akcija=dodavanje&proizvod="+JSON.stringify(modify));
    else {
        modify = JSON.stringify(modify);
        ajax.send("brindexa=16416&akcija=promjena&proizvod="+modify);
    }
}

function deleteService(ev, anchor, proizvod){
    ev.preventDefault();
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == "4" && ajax.status == "200") {
            anchor.parentNode.parentNode.innerHTML="";
            var msg = document.getElementById("message");
            msg.innerHTML="<p>Deleted!</p>";
            msg.style.backgroundColor = "firebrick";
            msg.style.visibility="visible";
            msg.style.opacity = "1";
            var removeMsg=window.setTimeout(function(){
                window.clearTimeout(removeMsg);
                var msg = document.getElementById("message");
                msg.style.visibility="hidden";
                msg.style.opacity = "0";
                msg.style.transition = "opacity 0.5s linear";
            }, 1000);
        }
        if (ajax.readyState == "4" && ajax.status == "400") {
            //div.innerHTML = "Pogrešni podaci!";
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            //div.innerHTML = "Stranica nije pronađena!";
        }
    }
    ajax.open("POST", "http://zamger.etf.unsa.ba/wt/proizvodi.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    proizvod = JSON.stringify(proizvod);
    ajax.send("brindexa=16416&akcija=brisanje&proizvod="+proizvod);
}
function changeService(ev, anchor, product){
    ev.preventDefault();
    console.log(JSON.stringify(product));
    var div = document.getElementById("content");
    var str = '<form name="kontaktForma" onmouseover="validacija('+'"mmm"'+')">';
    div.innerHTML = '<div class="kontakt" style="width: 100%;"><h3>&nbsp;Unos usluge</h3>' +
        '<form name="kontaktForma">' +
        '<label class="ispred">Naziv usluge<span class="obavezno">&#42;</span></label>' +
        '<input type="text" name="naziv" class="obavezan" value="'+product["naziv"]+'">' +
        '<img src="static/images/error.png" alt="greska" class="error_img" id="naziv_error">' +
        '<label class="iza" id="naziv_greska"></label>' +
        '<label class="ispred">Opis Usluge<span class="obavezno">&#42;</span></label>' +
        '<textarea cols="25" rows="5" placeholder=" Unesite opis usluge..." class="obavezan" name="opis"></textarea>' +
        '<img src="static/images/error.png" alt="greska" class="error_img" id="opis_error">' +
        '<label class="iza" id="opis_greska"></label>' +
        '<label class="ispred">Slika</label><input type="text" name="slika" value="'+product["slika"]+'">' +
        '<img src="static/images/error.png" alt="greska" class="error_img" id="slika_error">' +
        '<label class="iza" id="slika_greska"></label><label class="ispred">URL</label>' +
        '<input type="text" name="url" value="'+product["url"]+'">' +
        '<img src="static/images/error.png" alt="greska" class="error_img" id="url_error">' +
        '<label class="iza" id="url_greska"></label><label class="ispred">Cijena</label>' +
        '<input type="number" name="cijena"  value="'+product["cijena"]+'">' +
        '<img src="static/images/error.png" alt="greska" class="error_img" id="cijena_error">' +
        '<label class="iza" id="cijena_greska"></label>' +
        '<label id="upozorenje">&#42;&nbsp;Polja označena zvjezdicom su obavezna.</label>' +
        '<label class="ispred">&nbsp;</label>' +
        '<input type="submit" name="posalji" value="Pošalji"></form></div>';
    var txt = document.kontaktForma.opis;
    txt.value += product["opis"];
    document.kontaktForma.onsubmit = function(){
        return validacija(product);
    }
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}