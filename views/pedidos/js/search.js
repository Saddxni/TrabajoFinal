$(function () {
  $("#f_buscar").on("click", '.filtrar', filtrar);
  $("#datos").on("click", '.borrarPedido', eliminarPedido);
  $("#datos").on("click", '.borrarLinea', eliminarLinea);
});


function filtrar(e) {

  e.preventDefault();
  let datos = new FormData();
  let url = "views/pedidos/filtrarAjax.php?evento=todos";

  if (this.id == "filtrar") {
    const fBuscar = document.getElementById("f_buscar");
    datos = new FormData(fBuscar);
    url = "views/pedidos/filtrarAjax.php?evento=filtrar";
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

      document.getElementById("datos").innerHTML = "";
      if (respuesta.ok == true) {

        if (respuesta.datos.length > 0) DibujarTablaPedido(respuesta.datos);
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

function DibujarTablaPedido(datos) {
  let contenidoTabla = document.getElementById("datos");
  tabla = "</br>" +
    "<table id='tablaDatos' class='table table text-center'>" +
    "<thead class='table-dark'>" +
    "<tr>" +
    "<th scope='col'></th>" +
    "<th scope='col'>Código pedido</th>" +
    "<th scope='col'>Código cliente</th>" +
    "<th scope='col'>Fecha</th>" +
    "<th scope='col'>Estados</th>" +
    "<th>Borrar</th><th>Modificar pedido</th>" +
    "<th>Crear albarán</th>" +
    "</tr>" +
    "</thead>" +
    "<tbody>";

  contenido = "";
  for (let i = 0; i < datos.length; i++) {
    let activo = "";
    let tipo = "danger";
    if (datos[i].estado != "Sin Albaran") {
      activo = "disabled";
      tipo = "secondary"
    }
    let botonEliminar = "<a class='borrarPedido btn btn-" + tipo + " " +
      activo + "'  data-id='" + datos[i].cod_pedido +
      "' data-name='" + datos[i].nick +
      "'><i class='fa fa-trash'></i> Borrar</a>";

    activo = "";
    tipo = "success";
    if (datos[i].estado == "Totalmente en Albaran") {
      activo = "disabled";
      tipo = "secondary";
    }

    let botonAlbaran = "<a class='btn btn-" + tipo + " " + activo + "' href='index.php?accion=crear&tabla=albaranes&idPedido=" + datos[i].cod_pedido + "'> <i class='fas fa-plus'></i> Crear albarán</a>";
    tabla +=
      "<tr data-fila='" + datos[i].cod_pedido + "'>" +
      "<td scope='col'><a class='ampliarTabla success btn " + "'  data-id='" + datos[i].cod_pedido + "'><i class='fa fa-plus fa-xl'></i></a></td> " +
      "<td>" + datos[i].cod_pedido + "</td>" +
      "<td>" + datos[i].cod_cliente + "</td>" +
      "<td>" + datos[i].fecha + "</td>" +
      "<td>" + datos[i].estado + "</td>" +
      "<td>" + botonEliminar + "</td>" +
      "<td><a class='btn btn-success " + activo + "' href='index.php?accion=editar&tabla=pedidos&idPedido=" +  datos[i].cod_pedido + "'> <i class='fas  fa-paint-brush'></i> Modificar</a></td>" +
      "<td>" + botonAlbaran + "</td>" +
      "</tr>";


    tabla +=
      "<tr hidden data-cod_pedido=" + datos[i].cod_pedido + "><td colspan='8'>" +
      " <table  data-fila='" + datos[i].cod_pedido + "' id='datos' class='table table-striped table-dark text-center'>" +
      "<thead class='table-dark'>" +
      "<tr>" +
      "<th scope='col'></th>" +
      "<th scope='col'>Num Linea</th>" +
      "<th scope='col'>Código pedido</th>" +
      "<th scope='col'>Código artículo</th>" +
      "<th scope='col'>Nombre artículo</th>" +
      "<th scope='col'>Precio</th>" +
      "<th scope='col'>Cantidad</th>" +
      "<th scope='col'>Cantidad en Alb</th>" +
      "<th scope='col'>Estado</th>" +
      "<th>Borrar</th>" +
      "</td></tr>" +
      "</thead>" +
      "<tbody>";

    for (let x = 0; x < datos[i].pedidos.length; x++) {

      let activo = "";
      let tipo = "danger";
      if (datos[i].pedidos[x].estado != "Sin Albaran") {
        activo = "disabled";
        tipo = "secondary"
      }
      let botonEliminar = "<a class='borrarLinea btn btn-" + tipo + " " +
        activo + "'  data-id='" + datos[i].pedidos[x].num_linea_pedido + "'  data-fila='" + datos[i].cod_pedido + "'data-name='" + datos[i].pedidos[x].nombre
        + "'<i class='fa fa-trash'></i> Borrar</a>";

      tabla += "<tr data-fila='" + datos[i].cod_pedido + "' data-linea='" + datos[i].pedidos[x].num_linea_pedido + "'>" +
        `<td> <img class='img-fluid rounded' style="width:100px; height:100px;"src='views/articulos/img/${datos[i].pedidos[x].cod_articulo}.png'></td>` +
        "<td>" + datos[i].pedidos[x].num_linea_pedido + "</td>" +
        "<td>" + datos[i].cod_pedido + "</td>" +
        "<td>" + datos[i].pedidos[x].cod_articulo + "</td>" +
        "<td>" + datos[i].pedidos[x].nombre + "</td>" +
        "<td>" + datos[i].pedidos[x].precio + "</td>" +
        "<td>" + datos[i].pedidos[x].cantidad + "</td>" +
        "<td>" + datos[i].pedidos[x].cantidadenalbaran + "</td>" +
        "<td>" + datos[i].pedidos[x].estado + "</td>" +
        "<td>" + botonEliminar + "</td>" +
        "<tr>";

    }
    tabla += "</tbody> </table>";


  }
  tabla += "</tbody></table>";
  contenido += tabla;
  contenidoTabla.innerHTML = contenido;
  td = document.getElementsByTagName('td');
    for (let i = 0; i < td.length; i++) {
        td[i].className = 'align-middle';
    }
  ampliarTabla();
}


function eliminarPedido() {
  //boton que lo llama y que valor tiene en el campo data-id
  let valorEliminar = this.dataset.id;
  //let nombreCliente = this.dataset.name;

  Swal.fire({
    title: 'Estas seguro que quieres borrar el pedido: ' + valorEliminar + '?',
    showDenyButton: true,
    confirmButtonText: 'Borrar',
    denyButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isDenied) {
      Swal.fire('No se borrará el pedido ', '', 'info')
      return false;
    }
    else if (result.isConfirmed) {
      const datos = new FormData();
      datos.append("idPedido", valorEliminar);
      const myInit = {
        method: "POST", //GET POST PUT DELETE etc..
        mode: "cors",
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        body: datos,
      };
      let peticion = new Request("views/pedidos/delete.php", myInit);

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
            //Seleccionamos la tabla
            let collection = document.querySelectorAll('tr[data-fila="' + valorEliminar + '"]');
            //Seleccionamos el padre de las filas de pedidos (esto nos servirá para borrar la tabla entera después)
            padre = collection[0].parentNode;
            
            //Borramos todas las filas que tengan que ver con el pedido (lineas incluidas)
            for (let i = 0; i < collection.length; i++) {
              collection[i].remove();
            }
            //borramos la tabla de las lineas
            collection = document.querySelector('tr[data-cod_pedido="' + valorEliminar + '"]');
            collection.remove();


            //Si no hay más pedidos borramos la tabla entera
            if (padre.querySelectorAll('tr[data-fila').length == 0) {
              divDatos = document.getElementById("datos");
              divDatos.innerHTML = "";
            }


            Toast.fire({
              icon: 'success',
              title: 'Borrado ejecutado con exito.',
              text: 'Se ha podido borrar el pedido ' + valorEliminar,
            });
          }

        })
        .catch(function (error) {
          console.log(error);
        });
    }
  });
}

