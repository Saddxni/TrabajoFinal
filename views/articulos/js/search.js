$(function () {
    $("#f_buscar").on("click", '.filtrar', filtrar);
    $("#datos").on("click", '.borrar', cambiarEstado);
  });
  
  
  function filtrar(e) {
    
    e.preventDefault();
    let datos = new FormData();
    let url = "views/articulos/filtrarAjax.php?ajax=true&evento=todos";
  
    if (this.id == "filtrar") {
      const fBuscar = document.getElementById("f_buscar");
      datos = new FormData(fBuscar);
      url = "views/articulos/filtrarAjax.php?&evento=filtrar";
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
    let tabla = "</br>" +
    "<table id='datos' class='table table text-center'>" +
      "<thead class='table-dark'>" +
      "<tr>" +
      "<th scope='col'></th>" +
      "<th scope='col'>Código articulo</th>" +
      "<th scope='col'>Nombre</th>" +
      "<th scope='col'>Descripción </th>" +
      "<th scope='col'>Precio </th>" +
      "<th scope='col'>Descuento </th>" +
      "<th scope='col'>% IVA </th>" +
      "<th scope='col'>Disponibilidad </th>" +
      "<th></th><th></th>" +
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
        activo + "'  data-id='" + datos[i].cod_articulo +
        "' data-name='" + datos[i].nombre + "'data-contenido='" + contenido + "'data-estado='" + datos[i].disponibilidad +
        "'><i class='" + clase + "'></i> " + contenido +"</a>";
  
      tabla += "<tr data-fila='" + datos[i].cod_articulo + "'>" +
      `<td> <img class='img-fluid rounded' style="width:100px; height:100px;"src='views/articulos/img/${datos[i].cod_articulo}.png'></td>` +
        "<td>" + datos[i].cod_articulo + "</td>" +
        "<td>" + datos[i].nombre + "</td>" +
        "<td>" + datos[i].descripcion + "</td>" +
        "<td>" + datos[i].precio + "</td>" +
        "<td>" + datos[i].descuento + "</td>" +
        "<td>" + datos[i].iva + "</td>" +
        "<td>" + datos[i].disponibilidad + "</td>" +
        
        "<td>" + botonEliminar + "</td>" +
        "<td><a class='btn btn-success' href='index.php?accion=editar&tabla=articulos&id=" + datos[i].cod_articulo + "'> <i class='fas  fa-paint-brush'></i> Editar</a></td>" +
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
    let nombreArticulo = this.dataset.name;
    let content = this.dataset.contenido;
    let estado = this.dataset.estado;
    Swal.fire({
      title: 'Estas seguro que quieres cambiar el estado del articulo: ' + valorEliminar + ' - ' + nombreArticulo +  '?',
      showDenyButton: true,
      confirmButtonText: content,
      denyButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isDenied) {
        Swal.fire('No se modificará el estado del articulo', '', 'info')
        return false;
      }
      else if (result.isConfirmed) {
        const datos = new FormData();
        datos.append("id", valorEliminar);
        datos.append("estado", estado);
  
        const myInit = {
          method: "POST", 
          mode: "cors",
          cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
          body: datos,
        };
        let peticion = new Request("views/articulos/altaBaja.php", myInit);
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
                title: 'No se ha podido camnbiar el estado.',
                text: 'No se ha podido cambiar el estado de ' + valorEliminar + " " + nombreArticulo,
              });
            }
            else {
              let elemento = document.querySelector('tr[data-fila="' + valorEliminar + '"]').childNodes;
              if(elemento[7].innerHTML == "Disponible"){
                elemento[7].innerHTML = "No disponible";
                elemento[8].innerHTML = `<a class='borrar btn btn-success' data-id='${elemento[1].innerHTML}' data-name='${elemento[2].innerHTML}' data-contenido='Dar de alta' data-estado='No disponible'><i class='fa fa-arrow-up'></i> Dar de alta</a>`;
              }else{
                elemento[7].innerHTML = "Disponible";
                elemento[8].innerHTML = `<a class="borrar btn btn-danger " data-id="${elemento[1].innerHTML}" data-name="${elemento[2].innerHTML}" data-contenido="Dar de baja" data-estado="Disponible"><i class="fa fa-arrow-down"></i> Dar de baja</a>`;
              }
              Toast.fire({
                icon: 'success',
                title: 'Cambio de estado ejecutado con éxito.',
                text: 'Se ha podido cambiar el estado del artículo ' + valorEliminar + " - " + nombreArticulo,
              });
            }
  
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    });
  }