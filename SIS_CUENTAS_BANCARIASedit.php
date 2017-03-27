<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_CUENTAS_BANCARIASinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_CUENTAS_BANCARIAS_edit = NULL; // Initialize page object first

class cSIS_CUENTAS_BANCARIAS_edit extends cSIS_CUENTAS_BANCARIAS {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_CUENTAS_BANCARIAS';

	// Page object name
	var $PageObjName = 'SIS_CUENTAS_BANCARIAS_edit';

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

		// Table object (SIS_CUENTAS_BANCARIAS)
		if (!isset($GLOBALS["SIS_CUENTAS_BANCARIAS"]) || get_class($GLOBALS["SIS_CUENTAS_BANCARIAS"]) == "cSIS_CUENTAS_BANCARIAS") {
			$GLOBALS["SIS_CUENTAS_BANCARIAS"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_CUENTAS_BANCARIAS"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_CUENTAS_BANCARIAS', TRUE);

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
		$this->idcuenta->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $SIS_CUENTAS_BANCARIAS;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_CUENTAS_BANCARIAS);
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
		if (@$_GET["idcuenta"] <> "") {
			$this->idcuenta->setQueryStringValue($_GET["idcuenta"]);
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
		if ($this->idcuenta->CurrentValue == "")
			$this->Page_Terminate("SIS_CUENTAS_BANCARIASlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("SIS_CUENTAS_BANCARIASlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_CUENTAS_BANCARIASlist.php")
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
		if (!$this->idcuenta->FldIsDetailKey)
			$this->idcuenta->setFormValue($objForm->GetValue("x_idcuenta"));
		if (!$this->propietario->FldIsDetailKey) {
			$this->propietario->setFormValue($objForm->GetValue("x_propietario"));
		}
		if (!$this->cedula->FldIsDetailKey) {
			$this->cedula->setFormValue($objForm->GetValue("x_cedula"));
		}
		if (!$this->banco->FldIsDetailKey) {
			$this->banco->setFormValue($objForm->GetValue("x_banco"));
		}
		if (!$this->tipocuenta->FldIsDetailKey) {
			$this->tipocuenta->setFormValue($objForm->GetValue("x_tipocuenta"));
		}
		if (!$this->moneda->FldIsDetailKey) {
			$this->moneda->setFormValue($objForm->GetValue("x_moneda"));
		}
		if (!$this->numcuenta->FldIsDetailKey) {
			$this->numcuenta->setFormValue($objForm->GetValue("x_numcuenta"));
		}
		if (!$this->cuentacliente->FldIsDetailKey) {
			$this->cuentacliente->setFormValue($objForm->GetValue("x_cuentacliente"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idcuenta->CurrentValue = $this->idcuenta->FormValue;
		$this->propietario->CurrentValue = $this->propietario->FormValue;
		$this->cedula->CurrentValue = $this->cedula->FormValue;
		$this->banco->CurrentValue = $this->banco->FormValue;
		$this->tipocuenta->CurrentValue = $this->tipocuenta->FormValue;
		$this->moneda->CurrentValue = $this->moneda->FormValue;
		$this->numcuenta->CurrentValue = $this->numcuenta->FormValue;
		$this->cuentacliente->CurrentValue = $this->cuentacliente->FormValue;
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
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->cedula->setDbValue($rs->fields('cedula'));
		$this->banco->setDbValue($rs->fields('banco'));
		$this->tipocuenta->setDbValue($rs->fields('tipocuenta'));
		$this->moneda->setDbValue($rs->fields('moneda'));
		$this->numcuenta->setDbValue($rs->fields('numcuenta'));
		$this->cuentacliente->setDbValue($rs->fields('cuentacliente'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idcuenta->DbValue = $row['idcuenta'];
		$this->propietario->DbValue = $row['propietario'];
		$this->cedula->DbValue = $row['cedula'];
		$this->banco->DbValue = $row['banco'];
		$this->tipocuenta->DbValue = $row['tipocuenta'];
		$this->moneda->DbValue = $row['moneda'];
		$this->numcuenta->DbValue = $row['numcuenta'];
		$this->cuentacliente->DbValue = $row['cuentacliente'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idcuenta
		// propietario
		// cedula
		// banco
		// tipocuenta
		// moneda
		// numcuenta
		// cuentacliente

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idcuenta
		$this->idcuenta->ViewValue = $this->idcuenta->CurrentValue;
		$this->idcuenta->ViewCustomAttributes = "";

		// propietario
		$this->propietario->ViewValue = $this->propietario->CurrentValue;
		$this->propietario->ViewCustomAttributes = "";

		// cedula
		$this->cedula->ViewValue = $this->cedula->CurrentValue;
		$this->cedula->ViewCustomAttributes = "";

		// banco
		$this->banco->ViewValue = $this->banco->CurrentValue;
		$this->banco->ViewCustomAttributes = "";

		// tipocuenta
		if (strval($this->tipocuenta->CurrentValue) <> "") {
			$this->tipocuenta->ViewValue = $this->tipocuenta->OptionCaption($this->tipocuenta->CurrentValue);
		} else {
			$this->tipocuenta->ViewValue = NULL;
		}
		$this->tipocuenta->ViewCustomAttributes = "";

		// moneda
		if (strval($this->moneda->CurrentValue) <> "") {
			$this->moneda->ViewValue = $this->moneda->OptionCaption($this->moneda->CurrentValue);
		} else {
			$this->moneda->ViewValue = NULL;
		}
		$this->moneda->ViewCustomAttributes = "";

		// numcuenta
		$this->numcuenta->ViewValue = $this->numcuenta->CurrentValue;
		$this->numcuenta->ViewCustomAttributes = "";

		// cuentacliente
		$this->cuentacliente->ViewValue = $this->cuentacliente->CurrentValue;
		$this->cuentacliente->ViewCustomAttributes = "";

			// idcuenta
			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";
			$this->idcuenta->TooltipValue = "";

			// propietario
			$this->propietario->LinkCustomAttributes = "";
			$this->propietario->HrefValue = "";
			$this->propietario->TooltipValue = "";

			// cedula
			$this->cedula->LinkCustomAttributes = "";
			$this->cedula->HrefValue = "";
			$this->cedula->TooltipValue = "";

			// banco
			$this->banco->LinkCustomAttributes = "";
			$this->banco->HrefValue = "";
			$this->banco->TooltipValue = "";

			// tipocuenta
			$this->tipocuenta->LinkCustomAttributes = "";
			$this->tipocuenta->HrefValue = "";
			$this->tipocuenta->TooltipValue = "";

			// moneda
			$this->moneda->LinkCustomAttributes = "";
			$this->moneda->HrefValue = "";
			$this->moneda->TooltipValue = "";

			// numcuenta
			$this->numcuenta->LinkCustomAttributes = "";
			$this->numcuenta->HrefValue = "";
			$this->numcuenta->TooltipValue = "";

			// cuentacliente
			$this->cuentacliente->LinkCustomAttributes = "";
			$this->cuentacliente->HrefValue = "";
			$this->cuentacliente->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idcuenta
			$this->idcuenta->EditAttrs["class"] = "form-control";
			$this->idcuenta->EditCustomAttributes = "";
			$this->idcuenta->EditValue = $this->idcuenta->CurrentValue;
			$this->idcuenta->ViewCustomAttributes = "";

			// propietario
			$this->propietario->EditAttrs["class"] = "form-control";
			$this->propietario->EditCustomAttributes = "";
			$this->propietario->EditValue = ew_HtmlEncode($this->propietario->CurrentValue);
			$this->propietario->PlaceHolder = ew_RemoveHtml($this->propietario->FldCaption());

			// cedula
			$this->cedula->EditAttrs["class"] = "form-control";
			$this->cedula->EditCustomAttributes = "";
			$this->cedula->EditValue = ew_HtmlEncode($this->cedula->CurrentValue);
			$this->cedula->PlaceHolder = ew_RemoveHtml($this->cedula->FldCaption());

			// banco
			$this->banco->EditAttrs["class"] = "form-control";
			$this->banco->EditCustomAttributes = "";
			$this->banco->EditValue = ew_HtmlEncode($this->banco->CurrentValue);
			$this->banco->PlaceHolder = ew_RemoveHtml($this->banco->FldCaption());

			// tipocuenta
			$this->tipocuenta->EditAttrs["class"] = "form-control";
			$this->tipocuenta->EditCustomAttributes = "";
			$this->tipocuenta->EditValue = $this->tipocuenta->Options(TRUE);

			// moneda
			$this->moneda->EditAttrs["class"] = "form-control";
			$this->moneda->EditCustomAttributes = "";
			$this->moneda->EditValue = $this->moneda->Options(TRUE);

			// numcuenta
			$this->numcuenta->EditAttrs["class"] = "form-control";
			$this->numcuenta->EditCustomAttributes = "";
			$this->numcuenta->EditValue = ew_HtmlEncode($this->numcuenta->CurrentValue);
			$this->numcuenta->PlaceHolder = ew_RemoveHtml($this->numcuenta->FldCaption());

			// cuentacliente
			$this->cuentacliente->EditAttrs["class"] = "form-control";
			$this->cuentacliente->EditCustomAttributes = "";
			$this->cuentacliente->EditValue = ew_HtmlEncode($this->cuentacliente->CurrentValue);
			$this->cuentacliente->PlaceHolder = ew_RemoveHtml($this->cuentacliente->FldCaption());

			// Edit refer script
			// idcuenta

			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";

			// propietario
			$this->propietario->LinkCustomAttributes = "";
			$this->propietario->HrefValue = "";

			// cedula
			$this->cedula->LinkCustomAttributes = "";
			$this->cedula->HrefValue = "";

			// banco
			$this->banco->LinkCustomAttributes = "";
			$this->banco->HrefValue = "";

			// tipocuenta
			$this->tipocuenta->LinkCustomAttributes = "";
			$this->tipocuenta->HrefValue = "";

			// moneda
			$this->moneda->LinkCustomAttributes = "";
			$this->moneda->HrefValue = "";

			// numcuenta
			$this->numcuenta->LinkCustomAttributes = "";
			$this->numcuenta->HrefValue = "";

			// cuentacliente
			$this->cuentacliente->LinkCustomAttributes = "";
			$this->cuentacliente->HrefValue = "";
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
		if (!$this->propietario->FldIsDetailKey && !is_null($this->propietario->FormValue) && $this->propietario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->propietario->FldCaption(), $this->propietario->ReqErrMsg));
		}
		if (!$this->cedula->FldIsDetailKey && !is_null($this->cedula->FormValue) && $this->cedula->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cedula->FldCaption(), $this->cedula->ReqErrMsg));
		}
		if (!$this->banco->FldIsDetailKey && !is_null($this->banco->FormValue) && $this->banco->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->banco->FldCaption(), $this->banco->ReqErrMsg));
		}
		if (!$this->tipocuenta->FldIsDetailKey && !is_null($this->tipocuenta->FormValue) && $this->tipocuenta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipocuenta->FldCaption(), $this->tipocuenta->ReqErrMsg));
		}
		if (!$this->moneda->FldIsDetailKey && !is_null($this->moneda->FormValue) && $this->moneda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->moneda->FldCaption(), $this->moneda->ReqErrMsg));
		}
		if (!$this->numcuenta->FldIsDetailKey && !is_null($this->numcuenta->FormValue) && $this->numcuenta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->numcuenta->FldCaption(), $this->numcuenta->ReqErrMsg));
		}
		if (!$this->cuentacliente->FldIsDetailKey && !is_null($this->cuentacliente->FormValue) && $this->cuentacliente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuentacliente->FldCaption(), $this->cuentacliente->ReqErrMsg));
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

			// propietario
			$this->propietario->SetDbValueDef($rsnew, $this->propietario->CurrentValue, "", $this->propietario->ReadOnly);

			// cedula
			$this->cedula->SetDbValueDef($rsnew, $this->cedula->CurrentValue, "", $this->cedula->ReadOnly);

			// banco
			$this->banco->SetDbValueDef($rsnew, $this->banco->CurrentValue, "", $this->banco->ReadOnly);

			// tipocuenta
			$this->tipocuenta->SetDbValueDef($rsnew, $this->tipocuenta->CurrentValue, "", $this->tipocuenta->ReadOnly);

			// moneda
			$this->moneda->SetDbValueDef($rsnew, $this->moneda->CurrentValue, "", $this->moneda->ReadOnly);

			// numcuenta
			$this->numcuenta->SetDbValueDef($rsnew, $this->numcuenta->CurrentValue, "", $this->numcuenta->ReadOnly);

			// cuentacliente
			$this->cuentacliente->SetDbValueDef($rsnew, $this->cuentacliente->CurrentValue, "", $this->cuentacliente->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_CUENTAS_BANCARIASlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_CUENTAS_BANCARIAS_edit)) $SIS_CUENTAS_BANCARIAS_edit = new cSIS_CUENTAS_BANCARIAS_edit();

