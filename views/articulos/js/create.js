let botonInsertar = document.getElementById("insertar");
var campos = ["nombre", "descripcion", "precio", "descuento", "iva", "imagen"];

botonInsertar.onclick = function (e) {
    e.preventDefault();

    validacion = validarFormulario();
    if (validacion == false) {
        const formInsercion = document.getElementById("f_insercion");
        const datos = new FormData(formInsercion);

        const myInit = {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            body: datos,
        };
        let peticion = new Request("views/articulos/store.php?evento=crear", myInit);
        fetch(peticion)
            .then((resp) => resp.json())
            .then(function (datos) {
                console.log(datos);
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
                        text: 'El artículo se ha añadido a la base de datos',
                    });
                    limpiaInput = document.getElementsByClassName('form-control');
                    for (let i = 0; i < limpiaInput.length; i++) {
                        limpiaInput[i].value = "";
                    }
                    campoImange = document.getElementById('contenedorImagen');
                    contenedorImagen.hidden = true;
                }
            })
            .catch(
                function (error) {
                    console.log(error);
                })
    }
}

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

function validarFormulario() {
    let nombre = document.getElementById("nombre").value;
    let descripcion = document.getElementById("descripcion").value;
    let precio = document.getElementById("precio").value;
    let descuento = document.getElementById("descuento").value;
    let iva = document.getElementById("iva").value;
    let imagen = document.getElementById("imagen").value;

    let error = false;
    let errores = [];

    if (nombre.trim() == "") {
        error = true;
        errores["nombre"] = "El campo nombre es obligatorio";
    }

    if (descripcion.trim() == "") {
        error = true;
        errores["descripcion"] = "El campo descripción es obligatorio";
    }

    if (precio.trim() == "" || isNaN(parseFloat(precio)) || parseFloat(precio) <= 0) {
        error = true;
        errores["precio"] = "El campo precio debe ser un número positivo";
    }

    if (descuento.trim() == "" || isNaN(parseFloat(descuento)) || parseFloat(descuento) < 0 || parseFloat(descuento) > 100) {
        error = true;
        errores["descuento"] = "El campo descuento debe ser un número positivo entre 0 y 100";
    }

    if (iva.trim() == "" || isNaN(parseFloat(iva)) || parseFloat(iva) < 0 || parseFloat(iva) > 100) {
        error = true;
        errores["iva"] = "El campo IVA debe ser un número positivo entre 0 y 100";
    }

    if (imagen.trim() != "") {
        let extension = imagen.substring(imagen.lastIndexOf('.') + 1).toLowerCase();
        if (extension != "png" && extension != "jpg") {
            error = true;
            errores["imagen"] = "La imagen debe ser de tipo png o jpg";
        }
    } else {
        error = true;
        errores["imagen"] = "No ha adjuntado ninguna imagen";
    }


    dibujarErrores(errores, campos);

    return error;
}

//Previsualización de la imagen al crear el artículo
const input = document.getElementById('imagen');
const preview = document.getElementById('preview');
const contenedorImagen = document.getElementById('contenedorImagen');


input.addEventListener('change', () => {
    const file = input.files[0];
    const reader = new FileReader();

    reader.addEventListener('load', () => {
        preview.src = reader.result;
        contenedorImagen.removeAttribute('hidden'); // Muestra el contenedor cuando se ha cargado la imagen
    });

    reader.readAsDataURL(file);
});

