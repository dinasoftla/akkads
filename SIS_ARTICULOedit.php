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

$SIS_ARTICULO_edit = NULL; // Initialize page object first

class cSIS_ARTICULO_edit extends cSIS_ARTICULO {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_ARTICULO';

	// Page object name
	var $PageObjName = 'SIS_ARTICULO_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["codarticulo"] <> "") {
			$this->codarticulo->setQueryStringValue($_GET["codarticulo"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->codarticulo->CurrentValue == "")
			$this->Page_Terminate("SIS_ARTICULOlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("SIS_ARTICULOlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_ARTICULOlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->foto->Upload->Index = $objForm->Index;
		$this->foto->Upload->UploadFile();
		$this->foto->CurrentValue = $this->foto->Upload->FileName;
		$this->foto2->Upload->Index = $objForm->Index;
		$this->foto2->Upload->UploadFile();
		$this->foto2->CurrentValue = $this->foto2->Upload->FileName;
		$this->foto3->Upload->Index = $objForm->Index;
		$this->foto3->Upload->UploadFile();
		$this->foto3->CurrentValue = $this->foto3->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->codarticulo->FldIsDetailKey) {
			$this->codarticulo->setFormValue($objForm->GetValue("x_codarticulo"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->precio->FldIsDetailKey) {
			$this->precio->setFormValue($objForm->GetValue("x_precio"));
		}
		if (!$this->caracteristicas->FldIsDetailKey) {
			$this->caracteristicas->setFormValue($objForm->GetValue("x_caracteristicas"));
		}
		if (!$this->garantia->FldIsDetailKey) {
			$this->garantia->setFormValue($objForm->GetValue("x_garantia"));
		}
		if (!$this->referencia->FldIsDetailKey) {
			$this->referencia->setFormValue($objForm->GetValue("x_referencia"));
		}
		if (!$this->codlinea->FldIsDetailKey) {
			$this->codlinea->setFormValue($objForm->GetValue("x_codlinea"));
		}
		if (!$this->calificacion->FldIsDetailKey) {
			$this->calificacion->setFormValue($objForm->GetValue("x_calificacion"));
		}
		if (!$this->fecultimamod->FldIsDetailKey) {
			$this->fecultimamod->setFormValue($objForm->GetValue("x_fecultimamod"));
		}
		if (!$this->usuarioultimamod->FldIsDetailKey) {
			$this->usuarioultimamod->setFormValue($objForm->GetValue("x_usuarioultimamod"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->codarticulo->CurrentValue = $this->codarticulo->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->precio->CurrentValue = $this->precio->FormValue;
		$this->caracteristicas->CurrentValue = $this->caracteristicas->FormValue;
		$this->garantia->CurrentValue = $this->garantia->FormValue;
		$this->referencia->CurrentValue = $this->referencia->FormValue;
		$this->codlinea->CurrentValue = $this->codlinea->FormValue;
		$this->calificacion->CurrentValue = $this->calificacion->FormValue;
		$this->fecultimamod->CurrentValue = $this->fecultimamod->FormValue;
		$this->usuarioultimamod->CurrentValue = $this->usuarioultimamod->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codarticulo
			$this->codarticulo->EditAttrs["class"] = "form-control";
			$this->codarticulo->EditCustomAttributes = "";
			$this->codarticulo->EditValue = $this->codarticulo->CurrentValue;
			$this->codarticulo->CssStyle = "font-weight: bold;";
			$this->codarticulo->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// precio
			$this->precio->EditAttrs["class"] = "form-control";
			$this->precio->EditCustomAttributes = "";
			$this->precio->EditValue = ew_HtmlEncode($this->precio->CurrentValue);
			$this->precio->PlaceHolder = ew_RemoveHtml($this->precio->FldCaption());
			if (strval($this->precio->EditValue) <> "" && is_numeric($this->precio->EditValue)) $this->precio->EditValue = ew_FormatNumber($this->precio->EditValue, -2, -2, -2, -2);

			// foto
			$this->foto->EditAttrs["class"] = "form-control";
			$this->foto->EditCustomAttributes = "";
			$this->foto->UploadPath = "uploads";
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->ImageAlt = $this->foto->FldAlt();
				$this->foto->EditValue = $this->foto->Upload->DbValue;
			} else {
				$this->foto->EditValue = "";
			}
			if (!ew_Empty($this->foto->CurrentValue))
				$this->foto->Upload->FileName = $this->foto->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto);

			// foto2
			$this->foto2->EditAttrs["class"] = "form-control";
			$this->foto2->EditCustomAttributes = "";
			$this->foto2->UploadPath = "uploads";
			if (!ew_Empty($this->foto2->Upload->DbValue)) {
				$this->foto2->ImageAlt = $this->foto2->FldAlt();
				$this->foto2->EditValue = $this->foto2->Upload->DbValue;
			} else {
				$this->foto2->EditValue = "";
			}
			if (!ew_Empty($this->foto2->CurrentValue))
				$this->foto2->Upload->FileName = $this->foto2->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto2);

			// foto3
			$this->foto3->EditAttrs["class"] = "form-control";
			$this->foto3->EditCustomAttributes = "";
			$this->foto3->UploadPath = "uploads";
			if (!ew_Empty($this->foto3->Upload->DbValue)) {
				$this->foto3->ImageAlt = $this->foto3->FldAlt();
				$this->foto3->EditValue = $this->foto3->Upload->DbValue;
			} else {
				$this->foto3->EditValue = "";
			}
			if (!ew_Empty($this->foto3->CurrentValue))
				$this->foto3->Upload->FileName = $this->foto3->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto3);

			// caracteristicas
			$this->caracteristicas->EditAttrs["class"] = "form-control";
			$this->caracteristicas->EditCustomAttributes = "";
			$this->caracteristicas->EditValue = ew_HtmlEncode($this->caracteristicas->CurrentValue);
			$this->caracteristicas->PlaceHolder = ew_RemoveHtml($this->caracteristicas->FldCaption());

			// garantia
			$this->garantia->EditAttrs["class"] = "form-control";
			$this->garantia->EditCustomAttributes = "";
			$this->garantia->EditValue = ew_HtmlEncode($this->garantia->CurrentValue);
			$this->garantia->PlaceHolder = ew_RemoveHtml($this->garantia->FldCaption());

			// referencia
			$this->referencia->EditAttrs["class"] = "form-control";
			$this->referencia->EditCustomAttributes = "";
			$this->referencia->EditValue = ew_HtmlEncode($this->referencia->CurrentValue);
			$this->referencia->PlaceHolder = ew_RemoveHtml($this->referencia->FldCaption());

			// codlinea
			$this->codlinea->EditAttrs["class"] = "form-control";
			$this->codlinea->EditCustomAttributes = "";
			if (trim(strval($this->codlinea->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[cod_linea]" . ew_SearchString("=", $this->codlinea->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT [cod_linea], [Descripcion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[SIS_LINEA]";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->codlinea, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codlinea->EditValue = $arwrk;

			// calificacion
			$this->calificacion->EditAttrs["class"] = "form-control";
			$this->calificacion->EditCustomAttributes = "";
			$this->calificacion->EditValue = $this->calificacion->Options(TRUE);

			// fecultimamod
			// usuarioultimamod
			// Edit refer script
			// codarticulo

			$this->codarticulo->LinkCustomAttributes = "";
			$this->codarticulo->HrefValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";

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

			// caracteristicas
			$this->caracteristicas->LinkCustomAttributes = "";
			$this->caracteristicas->HrefValue = "";

			// garantia
			$this->garantia->LinkCustomAttributes = "";
			$this->garantia->HrefValue = "";

			// referencia
			$this->referencia->LinkCustomAttributes = "";
			$this->referencia->HrefValue = "";

			// codlinea
			$this->codlinea->LinkCustomAttributes = "";
			$this->codlinea->HrefValue = "";

			// calificacion
			$this->calificacion->LinkCustomAttributes = "";
			$this->calificacion->HrefValue = "";

			// fecultimamod
			$this->fecultimamod->LinkCustomAttributes = "";
			$this->fecultimamod->HrefValue = "";

			// usuarioultimamod
			$this->usuarioultimamod->LinkCustomAttributes = "";
			$this->usuarioultimamod->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->codarticulo->FldIsDetailKey && !is_null($this->codarticulo->FormValue) && $this->codarticulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codarticulo->FldCaption(), $this->codarticulo->ReqErrMsg));
		}
		if (!$this->descripcion->FldIsDetailKey && !is_null($this->descripcion->FormValue) && $this->descripcion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->descripcion->FldCaption(), $this->descripcion->ReqErrMsg));
		}
		if (!$this->precio->FldIsDetailKey && !is_null($this->precio->FormValue) && $this->precio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->precio->FldCaption(), $this->precio->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->precio->FormValue)) {
			ew_AddMessage($gsFormError, $this->precio->FldErrMsg());
		}
		if ($this->foto->Upload->FileName == "" && !$this->foto->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->foto->FldCaption(), $this->foto->ReqErrMsg));
		}
		if (!$this->caracteristicas->FldIsDetailKey && !is_null($this->caracteristicas->FormValue) && $this->caracteristicas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->caracteristicas->FldCaption(), $this->caracteristicas->ReqErrMsg));
		}
		if (!$this->garantia->FldIsDetailKey && !is_null($this->garantia->FormValue) && $this->garantia->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->garantia->FldCaption(), $this->garantia->ReqErrMsg));
		}
		if (!$this->referencia->FldIsDetailKey && !is_null($this->referencia->FormValue) && $this->referencia->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->referencia->FldCaption(), $this->referencia->ReqErrMsg));
		}
		if (!$this->codlinea->FldIsDetailKey && !is_null($this->codlinea->FormValue) && $this->codlinea->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codlinea->FldCaption(), $this->codlinea->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->foto->OldUploadPath = "uploads";
			$this->foto->UploadPath = $this->foto->OldUploadPath;
			$this->foto2->OldUploadPath = "uploads";
			$this->foto2->UploadPath = $this->foto2->OldUploadPath;
			$this->foto3->OldUploadPath = "uploads";
			$this->foto3->UploadPath = $this->foto3->OldUploadPath;
			$rsnew = array();

			// codarticulo
			// descripcion

			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, "", $this->descripcion->ReadOnly);

			// precio
			$this->precio->SetDbValueDef($rsnew, $this->precio->CurrentValue, NULL, $this->precio->ReadOnly);

			// foto
			if ($this->foto->Visible && !$this->foto->ReadOnly && !$this->foto->Upload->KeepFile) {
				$this->foto->Upload->DbValue = $rsold['foto']; // Get original value
				if ($this->foto->Upload->FileName == "") {
					$rsnew['foto'] = NULL;
				} else {
					$rsnew['foto'] = $this->foto->Upload->FileName;
				}
				$this->foto->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH; // Resize width
				$this->foto->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT; // Resize height
			}

			// foto2
			if ($this->foto2->Visible && !$this->foto2->ReadOnly && !$this->foto2->Upload->KeepFile) {
				$this->foto2->Upload->DbValue = $rsold['foto2']; // Get original value
				if ($this->foto2->Upload->FileName == "") {
					$rsnew['foto2'] = NULL;
				} else {
					$rsnew['foto2'] = $this->foto2->Upload->FileName;
				}
			}

			// foto3
			if ($this->foto3->Visible && !$this->foto3->ReadOnly && !$this->foto3->Upload->KeepFile) {
				$this->foto3->Upload->DbValue = $rsold['foto3']; // Get original value
				if ($this->foto3->Upload->FileName == "") {
					$rsnew['foto3'] = NULL;
				} else {
					$rsnew['foto3'] = $this->foto3->Upload->FileName;
				}
			}

			// caracteristicas
			$this->caracteristicas->SetDbValueDef($rsnew, $this->caracteristicas->CurrentValue, "", $this->caracteristicas->ReadOnly);

			// garantia
			$this->garantia->SetDbValueDef($rsnew, $this->garantia->CurrentValue, NULL, $this->garantia->ReadOnly);

			// referencia
			$this->referencia->SetDbValueDef($rsnew, $this->referencia->CurrentValue, "", $this->referencia->ReadOnly);

			// codlinea
			$this->codlinea->SetDbValueDef($rsnew, $this->codlinea->CurrentValue, 0, $this->codlinea->ReadOnly);

			// calificacion
			$this->calificacion->SetDbValueDef($rsnew, $this->calificacion->CurrentValue, NULL, $this->calificacion->ReadOnly);

			// fecultimamod
			$this->fecultimamod->SetDbValueDef($rsnew, ew_CurrentDate(), "");
			$rsnew['fecultimamod'] = &$this->fecultimamod->DbValue;

			// usuarioultimamod
			$this->usuarioultimamod->SetDbValueDef($rsnew, CurrentUserName(), "");
			$rsnew['usuarioultimamod'] = &$this->usuarioultimamod->DbValue;
			if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
				$this->foto->UploadPath = "uploads";
				if (!ew_Empty($this->foto->Upload->Value)) {
					$rsnew['foto'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto->UploadPath), $rsnew['foto']); // Get new file name
				}
			}
			if ($this->foto2->Visible && !$this->foto2->Upload->KeepFile) {
				$this->foto2->UploadPath = "uploads";
				if (!ew_Empty($this->foto2->Upload->Value)) {
					$rsnew['foto2'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto2->UploadPath), $rsnew['foto2']); // Get new file name
				}
			}
			if ($this->foto3->Visible && !$this->foto3->Upload->KeepFile) {
				$this->foto3->UploadPath = "uploads";
				if (!ew_Empty($this->foto3->Upload->Value)) {
					$rsnew['foto3'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto3->UploadPath), $rsnew['foto3']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
						if (!ew_Empty($this->foto->Upload->Value)) {
							$this->foto->Upload->Resize($this->foto->ImageWidth, $this->foto->ImageHeight);
							$this->foto->Upload->SaveToFile($this->foto->UploadPath, $rsnew['foto'], TRUE);
						}
					}
					if ($this->foto2->Visible && !$this->foto2->Upload->KeepFile) {
						if (!ew_Empty($this->foto2->Upload->Value)) {
							$this->foto2->Upload->SaveToFile($this->foto2->UploadPath, $rsnew['foto2'], TRUE);
						}
					}
					if ($this->foto3->Visible && !$this->foto3->Upload->KeepFile) {
						if (!ew_Empty($this->foto3->Upload->Value)) {
							$this->foto3->Upload->SaveToFile($this->foto3->UploadPath, $rsnew['foto3'], TRUE);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// foto
		ew_CleanUploadTempPath($this->foto, $this->foto->Upload->Index);

		// foto2
		ew_CleanUploadTempPath($this->foto2, $this->foto2->Upload->Index);

		// foto3
		ew_CleanUploadTempPath($this->foto3, $this->foto3->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_ARTICULOlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_ARTICULO_edit)) $SIS_ARTICULO_edit = new cSIS_ARTICULO_edit();

// Page init
$SIS_ARTICULO_edit->Page_Init();

// Page main
$SIS_ARTICULO_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_ARTICULO_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_ARTICULOedit = new ew_Form("fSIS_ARTICULOedit", "edit");

// Validate form
fSIS_ARTICULOedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_codarticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->codarticulo->FldCaption(), $SIS_ARTICULO->codarticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_descripcion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->descripcion->FldCaption(), $SIS_ARTICULO->descripcion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->precio->FldCaption(), $SIS_ARTICULO->precio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($SIS_ARTICULO->precio->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_foto");
			elm = this.GetElements("fn_x" + infix + "_foto");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->foto->FldCaption(), $SIS_ARTICULO->foto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_caracteristicas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->caracteristicas->FldCaption(), $SIS_ARTICULO->caracteristicas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_garantia");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->garantia->FldCaption(), $SIS_ARTICULO->garantia->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referencia");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->referencia->FldCaption(), $SIS_ARTICULO->referencia->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codlinea");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $SIS_ARTICULO->codlinea->FldCaption(), $SIS_ARTICULO->codlinea->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fSIS_ARTICULOedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_ARTICULOedit.ValidateRequired = true;
<?php } else { ?>
fSIS_ARTICULOedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_ARTICULOedit.Lists["x_codlinea"] = {"LinkField":"x_cod_linea","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOedit.Lists["x_calificacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_ARTICULOedit.Lists["x_calificacion"].Options = <?php echo json_encode($SIS_ARTICULO->calificacion->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $SIS_ARTICULO_edit->ShowPageHeader(); ?>
<?php
$SIS_ARTICULO_edit->ShowMessage();
?>
<form name="fSIS_ARTICULOedit" id="fSIS_ARTICULOedit" class="<?php echo $SIS_ARTICULO_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_ARTICULO_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_ARTICULO_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_ARTICULO">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($SIS_ARTICULO->codarticulo->Visible) { // codarticulo ?>
	<div id="r_codarticulo" class="form-group">
		<label id="elh_SIS_ARTICULO_codarticulo" for="x_codarticulo" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->codarticulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->codarticulo->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_codarticulo">
<span<?php echo $SIS_ARTICULO->codarticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_ARTICULO->codarticulo->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_ARTICULO" data-field="x_codarticulo" name="x_codarticulo" id="x_codarticulo" value="<?php echo ew_HtmlEncode($SIS_ARTICULO->codarticulo->CurrentValue) ?>">
<?php echo $SIS_ARTICULO->codarticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_SIS_ARTICULO_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->descripcion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->descripcion->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_descripcion">
<input type="text" data-table="SIS_ARTICULO" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO->descripcion->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO->descripcion->EditValue ?>"<?php echo $SIS_ARTICULO->descripcion->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->precio->Visible) { // precio ?>
	<div id="r_precio" class="form-group">
		<label id="elh_SIS_ARTICULO_precio" for="x_precio" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->precio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->precio->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_precio">
<input type="text" data-table="SIS_ARTICULO" data-field="x_precio" name="x_precio" id="x_precio" size="30" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO->precio->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO->precio->EditValue ?>"<?php echo $SIS_ARTICULO->precio->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO->precio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->foto->Visible) { // foto ?>
	<div id="r_foto" class="form-group">
		<label id="elh_SIS_ARTICULO_foto" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->foto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->foto->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_foto">
<div id="fd_x_foto">
<span title="<?php echo $SIS_ARTICULO->foto->FldTitle() ? $SIS_ARTICULO->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($SIS_ARTICULO->foto->ReadOnly || $SIS_ARTICULO->foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="SIS_ARTICULO" data-field="x_foto" name="x_foto" id="x_foto"<?php echo $SIS_ARTICULO->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?php echo $SIS_ARTICULO->foto->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto"] == "0") { ?>
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto" id= "fs_x_foto" value="200">
<input type="hidden" name="fx_x_foto" id= "fx_x_foto" value="<?php echo $SIS_ARTICULO->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto" id= "fm_x_foto" value="<?php echo $SIS_ARTICULO->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $SIS_ARTICULO->foto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->foto2->Visible) { // foto2 ?>
	<div id="r_foto2" class="form-group">
		<label id="elh_SIS_ARTICULO_foto2" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->foto2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->foto2->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_foto2">
<div id="fd_x_foto2">
<span title="<?php echo $SIS_ARTICULO->foto2->FldTitle() ? $SIS_ARTICULO->foto2->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($SIS_ARTICULO->foto2->ReadOnly || $SIS_ARTICULO->foto2->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="SIS_ARTICULO" data-field="x_foto2" name="x_foto2" id="x_foto2"<?php echo $SIS_ARTICULO->foto2->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_foto2" id= "fn_x_foto2" value="<?php echo $SIS_ARTICULO->foto2->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto2"] == "0") { ?>
<input type="hidden" name="fa_x_foto2" id= "fa_x_foto2" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto2" id= "fa_x_foto2" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto2" id= "fs_x_foto2" value="200">
<input type="hidden" name="fx_x_foto2" id= "fx_x_foto2" value="<?php echo $SIS_ARTICULO->foto2->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto2" id= "fm_x_foto2" value="<?php echo $SIS_ARTICULO->foto2->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto2" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $SIS_ARTICULO->foto2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->foto3->Visible) { // foto3 ?>
	<div id="r_foto3" class="form-group">
		<label id="elh_SIS_ARTICULO_foto3" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->foto3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->foto3->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_foto3">
<div id="fd_x_foto3">
<span title="<?php echo $SIS_ARTICULO->foto3->FldTitle() ? $SIS_ARTICULO->foto3->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($SIS_ARTICULO->foto3->ReadOnly || $SIS_ARTICULO->foto3->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="SIS_ARTICULO" data-field="x_foto3" name="x_foto3" id="x_foto3"<?php echo $SIS_ARTICULO->foto3->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_foto3" id= "fn_x_foto3" value="<?php echo $SIS_ARTICULO->foto3->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto3"] == "0") { ?>
<input type="hidden" name="fa_x_foto3" id= "fa_x_foto3" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto3" id= "fa_x_foto3" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto3" id= "fs_x_foto3" value="200">
<input type="hidden" name="fx_x_foto3" id= "fx_x_foto3" value="<?php echo $SIS_ARTICULO->foto3->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto3" id= "fm_x_foto3" value="<?php echo $SIS_ARTICULO->foto3->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto3" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $SIS_ARTICULO->foto3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->caracteristicas->Visible) { // caracteristicas ?>
	<div id="r_caracteristicas" class="form-group">
		<label id="elh_SIS_ARTICULO_caracteristicas" for="x_caracteristicas" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->caracteristicas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->caracteristicas->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_caracteristicas">
<input type="text" data-table="SIS_ARTICULO" data-field="x_caracteristicas" name="x_caracteristicas" id="x_caracteristicas" size="30" maxlength="400" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO->caracteristicas->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO->caracteristicas->EditValue ?>"<?php echo $SIS_ARTICULO->caracteristicas->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO->caracteristicas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->garantia->Visible) { // garantia ?>
	<div id="r_garantia" class="form-group">
		<label id="elh_SIS_ARTICULO_garantia" for="x_garantia" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->garantia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->garantia->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_garantia">
<input type="text" data-table="SIS_ARTICULO" data-field="x_garantia" name="x_garantia" id="x_garantia" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO->garantia->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO->garantia->EditValue ?>"<?php echo $SIS_ARTICULO->garantia->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO->garantia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->referencia->Visible) { // referencia ?>
	<div id="r_referencia" class="form-group">
		<label id="elh_SIS_ARTICULO_referencia" for="x_referencia" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->referencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->referencia->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_referencia">
<input type="text" data-table="SIS_ARTICULO" data-field="x_referencia" name="x_referencia" id="x_referencia" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($SIS_ARTICULO->referencia->getPlaceHolder()) ?>" value="<?php echo $SIS_ARTICULO->referencia->EditValue ?>"<?php echo $SIS_ARTICULO->referencia->EditAttributes() ?>>
</span>
<?php echo $SIS_ARTICULO->referencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->codlinea->Visible) { // codlinea ?>
	<div id="r_codlinea" class="form-group">
		<label id="elh_SIS_ARTICULO_codlinea" for="x_codlinea" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->codlinea->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->codlinea->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_codlinea">
<select data-table="SIS_ARTICULO" data-field="x_codlinea" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_ARTICULO->codlinea->DisplayValueSeparator) ? json_encode($SIS_ARTICULO->codlinea->DisplayValueSeparator) : $SIS_ARTICULO->codlinea->DisplayValueSeparator) ?>" id="x_codlinea" name="x_codlinea"<?php echo $SIS_ARTICULO->codlinea->EditAttributes() ?>>
<?php
if (is_array($SIS_ARTICULO->codlinea->EditValue)) {
	$arwrk = $SIS_ARTICULO->codlinea->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_ARTICULO->codlinea->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_ARTICULO->codlinea->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_ARTICULO->codlinea->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_ARTICULO->codlinea->CurrentValue) ?>" selected><?php echo $SIS_ARTICULO->codlinea->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT [cod_linea], [Descripcion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_LINEA]";
