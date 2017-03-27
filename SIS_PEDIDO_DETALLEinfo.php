<?php

// Global variable for table object
$SIS_PEDIDO_DETALLE = NULL;

//
// Table class for SIS_PEDIDO_DETALLE
//
class cSIS_PEDIDO_DETALLE extends cTable {
	var $numpedido;
	var $numdetpedido;
	var $codarticulo;
	var $descripcion;
	var $cantidad;
	var $preciounitario;
	var $total;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'SIS_PEDIDO_DETALLE';
		$this->TableName = 'SIS_PEDIDO_DETALLE';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "[dbo].[SIS_PEDIDO_DETALLE]";
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

		// numpedido
		$this->numpedido = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_numpedido', 'numpedido', '[numpedido]', 'CAST([numpedido] AS NVARCHAR)', 3, -1, FALSE, '[numpedido]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->numpedido->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numpedido'] = &$this->numpedido;

		// numdetpedido
		$this->numdetpedido = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_numdetpedido', 'numdetpedido', '[numdetpedido]', 'CAST([numdetpedido] AS NVARCHAR)', 3, -1, FALSE, '[numdetpedido]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->numdetpedido->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numdetpedido'] = &$this->numdetpedido;

		// codarticulo
		$this->codarticulo = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_codarticulo', 'codarticulo', '[codarticulo]', '[codarticulo]', 200, -1, FALSE, '[codarticulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['codarticulo'] = &$this->codarticulo;

		// descripcion
		$this->descripcion = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_descripcion', 'descripcion', '[descripcion]', '[descripcion]', 200, -1, FALSE, '[descripcion]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// cantidad
		$this->cantidad = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_cantidad', 'cantidad', '[cantidad]', 'CAST([cantidad] AS NVARCHAR)', 3, -1, FALSE, '[cantidad]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cantidad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cantidad'] = &$this->cantidad;

		// preciounitario
		$this->preciounitario = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_preciounitario', 'preciounitario', '[preciounitario]', 'CAST([preciounitario] AS NVARCHAR)', 131, -1, FALSE, '[preciounitario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->preciounitario->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['preciounitario'] = &$this->preciounitario;

		// total
		$this->total = new cField('SIS_PEDIDO_DETALLE', 'SIS_PEDIDO_DETALLE', 'x_total', 'total', '[total]', 'CAST([total] AS NVARCHAR)', 131, -1, FALSE, '[total]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "SIS_PEDIDO") {
			if ($this->numpedido->getSessionValue() <> "")
				$sMasterFilter .= "[numpedido]=" . ew_QuotedValue($this->numpedido->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "SIS_PEDIDO") {
			if ($this->numpedido->getSessionValue() <> "")
				$sDetailFilter .= "[numpedido]=" . ew_QuotedValue($this->numpedido->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_SIS_PEDIDO() {
		return "[numpedido]=@numpedido@";
	}

	// Detail filter
	function SqlDetailFilter_SIS_PEDIDO() {
		return "[numpedido]=@numpedido@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "[dbo].[SIS_PEDIDO_DETALLE]";
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
			if (array_key_exists('numpedido', $rs))
				ew_AddFilter($where, ew_QuotedName('numpedido', $this->DBID) . '=' . ew_QuotedValue($rs['numpedido'], $this->numpedido->FldDataType, $this->DBID));
			if (array_key_exists('numdetpedido', $rs))
				ew_AddFilter($where, ew_QuotedName('numdetpedido', $this->DBID) . '=' . ew_QuotedValue($rs['numdetpedido'], $this->numdetpedido->FldDataType, $this->DBID));
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
		return "[numpedido] = @numpedido@ AND [numdetpedido] = @numdetpedido@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->numpedido->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@numpedido@", ew_AdjustSql($this->numpedido->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->numdetpedido->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@numdetpedido@", ew_AdjustSql($this->numdetpedido->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "SIS_PEDIDO_DETALLElist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "SIS_PEDIDO_DETALLElist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("SIS_PEDIDO_DETALLEview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("SIS_PEDIDO_DETALLEview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "SIS_PEDIDO_DETALLEadd.php?" . $this->UrlParm($parm);
		else
			$url = "SIS_PEDIDO_DETALLEadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("SIS_PEDIDO_DETALLEedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("SIS_PEDIDO_DETALLEadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("SIS_PEDIDO_DETALLEdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "SIS_PEDIDO" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_numpedido=" . urlencode($this->numpedido->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "numpedido:" . ew_VarToJson($this->numpedido->CurrentValue, "number", "'");
		$json .= ",numdetpedido:" . ew_VarToJson($this->numdetpedido->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->numpedido->CurrentValue)) {
			$sUrl .= "numpedido=" . urlencode($this->numpedido->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->numdetpedido->CurrentValue)) {
			$sUrl .= "&numdetpedido=" . urlencode($this->numdetpedido->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["numpedido"]))
				$arKey[] = ew_StripSlashes($_POST["numpedido"]);
			elseif (isset($_GET["numpedido"]))
				$arKey[] = ew_StripSlashes($_GET["numpedido"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["numdetpedido"]))
				$arKey[] = ew_StripSlashes($_POST["numdetpedido"]);
			elseif (isset($_GET["numdetpedido"]))
				$arKey[] = ew_StripSlashes($_GET["numdetpedido"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 2)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // numpedido
					continue;
				if (!is_numeric($key[1])) // numdetpedido
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
			$this->numpedido->CurrentValue = $key[0];
			$this->numdetpedido->CurrentValue = $key[1];
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
		$this->numpedido->setDbValue($rs->fields('numpedido'));
		$this->numdetpedido->setDbValue($rs->fields('numdetpedido'));
		$this->codarticulo->setDbValue($rs->fields('codarticulo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->preciounitario->setDbValue($rs->fields('preciounitario'));
		$this->total->setDbValue($rs->fields('total'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// numpedido
		// numdetpedido
		// codarticulo
		// descripcion
		// cantidad
		// preciounitario
		// total
		// numpedido

		$this->numpedido->ViewValue = $this->numpedido->CurrentValue;
		$this->numpedido->ViewCustomAttributes = "";

		// numdetpedido
		$this->numdetpedido->ViewValue = $this->numdetpedido->CurrentValue;
		$this->numdetpedido->ViewCustomAttributes = "";

		// codarticulo
		$this->codarticulo->ViewValue = $this->codarticulo->CurrentValue;
		$this->codarticulo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// preciounitario
		$this->preciounitario->ViewValue = $this->preciounitario->CurrentValue;
		$this->preciounitario->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// numpedido
		$this->numpedido->LinkCustomAttributes = "";
		$this->numpedido->HrefValue = "";
		$this->numpedido->TooltipValue = "";

		// numdetpedido
		$this->numdetpedido->LinkCustomAttributes = "";
		$this->numdetpedido->HrefValue = "";
		$this->numdetpedido->TooltipValue = "";

		// codarticulo
		$this->codarticulo->LinkCustomAttributes = "";
		$this->codarticulo->HrefValue = "";
		$this->codarticulo->TooltipValue = "";

		// descripcion
		$this->descripcion->LinkCustomAttributes = "";
		$this->descripcion->HrefValue = "";
		$this->descripcion->TooltipValue = "";

		// cantidad
		$this->cantidad->LinkCustomAttributes = "";
		$this->cantidad->HrefValue = "";
		$this->cantidad->TooltipValue = "";

		// preciounitario
		$this->preciounitario->LinkCustomAttributes = "";
		$this->preciounitario->HrefValue = "";
		$this->preciounitario->TooltipValue = "";

		// total
		$this->total->LinkCustomAttributes = "";
		$this->total->HrefValue = "";
		$this->total->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// numpedido
		$this->numpedido->EditAttrs["class"] = "form-control";
		$this->numpedido->EditCustomAttributes = "";
		$this->numpedido->EditValue = $this->numpedido->CurrentValue;
		$this->numpedido->ViewCustomAttributes = "";

		// numdetpedido
		$this->numdetpedido->EditAttrs["class"] = "form-control";
		$this->numdetpedido->EditCustomAttributes = "";
		$this->numdetpedido->EditValue = $this->numdetpedido->CurrentValue;
		$this->numdetpedido->ViewCustomAttributes = "";

		// codarticulo
		$this->codarticulo->EditAttrs["class"] = "form-control";
		$this->codarticulo->EditCustomAttributes = "";
		$this->codarticulo->EditValue = $this->codarticulo->CurrentValue;
		$this->codarticulo->PlaceHolder = ew_RemoveHtml($this->codarticulo->FldCaption());

		// descripcion
		$this->descripcion->EditAttrs["class"] = "form-control";
		$this->descripcion->EditCustomAttributes = "";
		$this->descripcion->EditValue = $this->descripcion->CurrentValue;
		$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

		// cantidad
		$this->cantidad->EditAttrs["class"] = "form-control";
		$this->cantidad->EditCustomAttributes = "";
		$this->cantidad->EditValue = $this->cantidad->CurrentValue;
		$this->cantidad->PlaceHolder = ew_RemoveHtml($this->cantidad->FldCaption());

		// preciounitario
		$this->preciounitario->EditAttrs["class"] = "form-control";
		$this->preciounitario->EditCustomAttributes = "";
		$this->preciounitario->EditValue = $this->preciounitario->CurrentValue;
		$this->preciounitario->PlaceHolder = ew_RemoveHtml($this->preciounitario->FldCaption());
		if (strval($this->preciounitario->EditValue) <> "" && is_numeric($this->preciounitario->EditValue)) $this->preciounitario->EditValue = ew_FormatNumber($this->preciounitario->EditValue, -2, -1, -2, 0);

		// total
		$this->total->EditAttrs["class"] = "form-control";
		$this->total->EditCustomAttributes = "";
		$this->total->EditValue = $this->total->CurrentValue;
		$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->total->CurrentValue))
				$this->total->Total += $this->total->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->total->CurrentValue = $this->total->Total;
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewCustomAttributes = "";
			$this->total->HrefValue = ""; // Clear href value

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
					if ($this->numpedido->Exportable) $Doc->ExportCaption($this->numpedido);
					if ($this->numdetpedido->Exportable) $Doc->ExportCaption($this->numdetpedido);
					if ($this->codarticulo->Exportable) $Doc->ExportCaption($this->codarticulo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->cantidad->Exportable) $Doc->ExportCaption($this->cantidad);
					if ($this->preciounitario->Exportable) $Doc->ExportCaption($this->preciounitario);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
				} else {
					if ($this->numpedido->Exportable) $Doc->ExportCaption($this->numpedido);
					if ($this->numdetpedido->Exportable) $Doc->ExportCaption($this->numdetpedido);
					if ($this->codarticulo->Exportable) $Doc->ExportCaption($this->codarticulo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->cantidad->Exportable) $Doc->ExportCaption($this->cantidad);
					if ($this->preciounitario->Exportable) $Doc->ExportCaption($this->preciounitario);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
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
						if ($this->numpedido->Exportable) $Doc->ExportField($this->numpedido);
						if ($this->numdetpedido->Exportable) $Doc->ExportField($this->numdetpedido);
						if ($this->codarticulo->Exportable) $Doc->ExportField($this->codarticulo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->cantidad->Exportable) $Doc->ExportField($this->cantidad);
						if ($this->preciounitario->Exportable) $Doc->ExportField($this->preciounitario);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
					} else {
						if ($this->numpedido->Exportable) $Doc->ExportField($this->numpedido);
						if ($this->numdetpedido->Exportable) $Doc->ExportField($this->numdetpedido);
						if ($this->codarticulo->Exportable) $Doc->ExportField($this->codarticulo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->cantidad->Exportable) $Doc->ExportField($this->cantidad);
						if ($this->preciounitario->Exportable) $Doc->ExportField($this->preciounitario);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
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
		//IF ($rsnew["codarticulo"] <= 0)
		//{
			//echo '<script type="text/javascript">alert("La cantidad del articulo '.$rsnew["codarticulo"].' es invalida.");</script>';
			//return false;
		//}

		$rs =& $rsnew;
		$articuloexiste = false;
		$query = "EXEC SP_CONSULTARDETALLEARTICULO ".$rsnew["codarticulo"];
		$dsquery = ew_Execute($query);
		foreach ($dsquery as $dsvalue => $value) {	
			$rs["preciounitario"] = $value["precio"];
			$rs["total"] = $value["precio"] * $rs["cantidad"];
			$rs["descripcion"] = $value["descripcion"]. " - Garantia (".$value["garantia"]. ")" ;
			$rsnew["codarticulo"] = $value["origen"];
			$articuloexiste = true;
		}
		IF ($articuloexiste)
		{
			return true;
		}
		ELSE
		{
			echo '<script type="text/javascript">alert("El articulo '.$rsnew["codarticulo"].' no existe.");</script>';
			return false;
		}
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
