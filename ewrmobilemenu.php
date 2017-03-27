<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mmi_VIW_HISTORIAL_ARTICULOS", $ReportLanguage->Phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("5", "MenuText") . $ReportLanguage->Phrase("SimpleReportMenuItemSuffix"), "VIW_HISTORIAL_ARTICULOSrpt.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mmi_VIW_HISTORIAL_ARTICULOS_Ventas", $ReportLanguage->Phrase("ChartReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("6", "MenuText") . $ReportLanguage->Phrase("ChartReportMenuItemSuffix"), "VIW_HISTORIAL_ARTICULOSrpt.php#cht_VIW_HISTORIAL_ARTICULOS_Ventas", 5, "", IsLoggedIn(), FALSE);
if (IsLoggedIn()) {
	$RootMenu->AddMenuItem(-1, "mmi_logout", $ReportLanguage->Phrase("Logout"), "rlogout.php", -1, "", TRUE);
} elseif (substr(ewr_ScriptName(), 0 - strlen("rlogin.php")) <> "rlogin.php") {
	$RootMenu->AddMenuItem(-1, "mmi_login", $ReportLanguage->Phrase("Login"), "rlogin.php", -1, "", TRUE);
}
$RootMenu->Render();
?>
<!-- End Main Menu -->
