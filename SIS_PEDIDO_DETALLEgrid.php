<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php

// Create page object
if (!isset($SIS_PEDIDO_DETALLE_grid)) $SIS_PEDIDO_DETALLE_grid = new cSIS_PEDIDO_DETALLE_grid();

// Page init
$SIS_PEDIDO_DETALLE_grid->Page_Init();

// Page main
$SIS_PEDIDO_DETALLE_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_PEDIDO_DETALLE_grid->Page_Render();
?>
<?php if ($SIS_PEDIDO_DETALLE->Export == "") { ?>
<script type="text/javascript">

// Form object
var fSIS_PEDIDO_DETALLEgrid = new ew_Form("fSIS_PEDIDO_DETALLEgrid", "grid");
fSIS_PEDIDO_DETALLEgrid.FormKeyCountName = '<?php echo $SIS_PEDIDO_DETALLE_grid->FormKeyCountName ?>';

// Validate form
fSIS_PEDIDO_DETALLEgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_numpedido");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PEDIDO_DETALLE->numpedido->FldCaption(), $SIS_PEDIDO_DETALLE->numpedido->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numpedido");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PEDIDO_DETALLE->numpedido->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codarticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PEDIDO_DETALLE->codarticulo->FldCaption(), $SIS_PEDIDO_DETALLE->codarticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PEDIDO_DETALLE->cantidad->FldCaption(), $SIS_PEDIDO_DETALLE->cantidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PEDIDO_DETALLE->cantidad->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fSIS_PEDIDO_DETALLEgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "numpedido", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codarticulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cantidad", false)) return false;
	return true;
}

// Form_CustomValidate event
fSIS_PEDIDO_DETALLEgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_PEDIDO_DETALLEgrid.ValidateRequired = true;
<?php } else { ?>
fSIS_PEDIDO_DETALLEgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd") {
	if ($SIS_PEDIDO_DETALLE->CurrentMode == "copy") {
		$bSelectLimit = $SIS_PEDIDO_DETALLE_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$SIS_PEDIDO_DETALLE_grid->TotalRecs = $SIS_PEDIDO_DETALLE->SelectRecordCount();
			$SIS_PEDIDO_DETALLE_grid->Recordset = $SIS_PEDIDO_DETALLE_grid->LoadRecordset($SIS_PEDIDO_DETALLE_grid->StartRec-1, $SIS_PEDIDO_DETALLE_grid->DisplayRecs);
		} else {
			if ($SIS_PEDIDO_DETALLE_grid->Recordset = $SIS_PEDIDO_DETALLE_grid->LoadRecordset())
				$SIS_PEDIDO_DETALLE_grid->TotalRecs = $SIS_PEDIDO_DETALLE_grid->Recordset->RecordCount();
		}
		$SIS_PEDIDO_DETALLE_grid->StartRec = 1;
		$SIS_PEDIDO_DETALLE_grid->DisplayRecs = $SIS_PEDIDO_DETALLE_grid->TotalRecs;
	} else {
		$SIS_PEDIDO_DETALLE->CurrentFilter = "0=1";
		$SIS_PEDIDO_DETALLE_grid->StartRec = 1;
		$SIS_PEDIDO_DETALLE_grid->DisplayRecs = $SIS_PEDIDO_DETALLE->GridAddRowCount;
	}
	$SIS_PEDIDO_DETALLE_grid->TotalRecs = $SIS_PEDIDO_DETALLE_grid->DisplayRecs;
	$SIS_PEDIDO_DETALLE_grid->StopRec = $SIS_PEDIDO_DETALLE_grid->DisplayRecs;
} else {
	$bSelectLimit = $SIS_PEDIDO_DETALLE_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_PEDIDO_DETALLE_grid->TotalRecs <= 0)
			$SIS_PEDIDO_DETALLE_grid->TotalRecs = $SIS_PEDIDO_DETALLE->SelectRecordCount();
	} else {
		if (!$SIS_PEDIDO_DETALLE_grid->Recordset && ($SIS_PEDIDO_DETALLE_grid->Recordset = $SIS_PEDIDO_DETALLE_grid->LoadRecordset()))
			$SIS_PEDIDO_DETALLE_grid->TotalRecs = $SIS_PEDIDO_DETALLE_grid->Recordset->RecordCount();
	}
	$SIS_PEDIDO_DETALLE_grid->StartRec = 1;
	$SIS_PEDIDO_DETALLE_grid->DisplayRecs = $SIS_PEDIDO_DETALLE_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$SIS_PEDIDO_DETALLE_grid->Recordset = $SIS_PEDIDO_DETALLE_grid->LoadRecordset($SIS_PEDIDO_DETALLE_grid->StartRec-1, $SIS_PEDIDO_DETALLE_grid->DisplayRecs);

	// Set no record found message
	if ($SIS_PEDIDO_DETALLE->CurrentAction == "" && $SIS_PEDIDO_DETALLE_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_PEDIDO_DETALLE_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_PEDIDO_DETALLE_grid->SearchWhere == "0=101")
			$SIS_PEDIDO_DETALLE_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_PEDIDO_DETALLE_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$SIS_PEDIDO_DETALLE_grid->RenderOtherOptions();
