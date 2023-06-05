let b_guardar = document.getElementById("b_guardar");


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
  let peticion = new Request("views/articulos/store.php?evento=editar", myInit);

  fetch(peticion)
    .then((resp) => resp.json())
    .then(function(datos) {
        console.log(datos);

        let capasErrores=document.querySelectorAll(".errores");
        capasErrores.forEach(function(capa) {
            capa.innerHTML="";
            capa.classList.remove("visible");
            capa.classList.add("invisible");
          });

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        if (datos.ok==false){
            console.log(datos.errores);
            $.each(datos.errores, function(index, errores) {
                          
                let capaError=document.getElementById("e_"+index);
                let mensaje="";
                for  (let i=0;i<errores.length;i++){
                    mensaje+= errores[i];
                }
                capaError.innerHTML=mensaje;
                capaError.classList.remove("invisible");
                capaError.classList.add("visible");
            });

            
            Toast.fire({
                icon: 'error',
                title: 'No se ha podido insertar.',
                text: 'Montar sistema de Mensajes largo esto solo es una cadena'
                });
            
        }
        else{
            document.getElementById("cod_articulo").value=document.getElementById("cod_articulo").value
            Toast.fire({
                icon: 'success',
                title: 'Insercion Ejecutada con Éxito.',
                text: 'Mensaje largo',
                });
            }
    })
    .catch(function(error) {
        console.log(error);
    });
};

//Previsualización de la imagen al crear el artículo

const input = document.getElementById('imagen');
const preview = document.getElementById('preview');
const contenedorImagen = document.getElementById('contenedorImagen');


input.addEventListener('change', () => {
    const file = input.files[0];
    const reader = new FileReader();

    reader.addEventListener('load', () => {
        preview.src = reader.result;
    });

    reader.readAsDataURL(file);
});
