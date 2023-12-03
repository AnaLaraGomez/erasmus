window.addEventListener("load", function() { 
    let url = 'http://localhost/erasmus/servidor/api/apiConvocatoria.php';
    fetch(url)
    .then((respuesta) =>  respuesta.json())
    .then((convocatorias) => {
        pintarConvocatoria(convocatorias);
    });

    function pintarConvocatoria(convocatorias) {
        let convocatoriasAbiertas = JSON.parse(convocatorias.convocatorias);
        let tusConvocatorias = JSON.parse(convocatorias.tusConvocatorias);

        localStorage.setItem('convocatorias', convocatorias.convocatorias);

        let convocatoriasDiv = document.getElementById('convocatorias');
        let tusConvocatoriasDiv = document.getElementById('tusConvocatorias');
        
        convocatoriasAbiertas.forEach(c => {
            convocatoriasDiv.appendChild(crearNodoConvocatoria(c));
        });

        tusConvocatorias.forEach(c => {
            tusConvocatoriasDiv.appendChild(crearNodoConvocatoria(c));
        })


        function crearNodoConvocatoria(c) {
            let cDiv = document.createElement('div');
            cDiv.classList.add('convocatoria');
            cDiv.addEventListener('click', () => abrirConvocatoria(c.id))

            let cEstado = document.createElement('div');
            cEstado.classList.add('estado');

            let cInfo = document.createElement('div');
            cInfo.classList.add('informacion');
            let cInfoTitulo = document.createElement('p');
            cInfoTitulo.classList.add('titulo');
            cInfoTitulo.appendChild(document.createTextNode(c.nombre + ' - ' + c.proyectoNombre));
            
            let cInfoDescripcion = document.createElement('p');
            cInfoDescripcion.classList.add('descripcion');
            cInfoDescripcion.appendChild(document.createTextNode('Periodo solicitud: ' + c.fechaInicioSolicitudes + ' - ' + c.fechaFinSolicitudes));

            cInfo.appendChild(cInfoTitulo)
            cInfo.appendChild(cInfoDescripcion)

            cDiv.appendChild(cEstado)
            cDiv.appendChild(cInfo)
            return cDiv;
        }

        function abrirConvocatoria(convocatoriaId) {
            document.location = `http://localhost/erasmus/interfaz/convocatorias/detalle.html?id=${convocatoriaId}`
        }
    }
})

