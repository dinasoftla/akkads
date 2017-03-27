<?php

// numpedido
// codcliente
// fechaultimamod
// usuarioultimamod
// fecha
// descuento
// estado
// transporte

?>
<?php if ($SIS_PEDIDO->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $SIS_PEDIDO->TableCaption() ?></h4> -->
<table id="tbl_SIS_PEDIDOmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $SIS_PEDIDO->TableCustomInnerHtml ?>
	<tbody>
<?php if ($SIS_PEDIDO->numpedido->Visible) { // numpedido ?>
		<tr id="r_numpedido">
			<td><?php echo $SIS_PEDIDO->numpedido->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->numpedido->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_numpedido">
<span<?php echo $SIS_PEDIDO->numpedido->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->numpedido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->codcliente->Visible) { // codcliente ?>
		<tr id="r_codcliente">
			<td><?php echo $SIS_PEDIDO->codcliente->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->codcliente->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_codcliente">
<span<?php echo $SIS_PEDIDO->codcliente->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->codcliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->fechaultimamod->Visible) { // fechaultimamod ?>
		<tr id="r_fechaultimamod">
			<td><?php echo $SIS_PEDIDO->fechaultimamod->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->fechaultimamod->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_fechaultimamod">
<span<?php echo $SIS_PEDIDO->fechaultimamod->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->fechaultimamod->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<tr id="r_usuarioultimamod">
			<td><?php echo $SIS_PEDIDO->usuarioultimamod->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->usuarioultimamod->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_usuarioultimamod">
<span<?php echo $SIS_PEDIDO->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td><?php echo $SIS_PEDIDO->fecha->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->fecha->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_fecha">
<span<?php echo $SIS_PEDIDO->fecha->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->descuento->Visible) { // descuento ?>
		<tr id="r_descuento">
			<td><?php echo $SIS_PEDIDO->descuento->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->descuento->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_descuento">
<span<?php echo $SIS_PEDIDO->descuento->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->descuento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $SIS_PEDIDO->estado->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->estado->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_estado">
<span<?php echo $SIS_PEDIDO->estado->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_PEDIDO->transporte->Visible) { // transporte ?>
		<tr id="r_transporte">
			<td><?php echo $SIS_PEDIDO->transporte->FldCaption() ?></td>
			<td<?php echo $SIS_PEDIDO->transporte->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_transporte">
<span<?php echo $SIS_PEDIDO->transporte->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->transporte->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
