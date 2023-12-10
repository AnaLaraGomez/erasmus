
var detalleGlobal;
var convocatoriaGlobal;

var itemsQueSubeAlumno = [];
// Array que contiene los itemId de los ya subidos.
var subidos = [];
var candidato;
var user;
window.addEventListener("load", function() { 
    user = JSON.parse(localStorage.getItem('user'));
    candidato = JSON.parse(localStorage.getItem('candidato'));
    document.getElementById('continuarBtn').style.display = 'none';
    document.getElementById('continuarBtn').addEventListener('click', () => mostrarModalSolicitud());

    if(user == null || user.admin == 1) {
        document.getElementById('solicitarBtn').style.display = 'none';
    }

    document.getElementById('solicitarBtn').addEventListener('click', () => mostrarModalSolicitud());
    document.getElementById('cerrarSolicitudBtn').addEventListener('click', () => cerrarModalSolicitud());
    document.getElementById('guardarSolicitudBtn').addEventListener('click', () => guardarSolicitud());
    document.getElementById('solicitud').addEventListener('submit', (e) => eviarSolicitud(e));
    
    document.getElementById('atras').addEventListener('click', () => atras());
    let convocatoriaId = this.document.location.href.split('id=')[1];
    let convocatorias = JSON.parse(this.localStorage.getItem('convocatorias'));
    let convocatoriaGlobal = convocatorias.find(c => c.id == convocatoriaId);
    if(!convocatoriaGlobal) {
        atras();
    }
    
    let url = `http://localhost/erasmus/servidor/api/apiConvocatoria.php?id=${convocatoriaId}`;
    fetch(url)
    .then((respuesta) =>  respuesta.json())
    .then((detalle) => {
        detalleGlobal = detalle;
        itemsQueSubeAlumno =  detalleGlobal.items.filter(itActual => itActual.subeAlumno == 1);

        mostrarBotonSolicitarOContinuar();

        // Pintar listas provisionales o definitivas
        if(detalle.lista.length == 0) {
            // Si aun no hay listados provisionales o definitivos,
            // no pintamos este nodo
            this.document.getElementById('listadoContenedor').style.display = 'none'
        }
        
        pintarConvocatoria();
    });   

    function atras() {
        document.location = `http://localhost/erasmus/interfaz/convocatorias/tablon.html`
    }

    function mostrarBotonSolicitarOContinuar() {
        let cursosPermitidos = detalleGlobal.destinatarios.map(d => d.codigoGrupo + ' ' + d.destinatarioNombre);
        if(candidato && !cursosPermitidos.includes(candidato.curso)) {
            // Esta convocatoria no esta disponible para este alumno
            document.getElementById('solicitarBtn').style.display = 'none';
            document.getElementById('continuarBtn').style.display = 'none';

        }else if(detalleGlobal.entregados && detalleGlobal.entregados.length >= itemsQueSubeAlumno.length ) {
            // Ya se ha solicitado
            document.getElementById('solicitarBtn').disabled = 'true';
            document.getElementById('solicitarBtn').innerHTML = 'Solicitada';
            document.getElementById('continuarBtn').style.display = 'none';
        } else if(detalleGlobal.entregados 
                && detalleGlobal.entregados.length < itemsQueSubeAlumno.length 
                && detalleGlobal.entregados.length > 0 )  {
            // Se ha comenzado a subir archivos pero no se ha terminado
            document.getElementById('solicitarBtn').style.display = 'none';
            document.getElementById('continuarBtn').style.display = 'inherit';
        } else if(detalleGlobal.entregados == undefined ) {
            document.getElementById('solicitarBtn').style.display = 'none';
        }
    }

    function pintarConvocatoria() {
        let destinatariosEnFrase = detalleGlobal.destinatarios.map(actual => actual.codigoGrupo + ' de ' + actual.destinatarioNombre).join(', ');

        // Titulo
        document.getElementById('codigoProyecto').innerHTML = convocatoriaGlobal.proyectoCodigo;
        document.getElementById('proyectoNombre').innerHTML = convocatoriaGlobal.proyectoNombre;
        document.getElementById('a1.1.tipoDur').innerHTML = convocatoriaGlobal.largaDuracion ? 'larga' : 'corta';
        document.getElementById('a1.2.des').innerHTML = destinatariosEnFrase

        document.getElementById('a2.movilidades').innerHTML = convocatoriaGlobal.movilidades;
        document.getElementById('a2.duracion').innerHTML = convocatoriaGlobal.duracion;

        document.getElementById('a5.inicioPruebas').innerHTML = new Date(convocatoriaGlobal.fechaInicioPruebas).toLocaleString().split(',')[0];
        document.getElementById('a5.finPruebas').innerHTML = new Date(convocatoriaGlobal.fechaFinPruebas).toLocaleString().split(',')[0];

        pintarListado();
    }

    function mostrarModalSolicitud() {
        document.getElementById('modalSolicitud').style.visibility = 'visible';
        //mas cosicas
        pintarDatosCandidato();
        pintarDatosEntregables();
    }

    function cerrarModalSolicitud() {
        document.getElementById('modalSolicitud').style.visibility = 'hidden';
        subidos = [];
    }

    function pintarDatosCandidato(){
        document.getElementById('nombre').value = candidato.nombre;
        document.getElementById('telefono').value = candidato.telefono;
        document.getElementById('apellidos').value = candidato.apellidos;
        document.getElementById('fechaNac').value = candidato.fechaNac;
        document.getElementById('domicilio').value = candidato.domicilio;
        document.getElementById('dni').value = user.dni;
        document.getElementById('correo').value = candidato.correo;
        document.getElementById('curso').value = candidato.curso;
        mostrarCamposTutor();
    }

    function mostrarCamposTutor(){
        let fechaNacimiento = new Date(candidato.fechaNac);
        let fechaActualMenos18Años = new Date();
        fechaActualMenos18Años.setFullYear((new Date()).getFullYear() - 18);
        let elementosTutor = document.getElementById("elementosTutor");

    
        if(fechaActualMenos18Años.getTime() <= fechaNacimiento.getTime()){
            document.getElementById('nombreTutor').value = candidato.tutorNombre;
            document.getElementById('apellidosTutor').value = candidato.tutorApellidos;
            document.getElementById('dniTutor').value = candidato.tutorDni;
            document.getElementById('domicilioTutor').value = candidato.tutorDomicilio;
            document.getElementById('telefonoTutor').value = candidato.tutorTelefono;

            elementosTutor.style.display = "block";
        } else {
            elementosTutor.style.display = "none";
        }
    }

    function pintarDatosEntregables() {
        // Inicializamos el array de 'subidos' transformando 
        // el array de objetos (entregados) en un array de sus ids
        subidos = detalleGlobal.entregados.map(e => e.itemId);
        if(itemsQueSubeAlumno.length == subidos.length ) {
            document.getElementById('guardarSolicitudBtn').style.visibility = 'hidden';
        } else {
            document.getElementById('guardarSolicitudBtn').style.visibility = 'inherit';
        }

        let entregables = document.getElementById('entregables');

        entregables.innerHTML = ''
        let iConvocatoriaIdHidden = document.createElement('input');
        iConvocatoriaIdHidden.type = 'hidden';
        iConvocatoriaIdHidden.name = 'convocatoriaId';
        iConvocatoriaIdHidden.value = convocatoriaGlobal.id;

        entregables.appendChild(iConvocatoriaIdHidden);

        itemsQueSubeAlumno.forEach(item => {
            let itemEntregado = detalleGlobal.entregados.find(entregadoActual => entregadoActual.itemId == item.itemId)
            let iDiv = document.createElement('div');
            iDiv.classList.add('entregable');

            let iNombre = document.createElement('p');
            iNombre.innerHTML = item.itemNombre;
            
            let iContenidoDiv = document.createElement('div');
            iContenidoDiv.classList.add('fila');

            let iMiniatura = document.createElement('div');
            iMiniatura.classList.add('miniatura');
            if(itemEntregado) {
                let iframe = document.createElement("iframe");
                iframe.src = itemEntregado.itemUrl;
                iMiniatura.innerHTML= '';
                iMiniatura.style.display = 'inherit';
                iMiniatura.append(iframe);
            }

            let iSubirBtn = document.createElement('input');
            iSubirBtn.setAttribute('data-valida', 'ficheroSubidoOSeleccionado');
            iSubirBtn.setAttribute('data-subido', itemEntregado ? true : false);
            iSubirBtn.name = item.itemId;
            iSubirBtn.type = 'file';
            iSubirBtn.accept="application/pdf"; // solo te deja elegir PDF
            iSubirBtn.addEventListener('change', (e)=> {
                
                if(e.target.files.length == 1 && e.target.files[0].type=="application/pdf") {
                    if(!subidos.includes(item.itemId)) {
                        subidos.push(item.itemId);
                    }
                    if(itemsQueSubeAlumno.length ==subidos.length ) {
                        document.getElementById('guardarSolicitudBtn').style.visibility = 'hidden';
                    }

                    e.target.classList.remove('error-input');
                    let iframe = document.createElement("iframe");
                    iframe.src = URL.createObjectURL(e.target.files[0]);
                    iMiniatura.innerHTML= '';
                    iMiniatura.style.display = 'inherit';
                    iMiniatura.append(iframe);
                }
                
            })
            iContenidoDiv.appendChild(iMiniatura);
            iContenidoDiv.appendChild(iSubirBtn);
            
            iDiv.appendChild(iNombre);
            iDiv.appendChild(iContenidoDiv);
            entregables.appendChild(iDiv);
       })
    }

    function pintarListado() {
        let fechaListaProvisional  = new Date(convocatoriaGlobal.fechaListaProvisional).getTime();
        let fechaListaDefinitiva  = new Date(convocatoriaGlobal.fechaListaDefinitiva).getTime();
        let fechaActual = (new Date()).getTime();
        if(fechaActual < fechaListaProvisional) {
            return;
        }else if(fechaListaProvisional < fechaActual && fechaActual < fechaListaDefinitiva ) {
            // estamos en lista provisional
            document.getElementById('tipoListado').innerHTML = 'PROVISIONAL';
        } else if (fechaActual >= fechaListaDefinitiva) {
            // estamos en lista definitiva
            document.getElementById('tipoListado').innerHTML = 'DEFINITIVO';
        }

        detalleGlobal.lista.sort(ordenarPorPuntuacion);
        let becados = detalleGlobal.lista.slice(0, convocatoriaGlobal.movilidades);
        let noBecados = detalleGlobal.lista.slice(convocatoriaGlobal.movilidades);

        let listadoBecados = document.getElementById('listadoBecados');
        becados.forEach( candidato => {
            let li = document.createElement('li');
            li.innerHTML= candidato.dni + ' - ' + candidato.puntuacion
            listadoBecados.appendChild(li)
        })

        let listadoNoBecados = document.getElementById('listadoNoBecados');
        noBecados.forEach( candidato => {
            let li = document.createElement('li');
            li.innerHTML = candidato.dni + ' - ' + candidato.puntuacion
            listadoNoBecados.appendChild(li)
        })
    }

    function ordenarPorPuntuacion(a,b) {
        return  b.puntuacion - a.puntuacion // descendente!!

    }
    function guardarSolicitud() {
        enviarFormulario();
    }

    function eviarSolicitud(e) {
        e.preventDefault();
        // Validaciones  en JS
        if(e.target.valido()){
            enviarFormulario();
        }
    }

    function enviarFormulario() {
        let url = `http://localhost/erasmus/servidor/api/apiSolicitudes.php`;
        let formularioAEnviar = document.getElementById("solicitud");
    
        let opciones = {
            method: 'POST',
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
            if(respuestaEnJson.status_code == 201) {
                // refrescamos la pagina
                this.document.location = this.document.location;
            } else {
                console.error(respuestaEnJson.msg)
            }
        });
    }
})