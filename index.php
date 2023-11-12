<?php

session_start();

//$_SESSION['passo'] = 3;

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
			if (!isset($_POST['valor']) || $_POST['valor'] == null)
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
		<table id="original">
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
		$status = [];
		$coef = [];
		$letras = ["a","b","c","d","e","f","g","h","i"];
		//Explicacao dos passos
		$passo = [
			"Substituimos os valores",
			"Multiplicamos o lambda pela matriz identidade",
			"Calculamos a diferenca entre as duas matrizes",
			"Simplificamos a diferenca",
			"Efectuamos a diferenca entre as diagonais",
			"Desta forma:",
			"Ou seja:",
			"Multiplicando os valores, temos:",
			"Simplificando, temos:",
			"Somando todos termos iguais, temos:",
			"Agora, aplicamos a formula da equacao quadratica para obter as raizes",
			"Temos",
			"Primeiro verificamos para a primeira raiz",
			"Depois verificamos para a segunda raiz",
			"Multiplicamos as matrizes",
			"Formamos um sistema de equacoes, para obter as incognitas",
			"Apos resolvermos o sistema, temos:",
			"E as incognitas:",
			"Por fim, a nossa solucao:",
		];
		$passo_aux = ["Como a matriz e de terceira ordem, voltamos a escrever as primeiras duas colunas dos elementos da matriz"];
		$pass = 0;
		for ($step = 0; $step <= 9; $step++) {
			$pos = 0; ?>
			<br><br><br><br><br>
			<p><?php echo $passo[$pass] ?></p>
			<?php	
		 	for ($count = 0; $count <= 1; $count++) { ?> 
				<table style="display: inline-block">
				<?php
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
								case 7: for ($i = 0; $i < $num_T; $i++) {
											if ($numbros[$count][0][$i] == "")
												continue;
											$plus = false;
											$sign = $count == 0? "+" : "-";
											for ($j = 0; $j < $num_T; $j++) {
												$cont = "";
												for ($k = 1; $k < $num_T; $k++) {
													if ($numbros[$count][$k][$j] == "")
														continue;
													if ($plus == true)
														echo "+";
													$plus = true;
													$vero1 = intval($numbros[$count][0][$i]);
													$vero2 = intval($numbros[$count][$k][$j]);			
													if (($vero1 != 0 && $vero2 != 0) || ($numbros[$count][0][$i] == "0"|| $numbros[$count][$k][$i] == "0")) {
														$cont = (intval($numbros[$count][0][$i])*intval($numbros[$count][$k][$j]));
														array_push($status,"num");
														array_push($coef,intval($sign.$cont));
													}
													else
														if ($vero1 == 0 && $vero2 == 0) {
															$cont = "L^2";
															array_push($status,"sqr");
															array_push($coef,intval($sign."1"));
														}
														else {
															if ($vero1 == 0) {
																$cont = (-1*intval($numbros[$count][$k][$j]))."L";
																array_push($coef,intval("-".$numbros[$count][$k][$j]));
															}
															else
															if ($vero2 == 0) {
																$cont = (-1*intval($numbros[$count][0][$i]))."L";
																array_push($coef,intval("-".$numbros[$count][0][$i]));
															}
															array_push($status,"sgl");
														}
												}
												echo $cont." ";
												$result[$count][$j] = $cont;
											}
										}
										break;
								case 8:	$sign = false;
										for ($k = 0; $k < $num_T; $k++) {
											for ($l = 0; $l < $num_T; $l++) {
												if ($result[$k][$l] != " ") {
													if ($sign == true)
														echo $k == 0? "+": "-";
													echo $result[$k][$l];
												} 
												$sign = true;
											}
										}
										$count = 1;
										$i = $num_T;
										$j = $num_T;
										break;
								case 9:	$somas = [0,0,0,0];
										$statuses = ["cub","sqr","sgl","num"];
										$coeff = [0,0,0,0];
										$sign = false;
										$inserido = 0;
										for ($i = 0; $i <= 3; $i++) {
											for ($j = 0; $j < sizeof($coef); $j++) {
												if ($status[$j] == $statuses[$i])
													$somas[$i] += $coef[$j];
											}
											if ($somas[$i] != 0) {
												if ($somas[$i] > 0 && $sign == true)
													echo "+";
												echo $somas[$i];
												switch ($statuses[$i]) {
													case "cub": echo "L"?><sup>3</sup>
													<?php		break;
													case "sqr": echo "L"?><sup>2</sup>
													<?php		break;
													case "sgl": echo "L";
																break;
												}
												$sign = true;
												//array_push($coeff,$somas[$i]);
										$coeff[$inserido++] = $somas[$i];

								}
										}
										$count = 1;
										$i = $num_T;
										$j = $num_T;
										break;			
							}
						}
						if ($step == 4 && $num_T == 3) {
						   echo "| ".$letras[$pos-3]." ".$letras[$pos-2]." ";
						}
							echo ")";
