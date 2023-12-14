
// Después de comenzar este script me comentaron que no era necesaria en el proyecto, por lo que todo lo que aparece 
// no está implementando y se debe ignorar

var proyectos = [];

window.addEventListener("load", function() {
    cargarProyectos();

});


function cargarProyectos() {
    let url = 'http://localhost/erasmus/servidor/api/apiProyecto.php';
    fetch(url)
    .then((respuesta) => {
        if(respuesta.redirected) {
            document.location = respuesta.url;
        }
        return respuesta.json()
    }).then((proyectosJson) => {
        proyectos = proyectosJson;
        pintarProyectos();
    });
}


function pintarProyectos() {
    // Usamos la variable global para pintar los proyectos
    // Recibidos por el servidor
    limpiarTablas();
    let tablaProyectos = document.getElementById('tablaProyectos');

    proyectos.forEach(proyectoActual => {
        let fila = document.createElement('tr');
        fila.setAttribute('data-tipo-fila', proyectoActual.id);
        
        let colId = document.createElement('td');
        colId.classList.add('descripcion');
        colId.append(document.createTextNode(proyectoActual.id));

        let colCodigo = document.createElement('td');
        colCodigo.classList.add('descripcion');
        colCodigo.append(document.createTextNode(proyectoActual.codigo));

        let colNombre = document.createElement('td');
        colNombre.classList.add('descripcion');
        let nombreInput = document.createElement('textarea');
        nombreInput.value=proyectoActual.nombre;
        nombreInput.setAttribute('readonly', '')
        
        colNombre.append(nombreInput);


        let colInicio = document.createElement('td');
        colInicio.classList.add('descripcion');
        let inicioInput = document.createElement('input');
        inicioInput.value=proyectoActual.fechaInicio;
        inicioInput.setAttribute('readonly', '')
        inicioInput.type='date-time';
        colInicio.append(inicioInput);

        let colFin = document.createElement('td');
        colFin.classList.add('descripcion');
        let finInput = document.createElement('input');
        finInput.value=proyectoActual.fechaFin;
        finInput.setAttribute('readonly', '')
        finInput.type='date-time';
        colFin.append(finInput);
        
        let colAcciones = document.createElement('td');
        let modificarBtn = document.createElement('button');
        modificarBtn.appendChild(document.createTextNode('Modificar'));
        modificarBtn.classList.add('edit-btn');
//        modificarBtn.addEventListener('click', () => denegarUsuarioPendiente(pendienteActual));
        let eliminarBtn = document.createElement('button');
        eliminarBtn.appendChild(document.createTextNode('Eliminar'));
//        eliminarBtn.addEventListener('click', () => aceptarUsuario(pendienteActual));
        eliminarBtn.classList.add('delete-btn');
        colAcciones.appendChild(modificarBtn);
        colAcciones.appendChild(eliminarBtn);

        fila.appendChild(colId);
        fila.appendChild(colCodigo);
        fila.appendChild(colNombre);
        fila.appendChild(colInicio);
        fila.appendChild(colFin);
        fila.appendChild(colAcciones);
        tablaProyectos.appendChild(fila);
    });
}

function limpiarTablas() {
    let filasTotales = [...document.getElementsByTagName('tr')];
    for (let i=0; i < filasTotales.length; i ++) {
        if(filasTotales[i].getAttribute('data-tipo-fila')) {
            filasTotales[i].remove();
        }
    }
}

function abrirModalCrearProyecto() {
    document.getElementById('crearProyectoModal').style.visibility = 'visible';
}

function cerrarModalCrearProyecto() {
    document.getElementById('crearProyectoModal').style.visibility = 'hidden';
}

function crearProyecto() {
    let url = 'http://localhost/erasmus/servidor/api/apiProyecto.php';
    let formularioAEnviar = document.getElementById("crearProyecto");

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
        if(respuestaEnJson.succeed) {
            cargarProyectos();
            cerrarModalCrearProyecto();
        } else {
            
        }
    })
}