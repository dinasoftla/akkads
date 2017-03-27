<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php

// Create page object
if (!isset($SIS_ABONOS_grid)) $SIS_ABONOS_grid = new cSIS_ABONOS_grid();

// Page init
$SIS_ABONOS_grid->Page_Init();

// Page main
$SIS_ABONOS_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_ABONOS_grid->Page_Render();
?>
<?php if ($SIS_ABONOS->Export == "") { ?>
<script type="text/javascript">

// Form object
var fSIS_ABONOSgrid = new ew_Form("fSIS_ABONOSgrid", "grid");
fSIS_ABONOSgrid.FormKeyCountName = '<?php echo $SIS_ABONOS_grid->FormKeyCountName ?>';

// Validate form
fSIS_ABONOSgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codapartado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ABONOS->codapartado->FldCaption(), $SIS_ABONOS->codapartado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codapartado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_ABONOS->codapartado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_abono");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_ABONOS->abono->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fSIS_ABONOSgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "codapartado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "abono", false)) return false;
	return true;
}

// Form_CustomValidate event
fSIS_ABONOSgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ABONOSgrid.ValidateRequired = true;
<?php } else { ?>
fSIS_ABONOSgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($SIS_ABONOS->CurrentAction == "gridadd") {
	if ($SIS_ABONOS->CurrentMode == "copy") {
		$bSelectLimit = $SIS_ABONOS_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$SIS_ABONOS_grid->TotalRecs = $SIS_ABONOS->SelectRecordCount();
			$SIS_ABONOS_grid->Recordset = $SIS_ABONOS_grid->LoadRecordset($SIS_ABONOS_grid->StartRec-1, $SIS_ABONOS_grid->DisplayRecs);
		} else {
			if ($SIS_ABONOS_grid->Recordset = $SIS_ABONOS_grid->LoadRecordset())
				$SIS_ABONOS_grid->TotalRecs = $SIS_ABONOS_grid->Recordset->RecordCount();
		}
		$SIS_ABONOS_grid->StartRec = 1;
		$SIS_ABONOS_grid->DisplayRecs = $SIS_ABONOS_grid->TotalRecs;
	} else {
		$SIS_ABONOS->CurrentFilter = "0=1";
		$SIS_ABONOS_grid->StartRec = 1;
		$SIS_ABONOS_grid->DisplayRecs = $SIS_ABONOS->GridAddRowCount;
	}
	$SIS_ABONOS_grid->TotalRecs = $SIS_ABONOS_grid->DisplayRecs;
	$SIS_ABONOS_grid->StopRec = $SIS_ABONOS_grid->DisplayRecs;
} else {
	$bSelectLimit = $SIS_ABONOS_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_ABONOS_grid->TotalRecs <= 0)
			$SIS_ABONOS_grid->TotalRecs = $SIS_ABONOS->SelectRecordCount();
	} else {
		if (!$SIS_ABONOS_grid->Recordset && ($SIS_ABONOS_grid->Recordset = $SIS_ABONOS_grid->LoadRecordset()))
			$SIS_ABONOS_grid->TotalRecs = $SIS_ABONOS_grid->Recordset->RecordCount();
	}
	$SIS_ABONOS_grid->StartRec = 1;
	$SIS_ABONOS_grid->DisplayRecs = $SIS_ABONOS_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$SIS_ABONOS_grid->Recordset = $SIS_ABONOS_grid->LoadRecordset($SIS_ABONOS_grid->StartRec-1, $SIS_ABONOS_grid->DisplayRecs);

	// Set no record found message
	if ($SIS_ABONOS->CurrentAction == "" && $SIS_ABONOS_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_ABONOS_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_ABONOS_grid->SearchWhere == "0=101")
			$SIS_ABONOS_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_ABONOS_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$SIS_ABONOS_grid->RenderOtherOptions();
