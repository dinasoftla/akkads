<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_PEDIDOinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "SIS_PEDIDO_DETALLEgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_PEDIDO_add = NULL; // Initialize page object first

class cSIS_PEDIDO_add extends cSIS_PEDIDO {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_PEDIDO';

	// Page object name
	var $PageObjName = 'SIS_PEDIDO_add';

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

		// Table object (SIS_PEDIDO)
		if (!isset($GLOBALS["SIS_PEDIDO"]) || get_class($GLOBALS["SIS_PEDIDO"]) == "cSIS_PEDIDO") {
			$GLOBALS["SIS_PEDIDO"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_PEDIDO"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_PEDIDO', TRUE);

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

			// Process auto fill for detail table 'SIS_PEDIDO_DETALLE'
			if (@$_POST["grid"] == "fSIS_PEDIDO_DETALLEgrid") {
				if (!isset($GLOBALS["SIS_PEDIDO_DETALLE_grid"])) $GLOBALS["SIS_PEDIDO_DETALLE_grid"] = new cSIS_PEDIDO_DETALLE_grid;
				$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->Page_Init();
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
		global $EW_EXPORT, $SIS_PEDIDO;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_PEDIDO);
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
			if (@$_GET["numpedido"] != "") {
				$this->numpedido->setQueryStringValue($_GET["numpedido"]);
				$this->setKey("numpedido", $this->numpedido->CurrentValue); // Set up key
			} else {
				$this->setKey("numpedido", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("SIS_PEDIDOlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "SIS_PEDIDOlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "SIS_PEDIDOview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->codcliente->CurrentValue = NULL;
		$this->codcliente->OldValue = $this->codcliente->CurrentValue;
		$this->fechaultimamod->CurrentValue = NULL;
		$this->fechaultimamod->OldValue = $this->fechaultimamod->CurrentValue;
		$this->usuarioultimamod->CurrentValue = NULL;
		$this->usuarioultimamod->OldValue = $this->usuarioultimamod->CurrentValue;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->descuento->CurrentValue = "0";
		$this->estado->CurrentValue = "PENDIENTE";
		$this->transporte->CurrentValue = NULL;
		$this->transporte->OldValue = $this->transporte->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codcliente->FldIsDetailKey) {
			$this->codcliente->setFormValue($objForm->GetValue("x_codcliente"));
		}
		if (!$this->fechaultimamod->FldIsDetailKey) {
			$this->fechaultimamod->setFormValue($objForm->GetValue("x_fechaultimamod"));
		}
		if (!$this->usuarioultimamod->FldIsDetailKey) {
			$this->usuarioultimamod->setFormValue($objForm->GetValue("x_usuarioultimamod"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
		}
		if (!$this->descuento->FldIsDetailKey) {
			$this->descuento->setFormValue($objForm->GetValue("x_descuento"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->transporte->FldIsDetailKey) {
			$this->transporte->setFormValue($objForm->GetValue("x_transporte"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->codcliente->CurrentValue = $this->codcliente->FormValue;
		$this->fechaultimamod->CurrentValue = $this->fechaultimamod->FormValue;
		$this->usuarioultimamod->CurrentValue = $this->usuarioultimamod->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->descuento->CurrentValue = $this->descuento->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
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
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		if (array_key_exists('EV__codcliente', $rs->fields)) {
			$this->codcliente->VirtualValue = $rs->fields('EV__codcliente'); // Set up virtual field value
		} else {
			$this->codcliente->VirtualValue = ""; // Clear value
		}
		$this->fechaultimamod->setDbValue($rs->fields('fechaultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->transporte->setDbValue($rs->fields('transporte'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->numpedido->DbValue = $row['numpedido'];
		$this->codcliente->DbValue = $row['codcliente'];
		$this->fechaultimamod->DbValue = $row['fechaultimamod'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
		$this->fecha->DbValue = $row['fecha'];
		$this->descuento->DbValue = $row['descuento'];
		$this->estado->DbValue = $row['estado'];
		$this->transporte->DbValue = $row['transporte'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("numpedido")) <> "")
			$this->numpedido->CurrentValue = $this->getKey("numpedido"); // numpedido
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

		if ($this->transporte->FormValue == $this->transporte->CurrentValue && is_numeric(ew_StrToFloat($this->transporte->CurrentValue)))
			$this->transporte->CurrentValue = ew_StrToFloat($this->transporte->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// numpedido
		// codcliente
		// fechaultimamod
		// usuarioultimamod
		// fecha
		// descuento
		// estado
		// transporte

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// numpedido
		$this->numpedido->ViewValue = $this->numpedido->CurrentValue;
		$this->numpedido->ViewCustomAttributes = "";

		// codcliente
		if ($this->codcliente->VirtualValue <> "") {
			$this->codcliente->ViewValue = $this->codcliente->VirtualValue;
		} else {
		if (strval($this->codcliente->CurrentValue) <> "") {
			$sFilterWrk = "[codcliente]" . ew_SearchString("=", $this->codcliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_CLIENTE]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codcliente, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nombre] ASC";
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
		}
		$this->codcliente->ViewCustomAttributes = "";

		// fechaultimamod
		$this->fechaultimamod->ViewValue = $this->fechaultimamod->CurrentValue;
		$this->fechaultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewCustomAttributes = "";

		// descuento
		if (strval($this->descuento->CurrentValue) <> "") {
			$this->descuento->ViewValue = $this->descuento->OptionCaption($this->descuento->CurrentValue);
		} else {
			$this->descuento->ViewValue = NULL;
		}
		$this->descuento->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// transporte
		$this->transporte->ViewValue = $this->transporte->CurrentValue;
		$this->transporte->ViewCustomAttributes = "";

			// codcliente
			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";
			$this->codcliente->TooltipValue = "";

			// fechaultimamod
			$this->fechaultimamod->LinkCustomAttributes = "";
			$this->fechaultimamod->HrefValue = "";
			$this->fechaultimamod->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";
			$this->descuento->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// transporte
			$this->transporte->LinkCustomAttributes = "";
			$this->transporte->HrefValue = "";
			$this->transporte->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			$sSqlWrk .= " ORDER BY [nombre] ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codcliente->EditValue = $arwrk;

			// fechaultimamod
			// usuarioultimamod
			// fecha
			// descuento

			$this->descuento->EditAttrs["class"] = "form-control";
			$this->descuento->EditCustomAttributes = "";
			$this->descuento->EditValue = $this->descuento->Options(TRUE);

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->CurrentValue = "PENDIENTE";

			// transporte
			$this->transporte->EditAttrs["class"] = "form-control";
			$this->transporte->EditCustomAttributes = "";
			$this->transporte->EditValue = ew_HtmlEncode($this->transporte->CurrentValue);
			$this->transporte->PlaceHolder = ew_RemoveHtml($this->transporte->FldCaption());
			if (strval($this->transporte->EditValue) <> "" && is_numeric($this->transporte->EditValue)) $this->transporte->EditValue = ew_FormatNumber($this->transporte->EditValue, -2, -1, -2, 0);

			// Add refer script
			// codcliente

			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";

			// fechaultimamod
			$this->fechaultimamod->LinkCustomAttributes = "";
			$this->fechaultimamod->HrefValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";

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
		if (!$this->codcliente->FldIsDetailKey && !is_null($this->codcliente->FormValue) && $this->codcliente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codcliente->FldCaption(), $this->codcliente->ReqErrMsg));
		}
		if (!$this->descuento->FldIsDetailKey && !is_null($this->descuento->FormValue) && $this->descuento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->descuento->FldCaption(), $this->descuento->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->transporte->FormValue)) {
			ew_AddMessage($gsFormError, $this->transporte->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("SIS_PEDIDO_DETALLE", $DetailTblVar) && $GLOBALS["SIS_PEDIDO_DETALLE"]->DetailAdd) {
			if (!isset($GLOBALS["SIS_PEDIDO_DETALLE_grid"])) $GLOBALS["SIS_PEDIDO_DETALLE_grid"] = new cSIS_PEDIDO_DETALLE_grid(); // get detail page object
			$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// codcliente
		$this->codcliente->SetDbValueDef($rsnew, $this->codcliente->CurrentValue, NULL, FALSE);

		// fechaultimamod
		$this->fechaultimamod->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['fechaultimamod'] = &$this->fechaultimamod->DbValue;

		// usuarioultimamod
		$this->usuarioultimamod->SetDbValueDef($rsnew, CurrentUserName(), NULL);
		$rsnew['usuarioultimamod'] = &$this->usuarioultimamod->DbValue;

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['fecha'] = &$this->fecha->DbValue;

		// descuento
		$this->descuento->SetDbValueDef($rsnew, $this->descuento->CurrentValue, NULL, FALSE);

		// estado
		$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, NULL, FALSE);

		// transporte
		$this->transporte->SetDbValueDef($rsnew, $this->transporte->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->numpedido->setDbValue($conn->Insert_ID());
				$rsnew['numpedido'] = $this->numpedido->DbValue;
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("SIS_PEDIDO_DETALLE", $DetailTblVar) && $GLOBALS["SIS_PEDIDO_DETALLE"]->DetailAdd) {
				$GLOBALS["SIS_PEDIDO_DETALLE"]->numpedido->setSessionValue($this->numpedido->CurrentValue); // Set master key
				if (!isset($GLOBALS["SIS_PEDIDO_DETALLE_grid"])) $GLOBALS["SIS_PEDIDO_DETALLE_grid"] = new cSIS_PEDIDO_DETALLE_grid(); // Get detail page object
				$AddRow = $GLOBALS["SIS_PEDIDO_DETALLE_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["SIS_PEDIDO_DETALLE"]->numpedido->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
			if (in_array("SIS_PEDIDO_DETALLE", $DetailTblVar)) {
				if (!isset($GLOBALS["SIS_PEDIDO_DETALLE_grid"]))
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"] = new cSIS_PEDIDO_DETALLE_grid;
				if ($GLOBALS["SIS_PEDIDO_DETALLE_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->CurrentMode = "add";
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->setStartRecordNumber(1);
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->numpedido->FldIsDetailKey = TRUE;
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->numpedido->CurrentValue = $this->numpedido->CurrentValue;
					$GLOBALS["SIS_PEDIDO_DETALLE_grid"]->numpedido->setSessionValue($GLOBALS["SIS_PEDIDO_DETALLE_grid"]->numpedido->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_PEDIDOlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_PEDIDO_add)) $SIS_PEDIDO_add = new cSIS_PEDIDO_add();

// Page init
$SIS_PEDIDO_add->Page_Init();

// Page main
$SIS_PEDIDO_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_PEDIDO_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fSIS_PEDIDOadd = new ew_Form("fSIS_PEDIDOadd", "add");

// Validate form
fSIS_PEDIDOadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codcliente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PEDIDO->codcliente->FldCaption(), $SIS_PEDIDO->codcliente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_descuento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_PEDIDO->descuento->FldCaption(), $SIS_PEDIDO->descuento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transporte");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_PEDIDO->transporte->FldErrMsg()) ?>");

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
fSIS_PEDIDOadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_PEDIDOadd.ValidateRequired = true;
<?php } else { ?>
fSIS_PEDIDOadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_PEDIDOadd.Lists["x_codcliente"] = {"LinkField":"x_codcliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_PEDIDOadd.Lists["x_descuento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_PEDIDOadd.Lists["x_descuento"].Options = <?php echo json_encode($SIS_PEDIDO->descuento->Options()) ?>;

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
<?php $SIS_PEDIDO_add->ShowPageHeader(); ?>
<?php
$SIS_PEDIDO_add->ShowMessage();
?>
<form name="fSIS_PEDIDOadd" id="fSIS_PEDIDOadd" class="<?php echo $SIS_PEDIDO_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_PEDIDO_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_PEDIDO_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_PEDIDO">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($SIS_PEDIDO->codcliente->Visible) { // codcliente ?>
	<div id="r_codcliente" class="form-group">
		<label id="elh_SIS_PEDIDO_codcliente" for="x_codcliente" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO->codcliente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO->codcliente->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_codcliente">
<select data-table="SIS_PEDIDO" data-field="x_codcliente" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_PEDIDO->codcliente->DisplayValueSeparator) ? json_encode($SIS_PEDIDO->codcliente->DisplayValueSeparator) : $SIS_PEDIDO->codcliente->DisplayValueSeparator) ?>" id="x_codcliente" name="x_codcliente"<?php echo $SIS_PEDIDO->codcliente->EditAttributes() ?>>
<?php
if (is_array($SIS_PEDIDO->codcliente->EditValue)) {
	$arwrk = $SIS_PEDIDO->codcliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_PEDIDO->codcliente->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_PEDIDO->codcliente->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_PEDIDO->codcliente->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_PEDIDO->codcliente->CurrentValue) ?>" selected><?php echo $SIS_PEDIDO->codcliente->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $SIS_PEDIDO->codcliente->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_codcliente',url:'SIS_CLIENTEaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_codcliente"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $SIS_PEDIDO->codcliente->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_CLIENTE]";
$sWhereWrk = "";
$SIS_PEDIDO->codcliente->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_PEDIDO->codcliente->LookupFilters += array("f0" => "[codcliente] = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$SIS_PEDIDO->Lookup_Selecting($SIS_PEDIDO->codcliente, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [nombre] ASC";
if ($sSqlWrk <> "") $SIS_PEDIDO->codcliente->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codcliente" id="s_x_codcliente" value="<?php echo $SIS_PEDIDO->codcliente->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_PEDIDO->codcliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_PEDIDO->descuento->Visible) { // descuento ?>
	<div id="r_descuento" class="form-group">
		<label id="elh_SIS_PEDIDO_descuento" for="x_descuento" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO->descuento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO->descuento->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_descuento">
<select data-table="SIS_PEDIDO" data-field="x_descuento" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_PEDIDO->descuento->DisplayValueSeparator) ? json_encode($SIS_PEDIDO->descuento->DisplayValueSeparator) : $SIS_PEDIDO->descuento->DisplayValueSeparator) ?>" id="x_descuento" name="x_descuento"<?php echo $SIS_PEDIDO->descuento->EditAttributes() ?>>
<?php
if (is_array($SIS_PEDIDO->descuento->EditValue)) {
	$arwrk = $SIS_PEDIDO->descuento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_PEDIDO->descuento->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_PEDIDO->descuento->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_PEDIDO->descuento->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_PEDIDO->descuento->CurrentValue) ?>" selected><?php echo $SIS_PEDIDO->descuento->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_PEDIDO->descuento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<span id="el_SIS_PEDIDO_estado">
<input type="hidden" data-table="SIS_PEDIDO" data-field="x_estado" name="x_estado" id="x_estado" value="<?php echo ew_HtmlEncode($SIS_PEDIDO->estado->CurrentValue) ?>">
</span>
<?php if ($SIS_PEDIDO->transporte->Visible) { // transporte ?>
	<div id="r_transporte" class="form-group">
		<label id="elh_SIS_PEDIDO_transporte" for="x_transporte" class="col-sm-2 control-label ewLabel"><?php echo $SIS_PEDIDO->transporte->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_PEDIDO->transporte->CellAttributes() ?>>
<span id="el_SIS_PEDIDO_transporte">
<input type="text" data-table="SIS_PEDIDO" data-field="x_transporte" name="x_transporte" id="x_transporte" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_PEDIDO->transporte->getPlaceHolder()) ?>" value="<?php echo $SIS_PEDIDO->transporte->EditValue ?>"<?php echo $SIS_PEDIDO->transporte->EditAttributes() ?>>
</span>
<?php echo $SIS_PEDIDO->transporte->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("SIS_PEDIDO_DETALLE", explode(",", $SIS_PEDIDO->getCurrentDetailTable())) && $SIS_PEDIDO_DETALLE->DetailAdd) {
?>
<?php if ($SIS_PEDIDO->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("SIS_PEDIDO_DETALLE", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "SIS_PEDIDO_DETALLEgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_PEDIDO_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_PEDIDOadd.Init();
</script>
<?php
$SIS_PEDIDO_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_PEDIDO_add->Page_Terminate();
?>
