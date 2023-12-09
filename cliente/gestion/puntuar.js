window.addEventListener("load", function() {

    cargarConvocatoriasPuntuables();

    function cargarConvocatoriasPuntuables() {
        let url = 'http://localhost/erasmus/servidor/api/apiEvaluacion.php';
        fetch(url)
        .then((respuesta) => respuesta.json())
        .then((respuestaEnJson) => {
            let convocatorias = respuestaEnJson.convocatorias;
            let convocatoriasContenedor = document.getElementById('convocatorias');
            convocatorias.forEach(convocatoria => {
                convocatoriasContenedor.appendChild(pintarConvocatoria(convocatoria));
            });
        });
    }

    function pintarConvocatoria(convocatoria) {
        let cDiv = document.createElement('div');
        cDiv.classList.add('convocatoria');

        let cHeaderDiv = document.createElement('div');
        cHeaderDiv.classList.add('fila');

        let cEstado = document.createElement('div');
        cEstado.classList.add('estado');
        cEstado.classList.add(calcularClaseEstado(convocatoria));
        cHeaderDiv.appendChild(cEstado);

        let cTitulo = document.createElement('p');
        cTitulo.innerHTML = convocatoria.nombre + ' - ' + convocatoria.proyectoNombre
        cTitulo.classList.add('titulo');
        cHeaderDiv.appendChild(cTitulo);

        let cFechas = document.createElement('p');
        cFechas.innerHTML = 
            (new Date(convocatoria.fechaFinPruebas)).toLocaleDateString()  
            + ' - ' 
            + (new Date(convocatoria.fechaListaProvisional)).toLocaleDateString();;
        cFechas.classList.add('descripcion');
        cDiv.appendChild(cHeaderDiv);
        cDiv.appendChild(cFechas);
        cDiv.appendChild(pintarTablaCandidatos(convocatoria.id, convocatoria.candidatos));
        return cDiv;
    }

    function calcularClaseEstado(convocatoria) {
        let numeroCandidatos = convocatoria.candidatos.length ;
        let numeroCandidatosEvaluados = convocatoria.candidatos.filter(c => c.evaluado == 1);
        if(numeroCandidatos == numeroCandidatosEvaluados.length) {
            return 'estado-evaluado';
        } else {
            return 'estado-pendiente';
        }

    }

    function pintarTablaCandidatos(convocatoriaId, candidatos) {
        let tabla = document.createElement('table');
        tabla.appendChild(pintarHeaderDeTabla());
        let tbody = document.createElement('tbody');
        tabla.appendChild(tbody);
        candidatos.forEach(c => {
            tbody.appendChild(pintarFilaCandidato(convocatoriaId, c));

        });
        return tabla;
    }

    function pintarHeaderDeTabla() {
        let thead = document.createElement('thead');

        let filaHeaders = document.createElement('tr');

        // Header para pintar el estado
        filaHeaders.appendChild(document.createElement('th'));

        let dniHeader = document.createElement('th');
        dniHeader.innerHTML= 'DNI';
        filaHeaders.appendChild(dniHeader);

        let nombreHeader = document.createElement('th');
        nombreHeader.innerHTML= 'Nombre';
        filaHeaders.appendChild(nombreHeader);

        let apellidosHeader = document.createElement('th');
        apellidosHeader.innerHTML= 'Apellidos';
        filaHeaders.appendChild(apellidosHeader);

        let pendientesDeEvaluar = document.createElement('th');
        pendientesDeEvaluar.innerHTML= 'Notas Pendientes';
        filaHeaders.appendChild(pendientesDeEvaluar);

        // Header para pintar el boton de acceso
        filaHeaders.appendChild(document.createElement('th'));
        thead.appendChild(filaHeaders);
        return thead;
    }

    function pintarFilaCandidato(convocatoriaId, candidato) {
        let fila = document.createElement('tr');

        let estado = document.createElement('td');
        estado.classList.add('estado');
        estado.classList.add(calcularClaseEnBaseAEvaluado(candidato));
        fila.appendChild(estado);

        let dniHeader = document.createElement('td');
        dniHeader.innerHTML= candidato.dni;
        fila.appendChild(dniHeader);

        let nombreHeader = document.createElement('td');
        nombreHeader.innerHTML= candidato.nombre;
        fila.appendChild(nombreHeader);

        let apellidosHeader = document.createElement('td');
        apellidosHeader.innerHTML = candidato.apellidos;
        fila.appendChild(apellidosHeader);

        let pendientesDeEvaluar = document.createElement('td');
        pendientesDeEvaluar.innerHTML= candidato.evaluadas + '/' + candidato.evaluables;
        fila.appendChild(pendientesDeEvaluar);

        let acciones = document.createElement('td');
        let editarBtn = document.createElement('button');
        editarBtn.classList.add('edit-btn');
        editarBtn.innerHTML='Puntuar';
        editarBtn.addEventListener('click', () => abrirEvaluacionCandidato(convocatoriaId,candidato.id))
        acciones.appendChild(editarBtn);
        fila.appendChild(acciones);


        return fila;
    }

    function calcularClaseEnBaseAEvaluado(candidato) {
        if(candidato.evaluado != 0) {
            return 'evaluado';
        } else {
            return 'pendiente';
        }
    }

    function abrirEvaluacionCandidato(convocatoriaId, candidatoId) {
        document.location = `http://localhost/erasmus/interfaz/gestion/evaluar.html?convocatoriaId=${convocatoriaId}&candidatoId=${candidatoId}`
    }
});