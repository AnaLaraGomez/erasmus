var usuario;

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
        usuario = JSON.parse(localStorage.getItem('user'));

        localStorage.setItem('convocatorias', convocatorias.convocatorias);

        let convocatoriasDiv = document.getElementById('convocatorias');
        let tusConvocatoriasDiv = document.getElementById('tusConvocatorias');
        
        convocatoriasAbiertas.forEach(c => {
            // Ponemos usuario a Null porque deben ser pintadas como usuario anonimo.
            convocatoriasDiv.appendChild(crearNodoConvocatoria(c, null));
        });

        if(usuario != null) {
            let convocatoriasParaUsuario = document.getElementById('convocatoriasParaUsuario');

            if(usuario.admin) {
                let totalConvocatorias = convocatoriasAbiertas.concat(tusConvocatorias);
                localStorage.setItem('convocatorias', JSON.stringify(totalConvocatorias));
            }
            convocatoriasParaUsuario.innerHTML = usuario.admin ? 'Convocatorias Editables' : 'Tus Convocatorias';

            tusConvocatorias.forEach(c => {
                tusConvocatoriasDiv.appendChild(crearNodoConvocatoria(c, usuario));
            })
        }

        function crearNodoConvocatoria(c, usuario) {
            let cDiv = document.createElement('div');
            cDiv.classList.add('convocatoria');


            let cEstado = document.createElement('div');
            cEstado.classList.add('estado');
            cDiv.appendChild(cEstado)

            if(usuario && !usuario.admin) {
                // Indicamos con leyenda de colores el estado de la convocatoria (en pruebas, listado prov o def)
                cEstado.classList.add(calcularEstadoEnConvocatoria(c));
            }

            if(usuario && usuario.admin) {
                // Añadimos boton de edicion
                let cAcciones = document.createElement('div');
                let editBtn = document.createElement('button');
                editBtn.classList.add('edit-btn');
                editBtn.innerHTML = 'Editar';
                editBtn.addEventListener('click', () =>{
                    document.location = `http://localhost/erasmus/servidor/forms/EditarConvocatoria.php?convocatoriaId=${c.id}`
                });
                cAcciones.appendChild(editBtn);
                cDiv.appendChild(cAcciones)
            }

            let cInfo = document.createElement('div');
            cInfo.addEventListener('click', () => abrirConvocatoria(c.id))
            cInfo.classList.add('informacion');
            let cInfoTitulo = document.createElement('p');
            cInfoTitulo.classList.add('titulo');
            cInfoTitulo.appendChild(document.createTextNode(c.nombre + ' - ' + c.proyectoNombre));
            
            let cInfoDescripcion = document.createElement('p');
            cInfoDescripcion.addEventListener('click', () => abrirConvocatoria(c.id))
            cInfoDescripcion.classList.add('descripcion');
            cInfoDescripcion.appendChild(document.createTextNode('Periodo solicitud: ' + c.fechaInicioSolicitudes + ' - ' + c.fechaFinSolicitudes));

            cInfo.appendChild(cInfoTitulo)
            cInfo.appendChild(cInfoDescripcion)

            cDiv.appendChild(cInfo)
            return cDiv;
        }

        function abrirConvocatoria(convocatoriaId) {
            document.location = `http://localhost/erasmus/interfaz/convocatorias/detalle.html?id=${convocatoriaId}`
        }

        function calcularEstadoEnConvocatoria(c) {
            let inicioPruebas = (new Date(c.fechaInicioPruebas.replace(' ', 'T'))).getTime();
            let finPruebas = (new Date(c.fechaFinPruebas.replace(' ', 'T'))).getTime();
            let fechaProvisional = (new Date(c.fechaListaProvisional.replace(' ', 'T'))).getTime();
            let fechaDefinitiva = (new Date(c.fechaListaDefinitiva.replace(' ', 'T'))).getTime();
            let ahora = (new Date()).getTime();
            if(inicioPruebas < ahora && ahora < finPruebas) {
                return 'estado-pruebas';
            } else if(fechaProvisional < ahora && ahora < fechaDefinitiva) {
                return 'estado-provisional';
            } else if(fechaDefinitiva < ahora) {
                return 'estado-definitivo';
            }
        }
    }
})

