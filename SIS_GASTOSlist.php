<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_GASTOSinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_GASTOS_list = NULL; // Initialize page object first

class cSIS_GASTOS_list extends cSIS_GASTOS {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_GASTOS';

	// Page object name
	var $PageObjName = 'SIS_GASTOS_list';

	// Grid form hidden field names
	var $FormName = 'fSIS_GASTOSlist';
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

		// Table object (SIS_GASTOS)
		if (!isset($GLOBALS["SIS_GASTOS"]) || get_class($GLOBALS["SIS_GASTOS"]) == "cSIS_GASTOS") {
			$GLOBALS["SIS_GASTOS"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_GASTOS"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "SIS_GASTOSadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "SIS_GASTOSdelete.php";
		$this->MultiUpdateUrl = "SIS_GASTOSupdate.php";

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_GASTOS', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fSIS_GASTOSlistsrch";

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
		$this->consecutivo->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->fecha->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $SIS_GASTOS;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_GASTOS);
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
			$this->consecutivo->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->consecutivo->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->consecutivo->AdvancedSearch->ToJSON(), ","); // Field consecutivo
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJSON(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->tipo->AdvancedSearch->ToJSON(), ","); // Field tipo
		$sFilterList = ew_Concat($sFilterList, $this->codproveedor->AdvancedSearch->ToJSON(), ","); // Field codproveedor
		$sFilterList = ew_Concat($sFilterList, $this->factura->AdvancedSearch->ToJSON(), ","); // Field factura
		$sFilterList = ew_Concat($sFilterList, $this->descripcion->AdvancedSearch->ToJSON(), ","); // Field descripcion
		$sFilterList = ew_Concat($sFilterList, $this->cantidad->AdvancedSearch->ToJSON(), ","); // Field cantidad
		$sFilterList = ew_Concat($sFilterList, $this->monto->AdvancedSearch->ToJSON(), ","); // Field monto
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

		// Field consecutivo
		$this->consecutivo->AdvancedSearch->SearchValue = @$filter["x_consecutivo"];
		$this->consecutivo->AdvancedSearch->SearchOperator = @$filter["z_consecutivo"];
		$this->consecutivo->AdvancedSearch->SearchCondition = @$filter["v_consecutivo"];
		$this->consecutivo->AdvancedSearch->SearchValue2 = @$filter["y_consecutivo"];
		$this->consecutivo->AdvancedSearch->SearchOperator2 = @$filter["w_consecutivo"];
		$this->consecutivo->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

		// Field tipo
		$this->tipo->AdvancedSearch->SearchValue = @$filter["x_tipo"];
		$this->tipo->AdvancedSearch->SearchOperator = @$filter["z_tipo"];
		$this->tipo->AdvancedSearch->SearchCondition = @$filter["v_tipo"];
		$this->tipo->AdvancedSearch->SearchValue2 = @$filter["y_tipo"];
		$this->tipo->AdvancedSearch->SearchOperator2 = @$filter["w_tipo"];
		$this->tipo->AdvancedSearch->Save();

		// Field codproveedor
		$this->codproveedor->AdvancedSearch->SearchValue = @$filter["x_codproveedor"];
		$this->codproveedor->AdvancedSearch->SearchOperator = @$filter["z_codproveedor"];
		$this->codproveedor->AdvancedSearch->SearchCondition = @$filter["v_codproveedor"];
		$this->codproveedor->AdvancedSearch->SearchValue2 = @$filter["y_codproveedor"];
		$this->codproveedor->AdvancedSearch->SearchOperator2 = @$filter["w_codproveedor"];
		$this->codproveedor->AdvancedSearch->Save();

		// Field factura
		$this->factura->AdvancedSearch->SearchValue = @$filter["x_factura"];
		$this->factura->AdvancedSearch->SearchOperator = @$filter["z_factura"];
		$this->factura->AdvancedSearch->SearchCondition = @$filter["v_factura"];
		$this->factura->AdvancedSearch->SearchValue2 = @$filter["y_factura"];
		$this->factura->AdvancedSearch->SearchOperator2 = @$filter["w_factura"];
		$this->factura->AdvancedSearch->Save();