$sWhereWrk = "";
$SIS_ARTICULO->codlinea->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$SIS_ARTICULO->codlinea->LookupFilters += array("f0" => "[cod_linea] = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$SIS_ARTICULO->Lookup_Selecting($SIS_ARTICULO->codlinea, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $SIS_ARTICULO->codlinea->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_codlinea" id="s_x_codlinea" value="<?php echo $SIS_ARTICULO->codlinea->LookupFilterQuery() ?>">
</span>
<?php echo $SIS_ARTICULO->codlinea->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_ARTICULO->calificacion->Visible) { // calificacion ?>
	<div id="r_calificacion" class="form-group">
		<label id="elh_SIS_ARTICULO_calificacion" for="x_calificacion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_ARTICULO->calificacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_ARTICULO->calificacion->CellAttributes() ?>>
<span id="el_SIS_ARTICULO_calificacion">
<select data-table="SIS_ARTICULO" data-field="x_calificacion" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_ARTICULO->calificacion->DisplayValueSeparator) ? json_encode($SIS_ARTICULO->calificacion->DisplayValueSeparator) : $SIS_ARTICULO->calificacion->DisplayValueSeparator) ?>" id="x_calificacion" name="x_calificacion"<?php echo $SIS_ARTICULO->calificacion->EditAttributes() ?>>
<?php
if (is_array($SIS_ARTICULO->calificacion->EditValue)) {
	$arwrk = $SIS_ARTICULO->calificacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_ARTICULO->calificacion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_ARTICULO->calificacion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_ARTICULO->calificacion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_ARTICULO->calificacion->CurrentValue) ?>" selected><?php echo $SIS_ARTICULO->calificacion->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_ARTICULO->calificacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_ARTICULO_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_ARTICULOedit.Init();
</script>
<?php
$SIS_ARTICULO_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_ARTICULO_edit->Page_Terminate();
?>
