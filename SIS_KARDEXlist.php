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

$SIS_KARDEX_list = NULL; // Initialize page object first

class cSIS_KARDEX_list extends cSIS_KARDEX {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_KARDEX';

	// Page object name
	var $PageObjName = 'SIS_KARDEX_list';

	// Grid form hidden field names
	var $FormName = 'fSIS_KARDEXlist';
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

		// Table object (SIS_KARDEX)
		if (!isset($GLOBALS["SIS_KARDEX"]) || get_class($GLOBALS["SIS_KARDEX"]) == "cSIS_KARDEX") {
			$GLOBALS["SIS_KARDEX"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_KARDEX"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "SIS_KARDEXadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "SIS_KARDEXdelete.php";
		$this->MultiUpdateUrl = "SIS_KARDEXupdate.php";

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fSIS_KARDEXlistsrch";

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
		if (count($arrKeyFlds) >= 3) {
			$this->consecutivo->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->consecutivo->FormValue))
				return FALSE;
			$this->fecha->setFormValue($arrKeyFlds[1]);
			$this->codarticulo->setFormValue($arrKeyFlds[2]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->consecutivo->AdvancedSearch->ToJSON(), ","); // Field consecutivo
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJSON(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->numfactura->AdvancedSearch->ToJSON(), ","); // Field numfactura
		$sFilterList = ew_Concat($sFilterList, $this->codarticulo->AdvancedSearch->ToJSON(), ","); // Field codarticulo
		$sFilterList = ew_Concat($sFilterList, $this->movimiento->AdvancedSearch->ToJSON(), ","); // Field movimiento
		$sFilterList = ew_Concat($sFilterList, $this->cantidad->AdvancedSearch->ToJSON(), ","); // Field cantidad
		$sFilterList = ew_Concat($sFilterList, $this->saldo->AdvancedSearch->ToJSON(), ","); // Field saldo
		$sFilterList = ew_Concat($sFilterList, $this->Descripcion->AdvancedSearch->ToJSON(), ","); // Field Descripcion
		$sFilterList = ew_Concat($sFilterList, $this->usuarioultimamod->AdvancedSearch->ToJSON(), ","); // Field usuarioultimamod
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

		// Field numfactura
		$this->numfactura->AdvancedSearch->SearchValue = @$filter["x_numfactura"];
		$this->numfactura->AdvancedSearch->SearchOperator = @$filter["z_numfactura"];
		$this->numfactura->AdvancedSearch->SearchCondition = @$filter["v_numfactura"];
		$this->numfactura->AdvancedSearch->SearchValue2 = @$filter["y_numfactura"];
		$this->numfactura->AdvancedSearch->SearchOperator2 = @$filter["w_numfactura"];
		$this->numfactura->AdvancedSearch->Save();

		// Field codarticulo
		$this->codarticulo->AdvancedSearch->SearchValue = @$filter["x_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchOperator = @$filter["z_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchCondition = @$filter["v_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchValue2 = @$filter["y_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchOperator2 = @$filter["w_codarticulo"];
		$this->codarticulo->AdvancedSearch->Save();

		// Field movimiento
		$this->movimiento->AdvancedSearch->SearchValue = @$filter["x_movimiento"];
		$this->movimiento->AdvancedSearch->SearchOperator = @$filter["z_movimiento"];
		$this->movimiento->AdvancedSearch->SearchCondition = @$filter["v_movimiento"];
		$this->movimiento->AdvancedSearch->SearchValue2 = @$filter["y_movimiento"];
		$this->movimiento->AdvancedSearch->SearchOperator2 = @$filter["w_movimiento"];
		$this->movimiento->AdvancedSearch->Save();

		// Field cantidad
		$this->cantidad->AdvancedSearch->SearchValue = @$filter["x_cantidad"];
		$this->cantidad->AdvancedSearch->SearchOperator = @$filter["z_cantidad"];
		$this->cantidad->AdvancedSearch->SearchCondition = @$filter["v_cantidad"];
		$this->cantidad->AdvancedSearch->SearchValue2 = @$filter["y_cantidad"];
		$this->cantidad->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad"];
		$this->cantidad->AdvancedSearch->Save();

		// Field saldo
		$this->saldo->AdvancedSearch->SearchValue = @$filter["x_saldo"];
		$this->saldo->AdvancedSearch->SearchOperator = @$filter["z_saldo"];
		$this->saldo->AdvancedSearch->SearchCondition = @$filter["v_saldo"];
		$this->saldo->AdvancedSearch->SearchValue2 = @$filter["y_saldo"];
		$this->saldo->AdvancedSearch->SearchOperator2 = @$filter["w_saldo"];
		$this->saldo->AdvancedSearch->Save();

		// Field Descripcion
		$this->Descripcion->AdvancedSearch->SearchValue = @$filter["x_Descripcion"];
		$this->Descripcion->AdvancedSearch->SearchOperator = @$filter["z_Descripcion"];
		$this->Descripcion->AdvancedSearch->SearchCondition = @$filter["v_Descripcion"];
		$this->Descripcion->AdvancedSearch->SearchValue2 = @$filter["y_Descripcion"];
		$this->Descripcion->AdvancedSearch->SearchOperator2 = @$filter["w_Descripcion"];
		$this->Descripcion->AdvancedSearch->Save();

		// Field usuarioultimamod
		$this->usuarioultimamod->AdvancedSearch->SearchValue = @$filter["x_usuarioultimamod"];
		$this->usuarioultimamod->AdvancedSearch->SearchOperator = @$filter["z_usuarioultimamod"];
		$this->usuarioultimamod->AdvancedSearch->SearchCondition = @$filter["v_usuarioultimamod"];
		$this->usuarioultimamod->AdvancedSearch->SearchValue2 = @$filter["y_usuarioultimamod"];
		$this->usuarioultimamod->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioultimamod"];
		$this->usuarioultimamod->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->fecha, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Descripcion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->usuarioultimamod, $arKeywords, $type);
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
			$this->UpdateSort($this->numfactura); // numfactura
			$this->UpdateSort($this->codarticulo); // codarticulo
			$this->UpdateSort($this->movimiento); // movimiento
			$this->UpdateSort($this->cantidad); // cantidad
			$this->UpdateSort($this->saldo); // saldo
			$this->UpdateSort($this->Descripcion); // Descripcion
			$this->UpdateSort($this->usuarioultimamod); // usuarioultimamod
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
				$this->numfactura->setSort("");
				$this->codarticulo->setSort("");
				$this->movimiento->setSort("");
				$this->cantidad->setSort("");
				$this->saldo->setSort("");
				$this->Descripcion->setSort("");
				$this->usuarioultimamod->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->consecutivo->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->fecha->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->codarticulo->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fSIS_KARDEXlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fSIS_KARDEXlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fSIS_KARDEXlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fSIS_KARDEXlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("consecutivo")) <> "")
			$this->consecutivo->CurrentValue = $this->getKey("consecutivo"); // consecutivo
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("fecha")) <> "")
			$this->fecha->CurrentValue = $this->getKey("fecha"); // fecha
		else
			$bValidKey = FALSE;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

		$this->fecultimamod->CellCssStyle = "white-space: nowrap;";

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
if (!isset($SIS_KARDEX_list)) $SIS_KARDEX_list = new cSIS_KARDEX_list();

