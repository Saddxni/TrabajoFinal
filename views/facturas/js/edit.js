let botonInsertar = document.getElementById("modificar");
let descuento_factura = document.getElementById('descuento_factura');
let descuentoAntiguo = document.getElementById('descuentoAntiguo').value;
let cod_factura = document.getElementById('cod_factura').value;
botonInsertar.disabled = true;
descuento_factura.addEventListener('input', validar);

//Activación y desactivación del botón cuando esté todo correcto

function validar(e){
    descuentoNuevo = e.target.value;
    console.log(descuentoNuevo);
    console.log(descuentoAntiguo);
    botonInsertar.disabled = true;
    if((Number(descuentoNuevo) <= 100 && Number(descuentoNuevo) >= 0) && descuentoNuevo != descuentoAntiguo){
        botonInsertar.disabled = false;
    }
}

botonInsertar.onclick = function (e) {
    e.preventDefault();

    formulario = document.getElementById('formulario');
        let datos = new FormData(formulario);

        url = "views/facturas/store.php?evento=editar";
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
    }