function abrirMenu(){
    document.getElementById("menu-Oculto").style.width="400px";
    document.getElementById("principal").style.marginLeft="0px";
}
function fecharMenu(){
    document.getElementById("menu-Oculto").style.width="0vw";
    document.getElementById("principal").style.marginLeft="0vw";
}


var modal = document.getElementById("container-modal");
var btn = document.getElementById("btn-aceitar");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}