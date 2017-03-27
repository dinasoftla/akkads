<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_KARDEXinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_KARDEX_edit = NULL; // Initialize page object first

class cSIS_KARDEX_edit extends cSIS_KARDEX {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_KARDEX';

	// Page object name
	var $PageObjName = 'SIS_KARDEX_edit';

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

		// Table object (SIS_KARDEX)
		if (!isset($GLOBALS["SIS_KARDEX"]) || get_class($GLOBALS["SIS_KARDEX"]) == "cSIS_KARDEX") {
			$GLOBALS["SIS_KARDEX"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_KARDEX"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_KARDEX', TRUE);

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
		global $EW_EXPORT, $SIS_KARDEX;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_KARDEX);
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
		if (@$_GET["fecha"] <> "") {
			$this->fecha->setQueryStringValue($_GET["fecha"]);
		}
		if (@$_GET["codarticulo"] <> "") {
			$this->codarticulo->setQueryStringValue($_GET["codarticulo"]);
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
			$this->Page_Terminate("SIS_KARDEXlist.php"); // Invalid key, return to list
		if ($this->fecha->CurrentValue == "")
			$this->Page_Terminate("SIS_KARDEXlist.php"); // Invalid key, return to list
		if ($this->codarticulo->CurrentValue == "")
			$this->Page_Terminate("SIS_KARDEXlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("SIS_KARDEXlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_KARDEXlist.php")
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
		if (!$this->consecutivo->FldIsDetailKey) {
			$this->consecutivo->setFormValue($objForm->GetValue("x_consecutivo"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
		}
		if (!$this->numfactura->FldIsDetailKey) {
			$this->numfactura->setFormValue($objForm->GetValue("x_numfactura"));
		}
		if (!$this->codarticulo->FldIsDetailKey) {
			$this->codarticulo->setFormValue($objForm->GetValue("x_codarticulo"));
		}
		if (!$this->movimiento->FldIsDetailKey) {
			$this->movimiento->setFormValue($objForm->GetValue("x_movimiento"));
		}
		if (!$this->cantidad->FldIsDetailKey) {
			$this->cantidad->setFormValue($objForm->GetValue("x_cantidad"));
		}
		if (!$this->saldo->FldIsDetailKey) {
			$this->saldo->setFormValue($objForm->GetValue("x_saldo"));
		}
		if (!$this->Descripcion->FldIsDetailKey) {
			$this->Descripcion->setFormValue($objForm->GetValue("x_Descripcion"));
		}
		if (!$this->usuarioultimamod->FldIsDetailKey) {
			$this->usuarioultimamod->setFormValue($objForm->GetValue("x_usuarioultimamod"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->consecutivo->CurrentValue = $this->consecutivo->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->numfactura->CurrentValue = $this->numfactura->FormValue;
		$this->codarticulo->CurrentValue = $this->codarticulo->FormValue;
		$this->movimiento->CurrentValue = $this->movimiento->FormValue;
		$this->cantidad->CurrentValue = $this->cantidad->FormValue;
		$this->saldo->CurrentValue = $this->saldo->FormValue;
		$this->Descripcion->CurrentValue = $this->Descripcion->FormValue;
		$this->usuarioultimamod->CurrentValue = $this->usuarioultimamod->FormValue;
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
		$this->numfactura->setDbValue($rs->fields('numfactura'));
		$this->codarticulo->setDbValue($rs->fields('codarticulo'));
		$this->movimiento->setDbValue($rs->fields('movimiento'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->saldo->setDbValue($rs->fields('saldo'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->Descripcion->setDbValue($rs->fields('Descripcion'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->consecutivo->DbValue = $row['consecutivo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->numfactura->DbValue = $row['numfactura'];
		$this->codarticulo->DbValue = $row['codarticulo'];
		$this->movimiento->DbValue = $row['movimiento'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->saldo->DbValue = $row['saldo'];
		$this->fecultimamod->DbValue = $row['fecultimamod'];
		$this->Descripcion->DbValue = $row['Descripcion'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// consecutivo
		// fecha
		// numfactura
		// codarticulo
		// movimiento
		// cantidad
		// saldo
		// fecultimamod
		// Descripcion
		// usuarioultimamod

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// consecutivo
		$this->consecutivo->ViewValue = $this->consecutivo->CurrentValue;
		$this->consecutivo->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewCustomAttributes = "";

		// numfactura
		$this->numfactura->ViewValue = $this->numfactura->CurrentValue;
		$this->numfactura->ViewCustomAttributes = "";

		// codarticulo
		$this->codarticulo->ViewValue = $this->codarticulo->CurrentValue;
		$this->codarticulo->ViewCustomAttributes = "";

		// movimiento
		$this->movimiento->ViewValue = $this->movimiento->CurrentValue;
		$this->movimiento->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// saldo
		$this->saldo->ViewValue = $this->saldo->CurrentValue;
		$this->saldo->ViewCustomAttributes = "";

		// Descripcion
		$this->Descripcion->ViewValue = $this->Descripcion->CurrentValue;
		$this->Descripcion->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

			// consecutivo
			$this->consecutivo->LinkCustomAttributes = "";
			$this->consecutivo->HrefValue = "";
			$this->consecutivo->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// numfactura
			$this->numfactura->LinkCustomAttributes = "";
			$this->numfactura->HrefValue = "";
			$this->numfactura->TooltipValue = "";

			// codarticulo
			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";
			$this->codarticulo->TooltipValue = "";

			// movimiento
			$this->movimiento->LinkCustomAttributes = "";
			$this->movimiento->HrefValue = "";
			$this->movimiento->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// saldo
			$this->saldo->LinkCustomAttributes = "";
			$this->saldo->HrefValue = "";
			$this->saldo->TooltipValue = "";

			// Descripcion
			$this->Descripcion->LinkCustomAttributes = "";
			$this->Descripcion->HrefValue = "";
			$this->Descripcion->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// consecutivo
			$this->consecutivo->EditAttrs["class"] = "form-control";
			$this->consecutivo->EditCustomAttributes = "";
			$this->consecutivo->EditValue = $this->consecutivo->CurrentValue;
			$this->consecutivo->ViewCustomAttributes = "";

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = $this->fecha->CurrentValue;
			$this->fecha->ViewCustomAttributes = "";

			// numfactura
			$this->numfactura->EditAttrs["class"] = "form-control";
			$this->numfactura->EditCustomAttributes = "";
			$this->numfactura->EditValue = ew_HtmlEncode($this->numfactura->CurrentValue);
			$this->numfactura->PlaceHolder = ew_RemoveHtml($this->numfactura->FldCaption());

			// codarticulo
			$this->codarticulo->EditAttrs["class"] = "form-control";
			$this->codarticulo->EditCustomAttributes = "";
			$this->codarticulo->EditValue = $this->codarticulo->CurrentValue;
			$this->codarticulo->ViewCustomAttributes = "";

			// movimiento
			$this->movimiento->EditAttrs["class"] = "form-control";
			$this->movimiento->EditCustomAttributes = "";
			$this->movimiento->EditValue = ew_HtmlEncode($this->movimiento->CurrentValue);
			$this->movimiento->PlaceHolder = ew_RemoveHtml($this->movimiento->FldCaption());

			// cantidad
			$this->cantidad->EditAttrs["class"] = "form-control";
			$this->cantidad->EditCustomAttributes = "";
			$this->cantidad->EditValue = ew_HtmlEncode($this->cantidad->CurrentValue);
			$this->cantidad->PlaceHolder = ew_RemoveHtml($this->cantidad->FldCaption());

			// saldo
			$this->saldo->EditAttrs["class"] = "form-control";
			$this->saldo->EditCustomAttributes = "";
			$this->saldo->EditValue = ew_HtmlEncode($this->saldo->CurrentValue);
			$this->saldo->PlaceHolder = ew_RemoveHtml($this->saldo->FldCaption());

			// Descripcion
			$this->Descripcion->EditAttrs["class"] = "form-control";
			$this->Descripcion->EditCustomAttributes = "";
			$this->Descripcion->EditValue = ew_HtmlEncode($this->Descripcion->CurrentValue);
			$this->Descripcion->PlaceHolder = ew_RemoveHtml($this->Descripcion->FldCaption());

			// usuarioultimamod
			$this->usuarioultimamod->EditAttrs["class"] = "form-control";
			$this->usuarioultimamod->EditCustomAttributes = "";
			$this->usuarioultimamod->EditValue = ew_HtmlEncode($this->usuarioultimamod->CurrentValue);
			$this->usuarioultimamod->PlaceHolder = ew_RemoveHtml($this->usuarioultimamod->FldCaption());

			// Edit refer script
			// consecutivo

			$this->consecutivo->LinkCustomAttributes = "";
			$this->consecutivo->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// numfactura
			$this->numfactura->LinkCustomAttributes = "";
			$this->numfactura->HrefValue = "";

			// codarticulo
			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";

			// movimiento
			$this->movimiento->LinkCustomAttributes = "";
			$this->movimiento->HrefValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";

			// saldo
			$this->saldo->LinkCustomAttributes = "";
			$this->saldo->HrefValue = "";

			// Descripcion
			$this->Descripcion->LinkCustomAttributes = "";
			$this->Descripcion->HrefValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
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
		if (!$this->consecutivo->FldIsDetailKey && !is_null($this->consecutivo->FormValue) && $this->consecutivo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->consecutivo->FldCaption(), $this->consecutivo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->consecutivo->FormValue)) {
			ew_AddMessage($gsFormError, $this->consecutivo->FldErrMsg());
		}
		if (!$this->fecha->FldIsDetailKey && !is_null($this->fecha->FormValue) && $this->fecha->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fecha->FldCaption(), $this->fecha->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->numfactura->FormValue)) {
			ew_AddMessage($gsFormError, $this->numfactura->FldErrMsg());
		}
		if (!$this->codarticulo->FldIsDetailKey && !is_null($this->codarticulo->FormValue) && $this->codarticulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codarticulo->FldCaption(), $this->codarticulo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->movimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->movimiento->FldErrMsg());
		}
		if (!ew_CheckInteger($this->cantidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidad->FldErrMsg());
		}
		if (!ew_CheckInteger($this->saldo->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo->FldErrMsg());
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

			// consecutivo
			// fecha
			// numfactura

			$this->numfactura->SetDbValueDef($rsnew, $this->numfactura->CurrentValue, NULL, $this->numfactura->ReadOnly);

			// codarticulo
			// movimiento

			$this->movimiento->SetDbValueDef($rsnew, $this->movimiento->CurrentValue, NULL, $this->movimiento->ReadOnly);

			// cantidad
			$this->cantidad->SetDbValueDef($rsnew, $this->cantidad->CurrentValue, NULL, $this->cantidad->ReadOnly);

			// saldo
			$this->saldo->SetDbValueDef($rsnew, $this->saldo->CurrentValue, NULL, $this->saldo->ReadOnly);

			// Descripcion
			$this->Descripcion->SetDbValueDef($rsnew, $this->Descripcion->CurrentValue, NULL, $this->Descripcion->ReadOnly);

			// usuarioultimamod
			$this->usuarioultimamod->SetDbValueDef($rsnew, $this->usuarioultimamod->CurrentValue, NULL, $this->usuarioultimamod->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_KARDEXlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_KARDEX_edit)) $SIS_KARDEX_edit = new cSIS_KARDEX_edit();

// Page init
$SIS_KARDEX_edit->Page_Init();

// Page main
$SIS_KARDEX_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_KARDEX_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_KARDEXedit = new ew_Form("fSIS_KARDEXedit", "edit");

// Validate form
fSIS_KARDEXedit.Validate = function() {
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
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_KARDEX->consecutivo->FldCaption(), $SIS_KARDEX->consecutivo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_consecutivo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_KARDEX->consecutivo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_KARDEX->fecha->FldCaption(), $SIS_KARDEX->fecha->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numfactura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_KARDEX->numfactura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codarticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_KARDEX->codarticulo->FldCaption(), $SIS_KARDEX->codarticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_movimiento");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_KARDEX->movimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_KARDEX->cantidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_KARDEX->saldo->FldErrMsg()) ?>");

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
fSIS_KARDEXedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_KARDEXedit.ValidateRequired = true;
<?php } else { ?>
fSIS_KARDEXedit.ValidateRequired = false; 
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
<?php $SIS_KARDEX_edit->ShowPageHeader(); ?>
<?php
$SIS_KARDEX_edit->ShowMessage();
?>
<form name="fSIS_KARDEXedit" id="fSIS_KARDEXedit" class="<?php echo $SIS_KARDEX_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_KARDEX_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_KARDEX_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_KARDEX">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($SIS_KARDEX->consecutivo->Visible) { // consecutivo ?>
	<div id="r_consecutivo" class="form-group">
		<label id="elh_SIS_KARDEX_consecutivo" for="x_consecutivo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->consecutivo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->consecutivo->CellAttributes() ?>>
<span id="el_SIS_KARDEX_consecutivo">
<span<?php echo $SIS_KARDEX->consecutivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_KARDEX->consecutivo->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_KARDEX" data-field="x_consecutivo" name="x_consecutivo" id="x_consecutivo" value="<?php echo ew_HtmlEncode($SIS_KARDEX->consecutivo->CurrentValue) ?>">
<?php echo $SIS_KARDEX->consecutivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_SIS_KARDEX_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->fecha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->fecha->CellAttributes() ?>>
<span id="el_SIS_KARDEX_fecha">
<span<?php echo $SIS_KARDEX->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_KARDEX->fecha->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_KARDEX" data-field="x_fecha" name="x_fecha" id="x_fecha" value="<?php echo ew_HtmlEncode($SIS_KARDEX->fecha->CurrentValue) ?>">
<?php echo $SIS_KARDEX->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->numfactura->Visible) { // numfactura ?>
	<div id="r_numfactura" class="form-group">
		<label id="elh_SIS_KARDEX_numfactura" for="x_numfactura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->numfactura->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->numfactura->CellAttributes() ?>>
<span id="el_SIS_KARDEX_numfactura">
<input type="text" data-table="SIS_KARDEX" data-field="x_numfactura" name="x_numfactura" id="x_numfactura" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_KARDEX->numfactura->getPlaceHolder()) ?>" value="<?php echo $SIS_KARDEX->numfactura->EditValue ?>"<?php echo $SIS_KARDEX->numfactura->EditAttributes() ?>>
</span>
<?php echo $SIS_KARDEX->numfactura->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->codarticulo->Visible) { // codarticulo ?>
	<div id="r_codarticulo" class="form-group">
		<label id="elh_SIS_KARDEX_codarticulo" for="x_codarticulo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->codarticulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->codarticulo->CellAttributes() ?>>
<span id="el_SIS_KARDEX_codarticulo">
<span<?php echo $SIS_KARDEX->codarticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_KARDEX->codarticulo->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_KARDEX" data-field="x_codarticulo" name="x_codarticulo" id="x_codarticulo" value="<?php echo ew_HtmlEncode($SIS_KARDEX->codarticulo->CurrentValue) ?>">
<?php echo $SIS_KARDEX->codarticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->movimiento->Visible) { // movimiento ?>
	<div id="r_movimiento" class="form-group">
		<label id="elh_SIS_KARDEX_movimiento" for="x_movimiento" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->movimiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->movimiento->CellAttributes() ?>>
<span id="el_SIS_KARDEX_movimiento">
<input type="text" data-table="SIS_KARDEX" data-field="x_movimiento" name="x_movimiento" id="x_movimiento" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_KARDEX->movimiento->getPlaceHolder()) ?>" value="<?php echo $SIS_KARDEX->movimiento->EditValue ?>"<?php echo $SIS_KARDEX->movimiento->EditAttributes() ?>>
</span>
<?php echo $SIS_KARDEX->movimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->cantidad->Visible) { // cantidad ?>
	<div id="r_cantidad" class="form-group">
		<label id="elh_SIS_KARDEX_cantidad" for="x_cantidad" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->cantidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->cantidad->CellAttributes() ?>>
<span id="el_SIS_KARDEX_cantidad">
<input type="text" data-table="SIS_KARDEX" data-field="x_cantidad" name="x_cantidad" id="x_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_KARDEX->cantidad->getPlaceHolder()) ?>" value="<?php echo $SIS_KARDEX->cantidad->EditValue ?>"<?php echo $SIS_KARDEX->cantidad->EditAttributes() ?>>
</span>
<?php echo $SIS_KARDEX->cantidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->saldo->Visible) { // saldo ?>
	<div id="r_saldo" class="form-group">
		<label id="elh_SIS_KARDEX_saldo" for="x_saldo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->saldo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->saldo->CellAttributes() ?>>
<span id="el_SIS_KARDEX_saldo">
<input type="text" data-table="SIS_KARDEX" data-field="x_saldo" name="x_saldo" id="x_saldo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_KARDEX->saldo->getPlaceHolder()) ?>" value="<?php echo $SIS_KARDEX->saldo->EditValue ?>"<?php echo $SIS_KARDEX->saldo->EditAttributes() ?>>
</span>
<?php echo $SIS_KARDEX->saldo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->Descripcion->Visible) { // Descripcion ?>
	<div id="r_Descripcion" class="form-group">
		<label id="elh_SIS_KARDEX_Descripcion" for="x_Descripcion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->Descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->Descripcion->CellAttributes() ?>>
<span id="el_SIS_KARDEX_Descripcion">
<input type="text" data-table="SIS_KARDEX" data-field="x_Descripcion" name="x_Descripcion" id="x_Descripcion" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_KARDEX->Descripcion->getPlaceHolder()) ?>" value="<?php echo $SIS_KARDEX->Descripcion->EditValue ?>"<?php echo $SIS_KARDEX->Descripcion->EditAttributes() ?>>
</span>
<?php echo $SIS_KARDEX->Descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_KARDEX->usuarioultimamod->Visible) { // usuarioultimamod ?>
	<div id="r_usuarioultimamod" class="form-group">
		<label id="elh_SIS_KARDEX_usuarioultimamod" for="x_usuarioultimamod" class="col-sm-2 control-label ewLabel"><?php echo $SIS_KARDEX->usuarioultimamod->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_KARDEX->usuarioultimamod->CellAttributes() ?>>
<span id="el_SIS_KARDEX_usuarioultimamod">
<input type="text" data-table="SIS_KARDEX" data-field="x_usuarioultimamod" name="x_usuarioultimamod" id="x_usuarioultimamod" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_KARDEX->usuarioultimamod->getPlaceHolder()) ?>" value="<?php echo $SIS_KARDEX->usuarioultimamod->EditValue ?>"<?php echo $SIS_KARDEX->usuarioultimamod->EditAttributes() ?>>
</span>
<?php echo $SIS_KARDEX->usuarioultimamod->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_KARDEX_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_KARDEXedit.Init();
</script>
<?php
$SIS_KARDEX_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_KARDEX_edit->Page_Terminate();
?>
