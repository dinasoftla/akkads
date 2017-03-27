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

$SIS_COMPANIA_delete = NULL; // Initialize page object first

class cSIS_COMPANIA_delete extends cSIS_COMPANIA {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_COMPANIA';

	// Page object name
	var $PageObjName = 'SIS_COMPANIA_delete';

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

		// Table object (SIS_COMPANIA)
		if (!isset($GLOBALS["SIS_COMPANIA"]) || get_class($GLOBALS["SIS_COMPANIA"]) == "cSIS_COMPANIA") {
			$GLOBALS["SIS_COMPANIA"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_COMPANIA"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("SIS_COMPANIAlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in SIS_COMPANIA class, SIS_COMPANIAinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
				$sThisKey .= $row['codcompania'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_COMPANIAlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_COMPANIA_delete)) $SIS_COMPANIA_delete = new cSIS_COMPANIA_delete();

// Page init
$SIS_COMPANIA_delete->Page_Init();

// Page main
$SIS_COMPANIA_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_COMPANIA_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fSIS_COMPANIAdelete = new ew_Form("fSIS_COMPANIAdelete", "delete");

// Form_CustomValidate event
fSIS_COMPANIAdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_COMPANIAdelete.ValidateRequired = true;
<?php } else { ?>
fSIS_COMPANIAdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_COMPANIAdelete.Lists["x_trasnporteenfactura"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_COMPANIAdelete.Lists["x_trasnporteenfactura"].Options = <?php echo json_encode($SIS_COMPANIA->trasnporteenfactura->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($SIS_COMPANIA_delete->Recordset = $SIS_COMPANIA_delete->LoadRecordset())
	$SIS_COMPANIA_deleteTotalRecs = $SIS_COMPANIA_delete->Recordset->RecordCount(); // Get record count
if ($SIS_COMPANIA_deleteTotalRecs <= 0) { // No record found, exit
	if ($SIS_COMPANIA_delete->Recordset)
		$SIS_COMPANIA_delete->Recordset->Close();
	$SIS_COMPANIA_delete->Page_Terminate("SIS_COMPANIAlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_COMPANIA_delete->ShowPageHeader(); ?>
<?php
$SIS_COMPANIA_delete->ShowMessage();
?>
<form name="fSIS_COMPANIAdelete" id="fSIS_COMPANIAdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_COMPANIA_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_COMPANIA_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_COMPANIA">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($SIS_COMPANIA_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $SIS_COMPANIA->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($SIS_COMPANIA->codcompania->Visible) { // codcompania ?>
		<th><span id="elh_SIS_COMPANIA_codcompania" class="SIS_COMPANIA_codcompania"><?php echo $SIS_COMPANIA->codcompania->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->nombre->Visible) { // nombre ?>
		<th><span id="elh_SIS_COMPANIA_nombre" class="SIS_COMPANIA_nombre"><?php echo $SIS_COMPANIA->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->telefono->Visible) { // telefono ?>
		<th><span id="elh_SIS_COMPANIA_telefono" class="SIS_COMPANIA_telefono"><?php echo $SIS_COMPANIA->telefono->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->direccion->Visible) { // direccion ?>
		<th><span id="elh_SIS_COMPANIA_direccion" class="SIS_COMPANIA_direccion"><?php echo $SIS_COMPANIA->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->descuento->Visible) { // descuento ?>
		<th><span id="elh_SIS_COMPANIA_descuento" class="SIS_COMPANIA_descuento"><?php echo $SIS_COMPANIA->descuento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->piefactura->Visible) { // piefactura ?>
		<th><span id="elh_SIS_COMPANIA_piefactura" class="SIS_COMPANIA_piefactura"><?php echo $SIS_COMPANIA->piefactura->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->leyendafactura->Visible) { // leyendafactura ?>
		<th><span id="elh_SIS_COMPANIA_leyendafactura" class="SIS_COMPANIA_leyendafactura"><?php echo $SIS_COMPANIA->leyendafactura->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_COMPANIA->trasnporteenfactura->Visible) { // trasnporteenfactura ?>
		<th><span id="elh_SIS_COMPANIA_trasnporteenfactura" class="SIS_COMPANIA_trasnporteenfactura"><?php echo $SIS_COMPANIA->trasnporteenfactura->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$SIS_COMPANIA_delete->RecCnt = 0;
$i = 0;
while (!$SIS_COMPANIA_delete->Recordset->EOF) {
	$SIS_COMPANIA_delete->RecCnt++;
	$SIS_COMPANIA_delete->RowCnt++;

	// Set row properties
	$SIS_COMPANIA->ResetAttrs();
	$SIS_COMPANIA->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$SIS_COMPANIA_delete->LoadRowValues($SIS_COMPANIA_delete->Recordset);

	// Render row
	$SIS_COMPANIA_delete->RenderRow();
?>
	<tr<?php echo $SIS_COMPANIA->RowAttributes() ?>>
<?php if ($SIS_COMPANIA->codcompania->Visible) { // codcompania ?>
		<td<?php echo $SIS_COMPANIA->codcompania->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_codcompania" class="SIS_COMPANIA_codcompania">
<span<?php echo $SIS_COMPANIA->codcompania->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->codcompania->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->nombre->Visible) { // nombre ?>
		<td<?php echo $SIS_COMPANIA->nombre->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_nombre" class="SIS_COMPANIA_nombre">
<span<?php echo $SIS_COMPANIA->nombre->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->telefono->Visible) { // telefono ?>
		<td<?php echo $SIS_COMPANIA->telefono->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_telefono" class="SIS_COMPANIA_telefono">
<span<?php echo $SIS_COMPANIA->telefono->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->direccion->Visible) { // direccion ?>
		<td<?php echo $SIS_COMPANIA->direccion->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_direccion" class="SIS_COMPANIA_direccion">
<span<?php echo $SIS_COMPANIA->direccion->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->descuento->Visible) { // descuento ?>
		<td<?php echo $SIS_COMPANIA->descuento->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_descuento" class="SIS_COMPANIA_descuento">
<span<?php echo $SIS_COMPANIA->descuento->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->descuento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->piefactura->Visible) { // piefactura ?>
		<td<?php echo $SIS_COMPANIA->piefactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_piefactura" class="SIS_COMPANIA_piefactura">
<span<?php echo $SIS_COMPANIA->piefactura->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->piefactura->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->leyendafactura->Visible) { // leyendafactura ?>
		<td<?php echo $SIS_COMPANIA->leyendafactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_leyendafactura" class="SIS_COMPANIA_leyendafactura">
<span<?php echo $SIS_COMPANIA->leyendafactura->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->leyendafactura->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_COMPANIA->trasnporteenfactura->Visible) { // trasnporteenfactura ?>
		<td<?php echo $SIS_COMPANIA->trasnporteenfactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_COMPANIA_delete->RowCnt ?>_SIS_COMPANIA_trasnporteenfactura" class="SIS_COMPANIA_trasnporteenfactura">
<span<?php echo $SIS_COMPANIA->trasnporteenfactura->ViewAttributes() ?>>
<?php echo $SIS_COMPANIA->trasnporteenfactura->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$SIS_COMPANIA_delete->Recordset->MoveNext();
}
$SIS_COMPANIA_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_COMPANIA_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fSIS_COMPANIAdelete.Init();
</script>
<?php
$SIS_COMPANIA_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_COMPANIA_delete->Page_Terminate();
?>
