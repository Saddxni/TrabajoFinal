$(function () {
    $("#f_buscar").on("click", '.filtrar', filtrar);
    $("#datos").on("click", '.borrar', borrar);
});

function filtrar(e) {

    e.preventDefault();
    let datos = new FormData();
    let url = "views/albaranes/filtrarAjax.php?evento=todos";

    if (this.id == "filtrar") {
        const fBuscar = document.getElementById("f_buscar");
        datos = new FormData(fBuscar);
        url = "views/albaranes/filtrarAjax.php?evento=filtrar";
    }
    const myInit = {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
    };
    let peticion = new Request(url, myInit);
    fetch(peticion)
        .then((resp) => resp.json())
        .then(function (respuesta) {
            console.log(respuesta);
            document.getElementById("datos").innerHTML = "";
            if (respuesta.ok == true) {
                if (respuesta.datos.length > 0) DibujarTabla(respuesta.datos);
                else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Oops... Sin datos',
                        text: 'La búsqueda no ha obtenido datos. Revise las opciones de búsqueda!',
                    });
                }
            }
            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... lo siento',
                    text: 'Me avergüenza decirlo, pero la búsqueda ha fallado!',
                });

            }

        })
        .catch(function (error) {
            console.log(error);
            document.getElementById("datos").innerHTML = "";
            Swal.fire({
                icon: 'error',
                title: 'Oops... lo siento',
                text: 'Me avergüenza decirlo, pero la búsqueda ha fallado!',
            });
        });
}

function DibujarTabla(datos) {
    let contenidoTabla = document.getElementById("datos");
    contenido = "";
    tabla = "</br>" +
        "<table id='datos' class='table table text-center align-middle'>" +
        "<thead class='table-dark'>" +
        "<tr>" +
        "<th scope='col'></th>" +
        "<th scope='col'>Código albaran</th>" +
        "<th scope='col'>Generado de pedido</th>" +
        "<th scope='col'>Código cliente</th>" +
        "<th scope='col'>Fecha </th>" +
        "<th scope='col'>Concepto </th>" +
        "<th scope='col'>Estado </th>" +
        "<th>Ampliar/modificar</th>" +
        "<th>Borrar</th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

    for (let i = 0; i < datos.length; i++) {
        let activo = "";
        let tipo = "danger";
        if (datos[i].estado != "No facturado") {
            activo = "disabled";
            tipo = "secondary"
        }
        let botonEliminar = "<a class='borrar btn btn-" + tipo + " " +
            activo + "'  data-id='" + datos[i].cod_albaran +
            "' data-name='" + datos[i].nick +
            "'><i class='fa fa-trash'></i> Borrar</a>";


        tabla += "<tr data-fila='" + datos[i].cod_albaran + "'>" +
            "<td scope='col'><a class='ampliarTabla success btn " + "'  data-id='" + datos[i].cod_albaran + "'><i class='fa fa-plus fa-xl'></i></a></td> " +
            "<td>" + datos[i].cod_albaran + "</td>" +
            "<td>" + datos[i].generado_de_pedido + "</td>" +
            "<td>" + datos[i].cod_cliente + "</td>" +
            "<td>" + datos[i].fecha + "</td>" +
            "<td>" + datos[i].concepto + "</td>" +
            "<td>" + datos[i].estado + "</td>" +
            "<td><a class='btn btn-success " + activo + "'href='index.php?accion=editar&tabla=albaranes&idPedido=" + datos[i].cod_albaran + "'> <i class='fas  fa-paint-brush'></i> Modificar</a></td>" +
            "<td>" + botonEliminar + "</td>" +
            "</tr>";

        tabla += "<tr hidden data-cod_albaran='" + datos[i].cod_albaran + "' id='datos'>" +
            "<td colspan='11'> <table class='table table-striped table-dark text-center' style=' vertical-align: middle;'>" +
            "<thead class='table-dark'>" +
            "<tr>" +
            "<th scope='col'>Num Linea</th>" +
            "<th scope='col'>Código artículo</th>" +
            "<th scope='col'>Nombre artículo</th>" +
            "<th scope='col'>Precio</th>" +
            "<th scope='col'>Cantidad</th>" +
            "<th scope='col'>IVA</th>" +
            "<th scope='col'>Descuento</th>" +
            "</tr>" +
            "</thead>" +
            "<tbody>";

        for (let x = 0; x < datos[i].lineaAlbaran.length; x++) {

            tabla +=
                "<tr data-fila='" + datos[i].cod_albaran + "' data-linea='" + datos[i].lineaAlbaran[x].num_linea_pedido + "' data-cantidad = '" + datos[i].lineaAlbaran[x].cantidad + "'>" +
                "<td>" + datos[i].lineaAlbaran[x].num_linea_albaran + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].cod_articulo + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].nombre + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].precio + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].cantidad + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].iva + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].descuento+ "</td>" +
                "</tr>";
        }
        tabla +=
            "</td>" +
            "</tr>" +
            "</tbody>" +
            "</table>";

    }
    tabla += "</tbody> </table>";
    contenido += tabla;
    contenidoTabla.innerHTML = contenido;
    td = document.getElementsByTagName('td');
    for (let i = 0; i < td.length; i++) {
        td[i].className = 'align-middle';
    }
    ampliarTabla();
}

