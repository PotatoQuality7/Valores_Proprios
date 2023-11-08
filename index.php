<?php

session_start();

$_SESSION['passo'] = 3;

if (isset($_POST['reiniciar']))
	$_SESSION['passo'] = null;

if (!isset($_SESSION['passo'])) {
	$_SESSION['passo'] = 0;
	$_POST['incrementar'] = 1;
}

switch ($_SESSION['passo']) {
	case 1: //Escolher metodo
			$_SESSION['metodo'] = $_POST['metodo'];
			break;
	case 2: //Escolher dimensao da matriz
			$_SESSION['num_T'] = $_POST['num_T'];
			$_SESSION['letra'] = "a";
			$_POST['y'] = 0;
			$_POST['x'] = 0;
			break;
	case 3: //Inserir valores da matriz
			if (isset($_POST['inserido']) && !isset($_POST['valor']))
				break;
			if ($_POST['valor'] == null)
				/*$msg = "Insira um valor"*/;
			 else {	
				switch ($_SESSION['letra']) {
					case "a": $algo = $_SESSION['letra'].$_POST['y'].$_POST['x']; break;
				    default: $algo = $_SESSION['letra'].$_POST['x']; break;
				}
				$_POST[$algo] = $_POST['valor'];
				$_POST['x']++;
				if ($_POST['x'] == $_SESSION['num_T']) {
					$_POST['y']++;
					$_POST['x'] = 0;
					if ($_POST['y'] == $_SESSION['num_T']) {
						$_SESSION['letra'] = "b";			
						$_POST['y'] = 0;
					}
				}
			 }
			break;
}


if (isset($_POST['incrementar'])) {
	$_SESSION['passo'] += $_POST['incrementar'];
}


function calcularMMC($mmc) {
	for ($i = 0; $i <= $_SESSION["num_T"]; $i++) {
		echo $mmc[$i]."\n";
	}	
}

?>

<html>
<head>
	<link href="./styles.css" rel="stylesheet">
</head>

<body>
	<h1>Valores Proprios e Vetores Proprios</h1>
	<?php

if ($_SESSION['passo'] >= 3) {
	$letras = ["x", "y", "z"];
	$algo = ""; ?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<table>
			<legend>Matriz</legend>
			<?php
				$num_T = $_SESSION['num_T'];
				for ($i = 0; $i <= $num_T; $i++) { ?>
					<tr>
						<?php 
						for ($j = 1; $j <= $num_T; $j++) { ?>
							<td>
								<?php 
									if ($i != 0) {
								    	if ($j == 1) echo "(";
										$algo = "a".($i-1).($j-1);
										if (!isset($_POST[$algo])) 
											  echo "a".$i.$j;
										   else
											 echo $_POST[$algo];
										if ($j == $num_T) echo ")";
									}
									else
										echo $letras[$j-1];
								 ?>
							</td>
							<?php if (isset($_POST[$algo])) { ?>
								<input class="ghost" name="<?php echo "a".($i-1).($j-1) ?>" value="<?php echo $_POST[$algo] ?>" >
							<?php } ?>
						<?php }
						 ?>
					</tr>
			<?php }
			$algo = "a".($_SESSION['num_T']-1).($_SESSION['num_T']-1);
			if ($_SESSION['metodo'] == "resolucao" && isset($_POST[$algo]))
				$_POST['inserido'] = 1; ?>
		</table>
		<?php if ($_SESSION['metodo'] == "demonstracao") { ?>
			<br>
			<table>
				<legend>Vetor</legend>
				<?php
					$num_T = $_SESSION['num_T'];
					for ($i = 0; $i <= 1; $i++) { ?>
						<tr>
						<?php
						for ($j = 1; $j <= $num_T; $j++) { ?>
							<td>
							<?php 									
								if ($i == 0) {
									echo $letras[$j-1];
									continue;
								}
								$algo = "b".($j-1);
								if ($j == 1) echo "(";
								if (!isset($_POST[$algo])) 
									  echo "b".$j;
								   else
									 echo $_POST[$algo];									
								if ($j == $num_T) echo ")";
							?>
							</td>
							<?php if (isset($_POST[$algo])) { ?>
								<input class="ghost" name="<?php echo "b".($j-1) ?>" value="<?php echo $_POST[$algo] ?>" >
								<?php if ($j == $_SESSION['num_T']-1) { ?>
										  <input class="ghost" type="number" name="inserido" value="1">
							 	<?php }
							}
						} ?>
						</tr>
				<?php } ?>
			</table>
		<?php } ?>
		<br>
		<?php
			if (!isset($_POST['inserido'])) {
				$algo = "b".($_SESSION['num_T']-1);
				if ($_SESSION['letra'] == "a") { ?>
					 	<label>Insira o valor de: <?php echo "a".($_POST['y']+1).($_POST['x']+1) ?></label>
				<?php } else { ?>
					 	<label>Insira o valor de: <?php echo "b".($_POST['x']+1) ?></label>
				<?php } ?>
				<input type="number" name="valor">
				<input class="ghost" type="number" name="y" value="<?php echo $_POST['y'] ?>">
				<input class="ghost" type="number" name="x" value="<?php echo $_POST['x'] ?>">
				<button name="inserir">Inserir</button>
		<?php }
			else 
				if ($_SESSION['passo'] == 3) { ?>
					<br><br>
					<button name="calcular">Calcular</button>
					<input class="ghost" type="number" name="incrementar" value="<?php echo $_SESSION['metodo'] == "demonstracao"? 1 : 2 ?>">
		<?php } ?>		
	</form>
	<?php
}

