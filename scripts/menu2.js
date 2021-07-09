var menu_disp = true;
function disp_menu() {
	if(menu_disp) document.getElementById("ulli_menu").style = "display: none;";
	else document.getElementById("ulli_menu").style = "display: block;";
	menu_disp = !menu_disp;
}
window.onload = disp_menu;