function ampliarTabla() {
    // Obtén todos los elementos con la clase 'ampliarTabla'
    const toggleBtns = document.querySelectorAll('.ampliarTabla');
    console.log(toggleBtns);

    // Recorre todos los elementos y agrega el evento 'click' a cada uno
    toggleBtns.forEach(function (toggleBtn) {
        const toggleIcon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', function (fila) {
            const button = fila.currentTarget;
            id = button.getAttribute('data-id')
            tabla = document.querySelector(`[data-cod_albaran="${id}"]`);
            // Comprueba si el elemento 'i' tiene la clase 'fa-plus'
            if (toggleIcon.classList.contains('fa-plus')) {
                // Si lo tiene, cambia la clase a 'fa-minus'
                toggleIcon.classList.remove('fa-plus');
                toggleIcon.classList.add('fa-minus');
                tabla.removeAttribute('hidden', false);

            } else {
                // Si no, cambia la clase a 'fa-plus'
                toggleIcon.classList.remove('fa-minus');
                toggleIcon.classList.add('fa-plus');
                tabla.setAttribute('hidden', true);
            }
        });
    });
}

function borrar(e) {
    //boton que lo llama y que valor tiene en el campo data-id
    let valorEliminar = this.dataset.id;
    let arrayLineas = extraerDatos(valorEliminar);
    let arrayDatos = {};
    arrayDatos.arrayLineas = arrayLineas;
    arrayDatos.cod_albaran = valorEliminar;
    console.log(arrayDatos);
    Swal.fire({
        title: 'Estas seguro que quieres borrar el albarán: ' + valorEliminar + '?',
        showDenyButton: true,
        confirmButtonText: 'Borrar',
        denyButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isDenied) {
            Swal.fire('No se borrará el albarán ', '', 'info')
            return false;
        }
        else if (result.isConfirmed) {
            arrayDatos = JSON.stringify(arrayDatos);
            const datos = new FormData();
            datos.append("arrayDatos", arrayDatos);
            const myInit = {
                method: "POST", //GET POST PUT DELETE etc..
                mode: "cors",
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                body: datos,
            };
            let peticion = new Request("views/albaranes/delete.php", myInit);

            //NOTACION COMPRIMIDA
            fetch(peticion)
                .then((resp) => resp.json())
                .then(function (datos) {
                    let Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    // y vuestra tarea es dibujar los errores.
                    if (datos.ok == false) {
                        Toast.fire({
                            icon: 'error',
                            title: 'No se ha podido Borrar.',
                            text: 'No se ha podido borrar ' + valorEliminar,
                        });
                    }
                    else {
                        let collection = document.querySelectorAll('tr[data-fila="' + valorEliminar + '"]');
                        for (let i = 0; i < collection.length; i++) {
                            collection[i].remove();
                        }
                        collection = document.querySelectorAll('table[data-fila="' + valorEliminar + '"]');
                        for (let i = 0; i < collection.length; i++) {
                            collection[i].remove();
                        }

                        Toast.fire({
                            icon: 'success',
                            title: 'Borrado ejecutado con exito.',
                            text: 'Se ha podido borrar la línea ' + valorEliminar,
                        });
                    }

                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });
}

function extraerDatos(albaranEliminar) {
    lineas = document.querySelectorAll('tr[data-fila="' + albaranEliminar + '"]');
    let arrayLineas = [];
    let datos;
    for (let indice = 1; indice < lineas.length; indice++) {
        
        if(lineas[indice].dataset.cantidad != 0){
            datos = {
                lineaPedido: lineas[indice].dataset.linea,
                cantidad: lineas[indice].dataset.cantidad
            };
            arrayLineas.push(datos);
        }
    }
    console.log(arrayLineas);
    return arrayLineas;
}
