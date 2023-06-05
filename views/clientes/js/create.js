let botonInsertar = document.getElementById("insertar");
campos = ["cif_dni", "nick", "contrasenya", "razon_social", "domicilio_social", "ciudad", "email", "telefono"];
botonInsertar.onclick = function(e) {
  e.preventDefault();
  const formInsercion = document.getElementById("f_insercion");
  const datos = new FormData(formInsercion);
    

  const myInit = {
    method: "POST",
    mode: "cors",
    cache: "no-cache", 
    body: datos,
  };
  let peticion = new Request("views/clientes/store.php?evento=crear", myInit);

  fetch(peticion)
    .then((resp) => resp.json())
    .then(function(datos) {
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
            dibujarErrores(datos.errores, campos);
            Toast.fire({
                icon: 'success',
                title: 'Inserción ejecutada con éxito.',
                text: 'El cliente se ha añadido a la base de datos',
            });
            limpiaInput = document.getElementsByClassName('form-control');
            for (let i = 0; i < limpiaInput.length; i++) {
                limpiaInput[i].value = "";
                
            }
        }
    })
    .catch(
        function (error) {
            console.log(error);
        })
};

function dibujarErrores(errores, campos) {
    console.log(errores);
    for (const campo of campos) {
        console.log(campo);
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
