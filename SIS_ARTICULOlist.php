<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_ARTICULOinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_ARTICULO_list = NULL; // Initialize page object first

class cSIS_ARTICULO_list extends cSIS_ARTICULO {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_ARTICULO';

	// Page object name
	var $PageObjName = 'SIS_ARTICULO_list';

	// Grid form hidden field names
	var $FormName = 'fSIS_ARTICULOlist';
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

		// Table object (SIS_ARTICULO)
		if (!isset($GLOBALS["SIS_ARTICULO"]) || get_class($GLOBALS["SIS_ARTICULO"]) == "cSIS_ARTICULO") {
			$GLOBALS["SIS_ARTICULO"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_ARTICULO"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "SIS_ARTICULOadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "SIS_ARTICULOdelete.php";
		$this->MultiUpdateUrl = "SIS_ARTICULOupdate.php";

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_ARTICULO', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fSIS_ARTICULOlistsrch";

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
		$this->fecultimamod->Visible = !$this->IsAddOrEdit();
		$this->usuarioultimamod->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $SIS_ARTICULO;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_ARTICULO);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

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

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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
			$this->codarticulo->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->codarticulo->AdvancedSearch->ToJSON(), ","); // Field codarticulo
		$sFilterList = ew_Concat($sFilterList, $this->descripcion->AdvancedSearch->ToJSON(), ","); // Field descripcion
		$sFilterList = ew_Concat($sFilterList, $this->precio->AdvancedSearch->ToJSON(), ","); // Field precio
		$sFilterList = ew_Concat($sFilterList, $this->foto->AdvancedSearch->ToJSON(), ","); // Field foto
		$sFilterList = ew_Concat($sFilterList, $this->foto2->AdvancedSearch->ToJSON(), ","); // Field foto2
		$sFilterList = ew_Concat($sFilterList, $this->foto3->AdvancedSearch->ToJSON(), ","); // Field foto3
		$sFilterList = ew_Concat($sFilterList, $this->caracteristicas->AdvancedSearch->ToJSON(), ","); // Field caracteristicas
		$sFilterList = ew_Concat($sFilterList, $this->garantia->AdvancedSearch->ToJSON(), ","); // Field garantia
		$sFilterList = ew_Concat($sFilterList, $this->referencia->AdvancedSearch->ToJSON(), ","); // Field referencia
		$sFilterList = ew_Concat($sFilterList, $this->codlinea->AdvancedSearch->ToJSON(), ","); // Field codlinea
		$sFilterList = ew_Concat($sFilterList, $this->calificacion->AdvancedSearch->ToJSON(), ","); // Field calificacion
		$sFilterList = ew_Concat($sFilterList, $this->fecultimamod->AdvancedSearch->ToJSON(), ","); // Field fecultimamod
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

