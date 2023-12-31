
var detalleGlobal;
var convocatoriaGlobal;

var itemsQueSubeAlumno = [];
// Array que contiene los itemId de los ya subidos.
var subidos = [];
var candidato;
var user;
var camaraEncendida = false;
var streamGlobal;
var chiquichiqui;
window.addEventListener("load", function() {
    chiquichiqui = document.getElementById("chiquichiqui");
    document.getElementById('descargarPdfListadoBtn').style.display = 'none';
    document.getElementById("descargarPdfListadoBtn").addEventListener("click", (ev) => { descargarListadoPdf(ev); });
    document.getElementById("sacarFotoBtn").style.display = 'none'; 
    document.getElementById("sacarFotoBtn").addEventListener("click", (ev) => { sacarFoto(ev)});
    document.getElementById("encenderCamaraBtn").addEventListener("click", (ev) => { encenderCamara(ev); });
    document.getElementById("apagarCamaraBtn").style.display = 'none'; 
    document.getElementById("apagarCamaraBtn").addEventListener("click", (ev) => { guardarFoto(ev); });
    document.getElementById("descargarPdfBtn").addEventListener("click", (ev) => { descargarPdf(ev); });
    
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
        document.getElementById('codigoProyecto').innerHTML = convocatoriaGlobal.nombre;
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
        apagarCamara();
    }

    function pintarDatosCandidato(){
        if(detalleGlobal.foto != null ) {
            document.getElementById('photo').src = detalleGlobal.foto;
        }
        
        document.getElementById('foto').value = detalleGlobal.foto;
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
            document.getElementById('descargarPdfListadoBtn').style.display = 'block';
            document.getElementById('descargarPdfListadoBtn').innerHTML = 'Descargar listado provisional';
        } else if (fechaActual >= fechaListaDefinitiva) {
            // estamos en lista definitiva
            document.getElementById('descargarPdfListadoBtn').style.display = 'block';
            document.getElementById('descargarPdfListadoBtn').innerHTML = 'Descargar listado definitivo';
        }

    }

    function guardarSolicitud() {
        enviarFormulario();
    }

    function eviarSolicitud(e) {
        e.preventDefault();
        if(e.target.valido()){
            enviarFormulario(true);
        }
    }

    function enviarFormulario(esFinal = false) {
        chiquichiqui.style.display = 'block';
        let url = `http://localhost/erasmus/servidor/api/apiSolicitudes.php?enviar=${esFinal}`;
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
            chiquichiqui.style.display = 'none';
        });
    }

    function apagarCamara() {
        if(streamGlobal) {
            streamGlobal.getTracks().forEach(function(track) {
                track.stop();
              });  
        }
        document.getElementById("encenderCamaraBtn").style.display = 'block';
        document.getElementById("sacarFotoBtn").style.display = 'none';
        document.getElementById("apagarCamaraBtn").style.display = 'none'; 

        let video = document.getElementById("video");
        video.setAttribute("width", 0);
        video.setAttribute("height", 0);

    }

    function encenderCamara() {
        document.getElementById("sacarFotoBtn").style.display = 'block';
        document.getElementById("apagarCamaraBtn").style.display = 'block'; 
        document.getElementById("encenderCamaraBtn").style.display = 'none';
        let video = document.getElementById("video");
        
        let width = 200;
        let height = 0;
        let opcionesCamara = {
            video: true,
            audio: false,
        };
      
        navigator.mediaDevices.getUserMedia(opcionesCamara)
        .then(stream => {
            video.srcObject=stream;
            video.play();
            height = video.videoHeight / (video.videoWidth / width);
            video.setAttribute("width", width);
            video.setAttribute("height", height);
            streamGlobal = stream;
        });
        
    }

    function sacarFoto(e) {
        e.preventDefault();
        let video = document.getElementById("video");
        let canvas = document.createElement("canvas");
        let photo = document.getElementById("photo");
        let foto  = document.getElementById("foto");
        let width = 200;
        let height = video.videoHeight / (video.videoWidth / width);
        canvas.setAttribute("width", width);
        canvas.setAttribute("height", height);
        
        canvas.getContext("2d").drawImage(video, 0, 0, width, height);
        let data = canvas.toDataURL("image/png");
        photo.setAttribute("src", data);
        foto.value = data;
    }

    function guardarFoto(e) {
        e.preventDefault();
        apagarCamara(); 
    }

    function descargarPdf() {
        window.open(`http://localhost/erasmus/servidor/api/apiPdf.php?convocatoriaId=${convocatoriaGlobal.id}`, "_blank");
    }

    function descargarListadoPdf() {
        window.open(`http://localhost/erasmus/servidor/api/apiPdf.php?convocatoriaListadoId=${convocatoriaGlobal.id}`, "_blank");

    }

})