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

$SIS_ARTICULO_delete = NULL; // Initialize page object first

class cSIS_ARTICULO_delete extends cSIS_ARTICULO {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_ARTICULO';

	// Page object name
	var $PageObjName = 'SIS_ARTICULO_delete';

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

		// Table object (SIS_ARTICULO)
		if (!isset($GLOBALS["SIS_ARTICULO"]) || get_class($GLOBALS["SIS_ARTICULO"]) == "cSIS_ARTICULO") {
			$GLOBALS["SIS_ARTICULO"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SIS_ARTICULO"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("SIS_ARTICULOlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in SIS_ARTICULO class, SIS_ARTICULOinfo.php

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
				$sThisKey .= $row['codarticulo'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_ARTICULOlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($SIS_ARTICULO_delete)) $SIS_ARTICULO_delete = new cSIS_ARTICULO_delete();

// Page init
$SIS_ARTICULO_delete->Page_Init();

// Page main
$SIS_ARTICULO_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_ARTICULO_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fSIS_ARTICULOdelete = new ew_Form("fSIS_ARTICULOdelete", "delete");

// Form_CustomValidate event
fSIS_ARTICULOdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ARTICULOdelete.ValidateRequired = true;
<?php } else { ?>
fSIS_ARTICULOdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_ARTICULOdelete.Lists["x_codlinea"] = {"LinkField":"x_cod_linea","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOdelete.Lists["x_calificacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOdelete.Lists["x_calificacion"].Options = <?php echo json_encode($SIS_ARTICULO->calificacion->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($SIS_ARTICULO_delete->Recordset = $SIS_ARTICULO_delete->LoadRecordset())
	$SIS_ARTICULO_deleteTotalRecs = $SIS_ARTICULO_delete->Recordset->RecordCount(); // Get record count
if ($SIS_ARTICULO_deleteTotalRecs <= 0) { // No record found, exit
	if ($SIS_ARTICULO_delete->Recordset)
		$SIS_ARTICULO_delete->Recordset->Close();
	$SIS_ARTICULO_delete->Page_Terminate("SIS_ARTICULOlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_ARTICULO_delete->ShowPageHeader(); ?>
<?php
$SIS_ARTICULO_delete->ShowMessage();
?>
<form name="fSIS_ARTICULOdelete" id="fSIS_ARTICULOdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_ARTICULO_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_ARTICULO_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_ARTICULO">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($SIS_ARTICULO_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $SIS_ARTICULO->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
		<th><span id="elh_SIS_ARTICULO_codarticulo" class="SIS_ARTICULO_codarticulo"><?php echo $SIS_ARTICULO->codarticulo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->descripcion->Visible) { // descripcion ?>
		<th><span id="elh_SIS_ARTICULO_descripcion" class="SIS_ARTICULO_descripcion"><?php echo $SIS_ARTICULO->descripcion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->precio->Visible) { // precio ?>
		<th><span id="elh_SIS_ARTICULO_precio" class="SIS_ARTICULO_precio"><?php echo $SIS_ARTICULO->precio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->caracteristicas->Visible) { // caracteristicas ?>
		<th><span id="elh_SIS_ARTICULO_caracteristicas" class="SIS_ARTICULO_caracteristicas"><?php echo $SIS_ARTICULO->caracteristicas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->garantia->Visible) { // garantia ?>
		<th><span id="elh_SIS_ARTICULO_garantia" class="SIS_ARTICULO_garantia"><?php echo $SIS_ARTICULO->garantia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->codlinea->Visible) { // codlinea ?>
		<th><span id="elh_SIS_ARTICULO_codlinea" class="SIS_ARTICULO_codlinea"><?php echo $SIS_ARTICULO->codlinea->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->calificacion->Visible) { // calificacion ?>
		<th><span id="elh_SIS_ARTICULO_calificacion" class="SIS_ARTICULO_calificacion"><?php echo $SIS_ARTICULO->calificacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->fecultimamod->Visible) { // fecultimamod ?>
		<th><span id="elh_SIS_ARTICULO_fecultimamod" class="SIS_ARTICULO_fecultimamod"><?php echo $SIS_ARTICULO->fecultimamod->FldCaption() ?></span></th>
<?php } ?>
<?php if ($SIS_ARTICULO->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<th><span id="elh_SIS_ARTICULO_usuarioultimamod" class="SIS_ARTICULO_usuarioultimamod"><?php echo $SIS_ARTICULO->usuarioultimamod->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$SIS_ARTICULO_delete->RecCnt = 0;
$i = 0;
while (!$SIS_ARTICULO_delete->Recordset->EOF) {
	$SIS_ARTICULO_delete->RecCnt++;
	$SIS_ARTICULO_delete->RowCnt++;

	// Set row properties
	$SIS_ARTICULO->ResetAttrs();
	$SIS_ARTICULO->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$SIS_ARTICULO_delete->LoadRowValues($SIS_ARTICULO_delete->Recordset);

	// Render row
	$SIS_ARTICULO_delete->RenderRow();
?>
	<tr<?php echo $SIS_ARTICULO->RowAttributes() ?>>
<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
		<td<?php echo $SIS_ARTICULO->codarticulo->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_codarticulo" class="SIS_ARTICULO_codarticulo">
<span<?php echo $SIS_ARTICULO->codarticulo->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->codarticulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->descripcion->Visible) { // descripcion ?>
		<td<?php echo $SIS_ARTICULO->descripcion->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_descripcion" class="SIS_ARTICULO_descripcion">
<span<?php echo $SIS_ARTICULO->descripcion->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->descripcion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->precio->Visible) { // precio ?>
		<td<?php echo $SIS_ARTICULO->precio->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_precio" class="SIS_ARTICULO_precio">
<span<?php echo $SIS_ARTICULO->precio->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->precio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->caracteristicas->Visible) { // caracteristicas ?>
		<td<?php echo $SIS_ARTICULO->caracteristicas->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_caracteristicas" class="SIS_ARTICULO_caracteristicas">
<span<?php echo $SIS_ARTICULO->caracteristicas->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->caracteristicas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->garantia->Visible) { // garantia ?>
		<td<?php echo $SIS_ARTICULO->garantia->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_garantia" class="SIS_ARTICULO_garantia">
<span<?php echo $SIS_ARTICULO->garantia->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->garantia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->codlinea->Visible) { // codlinea ?>
		<td<?php echo $SIS_ARTICULO->codlinea->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_codlinea" class="SIS_ARTICULO_codlinea">
<span<?php echo $SIS_ARTICULO->codlinea->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->codlinea->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->calificacion->Visible) { // calificacion ?>
		<td<?php echo $SIS_ARTICULO->calificacion->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_calificacion" class="SIS_ARTICULO_calificacion">
<span<?php echo $SIS_ARTICULO->calificacion->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->calificacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->fecultimamod->Visible) { // fecultimamod ?>
		<td<?php echo $SIS_ARTICULO->fecultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_fecultimamod" class="SIS_ARTICULO_fecultimamod">
<span<?php echo $SIS_ARTICULO->fecultimamod->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->fecultimamod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($SIS_ARTICULO->usuarioultimamod->Visible) { // usuarioultimamod ?>
		<td<?php echo $SIS_ARTICULO->usuarioultimamod->CellAttributes() ?>>
<span id="el<?php echo $SIS_ARTICULO_delete->RowCnt ?>_SIS_ARTICULO_usuarioultimamod" class="SIS_ARTICULO_usuarioultimamod">
<span<?php echo $SIS_ARTICULO->usuarioultimamod->ViewAttributes() ?>>
<?php echo $SIS_ARTICULO->usuarioultimamod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$SIS_ARTICULO_delete->Recordset->MoveNext();
}
$SIS_ARTICULO_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_ARTICULO_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fSIS_ARTICULOdelete.Init();
</script>
<?php
$SIS_ARTICULO_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_ARTICULO_delete->Page_Terminate();
?>