		// Field codarticulo
		$this->codarticulo->AdvancedSearch->SearchValue = @$filter["x_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchOperator = @$filter["z_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchCondition = @$filter["v_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchValue2 = @$filter["y_codarticulo"];
		$this->codarticulo->AdvancedSearch->SearchOperator2 = @$filter["w_codarticulo"];
		$this->codarticulo->AdvancedSearch->Save();

		// Field descripcion
		$this->descripcion->AdvancedSearch->SearchValue = @$filter["x_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator = @$filter["z_descripcion"];
		$this->descripcion->AdvancedSearch->SearchCondition = @$filter["v_descripcion"];
		$this->descripcion->AdvancedSearch->SearchValue2 = @$filter["y_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator2 = @$filter["w_descripcion"];
		$this->descripcion->AdvancedSearch->Save();

		// Field precio
		$this->precio->AdvancedSearch->SearchValue = @$filter["x_precio"];
		$this->precio->AdvancedSearch->SearchOperator = @$filter["z_precio"];
		$this->precio->AdvancedSearch->SearchCondition = @$filter["v_precio"];
		$this->precio->AdvancedSearch->SearchValue2 = @$filter["y_precio"];
		$this->precio->AdvancedSearch->SearchOperator2 = @$filter["w_precio"];
		$this->precio->AdvancedSearch->Save();

		// Field foto
		$this->foto->AdvancedSearch->SearchValue = @$filter["x_foto"];
		$this->foto->AdvancedSearch->SearchOperator = @$filter["z_foto"];
		$this->foto->AdvancedSearch->SearchCondition = @$filter["v_foto"];
		$this->foto->AdvancedSearch->SearchValue2 = @$filter["y_foto"];
		$this->foto->AdvancedSearch->SearchOperator2 = @$filter["w_foto"];
		$this->foto->AdvancedSearch->Save();

		// Field foto2
		$this->foto2->AdvancedSearch->SearchValue = @$filter["x_foto2"];
		$this->foto2->AdvancedSearch->SearchOperator = @$filter["z_foto2"];
		$this->foto2->AdvancedSearch->SearchCondition = @$filter["v_foto2"];
		$this->foto2->AdvancedSearch->SearchValue2 = @$filter["y_foto2"];
		$this->foto2->AdvancedSearch->SearchOperator2 = @$filter["w_foto2"];
		$this->foto2->AdvancedSearch->Save();

		// Field foto3
		$this->foto3->AdvancedSearch->SearchValue = @$filter["x_foto3"];
		$this->foto3->AdvancedSearch->SearchOperator = @$filter["z_foto3"];
		$this->foto3->AdvancedSearch->SearchCondition = @$filter["v_foto3"];
		$this->foto3->AdvancedSearch->SearchValue2 = @$filter["y_foto3"];
		$this->foto3->AdvancedSearch->SearchOperator2 = @$filter["w_foto3"];
		$this->foto3->AdvancedSearch->Save();

		// Field caracteristicas
		$this->caracteristicas->AdvancedSearch->SearchValue = @$filter["x_caracteristicas"];
		$this->caracteristicas->AdvancedSearch->SearchOperator = @$filter["z_caracteristicas"];
		$this->caracteristicas->AdvancedSearch->SearchCondition = @$filter["v_caracteristicas"];
		$this->caracteristicas->AdvancedSearch->SearchValue2 = @$filter["y_caracteristicas"];
		$this->caracteristicas->AdvancedSearch->SearchOperator2 = @$filter["w_caracteristicas"];
		$this->caracteristicas->AdvancedSearch->Save();

		// Field garantia
		$this->garantia->AdvancedSearch->SearchValue = @$filter["x_garantia"];
		$this->garantia->AdvancedSearch->SearchOperator = @$filter["z_garantia"];
		$this->garantia->AdvancedSearch->SearchCondition = @$filter["v_garantia"];
		$this->garantia->AdvancedSearch->SearchValue2 = @$filter["y_garantia"];
		$this->garantia->AdvancedSearch->SearchOperator2 = @$filter["w_garantia"];
		$this->garantia->AdvancedSearch->Save();

		// Field referencia
		$this->referencia->AdvancedSearch->SearchValue = @$filter["x_referencia"];
		$this->referencia->AdvancedSearch->SearchOperator = @$filter["z_referencia"];
		$this->referencia->AdvancedSearch->SearchCondition = @$filter["v_referencia"];
		$this->referencia->AdvancedSearch->SearchValue2 = @$filter["y_referencia"];
		$this->referencia->AdvancedSearch->SearchOperator2 = @$filter["w_referencia"];
		$this->referencia->AdvancedSearch->Save();

		// Field codlinea
		$this->codlinea->AdvancedSearch->SearchValue = @$filter["x_codlinea"];
		$this->codlinea->AdvancedSearch->SearchOperator = @$filter["z_codlinea"];
		$this->codlinea->AdvancedSearch->SearchCondition = @$filter["v_codlinea"];
		$this->codlinea->AdvancedSearch->SearchValue2 = @$filter["y_codlinea"];
		$this->codlinea->AdvancedSearch->SearchOperator2 = @$filter["w_codlinea"];
		$this->codlinea->AdvancedSearch->Save();

		// Field calificacion
		$this->calificacion->AdvancedSearch->SearchValue = @$filter["x_calificacion"];
		$this->calificacion->AdvancedSearch->SearchOperator = @$filter["z_calificacion"];
		$this->calificacion->AdvancedSearch->SearchCondition = @$filter["v_calificacion"];
		$this->calificacion->AdvancedSearch->SearchValue2 = @$filter["y_calificacion"];
		$this->calificacion->AdvancedSearch->SearchOperator2 = @$filter["w_calificacion"];
		$this->calificacion->AdvancedSearch->Save();

		// Field fecultimamod
		$this->fecultimamod->AdvancedSearch->SearchValue = @$filter["x_fecultimamod"];
		$this->fecultimamod->AdvancedSearch->SearchOperator = @$filter["z_fecultimamod"];
		$this->fecultimamod->AdvancedSearch->SearchCondition = @$filter["v_fecultimamod"];
		$this->fecultimamod->AdvancedSearch->SearchValue2 = @$filter["y_fecultimamod"];
		$this->fecultimamod->AdvancedSearch->SearchOperator2 = @$filter["w_fecultimamod"];
		$this->fecultimamod->AdvancedSearch->Save();

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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->codarticulo, $Default, FALSE); // codarticulo
		$this->BuildSearchSql($sWhere, $this->descripcion, $Default, FALSE); // descripcion
		$this->BuildSearchSql($sWhere, $this->precio, $Default, FALSE); // precio
		$this->BuildSearchSql($sWhere, $this->foto, $Default, FALSE); // foto
		$this->BuildSearchSql($sWhere, $this->foto2, $Default, FALSE); // foto2
		$this->BuildSearchSql($sWhere, $this->foto3, $Default, FALSE); // foto3
		$this->BuildSearchSql($sWhere, $this->caracteristicas, $Default, FALSE); // caracteristicas
		$this->BuildSearchSql($sWhere, $this->garantia, $Default, FALSE); // garantia
		$this->BuildSearchSql($sWhere, $this->referencia, $Default, FALSE); // referencia
		$this->BuildSearchSql($sWhere, $this->codlinea, $Default, FALSE); // codlinea
		$this->BuildSearchSql($sWhere, $this->calificacion, $Default, FALSE); // calificacion
		$this->BuildSearchSql($sWhere, $this->fecultimamod, $Default, FALSE); // fecultimamod
		$this->BuildSearchSql($sWhere, $this->usuarioultimamod, $Default, FALSE); // usuarioultimamod

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->codarticulo->AdvancedSearch->Save(); // codarticulo
			$this->descripcion->AdvancedSearch->Save(); // descripcion
			$this->precio->AdvancedSearch->Save(); // precio
			$this->foto->AdvancedSearch->Save(); // foto
			$this->foto2->AdvancedSearch->Save(); // foto2
			$this->foto3->AdvancedSearch->Save(); // foto3
			$this->caracteristicas->AdvancedSearch->Save(); // caracteristicas
			$this->garantia->AdvancedSearch->Save(); // garantia
			$this->referencia->AdvancedSearch->Save(); // referencia
			$this->codlinea->AdvancedSearch->Save(); // codlinea
			$this->calificacion->AdvancedSearch->Save(); // calificacion
			$this->fecultimamod->AdvancedSearch->Save(); // fecultimamod
			$this->usuarioultimamod->AdvancedSearch->Save(); // usuarioultimamod
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->foto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->foto2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->foto3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->caracteristicas, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->garantia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->referencia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->calificacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fecultimamod, $arKeywords, $type);
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
		if ($this->codarticulo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->descripcion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->precio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->foto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->foto2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->foto3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->caracteristicas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->garantia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->referencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codlinea->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->calificacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecultimamod->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->usuarioultimamod->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->codarticulo->AdvancedSearch->UnsetSession();
		$this->descripcion->AdvancedSearch->UnsetSession();
		$this->precio->AdvancedSearch->UnsetSession();
		$this->foto->AdvancedSearch->UnsetSession();
		$this->foto2->AdvancedSearch->UnsetSession();
		$this->foto3->AdvancedSearch->UnsetSession();
		$this->caracteristicas->AdvancedSearch->UnsetSession();
		$this->garantia->AdvancedSearch->UnsetSession();
		$this->referencia->AdvancedSearch->UnsetSession();
		$this->codlinea->AdvancedSearch->UnsetSession();
		$this->calificacion->AdvancedSearch->UnsetSession();
		$this->fecultimamod->AdvancedSearch->UnsetSession();
		$this->usuarioultimamod->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->codarticulo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->precio->AdvancedSearch->Load();
		$this->foto->AdvancedSearch->Load();
		$this->foto2->AdvancedSearch->Load();
		$this->foto3->AdvancedSearch->Load();
		$this->caracteristicas->AdvancedSearch->Load();
		$this->garantia->AdvancedSearch->Load();
		$this->referencia->AdvancedSearch->Load();
		$this->codlinea->AdvancedSearch->Load();
		$this->calificacion->AdvancedSearch->Load();
		$this->fecultimamod->AdvancedSearch->Load();
		$this->usuarioultimamod->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->codarticulo); // codarticulo
			$this->UpdateSort($this->descripcion); // descripcion
			$this->UpdateSort($this->precio); // precio
			$this->UpdateSort($this->caracteristicas); // caracteristicas
			$this->UpdateSort($this->garantia); // garantia
			$this->UpdateSort($this->codlinea); // codlinea
			$this->UpdateSort($this->calificacion); // calificacion
			$this->UpdateSort($this->fecultimamod); // fecultimamod
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
				$this->codarticulo->setSort("");
				$this->descripcion->setSort("");
				$this->precio->setSort("");
				$this->caracteristicas->setSort("");
				$this->garantia->setSort("");
				$this->codlinea->setSort("");
				$this->calificacion->setSort("");
				$this->fecultimamod->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->codarticulo->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fSIS_ARTICULOlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fSIS_ARTICULOlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fSIS_ARTICULOlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fSIS_ARTICULOlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// codarticulo

		$this->codarticulo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codarticulo"]);
		if ($this->codarticulo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codarticulo->AdvancedSearch->SearchOperator = @$_GET["z_codarticulo"];

		// descripcion
		$this->descripcion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_descripcion"]);
		if ($this->descripcion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->descripcion->AdvancedSearch->SearchOperator = @$_GET["z_descripcion"];

		// precio
		$this->precio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_precio"]);
		if ($this->precio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->precio->AdvancedSearch->SearchOperator = @$_GET["z_precio"];

		// foto
		$this->foto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_foto"]);
		if ($this->foto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->foto->AdvancedSearch->SearchOperator = @$_GET["z_foto"];

		// foto2
		$this->foto2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_foto2"]);
		if ($this->foto2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->foto2->AdvancedSearch->SearchOperator = @$_GET["z_foto2"];

		// foto3
		$this->foto3->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_foto3"]);
		if ($this->foto3->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->foto3->AdvancedSearch->SearchOperator = @$_GET["z_foto3"];

		// caracteristicas
		$this->caracteristicas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_caracteristicas"]);
		if ($this->caracteristicas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->caracteristicas->AdvancedSearch->SearchOperator = @$_GET["z_caracteristicas"];

		// garantia
		$this->garantia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_garantia"]);
		if ($this->garantia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->garantia->AdvancedSearch->SearchOperator = @$_GET["z_garantia"];

		// referencia
		$this->referencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_referencia"]);
		if ($this->referencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->referencia->AdvancedSearch->SearchOperator = @$_GET["z_referencia"];

		// codlinea
		$this->codlinea->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codlinea"]);
		if ($this->codlinea->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codlinea->AdvancedSearch->SearchOperator = @$_GET["z_codlinea"];

		// calificacion
		$this->calificacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_calificacion"]);
		if ($this->calificacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->calificacion->AdvancedSearch->SearchOperator = @$_GET["z_calificacion"];

		// fecultimamod
		$this->fecultimamod->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecultimamod"]);
		if ($this->fecultimamod->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecultimamod->AdvancedSearch->SearchOperator = @$_GET["z_fecultimamod"];

		// usuarioultimamod
		$this->usuarioultimamod->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_usuarioultimamod"]);
		if ($this->usuarioultimamod->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->usuarioultimamod->AdvancedSearch->SearchOperator = @$_GET["z_usuarioultimamod"];
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
		$this->codarticulo->setDbValue($rs->fields('codarticulo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->foto->Upload->DbValue = $rs->fields('foto');
		$this->foto->CurrentValue = $this->foto->Upload->DbValue;
		$this->foto2->Upload->DbValue = $rs->fields('foto2');
		$this->foto2->CurrentValue = $this->foto2->Upload->DbValue;
		$this->foto3->Upload->DbValue = $rs->fields('foto3');
		$this->foto3->CurrentValue = $this->foto3->Upload->DbValue;
		$this->caracteristicas->setDbValue($rs->fields('caracteristicas'));
		$this->garantia->setDbValue($rs->fields('garantia'));
		$this->referencia->setDbValue($rs->fields('referencia'));
		$this->codlinea->setDbValue($rs->fields('codlinea'));
		$this->calificacion->setDbValue($rs->fields('calificacion'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codarticulo->DbValue = $row['codarticulo'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->precio->DbValue = $row['precio'];
		$this->foto->Upload->DbValue = $row['foto'];
		$this->foto2->Upload->DbValue = $row['foto2'];
		$this->foto3->Upload->DbValue = $row['foto3'];
		$this->caracteristicas->DbValue = $row['caracteristicas'];
		$this->garantia->DbValue = $row['garantia'];
		$this->referencia->DbValue = $row['referencia'];
		$this->codlinea->DbValue = $row['codlinea'];
		$this->calificacion->DbValue = $row['calificacion'];
		$this->fecultimamod->DbValue = $row['fecultimamod'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ew_StrToFloat($this->precio->CurrentValue)))
			$this->precio->CurrentValue = ew_StrToFloat($this->precio->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codarticulo
		// descripcion
		// precio
		// foto
		// foto2
		// foto3
		// caracteristicas
		// garantia
		// referencia
		// codlinea
		// calificacion
		// fecultimamod
		// usuarioultimamod

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codarticulo
		$this->codarticulo->ViewValue = $this->codarticulo->CurrentValue;
		$this->codarticulo->CssStyle = "font-weight: bold;";
		$this->codarticulo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// precio
		$this->precio->ViewValue = $this->precio->CurrentValue;
		$this->precio->ViewValue = ew_FormatNumber($this->precio->ViewValue, 0, -2, -2, -2);
		$this->precio->ViewCustomAttributes = "";

		// foto
		$this->foto->UploadPath = "uploads";
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->ImageAlt = $this->foto->FldAlt();
			$this->foto->ViewValue = $this->foto->Upload->DbValue;
		} else {
			$this->foto->ViewValue = "";
		}
		$this->foto->ViewCustomAttributes = "";

		// foto2
		$this->foto2->UploadPath = "uploads";
		if (!ew_Empty($this->foto2->Upload->DbValue)) {
			$this->foto2->ImageAlt = $this->foto2->FldAlt();
			$this->foto2->ViewValue = $this->foto2->Upload->DbValue;
		} else {
			$this->foto2->ViewValue = "";
		}
		$this->foto2->ViewCustomAttributes = "";

		// foto3
		$this->foto3->UploadPath = "uploads";
		if (!ew_Empty($this->foto3->Upload->DbValue)) {
			$this->foto3->ImageAlt = $this->foto3->FldAlt();
			$this->foto3->ViewValue = $this->foto3->Upload->DbValue;
		} else {
			$this->foto3->ViewValue = "";
		}
		$this->foto3->ViewCustomAttributes = "";

		// caracteristicas
		$this->caracteristicas->ViewValue = $this->caracteristicas->CurrentValue;
		$this->caracteristicas->ViewCustomAttributes = "";

		// garantia
		$this->garantia->ViewValue = $this->garantia->CurrentValue;
		$this->garantia->ViewCustomAttributes = "";

		// referencia
		$this->referencia->ViewValue = $this->referencia->CurrentValue;
		$this->referencia->ViewCustomAttributes = "";

		// codlinea
		if (strval($this->codlinea->CurrentValue) <> "") {
			$sFilterWrk = "[cod_linea]" . ew_SearchString("=", $this->codlinea->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [cod_linea], [Descripcion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_LINEA]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codlinea, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codlinea->ViewValue = $this->codlinea->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codlinea->ViewValue = $this->codlinea->CurrentValue;
			}
		} else {
			$this->codlinea->ViewValue = NULL;
		}
		$this->codlinea->ViewCustomAttributes = "";

		// calificacion
		if (strval($this->calificacion->CurrentValue) <> "") {
			$this->calificacion->ViewValue = $this->calificacion->OptionCaption($this->calificacion->CurrentValue);
		} else {
			$this->calificacion->ViewValue = NULL;
		}
		$this->calificacion->ViewCustomAttributes = "";

		// fecultimamod
		$this->fecultimamod->ViewValue = $this->fecultimamod->CurrentValue;
		$this->fecultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

			// codarticulo
			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";
			$this->codarticulo->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";
			$this->precio->TooltipValue = "";

			// caracteristicas
			$this->caracteristicas->LinkCustomAttributes = "";
			$this->caracteristicas->HrefValue = "";
			$this->caracteristicas->TooltipValue = "";

			// garantia
			$this->garantia->LinkCustomAttributes = "";
			$this->garantia->HrefValue = "";
			$this->garantia->TooltipValue = "";

			// codlinea
			$this->codlinea->LinkCustomAttributes = "";
			$this->codlinea->HrefValue = "";
			$this->codlinea->TooltipValue = "";

			// calificacion
			$this->calificacion->LinkCustomAttributes = "";
			$this->calificacion->HrefValue = "";
			$this->calificacion->TooltipValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";
			$this->fecultimamod->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// codarticulo
			$this->codarticulo->EditAttrs["class"] = "form-control";
			$this->codarticulo->EditCustomAttributes = "";
			$this->codarticulo->EditValue = ew_HtmlEncode($this->codarticulo->AdvancedSearch->SearchValue);
			$this->codarticulo->PlaceHolder = ew_RemoveHtml($this->codarticulo->FldCaption());

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->AdvancedSearch->SearchValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// precio
			$this->precio->EditAttrs["class"] = "form-control";
			$this->precio->EditCustomAttributes = "";
			$this->precio->EditValue = ew_HtmlEncode($this->precio->AdvancedSearch->SearchValue);
			$this->precio->PlaceHolder = ew_RemoveHtml($this->precio->FldCaption());

			// caracteristicas
			$this->caracteristicas->EditAttrs["class"] = "form-control";
			$this->caracteristicas->EditCustomAttributes = "";
			$this->caracteristicas->EditValue = ew_HtmlEncode($this->caracteristicas->AdvancedSearch->SearchValue);
			$this->caracteristicas->PlaceHolder = ew_RemoveHtml($this->caracteristicas->FldCaption());

			// garantia
			$this->garantia->EditAttrs["class"] = "form-control";
			$this->garantia->EditCustomAttributes = "";
			$this->garantia->EditValue = ew_HtmlEncode($this->garantia->AdvancedSearch->SearchValue);
			$this->garantia->PlaceHolder = ew_RemoveHtml($this->garantia->FldCaption());

			// codlinea
			$this->codlinea->EditAttrs["class"] = "form-control";
			$this->codlinea->EditCustomAttributes = "";

			// calificacion
			$this->calificacion->EditAttrs["class"] = "form-control";
			$this->calificacion->EditCustomAttributes = "";
			$this->calificacion->EditValue = $this->calificacion->Options(TRUE);

			// fecultimamod
			$this->fecultimamod->EditAttrs["class"] = "form-control";
			$this->fecultimamod->EditCustomAttributes = "";
			$this->fecultimamod->EditValue = ew_HtmlEncode($this->fecultimamod->AdvancedSearch->SearchValue);
			$this->fecultimamod->PlaceHolder = ew_RemoveHtml($this->fecultimamod->FldCaption());

			// usuarioultimamod
			$this->usuarioultimamod->EditAttrs["class"] = "form-control";
			$this->usuarioultimamod->EditCustomAttributes = "";
			$this->usuarioultimamod->EditValue = ew_HtmlEncode($this->usuarioultimamod->AdvancedSearch->SearchValue);
			$this->usuarioultimamod->PlaceHolder = ew_RemoveHtml($this->usuarioultimamod->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->codarticulo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->precio->AdvancedSearch->Load();
		$this->foto->AdvancedSearch->Load();
		$this->foto2->AdvancedSearch->Load();
		$this->foto3->AdvancedSearch->Load();
		$this->caracteristicas->AdvancedSearch->Load();
		$this->garantia->AdvancedSearch->Load();
		$this->referencia->AdvancedSearch->Load();
		$this->codlinea->AdvancedSearch->Load();
		$this->calificacion->AdvancedSearch->Load();
		$this->fecultimamod->AdvancedSearch->Load();
		$this->usuarioultimamod->AdvancedSearch->Load();
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
if (!isset($SIS_ARTICULO_list)) $SIS_ARTICULO_list = new cSIS_ARTICULO_list();

// Page init
$SIS_ARTICULO_list->Page_Init();

// Page main
$SIS_ARTICULO_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_ARTICULO_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fSIS_ARTICULOlist = new ew_Form("fSIS_ARTICULOlist", "list");
fSIS_ARTICULOlist.FormKeyCountName = '<?php echo $SIS_ARTICULO_list->FormKeyCountName ?>';

// Form_CustomValidate event
fSIS_ARTICULOlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ARTICULOlist.ValidateRequired = true;
<?php } else { ?>
fSIS_ARTICULOlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_ARTICULOlist.Lists["x_codlinea"] = {"LinkField":"x_cod_linea","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOlist.Lists["x_calificacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOlist.Lists["x_calificacion"].Options = <?php echo json_encode($SIS_ARTICULO->calificacion->Options()) ?>;

// Form object for search
var CurrentSearchForm = fSIS_ARTICULOlistsrch = new ew_Form("fSIS_ARTICULOlistsrch");

// Validate function for search
fSIS_ARTICULOlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fSIS_ARTICULOlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ARTICULOlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fSIS_ARTICULOlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($SIS_ARTICULO_list->TotalRecs > 0 && $SIS_ARTICULO_list->ExportOptions->Visible()) { ?>
<?php $SIS_ARTICULO_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_ARTICULO_list->SearchOptions->Visible()) { ?>
<?php $SIS_ARTICULO_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($SIS_ARTICULO_list->FilterOptions->Visible()) { ?>
<?php $SIS_ARTICULO_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $SIS_ARTICULO_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($SIS_ARTICULO_list->TotalRecs <= 0)
			$SIS_ARTICULO_list->TotalRecs = $SIS_ARTICULO->SelectRecordCount();
	} else {
		if (!$SIS_ARTICULO_list->Recordset && ($SIS_ARTICULO_list->Recordset = $SIS_ARTICULO_list->LoadRecordset()))
			$SIS_ARTICULO_list->TotalRecs = $SIS_ARTICULO_list->Recordset->RecordCount();
	}
	$SIS_ARTICULO_list->StartRec = 1;
	if ($SIS_ARTICULO_list->DisplayRecs <= 0 || ($SIS_ARTICULO->Export <> "" && $SIS_ARTICULO->ExportAll)) // Display all records
		$SIS_ARTICULO_list->DisplayRecs = $SIS_ARTICULO_list->TotalRecs;
	if (!($SIS_ARTICULO->Export <> "" && $SIS_ARTICULO->ExportAll))
		$SIS_ARTICULO_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$SIS_ARTICULO_list->Recordset = $SIS_ARTICULO_list->LoadRecordset($SIS_ARTICULO_list->StartRec-1, $SIS_ARTICULO_list->DisplayRecs);

	// Set no record found message
	if ($SIS_ARTICULO->CurrentAction == "" && $SIS_ARTICULO_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$SIS_ARTICULO_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($SIS_ARTICULO_list->SearchWhere == "0=101")
			$SIS_ARTICULO_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$SIS_ARTICULO_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$SIS_ARTICULO_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($SIS_ARTICULO->Export == "" && $SIS_ARTICULO->CurrentAction == "") { ?>
<form name="fSIS_ARTICULOlistsrch" id="fSIS_ARTICULOlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($SIS_ARTICULO_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fSIS_ARTICULOlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="SIS_ARTICULO">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$SIS_ARTICULO_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$SIS_ARTICULO->RowType = EW_ROWTYPE_SEARCH;

// Render row
$SIS_ARTICULO->ResetAttrs();
$SIS_ARTICULO_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
	<div id="xsc_codarticulo" class="ewCell form-group">
		<label for="x_codarticulo" class="ewSearchCaption ewLabel"><?php echo $SIS_ARTICULO->codarticulo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_codarticulo" id="z_codarticulo" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="SIS_ARTICULO" data-field="x_codarticulo" name="x_codarticulo" id="x_codarticulo" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO->codarticulo->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO->codarticulo->EditValue ?>"<?php echo $SIS_ARTICULO->codarticulo->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($SIS_ARTICULO_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($SIS_ARTICULO_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $SIS_ARTICULO_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($SIS_ARTICULO_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($SIS_ARTICULO_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($SIS_ARTICULO_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($SIS_ARTICULO_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $SIS_ARTICULO_list->ShowPageHeader(); ?>
<?php
$SIS_ARTICULO_list->ShowMessage();
?>
<?php if ($SIS_ARTICULO_list->TotalRecs > 0 || $SIS_ARTICULO->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fSIS_ARTICULOlist" id="fSIS_ARTICULOlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_ARTICULO_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_ARTICULO_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_ARTICULO">
<div id="gmp_SIS_ARTICULO" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($SIS_ARTICULO_list->TotalRecs > 0) { ?>
<table id="tbl_SIS_ARTICULOlist" class="table ewTable">
<?php echo $SIS_ARTICULO->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$SIS_ARTICULO_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$SIS_ARTICULO_list->RenderListOptions();

// Render list options (header, left)
$SIS_ARTICULO_list->ListOptions->Render("header", "left");
?>
<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->codarticulo) == "") { ?>
		<th data-name="codarticulo"><div id="elh_SIS_ARTICULO_codarticulo" class="SIS_ARTICULO_codarticulo"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->codarticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codarticulo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->codarticulo) ?>',1);"><div id="elh_SIS_ARTICULO_codarticulo" class="SIS_ARTICULO_codarticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->codarticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->codarticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->codarticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->descripcion->Visible) { // descripcion ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_SIS_ARTICULO_descripcion" class="SIS_ARTICULO_descripcion"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->descripcion) ?>',1);"><div id="elh_SIS_ARTICULO_descripcion" class="SIS_ARTICULO_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->descripcion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->precio->Visible) { // precio ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->precio) == "") { ?>
		<th data-name="precio"><div id="elh_SIS_ARTICULO_precio" class="SIS_ARTICULO_precio"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->precio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->precio) ?>',1);"><div id="elh_SIS_ARTICULO_precio" class="SIS_ARTICULO_precio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->precio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->precio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->precio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->caracteristicas->Visible) { // caracteristicas ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->caracteristicas) == "") { ?>
		<th data-name="caracteristicas"><div id="elh_SIS_ARTICULO_caracteristicas" class="SIS_ARTICULO_caracteristicas"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->caracteristicas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="caracteristicas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->caracteristicas) ?>',1);"><div id="elh_SIS_ARTICULO_caracteristicas" class="SIS_ARTICULO_caracteristicas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->caracteristicas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->caracteristicas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->caracteristicas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->garantia->Visible) { // garantia ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->garantia) == "") { ?>
		<th data-name="garantia"><div id="elh_SIS_ARTICULO_garantia" class="SIS_ARTICULO_garantia"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->garantia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="garantia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->garantia) ?>',1);"><div id="elh_SIS_ARTICULO_garantia" class="SIS_ARTICULO_garantia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->garantia->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->garantia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->garantia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->codlinea->Visible) { // codlinea ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->codlinea) == "") { ?>
		<th data-name="codlinea"><div id="elh_SIS_ARTICULO_codlinea" class="SIS_ARTICULO_codlinea"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->codlinea->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codlinea"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->codlinea) ?>',1);"><div id="elh_SIS_ARTICULO_codlinea" class="SIS_ARTICULO_codlinea">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->codlinea->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->codlinea->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->codlinea->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->calificacion->Visible) { // calificacion ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->calificacion) == "") { ?>
		<th data-name="calificacion"><div id="elh_SIS_ARTICULO_calificacion" class="SIS_ARTICULO_calificacion"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->calificacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="calificacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->calificacion) ?>',1);"><div id="elh_SIS_ARTICULO_calificacion" class="SIS_ARTICULO_calificacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->calificacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->calificacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->calificacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->fecultimamod->Visible) { // fecultimamod ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->fecultimamod) == "") { ?>
		<th data-name="fecultimamod"><div id="elh_SIS_ARTICULO_fecultimamod" class="SIS_ARTICULO_fecultimamod"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->fecultimamod->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecultimamod"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->fecultimamod) ?>',1);"><div id="elh_SIS_ARTICULO_fecultimamod" class="SIS_ARTICULO_fecultimamod">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->fecultimamod->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->fecultimamod->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->fecultimamod->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($SIS_ARTICULO->usuarioultimamod->Visible) { // usuarioultimamod ?>
	<?php if ($SIS_ARTICULO->SortUrl($SIS_ARTICULO->usuarioultimamod) == "") { ?>
		<th data-name="usuarioultimamod"><div id="elh_SIS_ARTICULO_usuarioultimamod" class="SIS_ARTICULO_usuarioultimamod"><div class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->usuarioultimamod->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuarioultimamod"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $SIS_ARTICULO->SortUrl($SIS_ARTICULO->usuarioultimamod) ?>',1);"><div id="elh_SIS_ARTICULO_usuarioultimamod" class="SIS_ARTICULO_usuarioultimamod">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $SIS_ARTICULO->usuarioultimamod->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($SIS_ARTICULO->usuarioultimamod->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($SIS_ARTICULO->usuarioultimamod->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$SIS_ARTICULO_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($SIS_ARTICULO->ExportAll && $SIS_ARTICULO->Export <> "") {
	$SIS_ARTICULO_list->StopRec = $SIS_ARTICULO_list->TotalRecs;
} else {

	// Set the last record to display
	if ($SIS_ARTICULO_list->TotalRecs > $SIS_ARTICULO_list->StartRec + $SIS_ARTICULO_list->DisplayRecs - 1)
		$SIS_ARTICULO_list->StopRec = $SIS_ARTICULO_list->StartRec + $SIS_ARTICULO_list->DisplayRecs - 1;
	else
		$SIS_ARTICULO_list->StopRec = $SIS_ARTICULO_list->TotalRecs;
}
$SIS_ARTICULO_list->RecCnt = $SIS_ARTICULO_list->StartRec - 1;
if ($SIS_ARTICULO_list->Recordset && !$SIS_ARTICULO_list->Recordset->EOF) {
	$SIS_ARTICULO_list->Recordset->MoveFirst();
	$bSelectLimit = $SIS_ARTICULO_list->UseSelectLimit;
	if (!$bSelectLimit && $SIS_ARTICULO_list->StartRec > 1)
		$SIS_ARTICULO_list->Recordset->Move($SIS_ARTICULO_list->StartRec - 1);
} elseif (!$SIS_ARTICULO->AllowAddDeleteRow && $SIS_ARTICULO_list->StopRec == 0) {
	$SIS_ARTICULO_list->StopRec = $SIS_ARTICULO->GridAddRowCount;
}

// Initialize aggregate
$SIS_ARTICULO->RowType = EW_ROWTYPE_AGGREGATEINIT;
$SIS_ARTICULO->ResetAttrs();
$SIS_ARTICULO_list->RenderRow();
while ($SIS_ARTICULO_list->RecCnt < $SIS_ARTICULO_list->StopRec) {
	$SIS_ARTICULO_list->RecCnt++;
	if (intval($SIS_ARTICULO_list->RecCnt) >= intval($SIS_ARTICULO_list->StartRec)) {
		$SIS_ARTICULO_list->RowCnt++;

		// Set up key count
		$SIS_ARTICULO_list->KeyCount = $SIS_ARTICULO_list->RowIndex;

		// Init row class and style
		$SIS_ARTICULO->ResetAttrs();
		$SIS_ARTICULO->CssClass = "";
		if ($SIS_ARTICULO->CurrentAction == "gridadd") {
		} else {
			$SIS_ARTICULO_list->LoadRowValues($SIS_ARTICULO_list->Recordset); // Load row values
		}
		$SIS_ARTICULO->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$SIS_ARTICULO->RowAttrs = array_merge($SIS_ARTICULO->RowAttrs, array('data-rowindex'=>$SIS_ARTICULO_list->RowCnt, 'id'=>'r' . $SIS_ARTICULO_list->RowCnt . '_SIS_ARTICULO', 'data-rowtype'=>$SIS_ARTICULO->RowType));

		// Render row
		$SIS_ARTICULO_list->RenderRow();

		// Render list options
		$SIS_ARTICULO_list->RenderListOptions();
?>
	<tr<?php echo $SIS_ARTICULO->RowAttributes() ?>>
<?php

// Render list options (body, left)
$SIS_ARTICULO_list->ListOptions->Render("body", "left", $SIS_ARTICULO_list->RowCnt);
?>
	<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
		<td data-name="codarticulo"<?php echo $SIS_ARTICULO->codarticulo->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_codarticulo" class="SIS_ARTICULO_codarticulo">
<span<?php echo $SIS_ARTICULO->codarticulo->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->codarticulo->ListViewValue() ?></span>
</span>
<a id="<?php echo $SIS_ARTICULO_list->PageObjName . "_row_" . $SIS_ARTICULO_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $SIS_ARTICULO->descripcion->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_descripcion" class="SIS_ARTICULO_descripcion">
<span<?php echo $SIS_ARTICULO->descripcion->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->descripcion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->precio->Visible) { // precio ?>
		<td data-name="precio"<?php echo $SIS_ARTICULO->precio->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_precio" class="SIS_ARTICULO_precio">
<span<?php echo $SIS_ARTICULO->precio->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->precio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->caracteristicas->Visible) { // caracteristicas ?>
		<td data-name="caracteristicas"<?php echo $SIS_ARTICULO->caracteristicas->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_caracteristicas" class="SIS_ARTICULO_caracteristicas">
<span<?php echo $SIS_ARTICULO->caracteristicas->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->caracteristicas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->garantia->Visible) { // garantia ?>
		<td data-name="garantia"<?php echo $SIS_ARTICULO->garantia->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_garantia" class="SIS_ARTICULO_garantia">
<span<?php echo $SIS_ARTICULO->garantia->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->garantia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->codlinea->Visible) { // codlinea ?>
		<td data-name="codlinea"<?php echo $SIS_ARTICULO->codlinea->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_codlinea" class="SIS_ARTICULO_codlinea">
<span<?php echo $SIS_ARTICULO->codlinea->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->codlinea->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->calificacion->Visible) { // calificacion ?>
		<td data-name="calificacion"<?php echo $SIS_ARTICULO->calificacion->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_calificacion" class="SIS_ARTICULO_calificacion">
<span<?php echo $SIS_ARTICULO->calificacion->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->calificacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->fecultimamod->Visible) { // fecultimamod ?>
		<td data-name="fecultimamod"<?php echo $SIS_ARTICULO->fecultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_fecultimamod" class="SIS_ARTICULO_fecultimamod">
<span<?php echo $SIS_ARTICULO->fecultimamod->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->fecultimamod->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($SIS_ARTICULO->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<td data-name="usuarioultimamod"<?php echo $SIS_ARTICULO->usuarioultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_list->RowCnt ?>_SIS_ARTICULO_usuarioultimamod" class="SIS_ARTICULO_usuarioultimamod">
<span<?php echo $SIS_ARTICULO->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$SIS_ARTICULO_list->ListOptions->Render("body", "right", $SIS_ARTICULO_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($SIS_ARTICULO->CurrentAction <> "gridadd")
		$SIS_ARTICULO_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($SIS_ARTICULO->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($SIS_ARTICULO_list->Recordset)
	$SIS_ARTICULO_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($SIS_ARTICULO->CurrentAction <> "gridadd" && $SIS_ARTICULO->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($SIS_ARTICULO_list->Pager)) $SIS_ARTICULO_list->Pager = new cPrevNextPager($SIS_ARTICULO_list->StartRec, $SIS_ARTICULO_list->DisplayRecs, $SIS_ARTICULO_list->TotalRecs) ?>
<?php if ($SIS_ARTICULO_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($SIS_ARTICULO_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $SIS_ARTICULO_list->PageUrl() ?>start=<?php echo $SIS_ARTICULO_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($SIS_ARTICULO_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $SIS_ARTICULO_list->PageUrl() ?>start=<?php echo $SIS_ARTICULO_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $SIS_ARTICULO_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($SIS_ARTICULO_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $SIS_ARTICULO_list->PageUrl() ?>start=<?php echo $SIS_ARTICULO_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($SIS_ARTICULO_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $SIS_ARTICULO_list->PageUrl() ?>start=<?php echo $SIS_ARTICULO_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $SIS_ARTICULO_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $SIS_ARTICULO_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $SIS_ARTICULO_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $SIS_ARTICULO_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_ARTICULO_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($SIS_ARTICULO_list->TotalRecs == 0 && $SIS_ARTICULO->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($SIS_ARTICULO_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fSIS_ARTICULOlistsrch.Init();
fSIS_ARTICULOlistsrch.FilterList = <?php echo $SIS_ARTICULO_list->GetFilterList() ?>;
fSIS_ARTICULOlist.Init();
</script>
<?php
$SIS_ARTICULO_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_ARTICULO_list->Page_Terminate();
?>
