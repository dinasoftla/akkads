<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php

// Create page object
if (!isset($SIS_FACTURA_DETALLE_grid)) $SIS_FACTURA_DETALLE_grid = new cSIS_FACTURA_DETALLE_grid();

// Page init
$SIS_FACTURA_DETALLE_grid->Page_Init();

// Page main
$SIS_FACTURA_DETALLE_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_FACTURA_DETALLE_grid->Page_Render();
?>
<?php if ($SIS_FACTURA_DETALLE->Export == "") { ?>
<script type="text/javascript">

// Form object
var fSIS_FACTURA_DETALLEgrid = new ew_Form("fSIS_FACTURA_DETALLEgrid", "grid");
fSIS_FACTURA_DETALLEgrid.FormKeyCountName = '<?php echo $SIS_FACTURA_DETALLE_grid->FormKeyCountName ?>';

// Validate form
fSIS_FACTURA_DETALLEgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_numfactura");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_FACTURA_DETALLE->numfactura->FldCaption(), $SIS_FACTURA_DETALLE->numfactura->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numfactura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_FACTURA_DETALLE->numfactura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_numdetfactura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_FACTURA_DETALLE->numdetfactura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_FACTURA_DETALLE->cantidad->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fSIS_FACTURA_DETALLEgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "numfactura", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codarticulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cantidad", false)) return false;
	return true;
}

// Form_CustomValidate event
fSIS_FACTURA_DETALLEgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_FACTURA_DETALLEgrid.ValidateRequired = true;
<?php } else { ?>
fSIS_FACTURA_DETALLEgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd") {
	if ($SIS_FACTURA_DETALLE->CurrentMode == "copy") {
		$bSelectLimit = $SIS_FACTURA_DETALLE_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$SIS_FACTURA_DETALLE_grid->TotalRecs = $SIS_FACTURA_DETALLE->SelectRecordCount();
			$SIS_FACTURA_DETALLE_grid->Recordset = $SIS_FACTURA_DETALLE_grid->LoadRecordset($SIS_FACTURA_DETALLE_grid->StartRec-1, $SIS_FACTURA_DETALLE_grid->DisplayRecs);
		} else {
			if ($SIS_FACTURA_DETALLE_grid->Recordset = $SIS_FACTURA_DETALLE_grid->LoadRecordset())
				$SIS_FACTURA_DETALLE_grid->TotalRecs = $SIS_FACTURA_DETALLE_grid->Recordset->RecordCount();
		}
		$SIS_FACTURA_DETALLE_grid->StartRec = 1;
		$SIS_FACTURA_DETALLE_grid->DisplayRecs = $SIS_FACTURA_DETALLE_grid->TotalRecs;
	} else {
		$SIS_FACTURA_DETALLE->CurrentFilter = "0=1";
		$SIS_FACTURA_DETALLE_grid->StartRec = 1;
		$SIS_FACTURA_DETALLE_grid->DisplayRecs = $SIS_FACTURA_DETALLE->GridAddRowCount;
	}
	$SIS_FACTURA_DETALLE_grid->TotalRecs = $SIS_FACTURA_DETALLE_grid->DisplayRecs;
	$SIS_FACTURA_DETALLE_grid->StopRec = $SIS_FACTURA_DETALLE_grid->DisplayRecs;
} else {
	$bSelectLimit = $SIS_FACTURA_DETALLE_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_FACTURA_DETALLE_grid->TotalRecs <= 0)
			$SIS_FACTURA_DETALLE_grid->TotalRecs = $SIS_FACTURA_DETALLE->SelectRecordCount();
	} else {
		if (!$SIS_FACTURA_DETALLE_grid->Recordset && ($SIS_FACTURA_DETALLE_grid->Recordset = $SIS_FACTURA_DETALLE_grid->LoadRecordset()))
			$SIS_FACTURA_DETALLE_grid->TotalRecs = $SIS_FACTURA_DETALLE_grid->Recordset->RecordCount();
	}
	$SIS_FACTURA_DETALLE_grid->StartRec = 1;
	$SIS_FACTURA_DETALLE_grid->DisplayRecs = $SIS_FACTURA_DETALLE_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$SIS_FACTURA_DETALLE_grid->Recordset = $SIS_FACTURA_DETALLE_grid->LoadRecordset($SIS_FACTURA_DETALLE_grid->StartRec-1, $SIS_FACTURA_DETALLE_grid->DisplayRecs);

	// Set no record found message
	if ($SIS_FACTURA_DETALLE->CurrentAction == "" && $SIS_FACTURA_DETALLE_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_FACTURA_DETALLE_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_FACTURA_DETALLE_grid->SearchWhere == "0=101")
			$SIS_FACTURA_DETALLE_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_FACTURA_DETALLE_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$SIS_FACTURA_DETALLE_grid->RenderOtherOptions();
