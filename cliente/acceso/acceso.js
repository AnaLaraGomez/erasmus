
window.addEventListener("load", function() {
    let ojo = document.getElementById("ojo");
    if(ojo != null)  {
        ojo.addEventListener('click', () =>{
            let password = document.getElementById("password");
            if (password.type == 'password') {
                password.type = 'text';
                ojo.style.backgroundImage = "url('http://localhost/erasmus/interfaz/images/eye.png')";
            } else {
                password.type = 'password';
                ojo.style.backgroundImage = "url('http://localhost/erasmus/interfaz/images/sin-ver.png')";
            }
        });
    }

    let fechaNac = document.getElementById("fechaNac");
    if(fechaNac != null) {
        fechaNac.addEventListener('change', (event) =>{
            mostrarCamposTutor(event)
        });
    }

});

function login() {
    let url = 'http://localhost/erasmus/servidor/api/apiLogin.php';
    let formularioAEnviar = document.getElementById("login");

    let opciones = {
        method: "POST",
        body: new FormData(formularioAEnviar),
    };

    fetch(url, opciones)
    .then((respuesta) => {
        if(respuesta.redirected) {
            // si la api ha mandado un header Location, nos vamosa esa url
            document.location = respuesta.url;
        }

        return respuesta.json()
    })
    .then((respuestaEnJson) => {
        limpiaErrores();

        // Pintar los errores
        let grupoUsuario = document.getElementById("grupoUsuario");
        let grupoPassword = document.getElementById("grupoPassword");

        crearError(grupoUsuario,respuestaEnJson['usuario'])
        crearError(grupoPassword,respuestaEnJson['password'])
        crearError(grupoUsuario,respuestaEnJson['usuario2'])
        crearError(grupoPassword,respuestaEnJson['password2'])
    })
}

function registrar() {
    let url = 'http://localhost/erasmus/servidor/api/apiRegistro.php';
    let formularioAEnviar = document.getElementById("registro");
    let opciones = {
        method: "POST",
        body: new FormData(formularioAEnviar),
    };

    fetch(url, opciones)
    .then((respuesta) => {
        if(respuesta.redirected) {
            // si la api ha mandado un header Location, nos vamosa esa url
            let mensajeExito = document.getElementById("mensajeExito");
            let parrafoExito = document.createElement("p");
            let texto = document.createTextNode("Se ha realizado el registro con éxito. Se te redireccionará al login en unos segundos");
            parrafoExito.append(texto);
            parrafoExito.classList.add("exito");
            mensajeExito.appendChild(parrafoExito);

            setTimeout(() =>  document.location = respuesta.url, 5000);
            return {};
        }
        return respuesta.json();
    }).then((respuestaEnJson) => {
        limpiaErrores();

        let grupoNombre = document.getElementById("grupoNombre");
        let grupoApellidos = document.getElementById("grupoApellidos");
        let grupoDni = document.getElementById("grupoDni");
        let grupoFechaNac = document.getElementById("grupoFechaNac");
        let grupoDomicilio = document.getElementById("grupoDomicilio");
        let grupoTelefono = document.getElementById("grupoTelefono");
        let grupoCorreo = document.getElementById("grupoCorreo");
        let grupoCurso = document.getElementById("grupoCurso");
        let grupoNombreTutor = document.getElementById("grupoNombreTutor");
        let grupoApellidosTutor = document.getElementById("grupoApellidosTutor");
        let grupoDniTutor = document.getElementById("grupoDniTutor");
        let grupoDomicilioTutor = document.getElementById("grupoDomicilioTutor");
        let grupoTelefonoTutor = document.getElementById("grupoTelefonoTutor");


        let grupoPassword = document.getElementById("grupoPassword");
        let grupoPassword2 = document.getElementById("grupoPassword2");

        crearError(grupoNombre,respuestaEnJson['usuarioExiste']);
        crearError(grupoNombre,respuestaEnJson['nombre']);
        crearError(grupoApellidos,respuestaEnJson['apellidos']);
        crearError(grupoDni,respuestaEnJson['dni']);
        crearError(grupoDni,respuestaEnJson['dniInvalido']);
        crearError(grupoFechaNac,respuestaEnJson['fechaNac']);
        crearError(grupoFechaNac,respuestaEnJson['fechaFutura']);
        crearError(grupoDomicilio,respuestaEnJson['domicilio']);
        crearError(grupoTelefono,respuestaEnJson['telefono']);
        crearError(grupoTelefono,respuestaEnJson['telefonoInvalido']);
        crearError(grupoCorreo,respuestaEnJson['correo']);
        crearError(grupoCorreo,respuestaEnJson['correoInvalido']);
        crearError(grupoCurso,respuestaEnJson['curso']);

        crearError(grupoNombreTutor,respuestaEnJson['nombreTutor']);
        crearError(grupoApellidosTutor,respuestaEnJson['apellidosTutor']);
        crearError(grupoDniTutor,respuestaEnJson['dniTutor']);
        crearError(grupoDomicilioTutor,respuestaEnJson['domicilioTutor']);
        crearError(grupoTelefonoTutor,respuestaEnJson['telefonoTutor']);



        crearError(grupoPassword,respuestaEnJson['password']);
        crearError(grupoPassword2,respuestaEnJson['password2']);
        crearError(grupoPassword2,respuestaEnJson['passwordDiferente']);
        
    })
}

function crearError(grupo, error){
    if(error) {
        let parrafoError = document.createElement("p");
        let texto = document.createTextNode(error);
        parrafoError.append(texto);
        parrafoError.classList.add("error");
        grupo.appendChild(parrafoError);
    }
}

function limpiaErrores() {
    // Por alguna razon, hay que clonar la lista para que no se 
    // lie con los otros errores que se crearan despues.
    let errores = [...document.getElementsByClassName("error")];
    for (const elementoActual of errores) {
        elementoActual.remove(); 
    }
}

function mostrarCamposTutor(event){
    let fecha = event.target.value;
    let elementosTutor = document.getElementById("elementosTutor");

    let fechaNacimiento = new Date(fecha);
    let fechaActualMenos18Años = new Date();
    fechaActualMenos18Años.setFullYear((new Date()).getFullYear() - 18);


    if(fechaActualMenos18Años.getTime() <= fechaNacimiento.getTime()){
        elementosTutor.style.display = "inherit";
    } else {
        elementosTutor.style.display = "none";
    }

}

