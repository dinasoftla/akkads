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

$SIS_TAREAS_edit = NULL; // Initialize page object first

class cSIS_TAREAS_edit extends cSIS_TAREAS {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_TAREAS';

	// Page object name
	var $PageObjName = 'SIS_TAREAS_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["codtarea"] <> "") {
			$this->codtarea->setQueryStringValue($_GET["codtarea"]);
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
		if ($this->codtarea->CurrentValue == "")
			$this->Page_Terminate("SIS_TAREASlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("SIS_TAREASlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "SIS_TAREASlist.php")
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codtarea->FldIsDetailKey)
			$this->codtarea->setFormValue($objForm->GetValue("x_codtarea"));
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->respondable->FldIsDetailKey) {
			$this->respondable->setFormValue($objForm->GetValue("x_respondable"));
		}
		if (!$this->fechainicio->FldIsDetailKey) {
			$this->fechainicio->setFormValue($objForm->GetValue("x_fechainicio"));
			$this->fechainicio->CurrentValue = ew_UnFormatDateTime($this->fechainicio->CurrentValue, 7);
		}
		if (!$this->fechafinal->FldIsDetailKey) {
			$this->fechafinal->setFormValue($objForm->GetValue("x_fechafinal"));
			$this->fechafinal->CurrentValue = ew_UnFormatDateTime($this->fechafinal->CurrentValue, 7);
		}
		if (!$this->prioridad->FldIsDetailKey) {
			$this->prioridad->setFormValue($objForm->GetValue("x_prioridad"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->codtarea->CurrentValue = $this->codtarea->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->respondable->CurrentValue = $this->respondable->FormValue;
		$this->fechainicio->CurrentValue = $this->fechainicio->FormValue;
		$this->fechainicio->CurrentValue = ew_UnFormatDateTime($this->fechainicio->CurrentValue, 7);
		$this->fechafinal->CurrentValue = $this->fechafinal->FormValue;
		$this->fechafinal->CurrentValue = ew_UnFormatDateTime($this->fechafinal->CurrentValue, 7);
		$this->prioridad->CurrentValue = $this->prioridad->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codtarea
			$this->codtarea->EditAttrs["class"] = "form-control";
			$this->codtarea->EditCustomAttributes = "";
			$this->codtarea->EditValue = $this->codtarea->CurrentValue;
			$this->codtarea->ViewCustomAttributes = "";

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(TRUE);

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// respondable
			$this->respondable->EditAttrs["class"] = "form-control";
			$this->respondable->EditCustomAttributes = "";
			$this->respondable->EditValue = $this->respondable->Options(TRUE);

			// fechainicio
			$this->fechainicio->EditAttrs["class"] = "form-control";
			$this->fechainicio->EditCustomAttributes = "";
			$this->fechainicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechainicio->CurrentValue, 7));
			$this->fechainicio->PlaceHolder = ew_RemoveHtml($this->fechainicio->FldCaption());

			// fechafinal
			$this->fechafinal->EditAttrs["class"] = "form-control";
			$this->fechafinal->EditCustomAttributes = "";
			$this->fechafinal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechafinal->CurrentValue, 7));
			$this->fechafinal->PlaceHolder = ew_RemoveHtml($this->fechafinal->FldCaption());

			// prioridad
			$this->prioridad->EditAttrs["class"] = "form-control";
			$this->prioridad->EditCustomAttributes = "";
			$this->prioridad->EditValue = $this->prioridad->Options(TRUE);

			// Edit refer script
			// codtarea

			$this->codtarea->LinkCustomAttributes = "";
			$this->codtarea->HrefValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";

			// respondable
			$this->respondable->LinkCustomAttributes = "";
			$this->respondable->HrefValue = "";

			// fechainicio
			$this->fechainicio->LinkCustomAttributes = "";
			$this->fechainicio->HrefValue = "";

			// fechafinal
			$this->fechafinal->LinkCustomAttributes = "";
			$this->fechafinal->HrefValue = "";

			// prioridad
			$this->prioridad->LinkCustomAttributes = "";
			$this->prioridad->HrefValue = "";
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
			$rsnew = array();

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, $this->nombre->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, NULL, $this->estado->ReadOnly);

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, $this->descripcion->ReadOnly);

			// respondable
			$this->respondable->SetDbValueDef($rsnew, $this->respondable->CurrentValue, NULL, $this->respondable->ReadOnly);

			// fechainicio
			$this->fechainicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechainicio->CurrentValue, 7), NULL, $this->fechainicio->ReadOnly);

			// fechafinal
			$this->fechafinal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechafinal->CurrentValue, 7), NULL, $this->fechafinal->ReadOnly);

			// prioridad
			$this->prioridad->SetDbValueDef($rsnew, $this->prioridad->CurrentValue, NULL, $this->prioridad->ReadOnly);

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
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("SIS_TAREASlist.php"), "", $this->TableVar, TRUE);
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
		//CODIGO QUE CREA DATAPICKET

	  echo ' <link href="jscalendarjquery/css/jquery.datepick.css" rel="stylesheet">
		   <script src="jscalendarjquery/js/jquery.plugin.min.js"></script>
		   <script src="jscalendarjquery/js/jquery.datepick.js"></script>
		   <script>
		   $(function() {
		   	$(\'#x_fechainicio\').datepick();
		   	$(\'#x_fechafinal\').datepick();
		   });
		   </script>';
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
if (!isset($SIS_TAREAS_edit)) $SIS_TAREAS_edit = new cSIS_TAREAS_edit();

// Page init
$SIS_TAREAS_edit->Page_Init();

// Page main
$SIS_TAREAS_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SIS_TAREAS_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fSIS_TAREASedit = new ew_Form("fSIS_TAREASedit", "edit");

// Validate form
fSIS_TAREASedit.Validate = function() {
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
fSIS_TAREASedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fSIS_TAREASedit.ValidateRequired = true;
<?php } else { ?>
fSIS_TAREASedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fSIS_TAREASedit.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_TAREASedit.Lists["x_estado"].Options = <?php echo json_encode($SIS_TAREAS->estado->Options()) ?>;
fSIS_TAREASedit.Lists["x_respondable"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_TAREASedit.Lists["x_respondable"].Options = <?php echo json_encode($SIS_TAREAS->respondable->Options()) ?>;
fSIS_TAREASedit.Lists["x_prioridad"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fSIS_TAREASedit.Lists["x_prioridad"].Options = <?php echo json_encode($SIS_TAREAS->prioridad->Options()) ?>;

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
<?php $SIS_TAREAS_edit->ShowPageHeader(); ?>
<?php
$SIS_TAREAS_edit->ShowMessage();
?>
<form name="fSIS_TAREASedit" id="fSIS_TAREASedit" class="<?php echo $SIS_TAREAS_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($SIS_TAREAS_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $SIS_TAREAS_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="SIS_TAREAS">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($SIS_TAREAS->codtarea->Visible) { // codtarea ?>
	<div id="r_codtarea" class="form-group">
		<label id="elh_SIS_TAREAS_codtarea" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->codtarea->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->codtarea->CellAttributes() ?>>
<span id="el_SIS_TAREAS_codtarea">
<span<?php echo $SIS_TAREAS->codtarea->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $SIS_TAREAS->codtarea->EditValue ?></p></span>
</span>
<input type="hidden" data-table="SIS_TAREAS" data-field="x_codtarea" name="x_codtarea" id="x_codtarea" value="<?php echo ew_HtmlEncode($SIS_TAREAS->codtarea->CurrentValue) ?>">
<?php echo $SIS_TAREAS->codtarea->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_SIS_TAREAS_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->nombre->CellAttributes() ?>>
<span id="el_SIS_TAREAS_nombre">
<input type="text" data-table="SIS_TAREAS" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($SIS_TAREAS->nombre->getPlaceHolder()) ?>" value="<?php echo $SIS_TAREAS->nombre->EditValue ?>"<?php echo $SIS_TAREAS->nombre->EditAttributes() ?>>
</span>
<?php echo $SIS_TAREAS->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_SIS_TAREAS_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->estado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->estado->CellAttributes() ?>>
<span id="el_SIS_TAREAS_estado">
<select data-table="SIS_TAREAS" data-field="x_estado" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_TAREAS->estado->DisplayValueSeparator) ? json_encode($SIS_TAREAS->estado->DisplayValueSeparator) : $SIS_TAREAS->estado->DisplayValueSeparator) ?>" id="x_estado" name="x_estado"<?php echo $SIS_TAREAS->estado->EditAttributes() ?>>
<?php
if (is_array($SIS_TAREAS->estado->EditValue)) {
	$arwrk = $SIS_TAREAS->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_TAREAS->estado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_TAREAS->estado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_TAREAS->estado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_TAREAS->estado->CurrentValue) ?>" selected><?php echo $SIS_TAREAS->estado->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_TAREAS->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_SIS_TAREAS_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->descripcion->CellAttributes() ?>>
<span id="el_SIS_TAREAS_descripcion">
<textarea data-table="SIS_TAREAS" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" cols="30" rows="7" placeholder="<?php echo ew_HtmlEncode($SIS_TAREAS->descripcion->getPlaceHolder()) ?>"<?php echo $SIS_TAREAS->descripcion->EditAttributes() ?>><?php echo $SIS_TAREAS->descripcion->EditValue ?></textarea>
</span>
<?php echo $SIS_TAREAS->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->respondable->Visible) { // respondable ?>
	<div id="r_respondable" class="form-group">
		<label id="elh_SIS_TAREAS_respondable" for="x_respondable" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->respondable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->respondable->CellAttributes() ?>>
<span id="el_SIS_TAREAS_respondable">
<select data-table="SIS_TAREAS" data-field="x_respondable" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_TAREAS->respondable->DisplayValueSeparator) ? json_encode($SIS_TAREAS->respondable->DisplayValueSeparator) : $SIS_TAREAS->respondable->DisplayValueSeparator) ?>" id="x_respondable" name="x_respondable"<?php echo $SIS_TAREAS->respondable->EditAttributes() ?>>
<?php
if (is_array($SIS_TAREAS->respondable->EditValue)) {
	$arwrk = $SIS_TAREAS->respondable->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_TAREAS->respondable->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_TAREAS->respondable->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_TAREAS->respondable->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_TAREAS->respondable->CurrentValue) ?>" selected><?php echo $SIS_TAREAS->respondable->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_TAREAS->respondable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->fechainicio->Visible) { // fechainicio ?>
	<div id="r_fechainicio" class="form-group">
		<label id="elh_SIS_TAREAS_fechainicio" for="x_fechainicio" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->fechainicio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->fechainicio->CellAttributes() ?>>
<span id="el_SIS_TAREAS_fechainicio">
<input type="text" data-table="SIS_TAREAS" data-field="x_fechainicio" data-format="7" name="x_fechainicio" id="x_fechainicio" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($SIS_TAREAS->fechainicio->getPlaceHolder()) ?>" value="<?php echo $SIS_TAREAS->fechainicio->EditValue ?>"<?php echo $SIS_TAREAS->fechainicio->EditAttributes() ?>>
</span>
<?php echo $SIS_TAREAS->fechainicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->fechafinal->Visible) { // fechafinal ?>
	<div id="r_fechafinal" class="form-group">
		<label id="elh_SIS_TAREAS_fechafinal" for="x_fechafinal" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->fechafinal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->fechafinal->CellAttributes() ?>>
<span id="el_SIS_TAREAS_fechafinal">
<input type="text" data-table="SIS_TAREAS" data-field="x_fechafinal" data-format="7" name="x_fechafinal" id="x_fechafinal" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($SIS_TAREAS->fechafinal->getPlaceHolder()) ?>" value="<?php echo $SIS_TAREAS->fechafinal->EditValue ?>"<?php echo $SIS_TAREAS->fechafinal->EditAttributes() ?>>
</span>
<?php echo $SIS_TAREAS->fechafinal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($SIS_TAREAS->prioridad->Visible) { // prioridad ?>
	<div id="r_prioridad" class="form-group">
		<label id="elh_SIS_TAREAS_prioridad" for="x_prioridad" class="col-sm-2 control-label ewLabel"><?php echo $SIS_TAREAS->prioridad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $SIS_TAREAS->prioridad->CellAttributes() ?>>
<span id="el_SIS_TAREAS_prioridad">
<select data-table="SIS_TAREAS" data-field="x_prioridad" data-value-separator="<?php echo ew_HtmlEncode(is_array($SIS_TAREAS->prioridad->DisplayValueSeparator) ? json_encode($SIS_TAREAS->prioridad->DisplayValueSeparator) : $SIS_TAREAS->prioridad->DisplayValueSeparator) ?>" id="x_prioridad" name="x_prioridad"<?php echo $SIS_TAREAS->prioridad->EditAttributes() ?>>
<?php
if (is_array($SIS_TAREAS->prioridad->EditValue)) {
	$arwrk = $SIS_TAREAS->prioridad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($SIS_TAREAS->prioridad->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $SIS_TAREAS->prioridad->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($SIS_TAREAS->prioridad->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($SIS_TAREAS->prioridad->CurrentValue) ?>" selected><?php echo $SIS_TAREAS->prioridad->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $SIS_TAREAS->prioridad->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $SIS_TAREAS_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fSIS_TAREASedit.Init();
</script>
<?php
$SIS_TAREAS_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$SIS_TAREAS_edit->Page_Terminate();
?>
