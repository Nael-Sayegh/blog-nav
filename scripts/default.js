var rdisps = {};

function date_heure(id)
{
	var date = new Date;
	var annee = date.getFullYear();
	var mois = date.getMonth();
	var str_mois = new Array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	var jour = date.getDate();
	var jourw = date.getDay();
	var str_jour = new Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	var h = date.getHours(); if(h<10) h = "0"+h;
	var m = date.getMinutes(); if(m<10) m = "0"+m;
	var s = date.getSeconds(); if(s<10) s = "0"+s;
	var resultat = "Nous sommes le "+str_jour[jourw]+" "+jour+" "+str_mois[mois]+" "+annee+", il est "+h+":"+m+":"+s;
	document.getElementById(id).innerHTML = resultat;
	setTimeout("date_heure('"+id+"');",1000);
	return true;
}

function rdisp(id,aria_popup_id) {
	var obj = document.getElementById(id);
	if(rdisps[id] == undefined) {
		if(obj.style.display == undefined)
			rdisps[id] = [true, ""];
		else if(obj.style.display == "none")
			rdisps[id] = [false, "block"];
		else
			rdisps[id] = [true, obj.style.display];
	}
	if(rdisps[id][0]){
		obj.style.display = "none";
		if(typeof(aria_popup_id) != undefined)
			document.getElementById(aria_popup_id).setAttribute("aria-expanded", "false");
	}
	else{
		obj.style.display = rdisps[id][1];
		if(typeof(aria_popup_id) != undefined)
			document.getElementById(aria_popup_id).setAttribute("aria-expanded", "true");
	}
	rdisps[id][0] = !rdisps[id][0];
}

function redirect(event,elm){
	if(event.keyCode == 13)
		window.location = elm.value;
}

/*window.onload = function() {
	var objs = document.getElementsByClassName("jsonly");
	for(i=0; i<objs.length; i++) {// >
		objs[i].style.display = "initial";
	}
};*/

function showjs(id) {
	document.getElementById(id).style.display = "initial";
}

var close_confirm = false;
function init_close_confirm() {
	window.onbeforeunload = function(e) {
		if(close_confirm) {
			e = e || window.event;
			if(e) e.returnValue = "Sure?";
			return "Sure?";
		}
	};
}
