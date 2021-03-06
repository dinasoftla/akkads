<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_COMPANIAinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_COMPANIA_add = NULL; // Initialize page object first

class cSIS_COMPANIA_add extends cSIS_COMPANIA {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_COMPANIA';

	// Page object name
	var $PageObjName = 'SIS_COMPANIA_add';

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

		// Table object (SIS_COMPANIA)
		if (!isset($GLOBALS["SIS_COMPANIA"]) || get_class($GLOBALS["SIS_COMPANIA"]) == "cSIS_COMPANIA") {
			$GLOBALS["SIS_COMPANIA"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_COMPANIA"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_COMPANIA', TRUE);

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
		global $EW_EXPORT, $SIS_COMPANIA;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_COMPANIA);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["codcompania"] != "") {
				$this->codcompania->setQueryStringValue($_GET["codcompania"]);
				$this->setKey("codcompania", $this->codcompania->CurrentValue); // Set up key
			} else {
				$this->setKey("codcompania", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("SIS_COMPANIAlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "SIS_COMPANIAlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "SIS_COMPANIAview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->codcompania->CurrentValue = NULL;
		$this->codcompania->OldValue = $this->codcompania->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->descuento->CurrentValue = NULL;
		$this->descuento->OldValue = $this->descuento->CurrentValue;
		$this->piefactura->CurrentValue = NULL;
		$this->piefactura->OldValue = $this->piefactura->CurrentValue;
		$this->leyendafactura->CurrentValue = NULL;
		$this->leyendafactura->OldValue = $this->leyendafactura->CurrentValue;
		$this->trasnporteenfactura->CurrentValue = NULL;
		$this->trasnporteenfactura->OldValue = $this->trasnporteenfactura->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codcompania->FldIsDetailKey) {
			$this->codcompania->setFormValue($objForm->GetValue("x_codcompania"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->descuento->FldIsDetailKey) {
			$this->descuento->setFormValue($objForm->GetValue("x_descuento"));
		}
		if (!$this->piefactura->FldIsDetailKey) {
			$this->piefactura->setFormValue($objForm->GetValue("x_piefactura"));
		}
		if (!$this->leyendafactura->FldIsDetailKey) {
			$this->leyendafactura->setFormValue($objForm->GetValue("x_leyendafactura"));
		}
		if (!$this->trasnporteenfactura->FldIsDetailKey) {
			$this->trasnporteenfactura->setFormValue($objForm->GetValue("x_trasnporteenfactura"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->codcompania->CurrentValue = $this->codcompania->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->descuento->CurrentValue = $this->descuento->FormValue;
		$this->piefactura->CurrentValue = $this->piefactura->FormValue;
		$this->leyendafactura->CurrentValue = $this->leyendafactura->FormValue;
		$this->trasnporteenfactura->CurrentValue = $this->trasnporteenfactura->FormValue;
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
		$this->codcompania->setDbValue($rs->fields('codcompania'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->piefactura->setDbValue($rs->fields('piefactura'));
		$this->leyendafactura->setDbValue($rs->fields('leyendafactura'));
		$this->trasnporteenfactura->setDbValue($rs->fields('trasnporteenfactura'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codcompania->DbValue = $row['codcompania'];
		$this->nombre->DbValue = $row['nombre'];
		$this->telefono->DbValue = $row['telefono'];
		$this->direccion->DbValue = $row['direccion'];
		$this->descuento->DbValue = $row['descuento'];
		$this->piefactura->DbValue = $row['piefactura'];
		$this->leyendafactura->DbValue = $row['leyendafactura'];
		$this->trasnporteenfactura->DbValue = $row['trasnporteenfactura'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("codcompania")) <> "")
			$this->codcompania->CurrentValue = $this->getKey("codcompania"); // codcompania
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->descuento->FormValue == $this->descuento->CurrentValue && is_numeric(ew_StrToFloat($this->descuento->CurrentValue)))
			$this->descuento->CurrentValue = ew_StrToFloat($this->descuento->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codcompania
		// nombre
		// telefono
		// direccion
		// descuento
		// piefactura
		// leyendafactura
		// trasnporteenfactura

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codcompania
		$this->codcompania->ViewValue = $this->codcompania->CurrentValue;
		$this->codcompania->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// descuento
		$this->descuento->ViewValue = $this->descuento->CurrentValue;
		$this->descuento->ViewCustomAttributes = "";

		// piefactura
		$this->piefactura->ViewValue = $this->piefactura->CurrentValue;
		$this->piefactura->ViewCustomAttributes = "";

		// leyendafactura
		$this->leyendafactura->ViewValue = $this->leyendafactura->CurrentValue;
		$this->leyendafactura->ViewCustomAttributes = "";

		// trasnporteenfactura
		if (strval($this->trasnporteenfactura->CurrentValue) <> "") {
			$this->trasnporteenfactura->ViewValue = $this->trasnporteenfactura->OptionCaption($this->trasnporteenfactura->CurrentValue);
		} else {
			$this->trasnporteenfactura->ViewValue = NULL;
		}
		$this->trasnporteenfactura->ViewCustomAttributes = "";

			// codcompania
			$this->codcompania->LinkCustomAttributes = "";
			$this->codcompania->HrefValue = "";
			$this->codcompania->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";
			$this->descuento->TooltipValue = "";

			// piefactura
			$this->piefactura->LinkCustomAttributes = "";
			$this->piefactura->HrefValue = "";
			$this->piefactura->TooltipValue = "";

			// leyendafactura
			$this->leyendafactura->LinkCustomAttributes = "";
			$this->leyendafactura->HrefValue = "";
			$this->leyendafactura->TooltipValue = "";

			// trasnporteenfactura
			$this->trasnporteenfactura->LinkCustomAttributes = "";
			$this->trasnporteenfactura->HrefValue = "";
			$this->trasnporteenfactura->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codcompania
			$this->codcompania->EditAttrs["class"] = "form-control";
			$this->codcompania->EditCustomAttributes = "";
			$this->codcompania->EditValue = ew_HtmlEncode($this->codcompania->CurrentValue);
			$this->codcompania->PlaceHolder = ew_RemoveHtml($this->codcompania->FldCaption());

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// descuento
			$this->descuento->EditAttrs["class"] = "form-control";
			$this->descuento->EditCustomAttributes = "";
			$this->descuento->EditValue = ew_HtmlEncode($this->descuento->CurrentValue);
			$this->descuento->PlaceHolder = ew_RemoveHtml($this->descuento->FldCaption());
			if (strval($this->descuento->EditValue) <> "" && is_numeric($this->descuento->EditValue)) $this->descuento->EditValue = ew_FormatNumber($this->descuento->EditValue, -2, -1, -2, 0);

			// piefactura
			$this->piefactura->EditAttrs["class"] = "form-control";
			$this->piefactura->EditCustomAttributes = "";
			$this->piefactura->EditValue = ew_HtmlEncode($this->piefactura->CurrentValue);
			$this->piefactura->PlaceHolder = ew_RemoveHtml($this->piefactura->FldCaption());

			// leyendafactura
			$this->leyendafactura->EditAttrs["class"] = "form-control";
			$this->leyendafactura->EditCustomAttributes = "";
			$this->leyendafactura->EditValue = ew_HtmlEncode($this->leyendafactura->CurrentValue);
			$this->leyendafactura->PlaceHolder = ew_RemoveHtml($this->leyendafactura->FldCaption());

			// trasnporteenfactura
			$this->trasnporteenfactura->EditAttrs["class"] = "form-control";
			$this->trasnporteenfactura->EditCustomAttributes = "";
			$this->trasnporteenfactura->EditValue = $this->trasnporteenfactura->Options(TRUE);

			// Add refer script
			// codcompania

			$this->codcompania->LinkCustomAttributes = "";
			$this->codcompania->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";

			// piefactura
			$this->piefactura->LinkCustomAttributes = "";
			$this->piefactura->HrefValue = "";

			// leyendafactura
			$this->leyendafactura->LinkCustomAttributes = "";
			$this->leyendafactura->HrefValue = "";

			// trasnporteenfactura
			$this->trasnporteenfactura->LinkCustomAttributes = "";
			$this->trasnporteenfactura->HrefValue = "";
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
		if (!$this->codcompania->FldIsDetailKey && !is_null($this->codcompania->FormValue) && $this->codcompania->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codcompania->FldCaption(), $this->codcompania->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->codcompania->FormValue)) {
			ew_AddMessage($gsFormError, $this->codcompania->FldErrMsg());
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->descuento->FormValue)) {
			ew_AddMessage($gsFormError, $this->descuento->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		if ($this->codcompania->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(codcompania = " . ew_AdjustSql($this->codcompania->CurrentValue, $this->DBID) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->codcompania->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->codcompania->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// codcompania
		$this->codcompania->SetDbValueDef($rsnew, $this->codcompania->CurrentValue, 0, FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// descuento
		$this->descuento->SetDbValueDef($rsnew, $this->descuento->CurrentValue, NULL, FALSE);

		// piefactura
		$this->piefactura->SetDbValueDef($rsnew, $this->piefactura->CurrentValue, NULL, FALSE);

		// leyendafactura
		$this->leyendafactura->SetDbValueDef($rsnew, $this->leyendafactura->CurrentValue, NULL, FALSE);

		// trasnporteenfactura
		$this->trasnporteenfactura->SetDbValueDef($rsnew, $this->trasnporteenfactura->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['codcompania']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_COMPANIAlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($SIS_COMPANIA_add)) $SIS_COMPANIA_add = new cSIS_COMPANIA_add();

// Page init
$SIS_COMPANIA_add->Page_Init();

// Page main
$SIS_COMPANIA_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_COMPANIA_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fSIS_COMPANIAadd = new ew_Form("fSIS_COMPANIAadd", "add");

// Validate form
fSIS_COMPANIAadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codcompania");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_COMPANIA->codcompania->FldCaption(), $SIS_COMPANIA->codcompania->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codcompania");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_COMPANIA->codcompania->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_COMPANIA->nombre->FldCaption(), $SIS_COMPANIA->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_descuento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_COMPANIA->descuento->FldErrMsg()) ?>");

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
fSIS_COMPANIAadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_COMPANIAadd.ValidateRequired = true;
<?php } else { ?>
fSIS_COMPANIAadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_COMPANIAadd.Lists["x_trasnporteenfactura"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_COMPANIAadd.Lists["x_trasnporteenfactura"].Options = <?php echo json_encode($SIS_COMPANIA->trasnporteenfactura->Options()) ?>;

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
<?php $SIS_COMPANIA_add->ShowPageHeader(); ?>
<?php
$SIS_COMPANIA_add->ShowMessage();
?>
<form name="fSIS_COMPANIAadd" id="fSIS_COMPANIAadd" class="<?php echo $SIS_COMPANIA_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_COMPANIA_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_COMPANIA_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_COMPANIA">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($SIS_COMPANIA->codcompania->Visible) { // codcompania ?>
	<div id="r_codcompania" class="form-group">
		<label id="elh_SIS_COMPANIA_codcompania" for="x_codcompania" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->codcompania->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->codcompania->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_codcompania">
<input type="text" data-table="SIS_COMPANIA" data-field="x_codcompania" name="x_codcompania" id="x_codcompania" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->codcompania->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->codcompania->EditValue ?>"<?php echo $SIS_COMPANIA->codcompania->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->codcompania->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_SIS_COMPANIA_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->nombre->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_nombre">
<input type="text" data-table="SIS_COMPANIA" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->nombre->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->nombre->EditValue ?>"<?php echo $SIS_COMPANIA->nombre->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->telefono->Visible) { // telefono ?>
	<div id="r_telefono" class="form-group">
		<label id="elh_SIS_COMPANIA_telefono" for="x_telefono" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->telefono->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->telefono->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_telefono">
<input type="text" data-table="SIS_COMPANIA" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->telefono->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->telefono->EditValue ?>"<?php echo $SIS_COMPANIA->telefono->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_SIS_COMPANIA_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->direccion->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_direccion">
<input type="text" data-table="SIS_COMPANIA" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->direccion->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->direccion->EditValue ?>"<?php echo $SIS_COMPANIA->direccion->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->descuento->Visible) { // descuento ?>
	<div id="r_descuento" class="form-group">
		<label id="elh_SIS_COMPANIA_descuento" for="x_descuento" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->descuento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->descuento->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_descuento">
<input type="text" data-table="SIS_COMPANIA" data-field="x_descuento" name="x_descuento" id="x_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->descuento->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->descuento->EditValue ?>"<?php echo $SIS_COMPANIA->descuento->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->descuento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->piefactura->Visible) { // piefactura ?>
	<div id="r_piefactura" class="form-group">
		<label id="elh_SIS_COMPANIA_piefactura" for="x_piefactura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->piefactura->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->piefactura->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_piefactura">
<input type="text" data-table="SIS_COMPANIA" data-field="x_piefactura" name="x_piefactura" id="x_piefactura" size="30" maxlength="1000" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->piefactura->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->piefactura->EditValue ?>"<?php echo $SIS_COMPANIA->piefactura->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->piefactura->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->leyendafactura->Visible) { // leyendafactura ?>
	<div id="r_leyendafactura" class="form-group">
		<label id="elh_SIS_COMPANIA_leyendafactura" for="x_leyendafactura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->leyendafactura->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->leyendafactura->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_leyendafactura">
<input type="text" data-table="SIS_COMPANIA" data-field="x_leyendafactura" name="x_leyendafactura" id="x_leyendafactura" size="30" maxlength="1000" placeholder="<?php echo ew_HtmlEncode($SIS_COMPANIA->leyendafactura->getPlaceHolder()) ?>" value="<?php echo $SIS_COMPANIA->leyendafactura->EditValue ?>"<?php echo $SIS_COMPANIA->leyendafactura->EditAttributes() ?>>
</span>
<?php echo $SIS_COMPANIA->leyendafactura->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_COMPANIA->trasnporteenfactura->Visible) { // trasnporteenfactura ?>
	<div id="r_trasnporteenfactura" class="form-group">
		<label id="elh_SIS_COMPANIA_trasnporteenfactura" for="x_trasnporteenfactura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_COMPANIA->trasnporteenfactura->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_COMPANIA->trasnporteenfactura->CellAttributes() ?>>
<span id="el_SIS_COMPANIA_trasnporteenfactura">
<select data-table="SIS_COMPANIA" data-field="x_trasnporteenfactura" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_COMPANIA->trasnporteenfactura->DisplayValueSeparator) ? json_encode($SIS_COMPANIA->trasnporteenfactura->DisplayValueSeparator) : $SIS_COMPANIA->trasnporteenfactura->DisplayValueSeparator) ?>" id="x_trasnporteenfactura" name="x_trasnporteenfactura"<?php echo $SIS_COMPANIA->trasnporteenfactura->EditAttributes() ?>>
<?php
if (is_array($SIS_COMPANIA->trasnporteenfactura->EditValue)) {
	$arwrk = $SIS_COMPANIA->trasnporteenfactura->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_COMPANIA->trasnporteenfactura->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_COMPANIA->trasnporteenfactura->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_COMPANIA->trasnporteenfactura->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_COMPANIA->trasnporteenfactura->CurrentValue) ?>" selected><?php echo $SIS_COMPANIA->trasnporteenfactura->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_COMPANIA->trasnporteenfactura->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_COMPANIA_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_COMPANIAadd.Init();
</script>
<?php
$SIS_COMPANIA_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_COMPANIA_add->Page_Terminate();
?>