// Page init
$SIS_CUENTAS_BANCARIAS_edit->Page_Init();

// Page main
$SIS_CUENTAS_BANCARIAS_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_CUENTAS_BANCARIAS_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_CUENTAS_BANCARIASedit = new ew_Form("fSIS_CUENTAS_BANCARIASedit", "edit");

// Validate form
fSIS_CUENTAS_BANCARIASedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_propietario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->propietario->FldCaption(), $SIS_CUENTAS_BANCARIAS->propietario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cedula");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->cedula->FldCaption(), $SIS_CUENTAS_BANCARIAS->cedula->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_banco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->banco->FldCaption(), $SIS_CUENTAS_BANCARIAS->banco->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipocuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->tipocuenta->FldCaption(), $SIS_CUENTAS_BANCARIAS->tipocuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_moneda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->moneda->FldCaption(), $SIS_CUENTAS_BANCARIAS->moneda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numcuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->numcuenta->FldCaption(), $SIS_CUENTAS_BANCARIAS->numcuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cuentacliente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CUENTAS_BANCARIAS->cuentacliente->FldCaption(), $SIS_CUENTAS_BANCARIAS->cuentacliente->ReqErrMsg)) ?>");

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
fSIS_CUENTAS_BANCARIASedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_CUENTAS_BANCARIASedit.ValidateRequired = true;
<?php } else { ?>
fSIS_CUENTAS_BANCARIASedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_CUENTAS_BANCARIASedit.Lists["x_tipocuenta"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_CUENTAS_BANCARIASedit.Lists["x_tipocuenta"].Options = <?php echo json_encode($SIS_CUENTAS_BANCARIAS->tipocuenta->Options()) ?>;
fSIS_CUENTAS_BANCARIASedit.Lists["x_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_CUENTAS_BANCARIASedit.Lists["x_moneda"].Options = <?php echo json_encode($SIS_CUENTAS_BANCARIAS->moneda->Options()) ?>;

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
<?php $SIS_CUENTAS_BANCARIAS_edit->ShowPageHeader(); ?>
<?php
$SIS_CUENTAS_BANCARIAS_edit->ShowMessage();
?>
<form name="fSIS_CUENTAS_BANCARIASedit" id="fSIS_CUENTAS_BANCARIASedit" class="<?php echo $SIS_CUENTAS_BANCARIAS_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_CUENTAS_BANCARIAS_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_CUENTAS_BANCARIAS">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($SIS_CUENTAS_BANCARIAS->idcuenta->Visible) { // idcuenta ?>
	<div id="r_idcuenta" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_idcuenta" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_idcuenta">
<span<?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_idcuenta" name="x_idcuenta" id="x_idcuenta" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->idcuenta->CurrentValue) ?>">
<?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->propietario->Visible) { // propietario ?>
	<div id="r_propietario" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_propietario" for="x_propietario" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->propietario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->propietario->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_propietario">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_propietario" name="x_propietario" id="x_propietario" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->propietario->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->propietario->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->propietario->EditAttributes() ?>>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->propietario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->cedula->Visible) { // cedula ?>
	<div id="r_cedula" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_cedula" for="x_cedula" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->cedula->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->cedula->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_cedula">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_cedula" name="x_cedula" id="x_cedula" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->cedula->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->cedula->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->cedula->EditAttributes() ?>>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->cedula->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->banco->Visible) { // banco ?>
	<div id="r_banco" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_banco" for="x_banco" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->banco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->banco->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_banco">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_banco" name="x_banco" id="x_banco" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->banco->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->banco->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->banco->EditAttributes() ?>>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->banco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->tipocuenta->Visible) { // tipocuenta ?>
	<div id="r_tipocuenta" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_tipocuenta" for="x_tipocuenta" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_tipocuenta">
<select data-table="SIS_CUENTAS_BANCARIAS" data-field="x_tipocuenta" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValueSeparator) ? json_encode($SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValueSeparator) : $SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValueSeparator) ?>" id="x_tipocuenta" name="x_tipocuenta"<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->EditAttributes() ?>>
<?php
if (is_array($SIS_CUENTAS_BANCARIAS->tipocuenta->EditValue)) {
	$arwrk = $SIS_CUENTAS_BANCARIAS->tipocuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_CUENTAS_BANCARIAS->tipocuenta->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_CUENTAS_BANCARIAS->tipocuenta->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->tipocuenta->CurrentValue) ?>" selected><?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->moneda->Visible) { // moneda ?>
	<div id="r_moneda" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_moneda" for="x_moneda" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->moneda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->moneda->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_moneda">
<select data-table="SIS_CUENTAS_BANCARIAS" data-field="x_moneda" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_CUENTAS_BANCARIAS->moneda->DisplayValueSeparator) ? json_encode($SIS_CUENTAS_BANCARIAS->moneda->DisplayValueSeparator) : $SIS_CUENTAS_BANCARIAS->moneda->DisplayValueSeparator) ?>" id="x_moneda" name="x_moneda"<?php echo $SIS_CUENTAS_BANCARIAS->moneda->EditAttributes() ?>>
<?php
if (is_array($SIS_CUENTAS_BANCARIAS->moneda->EditValue)) {
	$arwrk = $SIS_CUENTAS_BANCARIAS->moneda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_CUENTAS_BANCARIAS->moneda->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->moneda->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_CUENTAS_BANCARIAS->moneda->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->moneda->CurrentValue) ?>" selected><?php echo $SIS_CUENTAS_BANCARIAS->moneda->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->moneda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->numcuenta->Visible) { // numcuenta ?>
	<div id="r_numcuenta" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_numcuenta" for="x_numcuenta" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_numcuenta">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_numcuenta" name="x_numcuenta" id="x_numcuenta" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->numcuenta->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->EditAttributes() ?>>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->cuentacliente->Visible) { // cuentacliente ?>
	<div id="r_cuentacliente" class="form-group">
		<label id="elh_SIS_CUENTAS_BANCARIAS_cuentacliente" for="x_cuentacliente" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->CellAttributes() ?>>
<span id="el_SIS_CUENTAS_BANCARIAS_cuentacliente">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_cuentacliente" name="x_cuentacliente" id="x_cuentacliente" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->cuentacliente->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->EditAttributes() ?>>
</span>
<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_CUENTAS_BANCARIAS_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_CUENTAS_BANCARIASedit.Init();
</script>
<?php
$SIS_CUENTAS_BANCARIAS_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_CUENTAS_BANCARIAS_edit->Page_Terminate();
?>
