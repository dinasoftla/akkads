<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$SIS_REPORTE_INGRESOSYPAGOS_php = NULL; // Initialize page object first

class cSIS_REPORTE_INGRESOSYPAGOS_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_REPORTE_INGRESOSYPAGOS.php';

	// Page object name
	var $PageObjName = 'SIS_REPORTE_INGRESOSYPAGOS_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'SIS_REPORTE_INGRESOSYPAGOS.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

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

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
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

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "SIS_REPORTE_INGRESOSYPAGOS_php", $url, "", "SIS_REPORTE_INGRESOSYPAGOS_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_REPORTE_INGRESOSYPAGOS_php)) $SIS_REPORTE_INGRESOSYPAGOS_php = new cSIS_REPORTE_INGRESOSYPAGOS_php();

// Page init
$SIS_REPORTE_INGRESOSYPAGOS_php->Page_Init();

// Page main
$SIS_REPORTE_INGRESOSYPAGOS_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<link href="jscalendarjquery/css/jquery.datepick.css" rel="stylesheet">
<script src="jscalendarjquery/js/jquery.plugin.min.js"></script>
<script src="jscalendarjquery/js/jquery.datepick.js"></script>
<script>
$(function() {
	$('#fecha1').datepick();
	$('#fecha2').datepick();
	$('#sv2_fecha').datepick();
});

function showDate(date) {
	alert('The date chosen is ' + date);
}
</script>
<style>
table, th, td {
border-bottom: 1px solid #ddd;
}
</style>
<table style="width:100%">
  <col>
  <tr>
	<th style="width:10%">FACTURA</th>
	<th style="width:20%">NOMBRE</th>
	<th style="width:40%">DESCRIPCION</th>
	<th style="width:30%">MONTO</th>
  </tr>
<?php
   if ((isset($_GET["fecha1"])) && (isset($_GET["fecha2"])))
   {
		echo "<form action='SIS_REPORTE_INGRESOSYPAGOS.php'>
			  <P><b>Rangos de Fecha entre:</b> <input value='" . $_GET["fecha1"] . "' type='text' name='fecha1' id='fecha1' size='10' class='form-control'>
			  y <input value='" . $_GET["fecha2"] . "' type='text' name='fecha2'id='fecha2' size='10' class='form-control'>
			  <input type='submit' value='Filtrar' class='form-control' name='btnfiltrar'>
			</form>";
			$query = "EXEC SP_GENERAR_REPORTE_INGRESOS '" . $_GET["fecha1"] . "','" . $_GET["fecha1"] . "'";
			$ingresos = ew_Execute($query);
				echo '<tr>
					  <td align= "left">';
							echo "INGRESOS";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '</tr>';

			//echo $query;
			$subtotal_ingresos = 0;
			$subtotal_pagos = 0;
			foreach ($ingresos as $rs => $value) {
				echo '<tr>
					  <td align= "left">';
							echo $value["factura"]. "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo $value["nombre"]. "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo $value["descripcion"]. "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo $value["monto"]. "  ";
				echo '</td>';
				echo '</tr>';
				$subtotal_ingresos = $subtotal_ingresos + $value["monto"];
			}
				echo '<tr>
					  <td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo '<b>TOTAL INGRESOS <IMG SRC="/akkads/phpimages/simbolocolon.jpg"></b>';
				echo '</td>';
				echo '<td align= "left">';
							echo "<b>".$subtotal_ingresos. "</b>  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '</tr>';
			$query = "EXEC SP_GENERAR_REPORTE_PAGOS '" . $_GET["fecha1"] . "','" . $_GET["fecha1"] . "'";
			$ingresos = ew_Execute($query);
				echo '<tr>
					  <td align= "left">';
							echo "PAGOS";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '</tr>';

			//echo $query;
			$subtotal = 0;
			foreach ($ingresos as $rs => $value) {
				echo '<tr>
					  <td align= "left">';
							echo $value["factura"]. "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo $value["nombre"]. "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo $value["descripcion"]. "  ";
				echo '</td>';
				echo '<td align= "right">';
							echo $value["monto"]. "  ";
				echo '</td>';
				echo '</tr>';
				$subtotal_pagos = $subtotal_pagos + $value["monto"];
			}
				echo '<tr>
					  <td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo '<b>TOTAL PAGOS <IMG SRC="/akkads/phpimages/simbolocolon.jpg"></b>';
				echo '</td>';
				echo '<td align= "right">';
						    echo "<b>".$subtotal_pagos. "</b>  ";
				echo '</td>';
				echo '<td align= "right">';
							echo "  ";
				echo '</td>';
				echo '</tr>';
	 echo "</table>";			
	 echo "<table>";
   	   echo '<td align= "right">';			
		  echo '<P><b>INGRESOS <IMG SRC="/akkads/phpimages/simbolocolon.jpg"></b>'; 
   			echo $subtotal_ingresos ;
   		  echo '<P><b>PAGOS <IMG SRC="/akkads/phpimages/simbolocolon.jpg"></b>'; 
   			echo $subtotal_pagos;
   		  echo '<P><b>TOTAL INGRESOS - PAGOS <IMG SRC="/akkads/phpimages/simbolocolon.jpg"></b>'; 
   			echo $subtotal_ingresos - $subtotal_pagos;
	   echo '</td>';
	 echo "</table>";
   }
   else
   { // Si no es post back no carga las fechas por defecto
		echo "<form action='SIS_REPORTE_INGRESOSYPAGOS.php'> 
			  <P><b>Rangos de Fecha entre:</b> <input type='text' name='fecha1' id='fecha1' size='10' class='form-control'>
			  y <input  type='text' name='fecha2'id='fecha2' size='10' class='form-control'>
			  <input type='submit' value='Filtrar' class='form-control'>
			</form>";
   }
?>
</table>
<P> <input type="button" value="IMPRIMIR REPORTE" onclick="window.print()">
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$SIS_REPORTE_INGRESOSYPAGOS_php->Page_Terminate();
?>
