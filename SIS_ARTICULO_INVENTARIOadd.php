<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_ARTICULO_INVENTARIOinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_ARTICULO_INVENTARIO_add = NULL; // Initialize page object first

class cSIS_ARTICULO_INVENTARIO_add extends cSIS_ARTICULO_INVENTARIO {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_ARTICULO_INVENTARIO';

	// Page object name
	var $PageObjName = 'SIS_ARTICULO_INVENTARIO_add';

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

		// Table object (SIS_ARTICULO_INVENTARIO)
		if (!isset($GLOBALS["SIS_ARTICULO_INVENTARIO"]) || get_class($GLOBALS["SIS_ARTICULO_INVENTARIO"]) == "cSIS_ARTICULO_INVENTARIO") {
			$GLOBALS["SIS_ARTICULO_INVENTARIO"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_ARTICULO_INVENTARIO"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_ARTICULO_INVENTARIO', TRUE);

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
		global $EW_EXPORT, $SIS_ARTICULO_INVENTARIO;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_ARTICULO_INVENTARIO);
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
			if (@$_GET["codarticulo"] != "") {
				$this->codarticulo->setQueryStringValue($_GET["codarticulo"]);
				$this->setKey("codarticulo", $this->codarticulo->CurrentValue); // Set up key
			} else {
				$this->setKey("codarticulo", ""); // Clear key
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
					$this->Page_Terminate("SIS_ARTICULO_INVENTARIOlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "SIS_ARTICULO_INVENTARIOlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "SIS_ARTICULO_INVENTARIOview.php")
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
		$this->codarticulo->CurrentValue = NULL;
		$this->codarticulo->OldValue = $this->codarticulo->CurrentValue;
		$this->canarticulo->CurrentValue = NULL;
		$this->canarticulo->OldValue = $this->canarticulo->CurrentValue;
		$this->candisponible->CurrentValue = NULL;
		$this->candisponible->OldValue = $this->candisponible->CurrentValue;
		$this->canapartado->CurrentValue = NULL;
		$this->canapartado->OldValue = $this->canapartado->CurrentValue;
		$this->fecultimamod->CurrentValue = NULL;
		$this->fecultimamod->OldValue = $this->fecultimamod->CurrentValue;
		$this->usuarioultimamod->CurrentValue = NULL;
		$this->usuarioultimamod->OldValue = $this->usuarioultimamod->CurrentValue;
		$this->codubicacion->CurrentValue = NULL;
		$this->codubicacion->OldValue = $this->codubicacion->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codarticulo->FldIsDetailKey) {
			$this->codarticulo->setFormValue($objForm->GetValue("x_codarticulo"));
		}
		if (!$this->canarticulo->FldIsDetailKey) {
			$this->canarticulo->setFormValue($objForm->GetValue("x_canarticulo"));
		}
		if (!$this->candisponible->FldIsDetailKey) {
			$this->candisponible->setFormValue($objForm->GetValue("x_candisponible"));
		}
		if (!$this->canapartado->FldIsDetailKey) {
			$this->canapartado->setFormValue($objForm->GetValue("x_canapartado"));
		}
		if (!$this->fecultimamod->FldIsDetailKey) {
			$this->fecultimamod->setFormValue($objForm->GetValue("x_fecultimamod"));
		}
		if (!$this->usuarioultimamod->FldIsDetailKey) {
			$this->usuarioultimamod->setFormValue($objForm->GetValue("x_usuarioultimamod"));
		}
		if (!$this->codubicacion->FldIsDetailKey) {
			$this->codubicacion->setFormValue($objForm->GetValue("x_codubicacion"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->codarticulo->CurrentValue = $this->codarticulo->FormValue;
		$this->canarticulo->CurrentValue = $this->canarticulo->FormValue;
		$this->candisponible->CurrentValue = $this->candisponible->FormValue;
		$this->canapartado->CurrentValue = $this->canapartado->FormValue;
		$this->fecultimamod->CurrentValue = $this->fecultimamod->FormValue;
		$this->usuarioultimamod->CurrentValue = $this->usuarioultimamod->FormValue;
		$this->codubicacion->CurrentValue = $this->codubicacion->FormValue;
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
		$this->codarticulo->setDbValue($rs->fields('codarticulo'));
		$this->canarticulo->setDbValue($rs->fields('canarticulo'));
		$this->candisponible->setDbValue($rs->fields('candisponible'));
		$this->canapartado->setDbValue($rs->fields('canapartado'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->codubicacion->setDbValue($rs->fields('codubicacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codarticulo->DbValue = $row['codarticulo'];
		$this->canarticulo->DbValue = $row['canarticulo'];
		$this->candisponible->DbValue = $row['candisponible'];
		$this->canapartado->DbValue = $row['canapartado'];
		$this->fecultimamod->DbValue = $row['fecultimamod'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
		$this->codubicacion->DbValue = $row['codubicacion'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("codarticulo")) <> "")
			$this->codarticulo->CurrentValue = $this->getKey("codarticulo"); // codarticulo
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
		// codarticulo
		// canarticulo
		// candisponible
		// canapartado
		// fecultimamod
		// usuarioultimamod
		// codubicacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codarticulo
		if (strval($this->codarticulo->CurrentValue) <> "") {
			$sFilterWrk = "[codarticulo]" . ew_SearchString("=", $this->codarticulo->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT [codarticulo], [codarticulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_ARTICULO]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codarticulo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codarticulo->ViewValue = $this->codarticulo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codarticulo->ViewValue = $this->codarticulo->CurrentValue;
			}
		} else {
			$this->codarticulo->ViewValue = NULL;
		}
		$this->codarticulo->ViewCustomAttributes = "";

		// canarticulo
		$this->canarticulo->ViewValue = $this->canarticulo->CurrentValue;
		$this->canarticulo->ViewCustomAttributes = "";

		// candisponible
		$this->candisponible->ViewValue = $this->candisponible->CurrentValue;
		$this->candisponible->ViewCustomAttributes = "";

		// canapartado
		$this->canapartado->ViewValue = $this->canapartado->CurrentValue;
		$this->canapartado->ViewCustomAttributes = "";

		// fecultimamod
		$this->fecultimamod->ViewValue = $this->fecultimamod->CurrentValue;
		$this->fecultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

		// codubicacion
		if (strval($this->codubicacion->CurrentValue) <> "") {
			$sFilterWrk = "[codubicacion]" . ew_SearchString("=", $this->codubicacion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codubicacion], [ubicacion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_UBICACIONES]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codubicacion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codubicacion->ViewValue = $this->codubicacion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codubicacion->ViewValue = $this->codubicacion->CurrentValue;
			}
		} else {
			$this->codubicacion->ViewValue = NULL;
		}
		$this->codubicacion->ViewCustomAttributes = "";

			// codarticulo
			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";
			$this->codarticulo->TooltipValue = "";

			// canarticulo
			$this->canarticulo->LinkCustomAttributes = "";
			$this->canarticulo->HrefValue = "";
			$this->canarticulo->TooltipValue = "";

			// candisponible
			$this->candisponible->LinkCustomAttributes = "";
			$this->candisponible->HrefValue = "";
			$this->candisponible->TooltipValue = "";

			// canapartado
			$this->canapartado->LinkCustomAttributes = "";
			$this->canapartado->HrefValue = "";
			$this->canapartado->TooltipValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";
			$this->fecultimamod->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";

			// codubicacion
			$this->codubicacion->LinkCustomAttributes = "";
			$this->codubicacion->HrefValue = "";
			$this->codubicacion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codarticulo
			$this->codarticulo->EditAttrs["class"] = "form-control";
			$this->codarticulo->EditCustomAttributes = "";
			if (trim(strval($this->codarticulo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[codarticulo]" . ew_SearchString("=", $this->codarticulo->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT [codarticulo], [codarticulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[SIS_ARTICULO]";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->codarticulo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codarticulo->EditValue = $arwrk;

			// canarticulo
			$this->canarticulo->EditAttrs["class"] = "form-control";
			$this->canarticulo->EditCustomAttributes = "";
			$this->canarticulo->EditValue = ew_HtmlEncode($this->canarticulo->CurrentValue);
			$this->canarticulo->PlaceHolder = ew_RemoveHtml($this->canarticulo->FldCaption());

			// candisponible
			$this->candisponible->EditAttrs["class"] = "form-control";
			$this->candisponible->EditCustomAttributes = "";
			$this->candisponible->EditValue = ew_HtmlEncode($this->candisponible->CurrentValue);
			$this->candisponible->PlaceHolder = ew_RemoveHtml($this->candisponible->FldCaption());

			// canapartado
			$this->canapartado->EditAttrs["class"] = "form-control";
			$this->canapartado->EditCustomAttributes = "";
			$this->canapartado->EditValue = ew_HtmlEncode($this->canapartado->CurrentValue);
			$this->canapartado->PlaceHolder = ew_RemoveHtml($this->canapartado->FldCaption());

			// fecultimamod
			// usuarioultimamod
			// codubicacion

			$this->codubicacion->EditAttrs["class"] = "form-control";
			$this->codubicacion->EditCustomAttributes = "";
			if (trim(strval($this->codubicacion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[codubicacion]" . ew_SearchString("=", $this->codubicacion->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT [codubicacion], [ubicacion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[SIS_UBICACIONES]";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->codubicacion, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codubicacion->EditValue = $arwrk;

			// Add refer script
			// codarticulo

			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";

			// canarticulo
			$this->canarticulo->LinkCustomAttributes = "";
			$this->canarticulo->HrefValue = "";

			// candisponible
			$this->candisponible->LinkCustomAttributes = "";
			$this->candisponible->HrefValue = "";

			// canapartado
			$this->canapartado->LinkCustomAttributes = "";
			$this->canapartado->HrefValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";

			// codubicacion
			$this->codubicacion->LinkCustomAttributes = "";
			$this->codubicacion->HrefValue = "";
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
		if (!$this->codarticulo->FldIsDetailKey && !is_null($this->codarticulo->FormValue) && $this->codarticulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codarticulo->FldCaption(), $this->codarticulo->ReqErrMsg));
		}
		if (!$this->canarticulo->FldIsDetailKey && !is_null($this->canarticulo->FormValue) && $this->canarticulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->canarticulo->FldCaption(), $this->canarticulo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->canarticulo->FormValue)) {
			ew_AddMessage($gsFormError, $this->canarticulo->FldErrMsg());
		}
		if (!$this->candisponible->FldIsDetailKey && !is_null($this->candisponible->FormValue) && $this->candisponible->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->candisponible->FldCaption(), $this->candisponible->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->candisponible->FormValue)) {
			ew_AddMessage($gsFormError, $this->candisponible->FldErrMsg());
		}
		if (!$this->canapartado->FldIsDetailKey && !is_null($this->canapartado->FormValue) && $this->canapartado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->canapartado->FldCaption(), $this->canapartado->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->canapartado->FormValue)) {
			ew_AddMessage($gsFormError, $this->canapartado->FldErrMsg());
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
		if ($this->codarticulo->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(codarticulo = '" . ew_AdjustSql($this->codarticulo->CurrentValue, $this->DBID) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->codarticulo->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->codarticulo->CurrentValue, $sIdxErrMsg);
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

		// codarticulo
		$this->codarticulo->SetDbValueDef($rsnew, $this->codarticulo->CurrentValue, "", FALSE);

		// canarticulo
		$this->canarticulo->SetDbValueDef($rsnew, $this->canarticulo->CurrentValue, NULL, FALSE);

		// candisponible
		$this->candisponible->SetDbValueDef($rsnew, $this->candisponible->CurrentValue, NULL, FALSE);

		// canapartado
		$this->canapartado->SetDbValueDef($rsnew, $this->canapartado->CurrentValue, NULL, FALSE);

		// fecultimamod
		$this->fecultimamod->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['fecultimamod'] = &$this->fecultimamod->DbValue;

		// usuarioultimamod
		$this->usuarioultimamod->SetDbValueDef($rsnew, CurrentUserName(), NULL);
		$rsnew['usuarioultimamod'] = &$this->usuarioultimamod->DbValue;

		// codubicacion
		$this->codubicacion->SetDbValueDef($rsnew, $this->codubicacion->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['codarticulo']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_ARTICULO_INVENTARIOlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_ARTICULO_INVENTARIO_add)) $SIS_ARTICULO_INVENTARIO_add = new cSIS_ARTICULO_INVENTARIO_add();

// Page init
$SIS_ARTICULO_INVENTARIO_add->Page_Init();

// Page main
$SIS_ARTICULO_INVENTARIO_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_ARTICULO_INVENTARIO_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fSIS_ARTICULO_INVENTARIOadd = new ew_Form("fSIS_ARTICULO_INVENTARIOadd", "add");

// Validate form
fSIS_ARTICULO_INVENTARIOadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codarticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO_INVENTARIO->codarticulo->FldCaption(), $SIS_ARTICULO_INVENTARIO->codarticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_canarticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO_INVENTARIO->canarticulo->FldCaption(), $SIS_ARTICULO_INVENTARIO->canarticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_canarticulo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_ARTICULO_INVENTARIO->canarticulo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_candisponible");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO_INVENTARIO->candisponible->FldCaption(), $SIS_ARTICULO_INVENTARIO->candisponible->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_candisponible");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_ARTICULO_INVENTARIO->candisponible->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_canapartado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO_INVENTARIO->canapartado->FldCaption(), $SIS_ARTICULO_INVENTARIO->canapartado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_canapartado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_ARTICULO_INVENTARIO->canapartado->FldErrMsg()) ?>");

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
fSIS_ARTICULO_INVENTARIOadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ARTICULO_INVENTARIOadd.ValidateRequired = true;
<?php } else { ?>
fSIS_ARTICULO_INVENTARIOadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_ARTICULO_INVENTARIOadd.Lists["x_codarticulo"] = {"LinkField":"x_codarticulo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codarticulo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULO_INVENTARIOadd.Lists["x_codubicacion"] = {"LinkField":"x_codubicacion","Ajax":true,"AutoFill":false,"DisplayFields":["x_ubicacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $SIS_ARTICULO_INVENTARIO_add->ShowPageHeader(); ?>
<?php
$SIS_ARTICULO_INVENTARIO_add->ShowMessage();
?>
<form name="fSIS_ARTICULO_INVENTARIOadd" id="fSIS_ARTICULO_INVENTARIOadd" class="<?php echo $SIS_ARTICULO_INVENTARIO_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_ARTICULO_INVENTARIO_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_ARTICULO_INVENTARIO_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_ARTICULO_INVENTARIO">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($SIS_ARTICULO_INVENTARIO->codarticulo->Visible) { // codarticulo ?>
	<div id="r_codarticulo" class="form-group">
		<label id="elh_SIS_ARTICULO_INVENTARIO_codarticulo" for="x_codarticulo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_INVENTARIO_codarticulo">
<select data-table="SIS_ARTICULO_INVENTARIO" data-field="x_codarticulo" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_ARTICULO_INVENTARIO->codarticulo->DisplayValueSeparator) ? json_encode($SIS_ARTICULO_INVENTARIO->codarticulo->DisplayValueSeparator) : $SIS_ARTICULO_INVENTARIO->codarticulo->DisplayValueSeparator) ?>" id="x_codarticulo" name="x_codarticulo"<?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->EditAttributes() ?>>
<?php
if (is_array($SIS_ARTICULO_INVENTARIO->codarticulo->EditValue)) {
	$arwrk = $SIS_ARTICULO_INVENTARIO->codarticulo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_ARTICULO_INVENTARIO->codarticulo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_ARTICULO_INVENTARIO->codarticulo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_ARTICULO_INVENTARIO->codarticulo->CurrentValue) ?>" selected><?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT [codarticulo], [codarticulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_ARTICULO]";
$sWhereWrk = "";
$SIS_ARTICULO_INVENTARIO->codarticulo->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_ARTICULO_INVENTARIO->codarticulo->LookupFilters += array("f0" => "[codarticulo] = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$SIS_ARTICULO_INVENTARIO->Lookup_Selecting($SIS_ARTICULO_INVENTARIO->codarticulo, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $SIS_ARTICULO_INVENTARIO->codarticulo->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codarticulo" id="s_x_codarticulo" value="<?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_ARTICULO_INVENTARIO->codarticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO_INVENTARIO->canarticulo->Visible) { // canarticulo ?>
	<div id="r_canarticulo" class="form-group">
		<label id="elh_SIS_ARTICULO_INVENTARIO_canarticulo" for="x_canarticulo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO_INVENTARIO->canarticulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO_INVENTARIO->canarticulo->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_INVENTARIO_canarticulo">
<input type="text" data-table="SIS_ARTICULO_INVENTARIO" data-field="x_canarticulo" name="x_canarticulo" id="x_canarticulo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO_INVENTARIO->canarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO_INVENTARIO->canarticulo->EditValue ?>"<?php echo $SIS_ARTICULO_INVENTARIO->canarticulo->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO_INVENTARIO->canarticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO_INVENTARIO->candisponible->Visible) { // candisponible ?>
	<div id="r_candisponible" class="form-group">
		<label id="elh_SIS_ARTICULO_INVENTARIO_candisponible" for="x_candisponible" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO_INVENTARIO->candisponible->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO_INVENTARIO->candisponible->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_INVENTARIO_candisponible">
<input type="text" data-table="SIS_ARTICULO_INVENTARIO" data-field="x_candisponible" name="x_candisponible" id="x_candisponible" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO_INVENTARIO->candisponible->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO_INVENTARIO->candisponible->EditValue ?>"<?php echo $SIS_ARTICULO_INVENTARIO->candisponible->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO_INVENTARIO->candisponible->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO_INVENTARIO->canapartado->Visible) { // canapartado ?>
	<div id="r_canapartado" class="form-group">
		<label id="elh_SIS_ARTICULO_INVENTARIO_canapartado" for="x_canapartado" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO_INVENTARIO->canapartado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO_INVENTARIO->canapartado->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_INVENTARIO_canapartado">
<input type="text" data-table="SIS_ARTICULO_INVENTARIO" data-field="x_canapartado" name="x_canapartado" id="x_canapartado" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO_INVENTARIO->canapartado->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO_INVENTARIO->canapartado->EditValue ?>"<?php echo $SIS_ARTICULO_INVENTARIO->canapartado->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO_INVENTARIO->canapartado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO_INVENTARIO->codubicacion->Visible) { // codubicacion ?>
	<div id="r_codubicacion" class="form-group">
		<label id="elh_SIS_ARTICULO_INVENTARIO_codubicacion" for="x_codubicacion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_INVENTARIO_codubicacion">
<select data-table="SIS_ARTICULO_INVENTARIO" data-field="x_codubicacion" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_ARTICULO_INVENTARIO->codubicacion->DisplayValueSeparator) ? json_encode($SIS_ARTICULO_INVENTARIO->codubicacion->DisplayValueSeparator) : $SIS_ARTICULO_INVENTARIO->codubicacion->DisplayValueSeparator) ?>" id="x_codubicacion" name="x_codubicacion"<?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->EditAttributes() ?>>
<?php
if (is_array($SIS_ARTICULO_INVENTARIO->codubicacion->EditValue)) {
	$arwrk = $SIS_ARTICULO_INVENTARIO->codubicacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_ARTICULO_INVENTARIO->codubicacion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_ARTICULO_INVENTARIO->codubicacion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_ARTICULO_INVENTARIO->codubicacion->CurrentValue) ?>" selected><?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT [codubicacion], [ubicacion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_UBICACIONES]";
$sWhereWrk = "";
$SIS_ARTICULO_INVENTARIO->codubicacion->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_ARTICULO_INVENTARIO->codubicacion->LookupFilters += array("f0" => "[codubicacion] = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$SIS_ARTICULO_INVENTARIO->Lookup_Selecting($SIS_ARTICULO_INVENTARIO->codubicacion, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $SIS_ARTICULO_INVENTARIO->codubicacion->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codubicacion" id="s_x_codubicacion" value="<?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_ARTICULO_INVENTARIO->codubicacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_ARTICULO_INVENTARIO_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_ARTICULO_INVENTARIOadd.Init();
</script>
<?php
$SIS_ARTICULO_INVENTARIO_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_ARTICULO_INVENTARIO_add->Page_Terminate();
?>
