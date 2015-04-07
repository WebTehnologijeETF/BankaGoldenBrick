function validacija(){
    //Provjera da li su obavezna polja popunjena
	var elements = document.getElementsByTagName("input");
	for (var i = 0; i < elements.length; i++){
        if(elements[i].classList.contains("obavezan")){
            if(elements[i].value == null || elements[i].value == ""){
                prikaziGresku(elements[i], "Polje je obavezno!");
                return false;
            }
        }
    }
	//Validacija imena (samo slova)
	var ulaz = document.kontaktForma.ime;
	var reg = /[a-zščćđž]{3,}$/i;
	if(!reg.test(ulaz.value)){
		prikaziGresku(ulaz, "Pogrešno ime!");
		return false;
	}
	//Validacija prezimena (samo slova)
	ulaz = document.kontaktForma.prezime;
	reg = /[a-zćčđšž]{3,}$/i;
	if(!reg.test(ulaz.value)){
		prikaziGresku(ulaz, "Pogrešno prezime!");
		return false;
	}
	//Validacija adrese (slova i brojevi sa razmacima)
	ulaz = document.kontaktForma.adresa;
	if(ulaz.value != null && ulaz.value != ""){
		reg = /[a-zćčđšž0-9\s,.]{4,}$/i;
		if(!reg.test(ulaz.value)){
			prikaziGresku(ulaz, "Pogrešna adresa!");
			return false;
		}
	}
	//Validacija email-a regexom
	//Najjednostavnije mora imati barem jedan znak prije @
	//najmanje jedan poslije, tačku . i najmanje dva znaka
	//u domeni
	ulaz = document.kontaktForma.email;
	reg = /\S+@\S+\.\S{2,}/;
	if(!reg.test(ulaz.value)){
		prikaziGresku(ulaz, "Email je pogrešan!");
		return false;
	}
	//Validacija potvrdnog email-a regexom
	//Najjednostavnije mora imati barem jedan znak prije @
	//najmanje jedan poslije, tačku . i najmanje dva znaka
	//u domeni
	ulaz = document.kontaktForma.emailPotvrda;
	reg = /\S+@\S+\.\S{2,}/;
	if(!reg.test(ulaz.value)){
		prikaziGresku(ulaz, "Email je pogrešan!");
		return false;
	}
	//Cros validacija potvrdnog emaila
	//Poredi se sa prvim emailom
	var potvrda = document.getElementById("emailPotvrdaID");
	var email = document.kontaktForma.email;
	if(potvrda.value != email.value){
		prikaziGresku(potvrda, "Email nije isti!");
		return false;
	}
	//Validacija broja telefona pomoću regexa
	//Broj mora biti u standardnom obliku +*** ** ***-*** (*-broj)
	ulaz = document.kontaktForma.telefon;
	if(ulaz.value != null && ulaz.value != ""){
		reg = /\+\d{3}\s\d\d\s\d{3}\-\d{3}$/;
		if(!reg.test(ulaz.value)){
			prikaziGresku(ulaz, "Pogrešan format!(+??? ?? ???-???)");
			return false;
		}
	}
	//Provjera da li je polje poruka obavezno
	//i u slučaju da jeste provjera da li je prazno
	ulaz = document.kontaktForma.poruka;
    if(ulaz.classList.contains("obavezan")){
        if(ulaz.value == null || ulaz.value == ""){
            prikaziGresku(ulaz, "Polje je obavezno!");
            return false;
        }
    }
    
    return true;
}


function prikaziGresku(element, string){
	element.focus();
	var ime = element.getAttribute("name") + "_error";
	var slika = document.getElementById(ime);
	slika.style.display = "block";
	var temp = element.getAttribute("name") + "_greska";				
	var labela = document.getElementById(temp);
	labela.innerHTML = string;
	element.addEventListener("input", function(){
		slika.style.display = "none";
		labela.innerHTML = "";
	});
}