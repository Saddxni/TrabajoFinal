<?php
require_once("assets/php/tcpdf/tcpdf.php");
require_once "controllers/facturasController.php";
require_once "controllers/clientesController.php";
$cod_factura = $_REQUEST["cod_factura"];
$controlador = new FacturasController();
$factura = $controlador->buscar("facturas", "cod_factura", "igual", $cod_factura);
$factura = $factura[0];
$cod_cliente = $factura["cod_cliente"];
$controladorCliente = new ClientesController();
$cliente = $controladorCliente->buscar("cod_cliente", "igual", $cod_cliente);
$cliente = $cliente[0];

$lineaDescuento = 0;
$lineaIva = 0;
$descuentoLinea = 0;

$descuentoTotal = 0;
$baseImponible = 0;
$totalIva = 0;

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// Datos del creador
// //que evidentemente no SOY YO
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Daniel García');
$pdf->SetTitle("Factura_".$cod_factura);
$pdf->SetSubject("Factura");
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Fuente por defecto de tipo monospaced 
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Margenes por defecto
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Cuando por ejemplo una tabla no cabe, que pasa
// // en este caso creo una nueva página
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Como esclar las fotos
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// Fuente por defecto, antes es en caso de fuente mono
$pdf->SetFont('dejavusans', '', 10);

$pdf->AddPage();
$pdf->resetColumns();
$pdf->setEqualColumns(2, 100);
$imagen = '
<div>
  <img src="assets/img/adv-logo.png" alt="Logo" width="400" height="300">
</div>';

$pdf->writeHTML($imagen);

$proveedor = '  
<div style="text-align:right;">
<p>Número de Factura: ' . $factura["cod_factura"] . '</p>
<h3 style="font-size:14px;">Proveedor</h3>
<p style="font-weight:bold;">Adventure Time S.L</p>
<p style="font-weight:bold;">Calle arbol de tierra de Ooo</p>
<p style="font-weight:bold;">S8131025B</p>
</div>';
$pdf->selectColumn(1);
$pdf->writeHTML($proveedor);

$pdf->resetColumns();
$html = '
    <h3 style="font-size:14px;">Cliente</h3>
    <p style="font-weight:bold;">' . $cliente["razon_social"] . '</p>
    <p style="font-weight:bold;">' . $cliente["domicilio_social"] . '</p>
    <p style="font-weight:bold;">' . $cliente["cif_dni"] . '</p>
   
<style>
.primeraLinea th {
  border-bottom: 1px solid black;
  font-weight:bold;
}
</style>
<table style="text-align:center;">
  <tr class="primeraLinea">
    <th  colspan="2">Nombre</th>
    <th>Precio</th>
    <th>Cantidad</th>
    <th>IVA</th>
    <th>Descuento</th>
    <th>Total</th>
  </tr>';
  foreach ($factura["lineaFactura"] as $lineaFactura) {
    //Total de la línea sin descuento
    $totalLinea = $lineaFactura["precio"] * $lineaFactura["cantidad"];
    //Descuento de la línea 
    $descuentoLinea += $lineaFactura["descuento"] * $totalLinea / 100;
    //Total del IVA
    $lineaIva = ($totalLinea - $descuentoLinea) * $lineaFactura["iva"] / 100;

    $baseImponible += $totalLinea;
    $descuentoTotal += $descuentoLinea;
    $totalIva += $lineaIva;
    $fila = '<tr>
      <td colspan="2">'. $lineaFactura["nombre"] . '</td>
      <td>' . $lineaFactura["precio"] . '</td>
      <td>' . $lineaFactura["cantidad"] . '</td>
      <td>' . $lineaFactura["iva"] . '%</td> 
      <td>' . $lineaFactura["descuento"] . '%</td>
      <td>' .$totalLinea . '€</td>
    </tr>';
    $html.= $fila;
  }
  
  $html .= '
  <tr>
    <td colspan="7"></td>
  </tr>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>Subtotal</th>
    <th>' . $baseImponible . '€</th>
  </tr>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>Descuento</th>
    <th>' . $descuentoTotal . '€</th>
  </tr>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>IVA</th>
    <th>' . $totalIva . '€</th>
  </tr>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>TOTAL</th>
    <th>' . $baseImponible - $descuentoTotal + $totalIva . '€</th>
  </tr>
</table>';
// escribo pero todavía sin imprimir
$pdf->writeHTML($html, true, false, true, false, '');
// Colocamos el puntero al final
$pdf->lastPage();

//Finalmente imprimos el PDF
ob_end_clean();
$pdf->Output("Factura_" . $cod_factura . ".pdf", 'I');
