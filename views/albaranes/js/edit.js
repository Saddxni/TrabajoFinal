let botonInsertar = document.getElementById("modificar");

//Activación y desactivación del botón cuando esté todo correcto.
window.addEventListener('load', function () {
  var camposNumericos = document.getElementsByClassName('input_cantidad');
  botonInsertar.disabled = true;

  var validarNumeros = function (e) {
    campoInput = e.target;
    console.log(campoInput.value);
    console.log(campoInput.dataset.cantidadanterior);
      //El botón de deshabilita si el campo tiene el mismo valor que antes de modificar, si es superior al mínimo o inferior al máximo.
      if (Number(campoInput.value) < Number(campoInput.min) || Number(campoInput.value > Number(campoInput.max) 
      || Number(campoInput.value == Number(campoInput.dataset.cantidadanterior)))){
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
  if (arrayArticulos.cont != 0) {
    let datos = new FormData();
    delete arrayArticulos.cont;


    arrayArticulos = JSON.stringify(arrayArticulos.arrayArticulos);
    datos.append("arrayAlbaran", arrayArticulos);

    url = "views/albaranes/store.php?evento=editar";
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
            text: 'No se ha podido modificar el albarán.',
          });
        }
        else {
          Toast.fire({
            icon: 'success',
            title: 'Inserción ejecutada con éxito.',
            text: 'El albarán se ha modificado',
          });
          botonInsertar.disabled = true;
        }
      })
      .catch(function (error) {
        console.log(error);
        Swal.fire({
          icon: 'error',
          title: 'Algo ha fallado!!',
          text: 'Ha ocurrido un error al modificar el albarán',
        });
      });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Algo ha fallado!',
      text: 'Los datos del albarán no han sido modificados.',
    });
  }
}


function extraerDatos() {
  arrayArticulos = {};
  arrayDatos = {};
  tbody = document.getElementsByTagName("tbody")
  lineas = tbody[0];
  lineas = lineas.getElementsByTagName("tr");
  cont = 0;
  console.log(lineas);
  arrayArticulos = {};
  for (const linea of lineas) {
    iva = linea.querySelector('#iva');
    descuento = linea.querySelector('#descuento');
    num_linea_albaran = linea.dataset.num_linea_albaran;
    if ((iva.value != iva.dataset.cantidadanterior) || descuento.value != descuento.dataset.cantidadanterior) {
      datosCelda = {};
      datosCelda["iva"] = iva.value;
      iva.cantidadanterior = iva.value;
      datosCelda["descuento"] = descuento.value;
      descuento.cantidadanterior = descuento.value;
      datosCelda["num_linea_albaran"] = num_linea_albaran;
      arrayArticulos[cont] = datosCelda;
      cont++;
    }
  }
  arrayDatos["cont"] = cont;
  arrayDatos["arrayArticulos"] = arrayArticulos;
  console.log(arrayDatos);
  return arrayDatos;
}