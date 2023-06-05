$(function () {
    $("#f_buscar").on("click", '.filtrar', filtrar);
    $("#datos").on("click", '.borrar', borrar);
    $("#datos").on("click", '.desfacturar', desfacturar);

});

function filtrar(e) {
    e.preventDefault();
    let datos = new FormData();
    let url = "views/facturas/filtrarAjax.php?evento=todos";

    if (this.id == "filtrar") {
        const fBuscar = document.getElementById("f_buscar");
        datos = new FormData(fBuscar);
        url = "views/facturas/filtrarAjax.php?evento=filtrar";
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
        "<th scope='col'>Código factura</th>" +
        "<th scope='col'>Código cliente</th>" +
        "<th scope='col'>Fecha</th>" +
        "<th scope='col'>Descuento Global </th>" +
        "<th scope='col'>Concepto </th>" +
        "<th>Ampliar/modificar</th>" +
        "<th>Borrar</th>" +
        "<th>Generar PDF</th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

    for (let i = 0; i < datos.length; i++) {
        let activo = "";
        let tipo = "danger";
        let botonEliminar = "<a class='borrar btn btn-" + tipo + " " +
            activo + "'  data-id='" + datos[i].cod_factura +
            "'><i class='fa fa-trash'></i> Borrar</a>";


        tabla += "<tr data-fila='" + datos[i].cod_factura + "'>" +
            "<td scope='col'><a class='ampliarTabla success btn " + "'  data-id='" + datos[i].cod_factura + "'><i class='fa fa-plus fa-xl'></i></a></td> " +
            "<td>" + datos[i].cod_factura + "</td>" +
            "<td>" + datos[i].cod_cliente + "</td>" +
            "<td>" + datos[i].fecha + "</td>" +
            "<td>" + datos[i].descuento_factura + "</td>" +
            "<td>" + datos[i].concepto + "</td>" +
            "<td><a class='btn btn-success' href='index.php?accion=editar&tabla=facturas&cod_factura=" + datos[i].cod_factura + "'> <i class='fas  fa-paint-brush'></i> Modificar</a></td>" +
            "<td>" + botonEliminar + "</td>" +
            "<td><a class='btn btn-success' href='index.php?accion=imprimir&tabla=facturas&cod_factura=" + datos[i].cod_factura + "'> <i class='fa  fa-file-pdf'> </i> Generar PDF</a></td>" +
            "</tr>";

        tabla += "<tr hidden data-cod_factura='" + datos[i].cod_factura + "' id='datos'>" +
            "<td colspan='11'> <table class='table table-striped table-dark text-center' style=' vertical-align: middle;'>" +
            "<thead class='table-dark'>" +
            "<tr>" +
            "<th scope='col'></th>" +
            "<th scope='col'>Num Linea Factura</th>" +
            "<th scope='col'>Precio</th>" +
            "<th scope='col'>Descuento</th>" +
            "<th scope='col'>Cantidad</th>" +
            "<th scope='col'>IVA</th>" +
            "<th scope='col'>Código factura</th>" +
            "<th scope='col'>Código artículo</th>" +
            "<th scope='col'>Número línea albarán</th>" +
            "<th scope='col'>Código albarán</th>" +
            "<th scope='col'>Desfacturar</th>" +
            "</tr>" +
            "</thead>" +
            "<tbody>";

        for (let x = 0; x < datos[i].lineaFactura.length; x++) {
            let activo = "";
            let tipo = "danger";
            let botonDesfacturar = "<a class='desfacturar btn btn-" + tipo + " " +
                activo + "'  data-id='" + datos[i].lineaFactura[x].num_linea_factura +
                "'data-albaran='" + datos[i].lineaFactura[x].cod_albaran +
                "'data-fila='" + datos[i].lineaFactura[x].cod_factura +
                "'><i class='fa fa-trash'></i> Desfacturar</a>";



            tabla +=
                "<tr data-fila='" + datos[i].cod_factura + "' data-linea='" + datos[i].lineaFactura[x].num_linea_factura + "' data-cantidad = '" + datos[i].lineaFactura[x].cantidad + "'>" +
                `<td> <img class='img-fluid rounded' style="width:100px; height:100px;"src='views/articulos/img/${datos[i].lineaFactura[x].cod_articulo}.png'></td>` +
                "<td>" + datos[i].lineaFactura[x].num_linea_factura + "</td>" +
                "<td>" + datos[i].lineaFactura[x].precio + "</td>" +
                "<td>" + datos[i].lineaFactura[x].descuento + "</td>" +
                "<td>" + datos[i].lineaFactura[x].cantidad + "</td>" +
                "<td>" + datos[i].lineaFactura[x].iva + "</td>" +
                "<td>" + datos[i].lineaFactura[x].cod_factura + "</td>" +
                "<td>" + datos[i].lineaFactura[x].cod_articulo + "</td>" +
                "<td>" + datos[i].lineaFactura[x].num_linea_albaran + "</td>" +
                "<td>" + datos[i].lineaFactura[x].cod_albaran + "</td>" +
                "<td>" + botonDesfacturar + "</td>" + 
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
            tabla = document.querySelector(`[data-cod_factura="${id}"]`);
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
    let cod_factura = this.dataset.id;
    Swal.fire({
        title: 'Estas seguro que quieres borrar el albarán: ' + cod_factura + '?',
        showDenyButton: true,
        confirmButtonText: 'Borrar',
        denyButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isDenied) {
            Swal.fire('No se borrará el albarán ', '', 'info')
            return false;
        }
        else if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("cod_factura", cod_factura);
            const myInit = {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: datos,
            };
            let peticion = new Request("views/facturas/delete.php", myInit);

            fetch(peticion)
                .then((resp) => resp.json())
                .then(function (datos) {
                    console.log(datos);
                    let Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    if (datos.ok == false) {
                        Toast.fire({
                            icon: 'error',
                            title: 'No se ha podido Borrar.',
                            text: 'No se ha podido borrar ' + cod_factura,
                        });
                    }
                    else {
                        //Seleccionamos la tabla
                        let collection = document.querySelectorAll('tr[data-fila="' + cod_factura + '"]');
                        //Seleccionamos el padre de las filas de facturas (esto nos servirá para borrar la tabla entera después)
                        padre = collection[0].parentNode;
                        console.log(collection);
                        //Borramos todas las filas que tengan que ver con el pedido (lineas incluidas)
                        for (let i = 0; i < collection.length; i++) {
                            collection[i].remove();
                        }
                        //borramos la tabla de las lineas
                        collection = document.querySelector('tr[data-cod_factura="' + cod_factura + '"]');
                        collection.remove();

                        //Si no hay más pedidos borramos la tabla entera
                        if (padre.querySelectorAll('tr[data-fila').length == 0) {
                            divDatos = document.getElementById("datos");
                            divDatos.innerHTML = "";
                        }

                        Toast.fire({
                            icon: 'success',
                            title: 'Borrado ejecutado con éxito.',
                            text: 'Se ha podido borrar la factura ' + cod_factura,
                        });
                    }

                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });
}

function desfacturar(e) {
    //boton que lo llama y que valor tiene en el campo data-id
    let cod_linea_factura = this.dataset.id;
    let cod_albaran = this.dataset.albaran;
    let cod_factura = this.dataset.fila;
    console.log(cod_factura);
    Swal.fire({
        title: 'Estas seguro que quieres desfacturar el albarán: ' + cod_albaran + '?',
        showDenyButton: true,
        confirmButtonText: 'Borrar',
        denyButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isDenied) {
            Swal.fire('No se desfacturará el albarán', '', 'info')
            return false;
        }
        else if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("cod_linea_factura", cod_linea_factura);
            const myInit = {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: datos,
            };
            let peticion = new Request("views/facturas/delete.php", myInit);

            fetch(peticion)
                .then((resp) => resp.json())
                .then(function (datos) {
                    let Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    if (datos.ok == false) {
                        Toast.fire({
                            icon: 'error',
                            title: 'No se ha podido desfacturar.',
                            text: 'No se ha podido desfacturar el albarán' + cod_albaran,
                        });
                    }
                    else {
                        let elemento = document.querySelector('tr[data-linea="' + cod_linea_factura + '"]');
                        elemento.parentNode.removeChild(elemento);

                        //En el caso de que sea la última línea del pedido borramos el pedido completo.
                        dataFila = document.querySelectorAll('tr[data-fila="' + cod_factura + '"]');

                        if (dataFila.length == 1) {
                            dataPedido = document.querySelector('tr[data-cod_factura="' + cod_factura + '"]');
                            padre = dataPedido.parentNode;
                            dataFila[0].parentNode.removeChild(dataFila[0]);
                            dataPedido.parentNode.removeChild(dataPedido);

                            //Buscamos si hay más pedidos
                            //Si no hay más pedidos borramos la tabla entera
                            if (padre.querySelectorAll('tr[data-fila').length == 0) {
                                divDatos = document.getElementById("datos");
                                divDatos.innerHTML = "";
                            }
                        }

                        Toast.fire({
                            icon: 'success',
                            title: 'Borrado ejecutado con éxito.',
                            text: 'Se ha podido desfacturar el albarán ' + cod_albaran,
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });
}
