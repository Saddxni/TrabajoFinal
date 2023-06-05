$(function () {
  $("#f_buscar").on("click", '.filtrar', filtrar);
  $("#datos").on("click", '.borrar', cambiarEstado);
});


function filtrar(e) { 

  e.preventDefault();
  let datos = new FormData();
  let url = "views/clientes/filtrarAjax.php?evento=todos";

  if (this.id == "filtrar") {
    const fBuscar = document.getElementById("f_buscar");
    datos = new FormData(fBuscar);
    url = "views/clientes/filtrarAjax.php?evento=filtrar";
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
  let tabla = "</br>"+
  "<table id='datos' class='table table table-hover text-center'>" +
    "<thead class='table-dark'>" +
    "<tr>" +
    "<th scope='col'>Código cliente</th>" +
    "<th scope='col'>DNI</th>" +
    "<th scope='col'>Nick </th>" +
    "<th scope='col'>Contraseña </th>" +
    "<th scope='col'>Razón social </th>" +
    "<th scope='col'>Domicilio social </th>" +
    "<th scope='col'>Ciudad </th>" +
    "<th scope='col'>Email </th>" +
    "<th scope='col'>Telefono </th>" +
    "<th scope='col'>Disponibilidad </th>" +

    "<th>Estado</th><th>Editar</th>" +
    "</tr>" +
    "</thead>" +
    "<tbody>";

    for (let i = 0; i < datos.length; i++) {
      let activo = "";
      let tipo = "danger";
      let contenido = "Dar de baja";
      let clase = "fa fa-arrow-down";
      if (datos[i].disponibilidad == "No disponible") {
        tipo = "success"
        contenido = "Dar de alta";
        clase = "fa fa-arrow-up";
      }
    let botonEliminar = "<a class='borrar btn btn-" + tipo + " " +
      activo + "'  data-id='" + datos[i].cod_cliente +
      "' data-name='" + datos[i].nick + "'data-contenido='" + contenido + "'data-estado='" + datos[i].disponibilidad +
        "'><i class='" + clase + "'></i> " + contenido +"</a>";

    tabla += "<tr data-fila='" + datos[i].cod_cliente + "'>" +
      "<td>" + datos[i].cod_cliente + "</td>" +
      "<td>" + datos[i].cif_dni + "</td>" +
      "<td>" + datos[i].nick + "</td>" +
      "<td>" + datos[i].contraseña + "</td>" +
      "<td>" + datos[i].razon_social + "</td>" +
      "<td>" + datos[i].domicilio_social + "</td>" +
      "<td>" + datos[i].ciudad + "</td>" +
      "<td>" + datos[i].email + "</td>" +
      "<td>" + datos[i].telefono + "</td>" +
      "<td>" + datos[i].disponibilidad + "</td>" +
      "<td>" + botonEliminar + "</td>" +
      "<td><a class='btn btn-success' href='index.php?accion=editar&tabla=clientes&id=" + datos[i].cod_cliente + "'> <i class='fas  fa-paint-brush'></i> Editar</a></td>" +
      "<tr>";
  }
  tabla += "</tbody> </table>";
  contenidoTabla.innerHTML = tabla;
  td = document.getElementsByTagName('td');
    for (let i = 0; i < td.length; i++) {
        td[i].className = 'align-middle';
    }
}

function cambiarEstado() {
  //boton que lo llama y que valor tiene en el campo data-id
  let valorEliminar = this.dataset.id;
  let nombreCliente = this.dataset.name;
  let content = this.dataset.contenido;
  let estado = this.dataset.estado;
  Swal.fire({
    title: 'Estas seguro que quieres cambiar el estado del cliente: ' + valorEliminar + ' - ' + nombreCliente +  '?',
    showDenyButton: true,
    confirmButtonText: content,
    denyButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isDenied) {
      Swal.fire('No se modificará el estado del cliente', '', 'info')
      return false;
    }
    else if (result.isConfirmed) {
      const datos = new FormData();
      datos.append("id", valorEliminar);
      datos.append("estado", estado);

      const myInit = {
        method: "POST", //GET POST PUT DELETE etc..
        mode: "cors",
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        body: datos,
      };
      let peticion = new Request("views/clientes/altaBaja.php", myInit);

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
              title: 'No se ha podido cambiar el estado.',
              text: 'No se ha podido cambiar el estado de ' + valorEliminar + " " + nombreCliente,
            });
          }
          else {
            console.log("Datos modificados con exito");
            let elemento = document.querySelector('tr[data-fila="' + valorEliminar + '"]').childNodes;
            console.log(elemento[9].innerHTML);

            if(elemento[9].innerHTML == "Disponible"){
              elemento[9].innerHTML = "No disponible";
              elemento[10].innerHTML = `<a class="borrar btn btn-success " data-id="${elemento[0].innerHTML}" data-name="${elemento[2].innerHTML}" data-contenido="Dar de alta" data-estado="No disponible"><i class="fa fa-arrow-up"></i> Dar de alta</a>`;
            }else{
              elemento[9].innerHTML = "Disponible";
              elemento[10].innerHTML = `<a class="borrar btn btn-danger " data-id="${elemento[0].innerHTML}" data-name="${elemento[2].innerHTML}" data-contenido="Dar de baja" data-estado="Disponible"><i class="fa fa-arrow-down"></i> Dar de baja</a>`;
            }
            
            //console.log(elemento.childNodes);
            Toast.fire({
              icon: 'success',
              title: 'Cambio de estado ejecutado con éxito.',
              text: 'Se ha podido cambiar el estado del cliente ' + valorEliminar + " - " + nombreCliente,
            });
          }

        })
        .catch(function (error) {
          console.log(error);
        });
    }
  });
}