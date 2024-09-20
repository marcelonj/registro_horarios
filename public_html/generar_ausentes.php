<?php
//Variables
$hoy = date('d/m', time());
$empresa = $_POST["empresa"];
$listado = $_POST["empleados"];
$sum = 0;
$total = 0;

$empresa = strtoupper($empresa);
$listado = explode(",",$listado);

echo "*AUSENTES {$empresa} AL {$hoy}*<br><br>";
foreach ($listado as $empleado){
	$total++;
	$aux = "";
	$band = false;
	for ($i=0; $i<strlen($empleado); $i++){
		if ($empleado[$i] != " "){
			$band = true;
		}
		if($band){
			$aux = $aux.$empleado[$i];
		}
	}
	//echo "letra: ", $empleado[0];
	$aux = ucwords($aux);
	echo "{$total}. {$aux}<br>";
}
echo "<br>TOTAL DE AUSENTES: *{$total} empleados*";