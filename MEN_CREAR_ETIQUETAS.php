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

$MEN_CREAR_ETIQUETAS_php = NULL; // Initialize page object first

class cMEN_CREAR_ETIQUETAS_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'MEN_CREAR_ETIQUETAS.php';

	// Page object name
	var $PageObjName = 'MEN_CREAR_ETIQUETAS_php';

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
			define("EW_TABLE_NAME", 'MEN_CREAR_ETIQUETAS.php', TRUE);

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
		$Breadcrumb->Add("custom", "MEN_CREAR_ETIQUETAS_php", $url, "", "MEN_CREAR_ETIQUETAS_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($MEN_CREAR_ETIQUETAS_php)) $MEN_CREAR_ETIQUETAS_php = new cMEN_CREAR_ETIQUETAS_php();

// Page init
$MEN_CREAR_ETIQUETAS_php->Page_Init();

// Page main
$MEN_CREAR_ETIQUETAS_php->Page_Main();

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
    <link rel="stylesheet" href="lib/font-awesome/css/font-awesome.min.css"/>
	<style>
		#container{
			max-width: 480px;
			width: 100%;
			margin: 20px auto;
		}
		#demo_box{
			width: 480px;
			z-index: -1;
		}
		.fa{
			font-size: 40px;
			line-height: 70px;
		}
		.fa-bars{
			color:#000000;
		}
		pre{
			font-family: Consolas,Liberation Mono,Courier,monospace;
			font-size: 13px;
		}
		@media screen and (orientation: portrait){
			pre{
				overflow-x: scroll;
			}
		}
	</style>
	<table width="600" border="0" align="center">
		 <tr>
			<td bgcolor="#CAF5FB">
			 <div  id="demo_box" style="background-color:#2DEEEE;border-radius:50%;padding:15px;width:100px;height:100px;text-align: center;margin:70px auto;">
			<span class="pop_ctrl"><i class="fa fa-bars"></i></span>
			<ul id="demo_ul" style="z-index:10;font-size:10px;">
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/bank.png"> </i>
					   <b><font color="black">CUENTAS BANCARIAS</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/users.png"> </i>
					   <b><font color="black">USUARIOS</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/home.png"> </i>
					   <b><font color="black">COMPAÑIA</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/trending-neutral.png"> </i>
					   <b><font color="black">LINEA</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/articulom.png"> </i>
					   <b><font color="black">ARTICULO</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/crosshairs-gps.png"> </i>
					   <b><font color="black">UBICACIONES</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/emoticon-happy.png"> </i>
					   <b><font color="black">CLIENTES</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/proveedores.png"> </i>
					   <b><font color="black">PROVEEDORES</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/justificaciones.png"> </i>
					   <b><font color="black">JUSTIFICACIONES</font></b>
					</div>
				</li>
				<li class="demo_li"><a href="http://www.yahoo.com">
					<div>
				       <br><i><img src="phpimages/etiquetas.png"> </i>
					   <b><font color="black">ETIQUETAS</font></b>
					</div>
				</li>
			</ul>
		</div>
		   </td>
			<td bgcolor="#A9FCD9">
			   <div id="menu_icon2" class="" style="border-radius: 50%;position: relative; width: 100px; height: 100px; background-color:#0FEC83;margin:70px auto;">
				  <div class="wcircle-icon">
					 <div style="padding: 15px;width: 50px;height: 50px;text-align: center;font-size:10px;">
						<img src="phpimages/imprimir.png" alt="HTML tutorial">				
					 </div>
				  </div>
				  <div class="wcircle-menu" style="display:none;">
					 <div class="wcircle-menu-item" style="background-color:#7AFC91;border-radius:50%;padding:15px;width:100px;height:100px;text-align: center;font-size:10px;">
						<a href="SIS_GENERADORCB.php">
						<img src="phpimages/articulo.png" alt="HTML tutorial">				  </a>				
					 </div>
					 <div class="wcircle-menu-item" style="background-color:#7AFC91;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
						<a href="SIS_GENERADORUBICACIONESCB.php">
						<img src="phpimages/ubicacion.png" alt="HTML tutorial">				  </a>				
					 </div>
				  </div>
			   </div>
		   </td>
		 </tr>
		 <tr>
			<td bgcolor="#F5FCC5">
			   <div id="menu_icon3" style="border-radius: 50%;position: relative; width: 100px; height: 100px; background-color:#FFFF3E;margin:70px auto;">
				  <div class="wcircle-icon">
					 <div style="padding: 15px;width: 50px;height: 50px;text-align: center;font-size:10px;">
						<img src="phpimages/admininventario.png" alt="HTML tutorial">				
					 </div>
				  </div>
				  <div class="wcircle-menu" style="display:none;">
					 <div class="wcircle-menu-item" style="background-color:#f1fd55;border-radius:50%;padding:15px;width:100px;height:100px;text-align: center;font-size:10px;">
						<a href="SIS_KARDEXlist.php">
						<img src="phpimages/kardex.png" alt="HTML tutorial">				  </a>				
					 </div>
					 <div class="wcircle-menu-item" style="background-color:#f1fd55;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
						<a href="VIW_SALIDA_INVENTARIOlist.php">
						<img src="phpimages/salidainv.png" alt="HTML tutorial">				  </a>				
					 </div>
					 <div class="wcircle-menu-item" style="background-color:#f1fd55;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
						<a href="VIW_ENTRADA_INVENTARIOlist.php">
						<img src="phpimages/entradainv.png" alt="HTML tutorial">				  </a>				
					 </div>
					 <div class="wcircle-menu-item" style="background-color:#f1fd55;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
						<a href="SIS_LEERARTICULO.php">
						<img src="phpimages/entra_ubicar.png" alt="HTML tutorial"></a>
					 </div>
				  </div>
			   </div>
		   </td>
			<td bgcolor="#F0CBFA">
			   <div id="menu_icon4" class="" style="border-radius: 50%;position: relative; width: 100px; height: 100px; background-color:#DA169F;margin:70px auto;">
			   <div class="wcircle-icon">
				  <div style="padding: 15px;width: 50px;height: 50px;text-align: center;font-size:10px;">
					 <img src="phpimages/proventa.png" alt="HTML tutorial">				
				  </div>
			   </div>
			   <div class="wcircle-menu" style="display:none;">
				  <div class="wcircle-menu-item" style="background-color:#F150D5;border-radius:50%;padding:15px;width:100px;height:100px;text-align: center;font-size:10px;">
					 <a href="SIS_FACTURAlist.php">
					 <img src="phpimages/genfactura.png" alt="HTML tutorial">				  </a>				
				  </div>
				  <div class="wcircle-menu-item" style="background-color:#F150D5;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
					 <a href="SIS_LECTURAARTICULOlist.php?txtlectura=&select1=1&descuento=0&transporte=0">
					 <img src="phpimages/genpedido.png" alt="HTML tutorial">				  </a>				
				  </div>
				  <div class="wcircle-menu-item" style="background-color:#F150D5;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
					 <a href="Consulta_Articuloslist.php">
					 <img src="phpimages/consultar.png" alt="HTML tutorial">				  </a>				
				  </div>
			   </div>
			 </div>
		   </td>
		 </tr>
		 <tr>
			<td bgcolor="#8EF775">
			   <div id="menu_icon5" class="" style="border-radius: 50%;position: relative; width: 100px; height: 100px; background-color:#1EB009;margin:70px auto;">
			   <div class="wcircle-icon ">
				  <div style="padding: 15px;width: 50px;height: 50px;text-align: center;font-size:10px;">
					 <img src="phpimages/profinancieros.png" alt="HTML tutorial">				
				  </div>
			   </div>
			   <div class="wcircle-menu " style="display:none;">
				  <div class="wcircle-menu-item" style="background-color:#64de3f;border-radius:50%;padding:15px;width:100px;height:100px;text-align: center;font-size:10px;">
					 <a href="SIS_ABONOSlist.php">
					 <img src="phpimages/abonos.png" alt="HTML tutorial">				  </a>				
				  </div>
				  <div class="wcircle-menu-item" style="background-color:#64de3f;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
					 <a href="SIS_INGRESOSlist.php">
					 <img src="phpimages/ingresos.png" alt="HTML tutorial">				  </a>				
				  </div>
				  <div class="wcircle-menu-item" style="background-color:#64de3f;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
					 <a href="SIS_PAGOSlist.php">
					 <img src="phpimages/pagos.png" alt="HTML tutorial">				  </a>				
				  </div>
			   </div>
			 </div>
		   </td>
			<td bgcolor="#8EBFF4">
			   <div id="menu_icon6" class="" style="border-radius: 50%;position: relative; width: 100px; height: 100px; background-color:#166DAD;margin:70px auto;">
			   <div class="wcircle-icon">
				  <div style="padding: 15px;width: 50px;height: 50px;text-align: center;font-size:10px;">
					 <img src="phpimages/reportes.png" alt="HTML tutorial">				
				  </div>
			   </div>
			   <div class="wcircle-menu" style="display:none;">
				  <div class="wcircle-menu-item" style="background-color:#4285f4;border-radius:50%;padding:15px;width:100px;height:100px;text-align: center;font-size:10px;">
					 <a href="SIS_GENERAR_REPORTE_TOP.php">
					 <img src="phpimages/top10.png" alt="HTML tutorial">				  </a>				
				  </div>
				  <div class="wcircle-menu-item" style="background-color:#4285f4;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
					 <a href="VIW_HISTORIAL_ARTICULOSrpt.php#cht_Ventas">
					 <img src="phpimages/ventas.png" alt="HTML tutorial">				  </a>				
				  </div>
				  <div class="wcircle-menu-item" style="background-color:#4285f4;border-radius:50%;padding:15px;width:100px;height:100px;font-size:10px;">
					 <a href="SIS_REPORTE_INGRESOSYPAGOS.php">
					 <img src="phpimages/ingresosypagos.png" alt="HTML tutorial">				  </a>				
				  </div>
			   </div>
			 </div>
		   </td>
		 </tr>
	  </table>
	  <script type="text/javascript" src="WCircleMenu/jQuery.WSquareMenu.js"></script>
	  <script src="menu_pop/jquery.popmenu.js"></script>
	  <script type="text/javascript">
		 $(document).ready(function(){
		 	$('#menu_icon').WCircleMenu({
		 		width: '50px',
		 		height: '50px',
		 		angle_start : 0,//-Math.PI/2,px auto;
		 		delay: 50,
		 		distance: 135,
		 		angle_interval: Math.PI/4,
		 		easingFuncShow:"easeOutBack",
		 		easingFuncHide:"easeInBack",
		 		step:35,
		 		itemRotation:360,
		 	});
		 	$('.icons').off('click').on('click',function(){
		 		console.log($(this).text());
		 	});
		 	$('#openWCM').off('click').on('click',function(){
		 		$('#menu_icon').WCircleMenu('open');
		 	});
		 	$('#closeWCM').off('click').on('click',function(){
		 		$('#menu_icon').WCircleMenu('close');
		 	});
		 });
		 $(document).ready(function(){
		 	$('#menu_icon2').WCircleMenu({
		 		width: '50px',
		 		height: '50px',
		 		angle_start : 0,//-Math.PI/2,px auto;
		 		delay: 50,
		 		distance: 135,
		 		angle_interval: Math.PI/4,
		 		easingFuncShow:"easeOutBack",
		 		easingFuncHide:"easeInBack",
		 		step:35,
		 		itemRotation:360,
		 	});
		 	$('.icons').off('click').on('click',function(){
		 		console.log($(this).text());
		 	});
		 	$('#openWCM').off('click').on('click',function(){
		 		$('#menu_icon2').WCircleMenu('open');
		 	});
		 	$('#closeWCM').off('click').on('click',function(){
		 		$('#menu_icon2').WCircleMenu('close');
		 	});
		 });
		 			$(document).ready(function(){
		 	$('#menu_icon3').WCircleMenu({
		 		width: '50px',
		 		height: '50px',
		 		angle_start : 0,//-Math.PI/2,px auto;
		 		delay: 50,
		 		distance: 135,
		 		angle_interval: Math.PI/4,
		 		easingFuncShow:"easeOutBack",
		 		easingFuncHide:"easeInBack",
		 		step:35,
		 		itemRotation:360,
		 	});
		 	$('.icons').off('click').on('click',function(){
		 		console.log($(this).text());
		 	});
		 	$('#openWCM').off('click').on('click',function(){
		 		$('#menu_icon3').WCircleMenu('open');
		 	});
		 	$('#closeWCM').off('click').on('click',function(){
		 		$('#menu_icon3').WCircleMenu('close');
		 	});
		 });
		 $(document).ready(function(){
		 	$('#menu_icon4').WCircleMenu({
		 		width: '50px',
		 		height: '50px',
		 		angle_start : 0,//-Math.PI/2,px auto;
		 		delay: 50,
		 		distance: 135,
		 		angle_interval: Math.PI/4,
		 		easingFuncShow:"easeOutBack",
		 		easingFuncHide:"easeInBack",
		 		step:35,
		 		itemRotation:360,
		 	});
		 	$('.icons').off('click').on('click',function(){
		 		console.log($(this).text());
		 	});
		 	$('#openWCM').off('click').on('click',function(){
		 		$('#menu_icon4').WCircleMenu('open');
		 	});
		 	$('#closeWCM').off('click').on('click',function(){
		 		$('#menu_icon4').WCircleMenu('close');
		 	});
		 });
		 $(document).ready(function(){
		 	$('#menu_icon5').WCircleMenu({
		 		width: '50px',
		 		height: '50px',
		 		angle_start : 0,//-Math.PI/2,px auto;
		 		delay: 50,
		 		distance: 135,
		 		angle_interval: Math.PI/4,
		 		easingFuncShow:"easeOutBack",
		 		easingFuncHide:"easeInBack",
		 		step:35,
		 		itemRotation:360,
		 	});
		 	$('.icons').off('click').on('click',function(){
		 		console.log($(this).text());
		 	});
		 	$('#openWCM').off('click').on('click',function(){
		 		$('#menu_icon5').WCircleMenu('open');
		 	});
		 	$('#closeWCM').off('click').on('click',function(){
		 		$('#menu_icon5').WCircleMenu('close');
		 	});
		 });
		 $(document).ready(function(){
		 	$('#menu_icon6').WCircleMenu({
		 		width: '50px',
		 		height: '50px',
		 		angle_start : 0,//-Math.PI/2,px auto;
		 		delay: 50,
		 		distance: 135,
		 		angle_interval: Math.PI/4,
		 		easingFuncShow:"easeOutBack",
		 		easingFuncHide:"easeInBack",
		 		step:35,
		 		itemRotation:360,
		 	});
		 	$('.icons').off('click').on('click',function(){
		 		console.log($(this).text());
		 	});
		 	$('#openWCM').off('click').on('click',function(){
		 		$('#menu_icon6').WCircleMenu('open');
		 	});
		 	$('#closeWCM').off('click').on('click',function(){
		 		$('#menu_icon6').WCircleMenu('close');
		 	});
		 });
		$(function(){
			$('#demo_box').popmenu({'background':'#16F8F2',
			'focusColor':'#00E1E1',
			'borderRadius':'10px', 
			'top': '70', 
			'left': '-40',
			'color':'#1265fe'});
		});
	  </script>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$MEN_CREAR_ETIQUETAS_php->Page_Terminate();
?>
