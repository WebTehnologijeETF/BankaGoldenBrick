function loadAjax(link) {
    var location = window.location.href.split("/");
    var file = location.pop();
    file = file.split("?")[0];
    if (file != "savings.html") {
        var number = link.getAttribute("href").split(".")[0];
        number = number.split('').pop();
        window.location = location.join("/") + "/savings.html?saving="+number;
    }else{
        loadPage(link);
    }
}
function loadPage(link){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (ajax.readyState == "4" && ajax.status == "200") {
            var container = document.getElementById(link.getAttribute("href").split(".")[0]);
            if(container.classList.contains("closed")) {
                var temp = link.getAttribute("href").split(".")[0].split("");
                temp = parseInt(temp.pop());
                var li = document.getElementsByClassName("ajaxLoaderLink")[1+temp];
                li.parentNode.style.listStyleImage = 'url("static/images/minus2.png")';
                container.style.display = "block";
                container.classList.remove("closed");
                container.innerHTML = ajax.responseText;
                if (link.classList.contains("meniLink")) {
                    container.scrollIntoView();
                }
            }
            else{
                link.parentNode.style.listStyleImage = 'url("static/images/plus2.png")';
                container.style.display = "none";
                container.classList.add("closed");
            }
        }
        if (ajax.readyState == "4" && ajax.status == "404") {
            var container = document.getElementById(link.getAttribute("href").split(".")[0]);
            container.style.display = "block";
            container.innerHTML = "Tražena stranica nije pronađena.";
        }
    }
    ajax.open("GET", link.getAttribute("href").split(".")[0]+"Ajax.html", true);
    ajax.send();
}