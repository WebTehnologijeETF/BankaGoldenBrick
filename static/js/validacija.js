function validacija(){
    //Provjera da li su obavezna polja popunjena
	var elements = document.getElementsByTagName("input");
	for (var i = 0; i < elements.length; i++){
		if(elements[i].getAttribute("type") != "submit" && elements[i].getAttribute("type") != "range"
		  && elements[i].getAttribute("type") != "button" && elements[i].getAttribute("name")!="search_text"){
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
	var message = document.kontaktForma.poruka;
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
	return validiraj(message, true);
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
		if(el.getAttribute("name")=="ime"){
			//Validacija imena (samo slova)
			var ulaz = document.kontaktForma.ime;
			var reg = /[a-zščćđž]{3,}$/i;
			if(!reg.test(ulaz.value)){
				if(ispis)
					prikaziGresku(ulaz, "Pogrešno ime!");
				return false;
			}
		}
		if(el.getAttribute("name")=="prezime"){
			//Validacija prezimena (samo slova)
			ulaz = document.kontaktForma.prezime;
			reg = /[a-zćčđšž]{3,}$/i;
			if(!reg.test(ulaz.value)){
				if(ispis)
					prikaziGresku(ulaz, "Pogrešno prezime!");
				return false;
			}
		}
		if(el.getAttribute("name")=="adresa"){
			//Validacija adrese (slova i brojevi sa razmacima)
			ulaz = document.kontaktForma.adresa;
			if(ulaz.value != null && ulaz.value != ""){
				reg = /[a-zćčđšž0-9\s,.]{4,}$/i;
				if(!reg.test(ulaz.value)){
					if(ispis)
						prikaziGresku(ulaz, "Pogrešna adresa!");
					return false;
				}
			}
		}
		if(el.getAttribute("name")=="email"){
			//Validacija email-a regexom
			//Najjednostavnije mora imati barem jedan znak prije @
			//najmanje jedan poslije, tačku . i najmanje dva znaka
			//u domeni
			ulaz = document.kontaktForma.email;
			reg = /\S+@\S+\.\S{2,}/;
			if(!reg.test(ulaz.value)){
				if(ispis)
					prikaziGresku(ulaz, "Email je pogrešan!");
				return false;
			}
		}
		if(el.getAttribute("name")=="emailPotvrda"){
			//Validacija potvrdnog email-a regexom
			//Najjednostavnije mora imati barem jedan znak prije @
			//najmanje jedan poslije, tačku . i najmanje dva znaka
			//u domeni
			ulaz = document.kontaktForma.emailPotvrda;
			reg = /\S+@\S+\.\S{2,}/;
			if(!reg.test(ulaz.value)){
				if(ispis)
					prikaziGresku(ulaz, "Email je pogrešan!");
				return false;
			}
			//Cros validacija potvrdnog emaila
			//Poredi se sa prvim emailom
			var potvrda = document.getElementById("emailPotvrdaID");
			var email = document.kontaktForma.email;
			if(potvrda.value != email.value){
				if(ispis)
					prikaziGresku(potvrda, "Email nije isti!");
				return false;
			}
		}
		if(el.getAttribute("name")=="telefon"){
			//Validacija broja telefona pomoću regexa
			//Broj mora biti u standardnom obliku +*** ** ***-*** (*-broj)
			ulaz = document.kontaktForma.telefon;
			reg = /^\+\d{3}\s\d\d\s\d{3}\-\d{3}$/;
			if(!reg.test(ulaz.value)){
				if(ispis)
					prikaziGresku(ulaz, "Pogrešan format!(+??? ?? ???-???)");
				return false;
			}
		}
	}
	//console.log(el);
	el.classList.remove("nevaljao");
	var ime = el.getAttribute("name") + "_error";
	var slika = document.getElementById(ime);
	slika.style.display = "none";
	var temp = el.getAttribute("name") + "_greska";				
	var labela = document.getElementById(temp);
	labela.innerHTML = "";
	return true;
}