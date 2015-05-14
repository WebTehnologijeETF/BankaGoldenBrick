window.onload = function(){
	var m = document.getElementsByClassName("meni");
	m = m[0].children[0].children;
	for(var i=0; i < m.length; i++){
		if(m[i].classList.contains("padajuci")){
			m[i].addEventListener("mouseover", function(){
				prikaziPadajuci(this);
			});
			m[i].addEventListener("mouseout", function(){
				sakrijPadajuci(this);
			});
		}
	}
    var aovi = document.getElementsByClassName("detaljnije");

    for(var i = 0; i < aovi.length; i++){
        aovi[i].addEventListener("click", function(ev){
            ev.preventDefault();
            ucitaj(this.getAttribute("href"));
        })
    }
	var path = window.location.pathname;
	var page = path.split("/").pop();
	if(page=="contact.html"){
		document.kontaktForma.telefon.setAttribute("placeholder", "+??? ?? ???-???");
	}else if(page == "services.html"){
        var tabs = document.getElementsByClassName("tabAnchor");
        loadTab(tabs[0]);
        for(var i=0; i < tabs.length; i++){
            tabs[i].addEventListener("click", function(evArgs){
                evArgs.preventDefault();
                loadTab(this);
            })
        }
        var anchors = document.getElementsByClassName("deleteAnchor");

        for(var i=0; i < anchors.length; i++){
            anchors[i].addEventListener("click", function(ev){
                ev.preventDefault();
                deleteService(this);
            });
        }
    }
    page = window.location.href.split("?")[1];
    if(page == "saving=1"){
        loadAjax(document.getElementsByClassName("ajaxLoaderLink meniLink")[0]);
    }else if(page == "saving=2"){
        loadAjax(document.getElementsByClassName("ajaxLoaderLink meniLink")[1]);
    }
	var vrh = document.getElementsByClassName("vrh");
	vrh = vrh[0];
	vrh = vrh.childNodes[1];
	vrh.addEventListener("click", function(ev){
		ev.preventDefault();
		idiNaVrh(); 
		return false;
	});
    //Ajax
    var anchors = document.getElementsByClassName("ajaxLoaderLink");
    for(var i=0; i < anchors.length; i++){
        anchors[i].addEventListener("click", function(evArgs){
            evArgs.preventDefault();
            loadAjax(this);
        });
    }
}

function prikaziPadajuci(obj){
	obj.children[1].style.visibility="visible";
}
function sakrijPadajuci(obj){
	obj.children[1].style.visibility="hidden";
}
var timeOut;
function idiNaVrh(){
  	if (document.body.scrollTop!=0 || document.documentElement.scrollTop!=0){
    window.scrollBy(0,-50);
    timeOut=setTimeout('idiNaVrh()',20);
  	}
  	else clearTimeout(timeOut);
}