?>
<?php $SIS_PEDIDO_DETALLE_grid->ShowPageHeader(); ?>
<?php
$SIS_PEDIDO_DETALLE_grid->ShowMessage();
?>
<?php if ($SIS_PEDIDO_DETALLE_grid->TotalRecs > 0 || $SIS_PEDIDO_DETALLE->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fSIS_PEDIDO_DETALLEgrid" class="ewForm form-inline">
<div id="gmp_SIS_PEDIDO_DETALLE" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_SIS_PEDIDO_DETALLEgrid" class="table ewTable">
<?php echo $SIS_PEDIDO_DETALLE->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_PEDIDO_DETALLE_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_PEDIDO_DETALLE_grid->RenderListOptions();

// Render list options (header, left)
$SIS_PEDIDO_DETALLE_grid->ListOptions->Render("header", "left");
?>
<?php if ($SIS_PEDIDO_DETALLE->numpedido->Visible) { // numpedido ?>
	<?php if ($SIS_PEDIDO_DETALLE->SortUrl($SIS_PEDIDO_DETALLE->numpedido) == "") { ?>
		<th data-name="numpedido"><div id="elh_SIS_PEDIDO_DETALLE_numpedido" class="SIS_PEDIDO_DETALLE_numpedido"><div class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->numpedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numpedido"><div><div id="elh_SIS_PEDIDO_DETALLE_numpedido" class="SIS_PEDIDO_DETALLE_numpedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->numpedido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_PEDIDO_DETALLE->numpedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_PEDIDO_DETALLE->numpedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_PEDIDO_DETALLE->numdetpedido->Visible) { // numdetpedido ?>
	<?php if ($SIS_PEDIDO_DETALLE->SortUrl($SIS_PEDIDO_DETALLE->numdetpedido) == "") { ?>
		<th data-name="numdetpedido"><div id="elh_SIS_PEDIDO_DETALLE_numdetpedido" class="SIS_PEDIDO_DETALLE_numdetpedido"><div class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->numdetpedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numdetpedido"><div><div id="elh_SIS_PEDIDO_DETALLE_numdetpedido" class="SIS_PEDIDO_DETALLE_numdetpedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->numdetpedido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_PEDIDO_DETALLE->numdetpedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_PEDIDO_DETALLE->numdetpedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_PEDIDO_DETALLE->codarticulo->Visible) { // codarticulo ?>
	<?php if ($SIS_PEDIDO_DETALLE->SortUrl($SIS_PEDIDO_DETALLE->codarticulo) == "") { ?>
		<th data-name="codarticulo"><div id="elh_SIS_PEDIDO_DETALLE_codarticulo" class="SIS_PEDIDO_DETALLE_codarticulo"><div class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->codarticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codarticulo"><div><div id="elh_SIS_PEDIDO_DETALLE_codarticulo" class="SIS_PEDIDO_DETALLE_codarticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->codarticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_PEDIDO_DETALLE->codarticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_PEDIDO_DETALLE->codarticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_PEDIDO_DETALLE->cantidad->Visible) { // cantidad ?>
	<?php if ($SIS_PEDIDO_DETALLE->SortUrl($SIS_PEDIDO_DETALLE->cantidad) == "") { ?>
		<th data-name="cantidad"><div id="elh_SIS_PEDIDO_DETALLE_cantidad" class="SIS_PEDIDO_DETALLE_cantidad"><div class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->cantidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cantidad"><div><div id="elh_SIS_PEDIDO_DETALLE_cantidad" class="SIS_PEDIDO_DETALLE_cantidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_PEDIDO_DETALLE->cantidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_PEDIDO_DETALLE->cantidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_PEDIDO_DETALLE->cantidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_PEDIDO_DETALLE_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$SIS_PEDIDO_DETALLE_grid->StartRec = 1;
$SIS_PEDIDO_DETALLE_grid->StopRec = $SIS_PEDIDO_DETALLE_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($SIS_PEDIDO_DETALLE_grid->FormKeyCountName) && ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd" || $SIS_PEDIDO_DETALLE->CurrentAction == "gridedit" || $SIS_PEDIDO_DETALLE->CurrentAction == "F")) {
		$SIS_PEDIDO_DETALLE_grid->KeyCount = $objForm->GetValue($SIS_PEDIDO_DETALLE_grid->FormKeyCountName);
		$SIS_PEDIDO_DETALLE_grid->StopRec = $SIS_PEDIDO_DETALLE_grid->StartRec + $SIS_PEDIDO_DETALLE_grid->KeyCount - 1;
	}
}
$SIS_PEDIDO_DETALLE_grid->RecCnt = $SIS_PEDIDO_DETALLE_grid->StartRec - 1;
if ($SIS_PEDIDO_DETALLE_grid->Recordset && !$SIS_PEDIDO_DETALLE_grid->Recordset->EOF) {
	$SIS_PEDIDO_DETALLE_grid->Recordset->MoveFirst();
	$bSelectLimit = $SIS_PEDIDO_DETALLE_grid->UseSelectLimit;
	if (!$bSelectLimit && $SIS_PEDIDO_DETALLE_grid->StartRec > 1)
		$SIS_PEDIDO_DETALLE_grid->Recordset->Move($SIS_PEDIDO_DETALLE_grid->StartRec - 1);
} elseif (!$SIS_PEDIDO_DETALLE->AllowAddDeleteRow && $SIS_PEDIDO_DETALLE_grid->StopRec == 0) {
	$SIS_PEDIDO_DETALLE_grid->StopRec = $SIS_PEDIDO_DETALLE->GridAddRowCount;
}

// Initialize aggregate
$SIS_PEDIDO_DETALLE->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_PEDIDO_DETALLE->ResetAttrs();
$SIS_PEDIDO_DETALLE_grid->RenderRow();
if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd")
	$SIS_PEDIDO_DETALLE_grid->RowIndex = 0;
if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridedit")
	$SIS_PEDIDO_DETALLE_grid->RowIndex = 0;
while ($SIS_PEDIDO_DETALLE_grid->RecCnt < $SIS_PEDIDO_DETALLE_grid->StopRec) {
	$SIS_PEDIDO_DETALLE_grid->RecCnt++;
	if (intval($SIS_PEDIDO_DETALLE_grid->RecCnt) >= intval($SIS_PEDIDO_DETALLE_grid->StartRec)) {
		$SIS_PEDIDO_DETALLE_grid->RowCnt++;
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd" || $SIS_PEDIDO_DETALLE->CurrentAction == "gridedit" || $SIS_PEDIDO_DETALLE->CurrentAction == "F") {
			$SIS_PEDIDO_DETALLE_grid->RowIndex++;
			$objForm->Index = $SIS_PEDIDO_DETALLE_grid->RowIndex;
			if ($objForm->HasValue($SIS_PEDIDO_DETALLE_grid->FormActionName))
				$SIS_PEDIDO_DETALLE_grid->RowAction = strval($objForm->GetValue($SIS_PEDIDO_DETALLE_grid->FormActionName));
			elseif ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd")
				$SIS_PEDIDO_DETALLE_grid->RowAction = "insert";
			else
				$SIS_PEDIDO_DETALLE_grid->RowAction = "";
		}

		// Set up key count
		$SIS_PEDIDO_DETALLE_grid->KeyCount = $SIS_PEDIDO_DETALLE_grid->RowIndex;

		// Init row class and style
		$SIS_PEDIDO_DETALLE->ResetAttrs();
		$SIS_PEDIDO_DETALLE->CssClass = "";
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd") {
			if ($SIS_PEDIDO_DETALLE->CurrentMode == "copy") {
				$SIS_PEDIDO_DETALLE_grid->LoadRowValues($SIS_PEDIDO_DETALLE_grid->Recordset); // Load row values
				$SIS_PEDIDO_DETALLE_grid->SetRecordKey($SIS_PEDIDO_DETALLE_grid->RowOldKey, $SIS_PEDIDO_DETALLE_grid->Recordset); // Set old record key
			} else {
				$SIS_PEDIDO_DETALLE_grid->LoadDefaultValues(); // Load default values
				$SIS_PEDIDO_DETALLE_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$SIS_PEDIDO_DETALLE_grid->LoadRowValues($SIS_PEDIDO_DETALLE_grid->Recordset); // Load row values
		}
		$SIS_PEDIDO_DETALLE->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd") // Grid add
			$SIS_PEDIDO_DETALLE->RowType = EW_ROWTYPE_ADD; // Render add
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridadd" && $SIS_PEDIDO_DETALLE->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$SIS_PEDIDO_DETALLE_grid->RestoreCurrentRowFormValues($SIS_PEDIDO_DETALLE_grid->RowIndex); // Restore form values
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridedit") { // Grid edit
			if ($SIS_PEDIDO_DETALLE->EventCancelled) {
				$SIS_PEDIDO_DETALLE_grid->RestoreCurrentRowFormValues($SIS_PEDIDO_DETALLE_grid->RowIndex); // Restore form values
			}
			if ($SIS_PEDIDO_DETALLE_grid->RowAction == "insert")
				$SIS_PEDIDO_DETALLE->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$SIS_PEDIDO_DETALLE->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "gridedit" && ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT || $SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_ADD) && $SIS_PEDIDO_DETALLE->EventCancelled) // Update failed
			$SIS_PEDIDO_DETALLE_grid->RestoreCurrentRowFormValues($SIS_PEDIDO_DETALLE_grid->RowIndex); // Restore form values
		if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT) // Edit row
			$SIS_PEDIDO_DETALLE_grid->EditRowCnt++;
		if ($SIS_PEDIDO_DETALLE->CurrentAction == "F") // Confirm row
			$SIS_PEDIDO_DETALLE_grid->RestoreCurrentRowFormValues($SIS_PEDIDO_DETALLE_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$SIS_PEDIDO_DETALLE->RowAttrs = array_merge($SIS_PEDIDO_DETALLE->RowAttrs, array('data-rowindex'=>$SIS_PEDIDO_DETALLE_grid->RowCnt, 'id'=>'r' . $SIS_PEDIDO_DETALLE_grid->RowCnt . '_SIS_PEDIDO_DETALLE', 'data-rowtype'=>$SIS_PEDIDO_DETALLE->RowType));

		// Render row
		$SIS_PEDIDO_DETALLE_grid->RenderRow();

		// Render list options
		$SIS_PEDIDO_DETALLE_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($SIS_PEDIDO_DETALLE_grid->RowAction <> "delete" && $SIS_PEDIDO_DETALLE_grid->RowAction <> "insertdelete" && !($SIS_PEDIDO_DETALLE_grid->RowAction == "insert" && $SIS_PEDIDO_DETALLE->CurrentAction == "F" && $SIS_PEDIDO_DETALLE_grid->EmptyRow())) {
?>
	<tr<?php echo $SIS_PEDIDO_DETALLE->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_PEDIDO_DETALLE_grid->ListOptions->Render("body", "left", $SIS_PEDIDO_DETALLE_grid->RowCnt);
?>
	<?php if ($SIS_PEDIDO_DETALLE->numpedido->Visible) { // numpedido ?>
		<td data-name="numpedido"<?php echo $SIS_PEDIDO_DETALLE->numpedido->CellAttributes() ?>>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($SIS_PEDIDO_DETALLE->numpedido->getSessionValue() <> "") { ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_numpedido" class="form-group SIS_PEDIDO_DETALLE_numpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_numpedido" class="form-group SIS_PEDIDO_DETALLE_numpedido">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->numpedido->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->numpedido->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->OldValue) ?>">
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_numpedido" class="form-group SIS_PEDIDO_DETALLE_numpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numpedido->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->CurrentValue) ?>">
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_numpedido" class="SIS_PEDIDO_DETALLE_numpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO_DETALLE->numpedido->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->FormValue) ?>">
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->OldValue) ?>">
<?php } ?>
<a id="<?php echo $SIS_PEDIDO_DETALLE_grid->PageObjName . "_row_" . $SIS_PEDIDO_DETALLE_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_PEDIDO_DETALLE->numdetpedido->Visible) { // numdetpedido ?>
		<td data-name="numdetpedido"<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->CellAttributes() ?>>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->OldValue) ?>">
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_numdetpedido" class="form-group SIS_PEDIDO_DETALLE_numdetpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numdetpedido->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->CurrentValue) ?>">
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_numdetpedido" class="SIS_PEDIDO_DETALLE_numdetpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->FormValue) ?>">
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($SIS_PEDIDO_DETALLE->codarticulo->Visible) { // codarticulo ?>
		<td data-name="codarticulo"<?php echo $SIS_PEDIDO_DETALLE->codarticulo->CellAttributes() ?>>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_codarticulo" class="form-group SIS_PEDIDO_DETALLE_codarticulo">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->OldValue) ?>">
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_codarticulo" class="form-group SIS_PEDIDO_DETALLE_codarticulo">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_codarticulo" class="SIS_PEDIDO_DETALLE_codarticulo">
<span<?php echo $SIS_PEDIDO_DETALLE->codarticulo->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO_DETALLE->codarticulo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->FormValue) ?>">
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($SIS_PEDIDO_DETALLE->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"<?php echo $SIS_PEDIDO_DETALLE->cantidad->CellAttributes() ?>>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_cantidad" class="form-group SIS_PEDIDO_DETALLE_cantidad">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->OldValue) ?>">
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_cantidad" class="form-group SIS_PEDIDO_DETALLE_cantidad">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_PEDIDO_DETALLE_grid->RowCnt ?>_SIS_PEDIDO_DETALLE_cantidad" class="SIS_PEDIDO_DETALLE_cantidad">
<span<?php echo $SIS_PEDIDO_DETALLE->cantidad->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO_DETALLE->cantidad->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->FormValue) ?>">
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_PEDIDO_DETALLE_grid->ListOptions->Render("body", "right", $SIS_PEDIDO_DETALLE_grid->RowCnt);
?>
	</tr>
