<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_FACTURAinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_FACTURA_delete = NULL; // Initialize page object first

class cSIS_FACTURA_delete extends cSIS_FACTURA {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_FACTURA';

	// Page object name
	var $PageObjName = 'SIS_FACTURA_delete';

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

		// Table object (SIS_FACTURA)
		if (!isset($GLOBALS["SIS_FACTURA"]) || get_class($GLOBALS["SIS_FACTURA"]) == "cSIS_FACTURA") {
			$GLOBALS["SIS_FACTURA"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_FACTURA"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		global $EW_EXPORT, $SIS_FACTURA;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
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
			$this->Page_Terminate("SIS_FACTURAlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in SIS_FACTURA class, SIS_FACTURAinfo.php

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
		$this->numfactura->setDbValue($rs->fields('numfactura'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->codcliente->setDbValue($rs->fields('codcliente'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->transporte->setDbValue($rs->fields('transporte'));
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
				$sThisKey .= $row['numfactura'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_FACTURAlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_FACTURA_delete)) $SIS_FACTURA_delete = new cSIS_FACTURA_delete();

// Page init
$SIS_FACTURA_delete->Page_Init();

// Page main
$SIS_FACTURA_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_FACTURA_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fSIS_FACTURAdelete = new ew_Form("fSIS_FACTURAdelete", "delete");

// Form_CustomValidate event
fSIS_FACTURAdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_FACTURAdelete.ValidateRequired = true;
<?php } else { ?>
fSIS_FACTURAdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_FACTURAdelete.Lists["x_codcliente"] = {"LinkField":"x_codcliente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAdelete.Lists["x_descuento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAdelete.Lists["x_descuento"].Options = <?php echo json_encode($SIS_FACTURA->descuento->Options()) ?>;
fSIS_FACTURAdelete.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_FACTURAdelete.Lists["x_estado"].Options = <?php echo json_encode($SIS_FACTURA->estado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($SIS_FACTURA_delete->Recordset = $SIS_FACTURA_delete->LoadRecordset())
	$SIS_FACTURA_deleteTotalRecs = $SIS_FACTURA_delete->Recordset->RecordCount(); // Get record count
if ($SIS_FACTURA_deleteTotalRecs <= 0) { // No record found, exit
	if ($SIS_FACTURA_delete->Recordset)
		$SIS_FACTURA_delete->Recordset->Close();
	$SIS_FACTURA_delete->Page_Terminate("SIS_FACTURAlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_FACTURA_delete->ShowPageHeader(); ?>
<?php
$SIS_FACTURA_delete->ShowMessage();
?>
<form name="fSIS_FACTURAdelete" id="fSIS_FACTURAdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_FACTURA_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_FACTURA_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_FACTURA">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($SIS_FACTURA_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $SIS_FACTURA->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($SIS_FACTURA->numfactura->Visible) { // numfactura ?>
		<th><span id="elh_SIS_FACTURA_numfactura" class="SIS_FACTURA_numfactura"><?php echo $SIS_FACTURA->numfactura->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->fecha->Visible) { // fecha ?>
		<th><span id="elh_SIS_FACTURA_fecha" class="SIS_FACTURA_fecha"><?php echo $SIS_FACTURA->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->codcliente->Visible) { // codcliente ?>
		<th><span id="elh_SIS_FACTURA_codcliente" class="SIS_FACTURA_codcliente"><?php echo $SIS_FACTURA->codcliente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->fecultimamod->Visible) { // fecultimamod ?>
		<th><span id="elh_SIS_FACTURA_fecultimamod" class="SIS_FACTURA_fecultimamod"><?php echo $SIS_FACTURA->fecultimamod->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<th><span id="elh_SIS_FACTURA_usuarioultimamod" class="SIS_FACTURA_usuarioultimamod"><?php echo $SIS_FACTURA->usuarioultimamod->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->descuento->Visible) { // descuento ?>
		<th><span id="elh_SIS_FACTURA_descuento" class="SIS_FACTURA_descuento"><?php echo $SIS_FACTURA->descuento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->estado->Visible) { // estado ?>
		<th><span id="elh_SIS_FACTURA_estado" class="SIS_FACTURA_estado"><?php echo $SIS_FACTURA->estado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->numpedido->Visible) { // numpedido ?>
		<th><span id="elh_SIS_FACTURA_numpedido" class="SIS_FACTURA_numpedido"><?php echo $SIS_FACTURA->numpedido->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_FACTURA->transporte->Visible) { // transporte ?>
		<th><span id="elh_SIS_FACTURA_transporte" class="SIS_FACTURA_transporte"><?php echo $SIS_FACTURA->transporte->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$SIS_FACTURA_delete->RecCnt = 0;
$i = 0;
while (!$SIS_FACTURA_delete->Recordset->EOF) {
	$SIS_FACTURA_delete->RecCnt++;
	$SIS_FACTURA_delete->RowCnt++;

	// Set row properties
	$SIS_FACTURA->ResetAttrs();
	$SIS_FACTURA->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$SIS_FACTURA_delete->LoadRowValues($SIS_FACTURA_delete->Recordset);

	// Render row
	$SIS_FACTURA_delete->RenderRow();
?>
	<tr<?php echo $SIS_FACTURA->RowAttributes() ?>>
<?php if ($SIS_FACTURA->numfactura->Visible) { // numfactura ?>
		<td<?php echo $SIS_FACTURA->numfactura->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_numfactura" class="SIS_FACTURA_numfactura">
<span<?php echo $SIS_FACTURA->numfactura->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->numfactura->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->fecha->Visible) { // fecha ?>
		<td<?php echo $SIS_FACTURA->fecha->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_fecha" class="SIS_FACTURA_fecha">
<span<?php echo $SIS_FACTURA->fecha->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->codcliente->Visible) { // codcliente ?>
		<td<?php echo $SIS_FACTURA->codcliente->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_codcliente" class="SIS_FACTURA_codcliente">
<span<?php echo $SIS_FACTURA->codcliente->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->codcliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->fecultimamod->Visible) { // fecultimamod ?>
		<td<?php echo $SIS_FACTURA->fecultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_fecultimamod" class="SIS_FACTURA_fecultimamod">
<span<?php echo $SIS_FACTURA->fecultimamod->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->fecultimamod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<td<?php echo $SIS_FACTURA->usuarioultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_usuarioultimamod" class="SIS_FACTURA_usuarioultimamod">
<span<?php echo $SIS_FACTURA->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->descuento->Visible) { // descuento ?>
		<td<?php echo $SIS_FACTURA->descuento->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_descuento" class="SIS_FACTURA_descuento">
<span<?php echo $SIS_FACTURA->descuento->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->descuento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->estado->Visible) { // estado ?>
		<td<?php echo $SIS_FACTURA->estado->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_estado" class="SIS_FACTURA_estado">
<span<?php echo $SIS_FACTURA->estado->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->numpedido->Visible) { // numpedido ?>
		<td<?php echo $SIS_FACTURA->numpedido->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_numpedido" class="SIS_FACTURA_numpedido">
<span<?php echo $SIS_FACTURA->numpedido->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->numpedido->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_FACTURA->transporte->Visible) { // transporte ?>
		<td<?php echo $SIS_FACTURA->transporte->CellAttributes() ?>>
<span id="el<?php echo $SIS_FACTURA_delete->RowCnt ?>_SIS_FACTURA_transporte" class="SIS_FACTURA_transporte">
<span<?php echo $SIS_FACTURA->transporte->ViewAttributes() ?>>
<?php echo $SIS_FACTURA->transporte->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$SIS_FACTURA_delete->Recordset->MoveNext();
}
$SIS_FACTURA_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_FACTURA_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fSIS_FACTURAdelete.Init();
</script>
<?php
$SIS_FACTURA_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_FACTURA_delete->Page_Terminate();
?>
