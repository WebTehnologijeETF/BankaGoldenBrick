function loadTab(anchor){
    if(anchor.getAttribute("href") == "viewServices.html") {
        setActive(0);
        loadOffers(anchor, document.getElementById("content"));
    }else if(anchor.getAttribute("href") == "addService.html") {
        setActive(1);
        loadTabContent(anchor, document.getElementById("content"));
    }else {
        //console.log("ušo");
        setActive(2);
        modifyServices(anchor, document.getElementById("content"));
    }
}
function loadTabContent(anchor, div) {
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == "4" && ajax.status == "200") {
            div.innerHTML = ajax.responseText;
        }
        if (ajax.readyState == "4" && ajax.status == "400") {
            div.innerHTML = "Pogrešni podaci!";
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            div.innerHTML = "Stranica nije pronađena!";
        }
    }
    ajax.open("GET", anchor.getAttribute("href"), true);
    ajax.send();
}
function loadOffers(anchor, div){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var arr = JSON.parse(ajax.responseText);
            var i;
            var out = "<table id='stednja' class='servicesTable'>";
            out += "<colgroup>" +
                "<col id='col1'>" +
                "<col id='col2'>" +
                "<col id='col3'>" +
                "<col id='col4'>" +
                "<col id='col5'>" +
                "<col id='col6'>" +
                "</colgroup>" +
                "<tr>" +
                "<th>Naziv</th>" +
                "<th>Opis</th>" +
                "<th>Slika</th>" +
                "<th>URL</th>" +
                "<th>Cijena</th>" +
                "</tr>";

            for(i = 0; i < arr.length; i++) {
                var http = arr[i].url.split(":")[0];
                var full="";
                if(http!="http")
                    full="http://";
                out += "<tr><td>" +
                    arr[i].naziv +
                    "</td><td>" +
                    arr[i].opis +
                    "</td><td class='slikaTd'><img src='" +
                    arr[i].slika + "' alt='Slika Usluge'>"+
                    "</td><td>" + '<a href="'+full+
                    arr[i].url +'" target="_blank">'+arr[i].url+"</a>"+
                    "</td><td>" +
                    arr[i].cijena +
                    "</td><td class='noBorder' id='TD"+arr[i].id+"'>" +
                    "<a href='deleteService.html' class='deleteAnchor' id='A_"+arr[i].id+
                    "' onclick='deleteService(event, this, "+JSON.stringify(arr[i])+");'>" +
                    "<img src='static/images/deleteService.png'></a></td> " +
                    "</td></tr>";
            }
            out += "</table>"
            div.innerHTML = out;
        }
        if (ajax.readyState == "4" && ajax.status == "400") {
            div.innerHTML = "Pogrešni podaci!";
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            div.innerHTML = "Stranica nije pronađena!";
        }
    }
    ajax.open("POST", "http://zamger.etf.unsa.ba/wt/proizvodi.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("brindexa=16416");
}
function modifyServices(anchor, div) {
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var arr = JSON.parse(ajax.responseText);
            var i;
            var out = "<table id='stednja' class='servicesTable'>";
            out += "<colgroup>" +
                "<col id='col1'>" +
                "<col id='col2'>" +
                "<col id='col3'>" +
                "<col id='col4'>" +
                "<col id='col5'>" +
                "<col id='col6'>" +
                "</colgroup>" +
                "<tr>" +
                "<th>Naziv</th>" +
                "<th>Opis</th>" +
                "<th>Slika</th>" +
                "<th>URL</th>" +
                "<th>Cijena</th>" +
                "</tr>";

            for(i = 0; i < arr.length; i++) {
                var http = arr[i].url.split(":")[0];
                var full="";
                if(http!="http")
                    full="http://";
                out += "<tr><td>" +
                    arr[i].naziv +
                    "</td><td>" +
                    arr[i].opis +
                    "</td><td class='slikaTd'><img src='" +
                    arr[i].slika + "' alt='Slika Usluge'>"+
                    "</td><td>" + '<a href="'+full+
                    arr[i].url +'" target="_blank">'+arr[i].url+"</a>"+
                    "</td><td>" +
                    arr[i].cijena +
                    "</td><td class='noBorder' id='TD'"+arr[i].id+">" +
                    "<a href='deleteService.html' onclick='changeService(event, this, "+
                    JSON.stringify(arr[i])+")' id='A_'"+arr[i].id+">" +
                    "<img src='static/images/changeService.png'></a></td> " +
                    "</td></tr>";
            }
            out += "</table>";
            div.innerHTML = out;
        }
        if (ajax.readyState == "4" && ajax.status == "400") {
            div.innerHTML = "Pogrešni podaci!";
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            div.innerHTML = "Stranica nije pronađena!";
        }
    }
    ajax.open("POST", "http://zamger.etf.unsa.ba/wt/proizvodi.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("brindexa=16416");
}

function setActive(number){
    var as = document.getElementsByClassName("tabAnchor");
    for(var i = 0; i < as.length; i++){
        if(as[i].parentNode.classList.contains("active")) {
            as[i].parentNode.classList.remove("active");
            as[i].parentNode.style.backgroundColor="#ffffff";
            break;
        }
    }
    as[number].parentNode.classList.add("active");
    as[number].parentNode.style.backgroundColor="whitesmoke";
}