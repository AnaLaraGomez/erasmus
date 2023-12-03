window.addEventListener("load", function() { 

    document.getElementById('atras').addEventListener('click', () => atras());
    let convocatoriaId = this.document.location.href.split('id=')[1];
    let convocatorias = JSON.parse(this.localStorage.getItem('convocatorias'));
    let convocatoria = convocatorias.find(c => c.id == convocatoriaId);
    if(!convocatoria) {
        atras();
    }

    let url = `http://localhost/erasmus/servidor/api/apiConvocatoria.php?id=${convocatoriaId}`;
    fetch(url)
    .then((respuesta) =>  respuesta.json())
    .then((detalle) => {
        pintarConvocatoria(convocatoria,detalle);
    });


    

    function atras() {
        document.location = `http://localhost/erasmus/interfaz/convocatorias/tablon.html`
    }

    function pintarConvocatoria(c, d) {
        console.log(c);

        console.log(d);
    }

})