switch ($_SESSION['passo']) {
	case 1: //Selecionar opcao ?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<label>Selecione uma das opcoes</label>
			<button type="text" name="metodo" value="demonstracao">Demonstração</button>
			<button type="text" name="metodo" value="resolucao">Resolução</button>
			<input class="ghost" type="number" name="incrementar" value="1">
		</form>
		<?php break;
	case 2:  //Escolher dimensao da matriz ?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<label>Selecione o numero de colunas e linhas</label>
			<button type="number" name="num_T" value="2">2</button>
			<button type="number" name="num_T" value="3">3</button>
			<input class="ghost" type="number" name="incrementar" value="1">
		</form>
		<?php break;
	case 4: //Calcular o autovalor e o autovetor ?>
		<div id="resposta">
			<h1>Resposta</h1>
			<table>
			<?php
				$num_T = $_SESSION['num_T'];
				$letras = ["x", "y"];
				$mmc = [0];
				if ($num_T == 3) 
					array_push($letras,"z");
				$resp = $num_T == 3? "T(x,y,z) = " : "T(x,y) = ";
				for ($i = 0; $i <= 3; $i++) { ?>
					<tr>
					<?php
						for ($j = 0; $j < $num_T; $j++) { ?>
							<td>
								<?php 
									$sum = 0;
									if ($j == 0)
										echo $resp." ( ";
									for ($k = 0; $k < $num_T; $k++) {
										$algo = "a".$j.$k;
										if ($i != 3) {
											echo $_POST[$algo].$letras[$k];
											if ($k < $num_T-1)
												echo "+";
											$algoo = "b".$k;
										}			
										switch ($i) {
											case 0:	if ($j == $num_T-1)				 
													$letras[$k] = "(".$_POST[$algoo].")";
													break;
											case 1: $_POST[$algo] *= $_POST[$algoo];
													if ($j == $num_T-1)				 
														$letras[$k] = "";
														break;
											case 3: $sum += $_POST[$algo];
													if ($k == $num_T-1) {
														echo $sum;
														array_push($mmc, $sum);
													}
													break;
										}
									} 
									if ($j < $num_T-1)
										echo ", ";
									if ($j == $num_T-1)
										echo " )";
								?>
							</td>
						<?php }	?>
					</tr>
				<?php } ?>
				<tr>
					<td>
					<?php calcularMMC($mmc);
						  echo "Sem autovalor, nem autovetor"; ?>
					</td>
				</tr>
			</table>	
		</div>
		<?php break;
	case 5: //Polinomio P
		$num_T = $_SESSION['num_T'];
		echo "P(L) = det(A - L•I)";
		$id = false;
		$main = [];
		$secondary = [];
		$identidade = [];
		$numbros = [];
		$result = [];
		$letras = ["a","b","c","d","e","f","g","h","i"];
		//Explicacao dos passos
		$passo = [
			"Substituimos as cenas",
			"Multiplicamos o lambda pela matriz identidade",
			"Calculamos a diferenca entre as duas matrizes",
			"Simplificamos a diferenca",
			"Efectuamos a diferenca entre as diagonais",
			"Desta forma:",
			"Ou seja:",
			"Multiplicando os valores, temos:",
		];
		$passo_aux = ["Como a matriz e de terceira ordem, voltamos a escrever as primeiras duas colunas dos elementos da matriz"];
		$pass = 0;
		for ($step = 0; $step <= 7; $step++) {
			$pos = 0; ?>
			<br>
			<p><?php echo $passo[$pass].": ".$pass ?></p>
			<?php	
		 	for ($count = 0; $count <= 1; $count++) { ?> 
				<table style="display: inline-block">
				<?php
$partition = 0;
				 if ($step >= 5) {
				 	 echo $count == 0? "[" : "- [";
				 }
				 for ($i = 0; $i < $num_T; $i++) {
					 $pos2 = $count == 0? 0 : $num_T-1;
				 ?>
				 <tr>
				<?php
					if ($count == 1) {
						switch ($step) {
								case 0: ;
								case 1:	$cont = "";
										if ($i == round(($num_T+1)/2,0,PHP_ROUND_HALF_DOWN)-1)
											$cont = $step == 0? "- L" : "-"; ?>
										<td><?php echo $cont ?></td>
										<?php break;
						}
					} ?>
					<td>
					<?php
						$temp = [];
						echo "( ";
						for ($j = 0; $j < $num_T; $j++) {
							$algo = "a".$i.$j;
							switch ($step) {
								case 0: ;
								case 1: switch ($count) {
											case 0: echo $_POST[$algo]." ";
													break;
											case 1:
													$cont = $step == 0? 1 : "L";
													array_push($temp, $i == $j? $cont : 0);
													echo $temp[$j]." ";
													if ($j == $num_T-1)
														array_push($identidade,$temp);
													break;
										}
										break;
								case 2: ;
								case 3: $cont = $_POST[$algo];
										$contt = "-".$identidade[$i][$j];
										if ($step == 3) {
											if ($_POST[$algo] == 0) {
												if ($identidade[$i][$j] == 0) {
													$cont = 0;
													$contt = "";	
												}
												else
												  $cont = "";
											}
											else
											  if ($identidade[$i][$j] == 0)
												  $contt = "";
											$main[$i][$j] = $cont.$contt;
											$secondary[$i][$j][0] = $cont;
											$secondary[$i][$j][1] = $contt;
										}
										if ($j == $num_T-1)
											$count = 1;
										echo $cont.$contt." ";
										break;
								case 4: echo $letras[$pos++]." ";
										if ($j == $num_T-1)
											$count = 1;
										break;
								case 5: ;
								case 6: if ($i == 0 && $j == $num_T-1) {
											$i = 1;
										}
										switch ($step) {
											case 5: $cont = $letras[$pos2];
													break;
											case 6: $ii = intdiv($pos2,$num_T);
													$jj = $pos2-($ii*($num_T));
												  	$cont = $main[$ii][$jj];
													break;
										}	
										$cont = "(".$cont.($j != $num_T-1? ")•" : ") ");
										if (fmod($pos2+1,$num_T) == 0)
											$pos2++;
										 else {
											if ($count == 0)
												$pos2 += 1+$num_T;
											 /*else
												$pos2 += 1+$num_T;*/
										 }
										echo $cont;					
										break;
case 7:

$sum = [0,0];
$product = [];
$string = [0,0];

for ($i = 0; $i < $num_T; $i++) {
	for ($j = 0; $j <= 1; $j++) {
			$product[$count] = $numbros[$count][0][$i];
			/*if (is_string($numbros[$count][0][$i]) || is_string($numbros[$count][1][$j])) {
				$string[$count] = $string[$count].$numbros[$count][1][$j];
				if (is_string($numbros[$count][0][$i]) && is_string($numbros[$count][1][$j]))
					$string[$count] = $string[$count]."L^2";
				 else
				   $string[$count] = $string[$count].$numbros[$count][0][$i].$numbros[$count][1][$j];
			}
			else {
				$product[$count] *= $numbros[$count][1][$j];
				$sum[$count] += $sum[$count].$product[$count];
			}*/
echo $numbros[$count][0][$i].$numbros[$count][1][$i];
	}
}
echo $sum[$count].$string[$count];
		/*for ($i = 0; $i < $num_T; $i++) {
			for ($j = 0; $j <= 1; $j++) {
				$product[$count] = $numbros[$count][0][$i];
				for ($k = 1; $k < $num_T; $k++) {
					$product[$count] *= $numbros[$count][$k][$j];
				}
				$sum[$count] += $product[$count];
			}
		}*/	
break;

							}
						}
						echo ")";
?>
					</td>
					</tr>
				<?php } ?>
				</table>
				<?php 
				if ($step == 0)
					$identidade = [];
				if ($step >= 5)
					echo "]";
if ($step == 3) {
//I APOLOGISE
$numbros[0][0][0] = $secondary[0][0][0];
$numbros[0][0][1] = $secondary[0][0][1];
$numbros[0][1][0] = $secondary[1][1][0];
$numbros[0][1][1] = $secondary[1][1][1];

$numbros[1][0][0] = $secondary[0][1][0];
$numbros[1][0][1] = $secondary[0][1][1];
$numbros[1][1][0] = $secondary[1][0][0];
$numbros[1][1][1] = $secondary[1][0][1];

//BUT I GOT TO DO WHAT I GOT TO DO
}
			}
		$pass++;
		}
		break;
//END SWITCH
}

if ($_SESSION['passo'] >= 2) { ?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<button name="reiniciar">Reiniciar</button>
	</form>
<?php } ?>

<script>
	let msg = <?php echo json_encode($msg) ?>;
	let numbros = <?php echo json_encode($numbros) ?>;
	console.log(numbros);
	alert("herr");
	alert(msg);
	if (msg != null) {
		alert(msg);
	}
</script>

</body>
</html>
