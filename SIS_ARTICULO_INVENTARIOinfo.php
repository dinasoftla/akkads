<?php

// Global variable for table object
$SIS_ARTICULO_INVENTARIO = NULL;

//
// Table class for SIS_ARTICULO_INVENTARIO
//
class cSIS_ARTICULO_INVENTARIO extends cTable {
	var $codarticulo;
	var $canarticulo;
	var $candisponible;
	var $canapartado;
	var $fecultimamod;
	var $usuarioultimamod;
	var $codubicacion;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'SIS_ARTICULO_INVENTARIO';
		$this->TableName = 'SIS_ARTICULO_INVENTARIO';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "[dbo].[SIS_ARTICULO_INVENTARIO]";
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

		// codarticulo
		$this->codarticulo = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_codarticulo', 'codarticulo', '[codarticulo]', '[codarticulo]', 200, -1, FALSE, '[codarticulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['codarticulo'] = &$this->codarticulo;

		// canarticulo
		$this->canarticulo = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_canarticulo', 'canarticulo', '[canarticulo]', 'CAST([canarticulo] AS NVARCHAR)', 3, -1, FALSE, '[canarticulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->canarticulo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['canarticulo'] = &$this->canarticulo;

		// candisponible
		$this->candisponible = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_candisponible', 'candisponible', '[candisponible]', 'CAST([candisponible] AS NVARCHAR)', 3, -1, FALSE, '[candisponible]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->candisponible->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['candisponible'] = &$this->candisponible;

		// canapartado
		$this->canapartado = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_canapartado', 'canapartado', '[canapartado]', 'CAST([canapartado] AS NVARCHAR)', 3, -1, FALSE, '[canapartado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->canapartado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['canapartado'] = &$this->canapartado;

		// fecultimamod
		$this->fecultimamod = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_fecultimamod', 'fecultimamod', '[fecultimamod]', '[fecultimamod]', 202, -1, FALSE, '[fecultimamod]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fecultimamod'] = &$this->fecultimamod;

		// usuarioultimamod
		$this->usuarioultimamod = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_usuarioultimamod', 'usuarioultimamod', '[usuarioultimamod]', '[usuarioultimamod]', 200, -1, FALSE, '[usuarioultimamod]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['usuarioultimamod'] = &$this->usuarioultimamod;

		// codubicacion
		$this->codubicacion = new cField('SIS_ARTICULO_INVENTARIO', 'SIS_ARTICULO_INVENTARIO', 'x_codubicacion', 'codubicacion', '[codubicacion]', 'CAST([codubicacion] AS NVARCHAR)', 3, -1, FALSE, '[codubicacion]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->codubicacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codubicacion'] = &$this->codubicacion;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "[dbo].[SIS_ARTICULO_INVENTARIO]";
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
			if (array_key_exists('codarticulo', $rs))
				ew_AddFilter($where, ew_QuotedName('codarticulo', $this->DBID) . '=' . ew_QuotedValue($rs['codarticulo'], $this->codarticulo->FldDataType, $this->DBID));
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
		return "[codarticulo] = '@codarticulo@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@codarticulo@", ew_AdjustSql($this->codarticulo->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "SIS_ARTICULO_INVENTARIOlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "SIS_ARTICULO_INVENTARIOlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("SIS_ARTICULO_INVENTARIOview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("SIS_ARTICULO_INVENTARIOview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "SIS_ARTICULO_INVENTARIOadd.php?" . $this->UrlParm($parm);
		else
			$url = "SIS_ARTICULO_INVENTARIOadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("SIS_ARTICULO_INVENTARIOedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("SIS_ARTICULO_INVENTARIOadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("SIS_ARTICULO_INVENTARIOdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "codarticulo:" . ew_VarToJson($this->codarticulo->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->codarticulo->CurrentValue)) {
			$sUrl .= "codarticulo=" . urlencode($this->codarticulo->CurrentValue);
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
			if ($isPost && isset($_POST["codarticulo"]))
				$arKeys[] = ew_StripSlashes($_POST["codarticulo"]);
			elseif (isset($_GET["codarticulo"]))
				$arKeys[] = ew_StripSlashes($_GET["codarticulo"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
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
			$this->codarticulo->CurrentValue = $key;
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
		$this->codarticulo->setDbValue($rs->fields('codarticulo'));
		$this->canarticulo->setDbValue($rs->fields('canarticulo'));
		$this->candisponible->setDbValue($rs->fields('candisponible'));
		$this->canapartado->setDbValue($rs->fields('canapartado'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
		$this->codubicacion->setDbValue($rs->fields('codubicacion'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// codarticulo
		// canarticulo
		// candisponible
		// canapartado
		// fecultimamod
		// usuarioultimamod
		// codubicacion
		// codarticulo

		if (strval($this->codarticulo->CurrentValue) <> "") {
			$sFilterWrk = "[codarticulo]" . ew_SearchString("=", $this->codarticulo->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT [codarticulo], [codarticulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_ARTICULO]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codarticulo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codarticulo->ViewValue = $this->codarticulo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codarticulo->ViewValue = $this->codarticulo->CurrentValue;
			}
		} else {
			$this->codarticulo->ViewValue = NULL;
		}
		$this->codarticulo->ViewCustomAttributes = "";

		// canarticulo
		$this->canarticulo->ViewValue = $this->canarticulo->CurrentValue;
		$this->canarticulo->ViewCustomAttributes = "";

		// candisponible
		$this->candisponible->ViewValue = $this->candisponible->CurrentValue;
		$this->candisponible->ViewCustomAttributes = "";

		// canapartado
		$this->canapartado->ViewValue = $this->canapartado->CurrentValue;
		$this->canapartado->ViewCustomAttributes = "";

		// fecultimamod
		$this->fecultimamod->ViewValue = $this->fecultimamod->CurrentValue;
		$this->fecultimamod->ViewCustomAttributes = "";

		// usuarioultimamod
		$this->usuarioultimamod->ViewValue = $this->usuarioultimamod->CurrentValue;
		$this->usuarioultimamod->ViewCustomAttributes = "";

		// codubicacion
		if (strval($this->codubicacion->CurrentValue) <> "") {
			$sFilterWrk = "[codubicacion]" . ew_SearchString("=", $this->codubicacion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codubicacion], [ubicacion] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_UBICACIONES]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codubicacion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codubicacion->ViewValue = $this->codubicacion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codubicacion->ViewValue = $this->codubicacion->CurrentValue;
			}
		} else {
			$this->codubicacion->ViewValue = NULL;
		}
		$this->codubicacion->ViewCustomAttributes = "";

		// codarticulo
		$this->codarticulo->LinkCustomAttributes = "";
		$this->codarticulo->HrefValue = "";
		$this->codarticulo->TooltipValue = "";

		// canarticulo
		$this->canarticulo->LinkCustomAttributes = "";
		$this->canarticulo->HrefValue = "";
		$this->canarticulo->TooltipValue = "";

		// candisponible
		$this->candisponible->LinkCustomAttributes = "";
		$this->candisponible->HrefValue = "";
		$this->candisponible->TooltipValue = "";

		// canapartado
		$this->canapartado->LinkCustomAttributes = "";
		$this->canapartado->HrefValue = "";
		$this->canapartado->TooltipValue = "";

		// fecultimamod
		$this->fecultimamod->LinkCustomAttributes = "";
		$this->fecultimamod->HrefValue = "";
		$this->fecultimamod->TooltipValue = "";

		// usuarioultimamod
		$this->usuarioultimamod->LinkCustomAttributes = "";
		$this->usuarioultimamod->HrefValue = "";
		$this->usuarioultimamod->TooltipValue = "";

		// codubicacion
		$this->codubicacion->LinkCustomAttributes = "";
		$this->codubicacion->HrefValue = "";
		$this->codubicacion->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// codarticulo
		$this->codarticulo->EditAttrs["class"] = "form-control";
		$this->codarticulo->EditCustomAttributes = "";
		if (strval($this->codarticulo->CurrentValue) <> "") {
			$sFilterWrk = "[codarticulo]" . ew_SearchString("=", $this->codarticulo->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT [codarticulo], [codarticulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_ARTICULO]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codarticulo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codarticulo->EditValue = $this->codarticulo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codarticulo->EditValue = $this->codarticulo->CurrentValue;
			}
		} else {
			$this->codarticulo->EditValue = NULL;
		}
		$this->codarticulo->ViewCustomAttributes = "";

		// canarticulo
		$this->canarticulo->EditAttrs["class"] = "form-control";
		$this->canarticulo->EditCustomAttributes = "";
		$this->canarticulo->EditValue = $this->canarticulo->CurrentValue;
		$this->canarticulo->PlaceHolder = ew_RemoveHtml($this->canarticulo->FldCaption());

		// candisponible
		$this->candisponible->EditAttrs["class"] = "form-control";
		$this->candisponible->EditCustomAttributes = "";
		$this->candisponible->EditValue = $this->candisponible->CurrentValue;
		$this->candisponible->PlaceHolder = ew_RemoveHtml($this->candisponible->FldCaption());

		// canapartado
		$this->canapartado->EditAttrs["class"] = "form-control";
		$this->canapartado->EditCustomAttributes = "";
		$this->canapartado->EditValue = $this->canapartado->CurrentValue;
		$this->canapartado->PlaceHolder = ew_RemoveHtml($this->canapartado->FldCaption());

		// fecultimamod
		// usuarioultimamod
		// codubicacion

		$this->codubicacion->EditAttrs["class"] = "form-control";
		$this->codubicacion->EditCustomAttributes = "";

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
					if ($this->codarticulo->Exportable) $Doc->ExportCaption($this->codarticulo);
					if ($this->canarticulo->Exportable) $Doc->ExportCaption($this->canarticulo);
					if ($this->candisponible->Exportable) $Doc->ExportCaption($this->candisponible);
					if ($this->canapartado->Exportable) $Doc->ExportCaption($this->canapartado);
					if ($this->fecultimamod->Exportable) $Doc->ExportCaption($this->fecultimamod);
					if ($this->usuarioultimamod->Exportable) $Doc->ExportCaption($this->usuarioultimamod);
					if ($this->codubicacion->Exportable) $Doc->ExportCaption($this->codubicacion);
				} else {
					if ($this->codarticulo->Exportable) $Doc->ExportCaption($this->codarticulo);
					if ($this->canarticulo->Exportable) $Doc->ExportCaption($this->canarticulo);
					if ($this->candisponible->Exportable) $Doc->ExportCaption($this->candisponible);
					if ($this->canapartado->Exportable) $Doc->ExportCaption($this->canapartado);
					if ($this->fecultimamod->Exportable) $Doc->ExportCaption($this->fecultimamod);
					if ($this->usuarioultimamod->Exportable) $Doc->ExportCaption($this->usuarioultimamod);
					if ($this->codubicacion->Exportable) $Doc->ExportCaption($this->codubicacion);
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
						if ($this->codarticulo->Exportable) $Doc->ExportField($this->codarticulo);
						if ($this->canarticulo->Exportable) $Doc->ExportField($this->canarticulo);
						if ($this->candisponible->Exportable) $Doc->ExportField($this->candisponible);
						if ($this->canapartado->Exportable) $Doc->ExportField($this->canapartado);
						if ($this->fecultimamod->Exportable) $Doc->ExportField($this->fecultimamod);
						if ($this->usuarioultimamod->Exportable) $Doc->ExportField($this->usuarioultimamod);
						if ($this->codubicacion->Exportable) $Doc->ExportField($this->codubicacion);
					} else {
						if ($this->codarticulo->Exportable) $Doc->ExportField($this->codarticulo);
						if ($this->canarticulo->Exportable) $Doc->ExportField($this->canarticulo);
						if ($this->candisponible->Exportable) $Doc->ExportField($this->candisponible);
						if ($this->canapartado->Exportable) $Doc->ExportField($this->canapartado);
						if ($this->fecultimamod->Exportable) $Doc->ExportField($this->fecultimamod);
						if ($this->usuarioultimamod->Exportable) $Doc->ExportField($this->usuarioultimamod);
						if ($this->codubicacion->Exportable) $Doc->ExportField($this->codubicacion);
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