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

$GeneradorCB_php = NULL; // Initialize page object first

class cGeneradorCB_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{63048E93-AC1C-4EAA-9EAD-41F8F2D9C88C}";

	// Table name
	var $TableName = 'GeneradorCB.php';

	// Page object name
	var $PageObjName = 'GeneradorCB_php';

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
			define("EW_TABLE_NAME", 'GeneradorCB.php', TRUE);

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
		$Breadcrumb->Add("custom", "GeneradorCB_php", $url, "", "GeneradorCB_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($GeneradorCB_php)) $GeneradorCB_php = new cGeneradorCB_php();

// Page init
$GeneradorCB_php->Page_Init();

// Page main
$GeneradorCB_php->Page_Main();

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
<html>
<head>
<style>
* {
color: #7F7F7F;
font-family: Arial, sans-serif;
font-size: 12px;
font-weight: normal;
}
#config {
overflow: auto;
margin-bottom: 10px;
}
.config {
float: left;
width: 200px;
height: 250px;
border: 1px solid #000;
margin-left: 10px;
}
.config .title {
font-weight: bold;
text-align: center;
}
.config .barcode2D,  #miscCanvas {
display: none;
}
#submit {
clear: both;
}
#barcodeTarget,  #canvasTarget {
margin-top: 20px;
}
</style>
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="jquery-barcode/jquery-barcode.js"></script>
<script type="text/javascript">

	  function generateBarcode(){
		var value = $("#barcodeValue").val();
		var btype = $("input[name=btype]:checked").val();
		var renderer = $("input[name=renderer]:checked").val();
		var quietZone = false;
		if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
		  quietZone = true;
		}
		var settings = {
barWidth: 1,
barHeight: 50,
moduleSize: 5,
showHRI: true,
addQuietZone: true,
marginHRI: 5,
bgColor: "#FFFFFF",
color: "#000000",
fontSize: 10,
output: "css",
posX: 0,
posY: 0
		};
		if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
		  value = {code:value, rect: true};
		}
		if (renderer == 'canvas'){
		  clearCanvas();
		  $("#barcodeTarget").hide();
		  $("#canvasTarget").show().barcode(value, btype, settings);
		} else {
		  $("#canvasTarget").hide();
		  $("#barcodeTarget").html("").show().barcode(value, btype, settings);
		}
	  }

	  function showConfig1D(){
		$('.config .barcode1D').show();
		$('.config .barcode2D').hide();
	  }

	  function showConfig2D(){
		$('.config .barcode1D').hide();
		$('.config .barcode2D').show();
	  }

	  function clearCanvas(){
		var canvas = $('#canvasTarget').get(0);
		var ctx = canvas.getContext('2d');
		ctx.lineWidth = 1;
		ctx.lineCap = 'butt';
		ctx.fillStyle = '#FFFFFF';
		ctx.strokeStyle  = '#000000';
		ctx.clearRect (0, 0, canvas.width, canvas.height);
		ctx.strokeRect (0, 0, canvas.width, canvas.height);
	  }
	  $(function(){
		$('input[name=btype]').click(function(){
		  if ($(this).attr('id') == 'datamatrix') showConfig2D(); else showConfig1D();
		});
		$('input[name=renderer]').click(function(){
		  if ($(this).attr('id') == 'canvas') $('#miscCanvas').show(); else $('#miscCanvas').hide();
		});
		generateBarcode();
	  });
	</script>
<title>jQuery Barcode Plugin Examples</title>
<meta name="generator" content="PHPMaker v12.0.5">
</head>
<body>
<div id="jquery-script-menu">
<div class="jquery-script-center">
<ul>
<li><a href="http://www.jqueryscript.net/other/Simple-jQuery-Based-Barcode-Generator-Barcode.html">Download This Plugin</a></li>
<li><a href="http://www.jqueryscript.net/">Back To jQueryScript.Net</a></li>
</ul>
<div class="jquery-script-ads"><script type="text/javascript"><!--
google_ad_client = "ca-pub-2783044520727903";

/* jQuery_demo */
google_ad_slot = "2780937993";
google_ad_width = 728;
google_ad_height = 90;

//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<div class="jquery-script-clear"></div>
</div>
</div>
<h1 style="margin-top:150px;">jQuery Barcode Plugin Examples</h1>
<div id="generator"> Please fill in the code :
<input type="text" id="barcodeValue" value="12345670">
<div id="config">
<div class="config">
<div class="title">Type</div>
<input type="radio" name="btype" id="ean8" value="ean8" checked="checked">
<label for="ean8">EAN 8</label>
<br>
<input type="radio" name="btype" id="ean13" value="ean13">
<label for="ean13">EAN 13</label>
<br>
<input type="radio" name="btype" id="upc" value="upc">
<label for="upc">UPC</label>
<br>
<input type="radio" name="btype" id="std25" value="std25">
<label for="std25">standard 2 of 5 (industrial)</label>
<br>
<input type="radio" name="btype" id="int25" value="int25">
<label for="int25">interleaved 2 of 5</label>
<br>
<input type="radio" name="btype" id="code11" value="code11">
<label for="code11">code 11</label>
<br>
<input type="radio" name="btype" id="code39" value="code39">
<label for="code39">code 39</label>
<br>
<input type="radio" name="btype" id="code93" value="code93">
<label for="code93">code 93</label>
<br>
<input type="radio" name="btype" id="code128" value="code128">
<label for="code128">code 128</label>
<br>
<input type="radio" name="btype" id="codabar" value="codabar">
<label for="codabar">codabar</label>
<br>
<input type="radio" name="btype" id="msi" value="msi">
<label for="msi">MSI</label>
<br>
<input type="radio" name="btype" id="datamatrix" value="datamatrix">
<label for="datamatrix">Data Matrix</label>
<br>
<br>
</div>
<div class="config">
<div class="title">Misc</div>
Background :
<input type="text" id="bgColor" value="#FFFFFF" size="7">
<br>
"1" Bars :
<input type="text" id="color" value="#000000" size="7">
<br>
<div class="barcode1D"> bar width:
<input type="text" id="barWidth" value="1" size="3">
<br>
bar height:
<input type="text" id="barHeight" value="50" size="3">
<br>
</div>
<div class="barcode2D"> Module Size:
<input type="text" id="moduleSize" value="5" size="3">
<br>
Quiet Zone Modules:
<input type="text" id="quietZoneSize" value="1" size="3">
<br>
Form:
<input type="checkbox" name="rectangular" id="rectangular">
<label for="rectangular">Rectangular</label>
<br>
</div>
<div id="miscCanvas"> x :
<input type="text" id="posX" value="10" size="3">
<br>
y :
<input type="text" id="posY" value="20" size="3">
<br>
</div>
</div>
<div class="config">
<div class="title">Format</div>
<input type="radio" id="css" name="renderer" value="css" checked="checked">
<label for="css">CSS</label>
<br>
<input type="radio" id="bmp" name="renderer" value="bmp">
<label for="bmp">BMP (not usable in IE)</label>
<br>
<input type="radio" id="svg" name="renderer" value="svg">
<label for="svg">SVG (not usable in IE)</label>
<br>
<input type="radio" id="canvas" name="renderer" value="canvas">
<label for="canvas">Canvas (not usable in IE)</label>
<br>
</div>
</div>
<div id="submit">
<input type="button" onclick="generateBarcode();" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generate the barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;">
</div>
</div>
<div id="barcodeTarget" class="barcodeTarget"></div>
<canvas id="canvasTarget" width="150" height="150"></canvas>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);
  (function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$GeneradorCB_php->Page_Terminate();
?>
