<?php
if (!isset($EWR_RELATIVE_PATH)) $EWR_RELATIVE_PATH = "";
if (!isset($EWR_ERROR_FN)) $EWR_ERROR_FN = "ewr_ErrorFn";
?>
<?php include_once $EWR_RELATIVE_PATH . "adodb5/adodb.inc.php" ?>
<?php

/**
 * PHP Report Maker 8 database helper class
 */

class crSISTEMA_db {

	// Debug
	var $Debug = FALSE;

	// Language
	var $Language;

	// Database connection info
	// ADODB (Access/SQL Server)

	var $CodePage = 0; // Code page

	// Database
	var $StartQuote = "[";
	var $EndQuote = "]";
	var $Connection;

	// Constructor
	function __construct($langfolder = "", $langid = "", $info = NULL) {
		$args = func_get_args();
		if (count($args) == 1 && is_array($args[0])) { // $info only
			$langfolder = "";
			$langid = "";
			$info = $args[0];
		}

		// Debug
		if (defined("EWR_DEBUG_ENABLED"))
			$this->Debug = EWR_DEBUG_ENABLED;

		// Open connection
		if (!isset($this->Connection)) $this->Connection = $this->Connect($info);

		// Set up language object
		if ($langfolder <> "")
			$this->Language = new crLanguage($langfolder, $langid);
		elseif (isset($GLOBALS["ReportLanguage"]))
			$this->Language = &$GLOBALS["ReportLanguage"];
	}

	// Connect to database
	function &Connect($info = NULL) {
		if (!(strtolower(substr(PHP_OS, 0, 3)) === 'win')) // Non Windows platform
			die("Microsoft Access or SQL Server is supported on Windows server only.");
		$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
		$GLOBALS["ADODB_COUNTRECS"] = FALSE;
		$conn = ADONewConnection('ado_mssql');
		$conn->debug = $this->Debug;
		$conn->debug_echo = FALSE;
		if (!$info) {
			$info = "Provider=SQLOLEDB;Persist Security Info=False;Data Source=DESKTOP-FN7FSTU\\SQLEXPRESS;Initial Catalog=SISTEMA;User Id=sa;Password=Sistema1032"; // ADO connection string
		}
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		if ($this->CodePage > 0)
		$conn->charPage = $this->CodePage;
		$conn->Connect($info, FALSE, FALSE);

		// Set date format
		$conn->Execute("SET DATEFORMAT ymd");
		$conn->raiseErrorFn = '';
		return $conn;
	}

	// Quote name
	private function QuotedName($Name) {
		$Name = str_replace($this->EndQuote, $this->EndQuote . $this->EndQuote, $Name);
		return $this->StartQuote . $Name . $this->EndQuote;
	}

	// Executes the query, and returns the row(s) as JSON
	function ExecuteJson($SQL, $FirstOnly = TRUE) {
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = ($FirstOnly) ? array($rs->fields) : $rs->GetRows();
			$rs->Close();
			return json_encode($res);
		}
		return "false";
	}

	// Execute UPDATE, INSERT, or DELETE statements
	function Execute($SQL, $fn = NULL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		if (is_callable($fn) && $rs) {
			while (!$rs->EOF) {
				$fn($rs->fields);
				$rs->MoveNext();
			}
			$rs->MoveFirst();
		}
		return $rs;
	}

	// Executes the query, and returns the first column of the first row
	function ExecuteScalar($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = $rs->fields[0];
			$rs->Close();
		}
		return $res;
	}

	// Executes the query, and returns the first row
	function ExecuteRow($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF) {
			$res = $rs->fields;
			$rs->Close();
		}
		return $res;
	}

	// Load recordset
	function &LoadRecordset($SQL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		return $rs;
	}

	// Table CSS class name
	var $TableClass = "table table-bordered table-striped ewDbTable";

	// Get result in HTML table
	// options: fieldcaption(bool|array), horizontal(bool), tablename(string|array), tableclass(string)
	function ExecuteHtml($SQL, $options = NULL) {
		$ar = is_array($options) ? $options : array();
		$horizontal = (array_key_exists("horizontal", $ar) && $ar["horizontal"]);
		$rs = $this->LoadRecordset($SQL);
		if (!$rs || $rs->EOF || $rs->FieldCount() < 1)
			return "";
		$html = "";
		$class = (array_key_exists("tableclass", $ar) && $ar["tableclass"]) ? $ar["tableclass"] : $this->TableClass;
		if ($rs->RecordCount() > 1 || $horizontal) { // Horizontal table
			$cnt = $rs->FieldCount();
			$html = "<table class=\"" . $class . "\">";
			$html .= "<thead><tr>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key))
					$html .= "<th>" . $this->GetFieldCaption($key, $ar) . "</th>";
			}
			$html .= "</tr></thead>";
			$html .= "<tbody>";
			$rowcnt = 0;
			while (!$rs->EOF) {
				$html .= "<tr>";
				$row = &$rs->fields;
				foreach ($row as $key => $value) {
					if (!is_numeric($key))
						$html .= "<td>" . $value . "</td>";
				}
				$html .= "</tr>";
				$rs->MoveNext();
			}
			$html .= "</tbody></table>";
		} else { // Single row, vertical table
			$html = "<table class=\"" . $class . "\"><tbody>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key)) {
					$html .= "<tr>";
					$html .= "<td>" . $this->GetFieldCaption($key, $ar) . "</td>";
					$html .= "<td>" . $value . "</td></tr>";
				}
			}
			$html .= "</tbody></table>";
		}
		return $html;
	}

	function GetFieldCaption($key, $ar) {
		$caption = "";
		if (!is_array($ar))
			return $key;
		$tablename = @$ar["tablename"];
		$usecaption = (array_key_exists("fieldcaption", $ar) && $ar["fieldcaption"]);
		if ($usecaption) {
			if (is_array($ar["fieldcaption"])) {
				$caption = @$ar["fieldcaption"][$key];
			} elseif (isset($this->Language)) {
				if (is_array($tablename)) {
					foreach ($tablename as $tbl) {
						$caption = @$this->Language->FieldPhrase($tbl, $key, "FldCaption");
						if ($caption <> "")
							break;
					}
				} elseif ($tablename <> "") {
					$caption = @$this->Language->FieldPhrase($tablename, $key, "FldCaption");
				}
			}
		}
		return ($caption <> "") ? $caption : $key;
	}
}

// Connection/Query error handler
if (!function_exists("ewr_ErrorFn")) {

	// Connection/Query error handler
	function ewr_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
		if ($ErrorType == 'CONNECT') {
			if ($DbType == "ado_access" || $DbType == "ado_mssql") {
				$msg = "Failed to connect to database. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
			}
		} elseif ($ErrorType == 'EXECUTE') {
			if (defined("EWR_DEBUG_ENABLED") && EWR_DEBUG_ENABLED) {
				$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to execute SQL. Error: " . $ErrorMsg;
			}
		}
		if (function_exists("ewr_AddMessage") && defined("EWR_SESSION_FAILURE_MESSAGE"))
			ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $msg);
		else
			echo "<div class=\"alert alert-danger ewError\">" . $msg . "</div>";
	}
}
?>
