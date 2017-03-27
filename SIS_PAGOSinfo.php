<?php

// Global variable for table object
$SIS_PAGOS = NULL;

//
// Table class for SIS_PAGOS
//
class cSIS_PAGOS extends cTable {
	var $consecutivo;
	var $fecha;
	var $tipo;
	var $codproveedor;
	var $factura;
	var $descripcion;
	var $cantidad;
	var $monto;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'SIS_PAGOS';
		$this->TableName = 'SIS_PAGOS';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "[dbo].[SIS_PAGOS]";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// consecutivo
		$this->consecutivo = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_consecutivo', 'consecutivo', '[consecutivo]', 'CAST([consecutivo] AS NVARCHAR)', 3, -1, FALSE, '[consecutivo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->consecutivo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['consecutivo'] = &$this->consecutivo;

		// fecha
		$this->fecha = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_fecha', 'fecha', '[fecha]', '[fecha]', 202, -1, FALSE, '[fecha]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fecha'] = &$this->fecha;

		// tipo
		$this->tipo = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_tipo', 'tipo', '[tipo]', '[tipo]', 200, -1, FALSE, '[tipo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipo->OptionCount = 2;
		$this->fields['tipo'] = &$this->tipo;

		// codproveedor
		$this->codproveedor = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_codproveedor', 'codproveedor', '[codproveedor]', 'CAST([codproveedor] AS NVARCHAR)', 3, -1, FALSE, '[codproveedor]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->codproveedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codproveedor'] = &$this->codproveedor;

		// factura
		$this->factura = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_factura', 'factura', '[factura]', 'CAST([factura] AS NVARCHAR)', 3, -1, FALSE, '[factura]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->factura->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['factura'] = &$this->factura;

		// descripcion
		$this->descripcion = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_descripcion', 'descripcion', '[descripcion]', '[descripcion]', 200, -1, FALSE, '[descripcion]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// cantidad
		$this->cantidad = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_cantidad', 'cantidad', '[cantidad]', 'CAST([cantidad] AS NVARCHAR)', 3, -1, FALSE, '[cantidad]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cantidad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cantidad'] = &$this->cantidad;

		// monto
		$this->monto = new cField('SIS_PAGOS', 'SIS_PAGOS', 'x_monto', 'monto', '[monto]', 'CAST([monto] AS NVARCHAR)', 5, -1, FALSE, '[monto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->monto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['monto'] = &$this->monto;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "[dbo].[SIS_PAGOS]";
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
			if (array_key_exists('consecutivo', $rs))
				ew_AddFilter($where, ew_QuotedName('consecutivo', $this->DBID) . '=' . ew_QuotedValue($rs['consecutivo'], $this->consecutivo->FldDataType, $this->DBID));
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
		return "[consecutivo] = @consecutivo@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->consecutivo->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@consecutivo@", ew_AdjustSql($this->consecutivo->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "SIS_PAGOSlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "SIS_PAGOSlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("SIS_PAGOSview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("SIS_PAGOSview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "SIS_PAGOSadd.php?" . $this->UrlParm($parm);
		else
			$url = "SIS_PAGOSadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("SIS_PAGOSedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("SIS_PAGOSadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("SIS_PAGOSdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "consecutivo:" . ew_VarToJson($this->consecutivo->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->consecutivo->CurrentValue)) {
			$sUrl .= "consecutivo=" . urlencode($this->consecutivo->CurrentValue);
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
			if ($isPost && isset($_POST["consecutivo"]))
				$arKeys[] = ew_StripSlashes($_POST["consecutivo"]);
			elseif (isset($_GET["consecutivo"]))
				$arKeys[] = ew_StripSlashes($_GET["consecutivo"]);
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
			$this->consecutivo->CurrentValue = $key;
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
		$this->consecutivo->setDbValue($rs->fields('consecutivo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->codproveedor->setDbValue($rs->fields('codproveedor'));
		$this->factura->setDbValue($rs->fields('factura'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->monto->setDbValue($rs->fields('monto'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// consecutivo
		// fecha
		// tipo
		// codproveedor
		// factura
		// descripcion
		// cantidad
		// monto
		// consecutivo

		$this->consecutivo->ViewValue = $this->consecutivo->CurrentValue;
		$this->consecutivo->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			$this->tipo->ViewValue = $this->tipo->OptionCaption($this->tipo->CurrentValue);
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// codproveedor
		if (strval($this->codproveedor->CurrentValue) <> "") {
			$sFilterWrk = "[codproveedor]" . ew_SearchString("=", $this->codproveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT [codproveedor], [nombre] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[SIS_PROVEEDORES]";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->codproveedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->codproveedor->ViewValue = $this->codproveedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->codproveedor->ViewValue = $this->codproveedor->CurrentValue;
			}
		} else {
			$this->codproveedor->ViewValue = NULL;
		}
		$this->codproveedor->ViewCustomAttributes = "";

		// factura
		$this->factura->ViewValue = $this->factura->CurrentValue;
		$this->factura->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// monto
		$this->monto->ViewValue = $this->monto->CurrentValue;
		$this->monto->ViewCustomAttributes = "";

		// consecutivo
		$this->consecutivo->LinkCustomAttributes = "";
		$this->consecutivo->HrefValue = "";
		$this->consecutivo->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// tipo
		$this->tipo->LinkCustomAttributes = "";
		$this->tipo->HrefValue = "";
		$this->tipo->TooltipValue = "";

		// codproveedor
		$this->codproveedor->LinkCustomAttributes = "";
		$this->codproveedor->HrefValue = "";
		$this->codproveedor->TooltipValue = "";

		// factura
		$this->factura->LinkCustomAttributes = "";
		$this->factura->HrefValue = "";
		$this->factura->TooltipValue = "";

		// descripcion
		$this->descripcion->LinkCustomAttributes = "";
		$this->descripcion->HrefValue = "";
		$this->descripcion->TooltipValue = "";

		// cantidad
		$this->cantidad->LinkCustomAttributes = "";
		$this->cantidad->HrefValue = "";
		$this->cantidad->TooltipValue = "";

		// monto
		$this->monto->LinkCustomAttributes = "";
		$this->monto->HrefValue = "";
		$this->monto->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// consecutivo
		$this->consecutivo->EditAttrs["class"] = "form-control";
		$this->consecutivo->EditCustomAttributes = "";
		$this->consecutivo->EditValue = $this->consecutivo->CurrentValue;
		$this->consecutivo->ViewCustomAttributes = "";

		// fecha
		// tipo

		$this->tipo->EditAttrs["class"] = "form-control";
		$this->tipo->EditCustomAttributes = "";
		$this->tipo->EditValue = $this->tipo->Options(TRUE);

		// codproveedor
		$this->codproveedor->EditAttrs["class"] = "form-control";
		$this->codproveedor->EditCustomAttributes = "";

		// factura
		$this->factura->EditAttrs["class"] = "form-control";
		$this->factura->EditCustomAttributes = "";
		$this->factura->EditValue = $this->factura->CurrentValue;
		$this->factura->PlaceHolder = ew_RemoveHtml($this->factura->FldCaption());

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

		// monto
		$this->monto->EditAttrs["class"] = "form-control";
		$this->monto->EditCustomAttributes = "";
		$this->monto->EditValue = $this->monto->CurrentValue;
		$this->monto->PlaceHolder = ew_RemoveHtml($this->monto->FldCaption());
		if (strval($this->monto->EditValue) <> "" && is_numeric($this->monto->EditValue)) $this->monto->EditValue = ew_FormatNumber($this->monto->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->monto->CurrentValue))
				$this->monto->Total += $this->monto->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->monto->CurrentValue = $this->monto->Total;
			$this->monto->ViewValue = $this->monto->CurrentValue;
			$this->monto->ViewCustomAttributes = "";
			$this->monto->HrefValue = ""; // Clear href value

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
					if ($this->consecutivo->Exportable) $Doc->ExportCaption($this->consecutivo);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->codproveedor->Exportable) $Doc->ExportCaption($this->codproveedor);
					if ($this->factura->Exportable) $Doc->ExportCaption($this->factura);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->cantidad->Exportable) $Doc->ExportCaption($this->cantidad);
					if ($this->monto->Exportable) $Doc->ExportCaption($this->monto);
				} else {
					if ($this->consecutivo->Exportable) $Doc->ExportCaption($this->consecutivo);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->codproveedor->Exportable) $Doc->ExportCaption($this->codproveedor);
					if ($this->factura->Exportable) $Doc->ExportCaption($this->factura);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->cantidad->Exportable) $Doc->ExportCaption($this->cantidad);
					if ($this->monto->Exportable) $Doc->ExportCaption($this->monto);
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
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->consecutivo->Exportable) $Doc->ExportField($this->consecutivo);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->codproveedor->Exportable) $Doc->ExportField($this->codproveedor);
						if ($this->factura->Exportable) $Doc->ExportField($this->factura);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->cantidad->Exportable) $Doc->ExportField($this->cantidad);
						if ($this->monto->Exportable) $Doc->ExportField($this->monto);
					} else {
						if ($this->consecutivo->Exportable) $Doc->ExportField($this->consecutivo);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->codproveedor->Exportable) $Doc->ExportField($this->codproveedor);
						if ($this->factura->Exportable) $Doc->ExportField($this->factura);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->cantidad->Exportable) $Doc->ExportField($this->cantidad);
						if ($this->monto->Exportable) $Doc->ExportField($this->monto);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
				$Doc->ExportAggregate($this->consecutivo, '');
				$Doc->ExportAggregate($this->fecha, '');
				$Doc->ExportAggregate($this->tipo, '');
				$Doc->ExportAggregate($this->codproveedor, '');
				$Doc->ExportAggregate($this->factura, '');
				$Doc->ExportAggregate($this->descripcion, '');
				$Doc->ExportAggregate($this->cantidad, '');
				$Doc->ExportAggregate($this->monto, 'TOTAL');
				$Doc->EndExportRow();
			}
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