?>
					</td>
					</tr>
				<?php } 
				?>
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
				if ($step == 4 && $num_T == 3) {?>
					<br><br><?php
					echo "Como a matriz é da terceira ordem, expandimos as primeiras duas colunas para efectuarmos as multiplicações";
					?><br><?php
				}				
			}
		$pass++;
		}
		break;
//END SWITCH
}
if ($_SESSION['passo'] >= 5) {
$raiz = [];
$raizes = [];
$valores = [];
$count = 0;
$broke = false;
for ($step = 10; $step <= 18; $step++) { ?>
			<br><br><br><br><br>

	<p><?php echo $passo[$pass] ?></p> <?php
	if ($broke == true)
		break;
	switch ($step) {
		case 10: ?>L<sub>1</sub><?php echo "= (-".$coeff[1]."+sqrt( (".$coeff[1].")"?><sup>2</sup><?php echo "-4•".$coeff[0]."•".$coeff[2]." ) )/2•".$coeff[0];
				 ?><br>
				 L<sub>2</sub><?php echo "= (-".$coeff[1]."-sqrt( (".$coeff[1].")"?><sup>2</sup><?php echo "-4•".$coeff[0]."•".$coeff[2]." ) )/2•".$coeff[0];
				 ?><br>
				 <?php 
if ($coeff[0] == 0) {
	echo "Divisao por 0 ilegal";
	$broke = true;
	break;
}
else
  $raiz[0] = (-$coeff[1] +sqrt( pow($coeff[1],2)-(4*$coeff[0]*$coeff[2]) ) )/(2*$coeff[0]);			 
				 $raiz[1] = (-$coeff[1] -sqrt( pow($coeff[1],2)-(4*$coeff[0]*$coeff[2]) ) )/(2*$coeff[0]);
				 break;
		case 11: echo "L"?><sub>1</sub><?php echo " = ".$raiz[0];?><br><?php
				 echo "L"?><sub>2</sub><?php echo " = ".$raiz[1];
				 break;
		case 12: ;
		case 13: $letras = ["x","y","z"];
				 for ($i = 0; $i < $num_T; $i++) {
					echo "( ";
					for ($j = 0; $j < $num_T; $j++) {
						 $algo = "a".$i.$j;
						 echo $_POST[$algo]." ";
					}
					echo ")"; 
					if ($i == round(($num_T+1)/2,0,PHP_ROUND_HALF_DOWN)-1)
						echo " • ";
					 else {
					   ?>&nbsp&nbsp<?php
					}
					echo " ( ".$letras[$i]." ) ";
					if ($i == round(($num_T+1)/2,0,PHP_ROUND_HALF_DOWN)-1)
						echo " = ".$raiz[$count];
					 else {
					   ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php
					 }
					$raizes[$i] = $raiz[$count];
					echo "( ".$letras[$i]." )"; ?>
				 	<br><?php
				 }
				 if ($count == 0) {
					 $step = 13;
					 $pass = 13;
				 }
				 break;
		case 14: ;
		case 15: for ($i = 0; $i < $num_T; $i++) {
					if ($step == 14)
						echo "( ";
					for ($j = 0; $j < $num_T; $j++) {
						 $algo = "a".$i.$j;
						 echo $_POST[$algo].$letras[$j];
						 if ($j != $num_T-1) {
							if ($step == 15)
								echo " +";
						 	echo " ";
						 }
					}
					if ($step == 14) {
						echo " )"; 
						if (($i == round(($num_T+1)/2,0,PHP_ROUND_HALF_DOWN)-1))
							echo " = ";
						 else {
						   ?>&nbsp&nbsp&nbsp&nbsp<?php
						 }
						echo "( ";
					 }
					 else
					   echo " = ";
					echo $raiz[$count].$letras[$i];
					if ($step == 14)
						echo " )"; ?>
				 	<br><?php
				 }
				 break;
		case 16: $matriz = [];
					for ($i = 0; $i < $num_T; $i++) {
						for ($j = 0; $j < $num_T; $j++) {
							$algo = "a".$i.$j;
							$matriz[$i][$j] = $_POST[$algo];
						} 
					}
				$x; $y; $z;
				//look for zeroes
				for ($i = 0; $i < $num_T; $i++) {
					$matriz[$i][$i] -= $raiz[$count];
				}
if ($matriz[0][0] == 0)
	$x = 0;
 else
				$x = (-$matriz[0][1])/$matriz[0][0];
if ($matriz[1][1] == 0)
	$y = 0;
 else
				$y = -(($x*$matriz[1][0])/$matriz[1][1]);
if ($matriz[0][0] == 0)
	$x = 0;
 else
				$x = -$matriz[0][1]/$matriz[0][0];
				$valores[$count][0] = $x;
				$valores[$count][1] = $y;
				for ($i = 0; $i < $num_T; $i++) {
					$j = $i;
					if ($matriz[$i][$j] == 0) {
						$valores[$count][$i] = 0;
						continue;
					}
					//$valores[$count][$i] = $raizes[$i]/$matriz[$i][$j];
					echo $letras[$i]." = ".$valores[$count][$i];
					?><br><?php
				 }
				 $step = 17;
				 $pass = 17;
				 if ($count == 0) {
				 	 $count++;
					 $step = 12;
					 $pass = 12;
			 	 }				 
				 break;
		 case 18: 
for ($count = 0; $count <= 1; $count++) {
		 echo "v"?><sub><?php echo ($count+1) ?></sub>
				 <?php echo "= w(";
				 for ($i = 0; $i < $num_T; $i++) {
				 	 echo $letras[$i];
				 	 echo $i == $num_T-1? ")" : ",";
				 } ?>
				 <br><?php
				echo "v"?><sub><?php echo ($count+1) ?></sub>
				 <?php echo "= w(";
				 for ($i = 0; $i < $num_T; $i++) {
				 	 echo $valores[$count][$i];
				 	 echo $i == $num_T-1? ")" : ",";
				 } ?>
				 <br><?php 
				 if ($count == 0)
				 	 echo "ou";?>
				 <br><?php 
}
			 	 echo "Onde o w é o nosso autovalor, e o (";
			 	 for ($i = 0; $i < $num_T; $i++) {
				 	 echo $letras[$i];
				 	 echo $i == $num_T-1? ")" : ",";
				 } ?>
				 <br><?php
				 echo "o nosso autovetor.";
				 break;
	}
	$pass++;
    }
    
} ?>

<br><br>
<?php
if ($_SESSION['passo'] >= 2) { ?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<button name="reiniciar">Reiniciar</button>
	</form>
<?php } ?>

<script>
	let msg = <?php echo json_encode($msg) ?>;
	let numbros = <?php echo json_encode($numbros) ?>;
	console.log(numbros);
	alert(msg);
	if (msg != null) {
		alert(msg);
	}
</script>

</body>
</html>
