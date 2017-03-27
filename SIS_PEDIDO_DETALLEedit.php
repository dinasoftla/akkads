<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_PEDIDO_DETALLEinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "SIS_PEDIDOinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_PEDIDO_DETALLE_edit = NULL; // Initialize page object first

class cSIS_PEDIDO_DETALLE_edit extends cSIS_PEDIDO_DETALLE {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_PEDIDO_DETALLE';

	// Page object name
	var $PageObjName = 'SIS_PEDIDO_DETALLE_edit';

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

		// Table object (SIS_PEDIDO_DETALLE)
		if (!isset($GLOBALS["SIS_PEDIDO_DETALLE"]) || get_class($GLOBALS["SIS_PEDIDO_DETALLE"]) == "cSIS_PEDIDO_DETALLE") {
			$GLOBALS["SIS_PEDIDO_DETALLE"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_PEDIDO_DETALLE"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Table object (SIS_PEDIDO)
		if (!isset($GLOBALS['SIS_PEDIDO'])) $GLOBALS['SIS_PEDIDO'] = new cSIS_PEDIDO();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_PEDIDO_DETALLE', TRUE);

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
		$this->numdetpedido->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $SIS_PEDIDO_DETALLE;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_PEDIDO_DETALLE);
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
		if (@$_GET["numpedido"] <> "") {
			$this->numpedido->setQueryStringValue($_GET["numpedido"]);
		}
		if (@$_GET["numdetpedido"] <> "") {
			$this->numdetpedido->setQueryStringValue($_GET["numdetpedido"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

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
		if ($this->numpedido->CurrentValue == "")
			$this->Page_Terminate("SIS_PEDIDO_DETALLElist.php"); // Invalid key, return to list
		if ($this->numdetpedido->CurrentValue == "")
			$this->Page_Terminate("SIS_PEDIDO_DETALLElist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("SIS_PEDIDO_DETALLElist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_PEDIDO_DETALLElist.php")
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
		if (!$this->numpedido->FldIsDetailKey) {
			$this->numpedido->setFormValue($objForm->GetValue("x_numpedido"));
		}
		if (!$this->numdetpedido->FldIsDetailKey)
			$this->numdetpedido->setFormValue($objForm->GetValue("x_numdetpedido"));
		if (!$this->codarticulo->FldIsDetailKey) {
			$this->codarticulo->setFormValue($objForm->GetValue("x_codarticulo"));
		}
		if (!$this->cantidad->FldIsDetailKey) {
			$this->cantidad->setFormValue($objForm->GetValue("x_cantidad"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->numpedido->CurrentValue = $this->numpedido->FormValue;
		$this->numdetpedido->CurrentValue = $this->numdetpedido->FormValue;
		$this->codarticulo->CurrentValue = $this->codarticulo->FormValue;
		$this->cantidad->CurrentValue = $this->cantidad->FormValue;
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
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->numdetpedido->setDbValue($rs->fields('numdetpedido'));
		$this->codarticulo->setDbValue($rs->fields('codarticulo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->preciounitario->setDbValue($rs->fields('preciounitario'));
		$this->total->setDbValue($rs->fields('total'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->numpedido->DbValue = $row['numpedido'];
		$this->numdetpedido->DbValue = $row['numdetpedido'];
		$this->codarticulo->DbValue = $row['codarticulo'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->preciounitario->DbValue = $row['preciounitario'];
		$this->total->DbValue = $row['total'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// numpedido
		// numdetpedido
		// codarticulo
		// descripcion
		// cantidad
		// preciounitario
		// total

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// numpedido
		$this->numpedido->ViewValue = $this->numpedido->CurrentValue;
		$this->numpedido->ViewCustomAttributes = "";

		// numdetpedido
		$this->numdetpedido->ViewValue = $this->numdetpedido->CurrentValue;
		$this->numdetpedido->ViewCustomAttributes = "";

		// codarticulo
		$this->codarticulo->ViewValue = $this->codarticulo->CurrentValue;
		$this->codarticulo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// preciounitario
		$this->preciounitario->ViewValue = $this->preciounitario->CurrentValue;
		$this->preciounitario->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

			// numpedido
			$this->numpedido->LinkCustomAttributes = "";
			$this->numpedido->HrefValue = "";
			$this->numpedido->TooltipValue = "";

			// numdetpedido
			$this->numdetpedido->LinkCustomAttributes = "";
			$this->numdetpedido->HrefValue = "";
			$this->numdetpedido->TooltipValue = "";

			// codarticulo
			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";
			$this->codarticulo->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// numpedido
			$this->numpedido->EditAttrs["class"] = "form-control";
			$this->numpedido->EditCustomAttributes = "";
			$this->numpedido->EditValue = $this->numpedido->CurrentValue;
			$this->numpedido->ViewCustomAttributes = "";

			// numdetpedido
			$this->numdetpedido->EditAttrs["class"] = "form-control";
			$this->numdetpedido->EditCustomAttributes = "";
			$this->numdetpedido->EditValue = $this->numdetpedido->CurrentValue;
			$this->numdetpedido->ViewCustomAttributes = "";

			// codarticulo
			$this->codarticulo->EditAttrs["class"] = "form-control";
			$this->codarticulo->EditCustomAttributes = "";
			$this->codarticulo->EditValue = ew_HtmlEncode($this->codarticulo->CurrentValue);
			$this->codarticulo->PlaceHolder = ew_RemoveHtml($this->codarticulo->FldCaption());

			// cantidad
			$this->cantidad->EditAttrs["class"] = "form-control";
			$this->cantidad->EditCustomAttributes = "";
			$this->cantidad->EditValue = ew_HtmlEncode($this->cantidad->CurrentValue);
			$this->cantidad->PlaceHolder = ew_RemoveHtml($this->cantidad->FldCaption());

			// Edit refer script
			// numpedido

			$this->numpedido->LinkCustomAttributes = "";
			$this->numpedido->HrefValue = "";

			// numdetpedido
			$this->numdetpedido->LinkCustomAttributes = "";
			$this->numdetpedido->HrefValue = "";

			// codarticulo
			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
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
		if (!$this->numpedido->FldIsDetailKey && !is_null($this->numpedido->FormValue) && $this->numpedido->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->numpedido->FldCaption(), $this->numpedido->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->numpedido->FormValue)) {
			ew_AddMessage($gsFormError, $this->numpedido->FldErrMsg());
		}
		if (!$this->codarticulo->FldIsDetailKey && !is_null($this->codarticulo->FormValue) && $this->codarticulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codarticulo->FldCaption(), $this->codarticulo->ReqErrMsg));
		}
		if (!$this->cantidad->FldIsDetailKey && !is_null($this->cantidad->FormValue) && $this->cantidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cantidad->FldCaption(), $this->cantidad->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cantidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidad->FldErrMsg());
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

			// numpedido
			// codarticulo

			$this->codarticulo->SetDbValueDef($rsnew, $this->codarticulo->CurrentValue, NULL, $this->codarticulo->ReadOnly);

			// cantidad
			$this->cantidad->SetDbValueDef($rsnew, $this->cantidad->CurrentValue, NULL, $this->cantidad->ReadOnly);

			// Check referential integrity for master table 'SIS_PEDIDO'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_SIS_PEDIDO();
			$KeyValue = isset($rsnew['numpedido']) ? $rsnew['numpedido'] : $rsold['numpedido'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@numpedido@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["SIS_PEDIDO"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "SIS_PEDIDO", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "SIS_PEDIDO") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_numpedido"] <> "") {
					$GLOBALS["SIS_PEDIDO"]->numpedido->setQueryStringValue($_GET["fk_numpedido"]);
					$this->numpedido->setQueryStringValue($GLOBALS["SIS_PEDIDO"]->numpedido->QueryStringValue);
					$this->numpedido->setSessionValue($this->numpedido->QueryStringValue);
					if (!is_numeric($GLOBALS["SIS_PEDIDO"]->numpedido->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "SIS_PEDIDO") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_numpedido"] <> "") {
					$GLOBALS["SIS_PEDIDO"]->numpedido->setFormValue($_POST["fk_numpedido"]);
					$this->numpedido->setFormValue($GLOBALS["SIS_PEDIDO"]->numpedido->FormValue);
					$this->numpedido->setSessionValue($this->numpedido->FormValue);
					if (!is_numeric($GLOBALS["SIS_PEDIDO"]->numpedido->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "SIS_PEDIDO") {
				if ($this->numpedido->CurrentValue == "") $this->numpedido->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_PEDIDO_DETALLElist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_PEDIDO_DETALLE_edit)) $SIS_PEDIDO_DETALLE_edit = new cSIS_PEDIDO_DETALLE_edit();

// Page init
$SIS_PEDIDO_DETALLE_edit->Page_Init();

// Page main
$SIS_PEDIDO_DETALLE_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_PEDIDO_DETALLE_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_PEDIDO_DETALLEedit = new ew_Form("fSIS_PEDIDO_DETALLEedit", "edit");

// Validate form
fSIS_PEDIDO_DETALLEedit.Validate = function() {
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
fSIS_PEDIDO_DETALLEedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_PEDIDO_DETALLEedit.ValidateRequired = true;
<?php } else { ?>
fSIS_PEDIDO_DETALLEedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $SIS_PEDIDO_DETALLE_edit->ShowPageHeader(); ?>
<?php
$SIS_PEDIDO_DETALLE_edit->ShowMessage();
?>
<form name="fSIS_PEDIDO_DETALLEedit" id="fSIS_PEDIDO_DETALLEedit" class="<?php echo $SIS_PEDIDO_DETALLE_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_PEDIDO_DETALLE_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_PEDIDO_DETALLE_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_PEDIDO_DETALLE">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($SIS_PEDIDO_DETALLE->getCurrentMasterTable() == "SIS_PEDIDO") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="SIS_PEDIDO">
<input type="hidden" name="fk_numpedido" value="<?php echo $SIS_PEDIDO_DETALLE->numpedido->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($SIS_PEDIDO_DETALLE->numpedido->Visible) { // numpedido ?>
	<div id="r_numpedido" class="form-group">
		<label id="elh_SIS_PEDIDO_DETALLE_numpedido" for="x_numpedido" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO_DETALLE->numpedido->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO_DETALLE->numpedido->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_DETALLE_numpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numpedido->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numpedido" name="x_numpedido" id="x_numpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numpedido->CurrentValue) ?>">
<?php echo $SIS_PEDIDO_DETALLE->numpedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->numdetpedido->Visible) { // numdetpedido ?>
	<div id="r_numdetpedido" class="form-group">
		<label id="elh_SIS_PEDIDO_DETALLE_numdetpedido" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO_DETALLE->numdetpedido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_DETALLE_numdetpedido">
<span<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_PEDIDO_DETALLE->numdetpedido->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_PEDIDO_DETALLE" data-field="x_numdetpedido" name="x_numdetpedido" id="x_numdetpedido" value="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->numdetpedido->CurrentValue) ?>">
<?php echo $SIS_PEDIDO_DETALLE->numdetpedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->codarticulo->Visible) { // codarticulo ?>
	<div id="r_codarticulo" class="form-group">
		<label id="elh_SIS_PEDIDO_DETALLE_codarticulo" for="x_codarticulo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO_DETALLE->codarticulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO_DETALLE->codarticulo->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_DETALLE_codarticulo">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_codarticulo" name="x_codarticulo" id="x_codarticulo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->codarticulo->EditAttributes() ?>>
</span>
<?php echo $SIS_PEDIDO_DETALLE->codarticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PEDIDO_DETALLE->cantidad->Visible) { // cantidad ?>
	<div id="r_cantidad" class="form-group">
		<label id="elh_SIS_PEDIDO_DETALLE_cantidad" for="x_cantidad" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO_DETALLE->cantidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO_DETALLE->cantidad->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_DETALLE_cantidad">
<input type="text" data-table="SIS_PEDIDO_DETALLE" data-field="x_cantidad" name="x_cantidad" id="x_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO_DETALLE->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditValue ?>"<?php echo $SIS_PEDIDO_DETALLE->cantidad->EditAttributes() ?>>
</span>
<?php echo $SIS_PEDIDO_DETALLE->cantidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_PEDIDO_DETALLE_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_PEDIDO_DETALLEedit.Init();
</script>
<?php
$SIS_PEDIDO_DETALLE_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_PEDIDO_DETALLE_edit->Page_Terminate();
?>
