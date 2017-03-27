<?php

// numfactura
// fecha
// codcliente
// fecultimamod
// usuarioultimamod
// descuento
// estado
// numpedido
// transporte

?>
<?php if ($SIS_FACTURA->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $SIS_FACTURA->TableCaption() ?></h4> -->
<table id="tbl_SIS_FACTURAmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $SIS_FACTURA->TableCustomInnerHtml ?>
	<tbody>
<?php if ($SIS_FACTURA->numfactura->Visible) { // numfactura ?>
		<tr id="r_numfactura">
			<td><?php echo $SIS_FACTURA->numfactura->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->numfactura->CellAttributes() ?>>
<span id="el_SIS_FACTURA_numfactura">
<span<?php echo $SIS_FACTURA->numfactura->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->numfactura->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td><?php echo $SIS_FACTURA->fecha->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->fecha->CellAttributes() ?>>
<span id="el_SIS_FACTURA_fecha">
<span<?php echo $SIS_FACTURA->fecha->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->codcliente->Visible) { // codcliente ?>
		<tr id="r_codcliente">
			<td><?php echo $SIS_FACTURA->codcliente->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->codcliente->CellAttributes() ?>>
<span id="el_SIS_FACTURA_codcliente">
<span<?php echo $SIS_FACTURA->codcliente->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->codcliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->fecultimamod->Visible) { // fecultimamod ?>
		<tr id="r_fecultimamod">
			<td><?php echo $SIS_FACTURA->fecultimamod->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->fecultimamod->CellAttributes() ?>>
<span id="el_SIS_FACTURA_fecultimamod">
<span<?php echo $SIS_FACTURA->fecultimamod->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->fecultimamod->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<tr id="r_usuarioultimamod">
			<td><?php echo $SIS_FACTURA->usuarioultimamod->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->usuarioultimamod->CellAttributes() ?>>
<span id="el_SIS_FACTURA_usuarioultimamod">
<span<?php echo $SIS_FACTURA->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->descuento->Visible) { // descuento ?>
		<tr id="r_descuento">
			<td><?php echo $SIS_FACTURA->descuento->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->descuento->CellAttributes() ?>>
<span id="el_SIS_FACTURA_descuento">
<span<?php echo $SIS_FACTURA->descuento->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->descuento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $SIS_FACTURA->estado->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->estado->CellAttributes() ?>>
<span id="el_SIS_FACTURA_estado">
<span<?php echo $SIS_FACTURA->estado->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->numpedido->Visible) { // numpedido ?>
		<tr id="r_numpedido">
			<td><?php echo $SIS_FACTURA->numpedido->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->numpedido->CellAttributes() ?>>
<span id="el_SIS_FACTURA_numpedido">
<span<?php echo $SIS_FACTURA->numpedido->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->numpedido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($SIS_FACTURA->transporte->Visible) { // transporte ?>
		<tr id="r_transporte">
			<td><?php echo $SIS_FACTURA->transporte->FldCaption() ?></td>
			<td<?php echo $SIS_FACTURA->transporte->CellAttributes() ?>>
<span id="el_SIS_FACTURA_transporte">
<span<?php echo $SIS_FACTURA->transporte->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->transporte->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
