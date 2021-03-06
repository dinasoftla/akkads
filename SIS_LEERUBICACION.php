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

$SIS_LEERUBICACION_php = NULL; // Initialize page object first

class cSIS_LEERUBICACION_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{583510BF-00E0-41A1-A4E7-AF2AC6016C3C}";

	// Table name
	var $TableName = 'SIS_LEERUBICACION.php';

	// Page object name
	var $PageObjName = 'SIS_LEERUBICACION_php';

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
			define("EW_TABLE_NAME", 'SIS_LEERUBICACION.php', TRUE);

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
		$Breadcrumb->Add("custom", "SIS_LEERUBICACION_php", $url, "", "SIS_LEERUBICACION_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($SIS_LEERUBICACION_php)) $SIS_LEERUBICACION_php = new cSIS_LEERUBICACION_php();

// Page init
$SIS_LEERUBICACION_php->Page_Init();

// Page main
$SIS_LEERUBICACION_php->Page_Main();

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
<script language="JavaScript">
javascript:window.history.forward(1);

function txtgetfocus(x) {
	x.style.background = "#04B404";
	x.style.color = "#04B404";
	document.getElementById("lblActivar").style.color = "#04B404";
	document.getElementById("lblActivar").innerHTML = "Listo para leer";
}

function txtlostfocus(x) {
	x.style.background = "#BDBDBD";
	x.style.color = "#BDBDBD";
	document.getElementById("lblActivar").style.color = "#BDBDBD";
	document.getElementById("lblActivar").innerHTML = "Activar";
}
</script> 
<?php
	   		$articulo = $_GET["txtarticulo"];
	   		$articulo = trim(str_replace("+",
	   						"",
	   						$articulo));
			$query = "EXEC SP_CONSULTAR_DETALLEARTICULO_INVENTARIO '" . $articulo . "'";
	   		$dsdetallearticulo = ew_Execute($query);
	   		$rows = 0;
	   		foreach ($dsdetallearticulo as $rs => $value) {
				$codarticulo = $value["codarticulo"];
				$ubicacionbd = $value["ubicacion"];
				$descripcion = $value["descripcion"];
				$canarticulo = $value["canarticulo"];
				$foto = $value["foto"];
				$rows = $rows + 1;
			}
			if ($rows == 0) //NO esta registrado en el inventario
			{
				$query = "EXEC SP_CONSULTAR_EXISTE_PRODUCTO '" . $articulo . "'";
	   			$dsexiste = ew_ExecuteScalar($query);
	   			if ($dsexiste == "1")//EXISTE EL ARTICULO
	   			{

	   					//echo "1";
	   					echo "<div style='text-align:center'>";

	   					//ASIGNA UBICACION
	   					 echo "<form name='form' action='SIS_ASIGNAR_UBICACION.php'> 
	   						   <Pre>El Articulo leido no tiene Ubicacion, a continuacion debe asignarle una ubicacion</Pre>";
	   						  echo '<center>
	   						 		<table>
	   						 		  <tr>
	   						 		    <td><p name="lblActivar" style="color:#BDBDBD;" id="lblActivar">Activar</p></td>
	   						 		    <td><img src="phpimages/barcode.png" alt="barcode"></td>
	   						 		  </tr>
	   						 		</table>
	   						 	    </center>';
	   						   echo '<input autocomplete="off" onfocusout="txtlostfocus(this)" onfocusin="txtgetfocus(this)"
				    		     type="password" name="txtubicacionleida" id="txtubicacionleida" value="" size="30"
				    		     maxlength="20" class="form-control" onchange = "document.form.submit();"
				    		     style= "background-color:#BDBDBD; color:#BDBDBD;">';
				    	 	   echo "<input type='hidden' name='txtarticulo' id='txtarticulo' value='". trim($_GET["txtarticulo"]) ."'
								size='30'	maxlength='20' class='form-control' onchange = 'document.form.submit();'>
							</form>";
					   echo "</div>";
	   			}
	   			else///NO EXISTE EL ARTICULO
	   			{

	   					//echo "2";
	   					echo "<div style='text-align:center'>";
	   					echo "<Pre>El Articulo no existe</Pre>";

	   					//CREA BOTON DE CONTINUAR
				       	echo '<form action="SIS_LEERARTICULO.php" method="get">
							      <input type="submit" value="Continuar" 
							         name="Submit" id="frm1_submit">
							   </form>';
					    echo "</div>";
	   			}
			}
			else // PREVIA DE ARTICULO PIDE UBICACION
			{
	   			$query = "EXEC SP_CONSULTAR_EXISTE_UBICACION_PRODUCTO '" . $articulo . "'";
	   			$dsexiste = ew_ExecuteScalar($query);
	   			if ($dsexiste == "1")//TIENE UBICACION
	   			{

	   				//echo "3";
	   				echo "<div style='text-align:center'>";
					echo "<form name='form' action='SIS_VALIDARUBICACION.php'>"; // VALIDA LA UBICACION E INSERTA EN INVENTARIO +
	   				   echo '<center>
	   						 	<table>
	   						 		 <tr>
	   						 		    <td><p name="lblActivar" style="color:#BDBDBD;" id="lblActivar">Activar</p></td>
	   						 		    <td><img src="phpimages/barcode.png" alt="barcode"></td>
	   						 		</tr>
	   						 	</table>
	   						</center>';
	   					echo '<input autocomplete="off" onfocusout="txtlostfocus(this)" onfocusin="txtgetfocus(this)"
				    	  		type="password" name="txtubicacionleida" id="txtubicacionleida" value="" size="30"
				    	  		maxlength="20" class="form-control" onchange = "document.form.submit();"
				    	  		style= "background-color:#BDBDBD; color:#BDBDBD;">';
				    	echo "<input type='hidden' name='ubicacionbd' id='ubicacionbd' value='". $ubicacionbd ."'
								size='30'	maxlength='20' class='form-control' onchange = 'document.form.submit();'>
							  <input type='hidden' name='txtarticulo' id='txtarticulo' value='". $codarticulo ."'
							  	size='30'	maxlength='20' class='form-control' onchange = 'document.form.submit();'>
						  </form>";
				    echo "</div>";
					echo "<P><PRE>Lea la ubicacion: " . $ubicacionbd . "</PRE>";
	   				echo "<center>
				   			<br><img class='ewImage img-thumbnail'
				   			data-pin-nopin='true'
				   			src='uploads/". $foto . "'/>
				   			<br><B>". $codarticulo . "</B>
				   			</center>";
	   			}
	   			else//ARTICULO SIN UBICACION - ASIGNAR UBICACION
	   			{

	   					//echo "4";
	   					echo "<form name='form' action='SIS_ASIGNAR_UBICACION.php'>";
	   					echo '<center>
	   						 	<table>
	   						 		 <tr>
	   						 		    <td><p name="lblActivar" style="color:#BDBDBD;" id="lblActivar">Activar</p></td>
	   						 		    <td><img src="phpimages/barcode.png" alt="barcode"></td>
	   						 		</tr>
	   						 	</table>
	   						</center>';
	   					echo "<Pre>El Articulo leido no tiene Ubicacion, a continuacion debe asignarle una ubicacion</Pre>";	
						echo "<input autofocus autocomplete='off' type='password' name='txtubicacionleida' id='txtubicacionleida' value=''
								size='30'	maxlength='20' class='form-control' onchange = 'document.form.submit();'>
							  <input type='hidden' name='txtarticulo' id='txtarticulo' value='". trim($_GET["txtarticulo"]) ."'
							    size='30'	maxlength='20' class='form-control' onchange = 'document.form.submit();'>
							 </form>";
	   			}
			}	
?>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$SIS_LEERUBICACION_php->Page_Terminate();
?>
