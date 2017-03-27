<?php

// Global variable for table object
$AuditTrail = NULL;

//
// Table class for AuditTrail
//
class cAuditTrail extends cTable {
	var $Id;
	var $DateTime;
	var $Script;
	var $User;
	var $Action;
	var $_Table;
	var $_Field;
	var $KeyValue;
	var $OldValue;
	var $NewValue;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'AuditTrail';
		$this->TableName = 'AuditTrail';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "[dbo].[AuditTrail]";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Id
		$this->Id = new cField('AuditTrail', 'AuditTrail', 'x_Id', 'Id', '[Id]', 'CAST([Id] AS NVARCHAR)', 3, -1, FALSE, '[Id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->Id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id'] = &$this->Id;

		// DateTime
		$this->DateTime = new cField('AuditTrail', 'AuditTrail', 'x_DateTime', 'DateTime', '[DateTime]', '(REPLACE(STR(DAY([DateTime]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([DateTime]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([DateTime]),4,0))', 135, 7, FALSE, '[DateTime]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->DateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['DateTime'] = &$this->DateTime;

		// Script
		$this->Script = new cField('AuditTrail', 'AuditTrail', 'x_Script', 'Script', '[Script]', '[Script]', 202, -1, FALSE, '[Script]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['Script'] = &$this->Script;

		// User
		$this->User = new cField('AuditTrail', 'AuditTrail', 'x_User', 'User', '[User]', '[User]', 202, -1, FALSE, '[User]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['User'] = &$this->User;

		// Action
		$this->Action = new cField('AuditTrail', 'AuditTrail', 'x_Action', 'Action', '[Action]', '[Action]', 202, -1, FALSE, '[Action]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['Action'] = &$this->Action;

		// Table
		$this->_Table = new cField('AuditTrail', 'AuditTrail', 'x__Table', 'Table', '[Table]', '[Table]', 202, -1, FALSE, '[Table]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['Table'] = &$this->_Table;

		// Field
		$this->_Field = new cField('AuditTrail', 'AuditTrail', 'x__Field', 'Field', '[Field]', '[Field]', 202, -1, FALSE, '[Field]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['Field'] = &$this->_Field;

		// KeyValue
		$this->KeyValue = new cField('AuditTrail', 'AuditTrail', 'x_KeyValue', 'KeyValue', '[KeyValue]', '[KeyValue]', 203, -1, FALSE, '[KeyValue]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['KeyValue'] = &$this->KeyValue;

		// OldValue
		$this->OldValue = new cField('AuditTrail', 'AuditTrail', 'x_OldValue', 'OldValue', '[OldValue]', '[OldValue]', 203, -1, FALSE, '[OldValue]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['OldValue'] = &$this->OldValue;

		// NewValue
		$this->NewValue = new cField('AuditTrail', 'AuditTrail', 'x_NewValue', 'NewValue', '[NewValue]', '[NewValue]', 203, -1, FALSE, '[NewValue]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['NewValue'] = &$this->NewValue;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "[dbo].[AuditTrail]";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('Id', $rs))
				ew_AddFilter($where, ew_QuotedName('Id', $this->DBID) . '=' . ew_QuotedValue($rs['Id'], $this->Id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[Id] = @Id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@Id@", ew_AdjustSql($this->Id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "AuditTraillist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "AuditTraillist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("AuditTrailview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("AuditTrailview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "AuditTrailadd.php?" . $this->UrlParm($parm);
		else
			$url = "AuditTrailadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("AuditTrailedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("AuditTrailadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("AuditTraildelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "Id:" . ew_VarToJson($this->Id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id->CurrentValue)) {
			$sUrl .= "Id=" . urlencode($this->Id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(141, 201, 203, 128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["Id"]))
				$arKeys[] = ew_StripSlashes($_POST["Id"]);
			elseif (isset($_GET["Id"]))
				$arKeys[] = ew_StripSlashes($_GET["Id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->Id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Id
		$this->Id->LinkCustomAttributes = "";
		$this->Id->HrefValue = "";
		$this->Id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id
		$this->Id->EditAttrs["class"] = "form-control";
		$this->Id->EditCustomAttributes = "";
		$this->Id->EditValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// DateTime
		$this->DateTime->EditAttrs["class"] = "form-control";
		$this->DateTime->EditCustomAttributes = "";
		$this->DateTime->EditValue = ew_FormatDateTime($this->DateTime->CurrentValue, 7);
		$this->DateTime->PlaceHolder = ew_RemoveHtml($this->DateTime->FldCaption());

		// Script
		$this->Script->EditAttrs["class"] = "form-control";
		$this->Script->EditCustomAttributes = "";
		$this->Script->EditValue = $this->Script->CurrentValue;
		$this->Script->PlaceHolder = ew_RemoveHtml($this->Script->FldCaption());

		// User
		$this->User->EditAttrs["class"] = "form-control";
		$this->User->EditCustomAttributes = "";
		$this->User->EditValue = $this->User->CurrentValue;
		$this->User->PlaceHolder = ew_RemoveHtml($this->User->FldCaption());

		// Action
		$this->Action->EditAttrs["class"] = "form-control";
		$this->Action->EditCustomAttributes = "";
		$this->Action->EditValue = $this->Action->CurrentValue;
		$this->Action->PlaceHolder = ew_RemoveHtml($this->Action->FldCaption());

		// Table
		$this->_Table->EditAttrs["class"] = "form-control";
		$this->_Table->EditCustomAttributes = "";
		$this->_Table->EditValue = $this->_Table->CurrentValue;
		$this->_Table->PlaceHolder = ew_RemoveHtml($this->_Table->FldCaption());

		// Field
		$this->_Field->EditAttrs["class"] = "form-control";
		$this->_Field->EditCustomAttributes = "";
		$this->_Field->EditValue = $this->_Field->CurrentValue;
		$this->_Field->PlaceHolder = ew_RemoveHtml($this->_Field->FldCaption());

		// KeyValue
		$this->KeyValue->EditAttrs["class"] = "form-control";
		$this->KeyValue->EditCustomAttributes = "";
		$this->KeyValue->EditValue = $this->KeyValue->CurrentValue;
		$this->KeyValue->PlaceHolder = ew_RemoveHtml($this->KeyValue->FldCaption());

		// OldValue
		$this->OldValue->EditAttrs["class"] = "form-control";
		$this->OldValue->EditCustomAttributes = "";
		$this->OldValue->EditValue = $this->OldValue->CurrentValue;
		$this->OldValue->PlaceHolder = ew_RemoveHtml($this->OldValue->FldCaption());

		// NewValue
		$this->NewValue->EditAttrs["class"] = "form-control";
		$this->NewValue->EditCustomAttributes = "";
		$this->NewValue->EditValue = $this->NewValue->CurrentValue;
		$this->NewValue->PlaceHolder = ew_RemoveHtml($this->NewValue->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->DateTime->Exportable) $Doc->ExportCaption($this->DateTime);
					if ($this->Script->Exportable) $Doc->ExportCaption($this->Script);
					if ($this->User->Exportable) $Doc->ExportCaption($this->User);
					if ($this->Action->Exportable) $Doc->ExportCaption($this->Action);
					if ($this->_Table->Exportable) $Doc->ExportCaption($this->_Table);
					if ($this->_Field->Exportable) $Doc->ExportCaption($this->_Field);
					if ($this->KeyValue->Exportable) $Doc->ExportCaption($this->KeyValue);
					if ($this->OldValue->Exportable) $Doc->ExportCaption($this->OldValue);
					if ($this->NewValue->Exportable) $Doc->ExportCaption($this->NewValue);
				} else {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->DateTime->Exportable) $Doc->ExportCaption($this->DateTime);
					if ($this->Script->Exportable) $Doc->ExportCaption($this->Script);
					if ($this->User->Exportable) $Doc->ExportCaption($this->User);
					if ($this->Action->Exportable) $Doc->ExportCaption($this->Action);
					if ($this->_Table->Exportable) $Doc->ExportCaption($this->_Table);
					if ($this->_Field->Exportable) $Doc->ExportCaption($this->_Field);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->DateTime->Exportable) $Doc->ExportField($this->DateTime);
						if ($this->Script->Exportable) $Doc->ExportField($this->Script);
						if ($this->User->Exportable) $Doc->ExportField($this->User);
						if ($this->Action->Exportable) $Doc->ExportField($this->Action);
						if ($this->_Table->Exportable) $Doc->ExportField($this->_Table);
						if ($this->_Field->Exportable) $Doc->ExportField($this->_Field);
						if ($this->KeyValue->Exportable) $Doc->ExportField($this->KeyValue);
						if ($this->OldValue->Exportable) $Doc->ExportField($this->OldValue);
						if ($this->NewValue->Exportable) $Doc->ExportField($this->NewValue);
					} else {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->DateTime->Exportable) $Doc->ExportField($this->DateTime);
						if ($this->Script->Exportable) $Doc->ExportField($this->Script);
						if ($this->User->Exportable) $Doc->ExportField($this->User);
						if ($this->Action->Exportable) $Doc->ExportField($this->Action);
						if ($this->_Table->Exportable) $Doc->ExportField($this->_Table);
						if ($this->_Field->Exportable) $Doc->ExportField($this->_Field);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