		// Field descripcion
		$this->descripcion->AdvancedSearch->SearchValue = @$filter["x_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator = @$filter["z_descripcion"];
		$this->descripcion->AdvancedSearch->SearchCondition = @$filter["v_descripcion"];
		$this->descripcion->AdvancedSearch->SearchValue2 = @$filter["y_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator2 = @$filter["w_descripcion"];
		$this->descripcion->AdvancedSearch->Save();

		// Field cantidad
		$this->cantidad->AdvancedSearch->SearchValue = @$filter["x_cantidad"];
		$this->cantidad->AdvancedSearch->SearchOperator = @$filter["z_cantidad"];
		$this->cantidad->AdvancedSearch->SearchCondition = @$filter["v_cantidad"];
		$this->cantidad->AdvancedSearch->SearchValue2 = @$filter["y_cantidad"];
		$this->cantidad->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad"];
		$this->cantidad->AdvancedSearch->Save();

		// Field monto
		$this->monto->AdvancedSearch->SearchValue = @$filter["x_monto"];
		$this->monto->AdvancedSearch->SearchOperator = @$filter["z_monto"];
		$this->monto->AdvancedSearch->SearchCondition = @$filter["v_monto"];
		$this->monto->AdvancedSearch->SearchValue2 = @$filter["y_monto"];
		$this->monto->AdvancedSearch->SearchOperator2 = @$filter["w_monto"];
		$this->monto->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->fecha, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion, $arKeywords, $type);
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
			$this->UpdateSort($this->consecutivo); // consecutivo
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->tipo); // tipo
			$this->UpdateSort($this->codproveedor); // codproveedor
			$this->UpdateSort($this->factura); // factura
			$this->UpdateSort($this->descripcion); // descripcion
			$this->UpdateSort($this->cantidad); // cantidad
			$this->UpdateSort($this->monto); // monto
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
				$this->consecutivo->setSort("");
				$this->fecha->setSort("");
				$this->tipo->setSort("");
				$this->codproveedor->setSort("");
				$this->factura->setSort("");
				$this->descripcion->setSort("");
				$this->cantidad->setSort("");
				$this->monto->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->consecutivo->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fSIS_GASTOSlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fSIS_GASTOSlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fSIS_GASTOSlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fSIS_GASTOSlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->consecutivo->setDbValue($rs->fields('consecutivo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->codproveedor->setDbValue($rs->fields('codproveedor'));
		$this->factura->setDbValue($rs->fields('factura'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->monto->setDbValue($rs->fields('monto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->consecutivo->DbValue = $row['consecutivo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->tipo->DbValue = $row['tipo'];
		$this->codproveedor->DbValue = $row['codproveedor'];
		$this->factura->DbValue = $row['factura'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->cantidad->DbValue = $row['cantidad'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ew_StrToFloat($this->monto->CurrentValue)))
			$this->monto->CurrentValue = ew_StrToFloat($this->monto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// consecutivo
		// fecha
		// tipo
		// codproveedor
		// factura
		// descripcion
		// cantidad
		// monto
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->monto->CurrentValue))
				$this->monto->Total += $this->monto->CurrentValue; // Accumulate total
		}
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

		// codproveedor
		if (strval($this->codproveedor->CurrentValue) <> "") {
			$sFilterWrk = "[codproveedor]" . ew_SearchString("=", $this->codproveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codproveedor], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_PROVEEDORES]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codproveedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codproveedor->ViewValue = $this->codproveedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codproveedor->ViewValue = $this->codproveedor->CurrentValue;
			}
		} else {
			$this->codproveedor->ViewValue = NULL;
		}
		$this->codproveedor->ViewCustomAttributes = "";

		// factura
		$this->factura->ViewValue = $this->factura->CurrentValue;
		$this->factura->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

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

			// codproveedor
			$this->codproveedor->LinkCustomAttributes = "";
			$this->codproveedor->HrefValue = "";
			$this->codproveedor->TooltipValue = "";

			// factura
			$this->factura->LinkCustomAttributes = "";
			$this->factura->HrefValue = "";
			$this->factura->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// monto
			$this->monto->LinkCustomAttributes = "";
			$this->monto->HrefValue = "";
			$this->monto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->monto->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->monto->CurrentValue = $this->monto->Total;
			$this->monto->ViewValue = $this->monto->CurrentValue;
			$this->monto->ViewCustomAttributes = "";
			$this->monto->HrefValue = ""; // Clear href value
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
if (!isset($SIS_GASTOS_list)) $SIS_GASTOS_list = new cSIS_GASTOS_list();

// Page init
$SIS_GASTOS_list->Page_Init();

// Page main
$SIS_GASTOS_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_GASTOS_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fSIS_GASTOSlist = new ew_Form("fSIS_GASTOSlist", "list");
fSIS_GASTOSlist.FormKeyCountName = '<?php echo $SIS_GASTOS_list->FormKeyCountName ?>';

// Form_CustomValidate event
fSIS_GASTOSlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_GASTOSlist.ValidateRequired = true;
<?php } else { ?>
fSIS_GASTOSlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_GASTOSlist.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_GASTOSlist.Lists["x_tipo"].Options = <?php echo json_encode($SIS_GASTOS->tipo->Options()) ?>;
fSIS_GASTOSlist.Lists["x_codproveedor"] = {"LinkField":"x_codproveedor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fSIS_GASTOSlistsrch = new ew_Form("fSIS_GASTOSlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($SIS_GASTOS_list->TotalRecs > 0 && $SIS_GASTOS_list->ExportOptions->Visible()) { ?>
<?php $SIS_GASTOS_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_GASTOS_list->SearchOptions->Visible()) { ?>
<?php $SIS_GASTOS_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_GASTOS_list->FilterOptions->Visible()) { ?>
<?php $SIS_GASTOS_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $SIS_GASTOS_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_GASTOS_list->TotalRecs <= 0)
			$SIS_GASTOS_list->TotalRecs = $SIS_GASTOS->SelectRecordCount();
	} else {
		if (!$SIS_GASTOS_list->Recordset && ($SIS_GASTOS_list->Recordset = $SIS_GASTOS_list->LoadRecordset()))
			$SIS_GASTOS_list->TotalRecs = $SIS_GASTOS_list->Recordset->RecordCount();
	}
	$SIS_GASTOS_list->StartRec = 1;
	if ($SIS_GASTOS_list->DisplayRecs <= 0 || ($SIS_GASTOS->Export <> "" && $SIS_GASTOS->ExportAll)) // Display all records
		$SIS_GASTOS_list->DisplayRecs = $SIS_GASTOS_list->TotalRecs;
	if (!($SIS_GASTOS->Export <> "" && $SIS_GASTOS->ExportAll))
		$SIS_GASTOS_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$SIS_GASTOS_list->Recordset = $SIS_GASTOS_list->LoadRecordset($SIS_GASTOS_list->StartRec-1, $SIS_GASTOS_list->DisplayRecs);

	// Set no record found message
	if ($SIS_GASTOS->CurrentAction == "" && $SIS_GASTOS_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_GASTOS_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_GASTOS_list->SearchWhere == "0=101")
			$SIS_GASTOS_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_GASTOS_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$SIS_GASTOS_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($SIS_GASTOS->Export == "" && $SIS_GASTOS->CurrentAction == "") { ?>
<form name="fSIS_GASTOSlistsrch" id="fSIS_GASTOSlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($SIS_GASTOS_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fSIS_GASTOSlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="SIS_GASTOS">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($SIS_GASTOS_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($SIS_GASTOS_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $SIS_GASTOS_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($SIS_GASTOS_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($SIS_GASTOS_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($SIS_GASTOS_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($SIS_GASTOS_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $SIS_GASTOS_list->ShowPageHeader(); ?>
<?php
$SIS_GASTOS_list->ShowMessage();
?>
<?php if ($SIS_GASTOS_list->TotalRecs > 0 || $SIS_GASTOS->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fSIS_GASTOSlist" id="fSIS_GASTOSlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_GASTOS_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_GASTOS_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_GASTOS">
<div id="gmp_SIS_GASTOS" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($SIS_GASTOS_list->TotalRecs > 0) { ?>
<table id="tbl_SIS_GASTOSlist" class="table ewTable">
<?php echo $SIS_GASTOS->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_GASTOS_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_GASTOS_list->RenderListOptions();

// Render list options (header, left)
$SIS_GASTOS_list->ListOptions->Render("header", "left");
?>
<?php if ($SIS_GASTOS->consecutivo->Visible) { // consecutivo ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->consecutivo) == "") { ?>
		<th data-name="consecutivo"><div id="elh_SIS_GASTOS_consecutivo" class="SIS_GASTOS_consecutivo"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->consecutivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="consecutivo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->consecutivo) ?>',1);"><div id="elh_SIS_GASTOS_consecutivo" class="SIS_GASTOS_consecutivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->consecutivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->consecutivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->consecutivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->fecha->Visible) { // fecha ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_SIS_GASTOS_fecha" class="SIS_GASTOS_fecha"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->fecha) ?>',1);"><div id="elh_SIS_GASTOS_fecha" class="SIS_GASTOS_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->fecha->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->tipo->Visible) { // tipo ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->tipo) == "") { ?>
		<th data-name="tipo"><div id="elh_SIS_GASTOS_tipo" class="SIS_GASTOS_tipo"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->tipo) ?>',1);"><div id="elh_SIS_GASTOS_tipo" class="SIS_GASTOS_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->codproveedor->Visible) { // codproveedor ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->codproveedor) == "") { ?>
		<th data-name="codproveedor"><div id="elh_SIS_GASTOS_codproveedor" class="SIS_GASTOS_codproveedor"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->codproveedor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codproveedor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->codproveedor) ?>',1);"><div id="elh_SIS_GASTOS_codproveedor" class="SIS_GASTOS_codproveedor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->codproveedor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->codproveedor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->codproveedor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->factura->Visible) { // factura ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->factura) == "") { ?>
		<th data-name="factura"><div id="elh_SIS_GASTOS_factura" class="SIS_GASTOS_factura"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->factura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="factura"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->factura) ?>',1);"><div id="elh_SIS_GASTOS_factura" class="SIS_GASTOS_factura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->factura->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->factura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->factura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->descripcion->Visible) { // descripcion ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_SIS_GASTOS_descripcion" class="SIS_GASTOS_descripcion"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->descripcion) ?>',1);"><div id="elh_SIS_GASTOS_descripcion" class="SIS_GASTOS_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->descripcion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->cantidad->Visible) { // cantidad ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->cantidad) == "") { ?>
		<th data-name="cantidad"><div id="elh_SIS_GASTOS_cantidad" class="SIS_GASTOS_cantidad"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->cantidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cantidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->cantidad) ?>',1);"><div id="elh_SIS_GASTOS_cantidad" class="SIS_GASTOS_cantidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->cantidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->cantidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->cantidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_GASTOS->monto->Visible) { // monto ?>
	<?php if ($SIS_GASTOS->SortUrl($SIS_GASTOS->monto) == "") { ?>
		<th data-name="monto"><div id="elh_SIS_GASTOS_monto" class="SIS_GASTOS_monto"><div class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->monto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="monto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_GASTOS->SortUrl($SIS_GASTOS->monto) ?>',1);"><div id="elh_SIS_GASTOS_monto" class="SIS_GASTOS_monto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_GASTOS->monto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_GASTOS->monto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_GASTOS->monto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_GASTOS_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($SIS_GASTOS->ExportAll && $SIS_GASTOS->Export <> "") {
	$SIS_GASTOS_list->StopRec = $SIS_GASTOS_list->TotalRecs;
} else {

	// Set the last record to display
	if ($SIS_GASTOS_list->TotalRecs > $SIS_GASTOS_list->StartRec + $SIS_GASTOS_list->DisplayRecs - 1)
		$SIS_GASTOS_list->StopRec = $SIS_GASTOS_list->StartRec + $SIS_GASTOS_list->DisplayRecs - 1;
	else
		$SIS_GASTOS_list->StopRec = $SIS_GASTOS_list->TotalRecs;
}
$SIS_GASTOS_list->RecCnt = $SIS_GASTOS_list->StartRec - 1;
if ($SIS_GASTOS_list->Recordset && !$SIS_GASTOS_list->Recordset->EOF) {
	$SIS_GASTOS_list->Recordset->MoveFirst();
	$bSelectLimit = $SIS_GASTOS_list->UseSelectLimit;
	if (!$bSelectLimit && $SIS_GASTOS_list->StartRec > 1)
		$SIS_GASTOS_list->Recordset->Move($SIS_GASTOS_list->StartRec - 1);
} elseif (!$SIS_GASTOS->AllowAddDeleteRow && $SIS_GASTOS_list->StopRec == 0) {
	$SIS_GASTOS_list->StopRec = $SIS_GASTOS->GridAddRowCount;
}

// Initialize aggregate
$SIS_GASTOS->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_GASTOS->ResetAttrs();
$SIS_GASTOS_list->RenderRow();
while ($SIS_GASTOS_list->RecCnt < $SIS_GASTOS_list->StopRec) {
	$SIS_GASTOS_list->RecCnt++;
	if (intval($SIS_GASTOS_list->RecCnt) >= intval($SIS_GASTOS_list->StartRec)) {
		$SIS_GASTOS_list->RowCnt++;

		// Set up key count
		$SIS_GASTOS_list->KeyCount = $SIS_GASTOS_list->RowIndex;

		// Init row class and style
		$SIS_GASTOS->ResetAttrs();
		$SIS_GASTOS->CssClass = "";
		if ($SIS_GASTOS->CurrentAction == "gridadd") {
		} else {
			$SIS_GASTOS_list->LoadRowValues($SIS_GASTOS_list->Recordset); // Load row values
		}
		$SIS_GASTOS->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$SIS_GASTOS->RowAttrs = array_merge($SIS_GASTOS->RowAttrs, array('data-rowindex'=>$SIS_GASTOS_list->RowCnt, 'id'=>'r' . $SIS_GASTOS_list->RowCnt . '_SIS_GASTOS', 'data-rowtype'=>$SIS_GASTOS->RowType));

		// Render row
		$SIS_GASTOS_list->RenderRow();

		// Render list options
		$SIS_GASTOS_list->RenderListOptions();
?>
	<tr<?php echo $SIS_GASTOS->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_GASTOS_list->ListOptions->Render("body", "left", $SIS_GASTOS_list->RowCnt);
?>
	<?php if ($SIS_GASTOS->consecutivo->Visible) { // consecutivo ?>
		<td data-name="consecutivo"<?php echo $SIS_GASTOS->consecutivo->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_consecutivo" class="SIS_GASTOS_consecutivo">
<span<?php echo $SIS_GASTOS->consecutivo->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->consecutivo->ListViewValue() ?></span>
</span>
<a id="<?php echo $SIS_GASTOS_list->PageObjName . "_row_" . $SIS_GASTOS_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $SIS_GASTOS->fecha->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_fecha" class="SIS_GASTOS_fecha">
<span<?php echo $SIS_GASTOS->fecha->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_GASTOS->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $SIS_GASTOS->tipo->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_tipo" class="SIS_GASTOS_tipo">
<span<?php echo $SIS_GASTOS->tipo->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->tipo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_GASTOS->codproveedor->Visible) { // codproveedor ?>
		<td data-name="codproveedor"<?php echo $SIS_GASTOS->codproveedor->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_codproveedor" class="SIS_GASTOS_codproveedor">
<span<?php echo $SIS_GASTOS->codproveedor->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->codproveedor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_GASTOS->factura->Visible) { // factura ?>
		<td data-name="factura"<?php echo $SIS_GASTOS->factura->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_factura" class="SIS_GASTOS_factura">
<span<?php echo $SIS_GASTOS->factura->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->factura->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_GASTOS->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $SIS_GASTOS->descripcion->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_descripcion" class="SIS_GASTOS_descripcion">
<span<?php echo $SIS_GASTOS->descripcion->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->descripcion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_GASTOS->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"<?php echo $SIS_GASTOS->cantidad->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_cantidad" class="SIS_GASTOS_cantidad">
<span<?php echo $SIS_GASTOS->cantidad->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->cantidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_GASTOS->monto->Visible) { // monto ?>
		<td data-name="monto"<?php echo $SIS_GASTOS->monto->CellAttributes() ?>>
<span id="el<?php echo $SIS_GASTOS_list->RowCnt ?>_SIS_GASTOS_monto" class="SIS_GASTOS_monto">
<span<?php echo $SIS_GASTOS->monto->ViewAttributes() ?>>
<?php echo $SIS_GASTOS->monto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_GASTOS_list->ListOptions->Render("body", "right", $SIS_GASTOS_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($SIS_GASTOS->CurrentAction <> "gridadd")
		$SIS_GASTOS_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$SIS_GASTOS->RowType = EW_ROWTYPE_AGGREGATE;
$SIS_GASTOS->ResetAttrs();
$SIS_GASTOS_list->RenderRow();
?>
<?php if ($SIS_GASTOS_list->TotalRecs > 0 && ($SIS_GASTOS->CurrentAction <> "gridadd" && $SIS_GASTOS->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$SIS_GASTOS_list->RenderListOptions();

// Render list options (footer, left)
$SIS_GASTOS_list->ListOptions->Render("footer", "left");
?>
	<?php if ($SIS_GASTOS->consecutivo->Visible) { // consecutivo ?>
		<td data-name="consecutivo"><span id="elf_SIS_GASTOS_consecutivo" class="SIS_GASTOS_consecutivo">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->fecha->Visible) { // fecha ?>
		<td data-name="fecha"><span id="elf_SIS_GASTOS_fecha" class="SIS_GASTOS_fecha">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->tipo->Visible) { // tipo ?>
		<td data-name="tipo"><span id="elf_SIS_GASTOS_tipo" class="SIS_GASTOS_tipo">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->codproveedor->Visible) { // codproveedor ?>
		<td data-name="codproveedor"><span id="elf_SIS_GASTOS_codproveedor" class="SIS_GASTOS_codproveedor">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->factura->Visible) { // factura ?>
		<td data-name="factura"><span id="elf_SIS_GASTOS_factura" class="SIS_GASTOS_factura">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"><span id="elf_SIS_GASTOS_descripcion" class="SIS_GASTOS_descripcion">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"><span id="elf_SIS_GASTOS_cantidad" class="SIS_GASTOS_cantidad">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($SIS_GASTOS->monto->Visible) { // monto ?>
		<td data-name="monto"><span id="elf_SIS_GASTOS_monto" class="SIS_GASTOS_monto">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $SIS_GASTOS->monto->ViewValue ?>
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$SIS_GASTOS_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($SIS_GASTOS->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($SIS_GASTOS_list->Recordset)
	$SIS_GASTOS_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($SIS_GASTOS->CurrentAction <> "gridadd" && $SIS_GASTOS->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($SIS_GASTOS_list->Pager)) $SIS_GASTOS_list->Pager = new cPrevNextPager($SIS_GASTOS_list->StartRec, $SIS_GASTOS_list->DisplayRecs, $SIS_GASTOS_list->TotalRecs) ?>
<?php if ($SIS_GASTOS_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($SIS_GASTOS_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $SIS_GASTOS_list->PageUrl() ?>start=<?php echo $SIS_GASTOS_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($SIS_GASTOS_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $SIS_GASTOS_list->PageUrl() ?>start=<?php echo $SIS_GASTOS_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $SIS_GASTOS_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($SIS_GASTOS_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $SIS_GASTOS_list->PageUrl() ?>start=<?php echo $SIS_GASTOS_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($SIS_GASTOS_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $SIS_GASTOS_list->PageUrl() ?>start=<?php echo $SIS_GASTOS_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $SIS_GASTOS_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $SIS_GASTOS_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $SIS_GASTOS_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $SIS_GASTOS_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_GASTOS_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($SIS_GASTOS_list->TotalRecs == 0 && $SIS_GASTOS->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_GASTOS_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fSIS_GASTOSlistsrch.Init();
fSIS_GASTOSlistsrch.FilterList = <?php echo $SIS_GASTOS_list->GetFilterList() ?>;
fSIS_GASTOSlist.Init();
</script>
<?php
$SIS_GASTOS_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_GASTOS_list->Page_Terminate();
?>
