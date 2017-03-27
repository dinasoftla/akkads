<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_FACTURAinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "SIS_FACTURA_DETALLEgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_FACTURA_edit = NULL; // Initialize page object first

class cSIS_FACTURA_edit extends cSIS_FACTURA {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_FACTURA';

	// Page object name
	var $PageObjName = 'SIS_FACTURA_edit';

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

		// Table object (SIS_FACTURA)
		if (!isset($GLOBALS["SIS_FACTURA"]) || get_class($GLOBALS["SIS_FACTURA"]) == "cSIS_FACTURA") {
			$GLOBALS["SIS_FACTURA"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_FACTURA"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_FACTURA', TRUE);

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

			// Process auto fill for detail table 'SIS_FACTURA_DETALLE'
			if (@$_POST["grid"] == "fSIS_FACTURA_DETALLEgrid") {
				if (!isset($GLOBALS["SIS_FACTURA_DETALLE_grid"])) $GLOBALS["SIS_FACTURA_DETALLE_grid"] = new cSIS_FACTURA_DETALLE_grid;
				$GLOBALS["SIS_FACTURA_DETALLE_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $SIS_FACTURA;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_FACTURA);
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
		if (@$_GET["numfactura"] <> "") {
			$this->numfactura->setQueryStringValue($_GET["numfactura"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->numfactura->CurrentValue == "")
			$this->Page_Terminate("SIS_FACTURAlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("SIS_FACTURAlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_FACTURAlist.php")
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

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->numfactura->FldIsDetailKey) {
			$this->numfactura->setFormValue($objForm->GetValue("x_numfactura"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
		}
		if (!$this->codcliente->FldIsDetailKey) {
			$this->codcliente->setFormValue($objForm->GetValue("x_codcliente"));
		}
		if (!$this->fecultimamod->FldIsDetailKey) {
			$this->fecultimamod->setFormValue($objForm->GetValue("x_fecultimamod"));
		}
		if (!$this->usuarioultimamod->FldIsDetailKey) {
			$this->usuarioultimamod->setFormValue($objForm->GetValue("x_usuarioultimamod"));
		}
		if (!$this->descuento->FldIsDetailKey) {
			$this->descuento->setFormValue($objForm->GetValue("x_descuento"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->numpedido->FldIsDetailKey) {
			$this->numpedido->setFormValue($objForm->GetValue("x_numpedido"));
		}
		if (!$this->transporte->FldIsDetailKey) {
			$this->transporte->setFormValue($objForm->GetValue("x_transporte"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->numfactura->CurrentValue = $this->numfactura->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->codcliente->CurrentValue = $this->codcliente->FormValue;
		$this->fecultimamod->CurrentValue = $this->fecultimamod->FormValue;
		$this->usuarioultimamod->CurrentValue = $this->usuarioultimamod->FormValue;
		$this->descuento->CurrentValue = $this->descuento->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->numpedido->CurrentValue = $this->numpedido->FormValue;
		$this->transporte->CurrentValue = $this->transporte->FormValue;
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
		$this->numfactura->setDbValue($rs->fields('numfactura'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->transporte->setDbValue($rs->fields('transporte'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->numfactura->DbValue = $row['numfactura'];
		$this->fecha->DbValue = $row['fecha'];
		$this->codcliente->DbValue = $row['codcliente'];
		$this->fecultimamod->DbValue = $row['fecultimamod'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
		$this->descuento->DbValue = $row['descuento'];
		$this->estado->DbValue = $row['estado'];
		$this->numpedido->DbValue = $row['numpedido'];
		$this->transporte->DbValue = $row['transporte'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->transporte->FormValue == $this->transporte->CurrentValue && is_numeric(ew_StrToFloat($this->transporte->CurrentValue)))
			$this->transporte->CurrentValue = ew_StrToFloat($this->transporte->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// numfactura
		// fecha
		// codcliente
		// fecultimamod
		// usuarioultimamod
		// descuento
		// estado
		// numpedido
		// transporte

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// numfactura
		$this->numfactura->ViewValue = $this->numfactura->CurrentValue;
		$this->numfactura->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewCustomAttributes = "";

		// codcliente
		if (strval($this->codcliente->CurrentValue) <> "") {
			$sFilterWrk = "[codcliente]" . ew_SearchString("=", $this->codcliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_CLIENTE]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codcliente, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codcliente->ViewValue = $this->codcliente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codcliente->ViewValue = $this->codcliente->CurrentValue;
			}
		} else {
			$this->codcliente->ViewValue = NULL;
		}
		$this->codcliente->ViewCustomAttributes = "";

		// fecultimamod
		$this->fecultimamod->ViewValue = $this->fecultimamod->CurrentValue;
		$this->fecultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

		// descuento
		if (strval($this->descuento->CurrentValue) <> "") {
			$this->descuento->ViewValue = $this->descuento->OptionCaption($this->descuento->CurrentValue);
		} else {
			$this->descuento->ViewValue = NULL;
		}
		$this->descuento->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// numpedido
		$this->numpedido->ViewValue = $this->numpedido->CurrentValue;
		$this->numpedido->ViewCustomAttributes = "";

		// transporte
		$this->transporte->ViewValue = $this->transporte->CurrentValue;
		$this->transporte->ViewCustomAttributes = "";

			// numfactura
			$this->numfactura->LinkCustomAttributes = "";
			$this->numfactura->HrefValue = "";
			$this->numfactura->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// codcliente
			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";
			$this->codcliente->TooltipValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";
			$this->fecultimamod->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";
			$this->descuento->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// numpedido
			$this->numpedido->LinkCustomAttributes = "";
			$this->numpedido->HrefValue = "";
			$this->numpedido->TooltipValue = "";

			// transporte
			$this->transporte->LinkCustomAttributes = "";
			$this->transporte->HrefValue = "";
			$this->transporte->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// numfactura
			$this->numfactura->EditAttrs["class"] = "form-control";
			$this->numfactura->EditCustomAttributes = "";
			$this->numfactura->EditValue = $this->numfactura->CurrentValue;
			$this->numfactura->ViewCustomAttributes = "";

			// fecha
			// codcliente

			$this->codcliente->EditAttrs["class"] = "form-control";
			$this->codcliente->EditCustomAttributes = "";
			if (trim(strval($this->codcliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[codcliente]" . ew_SearchString("=", $this->codcliente->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[SIS_CLIENTE]";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->codcliente, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codcliente->EditValue = $arwrk;

			// fecultimamod
			// usuarioultimamod
			// descuento

			$this->descuento->EditAttrs["class"] = "form-control";
			$this->descuento->EditCustomAttributes = "";
			$this->descuento->EditValue = $this->descuento->Options(TRUE);

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(TRUE);

			// numpedido
			$this->numpedido->EditAttrs["class"] = "form-control";
			$this->numpedido->EditCustomAttributes = "";
			$this->numpedido->EditValue = ew_HtmlEncode($this->numpedido->CurrentValue);
			$this->numpedido->PlaceHolder = ew_RemoveHtml($this->numpedido->FldCaption());

			// transporte
			$this->transporte->EditAttrs["class"] = "form-control";
			$this->transporte->EditCustomAttributes = "";
			$this->transporte->EditValue = ew_HtmlEncode($this->transporte->CurrentValue);
			$this->transporte->PlaceHolder = ew_RemoveHtml($this->transporte->FldCaption());
			if (strval($this->transporte->EditValue) <> "" && is_numeric($this->transporte->EditValue)) $this->transporte->EditValue = ew_FormatNumber($this->transporte->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// numfactura

			$this->numfactura->LinkCustomAttributes = "";
			$this->numfactura->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// codcliente
			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";

			// numpedido
			$this->numpedido->LinkCustomAttributes = "";
			$this->numpedido->HrefValue = "";

			// transporte
			$this->transporte->LinkCustomAttributes = "";
			$this->transporte->HrefValue = "";
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
		if (!$this->numfactura->FldIsDetailKey && !is_null($this->numfactura->FormValue) && $this->numfactura->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->numfactura->FldCaption(), $this->numfactura->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->numfactura->FormValue)) {
			ew_AddMessage($gsFormError, $this->numfactura->FldErrMsg());
		}
		if (!ew_CheckInteger($this->numpedido->FormValue)) {
			ew_AddMessage($gsFormError, $this->numpedido->FldErrMsg());
		}
		if (!ew_CheckNumber($this->transporte->FormValue)) {
			ew_AddMessage($gsFormError, $this->transporte->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("SIS_FACTURA_DETALLE", $DetailTblVar) && $GLOBALS["SIS_FACTURA_DETALLE"]->DetailEdit) {
			if (!isset($GLOBALS["SIS_FACTURA_DETALLE_grid"])) $GLOBALS["SIS_FACTURA_DETALLE_grid"] = new cSIS_FACTURA_DETALLE_grid(); // get detail page object
			$GLOBALS["SIS_FACTURA_DETALLE_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// numfactura
			// fecha

			$this->fecha->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
			$rsnew['fecha'] = &$this->fecha->DbValue;

			// codcliente
			$this->codcliente->SetDbValueDef($rsnew, $this->codcliente->CurrentValue, NULL, $this->codcliente->ReadOnly);

			// fecultimamod
			$this->fecultimamod->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
			$rsnew['fecultimamod'] = &$this->fecultimamod->DbValue;

			// usuarioultimamod
			$this->usuarioultimamod->SetDbValueDef($rsnew, CurrentUserName(), NULL);
			$rsnew['usuarioultimamod'] = &$this->usuarioultimamod->DbValue;

			// descuento
			$this->descuento->SetDbValueDef($rsnew, $this->descuento->CurrentValue, NULL, $this->descuento->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, NULL, $this->estado->ReadOnly);

			// numpedido
			$this->numpedido->SetDbValueDef($rsnew, $this->numpedido->CurrentValue, NULL, $this->numpedido->ReadOnly);

			// transporte
			$this->transporte->SetDbValueDef($rsnew, $this->transporte->CurrentValue, NULL, $this->transporte->ReadOnly);

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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("SIS_FACTURA_DETALLE", $DetailTblVar) && $GLOBALS["SIS_FACTURA_DETALLE"]->DetailEdit) {
						if (!isset($GLOBALS["SIS_FACTURA_DETALLE_grid"])) $GLOBALS["SIS_FACTURA_DETALLE_grid"] = new cSIS_FACTURA_DETALLE_grid(); // Get detail page object
						$EditRow = $GLOBALS["SIS_FACTURA_DETALLE_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("SIS_FACTURA_DETALLE", $DetailTblVar)) {
				if (!isset($GLOBALS["SIS_FACTURA_DETALLE_grid"]))
					$GLOBALS["SIS_FACTURA_DETALLE_grid"] = new cSIS_FACTURA_DETALLE_grid;
				if ($GLOBALS["SIS_FACTURA_DETALLE_grid"]->DetailEdit) {
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->CurrentMode = "edit";
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->setStartRecordNumber(1);
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->numfactura->FldIsDetailKey = TRUE;
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->numfactura->CurrentValue = $this->numfactura->CurrentValue;
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->numfactura->setSessionValue($GLOBALS["SIS_FACTURA_DETALLE_grid"]->numfactura->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_FACTURAlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_FACTURA_edit)) $SIS_FACTURA_edit = new cSIS_FACTURA_edit();

// Page init
$SIS_FACTURA_edit->Page_Init();

// Page main
$SIS_FACTURA_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_FACTURA_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_FACTURAedit = new ew_Form("fSIS_FACTURAedit", "edit");

// Validate form
fSIS_FACTURAedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_numfactura");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_FACTURA->numfactura->FldCaption(), $SIS_FACTURA->numfactura->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numfactura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_FACTURA->numfactura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_numpedido");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_FACTURA->numpedido->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_transporte");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_FACTURA->transporte->FldErrMsg()) ?>");

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
fSIS_FACTURAedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_FACTURAedit.ValidateRequired = true;
<?php } else { ?>
fSIS_FACTURAedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_FACTURAedit.Lists["x_codcliente"] = {"LinkField":"x_codcliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAedit.Lists["x_descuento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAedit.Lists["x_descuento"].Options = <?php echo json_encode($SIS_FACTURA->descuento->Options()) ?>;
fSIS_FACTURAedit.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAedit.Lists["x_estado"].Options = <?php echo json_encode($SIS_FACTURA->estado->Options()) ?>;

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
<?php $SIS_FACTURA_edit->ShowPageHeader(); ?>
<?php
$SIS_FACTURA_edit->ShowMessage();
?>
<form name="fSIS_FACTURAedit" id="fSIS_FACTURAedit" class="<?php echo $SIS_FACTURA_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_FACTURA_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_FACTURA_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_FACTURA">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($SIS_FACTURA->numfactura->Visible) { // numfactura ?>
	<div id="r_numfactura" class="form-group">
		<label id="elh_SIS_FACTURA_numfactura" for="x_numfactura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_FACTURA->numfactura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_FACTURA->numfactura->CellAttributes() ?>>
<span id="el_SIS_FACTURA_numfactura">
<span<?php echo $SIS_FACTURA->numfactura->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_FACTURA->numfactura->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_FACTURA" data-field="x_numfactura" name="x_numfactura" id="x_numfactura" value="<?php echo ew_HtmlEncode($SIS_FACTURA->numfactura->CurrentValue) ?>">
<?php echo $SIS_FACTURA->numfactura->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_FACTURA->codcliente->Visible) { // codcliente ?>
	<div id="r_codcliente" class="form-group">
		<label id="elh_SIS_FACTURA_codcliente" for="x_codcliente" class="col-sm-2 control-label ewLabel"><?php echo $SIS_FACTURA->codcliente->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_FACTURA->codcliente->CellAttributes() ?>>
<span id="el_SIS_FACTURA_codcliente">
<select data-table="SIS_FACTURA" data-field="x_codcliente" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_FACTURA->codcliente->DisplayValueSeparator) ? json_encode($SIS_FACTURA->codcliente->DisplayValueSeparator) : $SIS_FACTURA->codcliente->DisplayValueSeparator) ?>" id="x_codcliente" name="x_codcliente"<?php echo $SIS_FACTURA->codcliente->EditAttributes() ?>>
<?php
if (is_array($SIS_FACTURA->codcliente->EditValue)) {
	$arwrk = $SIS_FACTURA->codcliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_FACTURA->codcliente->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_FACTURA->codcliente->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_FACTURA->codcliente->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_FACTURA->codcliente->CurrentValue) ?>" selected><?php echo $SIS_FACTURA->codcliente->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_CLIENTE]";
$sWhereWrk = "";
$SIS_FACTURA->codcliente->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_FACTURA->codcliente->LookupFilters += array("f0" => "[codcliente] = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$SIS_FACTURA->Lookup_Selecting($SIS_FACTURA->codcliente, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $SIS_FACTURA->codcliente->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codcliente" id="s_x_codcliente" value="<?php echo $SIS_FACTURA->codcliente->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_FACTURA->codcliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_FACTURA->descuento->Visible) { // descuento ?>
	<div id="r_descuento" class="form-group">
		<label id="elh_SIS_FACTURA_descuento" for="x_descuento" class="col-sm-2 control-label ewLabel"><?php echo $SIS_FACTURA->descuento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_FACTURA->descuento->CellAttributes() ?>>
<span id="el_SIS_FACTURA_descuento">
<select data-table="SIS_FACTURA" data-field="x_descuento" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_FACTURA->descuento->DisplayValueSeparator) ? json_encode($SIS_FACTURA->descuento->DisplayValueSeparator) : $SIS_FACTURA->descuento->DisplayValueSeparator) ?>" id="x_descuento" name="x_descuento"<?php echo $SIS_FACTURA->descuento->EditAttributes() ?>>
<?php
if (is_array($SIS_FACTURA->descuento->EditValue)) {
	$arwrk = $SIS_FACTURA->descuento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_FACTURA->descuento->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_FACTURA->descuento->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_FACTURA->descuento->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_FACTURA->descuento->CurrentValue) ?>" selected><?php echo $SIS_FACTURA->descuento->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_FACTURA->descuento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_FACTURA->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_SIS_FACTURA_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $SIS_FACTURA->estado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_FACTURA->estado->CellAttributes() ?>>
<span id="el_SIS_FACTURA_estado">
<select data-table="SIS_FACTURA" data-field="x_estado" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_FACTURA->estado->DisplayValueSeparator) ? json_encode($SIS_FACTURA->estado->DisplayValueSeparator) : $SIS_FACTURA->estado->DisplayValueSeparator) ?>" id="x_estado" name="x_estado"<?php echo $SIS_FACTURA->estado->EditAttributes() ?>>
<?php
if (is_array($SIS_FACTURA->estado->EditValue)) {
	$arwrk = $SIS_FACTURA->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_FACTURA->estado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_FACTURA->estado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_FACTURA->estado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_FACTURA->estado->CurrentValue) ?>" selected><?php echo $SIS_FACTURA->estado->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_FACTURA->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_FACTURA->numpedido->Visible) { // numpedido ?>
	<div id="r_numpedido" class="form-group">
		<label id="elh_SIS_FACTURA_numpedido" for="x_numpedido" class="col-sm-2 control-label ewLabel"><?php echo $SIS_FACTURA->numpedido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_FACTURA->numpedido->CellAttributes() ?>>
<span id="el_SIS_FACTURA_numpedido">
<input type="text" data-table="SIS_FACTURA" data-field="x_numpedido" name="x_numpedido" id="x_numpedido" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA->numpedido->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA->numpedido->EditValue ?>"<?php echo $SIS_FACTURA->numpedido->EditAttributes() ?>>
</span>
<?php echo $SIS_FACTURA->numpedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_FACTURA->transporte->Visible) { // transporte ?>
	<div id="r_transporte" class="form-group">
		<label id="elh_SIS_FACTURA_transporte" for="x_transporte" class="col-sm-2 control-label ewLabel"><?php echo $SIS_FACTURA->transporte->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_FACTURA->transporte->CellAttributes() ?>>
<span id="el_SIS_FACTURA_transporte">
<input type="text" data-table="SIS_FACTURA" data-field="x_transporte" name="x_transporte" id="x_transporte" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_FACTURA->transporte->getPlaceHolder()) ?>" value="<?php echo $SIS_FACTURA->transporte->EditValue ?>"<?php echo $SIS_FACTURA->transporte->EditAttributes() ?>>
</span>
<?php echo $SIS_FACTURA->transporte->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("SIS_FACTURA_DETALLE", explode(",", $SIS_FACTURA->getCurrentDetailTable())) && $SIS_FACTURA_DETALLE->DetailEdit) {
?>
<?php if ($SIS_FACTURA->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("SIS_FACTURA_DETALLE", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "SIS_FACTURA_DETALLEgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_FACTURA_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_FACTURAedit.Init();
</script>
<?php
$SIS_FACTURA_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_FACTURA_edit->Page_Terminate();
?>