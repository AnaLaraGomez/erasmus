
var detalleGlobal;
var convocatoriaGlobal;

var itemsQueSubeAlumno = [];
// Array que contiene los itemId de los ya subidos.
var subidos = [];

window.addEventListener("load", function() { 
    document.getElementById('continuarBtn').style.display = 'none';
    document.getElementById('continuarBtn').addEventListener('click', () => mostrarModalSolicitud());
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

        if(itemsQueSubeAlumno.length == detalle.entregados.length ) {
            document.getElementById('solicitarBtn').disabled = 'true';
            document.getElementById('solicitarBtn').innerHTML = 'Solicitada';
            document.getElementById('continuarBtn').style.display = 'none';
        } else if(detalle.entregados.length > 0)  {
            document.getElementById('solicitarBtn').style.display = 'none';
            document.getElementById('continuarBtn').style.display = 'inherit';
        }

        pintarConvocatoria();
    });   

    function atras() {
        document.location = `http://localhost/erasmus/interfaz/convocatorias/tablon.html`
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

    function pintarDatosCandidato() {
     // pendiente de implementar   
    }

    function pintarDatosEntregables() {
        // Inicializamos el array de 'subidos' transformando 
        // el array de objetos (entregados) en un array de sus ids
        subidos = detalleGlobal.entregados.map(e => e.itemId);
        if(itemsQueSubeAlumno.length == subidos.length ) {
            document.getElementById('guardarSolicitudBtn').style.visibility = 'hidden';
        } else {
            document.getElementById('guardarSolicitudBtn').style.visibility = 'visible';
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
            console.log(itemEntregado)

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
                console.log(respuestaEnJson.status_code)
                this.document.location = this.document.location;
            } else {
                console.error(respuestaEnJson.msg)
            }
        });
    }
})