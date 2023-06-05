inputCliente = document.getElementById('busqueda');
inputCliente.addEventListener('change', filtrar);
botonInsertar = document.getElementById('insertar');

//Al cargar la página
window.addEventListener('load', function () {
    //Inicializamos el filtro del primer cliente que aparezca en el select
    filtrar();
    botonInsertar.disabled = true;

});

//Función que muestra las facturas de cada cliente en función de cual seleccionemos
function filtrar() {
    const formulario = document.getElementById("formulario");
    datos = new FormData(formulario);
    let url = "views/albaranes/filtrarAjax.php?evento=filtrar";

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
            document.getElementById("datos").innerHTML = "";
            if (respuesta.ok == true) {
                if (respuesta.datos.length > 0) {
                    DibujarTabla(respuesta.datos);
                    funciones();
                    botonInsertar.disabled = true;
                }

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
//Función que inserta una factura nueva
botonInsertar.onclick = function(e) {
    e.preventDefault();

    const formInsercion = document.getElementById("formulario");
    const datos = new FormData(formInsercion);

    Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    arrayDatos = extraerDatos();
    if (arrayDatos["respuesta"] == false) {
        Toast.fire({
            icon: 'error',
            title: 'No se ha podido crear el albarán.',
            text: 'revisa los errores que aparecerán debajo de los campos..'
        });
    } else {
        array = JSON.stringify(arrayDatos);
        datos.append("arrayFactura", array);
        const myInit = {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            body: datos,
        };
        let peticion = new Request("views/facturas/store.php?evento=crear", myInit);
        fetch(peticion)
            .then((resp) => resp.json())
            .then(function (datos) {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                if (datos.ok == false) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Han ocurrido errores durante la inserción.',
                        text: 'revisa los errores que aparecerán debajo de los campos..'
                    });
                } else {
                    Toast.fire({
                        icon: 'success',
                        title: 'Inserción ejecutada con éxito.',
                        text: 'El albarán se ha añadido a la base de datos',
                    });
                    //limpiamos los campos
                    botonInsertar.disabled = true;
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    }
}

//Función que dibuja la tabla con los datos que le pasemos por parámetro
function DibujarTabla(datos) {
    let contenidoTabla = document.getElementById("datos");
    contenido = "";
    tabla =
        "<table id='tablaDatos' class='table table text-center align-middle'>" +
        "<thead class='table-dark'>" +
        "<tr>" +
        "<th scope='col'></th>" +
        "<th scope='col'>Código albaran</th>" +
        "<th scope='col'>Generado de pedido</th>" +
        "<th scope='col'>Código cliente</th>" +
        "<th scope='col'>Fecha </th>" +
        "<th scope='col'>Concepto </th>" +
        "<th scope='col'>Estado </th>" +
        "<th scope='col'></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

    for (let i = 0; i < datos.length; i++) {
        let activo = "";
        let tipo = "success";
        if (datos[i].estado != "No facturado") {
            activo = "disabled";
            tipo = "secondary"
        }
        let botonFacturar = "<a class='facturar btn btn-" + tipo + " " +
            activo + "'  data-id='" + datos[i].cod_albaran +
            "' data-name='" + datos[i].nick +
            "'><i class='fa fa-file-invoice'></i> Facturar</a>";


        tabla += "<tr data-fila='" + datos[i].cod_albaran + "'>" +
            "<td scope='col'><a class='ampliarTabla success btn " + "'  data-id='" + datos[i].cod_albaran + "'><i class='fa fa-plus fa-xl'></i></a></td> " +
            "<td>" + datos[i].cod_albaran + "</td>" +
            "<td>" + datos[i].generado_de_pedido + "</td>" +
            "<td>" + datos[i].cod_cliente + "</td>" +
            "<td>" + datos[i].fecha + "</td>" +
            "<td>" + datos[i].concepto + "</td>" +
            "<td>" + datos[i].estado + "</td>" +
            "<td> " + botonFacturar + "</td>" +
            "</tr>";

        tabla += "<tr hidden data-cod_albaran='" + datos[i].cod_albaran + "' id='datos'>" +
            "<td colspan='11'> <table class='table table-striped table-dark text-center'>" +
            "<thead class='table-dark'>" +
            "<tr>" +
            "<th scope='col'></th>" +
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
                `<td> <img class='img-fluid rounded' style="width:100px; height:100px;"src='views/articulos/img/${datos[i].lineaAlbaran[x].cod_articulo}.png'></td>` +
                "<td>" + datos[i].lineaAlbaran[x].num_linea_albaran + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].cod_articulo + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].nombre + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].precio + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].cantidad + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].iva + "</td>" +
                "<td>" + datos[i].lineaAlbaran[x].descuento + "</td>" +
                

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