?>
<?php $SIS_ABONOS_grid->ShowPageHeader(); ?>
<?php
$SIS_ABONOS_grid->ShowMessage();
?>
<?php if ($SIS_ABONOS_grid->TotalRecs > 0 || $SIS_ABONOS->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fSIS_ABONOSgrid" class="ewForm form-inline">
<div id="gmp_SIS_ABONOS" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_SIS_ABONOSgrid" class="table ewTable">
<?php echo $SIS_ABONOS->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_ABONOS_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_ABONOS_grid->RenderListOptions();

// Render list options (header, left)
$SIS_ABONOS_grid->ListOptions->Render("header", "left");
?>
<?php if ($SIS_ABONOS->codapartado->Visible) { // codapartado ?>
	<?php if ($SIS_ABONOS->SortUrl($SIS_ABONOS->codapartado) == "") { ?>
		<th data-name="codapartado"><div id="elh_SIS_ABONOS_codapartado" class="SIS_ABONOS_codapartado"><div class="ewTableHeaderCaption"><?php echo $SIS_ABONOS->codapartado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codapartado"><div><div id="elh_SIS_ABONOS_codapartado" class="SIS_ABONOS_codapartado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ABONOS->codapartado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ABONOS->codapartado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ABONOS->codapartado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ABONOS->codabono->Visible) { // codabono ?>
	<?php if ($SIS_ABONOS->SortUrl($SIS_ABONOS->codabono) == "") { ?>
		<th data-name="codabono"><div id="elh_SIS_ABONOS_codabono" class="SIS_ABONOS_codabono"><div class="ewTableHeaderCaption"><?php echo $SIS_ABONOS->codabono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codabono"><div><div id="elh_SIS_ABONOS_codabono" class="SIS_ABONOS_codabono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ABONOS->codabono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ABONOS->codabono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ABONOS->codabono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ABONOS->abono->Visible) { // abono ?>
	<?php if ($SIS_ABONOS->SortUrl($SIS_ABONOS->abono) == "") { ?>
		<th data-name="abono"><div id="elh_SIS_ABONOS_abono" class="SIS_ABONOS_abono"><div class="ewTableHeaderCaption"><?php echo $SIS_ABONOS->abono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="abono"><div><div id="elh_SIS_ABONOS_abono" class="SIS_ABONOS_abono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ABONOS->abono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ABONOS->abono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ABONOS->abono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_ABONOS_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$SIS_ABONOS_grid->StartRec = 1;
$SIS_ABONOS_grid->StopRec = $SIS_ABONOS_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($SIS_ABONOS_grid->FormKeyCountName) && ($SIS_ABONOS->CurrentAction == "gridadd" || $SIS_ABONOS->CurrentAction == "gridedit" || $SIS_ABONOS->CurrentAction == "F")) {
		$SIS_ABONOS_grid->KeyCount = $objForm->GetValue($SIS_ABONOS_grid->FormKeyCountName);
		$SIS_ABONOS_grid->StopRec = $SIS_ABONOS_grid->StartRec + $SIS_ABONOS_grid->KeyCount - 1;
	}
}
$SIS_ABONOS_grid->RecCnt = $SIS_ABONOS_grid->StartRec - 1;
if ($SIS_ABONOS_grid->Recordset && !$SIS_ABONOS_grid->Recordset->EOF) {
	$SIS_ABONOS_grid->Recordset->MoveFirst();
	$bSelectLimit = $SIS_ABONOS_grid->UseSelectLimit;
	if (!$bSelectLimit && $SIS_ABONOS_grid->StartRec > 1)
		$SIS_ABONOS_grid->Recordset->Move($SIS_ABONOS_grid->StartRec - 1);
} elseif (!$SIS_ABONOS->AllowAddDeleteRow && $SIS_ABONOS_grid->StopRec == 0) {
	$SIS_ABONOS_grid->StopRec = $SIS_ABONOS->GridAddRowCount;
}

// Initialize aggregate
$SIS_ABONOS->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_ABONOS->ResetAttrs();
$SIS_ABONOS_grid->RenderRow();
if ($SIS_ABONOS->CurrentAction == "gridadd")
	$SIS_ABONOS_grid->RowIndex = 0;
if ($SIS_ABONOS->CurrentAction == "gridedit")
	$SIS_ABONOS_grid->RowIndex = 0;
while ($SIS_ABONOS_grid->RecCnt < $SIS_ABONOS_grid->StopRec) {
	$SIS_ABONOS_grid->RecCnt++;
	if (intval($SIS_ABONOS_grid->RecCnt) >= intval($SIS_ABONOS_grid->StartRec)) {
		$SIS_ABONOS_grid->RowCnt++;
		if ($SIS_ABONOS->CurrentAction == "gridadd" || $SIS_ABONOS->CurrentAction == "gridedit" || $SIS_ABONOS->CurrentAction == "F") {
			$SIS_ABONOS_grid->RowIndex++;
			$objForm->Index = $SIS_ABONOS_grid->RowIndex;
			if ($objForm->HasValue($SIS_ABONOS_grid->FormActionName))
				$SIS_ABONOS_grid->RowAction = strval($objForm->GetValue($SIS_ABONOS_grid->FormActionName));
			elseif ($SIS_ABONOS->CurrentAction == "gridadd")
				$SIS_ABONOS_grid->RowAction = "insert";
			else
				$SIS_ABONOS_grid->RowAction = "";
		}

		// Set up key count
		$SIS_ABONOS_grid->KeyCount = $SIS_ABONOS_grid->RowIndex;

		// Init row class and style
		$SIS_ABONOS->ResetAttrs();
		$SIS_ABONOS->CssClass = "";
		if ($SIS_ABONOS->CurrentAction == "gridadd") {
			if ($SIS_ABONOS->CurrentMode == "copy") {
				$SIS_ABONOS_grid->LoadRowValues($SIS_ABONOS_grid->Recordset); // Load row values
				$SIS_ABONOS_grid->SetRecordKey($SIS_ABONOS_grid->RowOldKey, $SIS_ABONOS_grid->Recordset); // Set old record key
			} else {
				$SIS_ABONOS_grid->LoadDefaultValues(); // Load default values
				$SIS_ABONOS_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$SIS_ABONOS_grid->LoadRowValues($SIS_ABONOS_grid->Recordset); // Load row values
		}
		$SIS_ABONOS->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($SIS_ABONOS->CurrentAction == "gridadd") // Grid add
			$SIS_ABONOS->RowType = EW_ROWTYPE_ADD; // Render add
		if ($SIS_ABONOS->CurrentAction == "gridadd" && $SIS_ABONOS->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$SIS_ABONOS_grid->RestoreCurrentRowFormValues($SIS_ABONOS_grid->RowIndex); // Restore form values
		if ($SIS_ABONOS->CurrentAction == "gridedit") { // Grid edit
			if ($SIS_ABONOS->EventCancelled) {
				$SIS_ABONOS_grid->RestoreCurrentRowFormValues($SIS_ABONOS_grid->RowIndex); // Restore form values
			}
			if ($SIS_ABONOS_grid->RowAction == "insert")
				$SIS_ABONOS->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$SIS_ABONOS->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($SIS_ABONOS->CurrentAction == "gridedit" && ($SIS_ABONOS->RowType == EW_ROWTYPE_EDIT || $SIS_ABONOS->RowType == EW_ROWTYPE_ADD) && $SIS_ABONOS->EventCancelled) // Update failed
			$SIS_ABONOS_grid->RestoreCurrentRowFormValues($SIS_ABONOS_grid->RowIndex); // Restore form values
		if ($SIS_ABONOS->RowType == EW_ROWTYPE_EDIT) // Edit row
			$SIS_ABONOS_grid->EditRowCnt++;
		if ($SIS_ABONOS->CurrentAction == "F") // Confirm row
			$SIS_ABONOS_grid->RestoreCurrentRowFormValues($SIS_ABONOS_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$SIS_ABONOS->RowAttrs = array_merge($SIS_ABONOS->RowAttrs, array('data-rowindex'=>$SIS_ABONOS_grid->RowCnt, 'id'=>'r' . $SIS_ABONOS_grid->RowCnt . '_SIS_ABONOS', 'data-rowtype'=>$SIS_ABONOS->RowType));

		// Render row
		$SIS_ABONOS_grid->RenderRow();

		// Render list options
		$SIS_ABONOS_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($SIS_ABONOS_grid->RowAction <> "delete" && $SIS_ABONOS_grid->RowAction <> "insertdelete" && !($SIS_ABONOS_grid->RowAction == "insert" && $SIS_ABONOS->CurrentAction == "F" && $SIS_ABONOS_grid->EmptyRow())) {
?>
	<tr<?php echo $SIS_ABONOS->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_ABONOS_grid->ListOptions->Render("body", "left", $SIS_ABONOS_grid->RowCnt);
?>
	<?php if ($SIS_ABONOS->codapartado->Visible) { // codapartado ?>
		<td data-name="codapartado"<?php echo $SIS_ABONOS->codapartado->CellAttributes() ?>>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($SIS_ABONOS->codapartado->getSessionValue() <> "") { ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_codapartado" class="form-group SIS_ABONOS_codapartado">
<span<?php echo $SIS_ABONOS->codapartado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->codapartado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_codapartado" class="form-group SIS_ABONOS_codapartado">
<input type="text" data-table="SIS_ABONOS" data-field="x_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->getPlaceHolder()) ?>" value="<?php echo $SIS_ABONOS->codapartado->EditValue ?>"<?php echo $SIS_ABONOS->codapartado->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codapartado" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->OldValue) ?>">
<?php } ?>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_codapartado" class="form-group SIS_ABONOS_codapartado">
<span<?php echo $SIS_ABONOS->codapartado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->codapartado->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->CurrentValue) ?>">
<?php } ?>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_codapartado" class="SIS_ABONOS_codapartado">
<span<?php echo $SIS_ABONOS->codapartado->ViewAttributes() ?>>
<?php echo $SIS_ABONOS->codapartado->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->FormValue) ?>">
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codapartado" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $SIS_ABONOS_grid->PageObjName . "_row_" . $SIS_ABONOS_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_ABONOS->codabono->Visible) { // codabono ?>
		<td data-name="codabono"<?php echo $SIS_ABONOS->codabono->CellAttributes() ?>>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codabono" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codabono->OldValue) ?>">
<?php } ?>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_codabono" class="form-group SIS_ABONOS_codabono">
<span<?php echo $SIS_ABONOS->codabono->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->codabono->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codabono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codabono->CurrentValue) ?>">
<?php } ?>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_codabono" class="SIS_ABONOS_codabono">
<span<?php echo $SIS_ABONOS->codabono->ViewAttributes() ?>>
<?php echo $SIS_ABONOS->codabono->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codabono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codabono->FormValue) ?>">
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codabono" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codabono->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($SIS_ABONOS->abono->Visible) { // abono ?>
		<td data-name="abono"<?php echo $SIS_ABONOS->abono->CellAttributes() ?>>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_abono" class="form-group SIS_ABONOS_abono">
<input type="text" data-table="SIS_ABONOS" data-field="x_abono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->getPlaceHolder()) ?>" value="<?php echo $SIS_ABONOS->abono->EditValue ?>"<?php echo $SIS_ABONOS->abono->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_abono" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->OldValue) ?>">
<?php } ?>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_abono" class="form-group SIS_ABONOS_abono">
<input type="text" data-table="SIS_ABONOS" data-field="x_abono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->getPlaceHolder()) ?>" value="<?php echo $SIS_ABONOS->abono->EditValue ?>"<?php echo $SIS_ABONOS->abono->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $SIS_ABONOS_grid->RowCnt ?>_SIS_ABONOS_abono" class="SIS_ABONOS_abono">
<span<?php echo $SIS_ABONOS->abono->ViewAttributes() ?>>
<?php echo $SIS_ABONOS->abono->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_abono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->FormValue) ?>">
<input type="hidden" data-table="SIS_ABONOS" data-field="x_abono" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_ABONOS_grid->ListOptions->Render("body", "right", $SIS_ABONOS_grid->RowCnt);
?>
	</tr>
<?php if ($SIS_ABONOS->RowType == EW_ROWTYPE_ADD || $SIS_ABONOS->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fSIS_ABONOSgrid.UpdateOpts(<?php echo $SIS_ABONOS_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($SIS_ABONOS->CurrentAction <> "gridadd" || $SIS_ABONOS->CurrentMode == "copy")
		if (!$SIS_ABONOS_grid->Recordset->EOF) $SIS_ABONOS_grid->Recordset->MoveNext();
}
?>
<?php
	if ($SIS_ABONOS->CurrentMode == "add" || $SIS_ABONOS->CurrentMode == "copy" || $SIS_ABONOS->CurrentMode == "edit") {
		$SIS_ABONOS_grid->RowIndex = '$rowindex$';
		$SIS_ABONOS_grid->LoadDefaultValues();

		// Set row properties
		$SIS_ABONOS->ResetAttrs();
		$SIS_ABONOS->RowAttrs = array_merge($SIS_ABONOS->RowAttrs, array('data-rowindex'=>$SIS_ABONOS_grid->RowIndex, 'id'=>'r0_SIS_ABONOS', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($SIS_ABONOS->RowAttrs["class"], "ewTemplate");
		$SIS_ABONOS->RowType = EW_ROWTYPE_ADD;

		// Render row
		$SIS_ABONOS_grid->RenderRow();

		// Render list options
		$SIS_ABONOS_grid->RenderListOptions();
		$SIS_ABONOS_grid->StartRowCnt = 0;
?>
	<tr<?php echo $SIS_ABONOS->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_ABONOS_grid->ListOptions->Render("body", "left", $SIS_ABONOS_grid->RowIndex);
?>
	<?php if ($SIS_ABONOS->codapartado->Visible) { // codapartado ?>
		<td data-name="codapartado">
<?php if ($SIS_ABONOS->CurrentAction <> "F") { ?>
<?php if ($SIS_ABONOS->codapartado->getSessionValue() <> "") { ?>
<span id="el$rowindex$_SIS_ABONOS_codapartado" class="form-group SIS_ABONOS_codapartado">
<span<?php echo $SIS_ABONOS->codapartado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->codapartado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_SIS_ABONOS_codapartado" class="form-group SIS_ABONOS_codapartado">
<input type="text" data-table="SIS_ABONOS" data-field="x_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->getPlaceHolder()) ?>" value="<?php echo $SIS_ABONOS->codapartado->EditValue ?>"<?php echo $SIS_ABONOS->codapartado->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_SIS_ABONOS_codapartado" class="form-group SIS_ABONOS_codapartado">
<span<?php echo $SIS_ABONOS->codapartado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->codapartado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codapartado" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codapartado" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codapartado" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codapartado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_ABONOS->codabono->Visible) { // codabono ?>
		<td data-name="codabono">
<?php if ($SIS_ABONOS->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_SIS_ABONOS_codabono" class="form-group SIS_ABONOS_codabono">
<span<?php echo $SIS_ABONOS->codabono->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->codabono->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codabono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codabono->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_codabono" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_codabono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->codabono->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_ABONOS->abono->Visible) { // abono ?>
		<td data-name="abono">
<?php if ($SIS_ABONOS->CurrentAction <> "F") { ?>
<span id="el$rowindex$_SIS_ABONOS_abono" class="form-group SIS_ABONOS_abono">
<input type="text" data-table="SIS_ABONOS" data-field="x_abono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->getPlaceHolder()) ?>" value="<?php echo $SIS_ABONOS->abono->EditValue ?>"<?php echo $SIS_ABONOS->abono->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_SIS_ABONOS_abono" class="form-group SIS_ABONOS_abono">
<span<?php echo $SIS_ABONOS->abono->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ABONOS->abono->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_abono" name="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="x<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="SIS_ABONOS" data-field="x_abono" name="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" id="o<?php echo $SIS_ABONOS_grid->RowIndex ?>_abono" value="<?php echo ew_HtmlEncode($SIS_ABONOS->abono->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_ABONOS_grid->ListOptions->Render("body", "right", $SIS_ABONOS_grid->RowCnt);
?>
<script type="text/javascript">
fSIS_ABONOSgrid.UpdateOpts(<?php echo $SIS_ABONOS_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($SIS_ABONOS->CurrentMode == "add" || $SIS_ABONOS->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $SIS_ABONOS_grid->FormKeyCountName ?>" id="<?php echo $SIS_ABONOS_grid->FormKeyCountName ?>" value="<?php echo $SIS_ABONOS_grid->KeyCount ?>">
<?php echo $SIS_ABONOS_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($SIS_ABONOS->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $SIS_ABONOS_grid->FormKeyCountName ?>" id="<?php echo $SIS_ABONOS_grid->FormKeyCountName ?>" value="<?php echo $SIS_ABONOS_grid->KeyCount ?>">
<?php echo $SIS_ABONOS_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($SIS_ABONOS->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fSIS_ABONOSgrid">
</div>
<?php

// Close recordset
if ($SIS_ABONOS_grid->Recordset)
	$SIS_ABONOS_grid->Recordset->Close();
?>
<?php if ($SIS_ABONOS_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($SIS_ABONOS_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($SIS_ABONOS_grid->TotalRecs == 0 && $SIS_ABONOS->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_ABONOS_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($SIS_ABONOS->Export == "") { ?>
<script type="text/javascript">
fSIS_ABONOSgrid.Init();
</script>
<?php } ?>
<?php
$SIS_ABONOS_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$SIS_ABONOS_grid->Page_Terminate();
?>
