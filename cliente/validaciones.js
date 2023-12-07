HTMLInputElement.prototype.relleno = function () {
    var respuesta = false;
    if (this.value != "") {
        respuesta = true;
    }
    return respuesta;
}

HTMLInputElement.prototype.ficheroSubidoOSeleccionado = function () {
    var respuestaSeleccionado = false;
    // esta Seleccionado ?
    if (this.value != "") {
        respuestaSeleccionado = true;
    }
    // esta subido?
    var respuestaSubido = false;
    if (this.getAttribute('data-subido') == 'true') {
        respuestaSubido = true;
    }
    return respuestaSeleccionado || respuestaSubido;
}

HTMLInputElement.prototype.dni = function () {
    var respuesta = false;
    if (this.value != "") {
        const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

        var partes = (/^(\d{8})([TRWAGMYFPDXBNJZSQVHLCKE])$/i).exec(this.value);
        if (partes) {
            respuesta = (letras[partes[1] % 23] === partes[2].toUpperCase());
        }
    }
    return respuesta;
}

HTMLInputElement.prototype.edad = function () {
    var respuesta = false;
    if (this.value == parseInt(this.value) && this.value >= 0 && this.value < 150) {
        respuesta = true;
    }
    return respuesta;
}
HTMLInputElement.prototype.seleccionado = function () {
    var respuesta = false;
    var name=this.name;
    if(this.form[name].value!=""){
        respuesta = true;
    }
    return respuesta;
}

HTMLFormElement.prototype.valido = function () {
    var elementos = this.querySelectorAll("input[data-valida]");
    var respuesta = true;
    let n = elementos.length;
    for (let i = 0; i < n; i++) {
        let funcionValidacion = elementos[i].getAttribute("data-valida");
        var resultadoValidacion = elementos[i][funcionValidacion]();
        if(resultadoValidacion){
            elementos[i].classList.add("valido");
            elementos[i].classList.remove("error-input");
        }else{
            elementos[i].classList.remove("valido");
            elementos[i].classList.add("error-input");
        }
        respuesta = respuesta && resultadoValidacion;
    }
    return respuesta;
}