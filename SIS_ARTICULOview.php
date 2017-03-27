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

$SIS_ARTICULO_view = NULL; // Initialize page object first

class cSIS_ARTICULO_view extends cSIS_ARTICULO {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_ARTICULO';

	// Page object name
	var $PageObjName = 'SIS_ARTICULO_view';

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
		$KeyUrl = "";
		if (@$_GET["codarticulo"] <> "") {
			$this->RecKey["codarticulo"] = $_GET["codarticulo"];
			$KeyUrl .= "&amp;codarticulo=" . urlencode($this->RecKey["codarticulo"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["codarticulo"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["codarticulo"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["codarticulo"] <> "") {
				$this->codarticulo->setQueryStringValue($_GET["codarticulo"]);
				$this->RecKey["codarticulo"] = $this->codarticulo->QueryStringValue;
			} elseif (@$_POST["codarticulo"] <> "") {
				$this->codarticulo->setFormValue($_POST["codarticulo"]);
				$this->RecKey["codarticulo"] = $this->codarticulo->FormValue;
			} else {
				$sReturnUrl = "SIS_ARTICULOlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "SIS_ARTICULOlist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "SIS_ARTICULOlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// foto
			$this->foto->LinkCustomAttributes = "";
			$this->foto->UploadPath = "uploads";
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->HrefValue = ew_GetFileUploadUrl($this->foto, $this->foto->Upload->DbValue); // Add prefix/suffix
				$this->foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto->HrefValue = ew_ConvertFullUrl($this->foto->HrefValue);
			} else {
				$this->foto->HrefValue = "";
			}
			$this->foto->HrefValue2 = $this->foto->UploadPath . $this->foto->Upload->DbValue;
			$this->foto->TooltipValue = "";
			if ($this->foto->UseColorbox) {
				if (ew_Empty($this->foto->TooltipValue))
					$this->foto->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->foto->LinkAttrs["data-rel"] = "SIS_ARTICULO_x_foto";
				ew_AppendClass($this->foto->LinkAttrs["class"], "ewLightbox");
			}

			// foto2
			$this->foto2->LinkCustomAttributes = "";
			$this->foto2->UploadPath = "uploads";
			if (!ew_Empty($this->foto2->Upload->DbValue)) {
				$this->foto2->HrefValue = ew_GetFileUploadUrl($this->foto2, $this->foto2->Upload->DbValue); // Add prefix/suffix
				$this->foto2->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto2->HrefValue = ew_ConvertFullUrl($this->foto2->HrefValue);
			} else {
				$this->foto2->HrefValue = "";
			}
			$this->foto2->HrefValue2 = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;
			$this->foto2->TooltipValue = "";
			if ($this->foto2->UseColorbox) {
				if (ew_Empty($this->foto2->TooltipValue))
					$this->foto2->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->foto2->LinkAttrs["data-rel"] = "SIS_ARTICULO_x_foto2";
				ew_AppendClass($this->foto2->LinkAttrs["class"], "ewLightbox");
			}

			// foto3
			$this->foto3->LinkCustomAttributes = "";
			$this->foto3->UploadPath = "uploads";
			if (!ew_Empty($this->foto3->Upload->DbValue)) {
				$this->foto3->HrefValue = ew_GetFileUploadUrl($this->foto3, $this->foto3->Upload->DbValue); // Add prefix/suffix
				$this->foto3->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto3->HrefValue = ew_ConvertFullUrl($this->foto3->HrefValue);
			} else {
				$this->foto3->HrefValue = "";
			}
			$this->foto3->HrefValue2 = $this->foto3->UploadPath . $this->foto3->Upload->DbValue;
			$this->foto3->TooltipValue = "";
			if ($this->foto3->UseColorbox) {
				if (ew_Empty($this->foto3->TooltipValue))
					$this->foto3->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->foto3->LinkAttrs["data-rel"] = "SIS_ARTICULO_x_foto3";
				ew_AppendClass($this->foto3->LinkAttrs["class"], "ewLightbox");
			}

			// caracteristicas
			$this->caracteristicas->LinkCustomAttributes = "";
			$this->caracteristicas->HrefValue = "";
			$this->caracteristicas->TooltipValue = "";

			// garantia
			$this->garantia->LinkCustomAttributes = "";
			$this->garantia->HrefValue = "";
			$this->garantia->TooltipValue = "";

			// referencia
			$this->referencia->LinkCustomAttributes = "";
			$this->referencia->HrefValue = "";
			$this->referencia->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_SIS_ARTICULO\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_SIS_ARTICULO',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fSIS_ARTICULOview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_ARTICULOlist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($SIS_ARTICULO_view)) $SIS_ARTICULO_view = new cSIS_ARTICULO_view();

// Page init
$SIS_ARTICULO_view->Page_Init();

// Page main
$SIS_ARTICULO_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_ARTICULO_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($SIS_ARTICULO->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fSIS_ARTICULOview = new ew_Form("fSIS_ARTICULOview", "view");

// Form_CustomValidate event
fSIS_ARTICULOview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ARTICULOview.ValidateRequired = true;
<?php } else { ?>
fSIS_ARTICULOview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_ARTICULOview.Lists["x_codlinea"] = {"LinkField":"x_cod_linea","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOview.Lists["x_calificacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOview.Lists["x_calificacion"].Options = <?php echo json_encode($SIS_ARTICULO->calificacion->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($SIS_ARTICULO->Export == "") { ?>
<div class="ewToolbar">
<?php if ($SIS_ARTICULO->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $SIS_ARTICULO_view->ExportOptions->Render("body") ?>
<?php
	foreach ($SIS_ARTICULO_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($SIS_ARTICULO->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $SIS_ARTICULO_view->ShowPageHeader(); ?>
<?php
$SIS_ARTICULO_view->ShowMessage();
?>
<form name="fSIS_ARTICULOview" id="fSIS_ARTICULOview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_ARTICULO_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_ARTICULO_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_ARTICULO">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
	<tr id="r_codarticulo">
		<td><span id="elh_SIS_ARTICULO_codarticulo"><?php echo $SIS_ARTICULO->codarticulo->FldCaption() ?></span></td>
		<td data-name="codarticulo"<?php echo $SIS_ARTICULO->codarticulo->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_codarticulo">
<span<?php echo $SIS_ARTICULO->codarticulo->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->codarticulo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->descripcion->Visible) { // descripcion ?>
	<tr id="r_descripcion">
		<td><span id="elh_SIS_ARTICULO_descripcion"><?php echo $SIS_ARTICULO->descripcion->FldCaption() ?></span></td>
		<td data-name="descripcion"<?php echo $SIS_ARTICULO->descripcion->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_descripcion">
<span<?php echo $SIS_ARTICULO->descripcion->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->descripcion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->precio->Visible) { // precio ?>
	<tr id="r_precio">
		<td><span id="elh_SIS_ARTICULO_precio"><?php echo $SIS_ARTICULO->precio->FldCaption() ?></span></td>
		<td data-name="precio"<?php echo $SIS_ARTICULO->precio->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_precio">
<span<?php echo $SIS_ARTICULO->precio->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->precio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->foto->Visible) { // foto ?>
	<tr id="r_foto">
		<td><span id="elh_SIS_ARTICULO_foto"><?php echo $SIS_ARTICULO->foto->FldCaption() ?></span></td>
		<td data-name="foto"<?php echo $SIS_ARTICULO->foto->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_foto">
<span>
<?php echo ew_GetFileViewTag($SIS_ARTICULO->foto, $SIS_ARTICULO->foto->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->foto2->Visible) { // foto2 ?>
	<tr id="r_foto2">
		<td><span id="elh_SIS_ARTICULO_foto2"><?php echo $SIS_ARTICULO->foto2->FldCaption() ?></span></td>
		<td data-name="foto2"<?php echo $SIS_ARTICULO->foto2->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_foto2">
<span>
<?php echo ew_GetFileViewTag($SIS_ARTICULO->foto2, $SIS_ARTICULO->foto2->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->foto3->Visible) { // foto3 ?>
	<tr id="r_foto3">
		<td><span id="elh_SIS_ARTICULO_foto3"><?php echo $SIS_ARTICULO->foto3->FldCaption() ?></span></td>
		<td data-name="foto3"<?php echo $SIS_ARTICULO->foto3->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_foto3">
<span>
<?php echo ew_GetFileViewTag($SIS_ARTICULO->foto3, $SIS_ARTICULO->foto3->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->caracteristicas->Visible) { // caracteristicas ?>
	<tr id="r_caracteristicas">
		<td><span id="elh_SIS_ARTICULO_caracteristicas"><?php echo $SIS_ARTICULO->caracteristicas->FldCaption() ?></span></td>
		<td data-name="caracteristicas"<?php echo $SIS_ARTICULO->caracteristicas->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_caracteristicas">
<span<?php echo $SIS_ARTICULO->caracteristicas->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->caracteristicas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->garantia->Visible) { // garantia ?>
	<tr id="r_garantia">
		<td><span id="elh_SIS_ARTICULO_garantia"><?php echo $SIS_ARTICULO->garantia->FldCaption() ?></span></td>
		<td data-name="garantia"<?php echo $SIS_ARTICULO->garantia->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_garantia">
<span<?php echo $SIS_ARTICULO->garantia->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->garantia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->referencia->Visible) { // referencia ?>
	<tr id="r_referencia">
		<td><span id="elh_SIS_ARTICULO_referencia"><?php echo $SIS_ARTICULO->referencia->FldCaption() ?></span></td>
		<td data-name="referencia"<?php echo $SIS_ARTICULO->referencia->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_referencia">
<span<?php echo $SIS_ARTICULO->referencia->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->referencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->codlinea->Visible) { // codlinea ?>
	<tr id="r_codlinea">
		<td><span id="elh_SIS_ARTICULO_codlinea"><?php echo $SIS_ARTICULO->codlinea->FldCaption() ?></span></td>
		<td data-name="codlinea"<?php echo $SIS_ARTICULO->codlinea->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_codlinea">
<span<?php echo $SIS_ARTICULO->codlinea->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->codlinea->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->calificacion->Visible) { // calificacion ?>
	<tr id="r_calificacion">
		<td><span id="elh_SIS_ARTICULO_calificacion"><?php echo $SIS_ARTICULO->calificacion->FldCaption() ?></span></td>
		<td data-name="calificacion"<?php echo $SIS_ARTICULO->calificacion->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_calificacion">
<span<?php echo $SIS_ARTICULO->calificacion->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->calificacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->fecultimamod->Visible) { // fecultimamod ?>
	<tr id="r_fecultimamod">
		<td><span id="elh_SIS_ARTICULO_fecultimamod"><?php echo $SIS_ARTICULO->fecultimamod->FldCaption() ?></span></td>
		<td data-name="fecultimamod"<?php echo $SIS_ARTICULO->fecultimamod->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_fecultimamod">
<span<?php echo $SIS_ARTICULO->fecultimamod->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->fecultimamod->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_ARTICULO->usuarioultimamod->Visible) { // usuarioultimamod ?>
	<tr id="r_usuarioultimamod">
		<td><span id="elh_SIS_ARTICULO_usuarioultimamod"><?php echo $SIS_ARTICULO->usuarioultimamod->FldCaption() ?></span></td>
		<td data-name="usuarioultimamod"<?php echo $SIS_ARTICULO->usuarioultimamod->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_usuarioultimamod">
<span<?php echo $SIS_ARTICULO->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->usuarioultimamod->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($SIS_ARTICULO->Export == "") { ?>
<script type="text/javascript">
fSIS_ARTICULOview.Init();
</script>
<?php } ?>
<?php
$SIS_ARTICULO_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($SIS_ARTICULO->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$SIS_ARTICULO_view->Page_Terminate();
?>
