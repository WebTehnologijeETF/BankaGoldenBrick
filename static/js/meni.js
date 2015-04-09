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
	var path = window.location.pathname;
	var page = path.split("/").pop();
	if(page=="contact.html"){
		document.kontaktForma.telefon.setAttribute("placeholder", "+??? ?? ???-???");
	}
	var vrh = document.getElementsByClassName("vrh");
	vrh = vrh[0];
	vrh = vrh.childNodes[1];
	vrh.addEventListener("click", function(ev){
		ev.preventDefault();
		idiNaVrh(); 
		return false;
	});
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
