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

$SIS_CUENTAS_BANCARIAS_list = NULL; // Initialize page object first

class cSIS_CUENTAS_BANCARIAS_list extends cSIS_CUENTAS_BANCARIAS {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_CUENTAS_BANCARIAS';

	// Page object name
	var $PageObjName = 'SIS_CUENTAS_BANCARIAS_list';

	// Grid form hidden field names
	var $FormName = 'fSIS_CUENTAS_BANCARIASlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "SIS_CUENTAS_BANCARIASadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "SIS_CUENTAS_BANCARIASdelete.php";
		$this->MultiUpdateUrl = "SIS_CUENTAS_BANCARIASupdate.php";

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fSIS_CUENTAS_BANCARIASlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->idcuenta->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idcuenta->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idcuenta->AdvancedSearch->ToJSON(), ","); // Field idcuenta
		$sFilterList = ew_Concat($sFilterList, $this->propietario->AdvancedSearch->ToJSON(), ","); // Field propietario
		$sFilterList = ew_Concat($sFilterList, $this->cedula->AdvancedSearch->ToJSON(), ","); // Field cedula
		$sFilterList = ew_Concat($sFilterList, $this->banco->AdvancedSearch->ToJSON(), ","); // Field banco
		$sFilterList = ew_Concat($sFilterList, $this->tipocuenta->AdvancedSearch->ToJSON(), ","); // Field tipocuenta
		$sFilterList = ew_Concat($sFilterList, $this->moneda->AdvancedSearch->ToJSON(), ","); // Field moneda
		$sFilterList = ew_Concat($sFilterList, $this->numcuenta->AdvancedSearch->ToJSON(), ","); // Field numcuenta
		$sFilterList = ew_Concat($sFilterList, $this->cuentacliente->AdvancedSearch->ToJSON(), ","); // Field cuentacliente
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field idcuenta
		$this->idcuenta->AdvancedSearch->SearchValue = @$filter["x_idcuenta"];
		$this->idcuenta->AdvancedSearch->SearchOperator = @$filter["z_idcuenta"];
		$this->idcuenta->AdvancedSearch->SearchCondition = @$filter["v_idcuenta"];
		$this->idcuenta->AdvancedSearch->SearchValue2 = @$filter["y_idcuenta"];
		$this->idcuenta->AdvancedSearch->SearchOperator2 = @$filter["w_idcuenta"];
		$this->idcuenta->AdvancedSearch->Save();

		// Field propietario
		$this->propietario->AdvancedSearch->SearchValue = @$filter["x_propietario"];
		$this->propietario->AdvancedSearch->SearchOperator = @$filter["z_propietario"];
		$this->propietario->AdvancedSearch->SearchCondition = @$filter["v_propietario"];
		$this->propietario->AdvancedSearch->SearchValue2 = @$filter["y_propietario"];
		$this->propietario->AdvancedSearch->SearchOperator2 = @$filter["w_propietario"];
		$this->propietario->AdvancedSearch->Save();

		// Field cedula
		$this->cedula->AdvancedSearch->SearchValue = @$filter["x_cedula"];
		$this->cedula->AdvancedSearch->SearchOperator = @$filter["z_cedula"];
		$this->cedula->AdvancedSearch->SearchCondition = @$filter["v_cedula"];
		$this->cedula->AdvancedSearch->SearchValue2 = @$filter["y_cedula"];
		$this->cedula->AdvancedSearch->SearchOperator2 = @$filter["w_cedula"];
		$this->cedula->AdvancedSearch->Save();

		// Field banco
		$this->banco->AdvancedSearch->SearchValue = @$filter["x_banco"];
		$this->banco->AdvancedSearch->SearchOperator = @$filter["z_banco"];
		$this->banco->AdvancedSearch->SearchCondition = @$filter["v_banco"];
		$this->banco->AdvancedSearch->SearchValue2 = @$filter["y_banco"];
		$this->banco->AdvancedSearch->SearchOperator2 = @$filter["w_banco"];
		$this->banco->AdvancedSearch->Save();

		// Field tipocuenta
		$this->tipocuenta->AdvancedSearch->SearchValue = @$filter["x_tipocuenta"];
		$this->tipocuenta->AdvancedSearch->SearchOperator = @$filter["z_tipocuenta"];
		$this->tipocuenta->AdvancedSearch->SearchCondition = @$filter["v_tipocuenta"];
		$this->tipocuenta->AdvancedSearch->SearchValue2 = @$filter["y_tipocuenta"];
		$this->tipocuenta->AdvancedSearch->SearchOperator2 = @$filter["w_tipocuenta"];
		$this->tipocuenta->AdvancedSearch->Save();

		// Field moneda
		$this->moneda->AdvancedSearch->SearchValue = @$filter["x_moneda"];
		$this->moneda->AdvancedSearch->SearchOperator = @$filter["z_moneda"];
		$this->moneda->AdvancedSearch->SearchCondition = @$filter["v_moneda"];
		$this->moneda->AdvancedSearch->SearchValue2 = @$filter["y_moneda"];
		$this->moneda->AdvancedSearch->SearchOperator2 = @$filter["w_moneda"];
		$this->moneda->AdvancedSearch->Save();

		// Field numcuenta
		$this->numcuenta->AdvancedSearch->SearchValue = @$filter["x_numcuenta"];
		$this->numcuenta->AdvancedSearch->SearchOperator = @$filter["z_numcuenta"];
		$this->numcuenta->AdvancedSearch->SearchCondition = @$filter["v_numcuenta"];
		$this->numcuenta->AdvancedSearch->SearchValue2 = @$filter["y_numcuenta"];
		$this->numcuenta->AdvancedSearch->SearchOperator2 = @$filter["w_numcuenta"];
		$this->numcuenta->AdvancedSearch->Save();

		// Field cuentacliente
		$this->cuentacliente->AdvancedSearch->SearchValue = @$filter["x_cuentacliente"];
		$this->cuentacliente->AdvancedSearch->SearchOperator = @$filter["z_cuentacliente"];
		$this->cuentacliente->AdvancedSearch->SearchCondition = @$filter["v_cuentacliente"];
		$this->cuentacliente->AdvancedSearch->SearchValue2 = @$filter["y_cuentacliente"];
		$this->cuentacliente->AdvancedSearch->SearchOperator2 = @$filter["w_cuentacliente"];
		$this->cuentacliente->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->propietario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cedula, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->banco, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipocuenta, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->moneda, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->numcuenta, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cuentacliente, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idcuenta); // idcuenta
			$this->UpdateSort($this->propietario); // propietario
			$this->UpdateSort($this->cedula); // cedula
			$this->UpdateSort($this->banco); // banco
			$this->UpdateSort($this->tipocuenta); // tipocuenta
			$this->UpdateSort($this->moneda); // moneda
			$this->UpdateSort($this->numcuenta); // numcuenta
			$this->UpdateSort($this->cuentacliente); // cuentacliente
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idcuenta->setSort("");
				$this->propietario->setSort("");
				$this->cedula->setSort("");
				$this->banco->setSort("");
				$this->tipocuenta->setSort("");
				$this->moneda->setSort("");
				$this->numcuenta->setSort("");
				$this->cuentacliente->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd() && ($this->CurrentAction == "add");
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idcuenta->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fSIS_CUENTAS_BANCARIASlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fSIS_CUENTAS_BANCARIASlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fSIS_CUENTAS_BANCARIASlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fSIS_CUENTAS_BANCARIASlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->idcuenta->CurrentValue = NULL;
		$this->idcuenta->OldValue = $this->idcuenta->CurrentValue;
		$this->propietario->CurrentValue = NULL;
		$this->propietario->OldValue = $this->propietario->CurrentValue;
		$this->cedula->CurrentValue = NULL;
		$this->cedula->OldValue = $this->cedula->CurrentValue;
		$this->banco->CurrentValue = NULL;
		$this->banco->OldValue = $this->banco->CurrentValue;
		$this->tipocuenta->CurrentValue = NULL;
		$this->tipocuenta->OldValue = $this->tipocuenta->CurrentValue;
		$this->moneda->CurrentValue = NULL;
		$this->moneda->OldValue = $this->moneda->CurrentValue;
		$this->numcuenta->CurrentValue = NULL;
		$this->numcuenta->OldValue = $this->numcuenta->CurrentValue;
		$this->cuentacliente->CurrentValue = NULL;
		$this->cuentacliente->OldValue = $this->cuentacliente->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idcuenta->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->idcuenta->CurrentValue = $this->idcuenta->FormValue;
		$this->propietario->CurrentValue = $this->propietario->FormValue;
		$this->cedula->CurrentValue = $this->cedula->FormValue;
		$this->banco->CurrentValue = $this->banco->FormValue;
		$this->tipocuenta->CurrentValue = $this->tipocuenta->FormValue;
		$this->moneda->CurrentValue = $this->moneda->FormValue;
		$this->numcuenta->CurrentValue = $this->numcuenta->FormValue;
		$this->cuentacliente->CurrentValue = $this->cuentacliente->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idcuenta")) <> "")
			$this->idcuenta->CurrentValue = $this->getKey("idcuenta"); // idcuenta
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idcuenta
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

			// Add refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// propietario
		$this->propietario->SetDbValueDef($rsnew, $this->propietario->CurrentValue, "", FALSE);

		// cedula
		$this->cedula->SetDbValueDef($rsnew, $this->cedula->CurrentValue, "", FALSE);

		// banco
		$this->banco->SetDbValueDef($rsnew, $this->banco->CurrentValue, "", FALSE);

		// tipocuenta
		$this->tipocuenta->SetDbValueDef($rsnew, $this->tipocuenta->CurrentValue, "", FALSE);

		// moneda
		$this->moneda->SetDbValueDef($rsnew, $this->moneda->CurrentValue, "", FALSE);

		// numcuenta
		$this->numcuenta->SetDbValueDef($rsnew, $this->numcuenta->CurrentValue, "", FALSE);

		// cuentacliente
		$this->cuentacliente->SetDbValueDef($rsnew, $this->cuentacliente->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idcuenta->setDbValue($conn->Insert_ID());
				$rsnew['idcuenta'] = $this->idcuenta->DbValue;
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_CUENTAS_BANCARIAS_list)) $SIS_CUENTAS_BANCARIAS_list = new cSIS_CUENTAS_BANCARIAS_list();

