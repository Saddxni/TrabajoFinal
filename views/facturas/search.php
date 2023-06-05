<form id="f_buscar">
  <div class="form-group">
    <select class="form-control" name="campo" id="campo">
      <option value="num_linea_albaran">Número linea albaran</option>
      <option value="cod_albaran">Código albaran</option>
      <option value="cod_pedido">Código pedido</option>
      <option value="num_linea_pedido">Número línea pedido</option>
      <option value="cod_articulo">Código articulo</option>
      <option value="cod_cliente">Código cliente</option>
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
<div id="datos">

</div>

<style>
  #centrar{
    vertical-align: middle;
  }
</style>

