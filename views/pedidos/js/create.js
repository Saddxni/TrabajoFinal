let botonInsertar = document.getElementById("insertar");
var campos = ["fecha"];
var camposNumericos = document.getElementsByClassName('input_cantidad');
//Fecha de hoy por defecto y como máximo
const fechaMin = new Date('2000-01-01');
const fechaActual = new Date().toISOString().slice(0, 10);
var campoFecha = document.getElementById("fecha");
var fechaConvertida = new Date(campoFecha.value);
campoFecha = document.getElementById("fecha");
campoFecha.value = fechaActual;
campoFecha.max = fechaActual;


window.addEventListener('load', function() {
    var camposNumericos = document.getElementsByClassName('input_cantidad');
    
    botonInsertar.disabled = true;
  

    var validarNumeros = function() {
    let totalCantidad = 0;
      for (var i = 0; i < camposNumericos.length; i++) {
        totalCantidad += Number(camposNumericos[i].value);
      }
      if(totalCantidad <= 0){
        botonInsertar.disabled = true;
        return;
      }
      botonInsertar.disabled = false;
    };
  
    for (var i = 0; i < camposNumericos.length; i++) {
        camposNumericos[i].addEventListener('input', validarNumeros);
      }
    
    
  });

botonInsertar.onclick = function (e) {
    e.preventDefault();
    arrayArticulos = extraerDatos();
    
    cont = arrayArticulos.cont;
    validacion = validarFormulario(cont);
    
    if(validacion == false && arrayArticulos.cont != 0){
    const formInsercion = document.getElementById("f_insercion");
    const datos = new FormData(formInsercion);

    delete arrayArticulos.cont;
    array = JSON.stringify(arrayArticulos);

    datos.append("arrayArticulos", array);
    const myInit = {
        method: "POST",
        mode: "cors",
        cache: "no-cache", 
        body: datos,
    };
    let peticion = new Request("views/pedidos/store.php?evento=crear", myInit);

    
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

                dibujarErrores(datos.errores, campos);
            } else {
                Toast.fire({
                    icon: 'success',
                    title: 'Inserción ejecutada con éxito.',
                    text: 'El pedido se ha añadido a la base de datos',
                });

                //Limpiamos los datos
                for (let i = 0; i < camposNumericos.length; i++) {
                    camposNumericos[i].value = 0; 
                }
                botonInsertar.disabled = true;
            }
        })
        .catch(
            function (error) {
                console.log(error);
            })
        }
};



function dibujarErrores(errores, campo) {
    for (const campo of campos) {
        campoError = document.getElementById("errores" + campo);
        if (campo in errores) {
            campoError.innerHTML = errores[campo];
            campoError.classList.remove("invisible");
        } else {
            campoError.innerHTML = "";
            campoError.classList.add("invisible");
        }
    }
}

function validarFormulario() {
    let error = false;
    let errores = [];    

    if (fechaConvertida > fechaActual || fechaConvertida < fechaMin) {
        error = true;
    }
    dibujarErrores(errores, campos);
    return error;
}

function extraerDatos() {
    arrayDatos = {};
    tabla = document.getElementById("datos");
    tbody = tabla.getElementsByTagName("tbody")
    lineas = tbody[0];
    lineas = lineas.getElementsByTagName("tr");
    cont = 0;
    
    for (const index of lineas) {
        celdas = index.getElementsByTagName("td");
        celdaCantidad = celdas[3].childNodes;
        if (celdaCantidad[0].value != 0) {
            datosCelda = {};
            datosCelda["cod_articulo"] = celdas[0].innerHTML;
            datosCelda["nombre"] = celdas[1].innerHTML;
            datosCelda["precio"] = celdas[2].innerHTML;
            datosCelda["cantidad"] = celdaCantidad[0].value;
            datosCelda["descuento"] = celdas[4].innerHTML;
            datosCelda["iva"] = celdas[5].innerHTML;
            arrayDatos[cont] = datosCelda;
            cont++;
        }
    }
    arrayDatos["cont"] = cont;
    console.log(arrayDatos);
    return arrayDatos;
}