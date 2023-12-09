var usuario;
var candidato;
var baremables;
var iframeMaximizado;
var convocatoriaId
window.addEventListener("load", function() {
    document.getElementById('cerrarIframeBtn').addEventListener('click', () => minimizarPdf());
    document.getElementById('cerrarIframeBtn').style.visibility = 'hidden';
    document.getElementById('cerrarIframeBtn').style.position = 'fixed';
    document.getElementById('cerrarIframeBtn').style.top = 0;
    document.getElementById('atras').addEventListener('click', () => atras());

    // Obtener el candidatoId y la convocatoriaId que vamos a evaluar
    let parametrosSucios = this.document.location.href.split('convocatoriaId=')[1]
    let parametros = parametrosSucios.split('&candidatoId=')
    convocatoriaId = parametros[0];
    let candidatoId = parametros[1];

    cargarDatos();

    function atras() {
        document.location = `http://localhost/erasmus/interfaz/gestion/puntuar.html`
    }

    function cargarDatos() {
        let url = `http://localhost/erasmus/servidor/api/apiEvaluacion.php?convocatoriaId=${convocatoriaId}&candidatoId=${candidatoId}`;
        fetch(url)
        .then((respuesta) =>  respuesta.json())
        .then((datos) => {
            if(datos.status_code == 200) {
                usuario = JSON.parse(datos.usuario);
                candidato = JSON.parse(datos.candidato);
                baremables = datos.baremables;
                pintarEvaluacionAlumno();
            } else  {
                // toast error;
            }
        })
    }

    function pintarEvaluacionAlumno(){
        document.getElementById('nombre').value = candidato.nombre;
        document.getElementById('telefono').value = candidato.telefono;
        document.getElementById('apellidos').value = candidato.apellidos;
        document.getElementById('fechaNac').value = candidato.fechaNac;
        document.getElementById('domicilio').value = candidato.domicilio;
        document.getElementById('dni').value = usuario.dni;
        document.getElementById('correo').value = candidato.correo;
        document.getElementById('curso').value = candidato.curso;
        mostrarCamposTutor();
        pintarBaremable();
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

    function pintarBaremable() {
        let baremablesContenedor = document.getElementById('baremablesContenedor');
        baremables.forEach(b => {
            baremablesContenedor.appendChild(generarFila(b))
        });
    }

    function generarFila(baremable) {
        /* itemId, itemNombre, itemUrl, itemNota, subeAlumno, notaMax, notaMin, requisito */
        let formId = 'item-' + baremable.itemId;

        let fila = document.createElement('tr');

        let miniatura = document.createElement('td');
        miniatura.classList.add('miniatura');
        if(baremable.itemUrl != null) {
            let iframe = document.createElement("iframe");
            iframe.src = baremable.itemUrl;
            miniatura.innerHTML= '';
            miniatura.style.display = 'inherit';
            let maxMiniatura = document.createElement('button');
            maxMiniatura.innerHTML = '+'
            maxMiniatura.addEventListener('click', (e) => maximizarPdf(iframe))
            miniatura.append(iframe);
            miniatura.append(maxMiniatura);
        }
        fila.appendChild(miniatura);

        let informacion = document.createElement('td');
        let informacionNombre = document.createElement('p');
        informacionNombre.classList.add('descripcion');
        informacionNombre.innerHTML = baremable.itemNombre;
        informacion.appendChild(informacionNombre);

        let informacionNota = document.createElement('p');
        informacionNota.classList.add('descripcion');
        informacionNota.innerHTML = 'Min: '+ baremable.notaMin + ' Max: ' + baremable.notaMax;
        informacion.appendChild(informacionNota);

        let informacionRequisito = document.createElement('p');
        informacionRequisito.classList.add('descripcion');
        informacionRequisito.innerHTML = 'Requisito: ' + ( baremable.requisito == 1 ? 'Si' : 'No' );
        informacion.appendChild(informacionRequisito);

        fila.appendChild(informacion);

        let nota = document.createElement('td');
        let formulario = document.createElement('form');
        formulario.id = formId;
        formulario.name = formId;
        let notaInput = document.createElement('input');
        notaInput.type = 'number';
        notaInput.name = 'nota';
        notaInput.max = baremable.notaMax;
        notaInput.min = 0;
        if(baremable.itemNota != null) {
            notaInput.value = baremable.itemNota;
        }
        notaInput.setAttribute('required', '');
        formulario.appendChild(notaInput);

        nota.appendChild(formulario);
        fila.appendChild(nota);

        let acciones = document.createElement('td');
        
        let guardarBtn = document.createElement('button');
        guardarBtn.innerHTML = 'Guardar Nota'
        guardarBtn.setAttribute('form', formId);
        guardarBtn.addEventListener('click', (e)=> enviarFormulario(e))

        acciones.appendChild(guardarBtn);

        if(baremable.subeAlumno == 0) {
            let seleccionarFichero = document.createElement('input');
            seleccionarFichero.type = 'file';
            seleccionarFichero.accept="application/pdf"; // solo te deja elegir PDF
            seleccionarFichero.name = 'fichero';
            seleccionarFichero.setAttribute('form', formId);
            seleccionarFichero.addEventListener('change', (e)=> {
                if(e.target.files.length == 1 && e.target.files[0].type=="application/pdf") {
                    e.target.classList.remove('error-input');
                    let iframe = document.createElement("iframe");
                    iframe.src = URL.createObjectURL(e.target.files[0]);
                    miniatura.innerHTML= '';
                    miniatura.style.display = 'inherit';
                    miniatura.append(iframe);
                    let maxMiniatura = document.createElement('button');
                    maxMiniatura.innerHTML = '+'
                    maxMiniatura.addEventListener('click', (e) => maximizarPdf(iframe))
                    miniatura.append(iframe);
                    miniatura.append(maxMiniatura);
                }
            });
            acciones.appendChild(seleccionarFichero);
        }

        fila.appendChild(acciones);

        return fila;
    }

    function enviarFormulario(e) {
        e.preventDefault();
        const formData = new FormData();
        let formId = e.target.getAttribute('form');
        // Como los elementos del form estan distribuidos a traves de los
        // diferentes td, necesitamos usar el selector de forms['form-name]
        let elements = document.forms[formId].elements;

        // En este caso no podemos hacer un new FormData(form) porque el nodo form
        // no esta completo, sino que esta formado por elementos fuera del mismo
        // Para crear el FormData, iteraremos los elementos del relacionado con el form mediante su name
        // y añadiremos cada input manualmente al formData.
        for (i=0; i<elements.length; i++){
            // Filtramos solo los input porque el button submit es un elemento del form
            // y no queremos meterlo en el formData.
            // el tagName solo nos permite introducir la string en mayúscula
            if(elements[i].tagName == 'INPUT') {
                if(elements[i].type == 'file') {
                    formData.append(elements[i].name, elements[i].files[0]);
                } else {
                    formData.append(elements[i].name, elements[i].value);
                }
            }
        }
        formData.append('convocatoriaId', convocatoriaId);
        formData.append('candidatoId', candidato.id);
        formData.append('itemId', formId.split('-')[1]);
        guardarEvaluacionDelItem(formData);
    }

    function guardarEvaluacionDelItem(formData) {
        let url = `http://localhost/erasmus/servidor/api/apiEvaluacion.php`;
        let opciones = {
            method: 'POST',
            body: formData,
        };
    
        fetch(url, opciones)
        .then((respuesta) =>  respuesta.json())
        .then((datos) => {
            if(datos.status_code == 200) {
                // Refrescamos la pagina
                document.location = document.location;
            } else  {
                // toast error;
            }
        })
    }

    function maximizarPdf(iframe) {
        iframeMaximizado = iframe;
        iframe.style.position = 'fixed';
        iframe.style.zIndex = 1000;
        iframe.style.top = '3em';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        document.getElementById('cerrarIframeBtn').style.visibility = 'visible';
    }

    function minimizarPdf() {
        iframeMaximizado.style.position = 'relative';
        iframeMaximizado.style.zIndex = 0;
        iframeMaximizado.style.top = 'unset';
        iframeMaximizado.style.width = '100%';
        iframeMaximizado.style.height = '100%';
        iframeMaximizado = null;
        document.getElementById('cerrarIframeBtn').style.visibility = 'hidden';
    }
});