// Page init
$SIS_KARDEX_list->Page_Init();

// Page main
$SIS_KARDEX_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_KARDEX_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fSIS_KARDEXlist = new ew_Form("fSIS_KARDEXlist", "list");
fSIS_KARDEXlist.FormKeyCountName = '<?php echo $SIS_KARDEX_list->FormKeyCountName ?>';

// Form_CustomValidate event
fSIS_KARDEXlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_KARDEXlist.ValidateRequired = true;
<?php } else { ?>
fSIS_KARDEXlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fSIS_KARDEXlistsrch = new ew_Form("fSIS_KARDEXlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($SIS_KARDEX_list->TotalRecs > 0 && $SIS_KARDEX_list->ExportOptions->Visible()) { ?>
<?php $SIS_KARDEX_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_KARDEX_list->SearchOptions->Visible()) { ?>
<?php $SIS_KARDEX_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_KARDEX_list->FilterOptions->Visible()) { ?>
<?php $SIS_KARDEX_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $SIS_KARDEX_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_KARDEX_list->TotalRecs <= 0)
			$SIS_KARDEX_list->TotalRecs = $SIS_KARDEX->SelectRecordCount();
	} else {
		if (!$SIS_KARDEX_list->Recordset && ($SIS_KARDEX_list->Recordset = $SIS_KARDEX_list->LoadRecordset()))
			$SIS_KARDEX_list->TotalRecs = $SIS_KARDEX_list->Recordset->RecordCount();
	}
	$SIS_KARDEX_list->StartRec = 1;
	if ($SIS_KARDEX_list->DisplayRecs <= 0 || ($SIS_KARDEX->Export <> "" && $SIS_KARDEX->ExportAll)) // Display all records
		$SIS_KARDEX_list->DisplayRecs = $SIS_KARDEX_list->TotalRecs;
	if (!($SIS_KARDEX->Export <> "" && $SIS_KARDEX->ExportAll))
		$SIS_KARDEX_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$SIS_KARDEX_list->Recordset = $SIS_KARDEX_list->LoadRecordset($SIS_KARDEX_list->StartRec-1, $SIS_KARDEX_list->DisplayRecs);

	// Set no record found message
	if ($SIS_KARDEX->CurrentAction == "" && $SIS_KARDEX_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_KARDEX_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_KARDEX_list->SearchWhere == "0=101")
			$SIS_KARDEX_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_KARDEX_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$SIS_KARDEX_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($SIS_KARDEX->Export == "" && $SIS_KARDEX->CurrentAction == "") { ?>
<form name="fSIS_KARDEXlistsrch" id="fSIS_KARDEXlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($SIS_KARDEX_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fSIS_KARDEXlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="SIS_KARDEX">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($SIS_KARDEX_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($SIS_KARDEX_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $SIS_KARDEX_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($SIS_KARDEX_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($SIS_KARDEX_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($SIS_KARDEX_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($SIS_KARDEX_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $SIS_KARDEX_list->ShowPageHeader(); ?>
<?php
$SIS_KARDEX_list->ShowMessage();
?>
<?php if ($SIS_KARDEX_list->TotalRecs > 0 || $SIS_KARDEX->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fSIS_KARDEXlist" id="fSIS_KARDEXlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_KARDEX_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_KARDEX_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_KARDEX">
<div id="gmp_SIS_KARDEX" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($SIS_KARDEX_list->TotalRecs > 0) { ?>
<table id="tbl_SIS_KARDEXlist" class="table ewTable">
<?php echo $SIS_KARDEX->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_KARDEX_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_KARDEX_list->RenderListOptions();

// Render list options (header, left)
$SIS_KARDEX_list->ListOptions->Render("header", "left");
?>
<?php if ($SIS_KARDEX->consecutivo->Visible) { // consecutivo ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->consecutivo) == "") { ?>
		<th data-name="consecutivo"><div id="elh_SIS_KARDEX_consecutivo" class="SIS_KARDEX_consecutivo"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->consecutivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="consecutivo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->consecutivo) ?>',1);"><div id="elh_SIS_KARDEX_consecutivo" class="SIS_KARDEX_consecutivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->consecutivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->consecutivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->consecutivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->fecha->Visible) { // fecha ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_SIS_KARDEX_fecha" class="SIS_KARDEX_fecha"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->fecha) ?>',1);"><div id="elh_SIS_KARDEX_fecha" class="SIS_KARDEX_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->fecha->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->numfactura->Visible) { // numfactura ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->numfactura) == "") { ?>
		<th data-name="numfactura"><div id="elh_SIS_KARDEX_numfactura" class="SIS_KARDEX_numfactura"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->numfactura->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numfactura"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->numfactura) ?>',1);"><div id="elh_SIS_KARDEX_numfactura" class="SIS_KARDEX_numfactura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->numfactura->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->numfactura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->numfactura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->codarticulo->Visible) { // codarticulo ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->codarticulo) == "") { ?>
		<th data-name="codarticulo"><div id="elh_SIS_KARDEX_codarticulo" class="SIS_KARDEX_codarticulo"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->codarticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codarticulo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->codarticulo) ?>',1);"><div id="elh_SIS_KARDEX_codarticulo" class="SIS_KARDEX_codarticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->codarticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->codarticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->codarticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->movimiento->Visible) { // movimiento ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->movimiento) == "") { ?>
		<th data-name="movimiento"><div id="elh_SIS_KARDEX_movimiento" class="SIS_KARDEX_movimiento"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->movimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="movimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->movimiento) ?>',1);"><div id="elh_SIS_KARDEX_movimiento" class="SIS_KARDEX_movimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->movimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->movimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->movimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->cantidad->Visible) { // cantidad ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->cantidad) == "") { ?>
		<th data-name="cantidad"><div id="elh_SIS_KARDEX_cantidad" class="SIS_KARDEX_cantidad"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->cantidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cantidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->cantidad) ?>',1);"><div id="elh_SIS_KARDEX_cantidad" class="SIS_KARDEX_cantidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->cantidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->cantidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->cantidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->saldo->Visible) { // saldo ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->saldo) == "") { ?>
		<th data-name="saldo"><div id="elh_SIS_KARDEX_saldo" class="SIS_KARDEX_saldo"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->saldo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->saldo) ?>',1);"><div id="elh_SIS_KARDEX_saldo" class="SIS_KARDEX_saldo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->saldo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->saldo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->saldo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->Descripcion->Visible) { // Descripcion ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->Descripcion) == "") { ?>
		<th data-name="Descripcion"><div id="elh_SIS_KARDEX_Descripcion" class="SIS_KARDEX_Descripcion"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->Descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Descripcion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->Descripcion) ?>',1);"><div id="elh_SIS_KARDEX_Descripcion" class="SIS_KARDEX_Descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->Descripcion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->Descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->Descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_KARDEX->usuarioultimamod->Visible) { // usuarioultimamod ?>
	<?php if ($SIS_KARDEX->SortUrl($SIS_KARDEX->usuarioultimamod) == "") { ?>
		<th data-name="usuarioultimamod"><div id="elh_SIS_KARDEX_usuarioultimamod" class="SIS_KARDEX_usuarioultimamod"><div class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->usuarioultimamod->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuarioultimamod"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_KARDEX->SortUrl($SIS_KARDEX->usuarioultimamod) ?>',1);"><div id="elh_SIS_KARDEX_usuarioultimamod" class="SIS_KARDEX_usuarioultimamod">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_KARDEX->usuarioultimamod->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_KARDEX->usuarioultimamod->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_KARDEX->usuarioultimamod->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_KARDEX_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($SIS_KARDEX->ExportAll && $SIS_KARDEX->Export <> "") {
	$SIS_KARDEX_list->StopRec = $SIS_KARDEX_list->TotalRecs;
} else {

	// Set the last record to display
	if ($SIS_KARDEX_list->TotalRecs > $SIS_KARDEX_list->StartRec + $SIS_KARDEX_list->DisplayRecs - 1)
		$SIS_KARDEX_list->StopRec = $SIS_KARDEX_list->StartRec + $SIS_KARDEX_list->DisplayRecs - 1;
	else
		$SIS_KARDEX_list->StopRec = $SIS_KARDEX_list->TotalRecs;
}
$SIS_KARDEX_list->RecCnt = $SIS_KARDEX_list->StartRec - 1;
if ($SIS_KARDEX_list->Recordset && !$SIS_KARDEX_list->Recordset->EOF) {
	$SIS_KARDEX_list->Recordset->MoveFirst();
	$bSelectLimit = $SIS_KARDEX_list->UseSelectLimit;
	if (!$bSelectLimit && $SIS_KARDEX_list->StartRec > 1)
		$SIS_KARDEX_list->Recordset->Move($SIS_KARDEX_list->StartRec - 1);
} elseif (!$SIS_KARDEX->AllowAddDeleteRow && $SIS_KARDEX_list->StopRec == 0) {
	$SIS_KARDEX_list->StopRec = $SIS_KARDEX->GridAddRowCount;
}

// Initialize aggregate
$SIS_KARDEX->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_KARDEX->ResetAttrs();
$SIS_KARDEX_list->RenderRow();
while ($SIS_KARDEX_list->RecCnt < $SIS_KARDEX_list->StopRec) {
	$SIS_KARDEX_list->RecCnt++;
	if (intval($SIS_KARDEX_list->RecCnt) >= intval($SIS_KARDEX_list->StartRec)) {
		$SIS_KARDEX_list->RowCnt++;

		// Set up key count
		$SIS_KARDEX_list->KeyCount = $SIS_KARDEX_list->RowIndex;

		// Init row class and style
		$SIS_KARDEX->ResetAttrs();
		$SIS_KARDEX->CssClass = "";
		if ($SIS_KARDEX->CurrentAction == "gridadd") {
		} else {
			$SIS_KARDEX_list->LoadRowValues($SIS_KARDEX_list->Recordset); // Load row values
		}
		$SIS_KARDEX->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$SIS_KARDEX->RowAttrs = array_merge($SIS_KARDEX->RowAttrs, array('data-rowindex'=>$SIS_KARDEX_list->RowCnt, 'id'=>'r' . $SIS_KARDEX_list->RowCnt . '_SIS_KARDEX', 'data-rowtype'=>$SIS_KARDEX->RowType));

		// Render row
		$SIS_KARDEX_list->RenderRow();

		// Render list options
		$SIS_KARDEX_list->RenderListOptions();
?>
	<tr<?php echo $SIS_KARDEX->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_KARDEX_list->ListOptions->Render("body", "left", $SIS_KARDEX_list->RowCnt);
?>
	<?php if ($SIS_KARDEX->consecutivo->Visible) { // consecutivo ?>
		<td data-name="consecutivo"<?php echo $SIS_KARDEX->consecutivo->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_consecutivo" class="SIS_KARDEX_consecutivo">
<span<?php echo $SIS_KARDEX->consecutivo->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->consecutivo->ListViewValue() ?></span>
</span>
<a id="<?php echo $SIS_KARDEX_list->PageObjName . "_row_" . $SIS_KARDEX_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_KARDEX->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $SIS_KARDEX->fecha->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_fecha" class="SIS_KARDEX_fecha">
<span<?php echo $SIS_KARDEX->fecha->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->numfactura->Visible) { // numfactura ?>
		<td data-name="numfactura"<?php echo $SIS_KARDEX->numfactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_numfactura" class="SIS_KARDEX_numfactura">
<span<?php echo $SIS_KARDEX->numfactura->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->numfactura->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->codarticulo->Visible) { // codarticulo ?>
		<td data-name="codarticulo"<?php echo $SIS_KARDEX->codarticulo->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_codarticulo" class="SIS_KARDEX_codarticulo">
<span<?php echo $SIS_KARDEX->codarticulo->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->codarticulo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->movimiento->Visible) { // movimiento ?>
		<td data-name="movimiento"<?php echo $SIS_KARDEX->movimiento->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_movimiento" class="SIS_KARDEX_movimiento">
<span<?php echo $SIS_KARDEX->movimiento->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->movimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"<?php echo $SIS_KARDEX->cantidad->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_cantidad" class="SIS_KARDEX_cantidad">
<span<?php echo $SIS_KARDEX->cantidad->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->cantidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->saldo->Visible) { // saldo ?>
		<td data-name="saldo"<?php echo $SIS_KARDEX->saldo->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_saldo" class="SIS_KARDEX_saldo">
<span<?php echo $SIS_KARDEX->saldo->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->saldo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->Descripcion->Visible) { // Descripcion ?>
		<td data-name="Descripcion"<?php echo $SIS_KARDEX->Descripcion->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_Descripcion" class="SIS_KARDEX_Descripcion">
<span<?php echo $SIS_KARDEX->Descripcion->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->Descripcion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_KARDEX->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<td data-name="usuarioultimamod"<?php echo $SIS_KARDEX->usuarioultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_KARDEX_list->RowCnt ?>_SIS_KARDEX_usuarioultimamod" class="SIS_KARDEX_usuarioultimamod">
<span<?php echo $SIS_KARDEX->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_KARDEX->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_KARDEX_list->ListOptions->Render("body", "right", $SIS_KARDEX_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($SIS_KARDEX->CurrentAction <> "gridadd")
		$SIS_KARDEX_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($SIS_KARDEX->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($SIS_KARDEX_list->Recordset)
	$SIS_KARDEX_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($SIS_KARDEX->CurrentAction <> "gridadd" && $SIS_KARDEX->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($SIS_KARDEX_list->Pager)) $SIS_KARDEX_list->Pager = new cPrevNextPager($SIS_KARDEX_list->StartRec, $SIS_KARDEX_list->DisplayRecs, $SIS_KARDEX_list->TotalRecs) ?>
<?php if ($SIS_KARDEX_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($SIS_KARDEX_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $SIS_KARDEX_list->PageUrl() ?>start=<?php echo $SIS_KARDEX_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($SIS_KARDEX_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $SIS_KARDEX_list->PageUrl() ?>start=<?php echo $SIS_KARDEX_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $SIS_KARDEX_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($SIS_KARDEX_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $SIS_KARDEX_list->PageUrl() ?>start=<?php echo $SIS_KARDEX_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($SIS_KARDEX_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $SIS_KARDEX_list->PageUrl() ?>start=<?php echo $SIS_KARDEX_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $SIS_KARDEX_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $SIS_KARDEX_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $SIS_KARDEX_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $SIS_KARDEX_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_KARDEX_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($SIS_KARDEX_list->TotalRecs == 0 && $SIS_KARDEX->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_KARDEX_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fSIS_KARDEXlistsrch.Init();
fSIS_KARDEXlistsrch.FilterList = <?php echo $SIS_KARDEX_list->GetFilterList() ?>;
fSIS_KARDEXlist.Init();
</script>
<?php
$SIS_KARDEX_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_KARDEX_list->Page_Terminate();
?>
