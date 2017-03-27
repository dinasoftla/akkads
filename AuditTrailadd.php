<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "AuditTrailinfo.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$AuditTrail_add = NULL; // Initialize page object first

class cAuditTrail_add extends cAuditTrail {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'AuditTrail';

	// Page object name
	var $PageObjName = 'AuditTrail_add';

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

		// Table object (AuditTrail)
		if (!isset($GLOBALS["AuditTrail"]) || get_class($GLOBALS["AuditTrail"]) == "cAuditTrail") {
			$GLOBALS["AuditTrail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["AuditTrail"];
		}

		// Table object (SIS_USUARIOS)
		if (!isset($GLOBALS['SIS_USUARIOS'])) $GLOBALS['SIS_USUARIOS'] = new cSIS_USUARIOS();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'AuditTrail', TRUE);

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
		global $EW_EXPORT, $AuditTrail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($AuditTrail);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Id"] != "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->setKey("Id", $this->Id->CurrentValue); // Set up key
			} else {
				$this->setKey("Id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("AuditTraillist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "AuditTraillist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "AuditTrailview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->DateTime->CurrentValue = NULL;
		$this->DateTime->OldValue = $this->DateTime->CurrentValue;
		$this->Script->CurrentValue = NULL;
		$this->Script->OldValue = $this->Script->CurrentValue;
		$this->User->CurrentValue = NULL;
		$this->User->OldValue = $this->User->CurrentValue;
		$this->Action->CurrentValue = NULL;
		$this->Action->OldValue = $this->Action->CurrentValue;
		$this->_Table->CurrentValue = NULL;
		$this->_Table->OldValue = $this->_Table->CurrentValue;
		$this->_Field->CurrentValue = NULL;
		$this->_Field->OldValue = $this->_Field->CurrentValue;
		$this->KeyValue->CurrentValue = NULL;
		$this->KeyValue->OldValue = $this->KeyValue->CurrentValue;
		$this->OldValue->CurrentValue = NULL;
		$this->OldValue->OldValue = $this->OldValue->CurrentValue;
		$this->NewValue->CurrentValue = NULL;
		$this->NewValue->OldValue = $this->NewValue->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->DateTime->FldIsDetailKey) {
			$this->DateTime->setFormValue($objForm->GetValue("x_DateTime"));
			$this->DateTime->CurrentValue = ew_UnFormatDateTime($this->DateTime->CurrentValue, 7);
		}
		if (!$this->Script->FldIsDetailKey) {
			$this->Script->setFormValue($objForm->GetValue("x_Script"));
		}
		if (!$this->User->FldIsDetailKey) {
			$this->User->setFormValue($objForm->GetValue("x_User"));
		}
		if (!$this->Action->FldIsDetailKey) {
			$this->Action->setFormValue($objForm->GetValue("x_Action"));
		}
		if (!$this->_Table->FldIsDetailKey) {
			$this->_Table->setFormValue($objForm->GetValue("x__Table"));
		}
		if (!$this->_Field->FldIsDetailKey) {
			$this->_Field->setFormValue($objForm->GetValue("x__Field"));
		}
		if (!$this->KeyValue->FldIsDetailKey) {
			$this->KeyValue->setFormValue($objForm->GetValue("x_KeyValue"));
		}
		if (!$this->OldValue->FldIsDetailKey) {
			$this->OldValue->setFormValue($objForm->GetValue("x_OldValue"));
		}
		if (!$this->NewValue->FldIsDetailKey) {
			$this->NewValue->setFormValue($objForm->GetValue("x_NewValue"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->DateTime->CurrentValue = $this->DateTime->FormValue;
		$this->DateTime->CurrentValue = ew_UnFormatDateTime($this->DateTime->CurrentValue, 7);
		$this->Script->CurrentValue = $this->Script->FormValue;
		$this->User->CurrentValue = $this->User->FormValue;
		$this->Action->CurrentValue = $this->Action->FormValue;
		$this->_Table->CurrentValue = $this->_Table->FormValue;
		$this->_Field->CurrentValue = $this->_Field->FormValue;
		$this->KeyValue->CurrentValue = $this->KeyValue->FormValue;
		$this->OldValue->CurrentValue = $this->OldValue->FormValue;
		$this->NewValue->CurrentValue = $this->NewValue->FormValue;
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->DateTime->setDbValue($rs->fields('DateTime'));
		$this->Script->setDbValue($rs->fields('Script'));
		$this->User->setDbValue($rs->fields('User'));
		$this->Action->setDbValue($rs->fields('Action'));
		$this->_Table->setDbValue($rs->fields('Table'));
		$this->_Field->setDbValue($rs->fields('Field'));
		$this->KeyValue->setDbValue($rs->fields('KeyValue'));
		$this->OldValue->setDbValue($rs->fields('OldValue'));
		$this->NewValue->setDbValue($rs->fields('NewValue'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->DateTime->DbValue = $row['DateTime'];
		$this->Script->DbValue = $row['Script'];
		$this->User->DbValue = $row['User'];
		$this->Action->DbValue = $row['Action'];
		$this->_Table->DbValue = $row['Table'];
		$this->_Field->DbValue = $row['Field'];
		$this->KeyValue->DbValue = $row['KeyValue'];
		$this->OldValue->DbValue = $row['OldValue'];
		$this->NewValue->DbValue = $row['NewValue'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// DateTime
		// Script
		// User
		// Action
		// Table
		// Field
		// KeyValue
		// OldValue
		// NewValue

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// DateTime
		$this->DateTime->ViewValue = $this->DateTime->CurrentValue;
		$this->DateTime->ViewValue = ew_FormatDateTime($this->DateTime->ViewValue, 7);
		$this->DateTime->ViewCustomAttributes = "";

		// Script
		$this->Script->ViewValue = $this->Script->CurrentValue;
		$this->Script->ViewCustomAttributes = "";

		// User
		$this->User->ViewValue = $this->User->CurrentValue;
		$this->User->ViewCustomAttributes = "";

		// Action
		$this->Action->ViewValue = $this->Action->CurrentValue;
		$this->Action->ViewCustomAttributes = "";

		// Table
		$this->_Table->ViewValue = $this->_Table->CurrentValue;
		$this->_Table->ViewCustomAttributes = "";

		// Field
		$this->_Field->ViewValue = $this->_Field->CurrentValue;
		$this->_Field->ViewCustomAttributes = "";

		// KeyValue
		$this->KeyValue->ViewValue = $this->KeyValue->CurrentValue;
		$this->KeyValue->ViewCustomAttributes = "";

		// OldValue
		$this->OldValue->ViewValue = $this->OldValue->CurrentValue;
		$this->OldValue->ViewCustomAttributes = "";

		// NewValue
		$this->NewValue->ViewValue = $this->NewValue->CurrentValue;
		$this->NewValue->ViewCustomAttributes = "";

			// DateTime
			$this->DateTime->LinkCustomAttributes = "";
			$this->DateTime->HrefValue = "";
			$this->DateTime->TooltipValue = "";

			// Script
			$this->Script->LinkCustomAttributes = "";
			$this->Script->HrefValue = "";
			$this->Script->TooltipValue = "";

			// User
			$this->User->LinkCustomAttributes = "";
			$this->User->HrefValue = "";
			$this->User->TooltipValue = "";

			// Action
			$this->Action->LinkCustomAttributes = "";
			$this->Action->HrefValue = "";
			$this->Action->TooltipValue = "";

			// Table
			$this->_Table->LinkCustomAttributes = "";
			$this->_Table->HrefValue = "";
			$this->_Table->TooltipValue = "";

			// Field
			$this->_Field->LinkCustomAttributes = "";
			$this->_Field->HrefValue = "";
			$this->_Field->TooltipValue = "";

			// KeyValue
			$this->KeyValue->LinkCustomAttributes = "";
			$this->KeyValue->HrefValue = "";
			$this->KeyValue->TooltipValue = "";

			// OldValue
			$this->OldValue->LinkCustomAttributes = "";
			$this->OldValue->HrefValue = "";
			$this->OldValue->TooltipValue = "";

			// NewValue
			$this->NewValue->LinkCustomAttributes = "";
			$this->NewValue->HrefValue = "";
			$this->NewValue->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// DateTime
			$this->DateTime->EditAttrs["class"] = "form-control";
			$this->DateTime->EditCustomAttributes = "";
			$this->DateTime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DateTime->CurrentValue, 7));
			$this->DateTime->PlaceHolder = ew_RemoveHtml($this->DateTime->FldCaption());

			// Script
			$this->Script->EditAttrs["class"] = "form-control";
			$this->Script->EditCustomAttributes = "";
			$this->Script->EditValue = ew_HtmlEncode($this->Script->CurrentValue);
			$this->Script->PlaceHolder = ew_RemoveHtml($this->Script->FldCaption());

			// User
			$this->User->EditAttrs["class"] = "form-control";
			$this->User->EditCustomAttributes = "";
			$this->User->EditValue = ew_HtmlEncode($this->User->CurrentValue);
			$this->User->PlaceHolder = ew_RemoveHtml($this->User->FldCaption());

			// Action
			$this->Action->EditAttrs["class"] = "form-control";
			$this->Action->EditCustomAttributes = "";
			$this->Action->EditValue = ew_HtmlEncode($this->Action->CurrentValue);
			$this->Action->PlaceHolder = ew_RemoveHtml($this->Action->FldCaption());

			// Table
			$this->_Table->EditAttrs["class"] = "form-control";
			$this->_Table->EditCustomAttributes = "";
			$this->_Table->EditValue = ew_HtmlEncode($this->_Table->CurrentValue);
			$this->_Table->PlaceHolder = ew_RemoveHtml($this->_Table->FldCaption());

			// Field
			$this->_Field->EditAttrs["class"] = "form-control";
			$this->_Field->EditCustomAttributes = "";
			$this->_Field->EditValue = ew_HtmlEncode($this->_Field->CurrentValue);
			$this->_Field->PlaceHolder = ew_RemoveHtml($this->_Field->FldCaption());

			// KeyValue
			$this->KeyValue->EditAttrs["class"] = "form-control";
			$this->KeyValue->EditCustomAttributes = "";
			$this->KeyValue->EditValue = ew_HtmlEncode($this->KeyValue->CurrentValue);
			$this->KeyValue->PlaceHolder = ew_RemoveHtml($this->KeyValue->FldCaption());

			// OldValue
			$this->OldValue->EditAttrs["class"] = "form-control";
			$this->OldValue->EditCustomAttributes = "";
			$this->OldValue->EditValue = ew_HtmlEncode($this->OldValue->CurrentValue);
			$this->OldValue->PlaceHolder = ew_RemoveHtml($this->OldValue->FldCaption());

			// NewValue
			$this->NewValue->EditAttrs["class"] = "form-control";
			$this->NewValue->EditCustomAttributes = "";
			$this->NewValue->EditValue = ew_HtmlEncode($this->NewValue->CurrentValue);
			$this->NewValue->PlaceHolder = ew_RemoveHtml($this->NewValue->FldCaption());

			// Add refer script
			// DateTime

			$this->DateTime->LinkCustomAttributes = "";
			$this->DateTime->HrefValue = "";

			// Script
			$this->Script->LinkCustomAttributes = "";
			$this->Script->HrefValue = "";

			// User
			$this->User->LinkCustomAttributes = "";
			$this->User->HrefValue = "";

			// Action
			$this->Action->LinkCustomAttributes = "";
			$this->Action->HrefValue = "";

			// Table
			$this->_Table->LinkCustomAttributes = "";
			$this->_Table->HrefValue = "";

			// Field
			$this->_Field->LinkCustomAttributes = "";
			$this->_Field->HrefValue = "";

			// KeyValue
			$this->KeyValue->LinkCustomAttributes = "";
			$this->KeyValue->HrefValue = "";

			// OldValue
			$this->OldValue->LinkCustomAttributes = "";
			$this->OldValue->HrefValue = "";

			// NewValue
			$this->NewValue->LinkCustomAttributes = "";
			$this->NewValue->HrefValue = "";
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
		if (!$this->DateTime->FldIsDetailKey && !is_null($this->DateTime->FormValue) && $this->DateTime->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DateTime->FldCaption(), $this->DateTime->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DateTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->DateTime->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// DateTime
		$this->DateTime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DateTime->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// Script
		$this->Script->SetDbValueDef($rsnew, $this->Script->CurrentValue, NULL, FALSE);

		// User
		$this->User->SetDbValueDef($rsnew, $this->User->CurrentValue, NULL, FALSE);

		// Action
		$this->Action->SetDbValueDef($rsnew, $this->Action->CurrentValue, NULL, FALSE);

		// Table
		$this->_Table->SetDbValueDef($rsnew, $this->_Table->CurrentValue, NULL, FALSE);

		// Field
		$this->_Field->SetDbValueDef($rsnew, $this->_Field->CurrentValue, NULL, FALSE);

		// KeyValue
		$this->KeyValue->SetDbValueDef($rsnew, $this->KeyValue->CurrentValue, NULL, FALSE);

		// OldValue
		$this->OldValue->SetDbValueDef($rsnew, $this->OldValue->CurrentValue, NULL, FALSE);

		// NewValue
		$this->NewValue->SetDbValueDef($rsnew, $this->NewValue->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->Id->setDbValue($conn->Insert_ID());
				$rsnew['Id'] = $this->Id->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("AuditTraillist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($AuditTrail_add)) $AuditTrail_add = new cAuditTrail_add();

// Page init
$AuditTrail_add->Page_Init();

// Page main
$AuditTrail_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$AuditTrail_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fAuditTrailadd = new ew_Form("fAuditTrailadd", "add");

// Validate form
fAuditTrailadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_DateTime");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $AuditTrail->DateTime->FldCaption(), $AuditTrail->DateTime->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DateTime");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($AuditTrail->DateTime->FldErrMsg()) ?>");

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
fAuditTrailadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fAuditTrailadd.ValidateRequired = true;
<?php } else { ?>
fAuditTrailadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $AuditTrail_add->ShowPageHeader(); ?>
<?php
$AuditTrail_add->ShowMessage();
?>
<form name="fAuditTrailadd" id="fAuditTrailadd" class="<?php echo $AuditTrail_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($AuditTrail_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $AuditTrail_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="AuditTrail">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($AuditTrail->DateTime->Visible) { // DateTime ?>
	<div id="r_DateTime" class="form-group">
		<label id="elh_AuditTrail_DateTime" for="x_DateTime" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->DateTime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->DateTime->CellAttributes() ?>>
<span id="el_AuditTrail_DateTime">
<input type="text" data-table="AuditTrail" data-field="x_DateTime" data-format="7" name="x_DateTime" id="x_DateTime" placeholder="<?php echo ew_HtmlEncode($AuditTrail->DateTime->getPlaceHolder()) ?>" value="<?php echo $AuditTrail->DateTime->EditValue ?>"<?php echo $AuditTrail->DateTime->EditAttributes() ?>>
</span>
<?php echo $AuditTrail->DateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->Script->Visible) { // Script ?>
	<div id="r_Script" class="form-group">
		<label id="elh_AuditTrail_Script" for="x_Script" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->Script->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->Script->CellAttributes() ?>>
<span id="el_AuditTrail_Script">
<input type="text" data-table="AuditTrail" data-field="x_Script" name="x_Script" id="x_Script" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AuditTrail->Script->getPlaceHolder()) ?>" value="<?php echo $AuditTrail->Script->EditValue ?>"<?php echo $AuditTrail->Script->EditAttributes() ?>>
</span>
<?php echo $AuditTrail->Script->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->User->Visible) { // User ?>
	<div id="r_User" class="form-group">
		<label id="elh_AuditTrail_User" for="x_User" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->User->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->User->CellAttributes() ?>>
<span id="el_AuditTrail_User">
<input type="text" data-table="AuditTrail" data-field="x_User" name="x_User" id="x_User" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AuditTrail->User->getPlaceHolder()) ?>" value="<?php echo $AuditTrail->User->EditValue ?>"<?php echo $AuditTrail->User->EditAttributes() ?>>
</span>
<?php echo $AuditTrail->User->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->Action->Visible) { // Action ?>
	<div id="r_Action" class="form-group">
		<label id="elh_AuditTrail_Action" for="x_Action" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->Action->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->Action->CellAttributes() ?>>
<span id="el_AuditTrail_Action">
<input type="text" data-table="AuditTrail" data-field="x_Action" name="x_Action" id="x_Action" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AuditTrail->Action->getPlaceHolder()) ?>" value="<?php echo $AuditTrail->Action->EditValue ?>"<?php echo $AuditTrail->Action->EditAttributes() ?>>
</span>
<?php echo $AuditTrail->Action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->_Table->Visible) { // Table ?>
	<div id="r__Table" class="form-group">
		<label id="elh_AuditTrail__Table" for="x__Table" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->_Table->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->_Table->CellAttributes() ?>>
<span id="el_AuditTrail__Table">
<input type="text" data-table="AuditTrail" data-field="x__Table" name="x__Table" id="x__Table" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AuditTrail->_Table->getPlaceHolder()) ?>" value="<?php echo $AuditTrail->_Table->EditValue ?>"<?php echo $AuditTrail->_Table->EditAttributes() ?>>
</span>
<?php echo $AuditTrail->_Table->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->_Field->Visible) { // Field ?>
	<div id="r__Field" class="form-group">
		<label id="elh_AuditTrail__Field" for="x__Field" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->_Field->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->_Field->CellAttributes() ?>>
<span id="el_AuditTrail__Field">
<input type="text" data-table="AuditTrail" data-field="x__Field" name="x__Field" id="x__Field" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AuditTrail->_Field->getPlaceHolder()) ?>" value="<?php echo $AuditTrail->_Field->EditValue ?>"<?php echo $AuditTrail->_Field->EditAttributes() ?>>
</span>
<?php echo $AuditTrail->_Field->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->KeyValue->Visible) { // KeyValue ?>
	<div id="r_KeyValue" class="form-group">
		<label id="elh_AuditTrail_KeyValue" for="x_KeyValue" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->KeyValue->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->KeyValue->CellAttributes() ?>>
<span id="el_AuditTrail_KeyValue">
<textarea data-table="AuditTrail" data-field="x_KeyValue" name="x_KeyValue" id="x_KeyValue" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($AuditTrail->KeyValue->getPlaceHolder()) ?>"<?php echo $AuditTrail->KeyValue->EditAttributes() ?>><?php echo $AuditTrail->KeyValue->EditValue ?></textarea>
</span>
<?php echo $AuditTrail->KeyValue->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->OldValue->Visible) { // OldValue ?>
	<div id="r_OldValue" class="form-group">
		<label id="elh_AuditTrail_OldValue" for="x_OldValue" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->OldValue->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->OldValue->CellAttributes() ?>>
<span id="el_AuditTrail_OldValue">
<textarea data-table="AuditTrail" data-field="x_OldValue" name="x_OldValue" id="x_OldValue" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($AuditTrail->OldValue->getPlaceHolder()) ?>"<?php echo $AuditTrail->OldValue->EditAttributes() ?>><?php echo $AuditTrail->OldValue->EditValue ?></textarea>
</span>
<?php echo $AuditTrail->OldValue->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($AuditTrail->NewValue->Visible) { // NewValue ?>
	<div id="r_NewValue" class="form-group">
		<label id="elh_AuditTrail_NewValue" for="x_NewValue" class="col-sm-2 control-label ewLabel"><?php echo $AuditTrail->NewValue->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $AuditTrail->NewValue->CellAttributes() ?>>
<span id="el_AuditTrail_NewValue">
<textarea data-table="AuditTrail" data-field="x_NewValue" name="x_NewValue" id="x_NewValue" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($AuditTrail->NewValue->getPlaceHolder()) ?>"<?php echo $AuditTrail->NewValue->EditAttributes() ?>><?php echo $AuditTrail->NewValue->EditValue ?></textarea>
</span>
<?php echo $AuditTrail->NewValue->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $AuditTrail_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fAuditTrailadd.Init();
</script>
<?php
$AuditTrail_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$AuditTrail_add->Page_Terminate();
?>
