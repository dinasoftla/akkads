<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_PEDIDOinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_PEDIDO_delete = NULL; // Initialize page object first

class cSIS_PEDIDO_delete extends cSIS_PEDIDO {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_PEDIDO';

	// Page object name
	var $PageObjName = 'SIS_PEDIDO_delete';

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

		// Table object (SIS_PEDIDO)
		if (!isset($GLOBALS["SIS_PEDIDO"]) || get_class($GLOBALS["SIS_PEDIDO"]) == "cSIS_PEDIDO") {
			$GLOBALS["SIS_PEDIDO"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_PEDIDO"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_PEDIDO', TRUE);

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
		$this->numpedido->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $SIS_PEDIDO;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($SIS_PEDIDO);
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
			$this->Page_Terminate("SIS_PEDIDOlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in SIS_PEDIDO class, SIS_PEDIDOinfo.php

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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		if (array_key_exists('EV__codcliente', $rs->fields)) {
			$this->codcliente->VirtualValue = $rs->fields('EV__codcliente'); // Set up virtual field value
		} else {
			$this->codcliente->VirtualValue = ""; // Clear value
		}
		$this->fechaultimamod->setDbValue($rs->fields('fechaultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->transporte->setDbValue($rs->fields('transporte'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->numpedido->DbValue = $row['numpedido'];
		$this->codcliente->DbValue = $row['codcliente'];
		$this->fechaultimamod->DbValue = $row['fechaultimamod'];
		$this->usuarioultimamod->DbValue = $row['usuarioultimamod'];
		$this->fecha->DbValue = $row['fecha'];
		$this->descuento->DbValue = $row['descuento'];
		$this->estado->DbValue = $row['estado'];
		$this->transporte->DbValue = $row['transporte'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->transporte->FormValue == $this->transporte->CurrentValue && is_numeric(ew_StrToFloat($this->transporte->CurrentValue)))
			$this->transporte->CurrentValue = ew_StrToFloat($this->transporte->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// numpedido
		// codcliente
		// fechaultimamod
		// usuarioultimamod
		// fecha
		// descuento
		// estado
		// transporte

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// numpedido
		$this->numpedido->ViewValue = $this->numpedido->CurrentValue;
		$this->numpedido->ViewCustomAttributes = "";

		// codcliente
		if ($this->codcliente->VirtualValue <> "") {
			$this->codcliente->ViewValue = $this->codcliente->VirtualValue;
		} else {
		if (strval($this->codcliente->CurrentValue) <> "") {
			$sFilterWrk = "[codcliente]" . ew_SearchString("=", $this->codcliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codcliente], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_CLIENTE]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codcliente, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nombre] ASC";
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
		}
		$this->codcliente->ViewCustomAttributes = "";

		// fechaultimamod
		$this->fechaultimamod->ViewValue = $this->fechaultimamod->CurrentValue;
		$this->fechaultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewCustomAttributes = "";

		// descuento
		if (strval($this->descuento->CurrentValue) <> "") {
			$this->descuento->ViewValue = $this->descuento->OptionCaption($this->descuento->CurrentValue);
		} else {
			$this->descuento->ViewValue = NULL;
		}
		$this->descuento->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// transporte
		$this->transporte->ViewValue = $this->transporte->CurrentValue;
		$this->transporte->ViewCustomAttributes = "";

			// numpedido
			$this->numpedido->LinkCustomAttributes = "";
			$this->numpedido->HrefValue = "";
			$this->numpedido->TooltipValue = "";

			// codcliente
			$this->codcliente->LinkCustomAttributes = "";
			$this->codcliente->HrefValue = "";
			$this->codcliente->TooltipValue = "";

			// fechaultimamod
			$this->fechaultimamod->LinkCustomAttributes = "";
			$this->fechaultimamod->HrefValue = "";
			$this->fechaultimamod->TooltipValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
			$this->usuarioultimamod->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// descuento
			$this->descuento->LinkCustomAttributes = "";
			$this->descuento->HrefValue = "";
			$this->descuento->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// transporte
			$this->transporte->LinkCustomAttributes = "";
			$this->transporte->HrefValue = "";
			$this->transporte->TooltipValue = "";
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
				$sThisKey .= $row['numpedido'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_PEDIDOlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_PEDIDO_delete)) $SIS_PEDIDO_delete = new cSIS_PEDIDO_delete();

// Page init
$SIS_PEDIDO_delete->Page_Init();

// Page main
$SIS_PEDIDO_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_PEDIDO_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fSIS_PEDIDOdelete = new ew_Form("fSIS_PEDIDOdelete", "delete");

// Form_CustomValidate event
fSIS_PEDIDOdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_PEDIDOdelete.ValidateRequired = true;
<?php } else { ?>
fSIS_PEDIDOdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_PEDIDOdelete.Lists["x_codcliente"] = {"LinkField":"x_codcliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_PEDIDOdelete.Lists["x_descuento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_PEDIDOdelete.Lists["x_descuento"].Options = <?php echo json_encode($SIS_PEDIDO->descuento->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($SIS_PEDIDO_delete->Recordset = $SIS_PEDIDO_delete->LoadRecordset())
	$SIS_PEDIDO_deleteTotalRecs = $SIS_PEDIDO_delete->Recordset->RecordCount(); // Get record count
if ($SIS_PEDIDO_deleteTotalRecs <= 0) { // No record found, exit
	if ($SIS_PEDIDO_delete->Recordset)
		$SIS_PEDIDO_delete->Recordset->Close();
	$SIS_PEDIDO_delete->Page_Terminate("SIS_PEDIDOlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_PEDIDO_delete->ShowPageHeader(); ?>
<?php
$SIS_PEDIDO_delete->ShowMessage();
?>
<form name="fSIS_PEDIDOdelete" id="fSIS_PEDIDOdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_PEDIDO_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_PEDIDO_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_PEDIDO">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($SIS_PEDIDO_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $SIS_PEDIDO->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($SIS_PEDIDO->numpedido->Visible) { // numpedido ?>
		<th><span id="elh_SIS_PEDIDO_numpedido" class="SIS_PEDIDO_numpedido"><?php echo $SIS_PEDIDO->numpedido->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->codcliente->Visible) { // codcliente ?>
		<th><span id="elh_SIS_PEDIDO_codcliente" class="SIS_PEDIDO_codcliente"><?php echo $SIS_PEDIDO->codcliente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->fechaultimamod->Visible) { // fechaultimamod ?>
		<th><span id="elh_SIS_PEDIDO_fechaultimamod" class="SIS_PEDIDO_fechaultimamod"><?php echo $SIS_PEDIDO->fechaultimamod->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<th><span id="elh_SIS_PEDIDO_usuarioultimamod" class="SIS_PEDIDO_usuarioultimamod"><?php echo $SIS_PEDIDO->usuarioultimamod->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->fecha->Visible) { // fecha ?>
		<th><span id="elh_SIS_PEDIDO_fecha" class="SIS_PEDIDO_fecha"><?php echo $SIS_PEDIDO->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->descuento->Visible) { // descuento ?>
		<th><span id="elh_SIS_PEDIDO_descuento" class="SIS_PEDIDO_descuento"><?php echo $SIS_PEDIDO->descuento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->estado->Visible) { // estado ?>
		<th><span id="elh_SIS_PEDIDO_estado" class="SIS_PEDIDO_estado"><?php echo $SIS_PEDIDO->estado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_PEDIDO->transporte->Visible) { // transporte ?>
		<th><span id="elh_SIS_PEDIDO_transporte" class="SIS_PEDIDO_transporte"><?php echo $SIS_PEDIDO->transporte->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$SIS_PEDIDO_delete->RecCnt = 0;
$i = 0;
while (!$SIS_PEDIDO_delete->Recordset->EOF) {
	$SIS_PEDIDO_delete->RecCnt++;
	$SIS_PEDIDO_delete->RowCnt++;

	// Set row properties
	$SIS_PEDIDO->ResetAttrs();
	$SIS_PEDIDO->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$SIS_PEDIDO_delete->LoadRowValues($SIS_PEDIDO_delete->Recordset);

	// Render row
	$SIS_PEDIDO_delete->RenderRow();
?>
	<tr<?php echo $SIS_PEDIDO->RowAttributes() ?>>
<?php if ($SIS_PEDIDO->numpedido->Visible) { // numpedido ?>
		<td<?php echo $SIS_PEDIDO->numpedido->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_numpedido" class="SIS_PEDIDO_numpedido">
<span<?php echo $SIS_PEDIDO->numpedido->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->numpedido->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->codcliente->Visible) { // codcliente ?>
		<td<?php echo $SIS_PEDIDO->codcliente->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_codcliente" class="SIS_PEDIDO_codcliente">
<span<?php echo $SIS_PEDIDO->codcliente->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->codcliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->fechaultimamod->Visible) { // fechaultimamod ?>
		<td<?php echo $SIS_PEDIDO->fechaultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_fechaultimamod" class="SIS_PEDIDO_fechaultimamod">
<span<?php echo $SIS_PEDIDO->fechaultimamod->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->fechaultimamod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<td<?php echo $SIS_PEDIDO->usuarioultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_usuarioultimamod" class="SIS_PEDIDO_usuarioultimamod">
<span<?php echo $SIS_PEDIDO->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->fecha->Visible) { // fecha ?>
		<td<?php echo $SIS_PEDIDO->fecha->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_fecha" class="SIS_PEDIDO_fecha">
<span<?php echo $SIS_PEDIDO->fecha->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->descuento->Visible) { // descuento ?>
		<td<?php echo $SIS_PEDIDO->descuento->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_descuento" class="SIS_PEDIDO_descuento">
<span<?php echo $SIS_PEDIDO->descuento->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->descuento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->estado->Visible) { // estado ?>
		<td<?php echo $SIS_PEDIDO->estado->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_estado" class="SIS_PEDIDO_estado">
<span<?php echo $SIS_PEDIDO->estado->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_PEDIDO->transporte->Visible) { // transporte ?>
		<td<?php echo $SIS_PEDIDO->transporte->CellAttributes() ?>>
<span id="el<?php echo $SIS_PEDIDO_delete->RowCnt ?>_SIS_PEDIDO_transporte" class="SIS_PEDIDO_transporte">
<span<?php echo $SIS_PEDIDO->transporte->ViewAttributes() ?>>
<?php echo $SIS_PEDIDO->transporte->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$SIS_PEDIDO_delete->Recordset->MoveNext();
}
$SIS_PEDIDO_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_PEDIDO_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fSIS_PEDIDOdelete.Init();
</script>
<?php
$SIS_PEDIDO_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_PEDIDO_delete->Page_Terminate();
?>
