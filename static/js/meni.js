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
}

function prikaziPadajuci(obj){
	//console.log(obj.children[1]);
	obj.children[1].style.visibility="visible";
}
function sakrijPadajuci(obj){
	obj.children[1].style.visibility="hidden";
}