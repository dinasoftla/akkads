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

$SIS_COMPANIA_list = NULL; // Initialize page object first

class cSIS_COMPANIA_list extends cSIS_COMPANIA {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_COMPANIA';

	// Page object name
	var $PageObjName = 'SIS_COMPANIA_list';

	// Grid form hidden field names
	var $FormName = 'fSIS_COMPANIAlist';
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

		// Table object (SIS_COMPANIA)
		if (!isset($GLOBALS["SIS_COMPANIA"]) || get_class($GLOBALS["SIS_COMPANIA"]) == "cSIS_COMPANIA") {
			$GLOBALS["SIS_COMPANIA"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_COMPANIA"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "SIS_COMPANIAadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "SIS_COMPANIAdelete.php";
		$this->MultiUpdateUrl = "SIS_COMPANIAupdate.php";

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fSIS_COMPANIAlistsrch";

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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
			$this->codcompania->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->codcompania->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->codcompania->AdvancedSearch->ToJSON(), ","); // Field codcompania
		$sFilterList = ew_Concat($sFilterList, $this->nombre->AdvancedSearch->ToJSON(), ","); // Field nombre
		$sFilterList = ew_Concat($sFilterList, $this->telefono->AdvancedSearch->ToJSON(), ","); // Field telefono
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJSON(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->descuento->AdvancedSearch->ToJSON(), ","); // Field descuento
		$sFilterList = ew_Concat($sFilterList, $this->piefactura->AdvancedSearch->ToJSON(), ","); // Field piefactura
		$sFilterList = ew_Concat($sFilterList, $this->leyendafactura->AdvancedSearch->ToJSON(), ","); // Field leyendafactura
		$sFilterList = ew_Concat($sFilterList, $this->trasnporteenfactura->AdvancedSearch->ToJSON(), ","); // Field trasnporteenfactura
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

		// Field codcompania
		$this->codcompania->AdvancedSearch->SearchValue = @$filter["x_codcompania"];
		$this->codcompania->AdvancedSearch->SearchOperator = @$filter["z_codcompania"];
		$this->codcompania->AdvancedSearch->SearchCondition = @$filter["v_codcompania"];
		$this->codcompania->AdvancedSearch->SearchValue2 = @$filter["y_codcompania"];
		$this->codcompania->AdvancedSearch->SearchOperator2 = @$filter["w_codcompania"];
		$this->codcompania->AdvancedSearch->Save();

		// Field nombre
		$this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
		$this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
		$this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
		$this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
		$this->nombre->AdvancedSearch->Save();

		// Field telefono
		$this->telefono->AdvancedSearch->SearchValue = @$filter["x_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator = @$filter["z_telefono"];
		$this->telefono->AdvancedSearch->SearchCondition = @$filter["v_telefono"];
		$this->telefono->AdvancedSearch->SearchValue2 = @$filter["y_telefono"];
		$this->telefono->AdvancedSearch->SearchOperator2 = @$filter["w_telefono"];
		$this->telefono->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field descuento
		$this->descuento->AdvancedSearch->SearchValue = @$filter["x_descuento"];
		$this->descuento->AdvancedSearch->SearchOperator = @$filter["z_descuento"];
		$this->descuento->AdvancedSearch->SearchCondition = @$filter["v_descuento"];
		$this->descuento->AdvancedSearch->SearchValue2 = @$filter["y_descuento"];
		$this->descuento->AdvancedSearch->SearchOperator2 = @$filter["w_descuento"];
		$this->descuento->AdvancedSearch->Save();

		// Field piefactura
		$this->piefactura->AdvancedSearch->SearchValue = @$filter["x_piefactura"];
		$this->piefactura->AdvancedSearch->SearchOperator = @$filter["z_piefactura"];
		$this->piefactura->AdvancedSearch->SearchCondition = @$filter["v_piefactura"];
		$this->piefactura->AdvancedSearch->SearchValue2 = @$filter["y_piefactura"];
		$this->piefactura->AdvancedSearch->SearchOperator2 = @$filter["w_piefactura"];
		$this->piefactura->AdvancedSearch->Save();

		// Field leyendafactura
		$this->leyendafactura->AdvancedSearch->SearchValue = @$filter["x_leyendafactura"];
		$this->leyendafactura->AdvancedSearch->SearchOperator = @$filter["z_leyendafactura"];
		$this->leyendafactura->AdvancedSearch->SearchCondition = @$filter["v_leyendafactura"];
		$this->leyendafactura->AdvancedSearch->SearchValue2 = @$filter["y_leyendafactura"];
		$this->leyendafactura->AdvancedSearch->SearchOperator2 = @$filter["w_leyendafactura"];
		$this->leyendafactura->AdvancedSearch->Save();

		// Field trasnporteenfactura
		$this->trasnporteenfactura->AdvancedSearch->SearchValue = @$filter["x_trasnporteenfactura"];
		$this->trasnporteenfactura->AdvancedSearch->SearchOperator = @$filter["z_trasnporteenfactura"];
		$this->trasnporteenfactura->AdvancedSearch->SearchCondition = @$filter["v_trasnporteenfactura"];
		$this->trasnporteenfactura->AdvancedSearch->SearchValue2 = @$filter["y_trasnporteenfactura"];
		$this->trasnporteenfactura->AdvancedSearch->SearchOperator2 = @$filter["w_trasnporteenfactura"];
		$this->trasnporteenfactura->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telefono, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->piefactura, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->leyendafactura, $arKeywords, $type);
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
			$this->UpdateSort($this->codcompania); // codcompania
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->telefono); // telefono
			$this->UpdateSort($this->direccion); // direccion
			$this->UpdateSort($this->descuento); // descuento
			$this->UpdateSort($this->piefactura); // piefactura
			$this->UpdateSort($this->leyendafactura); // leyendafactura
			$this->UpdateSort($this->trasnporteenfactura); // trasnporteenfactura
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
				$this->codcompania->setSort("");
				$this->nombre->setSort("");
				$this->telefono->setSort("");
				$this->direccion->setSort("");
				$this->descuento->setSort("");
				$this->piefactura->setSort("");
				$this->leyendafactura->setSort("");
				$this->trasnporteenfactura->setSort("");
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

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
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

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->codcompania->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fSIS_COMPANIAlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fSIS_COMPANIAlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fSIS_COMPANIAlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fSIS_COMPANIAlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
if (!isset($SIS_COMPANIA_list)) $SIS_COMPANIA_list = new cSIS_COMPANIA_list();

// Page init
$SIS_COMPANIA_list->Page_Init();

// Page main
$SIS_COMPANIA_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_COMPANIA_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fSIS_COMPANIAlist = new ew_Form("fSIS_COMPANIAlist", "list");
fSIS_COMPANIAlist.FormKeyCountName = '<?php echo $SIS_COMPANIA_list->FormKeyCountName ?>';

// Form_CustomValidate event
fSIS_COMPANIAlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_COMPANIAlist.ValidateRequired = true;
<?php } else { ?>
fSIS_COMPANIAlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_COMPANIAlist.Lists["x_trasnporteenfactura"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_COMPANIAlist.Lists["x_trasnporteenfactura"].Options = <?php echo json_encode($SIS_COMPANIA->trasnporteenfactura->Options()) ?>;

// Form object for search
var CurrentSearchForm = fSIS_COMPANIAlistsrch = new ew_Form("fSIS_COMPANIAlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($SIS_COMPANIA_list->TotalRecs > 0 && $SIS_COMPANIA_list->ExportOptions->Visible()) { ?>
<?php $SIS_COMPANIA_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_COMPANIA_list->SearchOptions->Visible()) { ?>
<?php $SIS_COMPANIA_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_COMPANIA_list->FilterOptions->Visible()) { ?>
<?php $SIS_COMPANIA_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $SIS_COMPANIA_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_COMPANIA_list->TotalRecs <= 0)
			$SIS_COMPANIA_list->TotalRecs = $SIS_COMPANIA->SelectRecordCount();
	} else {
		if (!$SIS_COMPANIA_list->Recordset && ($SIS_COMPANIA_list->Recordset = $SIS_COMPANIA_list->LoadRecordset()))
			$SIS_COMPANIA_list->TotalRecs = $SIS_COMPANIA_list->Recordset->RecordCount();
	}
	$SIS_COMPANIA_list->StartRec = 1;
	if ($SIS_COMPANIA_list->DisplayRecs <= 0 || ($SIS_COMPANIA->Export <> "" && $SIS_COMPANIA->ExportAll)) // Display all records
		$SIS_COMPANIA_list->DisplayRecs = $SIS_COMPANIA_list->TotalRecs;
	if (!($SIS_COMPANIA->Export <> "" && $SIS_COMPANIA->ExportAll))
		$SIS_COMPANIA_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$SIS_COMPANIA_list->Recordset = $SIS_COMPANIA_list->LoadRecordset($SIS_COMPANIA_list->StartRec-1, $SIS_COMPANIA_list->DisplayRecs);

	// Set no record found message
	if ($SIS_COMPANIA->CurrentAction == "" && $SIS_COMPANIA_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_COMPANIA_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_COMPANIA_list->SearchWhere == "0=101")
			$SIS_COMPANIA_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_COMPANIA_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$SIS_COMPANIA_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($SIS_COMPANIA->Export == "" && $SIS_COMPANIA->CurrentAction == "") { ?>
<form name="fSIS_COMPANIAlistsrch" id="fSIS_COMPANIAlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($SIS_COMPANIA_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fSIS_COMPANIAlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="SIS_COMPANIA">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($SIS_COMPANIA_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($SIS_COMPANIA_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $SIS_COMPANIA_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($SIS_COMPANIA_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($SIS_COMPANIA_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($SIS_COMPANIA_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($SIS_COMPANIA_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $SIS_COMPANIA_list->ShowPageHeader(); ?>
<?php
$SIS_COMPANIA_list->ShowMessage();
?>
<?php if ($SIS_COMPANIA_list->TotalRecs > 0 || $SIS_COMPANIA->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fSIS_COMPANIAlist" id="fSIS_COMPANIAlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_COMPANIA_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_COMPANIA_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_COMPANIA">
<div id="gmp_SIS_COMPANIA" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($SIS_COMPANIA_list->TotalRecs > 0) { ?>
<table id="tbl_SIS_COMPANIAlist" class="table ewTable">
<?php echo $SIS_COMPANIA->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_COMPANIA_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_COMPANIA_list->RenderListOptions();

// Render list options (header, left)
$SIS_COMPANIA_list->ListOptions->Render("header", "left");
?>
<?php if ($SIS_COMPANIA->codcompania->Visible) { // codcompania ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->codcompania) == "") { ?>
		<th data-name="codcompania"><div id="elh_SIS_COMPANIA_codcompania" class="SIS_COMPANIA_codcompania"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->codcompania->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codcompania"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->codcompania) ?>',1);"><div id="elh_SIS_COMPANIA_codcompania" class="SIS_COMPANIA_codcompania">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->codcompania->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->codcompania->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->codcompania->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->nombre->Visible) { // nombre ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_SIS_COMPANIA_nombre" class="SIS_COMPANIA_nombre"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->nombre) ?>',1);"><div id="elh_SIS_COMPANIA_nombre" class="SIS_COMPANIA_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->telefono->Visible) { // telefono ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_SIS_COMPANIA_telefono" class="SIS_COMPANIA_telefono"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->telefono) ?>',1);"><div id="elh_SIS_COMPANIA_telefono" class="SIS_COMPANIA_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->telefono->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->direccion->Visible) { // direccion ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_SIS_COMPANIA_direccion" class="SIS_COMPANIA_direccion"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->direccion) ?>',1);"><div id="elh_SIS_COMPANIA_direccion" class="SIS_COMPANIA_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->direccion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->descuento->Visible) { // descuento ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->descuento) == "") { ?>
		<th data-name="descuento"><div id="elh_SIS_COMPANIA_descuento" class="SIS_COMPANIA_descuento"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->descuento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descuento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->descuento) ?>',1);"><div id="elh_SIS_COMPANIA_descuento" class="SIS_COMPANIA_descuento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->descuento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->descuento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->descuento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->piefactura->Visible) { // piefactura ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->piefactura) == "") { ?>
		<th data-name="piefactura"><div id="elh_SIS_COMPANIA_piefactura" class="SIS_COMPANIA_piefactura"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->piefactura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="piefactura"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->piefactura) ?>',1);"><div id="elh_SIS_COMPANIA_piefactura" class="SIS_COMPANIA_piefactura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->piefactura->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->piefactura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->piefactura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->leyendafactura->Visible) { // leyendafactura ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->leyendafactura) == "") { ?>
		<th data-name="leyendafactura"><div id="elh_SIS_COMPANIA_leyendafactura" class="SIS_COMPANIA_leyendafactura"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->leyendafactura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="leyendafactura"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->leyendafactura) ?>',1);"><div id="elh_SIS_COMPANIA_leyendafactura" class="SIS_COMPANIA_leyendafactura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->leyendafactura->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->leyendafactura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->leyendafactura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_COMPANIA->trasnporteenfactura->Visible) { // trasnporteenfactura ?>
	<?php if ($SIS_COMPANIA->SortUrl($SIS_COMPANIA->trasnporteenfactura) == "") { ?>
		<th data-name="trasnporteenfactura"><div id="elh_SIS_COMPANIA_trasnporteenfactura" class="SIS_COMPANIA_trasnporteenfactura"><div class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->trasnporteenfactura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="trasnporteenfactura"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_COMPANIA->SortUrl($SIS_COMPANIA->trasnporteenfactura) ?>',1);"><div id="elh_SIS_COMPANIA_trasnporteenfactura" class="SIS_COMPANIA_trasnporteenfactura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_COMPANIA->trasnporteenfactura->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_COMPANIA->trasnporteenfactura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_COMPANIA->trasnporteenfactura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_COMPANIA_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($SIS_COMPANIA->ExportAll && $SIS_COMPANIA->Export <> "") {
	$SIS_COMPANIA_list->StopRec = $SIS_COMPANIA_list->TotalRecs;
} else {

	// Set the last record to display
	if ($SIS_COMPANIA_list->TotalRecs > $SIS_COMPANIA_list->StartRec + $SIS_COMPANIA_list->DisplayRecs - 1)
		$SIS_COMPANIA_list->StopRec = $SIS_COMPANIA_list->StartRec + $SIS_COMPANIA_list->DisplayRecs - 1;
	else
		$SIS_COMPANIA_list->StopRec = $SIS_COMPANIA_list->TotalRecs;
}
$SIS_COMPANIA_list->RecCnt = $SIS_COMPANIA_list->StartRec - 1;
if ($SIS_COMPANIA_list->Recordset && !$SIS_COMPANIA_list->Recordset->EOF) {
	$SIS_COMPANIA_list->Recordset->MoveFirst();
	$bSelectLimit = $SIS_COMPANIA_list->UseSelectLimit;
	if (!$bSelectLimit && $SIS_COMPANIA_list->StartRec > 1)
		$SIS_COMPANIA_list->Recordset->Move($SIS_COMPANIA_list->StartRec - 1);
} elseif (!$SIS_COMPANIA->AllowAddDeleteRow && $SIS_COMPANIA_list->StopRec == 0) {
	$SIS_COMPANIA_list->StopRec = $SIS_COMPANIA->GridAddRowCount;
}

// Initialize aggregate
$SIS_COMPANIA->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_COMPANIA->ResetAttrs();
$SIS_COMPANIA_list->RenderRow();
while ($SIS_COMPANIA_list->RecCnt < $SIS_COMPANIA_list->StopRec) {
	$SIS_COMPANIA_list->RecCnt++;
	if (intval($SIS_COMPANIA_list->RecCnt) >= intval($SIS_COMPANIA_list->StartRec)) {
		$SIS_COMPANIA_list->RowCnt++;

		// Set up key count
		$SIS_COMPANIA_list->KeyCount = $SIS_COMPANIA_list->RowIndex;

		// Init row class and style
		$SIS_COMPANIA->ResetAttrs();
		$SIS_COMPANIA->CssClass = "";
		if ($SIS_COMPANIA->CurrentAction == "gridadd") {
		} else {
			$SIS_COMPANIA_list->LoadRowValues($SIS_COMPANIA_list->Recordset); // Load row values
		}
		$SIS_COMPANIA->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$SIS_COMPANIA->RowAttrs = array_merge($SIS_COMPANIA->RowAttrs, array('data-rowindex'=>$SIS_COMPANIA_list->RowCnt, 'id'=>'r' . $SIS_COMPANIA_list->RowCnt . '_SIS_COMPANIA', 'data-rowtype'=>$SIS_COMPANIA->RowType));

		// Render row
		$SIS_COMPANIA_list->RenderRow();

		// Render list options
		$SIS_COMPANIA_list->RenderListOptions();
?>
	<tr<?php echo $SIS_COMPANIA->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_COMPANIA_list->ListOptions->Render("body", "left", $SIS_COMPANIA_list->RowCnt);
?>
	<?php if ($SIS_COMPANIA->codcompania->Visible) { // codcompania ?>
		<td data-name="codcompania"<?php echo $SIS_COMPANIA->codcompania->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_codcompania" class="SIS_COMPANIA_codcompania">
<span<?php echo $SIS_COMPANIA->codcompania->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->codcompania->ListViewValue() ?></span>
</span>
<a id="<?php echo $SIS_COMPANIA_list->PageObjName . "_row_" . $SIS_COMPANIA_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $SIS_COMPANIA->nombre->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_nombre" class="SIS_COMPANIA_nombre">
<span<?php echo $SIS_COMPANIA->nombre->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->nombre->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $SIS_COMPANIA->telefono->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_telefono" class="SIS_COMPANIA_telefono">
<span<?php echo $SIS_COMPANIA->telefono->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->telefono->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $SIS_COMPANIA->direccion->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_direccion" class="SIS_COMPANIA_direccion">
<span<?php echo $SIS_COMPANIA->direccion->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->descuento->Visible) { // descuento ?>
		<td data-name="descuento"<?php echo $SIS_COMPANIA->descuento->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_descuento" class="SIS_COMPANIA_descuento">
<span<?php echo $SIS_COMPANIA->descuento->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->descuento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->piefactura->Visible) { // piefactura ?>
		<td data-name="piefactura"<?php echo $SIS_COMPANIA->piefactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_piefactura" class="SIS_COMPANIA_piefactura">
<span<?php echo $SIS_COMPANIA->piefactura->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->piefactura->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->leyendafactura->Visible) { // leyendafactura ?>
		<td data-name="leyendafactura"<?php echo $SIS_COMPANIA->leyendafactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_leyendafactura" class="SIS_COMPANIA_leyendafactura">
<span<?php echo $SIS_COMPANIA->leyendafactura->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->leyendafactura->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_COMPANIA->trasnporteenfactura->Visible) { // trasnporteenfactura ?>
		<td data-name="trasnporteenfactura"<?php echo $SIS_COMPANIA->trasnporteenfactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_list->RowCnt ?>_SIS_COMPANIA_trasnporteenfactura" class="SIS_COMPANIA_trasnporteenfactura">
<span<?php echo $SIS_COMPANIA->trasnporteenfactura->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->trasnporteenfactura->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_COMPANIA_list->ListOptions->Render("body", "right", $SIS_COMPANIA_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($SIS_COMPANIA->CurrentAction <> "gridadd")
		$SIS_COMPANIA_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($SIS_COMPANIA->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($SIS_COMPANIA_list->Recordset)
	$SIS_COMPANIA_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($SIS_COMPANIA->CurrentAction <> "gridadd" && $SIS_COMPANIA->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($SIS_COMPANIA_list->Pager)) $SIS_COMPANIA_list->Pager = new cPrevNextPager($SIS_COMPANIA_list->StartRec, $SIS_COMPANIA_list->DisplayRecs, $SIS_COMPANIA_list->TotalRecs) ?>
<?php if ($SIS_COMPANIA_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($SIS_COMPANIA_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $SIS_COMPANIA_list->PageUrl() ?>start=<?php echo $SIS_COMPANIA_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($SIS_COMPANIA_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $SIS_COMPANIA_list->PageUrl() ?>start=<?php echo $SIS_COMPANIA_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $SIS_COMPANIA_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($SIS_COMPANIA_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $SIS_COMPANIA_list->PageUrl() ?>start=<?php echo $SIS_COMPANIA_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($SIS_COMPANIA_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $SIS_COMPANIA_list->PageUrl() ?>start=<?php echo $SIS_COMPANIA_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $SIS_COMPANIA_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $SIS_COMPANIA_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $SIS_COMPANIA_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $SIS_COMPANIA_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_COMPANIA_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($SIS_COMPANIA_list->TotalRecs == 0 && $SIS_COMPANIA->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_COMPANIA_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fSIS_COMPANIAlistsrch.Init();
fSIS_COMPANIAlistsrch.FilterList = <?php echo $SIS_COMPANIA_list->GetFilterList() ?>;
fSIS_COMPANIAlist.Init();
</script>
<?php
$SIS_COMPANIA_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_COMPANIA_list->Page_Terminate();
?>
