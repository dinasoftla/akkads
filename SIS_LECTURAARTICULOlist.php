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

$SIS_LECTURAARTICULOlist_php = NULL; // Initialize page object first

class cSIS_LECTURAARTICULOlist_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_LECTURAARTICULOlist.php';

	// Page object name
	var $PageObjName = 'SIS_LECTURAARTICULOlist_php';

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
			define("EW_TABLE_NAME", 'SIS_LECTURAARTICULOlist.php', TRUE);

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
		$Breadcrumb->Add("custom", "SIS_LECTURAARTICULOlist_php", $url, "", "SIS_LECTURAARTICULOlist_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_LECTURAARTICULOlist_php)) $SIS_LECTURAARTICULOlist_php = new cSIS_LECTURAARTICULOlist_php();

// Page init
$SIS_LECTURAARTICULOlist_php->Page_Init();

// Page main
$SIS_LECTURAARTICULOlist_php->Page_Main();

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
	<?php
	$query = "EXEC SP_CONSULTAR_CLIENTES";
	$dsclientes = ew_Execute($query);

   //Carga los clientes en el combo
	echo '<form name="form" action="SIS_LECTURAARTICULOlist.php">
				<P><b>Codigo Articulo </b>
				<input type="text" name="txtlectura" id="txtlectura" value="" size="30"
				maxlength="20" class="form-control" onchange = "document.form.submit();">
				<P><b>Cliente    </b><select name="select1" onchange="document.form.submit();">';
			   	foreach ($dsclientes as $rs => $value) {
			   		if ($_GET["select1"] == $value["codcliente"])
			   			{
			   				echo "<option value='" . $value["codcliente"] ."' selected>". $value["nombre"] . "</option>";
			   			}
			   			else
			   			{
			   				echo "<option value='" . $value["codcliente"] ."'>". $value["nombre"]. "</option>";
			   			}
			   }
		   echo '</select>
<button type="button" title="Add&nbsp;Cliente" onclick="location.href=\'SIS_CLIENTEadd.php\'" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_codcliente"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><span data-phrase="AddLink" class="glyphicon glyphicon-plus ewIcon" data-caption="Add"></span>&nbsp;Cliente</span></button>
	&nbsp';
		   if (isset($_GET["descuento"]))
		   {
			   echo '<P><b>Descuento    </b>
			   <input type="text" data-field="descuento" name="descuento" id="descuento" size="30" maxlength="20" placeholder="descuento" value="';
			   echo $_GET["descuento"];
			   echo'" class="form-control" onchange = "document.form.submit();">';
		   }
		   else
		   {
			   echo '<P><b>Descuento    </b>
			   <input type="text" data-field="descuento" name="descuento" id="descuento" size="30" maxlength="20" placeholder="descuento"
			   value="0" class="form-control" onchange = "document.form.submit();">';
		   }
		   if (isset($_GET["transporte"]))
		   {
			   echo '<P><b>Transporte    </b>
			   <input type="text" data-field="transporte" name="transporte"
			   id="transporte" size="30" maxlength="20" placeholder="transporte" value="';
			   echo $_GET["transporte"];
			   echo '" class="form-control" onchange = "document.form.submit();">';
		   }
		   else
		   {
			   echo '<P><b>Transporte    </b>
			   <input type="text" data-field="transporte" name="transporte" id="transporte" size="30" maxlength="20"
			   placeholder="transporte" value="0" class="form-control" onchange = "document.form.submit();">';
		   }
?>
<style>
table, th, td {
	border-bottom: 1px solid #ddd;
}
th, td {
	padding: 5px;
	text-align: left;
}
table#t01 {
	width: 100%;    
	background-color: #F3F3F3;
}
</style>
<meta name="generator" content="PHPMaker v12.0.5">
</head>
<table style="width:100%">
<table id="t01">
  <tr>
	<th>Cod Articulo</th>
	<th>Descripcion</th> 
	<th>Cantidad</th>
	<th>Precio</th>
	<th>Total</th>
  </tr>
<?php
	if (isset($_GET["txtlectura"]))
	{
		if (strlen($_GET["txtlectura"]) == 0)
		{
		}else
		{

			//echo "SP ";
			$lectura = $_GET["txtlectura"];
			$query = "EXEC SP_INSERTARDETALLEPEDIDOACTUAL '". $lectura . "', ". "2";
			ew_Execute($query);
			echo $query;
		}
			$query = "EXEC SP_CONSULTARRDETALLEPEDIDOACTUAL";
			$detallepedido = ew_Execute($query);
			$subtotal = 0;
			echo $query;
			foreach ($detallepedido as $rs => $value) {
				echo '<tr>
					  <td align= "left">';
							echo $value["codarticulo"]. "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo $value["descripcion"]. "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo $value["cantidad"]. "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo $value["precio"]. "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo $value["total"]. "  ";
				echo '</td>';
				echo '</tr>';
				$subtotal = $subtotal + $value["total"];
			}
				echo '<tr>
					  <td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "left">';
							echo "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo "  ";
				echo '</td>';
				echo '<td align= "center">';
							echo '<b>SUBTOTAL <IMG SRC="/akkads/phpimages/simbolocolon.jpg"></b>';
				echo '</td>';
				echo '<td align= "center">';
							echo $subtotal. "  ";
				echo '</td>';
				echo '</tr>';
	}
	else
	{
	  echo "Variable nulla";
	}
?>
</table>
<!-- footer -->
<?php
	if (isset($_GET["select1"]))
			{
  	 	$LINKURL = "SIS_CREARPEDIDOACTUAL.php?select1=" . $_GET["select1"] . "&" . "descuento=" . $_GET["descuento"] . "&" . "transporte=". $_GET["transporte"];
		$botongenerarfactura = str_replace("%LINKURL%"
			, $LINKURL
			, '<button onclick="location.href=\'%LINKURL%\'" type="button">CREAR PEDIDO</button>');
			$parrafo = "<P><div>
				 		    <pre>".$botongenerarfactura." </pre>
				 	       </div>"; 
			  echo $parrafo;
		}
?>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$SIS_LECTURAARTICULOlist_php->Page_Terminate();
?>
