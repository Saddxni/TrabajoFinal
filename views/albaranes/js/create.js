let botonInsertar = document.getElementById("insertar");
var camposNumericos = document.getElementsByClassName('input_cantidad');

window.addEventListener('load', function () {

    botonInsertar.disabled = true;

    var validarNumeros = function () {
        for (var i = 0; i < camposNumericos.length; i++) {
            console.log(camposNumericos[i].value);
            if (Number(camposNumericos[i].value) > Number(camposNumericos[i].max) || Number(camposNumericos[i].value) < Number(camposNumericos[i].min)) {
                botonInsertar.disabled = true;
                return;
            }
        }
        botonInsertar.disabled = false;
    };

    var cambiarMaximo = function () {
        for (var i = 0; i < camposNumericos.length; i++) {
            var nuevoMaximo = Number(camposNumericos[i].max) - Number(camposNumericos[i].value);
            camposNumericos[i].max = nuevoMaximo >= Number(camposNumericos[i].min) ? nuevoMaximo : Number(camposNumericos[i].min);
        }
        validarNumeros();
    };

    for (var i = 0; i < camposNumericos.length; i++) {
        camposNumericos[i].addEventListener('input', validarNumeros);
    }
    botonInsertar.addEventListener('click', cambiarMaximo);
});



botonInsertar.onclick = function (e) {
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
        datos.append("arrayAlbaran", array);
        const myInit = {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            body: datos,
        };
        let peticion = new Request("views/albaranes/store.php?evento=crear", myInit);
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

                    //Limpiamos los datos
                    for (let i = 0; i < camposNumericos.length; i++) {
                        camposNumericos[i].value = 0;
                    }
                }


            })
            .catch(function (error) {
                console.log(error);
            })
    }
}

function extraerDatos() {
    //Recogemos los datos para generar el Albarán.
    fecha = creaFecha();
    cod_cliente = document.getElementById("cod_cliente").value;
    cod_pedido = document.getElementById("cod_pedido").value;
    concepto = document.getElementById("concepto").value;
    //Creamos el objeto base y almacenamos los datos.
    data = {};
    data["respuesta"] = true;
    data["fecha"] = fecha;
    data["cod_cliente"] = cod_cliente;
    data["generado_de_pedido"] = cod_pedido;
    data["concepto"] = concepto;
    //Recogemos los datos para generar las líneas de Albarán.
    tbody = document.getElementsByTagName("tbody")
    lineas = tbody[0];
    lineas = lineas.getElementsByTagName("tr");
    datosLinAlb = {};

    cont = 0;
    for (const index of lineas) {
        //Sumamos la cantidad a la cantidad en albarán
        cantidad = index.querySelector("#cantidad").value;
        celdas = index.getElementsByTagName("td");
        datosCelda = {};
        if (cantidad != 0) {
            cantAlb = Number(celdas[5].innerHTML) + Number(cantidad);
            celdas[5].innerHTML = cantAlb;


            datosCelda["num_linea_pedido"] = celdas[0].innerHTML;
            datosCelda["cod_articulo"] = celdas[1].innerHTML;
            datosCelda["nombre"] = celdas[2].innerHTML;
            datosCelda["precio"] = celdas[3].innerHTML;
            datosCelda["cantidadTotal"] = celdas[4].innerHTML;
            datosCelda["cantidadEnAlbaran"] = celdas[5].innerHTML;
            datosCelda["cantidad"] = cantidad;
            datosCelda["descuento"] = celdas[6].innerHTML;
            datosCelda["iva"] = celdas[7].innerHTML;
            datosLinAlb[cont] = datosCelda;
            cont++;
            console.log(datosLinAlb);
        }
    }

    data["linAlb"] = datosLinAlb;
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

