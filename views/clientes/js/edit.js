let b_guardar = document.getElementById("b_guardar");
campos = ["nick", "contrasenya", "razon_social", "domicilio_social", "ciudad", "email", "telefono"];

window.addEventListener('load', function() {
    var camposInput = document.getElementsByClassName('form-control');
    b_guardar.disabled = true;
    console.log(camposInput);

    var validar = function(e) {
      if(e.target.value != e.target.oldvalue){
        b_guardar.disabled = false;
        return;
      }
    };
  
    for (var i = 0; i < camposInput.length; i++) {
        camposInput[i].addEventListener('input', validar);
      }
    
    
  });

b_guardar.onclick = function(e) {
  e.preventDefault();
  
  const formInsercion = document.getElementById("f_actualizar");
  const datos = new FormData(formInsercion);

  const myInit = {
    method: "POST",
    mode: "cors",
    cache: "no-cache", 
    body: datos,
  };
  let peticion = new Request("views/clientes/store.php?evento=editar", myInit);

  fetch(peticion)
    .then((resp) => resp.json())
    .then(function(datos) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        if (datos.ok==false){
            Toast.fire({
                icon: 'error',
                title: 'No se ha podido editar el cliente',
                text: 'Revisa los errores que aparecerán debajo de los campos..'
                });
            
                dibujarErrores(datos.errores, campos);
        }
        else{
            dibujarErrores(datos.errores, campos);
            //modificamos el cliente viejo con el nuevo
            cliente_viejo = document.getElementById("old_nick");
            cliente_nuevo = document.getElementById("nick");
            cliente_viejo.value = cliente_nuevo.value;
            Toast.fire({
                icon: 'success',
                title: 'Inserción ejecutada con éxito.',
                text: 'El cliente se ha modificado',
                });
            }
    })
    .catch(function(error) {
        console.log(error);
    });
};

function dibujarErrores(errores, campos) {
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