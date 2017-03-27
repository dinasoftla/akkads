<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(10011, "mi_SIS_TAREAS", $Language->MenuPhrase("10011", "MenuText"), "SIS_TAREASlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10009, "mi_MENUPRINCIPAL_php", $Language->MenuPhrase("10009", "MenuText"), "MENUPRINCIPAL.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10010, "mi_MEN_CREAR_ETIQUETAS_php", $Language->MenuPhrase("10010", "MenuText"), "MEN_CREAR_ETIQUETAS.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10012, "mi_VIW_IMPRIMIR_UBICACIONES", $Language->MenuPhrase("10012", "MenuText"), "VIW_IMPRIMIR_UBICACIONESlist.php", 10010, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(45, "mi_VIW_CENTRODEIMPRESION", $Language->MenuPhrase("45", "MenuText"), "VIW_CENTRODEIMPRESIONlist.php", 10010, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(52, "mi_MEN_PROCESOS_php", $Language->MenuPhrase("52", "MenuText"), "MEN_PROCESOS.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10, "mi_Consulta_Articulos", $Language->MenuPhrase("10", "MenuText"), "Consulta_Articuloslist.php", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(42, "mci_CREAR_PEDIDO", $Language->MenuPhrase("42", "MenuText"), "SIS_LECTURAARTICULOlist.php?txtlectura=&select1=1&descuento=0&transporte=0", 52, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mi_SIS_PEDIDO", $Language->MenuPhrase("11", "MenuText"), "SIS_PEDIDOlist.php", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, "mi_SIS_PEDIDO_DETALLE", $Language->MenuPhrase("18", "MenuText"), "SIS_PEDIDO_DETALLElist.php?cmd=resetall", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(19, "mi_VIW_DETALLEPEDIDO", $Language->MenuPhrase("19", "MenuText"), "VIW_DETALLEPEDIDOlist.php", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_SIS_FACTURA", $Language->MenuPhrase("4", "MenuText"), "SIS_FACTURAlist.php", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mi_SIS_FACTURA_DETALLE", $Language->MenuPhrase("5", "MenuText"), "SIS_FACTURA_DETALLElist.php?cmd=resetall", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(20, "mi_SIS_PEDIDO_ACTUAL", $Language->MenuPhrase("20", "MenuText"), "SIS_PEDIDO_ACTUALlist.php", 52, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10008, "mi_MEN_PROCESOS_FINANCIEROS_php", $Language->MenuPhrase("10008", "MenuText"), "MEN_PROCESOS_FINANCIEROS.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(72, "mi_SIS_INGRESOS", $Language->MenuPhrase("72", "MenuText"), "SIS_INGRESOSlist.php", 10008, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(71, "mi_SIS_PAGOS", $Language->MenuPhrase("71", "MenuText"), "SIS_PAGOSlist.php", 10008, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mi_SIS_ABONOS", $Language->MenuPhrase("12", "MenuText"), "SIS_ABONOSlist.php", 10008, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(50, "mi_MEN_INVENTARIO_php", $Language->MenuPhrase("50", "MenuText"), "MEN_INVENTARIO.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(64, "mi_MEN_INVENTARIO_ENTRADA_php", $Language->MenuPhrase("64", "MenuText"), "MEN_INVENTARIO_ENTRADA.php", 50, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(59, "mi_SIS_LEERARTICULO_php", $Language->MenuPhrase("59", "MenuText"), "SIS_LEERARTICULO.php", 64, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(70, "mi_VIW_ENTRADA_INVENTARIO", $Language->MenuPhrase("70", "MenuText"), "VIW_ENTRADA_INVENTARIOlist.php", 64, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(65, "mi_MEN_INVENTARIO_SALIDA", $Language->MenuPhrase("65", "MenuText"), "MEN_INVENTARIO_SALIDA", 50, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(66, "mi_VIW_SALIDA_INVENTARIO", $Language->MenuPhrase("66", "MenuText"), "VIW_SALIDA_INVENTARIOlist.php", 65, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, "mi_SIS_ARTICULO_INVENTARIO", $Language->MenuPhrase("2", "MenuText"), "SIS_ARTICULO_INVENTARIOlist.php", 50, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mi_SIS_KARDEX", $Language->MenuPhrase("6", "MenuText"), "SIS_KARDEXlist.php", 50, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(49, "mi_MEN_MANTENIMIENTOS_php", $Language->MenuPhrase("49", "MenuText"), "MEN_MANTENIMIENTOS.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(24, "mi_SIS_CUENTAS_BANCARIAS", $Language->MenuPhrase("24", "MenuText"), "SIS_CUENTAS_BANCARIASlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(9, "mi_SIS_USUARIOS", $Language->MenuPhrase("9", "MenuText"), "SIS_USUARIOSlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mi_SIS_COMPANIA", $Language->MenuPhrase("17", "MenuText"), "SIS_COMPANIAlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, "mi_SIS_LINEA", $Language->MenuPhrase("8", "MenuText"), "SIS_LINEAlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, "mi_SIS_ARTICULO", $Language->MenuPhrase("1", "MenuText"), "SIS_ARTICULOlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mi_SIS_UBICACIONES", $Language->MenuPhrase("13", "MenuText"), "SIS_UBICACIONESlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mi_SIS_CLIENTE", $Language->MenuPhrase("3", "MenuText"), "SIS_CLIENTElist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(73, "mi_SIS_PROVEEDORES", $Language->MenuPhrase("73", "MenuText"), "SIS_PROVEEDORESlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(69, "mi_SIS_JUSTIFICACIONES", $Language->MenuPhrase("69", "MenuText"), "SIS_JUSTIFICACIONESlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(68, "mi_SIS_ETIQUETAS", $Language->MenuPhrase("68", "MenuText"), "SIS_ETIQUETASlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(46, "mi_SIS_SELECCION_USUARIO", $Language->MenuPhrase("46", "MenuText"), "SIS_SELECCION_USUARIOlist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(54, "mi_MEN_IMPRIMIR", $Language->MenuPhrase("54", "MenuText"), "MEN_IMPRIMIR", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(44, "mi_SIS_GENERADORCB_php", $Language->MenuPhrase("44", "MenuText"), "SIS_GENERADORCB.php", 54, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(57, "mi_SIS_GENERADORUBICACIONESCB_php", $Language->MenuPhrase("57", "MenuText"), "SIS_GENERADORUBICACIONESCB.php", 54, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(53, "mi_MEN_REPORTES_php", $Language->MenuPhrase("53", "MenuText"), "MEN_REPORTES.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(76, "mi_SIS_GENERAR_REPORTE_TOP_php", $Language->MenuPhrase("76", "MenuText"), "SIS_GENERAR_REPORTE_TOP.php", 53, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(75, "mi_SIS_REPORTE_INGRESOSYPAGOS_php", $Language->MenuPhrase("75", "MenuText"), "SIS_REPORTE_INGRESOSYPAGOS.php", 53, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10006, "mri_VIW5fHISTORIAL5fARTICULOS7cVentas", $Language->MenuPhrase("10006", "MenuText"), "VIW_HISTORIAL_ARTICULOSrpt.php#cht_Ventas", 53, "{F6306297-54D9-482A-802C-C71D7F2A088D}", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(15, "mi_AuditTrail", $Language->MenuPhrase("15", "MenuText"), "AuditTraillist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(63, "mi_PENDIENTES_txt", $Language->MenuPhrase("63", "MenuText"), "PENDIENTES.txt", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->