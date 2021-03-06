<?php

// Global variable for table object
$SIS_ARTICULO = NULL;

//
// Table class for SIS_ARTICULO
//
class cSIS_ARTICULO extends cTable {
	var $codarticulo;
	var $descripcion;
	var $precio;
	var $foto;
	var $foto2;
	var $foto3;
	var $caracteristicas;
	var $garantia;
	var $referencia;
	var $codlinea;
	var $calificacion;
	var $fecultimamod;
	var $usuarioultimamod;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'SIS_ARTICULO';
		$this->TableName = 'SIS_ARTICULO';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "[dbo].[SIS_ARTICULO]";
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
		$this->codarticulo = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_codarticulo', 'codarticulo', '[codarticulo]', '[codarticulo]', 200, -1, FALSE, '[codarticulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['codarticulo'] = &$this->codarticulo;

		// descripcion
		$this->descripcion = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_descripcion', 'descripcion', '[descripcion]', '[descripcion]', 200, -1, FALSE, '[descripcion]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// precio
		$this->precio = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_precio', 'precio', '[precio]', 'CAST([precio] AS NVARCHAR)', 131, -1, FALSE, '[precio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->precio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precio'] = &$this->precio;

		// foto
		$this->foto = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_foto', 'foto', '[foto]', '[foto]', 200, -1, TRUE, '[foto]', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['foto'] = &$this->foto;

		// foto2
		$this->foto2 = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_foto2', 'foto2', '[foto2]', '[foto2]', 200, -1, TRUE, '[foto2]', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['foto2'] = &$this->foto2;

		// foto3
		$this->foto3 = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_foto3', 'foto3', '[foto3]', '[foto3]', 200, -1, TRUE, '[foto3]', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['foto3'] = &$this->foto3;

		// caracteristicas
		$this->caracteristicas = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_caracteristicas', 'caracteristicas', '[caracteristicas]', '[caracteristicas]', 200, -1, FALSE, '[caracteristicas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['caracteristicas'] = &$this->caracteristicas;

		// garantia
		$this->garantia = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_garantia', 'garantia', '[garantia]', '[garantia]', 200, -1, FALSE, '[garantia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['garantia'] = &$this->garantia;

		// referencia
		$this->referencia = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_referencia', 'referencia', '[referencia]', '[referencia]', 200, -1, FALSE, '[referencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['referencia'] = &$this->referencia;

		// codlinea
		$this->codlinea = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_codlinea', 'codlinea', '[codlinea]', 'CAST([codlinea] AS NVARCHAR)', 3, -1, FALSE, '[codlinea]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->codlinea->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codlinea'] = &$this->codlinea;

		// calificacion
		$this->calificacion = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_calificacion', 'calificacion', '[calificacion]', '[calificacion]', 200, -1, FALSE, '[calificacion]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->calificacion->OptionCount = 4;
		$this->fields['calificacion'] = &$this->calificacion;

		// fecultimamod
		$this->fecultimamod = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_fecultimamod', 'fecultimamod', '[fecultimamod]', '[fecultimamod]', 202, -1, FALSE, '[fecultimamod]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fecultimamod'] = &$this->fecultimamod;

		// usuarioultimamod
		$this->usuarioultimamod = new cField('SIS_ARTICULO', 'SIS_ARTICULO', 'x_usuarioultimamod', 'usuarioultimamod', '[usuarioultimamod]', '[usuarioultimamod]', 200, -1, FALSE, '[usuarioultimamod]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['usuarioultimamod'] = &$this->usuarioultimamod;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "[dbo].[SIS_ARTICULO]";
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
			return "SIS_ARTICULOlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "SIS_ARTICULOlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("SIS_ARTICULOview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("SIS_ARTICULOview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "SIS_ARTICULOadd.php?" . $this->UrlParm($parm);
		else
			$url = "SIS_ARTICULOadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("SIS_ARTICULOedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("SIS_ARTICULOadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("SIS_ARTICULOdelete.php", $this->UrlParm());
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
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->foto->Upload->DbValue = $rs->fields('foto');
		$this->foto2->Upload->DbValue = $rs->fields('foto2');
		$this->foto3->Upload->DbValue = $rs->fields('foto3');
		$this->caracteristicas->setDbValue($rs->fields('caracteristicas'));
		$this->garantia->setDbValue($rs->fields('garantia'));
		$this->referencia->setDbValue($rs->fields('referencia'));
		$this->codlinea->setDbValue($rs->fields('codlinea'));
		$this->calificacion->setDbValue($rs->fields('calificacion'));
		$this->fecultimamod->setDbValue($rs->fields('fecultimamod'));
		$this->usuarioultimamod->setDbValue($rs->fields('usuarioultimamod'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		$this->codarticulo->EditValue = $this->codarticulo->CurrentValue;
		$this->codarticulo->CssStyle = "font-weight: bold;";
		$this->codarticulo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->EditAttrs["class"] = "form-control";
		$this->descripcion->EditCustomAttributes = "";
		$this->descripcion->EditValue = $this->descripcion->CurrentValue;
		$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

		// precio
		$this->precio->EditAttrs["class"] = "form-control";
		$this->precio->EditCustomAttributes = "";
		$this->precio->EditValue = $this->precio->CurrentValue;
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

		// caracteristicas
		$this->caracteristicas->EditAttrs["class"] = "form-control";
		$this->caracteristicas->EditCustomAttributes = "";
		$this->caracteristicas->EditValue = $this->caracteristicas->CurrentValue;
		$this->caracteristicas->PlaceHolder = ew_RemoveHtml($this->caracteristicas->FldCaption());

		// garantia
		$this->garantia->EditAttrs["class"] = "form-control";
		$this->garantia->EditCustomAttributes = "";
		$this->garantia->EditValue = $this->garantia->CurrentValue;
		$this->garantia->PlaceHolder = ew_RemoveHtml($this->garantia->FldCaption());

		// referencia
		$this->referencia->EditAttrs["class"] = "form-control";
		$this->referencia->EditCustomAttributes = "";
		$this->referencia->EditValue = $this->referencia->CurrentValue;
		$this->referencia->PlaceHolder = ew_RemoveHtml($this->referencia->FldCaption());

		// codlinea
		$this->codlinea->EditAttrs["class"] = "form-control";
		$this->codlinea->EditCustomAttributes = "";

		// calificacion
		$this->calificacion->EditAttrs["class"] = "form-control";
		$this->calificacion->EditCustomAttributes = "";
		$this->calificacion->EditValue = $this->calificacion->Options(TRUE);

		// fecultimamod
		// usuarioultimamod
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
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
					if ($this->foto2->Exportable) $Doc->ExportCaption($this->foto2);
					if ($this->foto3->Exportable) $Doc->ExportCaption($this->foto3);
					if ($this->caracteristicas->Exportable) $Doc->ExportCaption($this->caracteristicas);
					if ($this->garantia->Exportable) $Doc->ExportCaption($this->garantia);
					if ($this->referencia->Exportable) $Doc->ExportCaption($this->referencia);
					if ($this->codlinea->Exportable) $Doc->ExportCaption($this->codlinea);
					if ($this->calificacion->Exportable) $Doc->ExportCaption($this->calificacion);
					if ($this->fecultimamod->Exportable) $Doc->ExportCaption($this->fecultimamod);
					if ($this->usuarioultimamod->Exportable) $Doc->ExportCaption($this->usuarioultimamod);
				} else {
					if ($this->codarticulo->Exportable) $Doc->ExportCaption($this->codarticulo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
					if ($this->foto2->Exportable) $Doc->ExportCaption($this->foto2);
					if ($this->foto3->Exportable) $Doc->ExportCaption($this->foto3);
					if ($this->caracteristicas->Exportable) $Doc->ExportCaption($this->caracteristicas);
					if ($this->garantia->Exportable) $Doc->ExportCaption($this->garantia);
					if ($this->referencia->Exportable) $Doc->ExportCaption($this->referencia);
					if ($this->codlinea->Exportable) $Doc->ExportCaption($this->codlinea);
					if ($this->calificacion->Exportable) $Doc->ExportCaption($this->calificacion);
					if ($this->fecultimamod->Exportable) $Doc->ExportCaption($this->fecultimamod);
					if ($this->usuarioultimamod->Exportable) $Doc->ExportCaption($this->usuarioultimamod);
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
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->precio->Exportable) $Doc->ExportField($this->precio);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
						if ($this->foto2->Exportable) $Doc->ExportField($this->foto2);
						if ($this->foto3->Exportable) $Doc->ExportField($this->foto3);
						if ($this->caracteristicas->Exportable) $Doc->ExportField($this->caracteristicas);
						if ($this->garantia->Exportable) $Doc->ExportField($this->garantia);
						if ($this->referencia->Exportable) $Doc->ExportField($this->referencia);
						if ($this->codlinea->Exportable) $Doc->ExportField($this->codlinea);
						if ($this->calificacion->Exportable) $Doc->ExportField($this->calificacion);
						if ($this->fecultimamod->Exportable) $Doc->ExportField($this->fecultimamod);
						if ($this->usuarioultimamod->Exportable) $Doc->ExportField($this->usuarioultimamod);
					} else {
						if ($this->codarticulo->Exportable) $Doc->ExportField($this->codarticulo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->precio->Exportable) $Doc->ExportField($this->precio);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
						if ($this->foto2->Exportable) $Doc->ExportField($this->foto2);
						if ($this->foto3->Exportable) $Doc->ExportField($this->foto3);
						if ($this->caracteristicas->Exportable) $Doc->ExportField($this->caracteristicas);
						if ($this->garantia->Exportable) $Doc->ExportField($this->garantia);
						if ($this->referencia->Exportable) $Doc->ExportField($this->referencia);
						if ($this->codlinea->Exportable) $Doc->ExportField($this->codlinea);
						if ($this->calificacion->Exportable) $Doc->ExportField($this->calificacion);
						if ($this->fecultimamod->Exportable) $Doc->ExportField($this->fecultimamod);
						if ($this->usuarioultimamod->Exportable) $Doc->ExportField($this->usuarioultimamod);
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
