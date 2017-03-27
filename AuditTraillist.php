<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "AuditTrailinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$AuditTrail_list = NULL; // Initialize page object first

class cAuditTrail_list extends cAuditTrail {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'AuditTrail';

	// Page object name
	var $PageObjName = 'AuditTrail_list';

	// Grid form hidden field names
	var $FormName = 'fAuditTraillist';
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

		// Table object (AuditTrail)
		if (!isset($GLOBALS["AuditTrail"]) || get_class($GLOBALS["AuditTrail"]) == "cAuditTrail") {
			$GLOBALS["AuditTrail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["AuditTrail"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "AuditTrailadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "AuditTraildelete.php";
		$this->MultiUpdateUrl = "AuditTrailupdate.php";

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'AuditTrail', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fAuditTraillistsrch";

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
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $AuditTrail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($AuditTrail);
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
			$this->Id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Id->AdvancedSearch->ToJSON(), ","); // Field Id
		$sFilterList = ew_Concat($sFilterList, $this->DateTime->AdvancedSearch->ToJSON(), ","); // Field DateTime
		$sFilterList = ew_Concat($sFilterList, $this->Script->AdvancedSearch->ToJSON(), ","); // Field Script
		$sFilterList = ew_Concat($sFilterList, $this->User->AdvancedSearch->ToJSON(), ","); // Field User
		$sFilterList = ew_Concat($sFilterList, $this->Action->AdvancedSearch->ToJSON(), ","); // Field Action
		$sFilterList = ew_Concat($sFilterList, $this->_Table->AdvancedSearch->ToJSON(), ","); // Field Table
		$sFilterList = ew_Concat($sFilterList, $this->_Field->AdvancedSearch->ToJSON(), ","); // Field Field
		$sFilterList = ew_Concat($sFilterList, $this->KeyValue->AdvancedSearch->ToJSON(), ","); // Field KeyValue
		$sFilterList = ew_Concat($sFilterList, $this->OldValue->AdvancedSearch->ToJSON(), ","); // Field OldValue
		$sFilterList = ew_Concat($sFilterList, $this->NewValue->AdvancedSearch->ToJSON(), ","); // Field NewValue
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

		// Field Id
		$this->Id->AdvancedSearch->SearchValue = @$filter["x_Id"];
		$this->Id->AdvancedSearch->SearchOperator = @$filter["z_Id"];
		$this->Id->AdvancedSearch->SearchCondition = @$filter["v_Id"];
		$this->Id->AdvancedSearch->SearchValue2 = @$filter["y_Id"];
		$this->Id->AdvancedSearch->SearchOperator2 = @$filter["w_Id"];
		$this->Id->AdvancedSearch->Save();

		// Field DateTime
		$this->DateTime->AdvancedSearch->SearchValue = @$filter["x_DateTime"];
		$this->DateTime->AdvancedSearch->SearchOperator = @$filter["z_DateTime"];
		$this->DateTime->AdvancedSearch->SearchCondition = @$filter["v_DateTime"];
		$this->DateTime->AdvancedSearch->SearchValue2 = @$filter["y_DateTime"];
		$this->DateTime->AdvancedSearch->SearchOperator2 = @$filter["w_DateTime"];
		$this->DateTime->AdvancedSearch->Save();

		// Field Script
		$this->Script->AdvancedSearch->SearchValue = @$filter["x_Script"];
		$this->Script->AdvancedSearch->SearchOperator = @$filter["z_Script"];
		$this->Script->AdvancedSearch->SearchCondition = @$filter["v_Script"];
		$this->Script->AdvancedSearch->SearchValue2 = @$filter["y_Script"];
		$this->Script->AdvancedSearch->SearchOperator2 = @$filter["w_Script"];
		$this->Script->AdvancedSearch->Save();

		// Field User
		$this->User->AdvancedSearch->SearchValue = @$filter["x_User"];
		$this->User->AdvancedSearch->SearchOperator = @$filter["z_User"];
		$this->User->AdvancedSearch->SearchCondition = @$filter["v_User"];
		$this->User->AdvancedSearch->SearchValue2 = @$filter["y_User"];
		$this->User->AdvancedSearch->SearchOperator2 = @$filter["w_User"];
		$this->User->AdvancedSearch->Save();

		// Field Action
		$this->Action->AdvancedSearch->SearchValue = @$filter["x_Action"];
		$this->Action->AdvancedSearch->SearchOperator = @$filter["z_Action"];
		$this->Action->AdvancedSearch->SearchCondition = @$filter["v_Action"];
		$this->Action->AdvancedSearch->SearchValue2 = @$filter["y_Action"];
		$this->Action->AdvancedSearch->SearchOperator2 = @$filter["w_Action"];
		$this->Action->AdvancedSearch->Save();

		// Field Table
		$this->_Table->AdvancedSearch->SearchValue = @$filter["x__Table"];
		$this->_Table->AdvancedSearch->SearchOperator = @$filter["z__Table"];
		$this->_Table->AdvancedSearch->SearchCondition = @$filter["v__Table"];
		$this->_Table->AdvancedSearch->SearchValue2 = @$filter["y__Table"];
		$this->_Table->AdvancedSearch->SearchOperator2 = @$filter["w__Table"];
		$this->_Table->AdvancedSearch->Save();

		// Field Field
		$this->_Field->AdvancedSearch->SearchValue = @$filter["x__Field"];
		$this->_Field->AdvancedSearch->SearchOperator = @$filter["z__Field"];
		$this->_Field->AdvancedSearch->SearchCondition = @$filter["v__Field"];
		$this->_Field->AdvancedSearch->SearchValue2 = @$filter["y__Field"];
		$this->_Field->AdvancedSearch->SearchOperator2 = @$filter["w__Field"];
		$this->_Field->AdvancedSearch->Save();

		// Field KeyValue
		$this->KeyValue->AdvancedSearch->SearchValue = @$filter["x_KeyValue"];
		$this->KeyValue->AdvancedSearch->SearchOperator = @$filter["z_KeyValue"];
		$this->KeyValue->AdvancedSearch->SearchCondition = @$filter["v_KeyValue"];
		$this->KeyValue->AdvancedSearch->SearchValue2 = @$filter["y_KeyValue"];
		$this->KeyValue->AdvancedSearch->SearchOperator2 = @$filter["w_KeyValue"];
		$this->KeyValue->AdvancedSearch->Save();

		// Field OldValue
		$this->OldValue->AdvancedSearch->SearchValue = @$filter["x_OldValue"];
		$this->OldValue->AdvancedSearch->SearchOperator = @$filter["z_OldValue"];
		$this->OldValue->AdvancedSearch->SearchCondition = @$filter["v_OldValue"];
		$this->OldValue->AdvancedSearch->SearchValue2 = @$filter["y_OldValue"];
		$this->OldValue->AdvancedSearch->SearchOperator2 = @$filter["w_OldValue"];
		$this->OldValue->AdvancedSearch->Save();

		// Field NewValue
		$this->NewValue->AdvancedSearch->SearchValue = @$filter["x_NewValue"];
		$this->NewValue->AdvancedSearch->SearchOperator = @$filter["z_NewValue"];
		$this->NewValue->AdvancedSearch->SearchCondition = @$filter["v_NewValue"];
		$this->NewValue->AdvancedSearch->SearchValue2 = @$filter["y_NewValue"];
		$this->NewValue->AdvancedSearch->SearchOperator2 = @$filter["w_NewValue"];
		$this->NewValue->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Script, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->User, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Action, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_Table, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_Field, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->KeyValue, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->OldValue, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NewValue, $arKeywords, $type);
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
			$this->UpdateSort($this->Id); // Id
			$this->UpdateSort($this->DateTime); // DateTime
			$this->UpdateSort($this->Script); // Script
			$this->UpdateSort($this->User); // User
			$this->UpdateSort($this->Action); // Action
			$this->UpdateSort($this->_Table); // Table
			$this->UpdateSort($this->_Field); // Field
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
				$this->Id->setSort("");
				$this->DateTime->setSort("");
				$this->Script->setSort("");
				$this->User->setSort("");
				$this->Action->setSort("");
				$this->_Table->setSort("");
				$this->_Field->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fAuditTraillistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fAuditTraillistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fAuditTraillist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fAuditTraillistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->DateTime->setDbValue($rs->fields('DateTime'));
		$this->Script->setDbValue($rs->fields('Script'));
		$this->User->setDbValue($rs->fields('User'));
		$this->Action->setDbValue($rs->fields('Action'));
		$this->_Table->setDbValue($rs->fields('Table'));
		$this->_Field->setDbValue($rs->fields('Field'));
		$this->KeyValue->setDbValue($rs->fields('KeyValue'));
		$this->OldValue->setDbValue($rs->fields('OldValue'));
		$this->NewValue->setDbValue($rs->fields('NewValue'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->DateTime->DbValue = $row['DateTime'];
		$this->Script->DbValue = $row['Script'];
		$this->User->DbValue = $row['User'];
		$this->Action->DbValue = $row['Action'];
		$this->_Table->DbValue = $row['Table'];
		$this->_Field->DbValue = $row['Field'];
		$this->KeyValue->DbValue = $row['KeyValue'];
		$this->OldValue->DbValue = $row['OldValue'];
		$this->NewValue->DbValue = $row['NewValue'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
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
		// Id
		// DateTime
		// Script
		// User
		// Action
		// Table
		// Field
		// KeyValue
		// OldValue
		// NewValue

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// DateTime
		$this->DateTime->ViewValue = $this->DateTime->CurrentValue;
		$this->DateTime->ViewValue = ew_FormatDateTime($this->DateTime->ViewValue, 7);
		$this->DateTime->ViewCustomAttributes = "";

		// Script
		$this->Script->ViewValue = $this->Script->CurrentValue;
		$this->Script->ViewCustomAttributes = "";

		// User
		$this->User->ViewValue = $this->User->CurrentValue;
		$this->User->ViewCustomAttributes = "";

		// Action
		$this->Action->ViewValue = $this->Action->CurrentValue;
		$this->Action->ViewCustomAttributes = "";

		// Table
		$this->_Table->ViewValue = $this->_Table->CurrentValue;
		$this->_Table->ViewCustomAttributes = "";

		// Field
		$this->_Field->ViewValue = $this->_Field->CurrentValue;
		$this->_Field->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// DateTime
			$this->DateTime->LinkCustomAttributes = "";
			$this->DateTime->HrefValue = "";
			$this->DateTime->TooltipValue = "";

			// Script
			$this->Script->LinkCustomAttributes = "";
			$this->Script->HrefValue = "";
			$this->Script->TooltipValue = "";

			// User
			$this->User->LinkCustomAttributes = "";
			$this->User->HrefValue = "";
			$this->User->TooltipValue = "";

			// Action
			$this->Action->LinkCustomAttributes = "";
			$this->Action->HrefValue = "";
			$this->Action->TooltipValue = "";

			// Table
			$this->_Table->LinkCustomAttributes = "";
			$this->_Table->HrefValue = "";
			$this->_Table->TooltipValue = "";

			// Field
			$this->_Field->LinkCustomAttributes = "";
			$this->_Field->HrefValue = "";
			$this->_Field->TooltipValue = "";
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
if (!isset($AuditTrail_list)) $AuditTrail_list = new cAuditTrail_list();

// Page init
$AuditTrail_list->Page_Init();

// Page main
$AuditTrail_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$AuditTrail_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fAuditTraillist = new ew_Form("fAuditTraillist", "list");
fAuditTraillist.FormKeyCountName = '<?php echo $AuditTrail_list->FormKeyCountName ?>';

// Form_CustomValidate event
fAuditTraillist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fAuditTraillist.ValidateRequired = true;
<?php } else { ?>
fAuditTraillist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fAuditTraillistsrch = new ew_Form("fAuditTraillistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($AuditTrail_list->TotalRecs > 0 && $AuditTrail_list->ExportOptions->Visible()) { ?>
<?php $AuditTrail_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($AuditTrail_list->SearchOptions->Visible()) { ?>
<?php $AuditTrail_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($AuditTrail_list->FilterOptions->Visible()) { ?>
<?php $AuditTrail_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $AuditTrail_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($AuditTrail_list->TotalRecs <= 0)
			$AuditTrail_list->TotalRecs = $AuditTrail->SelectRecordCount();
	} else {
		if (!$AuditTrail_list->Recordset && ($AuditTrail_list->Recordset = $AuditTrail_list->LoadRecordset()))
			$AuditTrail_list->TotalRecs = $AuditTrail_list->Recordset->RecordCount();
	}
	$AuditTrail_list->StartRec = 1;
	if ($AuditTrail_list->DisplayRecs <= 0 || ($AuditTrail->Export <> "" && $AuditTrail->ExportAll)) // Display all records
		$AuditTrail_list->DisplayRecs = $AuditTrail_list->TotalRecs;
	if (!($AuditTrail->Export <> "" && $AuditTrail->ExportAll))
		$AuditTrail_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$AuditTrail_list->Recordset = $AuditTrail_list->LoadRecordset($AuditTrail_list->StartRec-1, $AuditTrail_list->DisplayRecs);

	// Set no record found message
	if ($AuditTrail->CurrentAction == "" && $AuditTrail_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$AuditTrail_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($AuditTrail_list->SearchWhere == "0=101")
			$AuditTrail_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$AuditTrail_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$AuditTrail_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($AuditTrail->Export == "" && $AuditTrail->CurrentAction == "") { ?>
<form name="fAuditTraillistsrch" id="fAuditTraillistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($AuditTrail_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fAuditTraillistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="AuditTrail">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($AuditTrail_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($AuditTrail_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $AuditTrail_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($AuditTrail_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($AuditTrail_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($AuditTrail_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($AuditTrail_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $AuditTrail_list->ShowPageHeader(); ?>
<?php
$AuditTrail_list->ShowMessage();
?>
<?php if ($AuditTrail_list->TotalRecs > 0 || $AuditTrail->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fAuditTraillist" id="fAuditTraillist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($AuditTrail_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $AuditTrail_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="AuditTrail">
<div id="gmp_AuditTrail" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($AuditTrail_list->TotalRecs > 0) { ?>
<table id="tbl_AuditTraillist" class="table ewTable">
<?php echo $AuditTrail->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$AuditTrail_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$AuditTrail_list->RenderListOptions();

// Render list options (header, left)
$AuditTrail_list->ListOptions->Render("header", "left");
?>
<?php if ($AuditTrail->Id->Visible) { // Id ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->Id) == "") { ?>
		<th data-name="Id"><div id="elh_AuditTrail_Id" class="AuditTrail_Id"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->Id) ?>',1);"><div id="elh_AuditTrail_Id" class="AuditTrail_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($AuditTrail->DateTime->Visible) { // DateTime ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->DateTime) == "") { ?>
		<th data-name="DateTime"><div id="elh_AuditTrail_DateTime" class="AuditTrail_DateTime"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->DateTime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DateTime"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->DateTime) ?>',1);"><div id="elh_AuditTrail_DateTime" class="AuditTrail_DateTime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->DateTime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->DateTime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->DateTime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($AuditTrail->Script->Visible) { // Script ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->Script) == "") { ?>
		<th data-name="Script"><div id="elh_AuditTrail_Script" class="AuditTrail_Script"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->Script->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Script"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->Script) ?>',1);"><div id="elh_AuditTrail_Script" class="AuditTrail_Script">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->Script->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->Script->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->Script->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($AuditTrail->User->Visible) { // User ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->User) == "") { ?>
		<th data-name="User"><div id="elh_AuditTrail_User" class="AuditTrail_User"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->User->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="User"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->User) ?>',1);"><div id="elh_AuditTrail_User" class="AuditTrail_User">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->User->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->User->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->User->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($AuditTrail->Action->Visible) { // Action ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->Action) == "") { ?>
		<th data-name="Action"><div id="elh_AuditTrail_Action" class="AuditTrail_Action"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->Action->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Action"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->Action) ?>',1);"><div id="elh_AuditTrail_Action" class="AuditTrail_Action">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->Action->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->Action->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->Action->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($AuditTrail->_Table->Visible) { // Table ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->_Table) == "") { ?>
		<th data-name="_Table"><div id="elh_AuditTrail__Table" class="AuditTrail__Table"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->_Table->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_Table"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->_Table) ?>',1);"><div id="elh_AuditTrail__Table" class="AuditTrail__Table">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->_Table->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->_Table->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->_Table->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($AuditTrail->_Field->Visible) { // Field ?>
	<?php if ($AuditTrail->SortUrl($AuditTrail->_Field) == "") { ?>
		<th data-name="_Field"><div id="elh_AuditTrail__Field" class="AuditTrail__Field"><div class="ewTableHeaderCaption"><?php echo $AuditTrail->_Field->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_Field"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AuditTrail->SortUrl($AuditTrail->_Field) ?>',1);"><div id="elh_AuditTrail__Field" class="AuditTrail__Field">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AuditTrail->_Field->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AuditTrail->_Field->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AuditTrail->_Field->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$AuditTrail_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($AuditTrail->ExportAll && $AuditTrail->Export <> "") {
	$AuditTrail_list->StopRec = $AuditTrail_list->TotalRecs;
} else {

	// Set the last record to display
	if ($AuditTrail_list->TotalRecs > $AuditTrail_list->StartRec + $AuditTrail_list->DisplayRecs - 1)
		$AuditTrail_list->StopRec = $AuditTrail_list->StartRec + $AuditTrail_list->DisplayRecs - 1;
	else
		$AuditTrail_list->StopRec = $AuditTrail_list->TotalRecs;
}
$AuditTrail_list->RecCnt = $AuditTrail_list->StartRec - 1;
if ($AuditTrail_list->Recordset && !$AuditTrail_list->Recordset->EOF) {
	$AuditTrail_list->Recordset->MoveFirst();
	$bSelectLimit = $AuditTrail_list->UseSelectLimit;
	if (!$bSelectLimit && $AuditTrail_list->StartRec > 1)
		$AuditTrail_list->Recordset->Move($AuditTrail_list->StartRec - 1);
} elseif (!$AuditTrail->AllowAddDeleteRow && $AuditTrail_list->StopRec == 0) {
	$AuditTrail_list->StopRec = $AuditTrail->GridAddRowCount;
}

// Initialize aggregate
$AuditTrail->RowType = EW_ROWTYPE_AGGREGATEINIT;
$AuditTrail->ResetAttrs();
$AuditTrail_list->RenderRow();
while ($AuditTrail_list->RecCnt < $AuditTrail_list->StopRec) {
	$AuditTrail_list->RecCnt++;
	if (intval($AuditTrail_list->RecCnt) >= intval($AuditTrail_list->StartRec)) {
		$AuditTrail_list->RowCnt++;

		// Set up key count
		$AuditTrail_list->KeyCount = $AuditTrail_list->RowIndex;

		// Init row class and style
		$AuditTrail->ResetAttrs();
		$AuditTrail->CssClass = "";
		if ($AuditTrail->CurrentAction == "gridadd") {
		} else {
			$AuditTrail_list->LoadRowValues($AuditTrail_list->Recordset); // Load row values
		}
		$AuditTrail->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$AuditTrail->RowAttrs = array_merge($AuditTrail->RowAttrs, array('data-rowindex'=>$AuditTrail_list->RowCnt, 'id'=>'r' . $AuditTrail_list->RowCnt . '_AuditTrail', 'data-rowtype'=>$AuditTrail->RowType));

		// Render row
		$AuditTrail_list->RenderRow();

		// Render list options
		$AuditTrail_list->RenderListOptions();
?>
	<tr<?php echo $AuditTrail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$AuditTrail_list->ListOptions->Render("body", "left", $AuditTrail_list->RowCnt);
?>
	<?php if ($AuditTrail->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $AuditTrail->Id->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail_Id" class="AuditTrail_Id">
<span<?php echo $AuditTrail->Id->ViewAttributes() ?>>
<?php echo $AuditTrail->Id->ListViewValue() ?></span>
</span>
<a id="<?php echo $AuditTrail_list->PageObjName . "_row_" . $AuditTrail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($AuditTrail->DateTime->Visible) { // DateTime ?>
		<td data-name="DateTime"<?php echo $AuditTrail->DateTime->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail_DateTime" class="AuditTrail_DateTime">
<span<?php echo $AuditTrail->DateTime->ViewAttributes() ?>>
<?php echo $AuditTrail->DateTime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AuditTrail->Script->Visible) { // Script ?>
		<td data-name="Script"<?php echo $AuditTrail->Script->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail_Script" class="AuditTrail_Script">
<span<?php echo $AuditTrail->Script->ViewAttributes() ?>>
<?php echo $AuditTrail->Script->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AuditTrail->User->Visible) { // User ?>
		<td data-name="User"<?php echo $AuditTrail->User->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail_User" class="AuditTrail_User">
<span<?php echo $AuditTrail->User->ViewAttributes() ?>>
<?php echo $AuditTrail->User->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AuditTrail->Action->Visible) { // Action ?>
		<td data-name="Action"<?php echo $AuditTrail->Action->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail_Action" class="AuditTrail_Action">
<span<?php echo $AuditTrail->Action->ViewAttributes() ?>>
<?php echo $AuditTrail->Action->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AuditTrail->_Table->Visible) { // Table ?>
		<td data-name="_Table"<?php echo $AuditTrail->_Table->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail__Table" class="AuditTrail__Table">
<span<?php echo $AuditTrail->_Table->ViewAttributes() ?>>
<?php echo $AuditTrail->_Table->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AuditTrail->_Field->Visible) { // Field ?>
		<td data-name="_Field"<?php echo $AuditTrail->_Field->CellAttributes() ?>>
<span id="el<?php echo $AuditTrail_list->RowCnt ?>_AuditTrail__Field" class="AuditTrail__Field">
<span<?php echo $AuditTrail->_Field->ViewAttributes() ?>>
<?php echo $AuditTrail->_Field->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$AuditTrail_list->ListOptions->Render("body", "right", $AuditTrail_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($AuditTrail->CurrentAction <> "gridadd")
		$AuditTrail_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($AuditTrail->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($AuditTrail_list->Recordset)
	$AuditTrail_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($AuditTrail->CurrentAction <> "gridadd" && $AuditTrail->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($AuditTrail_list->Pager)) $AuditTrail_list->Pager = new cPrevNextPager($AuditTrail_list->StartRec, $AuditTrail_list->DisplayRecs, $AuditTrail_list->TotalRecs) ?>
<?php if ($AuditTrail_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($AuditTrail_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $AuditTrail_list->PageUrl() ?>start=<?php echo $AuditTrail_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($AuditTrail_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $AuditTrail_list->PageUrl() ?>start=<?php echo $AuditTrail_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $AuditTrail_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($AuditTrail_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $AuditTrail_list->PageUrl() ?>start=<?php echo $AuditTrail_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($AuditTrail_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $AuditTrail_list->PageUrl() ?>start=<?php echo $AuditTrail_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $AuditTrail_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $AuditTrail_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $AuditTrail_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $AuditTrail_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($AuditTrail_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($AuditTrail_list->TotalRecs == 0 && $AuditTrail->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($AuditTrail_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fAuditTraillistsrch.Init();
fAuditTraillistsrch.FilterList = <?php echo $AuditTrail_list->GetFilterList() ?>;
fAuditTraillist.Init();
</script>
<?php
$AuditTrail_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$AuditTrail_list->Page_Terminate();
?>
