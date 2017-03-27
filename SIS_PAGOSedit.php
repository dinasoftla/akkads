<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_PAGOSinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_PAGOS_edit = NULL; // Initialize page object first

class cSIS_PAGOS_edit extends cSIS_PAGOS {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_PAGOS';

	// Page object name
	var $PageObjName = 'SIS_PAGOS_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (SIS_PAGOS)
		if (!isset($GLOBALS["SIS_PAGOS"]) || get_class($GLOBALS["SIS_PAGOS"]) == "cSIS_PAGOS") {
			$GLOBALS["SIS_PAGOS"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_PAGOS"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_PAGOS', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (SIS_USUARIOS)
		if (!isset($UserTable)) {
			$UserTable = new cSIS_USUARIOS();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->consecutivo->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $SIS_PAGOS;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_PAGOS);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["consecutivo"] <> "") {
			$this->consecutivo->setQueryStringValue($_GET["consecutivo"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->consecutivo->CurrentValue == "")
			$this->Page_Terminate("SIS_PAGOSlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("SIS_PAGOSlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_PAGOSlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->consecutivo->FldIsDetailKey)
			$this->consecutivo->setFormValue($objForm->GetValue("x_consecutivo"));
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->codproveedor->FldIsDetailKey) {
			$this->codproveedor->setFormValue($objForm->GetValue("x_codproveedor"));
		}
		if (!$this->factura->FldIsDetailKey) {
			$this->factura->setFormValue($objForm->GetValue("x_factura"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->cantidad->FldIsDetailKey) {
			$this->cantidad->setFormValue($objForm->GetValue("x_cantidad"));
		}
		if (!$this->monto->FldIsDetailKey) {
			$this->monto->setFormValue($objForm->GetValue("x_monto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->consecutivo->CurrentValue = $this->consecutivo->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
		$this->codproveedor->CurrentValue = $this->codproveedor->FormValue;
		$this->factura->CurrentValue = $this->factura->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->cantidad->CurrentValue = $this->cantidad->FormValue;
		$this->monto->CurrentValue = $this->monto->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->consecutivo->setDbValue($rs->fields('consecutivo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->codproveedor->setDbValue($rs->fields('codproveedor'));
		$this->factura->setDbValue($rs->fields('factura'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->monto->setDbValue($rs->fields('monto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->consecutivo->DbValue = $row['consecutivo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->tipo->DbValue = $row['tipo'];
		$this->codproveedor->DbValue = $row['codproveedor'];
		$this->factura->DbValue = $row['factura'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->monto->DbValue = $row['monto'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ew_StrToFloat($this->monto->CurrentValue)))
			$this->monto->CurrentValue = ew_StrToFloat($this->monto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// consecutivo
		// fecha
		// tipo
		// codproveedor
		// factura
		// descripcion
		// cantidad
		// monto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// consecutivo
		$this->consecutivo->ViewValue = $this->consecutivo->CurrentValue;
		$this->consecutivo->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// codproveedor
		if (strval($this->codproveedor->CurrentValue) <> "") {
			$sFilterWrk = "[codproveedor]" . ew_SearchString("=", $this->codproveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codproveedor], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_PROVEEDORES]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codproveedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codproveedor->ViewValue = $this->codproveedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codproveedor->ViewValue = $this->codproveedor->CurrentValue;
			}
		} else {
			$this->codproveedor->ViewValue = NULL;
		}
		$this->codproveedor->ViewCustomAttributes = "";

		// factura
		$this->factura->ViewValue = $this->factura->CurrentValue;
		$this->factura->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// monto
		$this->monto->ViewValue = $this->monto->CurrentValue;
		$this->monto->ViewCustomAttributes = "";

			// consecutivo
			$this->consecutivo->LinkCustomAttributes = "";
			$this->consecutivo->HrefValue = "";
			$this->consecutivo->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

			// codproveedor
			$this->codproveedor->LinkCustomAttributes = "";
			$this->codproveedor->HrefValue = "";
			$this->codproveedor->TooltipValue = "";

			// factura
			$this->factura->LinkCustomAttributes = "";
			$this->factura->HrefValue = "";
			$this->factura->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// monto
			$this->monto->LinkCustomAttributes = "";
			$this->monto->HrefValue = "";
			$this->monto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// consecutivo
			$this->consecutivo->EditAttrs["class"] = "form-control";
			$this->consecutivo->EditCustomAttributes = "";
			$this->consecutivo->EditValue = $this->consecutivo->CurrentValue;
			$this->consecutivo->ViewCustomAttributes = "";

			// fecha
			// tipo

			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(TRUE);

			// codproveedor
			$this->codproveedor->EditAttrs["class"] = "form-control";
			$this->codproveedor->EditCustomAttributes = "";
			if (trim(strval($this->codproveedor->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[codproveedor]" . ew_SearchString("=", $this->codproveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT [codproveedor], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[SIS_PROVEEDORES]";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->codproveedor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codproveedor->EditValue = $arwrk;

			// factura
			$this->factura->EditAttrs["class"] = "form-control";
			$this->factura->EditCustomAttributes = "";
			$this->factura->EditValue = ew_HtmlEncode($this->factura->CurrentValue);
			$this->factura->PlaceHolder = ew_RemoveHtml($this->factura->FldCaption());

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// cantidad
			$this->cantidad->EditAttrs["class"] = "form-control";
			$this->cantidad->EditCustomAttributes = "";
			$this->cantidad->EditValue = ew_HtmlEncode($this->cantidad->CurrentValue);
			$this->cantidad->PlaceHolder = ew_RemoveHtml($this->cantidad->FldCaption());

			// monto
			$this->monto->EditAttrs["class"] = "form-control";
			$this->monto->EditCustomAttributes = "";
			$this->monto->EditValue = ew_HtmlEncode($this->monto->CurrentValue);
			$this->monto->PlaceHolder = ew_RemoveHtml($this->monto->FldCaption());
			if (strval($this->monto->EditValue) <> "" && is_numeric($this->monto->EditValue)) $this->monto->EditValue = ew_FormatNumber($this->monto->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// consecutivo

			$this->consecutivo->LinkCustomAttributes = "";
			$this->consecutivo->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";

			// codproveedor
			$this->codproveedor->LinkCustomAttributes = "";
			$this->codproveedor->HrefValue = "";

			// factura
			$this->factura->LinkCustomAttributes = "";
			$this->factura->HrefValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";

			// monto
			$this->monto->LinkCustomAttributes = "";
			$this->monto->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->consecutivo->FormValue)) {
			ew_AddMessage($gsFormError, $this->consecutivo->FldErrMsg());
		}
		if (!$this->tipo->FldIsDetailKey && !is_null($this->tipo->FormValue) && $this->tipo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipo->FldCaption(), $this->tipo->ReqErrMsg));
		}
		if (!$this->codproveedor->FldIsDetailKey && !is_null($this->codproveedor->FormValue) && $this->codproveedor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codproveedor->FldCaption(), $this->codproveedor->ReqErrMsg));
		}
		if (!$this->factura->FldIsDetailKey && !is_null($this->factura->FormValue) && $this->factura->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->factura->FldCaption(), $this->factura->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->factura->FormValue)) {
			ew_AddMessage($gsFormError, $this->factura->FldErrMsg());
		}
		if (!$this->descripcion->FldIsDetailKey && !is_null($this->descripcion->FormValue) && $this->descripcion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->descripcion->FldCaption(), $this->descripcion->ReqErrMsg));
		}
		if (!$this->cantidad->FldIsDetailKey && !is_null($this->cantidad->FormValue) && $this->cantidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cantidad->FldCaption(), $this->cantidad->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cantidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidad->FldErrMsg());
		}
		if (!$this->monto->FldIsDetailKey && !is_null($this->monto->FormValue) && $this->monto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->monto->FldCaption(), $this->monto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->monto->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_CurrentDate(), "");
			$rsnew['fecha'] = &$this->fecha->DbValue;

			// tipo
			$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, "", $this->tipo->ReadOnly);

			// codproveedor
			$this->codproveedor->SetDbValueDef($rsnew, $this->codproveedor->CurrentValue, 0, $this->codproveedor->ReadOnly);

			// factura
			$this->factura->SetDbValueDef($rsnew, $this->factura->CurrentValue, 0, $this->factura->ReadOnly);

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, "", $this->descripcion->ReadOnly);

			// cantidad
			$this->cantidad->SetDbValueDef($rsnew, $this->cantidad->CurrentValue, 0, $this->cantidad->ReadOnly);

			// monto
			$this->monto->SetDbValueDef($rsnew, $this->monto->CurrentValue, 0, $this->monto->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_PAGOSlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_PAGOS_edit)) $SIS_PAGOS_edit = new cSIS_PAGOS_edit();

// Page init
$SIS_PAGOS_edit->Page_Init();

// Page main
$SIS_PAGOS_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_PAGOS_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_PAGOSedit = new ew_Form("fSIS_PAGOSedit", "edit");

// Validate form
fSIS_PAGOSedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_consecutivo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PAGOS->consecutivo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tipo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PAGOS->tipo->FldCaption(), $SIS_PAGOS->tipo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codproveedor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PAGOS->codproveedor->FldCaption(), $SIS_PAGOS->codproveedor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factura");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PAGOS->factura->FldCaption(), $SIS_PAGOS->factura->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PAGOS->factura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PAGOS->descripcion->FldCaption(), $SIS_PAGOS->descripcion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PAGOS->cantidad->FldCaption(), $SIS_PAGOS->cantidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PAGOS->cantidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PAGOS->monto->FldCaption(), $SIS_PAGOS->monto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PAGOS->monto->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fSIS_PAGOSedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_PAGOSedit.ValidateRequired = true;
<?php } else { ?>
fSIS_PAGOSedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_PAGOSedit.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_PAGOSedit.Lists["x_tipo"].Options = <?php echo json_encode($SIS_PAGOS->tipo->Options()) ?>;
fSIS_PAGOSedit.Lists["x_codproveedor"] = {"LinkField":"x_codproveedor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_PAGOS_edit->ShowPageHeader(); ?>
<?php
$SIS_PAGOS_edit->ShowMessage();
?>
<form name="fSIS_PAGOSedit" id="fSIS_PAGOSedit" class="<?php echo $SIS_PAGOS_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_PAGOS_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_PAGOS_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_PAGOS">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($SIS_PAGOS->consecutivo->Visible) { // consecutivo ?>
	<div id="r_consecutivo" class="form-group">
		<label id="elh_SIS_PAGOS_consecutivo" for="x_consecutivo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->consecutivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->consecutivo->CellAttributes() ?>>
<span id="el_SIS_PAGOS_consecutivo">
<span<?php echo $SIS_PAGOS->consecutivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PAGOS->consecutivo->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PAGOS" data-field="x_consecutivo" name="x_consecutivo" id="x_consecutivo" value="<?php echo ew_HtmlEncode($SIS_PAGOS->consecutivo->CurrentValue) ?>">
<?php echo $SIS_PAGOS->consecutivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PAGOS->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_SIS_PAGOS_tipo" for="x_tipo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->tipo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->tipo->CellAttributes() ?>>
<span id="el_SIS_PAGOS_tipo">
<select data-table="SIS_PAGOS" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_PAGOS->tipo->DisplayValueSeparator) ? json_encode($SIS_PAGOS->tipo->DisplayValueSeparator) : $SIS_PAGOS->tipo->DisplayValueSeparator) ?>" id="x_tipo" name="x_tipo"<?php echo $SIS_PAGOS->tipo->EditAttributes() ?>>
<?php
if (is_array($SIS_PAGOS->tipo->EditValue)) {
	$arwrk = $SIS_PAGOS->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_PAGOS->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_PAGOS->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_PAGOS->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_PAGOS->tipo->CurrentValue) ?>" selected><?php echo $SIS_PAGOS->tipo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_PAGOS->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PAGOS->codproveedor->Visible) { // codproveedor ?>
	<div id="r_codproveedor" class="form-group">
		<label id="elh_SIS_PAGOS_codproveedor" for="x_codproveedor" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->codproveedor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->codproveedor->CellAttributes() ?>>
<span id="el_SIS_PAGOS_codproveedor">
<select data-table="SIS_PAGOS" data-field="x_codproveedor" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_PAGOS->codproveedor->DisplayValueSeparator) ? json_encode($SIS_PAGOS->codproveedor->DisplayValueSeparator) : $SIS_PAGOS->codproveedor->DisplayValueSeparator) ?>" id="x_codproveedor" name="x_codproveedor"<?php echo $SIS_PAGOS->codproveedor->EditAttributes() ?>>
<?php
if (is_array($SIS_PAGOS->codproveedor->EditValue)) {
	$arwrk = $SIS_PAGOS->codproveedor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_PAGOS->codproveedor->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_PAGOS->codproveedor->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_PAGOS->codproveedor->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_PAGOS->codproveedor->CurrentValue) ?>" selected><?php echo $SIS_PAGOS->codproveedor->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $SIS_PAGOS->codproveedor->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_codproveedor',url:'SIS_PROVEEDORESaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_codproveedor"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $SIS_PAGOS->codproveedor->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT [codproveedor], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_PROVEEDORES]";
$sWhereWrk = "";
$SIS_PAGOS->codproveedor->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_PAGOS->codproveedor->LookupFilters += array("f0" => "[codproveedor] = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$SIS_PAGOS->Lookup_Selecting($SIS_PAGOS->codproveedor, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $SIS_PAGOS->codproveedor->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codproveedor" id="s_x_codproveedor" value="<?php echo $SIS_PAGOS->codproveedor->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_PAGOS->codproveedor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PAGOS->factura->Visible) { // factura ?>
	<div id="r_factura" class="form-group">
		<label id="elh_SIS_PAGOS_factura" for="x_factura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->factura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->factura->CellAttributes() ?>>
<span id="el_SIS_PAGOS_factura">
<input type="text" data-table="SIS_PAGOS" data-field="x_factura" name="x_factura" id="x_factura" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PAGOS->factura->getPlaceHolder()) ?>" value="<?php echo $SIS_PAGOS->factura->EditValue ?>"<?php echo $SIS_PAGOS->factura->EditAttributes() ?>>
</span>
<?php echo $SIS_PAGOS->factura->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PAGOS->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_SIS_PAGOS_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->descripcion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->descripcion->CellAttributes() ?>>
<span id="el_SIS_PAGOS_descripcion">
<input type="text" data-table="SIS_PAGOS" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($SIS_PAGOS->descripcion->getPlaceHolder()) ?>" value="<?php echo $SIS_PAGOS->descripcion->EditValue ?>"<?php echo $SIS_PAGOS->descripcion->EditAttributes() ?>>
</span>
<?php echo $SIS_PAGOS->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PAGOS->cantidad->Visible) { // cantidad ?>
	<div id="r_cantidad" class="form-group">
		<label id="elh_SIS_PAGOS_cantidad" for="x_cantidad" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->cantidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->cantidad->CellAttributes() ?>>
<span id="el_SIS_PAGOS_cantidad">
<input type="text" data-table="SIS_PAGOS" data-field="x_cantidad" name="x_cantidad" id="x_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PAGOS->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_PAGOS->cantidad->EditValue ?>"<?php echo $SIS_PAGOS->cantidad->EditAttributes() ?>>
</span>
<?php echo $SIS_PAGOS->cantidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PAGOS->monto->Visible) { // monto ?>
	<div id="r_monto" class="form-group">
		<label id="elh_SIS_PAGOS_monto" for="x_monto" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PAGOS->monto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PAGOS->monto->CellAttributes() ?>>
<span id="el_SIS_PAGOS_monto">
<input type="text" data-table="SIS_PAGOS" data-field="x_monto" name="x_monto" id="x_monto" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PAGOS->monto->getPlaceHolder()) ?>" value="<?php echo $SIS_PAGOS->monto->EditValue ?>"<?php echo $SIS_PAGOS->monto->EditAttributes() ?>>
</span>
<?php echo $SIS_PAGOS->monto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_PAGOS_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_PAGOSedit.Init();
</script>
<?php
$SIS_PAGOS_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_PAGOS_edit->Page_Terminate();
?>