<?php if ($SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_ADD || $SIS_PEDIDO_DETALLE->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fSIS_PEDIDO_DETALLEgrid.UpdateOpts(<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($SIS_PEDIDO_DETALLE->CurrentAction <> "gridadd" || $SIS_PEDIDO_DETALLE->CurrentMode == "copy")
		if (!$SIS_PEDIDO_DETALLE_grid->Recordset->EOF) $SIS_PEDIDO_DETALLE_grid->Recordset->MoveNext();
}
?>
<?php
	if ($SIS_PEDIDO_DETALLE->CurrentMode == "add" || $SIS_PEDIDO_DETALLE->CurrentMode == "copy" || $SIS_PEDIDO_DETALLE->CurrentMode == "edit") {
		$SIS_PEDIDO_DETALLE_grid->RowIndex = '$rowindex$';
		$SIS_PEDIDO_DETALLE_grid->LoadDefaultValues();

		// Set row properties
		$SIS_PEDIDO_DETALLE->ResetAttrs();
		$SIS_PEDIDO_DETALLE->RowAttrs = array_merge($SIS_PEDIDO_DETALLE->RowAttrs, array('data-rowindex'=>$SIS_PEDIDO_DETALLE_grid->RowIndex, 'id'=>'r0_SIS_PEDIDO_DETALLE', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($SIS_PEDIDO_DETALLE->RowAttrs["class"], "ewTemplate");
		$SIS_PEDIDO_DETALLE->RowType = EW_ROWTYPE_ADD;

		// Render row
		$SIS_PEDIDO_DETALLE_grid->RenderRow();

		// Render list options
		$SIS_PEDIDO_DETALLE_grid->RenderListOptions();
		$SIS_PEDIDO_DETALLE_grid->StartRowCnt = 0;
?>
	<tr<?php echo $SIS_PEDIDO_DETALLE->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_PEDIDO_DETALLE_grid->ListOptions->Render("body", "left", $SIS_PEDIDO_DETALLE_grid->RowIndex);
?>
	<?php if ($SIS_PEDIDO_DETALLE->numpedido->Visible) { // numpedido ?>
		<td data-name="numpedido">
<?php if ($SIS_PEDIDO_DETALLE->CurrentAction <> "F") { ?>
<?php if ($SIS_PEDIDO_DETALLE->numpedido->getSessionValue() <> "") { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_numpedido" class="form-group SIS_PEDIDO_DETALLE_numpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_numpedido" class="form-group SIS_PEDIDO_DETALLE_numpedido">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->numpedido->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->numpedido->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_numpedido" class="form-group SIS_PEDIDO_DETALLE_numpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_PEDIDO_DETALLE->numdetpedido->Visible) { // numdetpedido ?>
		<td data-name="numdetpedido">
<?php if ($SIS_PEDIDO_DETALLE->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_numdetpedido" class="form-group SIS_PEDIDO_DETALLE_numdetpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numdetpedido->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_PEDIDO_DETALLE->codarticulo->Visible) { // codarticulo ?>
		<td data-name="codarticulo">
<?php if ($SIS_PEDIDO_DETALLE->CurrentAction <> "F") { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_codarticulo" class="form-group SIS_PEDIDO_DETALLE_codarticulo">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_codarticulo" class="form-group SIS_PEDIDO_DETALLE_codarticulo">
<span<?php echo $SIS_PEDIDO_DETALLE->codarticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->codarticulo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_PEDIDO_DETALLE->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad">
<?php if ($SIS_PEDIDO_DETALLE->CurrentAction <> "F") { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_cantidad" class="form-group SIS_PEDIDO_DETALLE_cantidad">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_SIS_PEDIDO_DETALLE_cantidad" class="form-group SIS_PEDIDO_DETALLE_cantidad">
<span<?php echo $SIS_PEDIDO_DETALLE->cantidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->cantidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" id="o<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_PEDIDO_DETALLE_grid->ListOptions->Render("body", "right", $SIS_PEDIDO_DETALLE_grid->RowCnt);
?>
<script type="text/javascript">
fSIS_PEDIDO_DETALLEgrid.UpdateOpts(<?php echo $SIS_PEDIDO_DETALLE_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($SIS_PEDIDO_DETALLE->CurrentMode == "add" || $SIS_PEDIDO_DETALLE->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $SIS_PEDIDO_DETALLE_grid->FormKeyCountName ?>" id="<?php echo $SIS_PEDIDO_DETALLE_grid->FormKeyCountName ?>" value="<?php echo $SIS_PEDIDO_DETALLE_grid->KeyCount ?>">
<?php echo $SIS_PEDIDO_DETALLE_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $SIS_PEDIDO_DETALLE_grid->FormKeyCountName ?>" id="<?php echo $SIS_PEDIDO_DETALLE_grid->FormKeyCountName ?>" value="<?php echo $SIS_PEDIDO_DETALLE_grid->KeyCount ?>">
<?php echo $SIS_PEDIDO_DETALLE_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fSIS_PEDIDO_DETALLEgrid">
</div>
<?php

// Close recordset
if ($SIS_PEDIDO_DETALLE_grid->Recordset)
	$SIS_PEDIDO_DETALLE_grid->Recordset->Close();
?>
<?php if ($SIS_PEDIDO_DETALLE_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($SIS_PEDIDO_DETALLE_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE_grid->TotalRecs == 0 && $SIS_PEDIDO_DETALLE->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_PEDIDO_DETALLE_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->Export == "") { ?>
<script type="text/javascript">
fSIS_PEDIDO_DETALLEgrid.Init();
</script>
<?php } ?>
<?php
$SIS_PEDIDO_DETALLE_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$SIS_PEDIDO_DETALLE_grid->Page_Terminate();
?>
