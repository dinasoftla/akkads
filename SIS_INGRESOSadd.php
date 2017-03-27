<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_INGRESOSinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_INGRESOS_add = NULL; // Initialize page object first

class cSIS_INGRESOS_add extends cSIS_INGRESOS {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_INGRESOS';

	// Page object name
	var $PageObjName = 'SIS_INGRESOS_add';

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

		// Table object (SIS_INGRESOS)
		if (!isset($GLOBALS["SIS_INGRESOS"]) || get_class($GLOBALS["SIS_INGRESOS"]) == "cSIS_INGRESOS") {
			$GLOBALS["SIS_INGRESOS"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_INGRESOS"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_INGRESOS', TRUE);

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
		global $EW_EXPORT, $SIS_INGRESOS;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_INGRESOS);
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
			if (@$_GET["consecutivo"] != "") {
				$this->consecutivo->setQueryStringValue($_GET["consecutivo"]);
				$this->setKey("consecutivo", $this->consecutivo->CurrentValue); // Set up key
			} else {
				$this->setKey("consecutivo", ""); // Clear key
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
					$this->Page_Terminate("SIS_INGRESOSlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "SIS_INGRESOSlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "SIS_INGRESOSview.php")
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
		$this->consecutivo->CurrentValue = NULL;
		$this->consecutivo->OldValue = $this->consecutivo->CurrentValue;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->tipo->CurrentValue = NULL;
		$this->tipo->OldValue = $this->tipo->CurrentValue;
		$this->codcliente->CurrentValue = NULL;
		$this->codcliente->OldValue = $this->codcliente->CurrentValue;
		$this->factura->CurrentValue = NULL;
		$this->factura->OldValue = $this->factura->CurrentValue;
		$this->descripcion->CurrentValue = NULL;
		$this->descripcion->OldValue = $this->descripcion->CurrentValue;
		$this->monto->CurrentValue = NULL;
		$this->monto->OldValue = $this->monto->CurrentValue;
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
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->codcliente->FldIsDetailKey) {
			$this->codcliente->setFormValue($objForm->GetValue("x_codcliente"));
		}
		if (!$this->factura->FldIsDetailKey) {
			$this->factura->setFormValue($objForm->GetValue("x_factura"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->monto->FldIsDetailKey) {
			$this->monto->setFormValue($objForm->GetValue("x_monto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->consecutivo->CurrentValue = $this->consecutivo->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
		$this->codcliente->CurrentValue = $this->codcliente->FormValue;
		$this->factura->CurrentValue = $this->factura->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
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
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		$this->factura->setDbValue($rs->fields('factura'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->monto->setDbValue($rs->fields('monto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->consecutivo->DbValue = $row['consecutivo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->tipo->DbValue = $row['tipo'];
		$this->codcliente->DbValue = $row['codcliente'];
		$this->factura->DbValue = $row['factura'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->monto->DbValue = $row['monto'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("consecutivo")) <> "")
			$this->consecutivo->CurrentValue = $this->getKey("consecutivo"); // consecutivo
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

		if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ew_StrToFloat($this->monto->CurrentValue)))
			$this->monto->CurrentValue = ew_StrToFloat($this->monto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// consecutivo
		// fecha
		// tipo
		// codcliente
		// factura
		// descripcion
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

		// factura
		$this->factura->ViewValue = $this->factura->CurrentValue;
		$this->factura->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

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

			// codcliente
			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";
			$this->codcliente->TooltipValue = "";

			// factura
			$this->factura->LinkCustomAttributes = "";
			$this->factura->HrefValue = "";
			$this->factura->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// monto
			$this->monto->LinkCustomAttributes = "";
			$this->monto->HrefValue = "";
			$this->monto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// consecutivo
			$this->consecutivo->EditAttrs["class"] = "form-control";
			$this->consecutivo->EditCustomAttributes = "";
			$this->consecutivo->EditValue = ew_HtmlEncode($this->consecutivo->CurrentValue);
			$this->consecutivo->PlaceHolder = ew_RemoveHtml($this->consecutivo->FldCaption());

			// fecha
			// tipo

			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = $this->tipo->Options(TRUE);

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

			// monto
			$this->monto->EditAttrs["class"] = "form-control";
			$this->monto->EditCustomAttributes = "";
			$this->monto->EditValue = ew_HtmlEncode($this->monto->CurrentValue);
			$this->monto->PlaceHolder = ew_RemoveHtml($this->monto->FldCaption());
			if (strval($this->monto->EditValue) <> "" && is_numeric($this->monto->EditValue)) $this->monto->EditValue = ew_FormatNumber($this->monto->EditValue, -2, -1, -2, 0);

			// Add refer script
			// consecutivo

			$this->consecutivo->LinkCustomAttributes = "";
			$this->consecutivo->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";

			// codcliente
			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";

			// factura
			$this->factura->LinkCustomAttributes = "";
			$this->factura->HrefValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";

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
		if (!$this->consecutivo->FldIsDetailKey && !is_null($this->consecutivo->FormValue) && $this->consecutivo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->consecutivo->FldCaption(), $this->consecutivo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->consecutivo->FormValue)) {
			ew_AddMessage($gsFormError, $this->consecutivo->FldErrMsg());
		}
		if (!$this->tipo->FldIsDetailKey && !is_null($this->tipo->FormValue) && $this->tipo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipo->FldCaption(), $this->tipo->ReqErrMsg));
		}
		if (!$this->codcliente->FldIsDetailKey && !is_null($this->codcliente->FormValue) && $this->codcliente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codcliente->FldCaption(), $this->codcliente->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		if ($this->consecutivo->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(consecutivo = " . ew_AdjustSql($this->consecutivo->CurrentValue, $this->DBID) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->consecutivo->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->consecutivo->CurrentValue, $sIdxErrMsg);
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

		// consecutivo
		$this->consecutivo->SetDbValueDef($rsnew, $this->consecutivo->CurrentValue, 0, FALSE);

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_CurrentDate(), "");
		$rsnew['fecha'] = &$this->fecha->DbValue;

		// tipo
		$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, "", FALSE);

		// codcliente
		$this->codcliente->SetDbValueDef($rsnew, $this->codcliente->CurrentValue, 0, FALSE);

		// factura
		$this->factura->SetDbValueDef($rsnew, $this->factura->CurrentValue, 0, FALSE);

		// descripcion
		$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, "", FALSE);

		// monto
		$this->monto->SetDbValueDef($rsnew, $this->monto->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['consecutivo']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_INGRESOSlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_INGRESOS_add)) $SIS_INGRESOS_add = new cSIS_INGRESOS_add();

// Page init
$SIS_INGRESOS_add->Page_Init();

// Page main
$SIS_INGRESOS_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_INGRESOS_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fSIS_INGRESOSadd = new ew_Form("fSIS_INGRESOSadd", "add");

// Validate form
fSIS_INGRESOSadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_INGRESOS->consecutivo->FldCaption(), $SIS_INGRESOS->consecutivo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_consecutivo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_INGRESOS->consecutivo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tipo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_INGRESOS->tipo->FldCaption(), $SIS_INGRESOS->tipo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codcliente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_INGRESOS->codcliente->FldCaption(), $SIS_INGRESOS->codcliente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factura");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_INGRESOS->factura->FldCaption(), $SIS_INGRESOS->factura->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_INGRESOS->factura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_INGRESOS->descripcion->FldCaption(), $SIS_INGRESOS->descripcion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_INGRESOS->monto->FldCaption(), $SIS_INGRESOS->monto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_INGRESOS->monto->FldErrMsg()) ?>");

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
fSIS_INGRESOSadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_INGRESOSadd.ValidateRequired = true;
<?php } else { ?>
fSIS_INGRESOSadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_INGRESOSadd.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_INGRESOSadd.Lists["x_tipo"].Options = <?php echo json_encode($SIS_INGRESOS->tipo->Options()) ?>;
fSIS_INGRESOSadd.Lists["x_codcliente"] = {"LinkField":"x_codcliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $SIS_INGRESOS_add->ShowPageHeader(); ?>
<?php
$SIS_INGRESOS_add->ShowMessage();
?>
<form name="fSIS_INGRESOSadd" id="fSIS_INGRESOSadd" class="<?php echo $SIS_INGRESOS_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_INGRESOS_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_INGRESOS_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_INGRESOS">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($SIS_INGRESOS->consecutivo->Visible) { // consecutivo ?>
	<div id="r_consecutivo" class="form-group">
		<label id="elh_SIS_INGRESOS_consecutivo" for="x_consecutivo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_INGRESOS->consecutivo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_INGRESOS->consecutivo->CellAttributes() ?>>
<span id="el_SIS_INGRESOS_consecutivo">
<input type="text" data-table="SIS_INGRESOS" data-field="x_consecutivo" name="x_consecutivo" id="x_consecutivo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_INGRESOS->consecutivo->getPlaceHolder()) ?>" value="<?php echo $SIS_INGRESOS->consecutivo->EditValue ?>"<?php echo $SIS_INGRESOS->consecutivo->EditAttributes() ?>>
</span>
<?php echo $SIS_INGRESOS->consecutivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_INGRESOS->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_SIS_INGRESOS_tipo" for="x_tipo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_INGRESOS->tipo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_INGRESOS->tipo->CellAttributes() ?>>
<span id="el_SIS_INGRESOS_tipo">
<select data-table="SIS_INGRESOS" data-field="x_tipo" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_INGRESOS->tipo->DisplayValueSeparator) ? json_encode($SIS_INGRESOS->tipo->DisplayValueSeparator) : $SIS_INGRESOS->tipo->DisplayValueSeparator) ?>" id="x_tipo" name="x_tipo"<?php echo $SIS_INGRESOS->tipo->EditAttributes() ?>>
<?php
if (is_array($SIS_INGRESOS->tipo->EditValue)) {
	$arwrk = $SIS_INGRESOS->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_INGRESOS->tipo->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_INGRESOS->tipo->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_INGRESOS->tipo->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_INGRESOS->tipo->CurrentValue) ?>" selected><?php echo $SIS_INGRESOS->tipo->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_INGRESOS->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_INGRESOS->codcliente->Visible) { // codcliente ?>
	<div id="r_codcliente" class="form-group">
		<label id="elh_SIS_INGRESOS_codcliente" for="x_codcliente" class="col-sm-2 control-label ewLabel"><?php echo $SIS_INGRESOS->codcliente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_INGRESOS->codcliente->CellAttributes() ?>>
<span id="el_SIS_INGRESOS_codcliente">
<select data-table="SIS_INGRESOS" data-field="x_codcliente" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_INGRESOS->codcliente->DisplayValueSeparator) ? json_encode($SIS_INGRESOS->codcliente->DisplayValueSeparator) : $SIS_INGRESOS->codcliente->DisplayValueSeparator) ?>" id="x_codcliente" name="x_codcliente"<?php echo $SIS_INGRESOS->codcliente->EditAttributes() ?>>
<?php
if (is_array($SIS_INGRESOS->codcliente->EditValue)) {
	$arwrk = $SIS_INGRESOS->codcliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_INGRESOS->codcliente->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_INGRESOS->codcliente->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_INGRESOS->codcliente->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_INGRESOS->codcliente->CurrentValue) ?>" selected><?php echo $SIS_INGRESOS->codcliente->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_CLIENTE]";
$sWhereWrk = "";
$SIS_INGRESOS->codcliente->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_INGRESOS->codcliente->LookupFilters += array("f0" => "[codcliente] = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$SIS_INGRESOS->Lookup_Selecting($SIS_INGRESOS->codcliente, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $SIS_INGRESOS->codcliente->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codcliente" id="s_x_codcliente" value="<?php echo $SIS_INGRESOS->codcliente->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_INGRESOS->codcliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_INGRESOS->factura->Visible) { // factura ?>
	<div id="r_factura" class="form-group">
		<label id="elh_SIS_INGRESOS_factura" for="x_factura" class="col-sm-2 control-label ewLabel"><?php echo $SIS_INGRESOS->factura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_INGRESOS->factura->CellAttributes() ?>>
<span id="el_SIS_INGRESOS_factura">
<input type="text" data-table="SIS_INGRESOS" data-field="x_factura" name="x_factura" id="x_factura" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_INGRESOS->factura->getPlaceHolder()) ?>" value="<?php echo $SIS_INGRESOS->factura->EditValue ?>"<?php echo $SIS_INGRESOS->factura->EditAttributes() ?>>
</span>
<?php echo $SIS_INGRESOS->factura->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_INGRESOS->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_SIS_INGRESOS_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_INGRESOS->descripcion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_INGRESOS->descripcion->CellAttributes() ?>>
<span id="el_SIS_INGRESOS_descripcion">
<input type="text" data-table="SIS_INGRESOS" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($SIS_INGRESOS->descripcion->getPlaceHolder()) ?>" value="<?php echo $SIS_INGRESOS->descripcion->EditValue ?>"<?php echo $SIS_INGRESOS->descripcion->EditAttributes() ?>>
</span>
<?php echo $SIS_INGRESOS->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_INGRESOS->monto->Visible) { // monto ?>
	<div id="r_monto" class="form-group">
		<label id="elh_SIS_INGRESOS_monto" for="x_monto" class="col-sm-2 control-label ewLabel"><?php echo $SIS_INGRESOS->monto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_INGRESOS->monto->CellAttributes() ?>>
<span id="el_SIS_INGRESOS_monto">
<input type="text" data-table="SIS_INGRESOS" data-field="x_monto" name="x_monto" id="x_monto" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_INGRESOS->monto->getPlaceHolder()) ?>" value="<?php echo $SIS_INGRESOS->monto->EditValue ?>"<?php echo $SIS_INGRESOS->monto->EditAttributes() ?>>
</span>
<?php echo $SIS_INGRESOS->monto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_INGRESOS_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_INGRESOSadd.Init();
</script>
<?php
$SIS_INGRESOS_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_INGRESOS_add->Page_Terminate();
?>