//Función que permite desplegar y recoger las líneas de cada albarán 
function ampliarTabla() {
    // Obtén todos los elementos con la clase 'ampliarTabla'
    const toggleBtns = document.querySelectorAll('.ampliarTabla');
    //console.log(toggleBtns);

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



// Función para mover una fila de la tabla de arriba a la tabla de abajo
function facturarFila(evento) {
    const fila = evento.currentTarget.parentNode.parentNode;
    cod_albaran = fila.dataset.fila;
    const subFila = document.querySelector('tr[data-cod_albaran="' + cod_albaran + '"]');
    const boton = evento.currentTarget;

    // Cambiar el texto del botón y el evento click
    boton.innerHTML = "<i class='fa fa-file-invoice'></i> Desfacturar";
    boton.removeEventListener('click', facturarFila);
    boton.addEventListener('click', desfacturarFila);       
    boton.classList.remove('btn-success');
    boton.classList.add('btn-danger');
    // Mover la fila de la tabla de arriba a la tabla de abajo
    tablaFactura.querySelector('tbody').appendChild(fila);
    tablaFactura.querySelector('tbody').appendChild(subFila);
    validar();
}

// Función para mover una fila de la tabla de abajo a la tabla de arriba
function desfacturarFila(evento) {
    const fila = evento.currentTarget.parentNode.parentNode;
    cod_albaran = fila.dataset.fila;
    const subFila = document.querySelector('tr[data-cod_albaran="' + cod_albaran + '"]');
    const boton = evento.currentTarget;

    // Cambiar el texto del botón y el evento click
    boton.innerHTML = "<i class='fa fa-file-invoice'></i> Facturar";
    boton.classList.remove('btn-danger');
    boton.classList.add('btn-success');
    boton.removeEventListener('click', desfacturarFila);
    boton.addEventListener('click', facturarFila);

    // Mover la fila de la tabla de abajo a la tabla de arriba
    tablaDatos.querySelector('tbody').appendChild(fila);
    tablaDatos.querySelector('tbody').appendChild(subFila);
    validar();
}


function funciones() {
    // Obtener referencias a las tablas y a las filas
    const tablaDatos = document.getElementById('tablaDatos');
    const tablaFacturas = document.getElementById('tablaFactura');
    const filasTablaFacturas = tablaFacturas.getElementsByTagName('tr');

    //Si cambiamos el cliente, borramos la tabla de facturas
    if (filasTablaFacturas.length > 1) {
        nodoPadre = filasTablaFacturas[1].parentNode;
        hijos = nodoPadre.childNodes;
        for (let i = 0; i < hijos.length + 1; i++) { 
            nodoPadre.removeChild(hijos[0]);
        }
    }
    // Agregar el evento click a los botones de la tabla de arriba
    const botonesFacturar = tablaDatos.getElementsByClassName('facturar');
    for (let i = 0; i < botonesFacturar.length; i++) {
        botonesFacturar[i].addEventListener('click', facturarFila);
        
    }
}

function validar(){
    filasTablaFacturas = document.querySelectorAll('#tablaFactura tbody tr');
    descuento = document.getElementById('descuento');
    botonInsertar.disabled = true;
    if(Number(descuento.value) <= 100 && Number(descuento.value) >= 0 && filasTablaFacturas.length > 0){
        botonInsertar.disabled = false;
        
    }
    
}
descuento.addEventListener('input', validar);

function extraerDatos() {
    //Recogemos los datos para generar la Factura.
    fecha = creaFecha();
    //Creamos el objeto base y almacenamos los datos.
    data = {};
    data["respuesta"] = false;
    data["fecha"] = fecha;
    //Recogemos los datos para generar las líneas de Factura.
    lineas = document.querySelectorAll("#tablaFactura tr[data-linea");
    datosLinFac = {};
    cont = 0;
    for (const linea of lineas) {
        console.log(linea);
        celdas = linea.children;
        cod_albaran = linea.dataset.fila;
        datosCelda = {};
            num_linea_albaran = celdas[1].innerHTML
            datosCelda["num_linea_albaran"] = celdas[1].innerHTML;
            datosCelda["cod_articulo"] = celdas[2].innerHTML;
            datosCelda["precio"] = celdas[4].innerHTML;
            datosCelda["cantidad"] = celdas[5].innerHTML;
            datosCelda["iva"] = celdas[6].innerHTML;
            datosCelda["descuento"] = celdas[7].innerHTML;
            datosCelda["cod_albaran"] = cod_albaran;
            datosLinFac[num_linea_albaran] = datosCelda;
            cont++;        
    }
    if(cont > 0){
        data["respuesta"] = true;
        data["linFac"] = datosLinFac;
    }
    console.log(data);
    return data;
}

function creaFecha() {
    fecha = new Date;
    const anyo = fecha.getFullYear();
    const mes = fecha.getMonth() + 1;
    const dia = fecha.getDate();
    const fechaSQl = `${anyo}-${mes.toString().padStart(2, '0')}-${dia.toString().padStart(2, '0')}`;
    return fechaSQl;
}