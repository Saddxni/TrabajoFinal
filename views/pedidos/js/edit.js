let botonInsertar = document.getElementById("modificar");

//Activación y desactivación del botón cuando esté todo correcto.
window.addEventListener('load', function () {
  var camposNumericos = document.getElementsByClassName('input_cantidad');
  botonInsertar.disabled = true;

  var validarNumeros = function () {
    for (var i = 0; i < camposNumericos.length; i++) {
      //El botón de deshabilita si el campo tiene el mismo valor que antes de modificar, si es superior al mínimo o inferior al máximo.
      if (Number(camposNumericos[i].value) < Number(camposNumericos[i].min) || Number(camposNumericos[i].value == 0)) {
        botonInsertar.disabled = true;
        return;
      }
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
  if (arrayArticulos.cont != 0) {

    let datos = new FormData();
    delete arrayArticulos.cont;

    cod_pedido = arrayArticulos.cod_pedido;
    datos.append("cod_pedido", cod_pedido);
    delete arrayArticulos.cod_pedido;

    arrayArticulos = JSON.stringify(arrayArticulos.arrayArticulos);
    datos.append("arrayArticulos", arrayArticulos);

    url = "views/pedidos/store.php?evento=editar";
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
        let Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
        console.log(respuesta);
        if (respuesta.ok == false) {
          Swal.fire({
            icon: 'error',
            title: 'Algo ha fallado!',
            text: 'No se ha podido modificar el pedido.',
          });
        }
        else {
          Toast.fire({
            icon: 'success',
            title: 'Inserción ejecutada con éxito.',
            text: 'El pedido se ha modificado',
          });

          botonInsertar.disabled = true;
        }
      })
      .catch(function (error) {
        console.log(error);
        Swal.fire({
          icon: 'error',
          title: 'Algo ha fallado!!',
          text: 'Ha ocurrido un error al modificar el pedido',
        });
      });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Algo ha fallado!',
      text: 'Los datos del pedido no han sido modificados.',
    });
  }
}


function extraerDatos() {
  arrayDatos = {};
  arrayArticulos = {};
  cod_pedido = document.getElementById("cod_pedido").value;
  tbody = document.getElementsByTagName("tbody")
  lineas = tbody[0];
  lineas = lineas.getElementsByTagName("tr");
  cont = 0;
  for (const linea of lineas) {
    console.log(linea);
    celdaCantidad = linea.querySelector('#cantidad');
    if (celdaCantidad.value != celdaCantidad.dataset.cantidadanterior) {
      datosCelda = {};
      datosCelda["num_linea"] = linea.dataset.num_linea;
      datosCelda["cantidad"] = celdaCantidad.value;
      celdaCantidad.dataset.cantidadanterior = celdaCantidad.value;
      arrayArticulos[cont] = datosCelda;
      cont++;
    }

  }
  arrayDatos["cod_pedido"] = cod_pedido;
  arrayDatos["cont"] = cont;
  arrayDatos["arrayArticulos"] = arrayArticulos;
  console.log(arrayDatos);
  return arrayDatos;
}