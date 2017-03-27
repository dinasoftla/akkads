<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "SIS_USUARIOSinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Page object name
	var $PageObjName = 'default';

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
			define("EW_PAGE_ID", 'default', TRUE);

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->AllowList(CurrentProjectID() . 'SIS_CLIENTE'))
		$this->Page_Terminate("SIS_CLIENTElist.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'SIS_LINEA'))
			$this->Page_Terminate("SIS_LINEAlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_ARTICULO'))
			$this->Page_Terminate("SIS_ARTICULOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_ARTICULO_INVENTARIO'))
			$this->Page_Terminate("SIS_ARTICULO_INVENTARIOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_KARDEX'))
			$this->Page_Terminate("SIS_KARDEXlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_USUARIOS'))
			$this->Page_Terminate("SIS_USUARIOSlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_ABONOS'))
			$this->Page_Terminate("SIS_ABONOSlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_UBICACIONES'))
			$this->Page_Terminate("SIS_UBICACIONESlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'Consulta Articulos'))
			$this->Page_Terminate("Consulta_Articuloslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'AuditTrail'))
			$this->Page_Terminate("AuditTraillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_COMPANIA'))
			$this->Page_Terminate("SIS_COMPANIAlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_FACTURA'))
			$this->Page_Terminate("SIS_FACTURAlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_FACTURA_DETALLE'))
			$this->Page_Terminate("SIS_FACTURA_DETALLElist.php");
		if ($Security->AllowList(CurrentProjectID() . 'FACTURAS'))
			$this->Page_Terminate("FACTURASlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_PEDIDO'))
			$this->Page_Terminate("SIS_PEDIDOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_PEDIDO_DETALLE'))
			$this->Page_Terminate("SIS_PEDIDO_DETALLElist.php");
		if ($Security->AllowList(CurrentProjectID() . 'VIW_DETALLEPEDIDO'))
			$this->Page_Terminate("VIW_DETALLEPEDIDOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_PEDIDO_ACTUAL'))
			$this->Page_Terminate("SIS_PEDIDO_ACTUALlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_LECTURAARTICULOlist.php'))
			$this->Page_Terminate("SIS_LECTURAARTICULOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_CREARPEDIDOACTUAL.php'))
			$this->Page_Terminate("SIS_CREARPEDIDOACTUAL.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_CUENTAS_BANCARIAS'))
			$this->Page_Terminate("SIS_CUENTAS_BANCARIASlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_ARTICULOBARCODElist.php'))
			$this->Page_Terminate("SIS_ARTICULOBARCODElist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_GENERADORCB.php'))
			$this->Page_Terminate("SIS_GENERADORCB.php");
		if ($Security->AllowList(CurrentProjectID() . 'VIW_CENTRODEIMPRESION'))
			$this->Page_Terminate("VIW_CENTRODEIMPRESIONlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_SELECCION_USUARIO'))
			$this->Page_Terminate("SIS_SELECCION_USUARIOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_CREARIMPRESIONACTUAL.php'))
			$this->Page_Terminate("SIS_CREARIMPRESIONACTUAL.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_ETIQUETAS'))
			$this->Page_Terminate("SIS_ETIQUETASlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_MANTENIMIENTOS.php'))
			$this->Page_Terminate("MEN_MANTENIMIENTOS.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_INVENTARIO.php'))
			$this->Page_Terminate("MEN_INVENTARIO.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_PROCESOS.php'))
			$this->Page_Terminate("MEN_PROCESOS.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_REPORTES.php'))
			$this->Page_Terminate("MEN_REPORTES.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_IMPRIMIR'))
			$this->Page_Terminate("MEN_IMPRIMIR");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_GENERADORUBICACIONESCB.php'))
			$this->Page_Terminate("SIS_GENERADORUBICACIONESCB.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_ASIGNAR_UBICACION.php'))
			$this->Page_Terminate("SIS_ASIGNAR_UBICACION.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_LEERARTICULO.php'))
			$this->Page_Terminate("SIS_LEERARTICULO.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_LEERUBICACION.php'))
			$this->Page_Terminate("SIS_LEERUBICACION.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_DETALLEARTICULO_LEER.php'))
			$this->Page_Terminate("SIS_DETALLEARTICULO_LEER.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_VALIDARUBICACION.php'))
			$this->Page_Terminate("SIS_VALIDARUBICACION.php");
		if ($Security->AllowList(CurrentProjectID() . 'PENDIENTES.txt'))
			$this->Page_Terminate("PENDIENTES.txt");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_INVENTARIO_ENTRADA.php'))
			$this->Page_Terminate("MEN_INVENTARIO_ENTRADA.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_INVENTARIO_SALIDA'))
			$this->Page_Terminate("MEN_INVENTARIO_SALIDA");
		if ($Security->AllowList(CurrentProjectID() . 'VIW_SALIDA_INVENTARIO'))
			$this->Page_Terminate("VIW_SALIDA_INVENTARIOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_JUSTIFICACIONES'))
			$this->Page_Terminate("SIS_JUSTIFICACIONESlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_MOVIMIENTO_INV.php'))
			$this->Page_Terminate("SIS_MOVIMIENTO_INV.php");
		if ($Security->AllowList(CurrentProjectID() . 'VIW_ENTRADA_INVENTARIO'))
			$this->Page_Terminate("VIW_ENTRADA_INVENTARIOlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_PAGOS'))
			$this->Page_Terminate("SIS_PAGOSlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_INGRESOS'))
			$this->Page_Terminate("SIS_INGRESOSlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_PROVEEDORES'))
			$this->Page_Terminate("SIS_PROVEEDORESlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_GENERAR_FACTURA.php'))
			$this->Page_Terminate("SIS_GENERAR_FACTURA.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_REPORTE_INGRESOSYPAGOS.php'))
			$this->Page_Terminate("SIS_REPORTE_INGRESOSYPAGOS.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_GENERAR_REPORTE_TOP.php'))
			$this->Page_Terminate("SIS_GENERAR_REPORTE_TOP.php");
		if ($Security->AllowList(CurrentProjectID() . 'VIW_HISTORIAL_ARTICULOS'))
			$this->Page_Terminate("VIW_HISTORIAL_ARTICULOSlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_PROCESOS_FINANCIEROS.php'))
			$this->Page_Terminate("MEN_PROCESOS_FINANCIEROS.php");
		if ($Security->AllowList(CurrentProjectID() . 'MENUPRINCIPAL.php'))
			$this->Page_Terminate("MENUPRINCIPAL.php");
		if ($Security->AllowList(CurrentProjectID() . 'MEN_CREAR_ETIQUETAS.php'))
			$this->Page_Terminate("MEN_CREAR_ETIQUETAS.php");
		if ($Security->AllowList(CurrentProjectID() . 'SIS_TAREAS'))
			$this->Page_Terminate("SIS_TAREASlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'VIW IMPRIMIR UBICACIONES'))
			$this->Page_Terminate("VIW_IMPRIMIR_UBICACIONESlist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage($Language->Phrase("NoPermission") . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