?>
<?php $SIS_FACTURA_DETALLE_grid->ShowPageHeader(); ?>
<?php
$SIS_FACTURA_DETALLE_grid->ShowMessage();
?>
<?php if ($SIS_FACTURA_DETALLE_grid->TotalRecs > 0 || $SIS_FACTURA_DETALLE->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fSIS_FACTURA_DETALLEgrid" class="ewForm form-inline">
<div id="gmp_SIS_FACTURA_DETALLE" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_SIS_FACTURA_DETALLEgrid" class="table ewTable">
<?php echo $SIS_FACTURA_DETALLE->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_FACTURA_DETALLE_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_FACTURA_DETALLE_grid->RenderListOptions();

// Render list options (header, left)
$SIS_FACTURA_DETALLE_grid->ListOptions->Render("header", "left");
?>
<?php if ($SIS_FACTURA_DETALLE->numfactura->Visible) { // numfactura ?>
	<?php if ($SIS_FACTURA_DETALLE->SortUrl($SIS_FACTURA_DETALLE->numfactura) == "") { ?>
		<th data-name="numfactura"><div id="elh_SIS_FACTURA_DETALLE_numfactura" class="SIS_FACTURA_DETALLE_numfactura"><div class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->numfactura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numfactura"><div><div id="elh_SIS_FACTURA_DETALLE_numfactura" class="SIS_FACTURA_DETALLE_numfactura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->numfactura->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_FACTURA_DETALLE->numfactura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_FACTURA_DETALLE->numfactura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_FACTURA_DETALLE->numdetfactura->Visible) { // numdetfactura ?>
	<?php if ($SIS_FACTURA_DETALLE->SortUrl($SIS_FACTURA_DETALLE->numdetfactura) == "") { ?>
		<th data-name="numdetfactura"><div id="elh_SIS_FACTURA_DETALLE_numdetfactura" class="SIS_FACTURA_DETALLE_numdetfactura"><div class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->numdetfactura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numdetfactura"><div><div id="elh_SIS_FACTURA_DETALLE_numdetfactura" class="SIS_FACTURA_DETALLE_numdetfactura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->numdetfactura->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_FACTURA_DETALLE->numdetfactura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_FACTURA_DETALLE->numdetfactura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_FACTURA_DETALLE->codarticulo->Visible) { // codarticulo ?>
	<?php if ($SIS_FACTURA_DETALLE->SortUrl($SIS_FACTURA_DETALLE->codarticulo) == "") { ?>
		<th data-name="codarticulo"><div id="elh_SIS_FACTURA_DETALLE_codarticulo" class="SIS_FACTURA_DETALLE_codarticulo"><div class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->codarticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codarticulo"><div><div id="elh_SIS_FACTURA_DETALLE_codarticulo" class="SIS_FACTURA_DETALLE_codarticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->codarticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_FACTURA_DETALLE->codarticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_FACTURA_DETALLE->codarticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_FACTURA_DETALLE->cantidad->Visible) { // cantidad ?>
	<?php if ($SIS_FACTURA_DETALLE->SortUrl($SIS_FACTURA_DETALLE->cantidad) == "") { ?>
		<th data-name="cantidad"><div id="elh_SIS_FACTURA_DETALLE_cantidad" class="SIS_FACTURA_DETALLE_cantidad"><div class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->cantidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cantidad"><div><div id="elh_SIS_FACTURA_DETALLE_cantidad" class="SIS_FACTURA_DETALLE_cantidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_FACTURA_DETALLE->cantidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_FACTURA_DETALLE->cantidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_FACTURA_DETALLE->cantidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_FACTURA_DETALLE_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$SIS_FACTURA_DETALLE_grid->StartRec = 1;
$SIS_FACTURA_DETALLE_grid->StopRec = $SIS_FACTURA_DETALLE_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($SIS_FACTURA_DETALLE_grid->FormKeyCountName) && ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd" || $SIS_FACTURA_DETALLE->CurrentAction == "gridedit" || $SIS_FACTURA_DETALLE->CurrentAction == "F")) {
		$SIS_FACTURA_DETALLE_grid->KeyCount = $objForm->GetValue($SIS_FACTURA_DETALLE_grid->FormKeyCountName);
		$SIS_FACTURA_DETALLE_grid->StopRec = $SIS_FACTURA_DETALLE_grid->StartRec + $SIS_FACTURA_DETALLE_grid->KeyCount - 1;
	}
}
$SIS_FACTURA_DETALLE_grid->RecCnt = $SIS_FACTURA_DETALLE_grid->StartRec - 1;
if ($SIS_FACTURA_DETALLE_grid->Recordset && !$SIS_FACTURA_DETALLE_grid->Recordset->EOF) {
	$SIS_FACTURA_DETALLE_grid->Recordset->MoveFirst();
	$bSelectLimit = $SIS_FACTURA_DETALLE_grid->UseSelectLimit;
	if (!$bSelectLimit && $SIS_FACTURA_DETALLE_grid->StartRec > 1)
		$SIS_FACTURA_DETALLE_grid->Recordset->Move($SIS_FACTURA_DETALLE_grid->StartRec - 1);
} elseif (!$SIS_FACTURA_DETALLE->AllowAddDeleteRow && $SIS_FACTURA_DETALLE_grid->StopRec == 0) {
	$SIS_FACTURA_DETALLE_grid->StopRec = $SIS_FACTURA_DETALLE->GridAddRowCount;
}

// Initialize aggregate
$SIS_FACTURA_DETALLE->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_FACTURA_DETALLE->ResetAttrs();
$SIS_FACTURA_DETALLE_grid->RenderRow();
if ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd")
	$SIS_FACTURA_DETALLE_grid->RowIndex = 0;
if ($SIS_FACTURA_DETALLE->CurrentAction == "gridedit")
	$SIS_FACTURA_DETALLE_grid->RowIndex = 0;
while ($SIS_FACTURA_DETALLE_grid->RecCnt < $SIS_FACTURA_DETALLE_grid->StopRec) {
	$SIS_FACTURA_DETALLE_grid->RecCnt++;
	if (intval($SIS_FACTURA_DETALLE_grid->RecCnt) >= intval($SIS_FACTURA_DETALLE_grid->StartRec)) {
		$SIS_FACTURA_DETALLE_grid->RowCnt++;
		if ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd" || $SIS_FACTURA_DETALLE->CurrentAction == "gridedit" || $SIS_FACTURA_DETALLE->CurrentAction == "F") {
			$SIS_FACTURA_DETALLE_grid->RowIndex++;
			$objForm->Index = $SIS_FACTURA_DETALLE_grid->RowIndex;
			if ($objForm->HasValue($SIS_FACTURA_DETALLE_grid->FormActionName))
				$SIS_FACTURA_DETALLE_grid->RowAction = strval($objForm->GetValue($SIS_FACTURA_DETALLE_grid->FormActionName));
			elseif ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd")
				$SIS_FACTURA_DETALLE_grid->RowAction = "insert";
			else
				$SIS_FACTURA_DETALLE_grid->RowAction = "";
		}

		// Set up key count
		$SIS_FACTURA_DETALLE_grid->KeyCount = $SIS_FACTURA_DETALLE_grid->RowIndex;

		// Init row class and style
		$SIS_FACTURA_DETALLE->ResetAttrs();
		$SIS_FACTURA_DETALLE->CssClass = "";
		if ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd") {
			if ($SIS_FACTURA_DETALLE->CurrentMode == "copy") {
				$SIS_FACTURA_DETALLE_grid->LoadRowValues($SIS_FACTURA_DETALLE_grid->Recordset); // Load row values
				$SIS_FACTURA_DETALLE_grid->SetRecordKey($SIS_FACTURA_DETALLE_grid->RowOldKey, $SIS_FACTURA_DETALLE_grid->Recordset); // Set old record key
			} else {
				$SIS_FACTURA_DETALLE_grid->LoadDefaultValues(); // Load default values
				$SIS_FACTURA_DETALLE_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$SIS_FACTURA_DETALLE_grid->LoadRowValues($SIS_FACTURA_DETALLE_grid->Recordset); // Load row values
		}
		$SIS_FACTURA_DETALLE->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd") // Grid add
			$SIS_FACTURA_DETALLE->RowType = EW_ROWTYPE_ADD; // Render add
		if ($SIS_FACTURA_DETALLE->CurrentAction == "gridadd" && $SIS_FACTURA_DETALLE->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$SIS_FACTURA_DETALLE_grid->RestoreCurrentRowFormValues($SIS_FACTURA_DETALLE_grid->RowIndex); // Restore form values
		if ($SIS_FACTURA_DETALLE->CurrentAction == "gridedit") { // Grid edit
			if ($SIS_FACTURA_DETALLE->EventCancelled) {
				$SIS_FACTURA_DETALLE_grid->RestoreCurrentRowFormValues($SIS_FACTURA_DETALLE_grid->RowIndex); // Restore form values
			}
			if ($SIS_FACTURA_DETALLE_grid->RowAction == "insert")
				$SIS_FACTURA_DETALLE->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$SIS_FACTURA_DETALLE->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($SIS_FACTURA_DETALLE->CurrentAction == "gridedit" && ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT || $SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_ADD) && $SIS_FACTURA_DETALLE->EventCancelled) // Update failed
			$SIS_FACTURA_DETALLE_grid->RestoreCurrentRowFormValues($SIS_FACTURA_DETALLE_grid->RowIndex); // Restore form values
		if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT) // Edit row
			$SIS_FACTURA_DETALLE_grid->EditRowCnt++;
		if ($SIS_FACTURA_DETALLE->CurrentAction == "F") // Confirm row
			$SIS_FACTURA_DETALLE_grid->RestoreCurrentRowFormValues($SIS_FACTURA_DETALLE_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$SIS_FACTURA_DETALLE->RowAttrs = array_merge($SIS_FACTURA_DETALLE->RowAttrs, array('data-rowindex'=>$SIS_FACTURA_DETALLE_grid->RowCnt, 'id'=>'r' . $SIS_FACTURA_DETALLE_grid->RowCnt . '_SIS_FACTURA_DETALLE', 'data-rowtype'=>$SIS_FACTURA_DETALLE->RowType));

		// Render row
		$SIS_FACTURA_DETALLE_grid->RenderRow();

		// Render list options
		$SIS_FACTURA_DETALLE_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($SIS_FACTURA_DETALLE_grid->RowAction <> "delete" && $SIS_FACTURA_DETALLE_grid->RowAction <> "insertdelete" && !($SIS_FACTURA_DETALLE_grid->RowAction == "insert" && $SIS_FACTURA_DETALLE->CurrentAction == "F" && $SIS_FACTURA_DETALLE_grid->EmptyRow())) {
?>
	<tr<?php echo $SIS_FACTURA_DETALLE->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_FACTURA_DETALLE_grid->ListOptions->Render("body", "left", $SIS_FACTURA_DETALLE_grid->RowCnt);
?>
	<?php if ($SIS_FACTURA_DETALLE->numfactura->Visible) { // numfactura ?>
		<td data-name="numfactura"<?php echo $SIS_FACTURA_DETALLE->numfactura->CellAttributes() ?>>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($SIS_FACTURA_DETALLE->numfactura->getSessionValue() <> "") { ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_numfactura" class="form-group SIS_FACTURA_DETALLE_numfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->numfactura->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_numfactura" class="form-group SIS_FACTURA_DETALLE_numfactura">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->numfactura->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->numfactura->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->OldValue) ?>">
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_numfactura" class="form-group SIS_FACTURA_DETALLE_numfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->numfactura->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->CurrentValue) ?>">
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_numfactura" class="SIS_FACTURA_DETALLE_numfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numfactura->ViewAttributes() ?>>
<?php echo $SIS_FACTURA_DETALLE->numfactura->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->FormValue) ?>">
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->OldValue) ?>">
<?php } ?>
<a id="<?php echo $SIS_FACTURA_DETALLE_grid->PageObjName . "_row_" . $SIS_FACTURA_DETALLE_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_FACTURA_DETALLE->numdetfactura->Visible) { // numdetfactura ?>
		<td data-name="numdetfactura"<?php echo $SIS_FACTURA_DETALLE->numdetfactura->CellAttributes() ?>>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numdetfactura" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numdetfactura->OldValue) ?>">
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_numdetfactura" class="form-group SIS_FACTURA_DETALLE_numdetfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numdetfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->numdetfactura->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numdetfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numdetfactura->CurrentValue) ?>">
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_numdetfactura" class="SIS_FACTURA_DETALLE_numdetfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numdetfactura->ViewAttributes() ?>>
<?php echo $SIS_FACTURA_DETALLE->numdetfactura->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numdetfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numdetfactura->FormValue) ?>">
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numdetfactura" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numdetfactura->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($SIS_FACTURA_DETALLE->codarticulo->Visible) { // codarticulo ?>
		<td data-name="codarticulo"<?php echo $SIS_FACTURA_DETALLE->codarticulo->CellAttributes() ?>>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_codarticulo" class="form-group SIS_FACTURA_DETALLE_codarticulo">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->OldValue) ?>">
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_codarticulo" class="form-group SIS_FACTURA_DETALLE_codarticulo">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_codarticulo" class="SIS_FACTURA_DETALLE_codarticulo">
<span<?php echo $SIS_FACTURA_DETALLE->codarticulo->ViewAttributes() ?>>
<?php echo $SIS_FACTURA_DETALLE->codarticulo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->FormValue) ?>">
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($SIS_FACTURA_DETALLE->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"<?php echo $SIS_FACTURA_DETALLE->cantidad->CellAttributes() ?>>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_cantidad" class="form-group SIS_FACTURA_DETALLE_cantidad">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->cantidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->OldValue) ?>">
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_cantidad" class="form-group SIS_FACTURA_DETALLE_cantidad">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->cantidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_FACTURA_DETALLE_grid->RowCnt ?>_SIS_FACTURA_DETALLE_cantidad" class="SIS_FACTURA_DETALLE_cantidad">
<span<?php echo $SIS_FACTURA_DETALLE->cantidad->ViewAttributes() ?>>
<?php echo $SIS_FACTURA_DETALLE->cantidad->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->FormValue) ?>">
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_FACTURA_DETALLE_grid->ListOptions->Render("body", "right", $SIS_FACTURA_DETALLE_grid->RowCnt);
?>
	</tr>
<?php if ($SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_ADD || $SIS_FACTURA_DETALLE->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fSIS_FACTURA_DETALLEgrid.UpdateOpts(<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($SIS_FACTURA_DETALLE->CurrentAction <> "gridadd" || $SIS_FACTURA_DETALLE->CurrentMode == "copy")
		if (!$SIS_FACTURA_DETALLE_grid->Recordset->EOF) $SIS_FACTURA_DETALLE_grid->Recordset->MoveNext();
}
?>
<?php
	if ($SIS_FACTURA_DETALLE->CurrentMode == "add" || $SIS_FACTURA_DETALLE->CurrentMode == "copy" || $SIS_FACTURA_DETALLE->CurrentMode == "edit") {
		$SIS_FACTURA_DETALLE_grid->RowIndex = '$rowindex$';
		$SIS_FACTURA_DETALLE_grid->LoadDefaultValues();

		// Set row properties
		$SIS_FACTURA_DETALLE->ResetAttrs();
		$SIS_FACTURA_DETALLE->RowAttrs = array_merge($SIS_FACTURA_DETALLE->RowAttrs, array('data-rowindex'=>$SIS_FACTURA_DETALLE_grid->RowIndex, 'id'=>'r0_SIS_FACTURA_DETALLE', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($SIS_FACTURA_DETALLE->RowAttrs["class"], "ewTemplate");
		$SIS_FACTURA_DETALLE->RowType = EW_ROWTYPE_ADD;

		// Render row
		$SIS_FACTURA_DETALLE_grid->RenderRow();

		// Render list options
		$SIS_FACTURA_DETALLE_grid->RenderListOptions();
		$SIS_FACTURA_DETALLE_grid->StartRowCnt = 0;
?>
	<tr<?php echo $SIS_FACTURA_DETALLE->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_FACTURA_DETALLE_grid->ListOptions->Render("body", "left", $SIS_FACTURA_DETALLE_grid->RowIndex);
?>
	<?php if ($SIS_FACTURA_DETALLE->numfactura->Visible) { // numfactura ?>
		<td data-name="numfactura">
<?php if ($SIS_FACTURA_DETALLE->CurrentAction <> "F") { ?>
<?php if ($SIS_FACTURA_DETALLE->numfactura->getSessionValue() <> "") { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_numfactura" class="form-group SIS_FACTURA_DETALLE_numfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->numfactura->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_numfactura" class="form-group SIS_FACTURA_DETALLE_numfactura">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->numfactura->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->numfactura->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_numfactura" class="form-group SIS_FACTURA_DETALLE_numfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->numfactura->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numfactura" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numfactura->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_FACTURA_DETALLE->numdetfactura->Visible) { // numdetfactura ?>
		<td data-name="numdetfactura">
<?php if ($SIS_FACTURA_DETALLE->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_numdetfactura" class="form-group SIS_FACTURA_DETALLE_numdetfactura">
<span<?php echo $SIS_FACTURA_DETALLE->numdetfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->numdetfactura->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numdetfactura" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numdetfactura->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_numdetfactura" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_numdetfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->numdetfactura->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_FACTURA_DETALLE->codarticulo->Visible) { // codarticulo ?>
		<td data-name="codarticulo">
<?php if ($SIS_FACTURA_DETALLE->CurrentAction <> "F") { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_codarticulo" class="form-group SIS_FACTURA_DETALLE_codarticulo">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_codarticulo" class="form-group SIS_FACTURA_DETALLE_codarticulo">
<span<?php echo $SIS_FACTURA_DETALLE->codarticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->codarticulo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_codarticulo" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_codarticulo" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->codarticulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_FACTURA_DETALLE->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad">
<?php if ($SIS_FACTURA_DETALLE->CurrentAction <> "F") { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_cantidad" class="form-group SIS_FACTURA_DETALLE_cantidad">
<input type="text" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_FACTURA_DETALLE->cantidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_SIS_FACTURA_DETALLE_cantidad" class="form-group SIS_FACTURA_DETALLE_cantidad">
<span<?php echo $SIS_FACTURA_DETALLE->cantidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA_DETALLE->cantidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="x<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_FACTURA_DETALLE" data-field="x_cantidad" name="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" id="o<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($SIS_FACTURA_DETALLE->cantidad->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_FACTURA_DETALLE_grid->ListOptions->Render("body", "right", $SIS_FACTURA_DETALLE_grid->RowCnt);
?>
<script type="text/javascript">
fSIS_FACTURA_DETALLEgrid.UpdateOpts(<?php echo $SIS_FACTURA_DETALLE_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($SIS_FACTURA_DETALLE->CurrentMode == "add" || $SIS_FACTURA_DETALLE->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $SIS_FACTURA_DETALLE_grid->FormKeyCountName ?>" id="<?php echo $SIS_FACTURA_DETALLE_grid->FormKeyCountName ?>" value="<?php echo $SIS_FACTURA_DETALLE_grid->KeyCount ?>">
<?php echo $SIS_FACTURA_DETALLE_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $SIS_FACTURA_DETALLE_grid->FormKeyCountName ?>" id="<?php echo $SIS_FACTURA_DETALLE_grid->FormKeyCountName ?>" value="<?php echo $SIS_FACTURA_DETALLE_grid->KeyCount ?>">
<?php echo $SIS_FACTURA_DETALLE_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fSIS_FACTURA_DETALLEgrid">
</div>
<?php

// Close recordset
if ($SIS_FACTURA_DETALLE_grid->Recordset)
	$SIS_FACTURA_DETALLE_grid->Recordset->Close();
?>
<?php if ($SIS_FACTURA_DETALLE_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($SIS_FACTURA_DETALLE_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE_grid->TotalRecs == 0 && $SIS_FACTURA_DETALLE->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_FACTURA_DETALLE_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($SIS_FACTURA_DETALLE->Export == "") { ?>
<script type="text/javascript">
fSIS_FACTURA_DETALLEgrid.Init();
</script>
<?php } ?>
<?php
$SIS_FACTURA_DETALLE_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$SIS_FACTURA_DETALLE_grid->Page_Terminate();
?>
