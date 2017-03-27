<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_TAREASinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_TAREAS_delete = NULL; // Initialize page object first

class cSIS_TAREAS_delete extends cSIS_TAREAS {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_TAREAS';

	// Page object name
	var $PageObjName = 'SIS_TAREAS_delete';

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

		// Table object (SIS_TAREAS)
		if (!isset($GLOBALS["SIS_TAREAS"]) || get_class($GLOBALS["SIS_TAREAS"]) == "cSIS_TAREAS") {
			$GLOBALS["SIS_TAREAS"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_TAREAS"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_TAREAS', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->codtarea->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $SIS_TAREAS;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_TAREAS);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("SIS_TAREASlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in SIS_TAREAS class, SIS_TAREASinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
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
		$this->codtarea->setDbValue($rs->fields('codtarea'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->respondable->setDbValue($rs->fields('respondable'));
		$this->fechainicio->setDbValue($rs->fields('fechainicio'));
		$this->fechafinal->setDbValue($rs->fields('fechafinal'));
		$this->prioridad->setDbValue($rs->fields('prioridad'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codtarea->DbValue = $row['codtarea'];
		$this->nombre->DbValue = $row['nombre'];
		$this->estado->DbValue = $row['estado'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->respondable->DbValue = $row['respondable'];
		$this->fechainicio->DbValue = $row['fechainicio'];
		$this->fechafinal->DbValue = $row['fechafinal'];
		$this->prioridad->DbValue = $row['prioridad'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// codtarea
		// nombre
		// estado
		// descripcion
		// respondable
		// fechainicio
		// fechafinal
		// prioridad

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codtarea
		$this->codtarea->ViewValue = $this->codtarea->CurrentValue;
		$this->codtarea->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// respondable
		if (strval($this->respondable->CurrentValue) <> "") {
			$this->respondable->ViewValue = $this->respondable->OptionCaption($this->respondable->CurrentValue);
		} else {
			$this->respondable->ViewValue = NULL;
		}
		$this->respondable->ViewCustomAttributes = "";

		// fechainicio
		$this->fechainicio->ViewValue = $this->fechainicio->CurrentValue;
		$this->fechainicio->ViewValue = ew_FormatDateTime($this->fechainicio->ViewValue, 7);
		$this->fechainicio->ViewCustomAttributes = "";

		// fechafinal
		$this->fechafinal->ViewValue = $this->fechafinal->CurrentValue;
		$this->fechafinal->ViewValue = ew_FormatDateTime($this->fechafinal->ViewValue, 7);
		$this->fechafinal->ViewCustomAttributes = "";

		// prioridad
		if (strval($this->prioridad->CurrentValue) <> "") {
			$this->prioridad->ViewValue = $this->prioridad->OptionCaption($this->prioridad->CurrentValue);
		} else {
			$this->prioridad->ViewValue = NULL;
		}
		$this->prioridad->ViewCustomAttributes = "";

			// codtarea
			$this->codtarea->LinkCustomAttributes = "";
			$this->codtarea->HrefValue = "";
			$this->codtarea->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// respondable
			$this->respondable->LinkCustomAttributes = "";
			$this->respondable->HrefValue = "";
			$this->respondable->TooltipValue = "";

			// fechainicio
			$this->fechainicio->LinkCustomAttributes = "";
			$this->fechainicio->HrefValue = "";
			$this->fechainicio->TooltipValue = "";

			// fechafinal
			$this->fechafinal->LinkCustomAttributes = "";
			$this->fechafinal->HrefValue = "";
			$this->fechafinal->TooltipValue = "";

			// prioridad
			$this->prioridad->LinkCustomAttributes = "";
			$this->prioridad->HrefValue = "";
			$this->prioridad->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['codtarea'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_TAREASlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_TAREAS_delete)) $SIS_TAREAS_delete = new cSIS_TAREAS_delete();

// Page init
$SIS_TAREAS_delete->Page_Init();

// Page main
$SIS_TAREAS_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_TAREAS_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fSIS_TAREASdelete = new ew_Form("fSIS_TAREASdelete", "delete");

// Form_CustomValidate event
fSIS_TAREASdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_TAREASdelete.ValidateRequired = true;
<?php } else { ?>
fSIS_TAREASdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_TAREASdelete.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_TAREASdelete.Lists["x_estado"].Options = <?php echo json_encode($SIS_TAREAS->estado->Options()) ?>;
fSIS_TAREASdelete.Lists["x_respondable"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_TAREASdelete.Lists["x_respondable"].Options = <?php echo json_encode($SIS_TAREAS->respondable->Options()) ?>;
fSIS_TAREASdelete.Lists["x_prioridad"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_TAREASdelete.Lists["x_prioridad"].Options = <?php echo json_encode($SIS_TAREAS->prioridad->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($SIS_TAREAS_delete->Recordset = $SIS_TAREAS_delete->LoadRecordset())
	$SIS_TAREAS_deleteTotalRecs = $SIS_TAREAS_delete->Recordset->RecordCount(); // Get record count
if ($SIS_TAREAS_deleteTotalRecs <= 0) { // No record found, exit
	if ($SIS_TAREAS_delete->Recordset)
		$SIS_TAREAS_delete->Recordset->Close();
	$SIS_TAREAS_delete->Page_Terminate("SIS_TAREASlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_TAREAS_delete->ShowPageHeader(); ?>
<?php
$SIS_TAREAS_delete->ShowMessage();
?>
<form name="fSIS_TAREASdelete" id="fSIS_TAREASdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_TAREAS_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_TAREAS_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_TAREAS">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($SIS_TAREAS_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $SIS_TAREAS->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($SIS_TAREAS->codtarea->Visible) { // codtarea ?>
		<th><span id="elh_SIS_TAREAS_codtarea" class="SIS_TAREAS_codtarea"><?php echo $SIS_TAREAS->codtarea->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->nombre->Visible) { // nombre ?>
		<th><span id="elh_SIS_TAREAS_nombre" class="SIS_TAREAS_nombre"><?php echo $SIS_TAREAS->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->estado->Visible) { // estado ?>
		<th><span id="elh_SIS_TAREAS_estado" class="SIS_TAREAS_estado"><?php echo $SIS_TAREAS->estado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->descripcion->Visible) { // descripcion ?>
		<th><span id="elh_SIS_TAREAS_descripcion" class="SIS_TAREAS_descripcion"><?php echo $SIS_TAREAS->descripcion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->respondable->Visible) { // respondable ?>
		<th><span id="elh_SIS_TAREAS_respondable" class="SIS_TAREAS_respondable"><?php echo $SIS_TAREAS->respondable->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->fechainicio->Visible) { // fechainicio ?>
		<th><span id="elh_SIS_TAREAS_fechainicio" class="SIS_TAREAS_fechainicio"><?php echo $SIS_TAREAS->fechainicio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->fechafinal->Visible) { // fechafinal ?>
		<th><span id="elh_SIS_TAREAS_fechafinal" class="SIS_TAREAS_fechafinal"><?php echo $SIS_TAREAS->fechafinal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_TAREAS->prioridad->Visible) { // prioridad ?>
		<th><span id="elh_SIS_TAREAS_prioridad" class="SIS_TAREAS_prioridad"><?php echo $SIS_TAREAS->prioridad->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$SIS_TAREAS_delete->RecCnt = 0;
$i = 0;
while (!$SIS_TAREAS_delete->Recordset->EOF) {
	$SIS_TAREAS_delete->RecCnt++;
	$SIS_TAREAS_delete->RowCnt++;

	// Set row properties
	$SIS_TAREAS->ResetAttrs();
	$SIS_TAREAS->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$SIS_TAREAS_delete->LoadRowValues($SIS_TAREAS_delete->Recordset);

	// Render row
	$SIS_TAREAS_delete->RenderRow();
?>
	<tr<?php echo $SIS_TAREAS->RowAttributes() ?>>
<?php if ($SIS_TAREAS->codtarea->Visible) { // codtarea ?>
		<td<?php echo $SIS_TAREAS->codtarea->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_codtarea" class="SIS_TAREAS_codtarea">
<span<?php echo $SIS_TAREAS->codtarea->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->codtarea->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->nombre->Visible) { // nombre ?>
		<td<?php echo $SIS_TAREAS->nombre->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_nombre" class="SIS_TAREAS_nombre">
<span<?php echo $SIS_TAREAS->nombre->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->estado->Visible) { // estado ?>
		<td<?php echo $SIS_TAREAS->estado->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_estado" class="SIS_TAREAS_estado">
<span<?php echo $SIS_TAREAS->estado->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->descripcion->Visible) { // descripcion ?>
		<td<?php echo $SIS_TAREAS->descripcion->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_descripcion" class="SIS_TAREAS_descripcion">
<span<?php echo $SIS_TAREAS->descripcion->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->descripcion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->respondable->Visible) { // respondable ?>
		<td<?php echo $SIS_TAREAS->respondable->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_respondable" class="SIS_TAREAS_respondable">
<span<?php echo $SIS_TAREAS->respondable->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->respondable->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->fechainicio->Visible) { // fechainicio ?>
		<td<?php echo $SIS_TAREAS->fechainicio->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_fechainicio" class="SIS_TAREAS_fechainicio">
<span<?php echo $SIS_TAREAS->fechainicio->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->fechainicio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->fechafinal->Visible) { // fechafinal ?>
		<td<?php echo $SIS_TAREAS->fechafinal->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_fechafinal" class="SIS_TAREAS_fechafinal">
<span<?php echo $SIS_TAREAS->fechafinal->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->fechafinal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_TAREAS->prioridad->Visible) { // prioridad ?>
		<td<?php echo $SIS_TAREAS->prioridad->CellAttributes() ?>>
<span id="el<?php echo $SIS_TAREAS_delete->RowCnt ?>_SIS_TAREAS_prioridad" class="SIS_TAREAS_prioridad">
<span<?php echo $SIS_TAREAS->prioridad->ViewAttributes() ?>>
<?php echo $SIS_TAREAS->prioridad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$SIS_TAREAS_delete->Recordset->MoveNext();
}
$SIS_TAREAS_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_TAREAS_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fSIS_TAREASdelete.Init();
</script>
<?php
$SIS_TAREAS_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_TAREAS_delete->Page_Terminate();
?>
