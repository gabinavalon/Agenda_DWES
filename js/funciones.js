function anadirNumero() {

    var capapadre = document.getElementById("telefonosformulario");
    var capa = document.getElementById("telefonoformulario");
    var nuevacapa = document.createElement('div');
    nuevacapa.innerHTML = `<label for="Numero">Numero: </label><input id="numeronuevo" type="text" name="numero[]" placeholder="Numero..." value="">
    <label for="tipotelefono">Tipo de telefono</label>
    <select id="tipotelefono" name="tipotelefono[]">
        <option value="Movil">Movil</option>
        <option value="Trabajo">Trabajo</option>
        <option value="Casa">Casa</option>
    </select> <br><br>`;

    //Insertamos ene la capa
    capapadre.insertBefore(nuevacapa, capa);

}

function quitarNumero() {
    var capapadre = document.getElementById("telefonosformulario");
    if (capapadre.children.length > 1) {
        capapadre.removeChild(capapadre.firstChild);
    }

}

// function desabilitarSubidaFoto() {

//     if (document.getElementById('checkfoto').checked) {
//         document.getElementById('subidafoto').disabled = false;
//     } else {
//         document.getElementById('subidafoto').disabled = true;
//     }


// }