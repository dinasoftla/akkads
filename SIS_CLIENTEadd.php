<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_CLIENTEinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_CLIENTE_add = NULL; // Initialize page object first

class cSIS_CLIENTE_add extends cSIS_CLIENTE {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_CLIENTE';

	// Page object name
	var $PageObjName = 'SIS_CLIENTE_add';

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

		// Table object (SIS_CLIENTE)
		if (!isset($GLOBALS["SIS_CLIENTE"]) || get_class($GLOBALS["SIS_CLIENTE"]) == "cSIS_CLIENTE") {
			$GLOBALS["SIS_CLIENTE"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_CLIENTE"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_CLIENTE', TRUE);

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
		global $EW_EXPORT, $SIS_CLIENTE;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_CLIENTE);
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
			if (@$_GET["codcliente"] != "") {
				$this->codcliente->setQueryStringValue($_GET["codcliente"]);
				$this->setKey("codcliente", $this->codcliente->CurrentValue); // Set up key
			} else {
				$this->setKey("codcliente", ""); // Clear key
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
					$this->Page_Terminate("SIS_CLIENTElist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "SIS_CLIENTElist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "SIS_CLIENTEview.php")
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
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->telefonos->CurrentValue = NULL;
		$this->telefonos->OldValue = $this->telefonos->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->tipoprecio->CurrentValue = NULL;
		$this->tipoprecio->OldValue = $this->tipoprecio->CurrentValue;
		$this->fecultimamod->CurrentValue = NULL;
		$this->fecultimamod->OldValue = $this->fecultimamod->CurrentValue;
		$this->usuarioultimamod->CurrentValue = NULL;
		$this->usuarioultimamod->OldValue = $this->usuarioultimamod->CurrentValue;
		$this->tipo->CurrentValue = NULL;
		$this->tipo->OldValue = $this->tipo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->telefonos->FldIsDetailKey) {
			$this->telefonos->setFormValue($objForm->GetValue("x_telefonos"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->tipoprecio->FldIsDetailKey) {
			$this->tipoprecio->setFormValue($objForm->GetValue("x_tipoprecio"));
		}
		if (!$this->fecultimamod->FldIsDetailKey) {
			$this->fecultimamod->setFormValue($objForm->GetValue("x_fecultimamod"));
		}
		if (!$this->usuarioultimamod->FldIsDetailKey) {
			$this->usuarioultimamod->setFormValue($objForm->GetValue("x_usuarioultimamod"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->telefonos->CurrentValue = $this->telefonos->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->tipoprecio->CurrentValue = $this->tipoprecio->FormValue;
		$this->fecultimamod->CurrentValue = $this->fecultimamod->FormValue;
		$this->usuarioultimamod->CurrentValue = $this->usuarioultimamod->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
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
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->telefonos->setDbValue($rs->fields('telefonos'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->tipoprecio->setDbValue($rs->fields('tipoprecio'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->tipo->setDbValue($rs->fields('tipo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codcliente->DbValue = $row['codcliente'];
		$this->nombre->DbValue = $row['nombre'];
		$this->telefonos->DbValue = $row['telefonos'];
		$this->_email->DbValue = $row['email'];
		$this->direccion->DbValue = $row['direccion'];
		$this->tipoprecio->DbValue = $row['tipoprecio'];
		$this->fecultimamod->DbValue = $row['fecultimamod'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
		$this->tipo->DbValue = $row['tipo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("codcliente")) <> "")
			$this->codcliente->CurrentValue = $this->getKey("codcliente"); // codcliente
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// codcliente
		// nombre
		// telefonos
		// email
		// direccion
		// tipoprecio
		// fecultimamod
		// usuarioultimamod
		// tipo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codcliente
		$this->codcliente->ViewValue = $this->codcliente->CurrentValue;
		$this->codcliente->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// telefonos
		$this->telefonos->ViewValue = $this->telefonos->CurrentValue;
		$this->telefonos->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// tipoprecio
		if (strval($this->tipoprecio->CurrentValue) <> "") {
			$this->tipoprecio->ViewValue = $this->tipoprecio->OptionCaption($this->tipoprecio->CurrentValue);
		} else {
			$this->tipoprecio->ViewValue = NULL;
		}
		$this->tipoprecio->ViewCustomAttributes = "";

		// fecultimamod
		$this->fecultimamod->ViewValue = $this->fecultimamod->CurrentValue;
		$this->fecultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// telefonos
			$this->telefonos->LinkCustomAttributes = "";
			$this->telefonos->HrefValue = "";
			$this->telefonos->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// tipoprecio
			$this->tipoprecio->LinkCustomAttributes = "";
			$this->tipoprecio->HrefValue = "";
			$this->tipoprecio->TooltipValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";
			$this->fecultimamod->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// telefonos
			$this->telefonos->EditAttrs["class"] = "form-control";
			$this->telefonos->EditCustomAttributes = "";
			$this->telefonos->EditValue = ew_HtmlEncode($this->telefonos->CurrentValue);
			$this->telefonos->PlaceHolder = ew_RemoveHtml($this->telefonos->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// tipoprecio
			$this->tipoprecio->EditAttrs["class"] = "form-control";
			$this->tipoprecio->EditCustomAttributes = "";
			$this->tipoprecio->EditValue = $this->tipoprecio->Options(TRUE);

			// fecultimamod
			// usuarioultimamod
			// tipo

			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(TRUE);

			// Add refer script
			// nombre

			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// telefonos
			$this->telefonos->LinkCustomAttributes = "";
			$this->telefonos->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// tipoprecio
			$this->tipoprecio->LinkCustomAttributes = "";
			$this->tipoprecio->HrefValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
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
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->telefonos->FldIsDetailKey && !is_null($this->telefonos->FormValue) && $this->telefonos->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telefonos->FldCaption(), $this->telefonos->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
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
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// telefonos
		$this->telefonos->SetDbValueDef($rsnew, $this->telefonos->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// tipoprecio
		$this->tipoprecio->SetDbValueDef($rsnew, $this->tipoprecio->CurrentValue, NULL, FALSE);

		// fecultimamod
		$this->fecultimamod->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['fecultimamod'] = &$this->fecultimamod->DbValue;

		// usuarioultimamod
		$this->usuarioultimamod->SetDbValueDef($rsnew, CurrentUserName(), NULL);
		$rsnew['usuarioultimamod'] = &$this->usuarioultimamod->DbValue;

		// tipo
		$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->codcliente->setDbValue($conn->Insert_ID());
				$rsnew['codcliente'] = $this->codcliente->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_CLIENTElist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_CLIENTE_add)) $SIS_CLIENTE_add = new cSIS_CLIENTE_add();

// Page init
$SIS_CLIENTE_add->Page_Init();

// Page main
$SIS_CLIENTE_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_CLIENTE_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fSIS_CLIENTEadd = new ew_Form("fSIS_CLIENTEadd", "add");

// Validate form
fSIS_CLIENTEadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CLIENTE->nombre->FldCaption(), $SIS_CLIENTE->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefonos");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CLIENTE->telefonos->FldCaption(), $SIS_CLIENTE->telefonos->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_CLIENTE->_email->FldCaption(), $SIS_CLIENTE->_email->ReqErrMsg)) ?>");

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
fSIS_CLIENTEadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_CLIENTEadd.ValidateRequired = true;
<?php } else { ?>
fSIS_CLIENTEadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_CLIENTEadd.Lists["x_tipoprecio"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_CLIENTEadd.Lists["x_tipoprecio"].Options = <?php echo json_encode($SIS_CLIENTE->tipoprecio->Options()) ?>;
fSIS_CLIENTEadd.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_CLIENTEadd.Lists["x_tipo"].Options = <?php echo json_encode($SIS_CLIENTE->tipo->Options()) ?>;

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
<?php $SIS_CLIENTE_add->ShowPageHeader(); ?>
<?php
$SIS_CLIENTE_add->ShowMessage();
?>
<form name="fSIS_CLIENTEadd" id="fSIS_CLIENTEadd" class="<?php echo $SIS_CLIENTE_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_CLIENTE_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_CLIENTE_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_CLIENTE">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($SIS_CLIENTE->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_SIS_CLIENTE_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CLIENTE->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CLIENTE->nombre->CellAttributes() ?>>
<span id="el_SIS_CLIENTE_nombre">
<input type="text" data-table="SIS_CLIENTE" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($SIS_CLIENTE->nombre->getPlaceHolder()) ?>" value="<?php echo $SIS_CLIENTE->nombre->EditValue ?>"<?php echo $SIS_CLIENTE->nombre->EditAttributes() ?>>
</span>
<?php echo $SIS_CLIENTE->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CLIENTE->telefonos->Visible) { // telefonos ?>
	<div id="r_telefonos" class="form-group">
		<label id="elh_SIS_CLIENTE_telefonos" for="x_telefonos" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CLIENTE->telefonos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CLIENTE->telefonos->CellAttributes() ?>>
<span id="el_SIS_CLIENTE_telefonos">
<input type="text" data-table="SIS_CLIENTE" data-field="x_telefonos" name="x_telefonos" id="x_telefonos" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($SIS_CLIENTE->telefonos->getPlaceHolder()) ?>" value="<?php echo $SIS_CLIENTE->telefonos->EditValue ?>"<?php echo $SIS_CLIENTE->telefonos->EditAttributes() ?>>
</span>
<?php echo $SIS_CLIENTE->telefonos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CLIENTE->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_SIS_CLIENTE__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CLIENTE->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CLIENTE->_email->CellAttributes() ?>>
<span id="el_SIS_CLIENTE__email">
<input type="text" data-table="SIS_CLIENTE" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($SIS_CLIENTE->_email->getPlaceHolder()) ?>" value="<?php echo $SIS_CLIENTE->_email->EditValue ?>"<?php echo $SIS_CLIENTE->_email->EditAttributes() ?>>
</span>
<?php echo $SIS_CLIENTE->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CLIENTE->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_SIS_CLIENTE_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CLIENTE->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CLIENTE->direccion->CellAttributes() ?>>
<span id="el_SIS_CLIENTE_direccion">
<input type="text" data-table="SIS_CLIENTE" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($SIS_CLIENTE->direccion->getPlaceHolder()) ?>" value="<?php echo $SIS_CLIENTE->direccion->EditValue ?>"<?php echo $SIS_CLIENTE->direccion->EditAttributes() ?>>
</span>
<?php echo $SIS_CLIENTE->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CLIENTE->tipoprecio->Visible) { // tipoprecio ?>
	<div id="r_tipoprecio" class="form-group">
		<label id="elh_SIS_CLIENTE_tipoprecio" for="x_tipoprecio" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CLIENTE->tipoprecio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CLIENTE->tipoprecio->CellAttributes() ?>>
<span id="el_SIS_CLIENTE_tipoprecio">
<select data-table="SIS_CLIENTE" data-field="x_tipoprecio" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_CLIENTE->tipoprecio->DisplayValueSeparator) ? json_encode($SIS_CLIENTE->tipoprecio->DisplayValueSeparator) : $SIS_CLIENTE->tipoprecio->DisplayValueSeparator) ?>" id="x_tipoprecio" name="x_tipoprecio"<?php echo $SIS_CLIENTE->tipoprecio->EditAttributes() ?>>
<?php
if (is_array($SIS_CLIENTE->tipoprecio->EditValue)) {
	$arwrk = $SIS_CLIENTE->tipoprecio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_CLIENTE->tipoprecio->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_CLIENTE->tipoprecio->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_CLIENTE->tipoprecio->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_CLIENTE->tipoprecio->CurrentValue) ?>" selected><?php echo $SIS_CLIENTE->tipoprecio->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_CLIENTE->tipoprecio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_CLIENTE->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_SIS_CLIENTE_tipo" for="x_tipo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_CLIENTE->tipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_CLIENTE->tipo->CellAttributes() ?>>
<span id="el_SIS_CLIENTE_tipo">
<select data-table="SIS_CLIENTE" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_CLIENTE->tipo->DisplayValueSeparator) ? json_encode($SIS_CLIENTE->tipo->DisplayValueSeparator) : $SIS_CLIENTE->tipo->DisplayValueSeparator) ?>" id="x_tipo" name="x_tipo"<?php echo $SIS_CLIENTE->tipo->EditAttributes() ?>>
<?php
if (is_array($SIS_CLIENTE->tipo->EditValue)) {
	$arwrk = $SIS_CLIENTE->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_CLIENTE->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_CLIENTE->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_CLIENTE->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_CLIENTE->tipo->CurrentValue) ?>" selected><?php echo $SIS_CLIENTE->tipo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_CLIENTE->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_CLIENTE_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_CLIENTEadd.Init();
</script>
<?php
$SIS_CLIENTE_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_CLIENTE_add->Page_Terminate();
?>
