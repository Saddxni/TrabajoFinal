<form id="f_buscar">
  <div class="form-group">
    <select class="form-control" name="campo" id="campo">
      <option value="cod_cliente">Codigo cliente</option>
      <option value="cif_dni">DNI</option>
      <option value="razon_social">Razon social</option>
      <option value="domicilio_social">Domicilio social</option>
      <option value="ciudad">Ciudad</option>
      <option value="email">Email</option>
      <option value="telefono">Telefono</option>
      <option value="nick">Nick</option>
      <option value="contraseña">Contraseña</option>
    </select>
    <select class="form-control" name="metodoBusqueda" id="metodoBusqueda">
      <option value="empieza"> Empieza Por</option>
      <option value="acaba"> Acaba En </option>
      <option value="contiene"> Contiene </option>
      <option value="igual"> Es Igual A</option>

    </select>
  </div>
  <input type="text" class="form-control" id="busqueda" name="busqueda" value="" placeholder="texto a Buscar">
  </br>
  <button id="filtrar" type="button" class="btn btn-success filtrar" name="filtrar">Buscar</button>
  <button id="todos" type="button" name="todos" class="btn btn-info filtrar" name="Todos">Ver todos</button>

</form>
<div id="datos"></div>
