// Variable que controla si el usuario ha iniciado ya session para saber si 
// pintamos el boton de login o no en el header.
var estaLogueado = false;

window.addEventListener("load", function() {

    document.getElementById('logout').addEventListener('click', () => logout());
    document.getElementById('login').addEventListener('click', () => this.document.location = 'http://localhost/erasmus/interfaz/acceso/login.html');

    let url = 'http://localhost/erasmus/servidor/api/apiUsuario.php';
    fetch(url)
    .then((respuesta) => {
        if(respuesta.redirected) {
            // El servidor nos manda al login si no estamos logueados.
            // Sin embargo, en cliente queremos gestionarlo de manera diferente
            estaLogueado = false;
            return null;
        }
        estaLogueado = true;
        return respuesta.json()
    }).then((user) => {
        pintarDatosUsuario(user);

        if(!user.admin) {
            let url = 'http://localhost/erasmus/servidor/api/apiUsuario.php?candidato';
            fetch(url)
            .then((respuesta) => respuesta.json())
            .then(candidatoJson => {
                localStorage.setItem('candidato', JSON.stringify(candidatoJson))
            })

        }
    });
    
    function pintarDatosUsuario(user) {  
        let nombre = document.getElementById('nombre');
        let foto = document.getElementById('foto');
        let logout = document.getElementById('logout');
        let login = document.getElementById('login');

        nombre.style.display = 'inherit';
        foto.style.display = 'inherit';
        logout.style.display = 'inherit';
        login.style.display = 'none';

        if(estaLogueado) {
            pintarBotonesEnHeader(user);
            pintarPerfilUsuario(user);
        } else {
            // pintar boton de login en header para redirigir al usuario al Login
            pintarHeaderDeUsuarioAnonimo();
        }
        // Página por defecto
        redireccionar('interfaz/convocatorias/tablon.html');
    }
    
    function pintarBotonesEnHeader(user) {
        // Añadir los botones comunes
        pintarBoton('Tablón', 'interfaz/convocatorias/tablon.html',"pregunta")

        if(user.admin) {
            // Añadir los botones de admin
            pintarBoton('Proyectos', 'interfaz/gestion/proyectos.html',"gestion")
            pintarBoton('Convocatorias', 'servidor/forms/CrearConvocatoria.php',"asda")
            pintarBoton('Modificar Alumno', 'interfaz/gestion/alumnos.html',"generarExamen")
            pintarBoton('Puntuar', 'interfaz/gestion/puntuar.html',"generarExamen")
        }else {   
            // Añadir los botones de alumno   
        }
    }

    function pintarHeaderDeUsuarioAnonimo() {
        let nombre = document.getElementById('nombre');
        let foto = document.getElementById('foto');
        let logout = document.getElementById('logout');
        let login = document.getElementById('login');

        nombre.style.display = 'none';
        foto.style.display = 'none';
        logout.style.display = 'none';
        login.style.display = 'inherit';
    }

    function pintarBoton(nombre, url,claseIcono) {
        let botones = document.getElementById('botones');
        let boton = document.createElement('li');
        boton.classList.add(claseIcono);
        boton.onclick = () => redireccionar(url);
        let texto = document.createTextNode(nombre);
        boton.append(texto);
        botones.appendChild(boton);
    }

    function redireccionar(url) {
        let iframe = document.getElementById('iframe');
        iframe.src = 'http://localhost/erasmus/' + url;
    }

    function pintarPerfilUsuario(user) {
        let nombre = document.getElementById("nombre");
        nombre.innerHTML = user.dni;
        let foto = document.getElementById("foto");
        //foto.src = user.foto;
    }

    function logout() {
        localStorage.clear();
        let url = 'http://localhost/erasmus/servidor/api/apiLogin.php';
        let opciones = {
            method: "DELETE",
        };

        fetch(url, opciones)
        .then((respuesta) => {
            if(respuesta.redirected) {
                document.location = respuesta.url;
            }
        })
    }
})




