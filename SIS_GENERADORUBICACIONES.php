<style>
table, th, td {
}
th, td {
	text-align: center;
	border: 1px solid #dddddd;
	padding: 8px;
}
table#t01 {
	width: 100%;
}
</style>
<meta name="generator" content="PHPMaker v12.0.5">
</head>
<P> <input type="button" value="IMPRIMIR ETIQUETAS" onclick="window.print()">
<table style="width:100%">
<table id="t01" >
<?php
	    $nomusuario = CurrentUserName();
		$query = "EXEC SP_GENERAR_ARTICULOS_CODIGO_DE_BARRAS " . $nomusuario;
		$articuloscodigodebarra = ew_Execute($query);
		$query = "EXEC SP_CONSULTARARTICULOSCODIGODEBARRAS " . $nomusuario;
		$articuloscodigodebarra = ew_Execute($query);
	    $columna = 1;
		foreach ($articuloscodigodebarra as $rs => $value) {
			if ($columna == 1)
			{
				echo '<tr>';
				echo '<td align="center">';
						echo "<b>". $value["codarticulo"] . "</b>";
						echo "<P>". $value["descripcion"];
						echo "<P>Precio Regular: ¢ ". $value["precio"];
						echo "<P>". "<img alt='testing' src='barcode.php?text= ". $value["referencia"] ."'>";
				echo '</td>';
				$columna = 2;
				continue;
			}
			if ($columna == 2)
			{
				  echo '<td align="center">';
						echo "<b>". $value["codarticulo"] . "</b>";
						echo "<P>". $value["descripcion"];
						echo "<P>Precio Regular: ¢ ". $value["precio"];
						echo "<P>". "<img alt='testing' src='barcode.php?text= ". $value["referencia"] ."'>";
				  echo '</td>';
				$columna = 3;
				continue;
			}
			if ($columna == 3)
			{
				  echo '<td align="center">';
						echo "<b>". $value["codarticulo"] . "</b>";
						echo "<P>". $value["descripcion"];
						echo "<P>Precio Regular: ¢ ". $value["precio"];
						echo "<P>". "<img alt='testing' src='barcode.php?text= ". $value["referencia"] ."'>";
				  echo '</td>';
				$columna = 1;
				echo '</td>';
				continue;
			}
			echo '</tr>';
		}
?>
</table>