// Page init
$SIS_CUENTAS_BANCARIAS_list->Page_Init();

// Page main
$SIS_CUENTAS_BANCARIAS_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_CUENTAS_BANCARIAS_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fSIS_CUENTAS_BANCARIASlist = new ew_Form("fSIS_CUENTAS_BANCARIASlist", "list");
fSIS_CUENTAS_BANCARIASlist.FormKeyCountName = '<?php echo $SIS_CUENTAS_BANCARIAS_list->FormKeyCountName ?>';

// Validate form
fSIS_CUENTAS_BANCARIASlist.Validate = function() {
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
	return true;
}

// Form_CustomValidate event
fSIS_CUENTAS_BANCARIASlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_CUENTAS_BANCARIASlist.ValidateRequired = true;
<?php } else { ?>
fSIS_CUENTAS_BANCARIASlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_CUENTAS_BANCARIASlist.Lists["x_tipocuenta"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_CUENTAS_BANCARIASlist.Lists["x_tipocuenta"].Options = <?php echo json_encode($SIS_CUENTAS_BANCARIAS->tipocuenta->Options()) ?>;
fSIS_CUENTAS_BANCARIASlist.Lists["x_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_CUENTAS_BANCARIASlist.Lists["x_moneda"].Options = <?php echo json_encode($SIS_CUENTAS_BANCARIAS->moneda->Options()) ?>;

// Form object for search
var CurrentSearchForm = fSIS_CUENTAS_BANCARIASlistsrch = new ew_Form("fSIS_CUENTAS_BANCARIASlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($SIS_CUENTAS_BANCARIAS_list->TotalRecs > 0 && $SIS_CUENTAS_BANCARIAS_list->ExportOptions->Visible()) { ?>
<?php $SIS_CUENTAS_BANCARIAS_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS_list->SearchOptions->Visible()) { ?>
<?php $SIS_CUENTAS_BANCARIAS_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS_list->FilterOptions->Visible()) { ?>
<?php $SIS_CUENTAS_BANCARIAS_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $SIS_CUENTAS_BANCARIAS_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_CUENTAS_BANCARIAS_list->TotalRecs <= 0)
			$SIS_CUENTAS_BANCARIAS_list->TotalRecs = $SIS_CUENTAS_BANCARIAS->SelectRecordCount();
	} else {
		if (!$SIS_CUENTAS_BANCARIAS_list->Recordset && ($SIS_CUENTAS_BANCARIAS_list->Recordset = $SIS_CUENTAS_BANCARIAS_list->LoadRecordset()))
			$SIS_CUENTAS_BANCARIAS_list->TotalRecs = $SIS_CUENTAS_BANCARIAS_list->Recordset->RecordCount();
	}
	$SIS_CUENTAS_BANCARIAS_list->StartRec = 1;
	if ($SIS_CUENTAS_BANCARIAS_list->DisplayRecs <= 0 || ($SIS_CUENTAS_BANCARIAS->Export <> "" && $SIS_CUENTAS_BANCARIAS->ExportAll)) // Display all records
		$SIS_CUENTAS_BANCARIAS_list->DisplayRecs = $SIS_CUENTAS_BANCARIAS_list->TotalRecs;
	if (!($SIS_CUENTAS_BANCARIAS->Export <> "" && $SIS_CUENTAS_BANCARIAS->ExportAll))
		$SIS_CUENTAS_BANCARIAS_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$SIS_CUENTAS_BANCARIAS_list->Recordset = $SIS_CUENTAS_BANCARIAS_list->LoadRecordset($SIS_CUENTAS_BANCARIAS_list->StartRec-1, $SIS_CUENTAS_BANCARIAS_list->DisplayRecs);

	// Set no record found message
	if ($SIS_CUENTAS_BANCARIAS->CurrentAction == "" && $SIS_CUENTAS_BANCARIAS_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_CUENTAS_BANCARIAS_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_CUENTAS_BANCARIAS_list->SearchWhere == "0=101")
			$SIS_CUENTAS_BANCARIAS_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_CUENTAS_BANCARIAS_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$SIS_CUENTAS_BANCARIAS_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($SIS_CUENTAS_BANCARIAS->Export == "" && $SIS_CUENTAS_BANCARIAS->CurrentAction == "") { ?>
<form name="fSIS_CUENTAS_BANCARIASlistsrch" id="fSIS_CUENTAS_BANCARIASlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($SIS_CUENTAS_BANCARIAS_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fSIS_CUENTAS_BANCARIASlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="SIS_CUENTAS_BANCARIAS">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $SIS_CUENTAS_BANCARIAS_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($SIS_CUENTAS_BANCARIAS_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($SIS_CUENTAS_BANCARIAS_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($SIS_CUENTAS_BANCARIAS_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($SIS_CUENTAS_BANCARIAS_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $SIS_CUENTAS_BANCARIAS_list->ShowPageHeader(); ?>
<?php
$SIS_CUENTAS_BANCARIAS_list->ShowMessage();
?>
<?php if ($SIS_CUENTAS_BANCARIAS_list->TotalRecs > 0 || $SIS_CUENTAS_BANCARIAS->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fSIS_CUENTAS_BANCARIASlist" id="fSIS_CUENTAS_BANCARIASlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_CUENTAS_BANCARIAS_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_CUENTAS_BANCARIAS">
<div id="gmp_SIS_CUENTAS_BANCARIAS" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($SIS_CUENTAS_BANCARIAS_list->TotalRecs > 0 || $SIS_CUENTAS_BANCARIAS->CurrentAction == "add" || $SIS_CUENTAS_BANCARIAS->CurrentAction == "copy") { ?>
<table id="tbl_SIS_CUENTAS_BANCARIASlist" class="table ewTable">
<?php echo $SIS_CUENTAS_BANCARIAS->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_CUENTAS_BANCARIAS_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_CUENTAS_BANCARIAS_list->RenderListOptions();

// Render list options (header, left)
$SIS_CUENTAS_BANCARIAS_list->ListOptions->Render("header", "left");
?>
<?php if ($SIS_CUENTAS_BANCARIAS->idcuenta->Visible) { // idcuenta ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_SIS_CUENTAS_BANCARIAS_idcuenta" class="SIS_CUENTAS_BANCARIAS_idcuenta"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->idcuenta) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_idcuenta" class="SIS_CUENTAS_BANCARIAS_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->propietario->Visible) { // propietario ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->propietario) == "") { ?>
		<th data-name="propietario"><div id="elh_SIS_CUENTAS_BANCARIAS_propietario" class="SIS_CUENTAS_BANCARIAS_propietario"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->propietario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="propietario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->propietario) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_propietario" class="SIS_CUENTAS_BANCARIAS_propietario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->propietario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->propietario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->propietario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->cedula->Visible) { // cedula ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->cedula) == "") { ?>
		<th data-name="cedula"><div id="elh_SIS_CUENTAS_BANCARIAS_cedula" class="SIS_CUENTAS_BANCARIAS_cedula"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->cedula->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cedula"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->cedula) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_cedula" class="SIS_CUENTAS_BANCARIAS_cedula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->cedula->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->cedula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->cedula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->banco->Visible) { // banco ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->banco) == "") { ?>
		<th data-name="banco"><div id="elh_SIS_CUENTAS_BANCARIAS_banco" class="SIS_CUENTAS_BANCARIAS_banco"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->banco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="banco"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->banco) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_banco" class="SIS_CUENTAS_BANCARIAS_banco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->banco->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->banco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->banco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->tipocuenta->Visible) { // tipocuenta ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->tipocuenta) == "") { ?>
		<th data-name="tipocuenta"><div id="elh_SIS_CUENTAS_BANCARIAS_tipocuenta" class="SIS_CUENTAS_BANCARIAS_tipocuenta"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipocuenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->tipocuenta) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_tipocuenta" class="SIS_CUENTAS_BANCARIAS_tipocuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->tipocuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->tipocuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->moneda->Visible) { // moneda ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->moneda) == "") { ?>
		<th data-name="moneda"><div id="elh_SIS_CUENTAS_BANCARIAS_moneda" class="SIS_CUENTAS_BANCARIAS_moneda"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->moneda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="moneda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->moneda) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_moneda" class="SIS_CUENTAS_BANCARIAS_moneda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->moneda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->moneda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->moneda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->numcuenta->Visible) { // numcuenta ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->numcuenta) == "") { ?>
		<th data-name="numcuenta"><div id="elh_SIS_CUENTAS_BANCARIAS_numcuenta" class="SIS_CUENTAS_BANCARIAS_numcuenta"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numcuenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->numcuenta) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_numcuenta" class="SIS_CUENTAS_BANCARIAS_numcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->numcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->numcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_CUENTAS_BANCARIAS->cuentacliente->Visible) { // cuentacliente ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->cuentacliente) == "") { ?>
		<th data-name="cuentacliente"><div id="elh_SIS_CUENTAS_BANCARIAS_cuentacliente" class="SIS_CUENTAS_BANCARIAS_cuentacliente"><div class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cuentacliente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_CUENTAS_BANCARIAS->SortUrl($SIS_CUENTAS_BANCARIAS->cuentacliente) ?>',1);"><div id="elh_SIS_CUENTAS_BANCARIAS_cuentacliente" class="SIS_CUENTAS_BANCARIAS_cuentacliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_CUENTAS_BANCARIAS->cuentacliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_CUENTAS_BANCARIAS->cuentacliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_CUENTAS_BANCARIAS_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($SIS_CUENTAS_BANCARIAS->CurrentAction == "add" || $SIS_CUENTAS_BANCARIAS->CurrentAction == "copy") {
		$SIS_CUENTAS_BANCARIAS_list->RowIndex = 0;
		$SIS_CUENTAS_BANCARIAS_list->KeyCount = $SIS_CUENTAS_BANCARIAS_list->RowIndex;
		if ($SIS_CUENTAS_BANCARIAS->CurrentAction == "add")
			$SIS_CUENTAS_BANCARIAS_list->LoadDefaultValues();
		if ($SIS_CUENTAS_BANCARIAS->EventCancelled) // Insert failed
			$SIS_CUENTAS_BANCARIAS_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$SIS_CUENTAS_BANCARIAS->ResetAttrs();
		$SIS_CUENTAS_BANCARIAS->RowAttrs = array_merge($SIS_CUENTAS_BANCARIAS->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_SIS_CUENTAS_BANCARIAS', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$SIS_CUENTAS_BANCARIAS->RowType = EW_ROWTYPE_ADD;

		// Render row
		$SIS_CUENTAS_BANCARIAS_list->RenderRow();

		// Render list options
		$SIS_CUENTAS_BANCARIAS_list->RenderListOptions();
		$SIS_CUENTAS_BANCARIAS_list->StartRowCnt = 0;
?>
	<tr<?php echo $SIS_CUENTAS_BANCARIAS->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_CUENTAS_BANCARIAS_list->ListOptions->Render("body", "left", $SIS_CUENTAS_BANCARIAS_list->RowCnt);
?>
	<?php if ($SIS_CUENTAS_BANCARIAS->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta">
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_idcuenta" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_idcuenta" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->idcuenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->propietario->Visible) { // propietario ?>
		<td data-name="propietario">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_propietario" class="form-group SIS_CUENTAS_BANCARIAS_propietario">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_propietario" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_propietario" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_propietario" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->propietario->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->propietario->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->propietario->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_propietario" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_propietario" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->propietario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->cedula->Visible) { // cedula ?>
		<td data-name="cedula">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_cedula" class="form-group SIS_CUENTAS_BANCARIAS_cedula">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_cedula" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cedula" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cedula" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->cedula->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->cedula->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->cedula->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_cedula" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cedula" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cedula" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->cedula->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->banco->Visible) { // banco ?>
		<td data-name="banco">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_banco" class="form-group SIS_CUENTAS_BANCARIAS_banco">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_banco" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_banco" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_banco" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->banco->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->banco->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->banco->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_banco" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_banco" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_banco" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->banco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->tipocuenta->Visible) { // tipocuenta ?>
		<td data-name="tipocuenta">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_tipocuenta" class="form-group SIS_CUENTAS_BANCARIAS_tipocuenta">
<select data-table="SIS_CUENTAS_BANCARIAS" data-field="x_tipocuenta" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValueSeparator) ? json_encode($SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValueSeparator) : $SIS_CUENTAS_BANCARIAS->tipocuenta->DisplayValueSeparator) ?>" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_tipocuenta" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_tipocuenta"<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->EditAttributes() ?>>
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
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_tipocuenta" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_tipocuenta" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_tipocuenta" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->tipocuenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->moneda->Visible) { // moneda ?>
		<td data-name="moneda">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_moneda" class="form-group SIS_CUENTAS_BANCARIAS_moneda">
<select data-table="SIS_CUENTAS_BANCARIAS" data-field="x_moneda" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_CUENTAS_BANCARIAS->moneda->DisplayValueSeparator) ? json_encode($SIS_CUENTAS_BANCARIAS->moneda->DisplayValueSeparator) : $SIS_CUENTAS_BANCARIAS->moneda->DisplayValueSeparator) ?>" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_moneda" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_moneda"<?php echo $SIS_CUENTAS_BANCARIAS->moneda->EditAttributes() ?>>
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
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_moneda" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_moneda" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_moneda" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->moneda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->numcuenta->Visible) { // numcuenta ?>
		<td data-name="numcuenta">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_numcuenta" class="form-group SIS_CUENTAS_BANCARIAS_numcuenta">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_numcuenta" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_numcuenta" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_numcuenta" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->numcuenta->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_numcuenta" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_numcuenta" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_numcuenta" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->numcuenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->cuentacliente->Visible) { // cuentacliente ?>
		<td data-name="cuentacliente">
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_cuentacliente" class="form-group SIS_CUENTAS_BANCARIAS_cuentacliente">
<input type="text" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_cuentacliente" name="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cuentacliente" id="x<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cuentacliente" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->cuentacliente->getPlaceHolder()) ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->EditValue ?>"<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->EditAttributes() ?>>
</span>
<input type="hidden" data-table="SIS_CUENTAS_BANCARIAS" data-field="x_cuentacliente" name="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cuentacliente" id="o<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>_cuentacliente" value="<?php echo ew_HtmlEncode($SIS_CUENTAS_BANCARIAS->cuentacliente->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_CUENTAS_BANCARIAS_list->ListOptions->Render("body", "right", $SIS_CUENTAS_BANCARIAS_list->RowCnt);
?>
<script type="text/javascript">
fSIS_CUENTAS_BANCARIASlist.UpdateOpts(<?php echo $SIS_CUENTAS_BANCARIAS_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($SIS_CUENTAS_BANCARIAS->ExportAll && $SIS_CUENTAS_BANCARIAS->Export <> "") {
	$SIS_CUENTAS_BANCARIAS_list->StopRec = $SIS_CUENTAS_BANCARIAS_list->TotalRecs;
} else {

	// Set the last record to display
	if ($SIS_CUENTAS_BANCARIAS_list->TotalRecs > $SIS_CUENTAS_BANCARIAS_list->StartRec + $SIS_CUENTAS_BANCARIAS_list->DisplayRecs - 1)
		$SIS_CUENTAS_BANCARIAS_list->StopRec = $SIS_CUENTAS_BANCARIAS_list->StartRec + $SIS_CUENTAS_BANCARIAS_list->DisplayRecs - 1;
	else
		$SIS_CUENTAS_BANCARIAS_list->StopRec = $SIS_CUENTAS_BANCARIAS_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($SIS_CUENTAS_BANCARIAS_list->FormKeyCountName) && ($SIS_CUENTAS_BANCARIAS->CurrentAction == "gridadd" || $SIS_CUENTAS_BANCARIAS->CurrentAction == "gridedit" || $SIS_CUENTAS_BANCARIAS->CurrentAction == "F")) {
		$SIS_CUENTAS_BANCARIAS_list->KeyCount = $objForm->GetValue($SIS_CUENTAS_BANCARIAS_list->FormKeyCountName);
		$SIS_CUENTAS_BANCARIAS_list->StopRec = $SIS_CUENTAS_BANCARIAS_list->StartRec + $SIS_CUENTAS_BANCARIAS_list->KeyCount - 1;
	}
}
$SIS_CUENTAS_BANCARIAS_list->RecCnt = $SIS_CUENTAS_BANCARIAS_list->StartRec - 1;
if ($SIS_CUENTAS_BANCARIAS_list->Recordset && !$SIS_CUENTAS_BANCARIAS_list->Recordset->EOF) {
	$SIS_CUENTAS_BANCARIAS_list->Recordset->MoveFirst();
	$bSelectLimit = $SIS_CUENTAS_BANCARIAS_list->UseSelectLimit;
	if (!$bSelectLimit && $SIS_CUENTAS_BANCARIAS_list->StartRec > 1)
		$SIS_CUENTAS_BANCARIAS_list->Recordset->Move($SIS_CUENTAS_BANCARIAS_list->StartRec - 1);
} elseif (!$SIS_CUENTAS_BANCARIAS->AllowAddDeleteRow && $SIS_CUENTAS_BANCARIAS_list->StopRec == 0) {
	$SIS_CUENTAS_BANCARIAS_list->StopRec = $SIS_CUENTAS_BANCARIAS->GridAddRowCount;
}

// Initialize aggregate
$SIS_CUENTAS_BANCARIAS->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_CUENTAS_BANCARIAS->ResetAttrs();
$SIS_CUENTAS_BANCARIAS_list->RenderRow();
while ($SIS_CUENTAS_BANCARIAS_list->RecCnt < $SIS_CUENTAS_BANCARIAS_list->StopRec) {
	$SIS_CUENTAS_BANCARIAS_list->RecCnt++;
	if (intval($SIS_CUENTAS_BANCARIAS_list->RecCnt) >= intval($SIS_CUENTAS_BANCARIAS_list->StartRec)) {
		$SIS_CUENTAS_BANCARIAS_list->RowCnt++;

		// Set up key count
		$SIS_CUENTAS_BANCARIAS_list->KeyCount = $SIS_CUENTAS_BANCARIAS_list->RowIndex;

		// Init row class and style
		$SIS_CUENTAS_BANCARIAS->ResetAttrs();
		$SIS_CUENTAS_BANCARIAS->CssClass = "";
		if ($SIS_CUENTAS_BANCARIAS->CurrentAction == "gridadd") {
			$SIS_CUENTAS_BANCARIAS_list->LoadDefaultValues(); // Load default values
		} else {
			$SIS_CUENTAS_BANCARIAS_list->LoadRowValues($SIS_CUENTAS_BANCARIAS_list->Recordset); // Load row values
		}
		$SIS_CUENTAS_BANCARIAS->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$SIS_CUENTAS_BANCARIAS->RowAttrs = array_merge($SIS_CUENTAS_BANCARIAS->RowAttrs, array('data-rowindex'=>$SIS_CUENTAS_BANCARIAS_list->RowCnt, 'id'=>'r' . $SIS_CUENTAS_BANCARIAS_list->RowCnt . '_SIS_CUENTAS_BANCARIAS', 'data-rowtype'=>$SIS_CUENTAS_BANCARIAS->RowType));

		// Render row
		$SIS_CUENTAS_BANCARIAS_list->RenderRow();

		// Render list options
		$SIS_CUENTAS_BANCARIAS_list->RenderListOptions();
?>
	<tr<?php echo $SIS_CUENTAS_BANCARIAS->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_CUENTAS_BANCARIAS_list->ListOptions->Render("body", "left", $SIS_CUENTAS_BANCARIAS_list->RowCnt);
?>
	<?php if ($SIS_CUENTAS_BANCARIAS->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_idcuenta" class="SIS_CUENTAS_BANCARIAS_idcuenta">
<span<?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->idcuenta->ListViewValue() ?></span>
</span>
<a id="<?php echo $SIS_CUENTAS_BANCARIAS_list->PageObjName . "_row_" . $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->propietario->Visible) { // propietario ?>
		<td data-name="propietario"<?php echo $SIS_CUENTAS_BANCARIAS->propietario->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_propietario" class="SIS_CUENTAS_BANCARIAS_propietario">
<span<?php echo $SIS_CUENTAS_BANCARIAS->propietario->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->propietario->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->cedula->Visible) { // cedula ?>
		<td data-name="cedula"<?php echo $SIS_CUENTAS_BANCARIAS->cedula->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_cedula" class="SIS_CUENTAS_BANCARIAS_cedula">
<span<?php echo $SIS_CUENTAS_BANCARIAS->cedula->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->cedula->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->banco->Visible) { // banco ?>
		<td data-name="banco"<?php echo $SIS_CUENTAS_BANCARIAS->banco->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_banco" class="SIS_CUENTAS_BANCARIAS_banco">
<span<?php echo $SIS_CUENTAS_BANCARIAS->banco->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->banco->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->tipocuenta->Visible) { // tipocuenta ?>
		<td data-name="tipocuenta"<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_tipocuenta" class="SIS_CUENTAS_BANCARIAS_tipocuenta">
<span<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->tipocuenta->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->moneda->Visible) { // moneda ?>
		<td data-name="moneda"<?php echo $SIS_CUENTAS_BANCARIAS->moneda->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_moneda" class="SIS_CUENTAS_BANCARIAS_moneda">
<span<?php echo $SIS_CUENTAS_BANCARIAS->moneda->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->moneda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->numcuenta->Visible) { // numcuenta ?>
		<td data-name="numcuenta"<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_numcuenta" class="SIS_CUENTAS_BANCARIAS_numcuenta">
<span<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->numcuenta->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_CUENTAS_BANCARIAS->cuentacliente->Visible) { // cuentacliente ?>
		<td data-name="cuentacliente"<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->CellAttributes() ?>>
<span id="el<?php echo $SIS_CUENTAS_BANCARIAS_list->RowCnt ?>_SIS_CUENTAS_BANCARIAS_cuentacliente" class="SIS_CUENTAS_BANCARIAS_cuentacliente">
<span<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->ViewAttributes() ?>>
<?php echo $SIS_CUENTAS_BANCARIAS->cuentacliente->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_CUENTAS_BANCARIAS_list->ListOptions->Render("body", "right", $SIS_CUENTAS_BANCARIAS_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($SIS_CUENTAS_BANCARIAS->CurrentAction <> "gridadd")
		$SIS_CUENTAS_BANCARIAS_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->CurrentAction == "add" || $SIS_CUENTAS_BANCARIAS->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $SIS_CUENTAS_BANCARIAS_list->FormKeyCountName ?>" id="<?php echo $SIS_CUENTAS_BANCARIAS_list->FormKeyCountName ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS_list->KeyCount ?>">
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($SIS_CUENTAS_BANCARIAS_list->Recordset)
	$SIS_CUENTAS_BANCARIAS_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($SIS_CUENTAS_BANCARIAS->CurrentAction <> "gridadd" && $SIS_CUENTAS_BANCARIAS->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($SIS_CUENTAS_BANCARIAS_list->Pager)) $SIS_CUENTAS_BANCARIAS_list->Pager = new cPrevNextPager($SIS_CUENTAS_BANCARIAS_list->StartRec, $SIS_CUENTAS_BANCARIAS_list->DisplayRecs, $SIS_CUENTAS_BANCARIAS_list->TotalRecs) ?>
<?php if ($SIS_CUENTAS_BANCARIAS_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($SIS_CUENTAS_BANCARIAS_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $SIS_CUENTAS_BANCARIAS_list->PageUrl() ?>start=<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($SIS_CUENTAS_BANCARIAS_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $SIS_CUENTAS_BANCARIAS_list->PageUrl() ?>start=<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($SIS_CUENTAS_BANCARIAS_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $SIS_CUENTAS_BANCARIAS_list->PageUrl() ?>start=<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($SIS_CUENTAS_BANCARIAS_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $SIS_CUENTAS_BANCARIAS_list->PageUrl() ?>start=<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $SIS_CUENTAS_BANCARIAS_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_CUENTAS_BANCARIAS_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($SIS_CUENTAS_BANCARIAS_list->TotalRecs == 0 && $SIS_CUENTAS_BANCARIAS->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_CUENTAS_BANCARIAS_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fSIS_CUENTAS_BANCARIASlistsrch.Init();
fSIS_CUENTAS_BANCARIASlistsrch.FilterList = <?php echo $SIS_CUENTAS_BANCARIAS_list->GetFilterList() ?>;
fSIS_CUENTAS_BANCARIASlist.Init();
</script>
<?php
$SIS_CUENTAS_BANCARIAS_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_CUENTAS_BANCARIAS_list->Page_Terminate();
?>
