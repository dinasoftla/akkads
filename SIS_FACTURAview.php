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

$SIS_FACTURA_view = NULL; // Initialize page object first

class cSIS_FACTURA_view extends cSIS_FACTURA {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_FACTURA';

	// Page object name
	var $PageObjName = 'SIS_FACTURA_view';

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
	var $ExportExcelCustom = TRUE;
	var $ExportWordCustom = TRUE;
	var $ExportPdfCustom = TRUE;
	var $ExportEmailCustom = TRUE;

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

		// Table object (SIS_FACTURA)
		if (!isset($GLOBALS["SIS_FACTURA"]) || get_class($GLOBALS["SIS_FACTURA"]) == "cSIS_FACTURA") {
			$GLOBALS["SIS_FACTURA"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_FACTURA"];
		}
		$KeyUrl = "";
		if (@$_GET["numfactura"] <> "") {
			$this->RecKey["numfactura"] = $_GET["numfactura"];
			$KeyUrl .= "&amp;numfactura=" . urlencode($this->RecKey["numfactura"]);
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
		if (@$_GET["numfactura"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["numfactura"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Custom export (post back from ew_ApplyTemplate), export and terminate page
		if (@$_POST["customexport"] <> "") {
			$this->CustomExport = $_POST["customexport"];
			$this->Export = $this->CustomExport;
			$this->Page_Terminate();
			exit();
		}

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
		if (@$_POST["customexport"] == "") {

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		}

		// Export
		global $EW_EXPORT, $SIS_FACTURA;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
			if (is_array(@$_SESSION[EW_SESSION_TEMP_IMAGES])) // Restore temp images
				$gTmpImages = @$_SESSION[EW_SESSION_TEMP_IMAGES];
			if (@$_POST["data"] <> "")
				$sContent = $_POST["data"];
			$gsExportFile = @$_POST["filename"];
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
	if ($this->CustomExport <> "") { // Save temp images array for custom export
		if (is_array($gTmpImages))
			$_SESSION[EW_SESSION_TEMP_IMAGES] = $gTmpImages;
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
	var $SIS_FACTURA_DETALLE_Count;
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
			if (@$_GET["numfactura"] <> "") {
				$this->numfactura->setQueryStringValue($_GET["numfactura"]);
				$this->RecKey["numfactura"] = $this->numfactura->QueryStringValue;
			} elseif (@$_POST["numfactura"] <> "") {
				$this->numfactura->setFormValue($_POST["numfactura"]);
				$this->RecKey["numfactura"] = $this->numfactura->FormValue;
			} else {
				$sReturnUrl = "SIS_FACTURAlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "SIS_FACTURAlist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "SIS_FACTURAlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_SIS_FACTURA_DETALLE"
		$item = &$option->Add("detail_SIS_FACTURA_DETALLE");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("SIS_FACTURA_DETALLE", "TblCaption");
		$body .= str_replace("%c", $this->SIS_FACTURA_DETALLE_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("SIS_FACTURA_DETALLElist.php?" . EW_TABLE_SHOW_MASTER . "=SIS_FACTURA&fk_numfactura=" . urlencode(strval($this->numfactura->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["SIS_FACTURA_DETALLE_grid"] && $GLOBALS["SIS_FACTURA_DETALLE_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'SIS_FACTURA_DETALLE')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=SIS_FACTURA_DETALLE")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "SIS_FACTURA_DETALLE";
		}
		if ($GLOBALS["SIS_FACTURA_DETALLE_grid"] && $GLOBALS["SIS_FACTURA_DETALLE_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'SIS_FACTURA_DETALLE')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=SIS_FACTURA_DETALLE")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "SIS_FACTURA_DETALLE";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'SIS_FACTURA_DETALLE');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "SIS_FACTURA_DETALLE";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		$this->numfactura->setDbValue($rs->fields('numfactura'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->transporte->setDbValue($rs->fields('transporte'));
		if (!isset($GLOBALS["SIS_FACTURA_DETALLE_grid"])) $GLOBALS["SIS_FACTURA_DETALLE_grid"] = new cSIS_FACTURA_DETALLE_grid;
		$sDetailFilter = $GLOBALS["SIS_FACTURA_DETALLE"]->SqlDetailFilter_SIS_FACTURA();
		$sDetailFilter = str_replace("@numfactura@", ew_AdjustSql($this->numfactura->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["SIS_FACTURA_DETALLE"]->setCurrentMasterTable("SIS_FACTURA");
		$sDetailFilter = $GLOBALS["SIS_FACTURA_DETALLE"]->ApplyUserIDFilters($sDetailFilter);
		$this->SIS_FACTURA_DETALLE_Count = $GLOBALS["SIS_FACTURA_DETALLE"]->LoadRecordCount($sDetailFilter);
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		if ($this->ExportExcelCustom)
			$item->Body = "<a href=\"javascript:void(0);\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" onclick=\"ew_Export(document.fSIS_FACTURAview,'" . $this->ExportExcelUrl . "','excel',true);\">" . $Language->Phrase("ExportToExcel") . "</a>";
		else
			$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		if ($this->ExportWordCustom)
			$item->Body = "<a href=\"javascript:void(0);\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" onclick=\"ew_Export(document.fSIS_FACTURAview,'" . $this->ExportWordUrl . "','word',true);\">" . $Language->Phrase("ExportToWord") . "</a>";
		else
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
		if ($this->ExportPdfCustom)
			$item->Body = "<a href=\"javascript:void(0);\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" onclick=\"ew_Export(document.fSIS_FACTURAview,'" . $this->ExportPdfUrl . "','pdf',true);\">" . $Language->Phrase("ExportToPDF") . "</a>";
		else
			$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->ExportEmailCustom ? ",url:'" . $this->PageUrl() . "export=email&amp;custom=1'" : "";
		$item->Body = "<button id=\"emf_SIS_FACTURA\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_SIS_FACTURA',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fSIS_FACTURAview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (SIS_FACTURA_DETALLE)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("SIS_FACTURA_DETALLE", explode(",", $this->getCurrentDetailTable()))) {
			global $SIS_FACTURA_DETALLE;
			if (!isset($SIS_FACTURA_DETALLE)) $SIS_FACTURA_DETALLE = new cSIS_FACTURA_DETALLE;
			$rsdetail = $SIS_FACTURA_DETALLE->LoadRs($SIS_FACTURA_DETALLE->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$SIS_FACTURA_DETALLE->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
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
				if ($GLOBALS["SIS_FACTURA_DETALLE_grid"]->DetailView) {
					$GLOBALS["SIS_FACTURA_DETALLE_grid"]->CurrentMode = "view";

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
if (!isset($SIS_FACTURA_view)) $SIS_FACTURA_view = new cSIS_FACTURA_view();

// Page init
$SIS_FACTURA_view->Page_Init();

// Page main
$SIS_FACTURA_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_FACTURA_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($SIS_FACTURA->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fSIS_FACTURAview = new ew_Form("fSIS_FACTURAview", "view");

// Form_CustomValidate event
fSIS_FACTURAview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_FACTURAview.ValidateRequired = true;
<?php } else { ?>
fSIS_FACTURAview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_FACTURAview.Lists["x_codcliente"] = {"LinkField":"x_codcliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAview.Lists["x_descuento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAview.Lists["x_descuento"].Options = <?php echo json_encode($SIS_FACTURA->descuento->Options()) ?>;
fSIS_FACTURAview.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAview.Lists["x_estado"].Options = <?php echo json_encode($SIS_FACTURA->estado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($SIS_FACTURA->Export == "") { ?>
<div class="ewToolbar">
<?php if ($SIS_FACTURA->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $SIS_FACTURA_view->ExportOptions->Render("body") ?>
<?php
	foreach ($SIS_FACTURA_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($SIS_FACTURA->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $SIS_FACTURA_view->ShowPageHeader(); ?>
<?php
$SIS_FACTURA_view->ShowMessage();
?>
<form name="fSIS_FACTURAview" id="fSIS_FACTURAview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_FACTURA_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_FACTURA_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_FACTURA">
<div id="tpd_SIS_FACTURAview" class="ewCustomTemplate"></div>
<script id="tpm_SIS_FACTURAview" type="text/html">
<div id="ct_SIS_FACTURA_view"><!-- Header image -->
<IMG SRC="http://127.0.0.1/akkads/phpimages/encabezadofactu.png">
<?php
	$numfactura = $_GET["numfactura"];
	$query = "SELECT F.fecha, 
	   		         C.codcliente,
	   		         C.nombre,
	   		         C.telefonos,
	   		         C.direccion,
	   		         C.email
	   		  FROM SIS_FACTURA F
	   		         INNER JOIN SIS_CLIENTE C ON
	   		         C.codcliente = F.codcliente
			  WHERE numfactura = ". $numfactura;
	$detallefactura = ew_Execute($query);
	foreach ($detallefactura as $rs => $value) {
		echo "<P><b>NUMERO DE FACTURA: </b>" . $_GET["numfactura"]."  ". "<b>FECHA: </b>" . $value["fecha"];
	 	echo "<P><b>CLIENTE: </b> (" . $value["codcliente"] . ") " .  $value["nombre"]. "  "."<b>TELEFONO(S): </b>" . $value["telefonos"]. "  ". "<b>EMAIL: </b>" . $value["email"];	
		echo "<P><b>DIRECCION: </b>" . $value["direccion"];
	}
?>
<style>
table, th, td {
border-bottom: 1px solid #ddd;
}
</style>
<table style="width:100%">
  <col>
  <tr>
	<th style="width:10%">CODIGO</th>
	<th style="width:60%">ARTICULO</th>
	<th style="width:10%">CANTIDAD</th>
	<th style="width:10%">COSTO</th>
	<th style="width:10%">TOTAL</th>
  </tr>
  <?php
  	$query = "EXEC SP_CONSULTARDETALLEFACTURA ". $numfactura;
	$detallefactura = ew_Execute($query);
	foreach ($detallefactura as $rs => $value) {
		echo '<tr>
			  <td align= "left">';
				echo $value["codarticulo"]. "  ";
		echo '</td>';
		echo '<td align= "left">';
		   		echo $value["descripcion"]. "  ";
		echo '</td>';
		echo '<td align= "center">';
				echo $value["cantidad"]. "  ";
		echo '</td>';
		echo '<td align= "center">';
				echo $value["preciounitario"]. "  ";
		echo '</td>';
		echo '<td align= "right">';
				echo $value["total"]. "  ";
		echo '</td>';
		echo '</tr>';
	}
	$query5 = "select descuento,transporte from SIS_FACTURA where numfactura = ". $numfactura;
	$infopedido = ew_Execute($query5);
	$aplicardescuento = 0;
	$transporte = 0;
	foreach ($infopedido as $rs => $value) {	
		$aplicardescuento = $value["descuento"];
		$transporte = $value["transporte"];
	}
	$piefactura = "";
	$leyendafactura = "";
	$query3 = "SELECT descuento,
					  piefactura,
					  leyendafactura
			   FROM SIS_COMPANIA
			   WHERE codcompania = 1";
	$infocompania = ew_Execute($query3);
	foreach ($infocompania as $rs => $value) {	
		if ($aplicardescuento == 1)
		{
			$descuentocompania = $value["descuento"];
		}
		else
		{
			$descuentocompania = 0;
		}
		$piefactura = $value["piefactura"];
		$leyendafactura = $value["leyendafactura"];
	}
	$query2 = "select sum(total) from SIS_FACTURA_DETALLE where numfactura = ". $numfactura;
	$subtotal = ew_ExecuteScalar($query2);
	$descuentoaplicar = ($subtotal * ($descuentocompania)/100);
	$totalcondescuento = $subtotal - $descuentoaplicar + $transporte;
	$totalizacion = "<div> <P>". "SUB-TOTAL: ". $subtotal.
					"<P>" ."DESCUENTO: ". $descuentoaplicar.
					"<P>". "TOTAL: " . $totalcondescuento .
			  "</div>" ;

		// SUB TOTAL
		echo '<tr>
			  <td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "center">';
				echo "";
		echo '</td>';
		echo '<td align= "right">';
				echo '<pre><b>SUB-TOTAL <IMG SRC="http://127.0.0.1/akkads/phpimages/simbolocolon.jpg"></b></pre>';
		echo '</td>';
		echo '<td align= "right">';
				echo round($subtotal);
		echo '</td>';
		echo '</tr>';

		// DESCUENTO
		echo '<tr>
			  <td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "center">';
				echo "";
		echo '</td>';
		echo '<td align= "right">';
				echo '<pre><b>DESCUENTO <IMG SRC="http://127.0.0.1/akkads/phpimages/simbolocolon.jpg"></b></pre>';
		echo '</td>';
		echo '<td align= "right">';
				echo round($descuentoaplicar);
		echo '</td>';
		echo '</tr>';

		// TRANSPORTE
		echo '<tr>
			  <td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "center">';
				echo "";
		echo '</td>';
		echo '<td align= "right">';
				echo '<pre><b>TRANSPORTE <IMG SRC="http://127.0.0.1/akkads/phpimages/simbolocolon.jpg"></b></pre>';
		echo '</td>';
		echo '<td align= "right">';
				echo round($transporte);
		echo '</td>';
		echo '</tr>';

		// TOTAL
		echo '<tr>
			  <td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "left">';
				echo "";
		echo '</td>';
		echo '<td align= "center">';
				echo "";
		echo '</td>';
		echo '<td align= "right">';
				echo '<pre><b>TOTAL <IMG SRC="http://127.0.0.1/akkads/phpimages/simbolocolon.jpg"></b></pre>';
		echo '</td>';
		echo '<td align= "right">';
				echo round($totalcondescuento);
		echo '</td>';
		echo '</tr>';
?>		
</table>
<?php
	$parrafo = '<div>
	             <pre>
				 <P>Recibido de conforme:__________________________
				 </pre>
			  </div>' ; 
	echo $parrafo;
	$parrafo = "<div>
				 <P>" . $piefactura .
			  "</div>" ;
	echo $parrafo;
	$parrafo = "<div>
				 <P>" . $leyendafactura .
			  "</div>" ;
	echo $parrafo;
?>
<!-- footer -->
<?php
?>
</div>
</script>
<table class="table table-bordered table-striped ewViewTable" style="display: none">
<?php if ($SIS_FACTURA->numfactura->Visible) { // numfactura ?>
	<tr id="r_numfactura">
		<td><span id="elh_SIS_FACTURA_numfactura"><script id="tpc_SIS_FACTURA_numfactura" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->numfactura->FldCaption() ?></span></script></span></td>
		<td data-name="numfactura"<?php echo $SIS_FACTURA->numfactura->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_numfactura" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_numfactura">
<span<?php echo $SIS_FACTURA->numfactura->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->numfactura->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->fecha->Visible) { // fecha ?>
	<tr id="r_fecha">
		<td><span id="elh_SIS_FACTURA_fecha"><script id="tpc_SIS_FACTURA_fecha" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->fecha->FldCaption() ?></span></script></span></td>
		<td data-name="fecha"<?php echo $SIS_FACTURA->fecha->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_fecha" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_fecha">
<span<?php echo $SIS_FACTURA->fecha->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->fecha->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->codcliente->Visible) { // codcliente ?>
	<tr id="r_codcliente">
		<td><span id="elh_SIS_FACTURA_codcliente"><script id="tpc_SIS_FACTURA_codcliente" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->codcliente->FldCaption() ?></span></script></span></td>
		<td data-name="codcliente"<?php echo $SIS_FACTURA->codcliente->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_codcliente" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_codcliente">
<span<?php echo $SIS_FACTURA->codcliente->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->codcliente->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->fecultimamod->Visible) { // fecultimamod ?>
	<tr id="r_fecultimamod">
		<td><span id="elh_SIS_FACTURA_fecultimamod"><script id="tpc_SIS_FACTURA_fecultimamod" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->fecultimamod->FldCaption() ?></span></script></span></td>
		<td data-name="fecultimamod"<?php echo $SIS_FACTURA->fecultimamod->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_fecultimamod" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_fecultimamod">
<span<?php echo $SIS_FACTURA->fecultimamod->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->fecultimamod->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->usuarioultimamod->Visible) { // usuarioultimamod ?>
	<tr id="r_usuarioultimamod">
		<td><span id="elh_SIS_FACTURA_usuarioultimamod"><script id="tpc_SIS_FACTURA_usuarioultimamod" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->usuarioultimamod->FldCaption() ?></span></script></span></td>
		<td data-name="usuarioultimamod"<?php echo $SIS_FACTURA->usuarioultimamod->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_usuarioultimamod" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_usuarioultimamod">
<span<?php echo $SIS_FACTURA->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->usuarioultimamod->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->descuento->Visible) { // descuento ?>
	<tr id="r_descuento">
		<td><span id="elh_SIS_FACTURA_descuento"><script id="tpc_SIS_FACTURA_descuento" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->descuento->FldCaption() ?></span></script></span></td>
		<td data-name="descuento"<?php echo $SIS_FACTURA->descuento->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_descuento" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_descuento">
<span<?php echo $SIS_FACTURA->descuento->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->descuento->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_SIS_FACTURA_estado"><script id="tpc_SIS_FACTURA_estado" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->estado->FldCaption() ?></span></script></span></td>
		<td data-name="estado"<?php echo $SIS_FACTURA->estado->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_estado" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_estado">
<span<?php echo $SIS_FACTURA->estado->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->estado->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->numpedido->Visible) { // numpedido ?>
	<tr id="r_numpedido">
		<td><span id="elh_SIS_FACTURA_numpedido"><script id="tpc_SIS_FACTURA_numpedido" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->numpedido->FldCaption() ?></span></script></span></td>
		<td data-name="numpedido"<?php echo $SIS_FACTURA->numpedido->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_numpedido" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_numpedido">
<span<?php echo $SIS_FACTURA->numpedido->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->numpedido->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
<?php if ($SIS_FACTURA->transporte->Visible) { // transporte ?>
	<tr id="r_transporte">
		<td><span id="elh_SIS_FACTURA_transporte"><script id="tpc_SIS_FACTURA_transporte" class="SIS_FACTURAview" type="text/html"><span><?php echo $SIS_FACTURA->transporte->FldCaption() ?></span></script></span></td>
		<td data-name="transporte"<?php echo $SIS_FACTURA->transporte->CellAttributes() ?>>
<script id="tpx_SIS_FACTURA_transporte" class="SIS_FACTURAview" type="text/html">
<span id="el_SIS_FACTURA_transporte">
<span<?php echo $SIS_FACTURA->transporte->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->transporte->ViewValue ?></span>
</span>
</script>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("SIS_FACTURA_DETALLE", explode(",", $SIS_FACTURA->getCurrentDetailTable())) && $SIS_FACTURA_DETALLE->DetailView) {
?>
<?php if ($SIS_FACTURA->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("SIS_FACTURA_DETALLE", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "SIS_FACTURA_DETALLEgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
ew_ApplyTemplate("tpd_SIS_FACTURAview", "tpm_SIS_FACTURAview", "SIS_FACTURAview", "<?php echo $SIS_FACTURA->CustomExport ?>");
jQuery("script.SIS_FACTURAview_js").each(function(){ew_AddScript(this.text);});
</script>
<?php if ($SIS_FACTURA->Export == "") { ?>
<script type="text/javascript">
fSIS_FACTURAview.Init();
</script>
<?php } ?>
<?php
$SIS_FACTURA_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($SIS_FACTURA->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$SIS_FACTURA_view->Page_Terminate();
?>