function eliminarLinea() {
  //boton que lo llama y que valor tiene en el campo data-id
  let valorEliminar = this.dataset.id;
  let nombreArticulo = this.dataset.name;
  let valorPedido = this.dataset.fila;

  Swal.fire({
    title: 'Estas seguro que quieres borrar la linea: ' + valorEliminar + ' - ' + nombreArticulo + '?',
    showDenyButton: true,
    confirmButtonText: 'Borrar',
    denyButtonText: 'Cancelar',
  }).then((result) => {

    if (result.isDenied) {
      Swal.fire('No se borrará la línea ', '', 'info')
      return false;
    }
    else if (result.isConfirmed) {
      const datos = new FormData();
      datos.append("idLinea", valorEliminar);

      const myInit = {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      };
      let peticion = new Request("views/pedidos/delete.php", myInit);


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
              title: 'No se ha podido borrar.',
              text: 'No se ha podido borrar ' + valorEliminar + " " + nombreArticulo,
            });
          }
          else {
            let elemento = document.querySelector('tr[data-linea="' + valorEliminar + '"]');
            elemento.parentNode.removeChild(elemento);

            //En el caso de que sea la última línea del pedido borramos el pedido completo.
            dataFila = document.querySelectorAll('tr[data-fila="' + valorPedido + '"]');
            
            if (dataFila.length == 1) {
              dataPedido = document.querySelector('tr[data-cod_pedido="' + valorPedido + '"]');
              padre = dataPedido.parentNode;
              dataFila[0].parentNode.removeChild(dataFila[0]);
              dataPedido.parentNode.removeChild(dataPedido);

              //Buscamos si hay más pedidos
              //Si no hay más pedidos borramos la tabla entera
              if(padre.querySelectorAll('tr[data-fila').length == 0){
                divDatos = document.getElementById("datos");
                divDatos.innerHTML = "";
              }
            }

            Toast.fire({
              icon: 'success',
              title: 'Borrado Ejecutada con exito.',
              text: 'Se ha podido borrar el artículo ' + valorEliminar + " " + nombreArticulo,
            });
          }

        })
        .catch(function (error) {
          console.log(error);
        });
    }
  });
}

function ampliarTabla() {
  // Obtén todos los elementos con la clase 'ampliarTabla'
  const toggleBtns = document.querySelectorAll('.ampliarTabla');

  // Recorre todos los elementos y agrega el evento 'click' a cada uno
  toggleBtns.forEach(function (toggleBtn) {
    const toggleIcon = toggleBtn.querySelector('i');

    toggleBtn.addEventListener('click', function (fila) {
      const button = fila.currentTarget;
      id = button.getAttribute('data-id')
      tabla = document.querySelector(`[data-cod_pedido="${id}"]`);
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