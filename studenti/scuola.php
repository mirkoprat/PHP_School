<html>
<head>

<title> Programma scuola </title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	
<style>
.contenitore {
	text-align: center;
    margin-top: 50px;
}

h2{
color: orange;
text-align: center;
}
</style>
</head>

<body>
<h2> PROGRAMMA STUDENTI </h2>
<?php
//faccio i controlli all'inzio cosi che evito di farli dentro ogni funzione
	if(isset($_POST['search'])){
		$nome=$_POST['search'];
	}else{
		$nome='';
	}
	if(isset($_POST['search1'])){
		$cognome=$_POST['search1'];
	}else{
		$cognome='';
	}
	
	if(isset($_POST['search2'])){
		$materia=$_POST['search2'];
	}else{
		$materia='';
	}
	
	if(isset($_POST['search3'])){
		$classe=$_POST['search3'];
	}else{
		$classe='';
	}
	
	if(isset($_POST['votoaggiungere'])){
		$voto=$_POST['votoaggiungere'];
	}else{
		$voto='';
	}
	
	if(isset($_POST['tipovoto'])){
		$tipo=$_POST['tipovoto'];
	}else{
		$tipo='';
	}
	
	
function Aggiungi_Voto($nome, $cognome, $materia, $classe, $voto, $tipo){
    if($nome === '' || $cognome === '' || $materia === '' || $classe === '' || $voto === ''){
        return "NESSUN RISULTATO - RIPROVA";
    }
    
    $data = date('Y-m-d'); // mette in automatico la data di oggi
    $nuovaRiga = "$cognome,$nome,$classe,$materia,$data,$voto,$tipo". "<br>";
    
    $handle = fopen("random-grades.csv", "a");
    if($handle){
        fwrite($handle, $nuovaRiga);
        fclose($handle);
        return "VOTO AGGIUNTO CON SUCCESSO!";
    }else{
        return "ERRORE";
    }
}


function MatchStudente($a, $nome, $cognome, $classe, $materia) {
    if (count($a) < 6) return false;
    if(
        ($nome === '' || $a[1] == $nome) &&
        ($cognome === '' || $a[0] == $cognome) &&
        ($classe === '' || $a[2] == $classe) &&
        ($materia === '' || $a[3] == $materia)
    ){
		return true;
		}else{
		return false;
		}
}

function Leggi_mediatot($nome, $cognome, $classe, $materia){
	
	if ($nome === '' && $cognome === '' && $classe === '' && $materia === '') return 0;
	$totale=0;
	$cont=0;
	$handle = fopen ("random-grades.csv", "r");
	while(!feof($handle)){
		$line = fgets($handle);
		$line = trim($line); 
		$a = explode(",",$line);
		if (count($a) < 6) continue; 
		if(MatchStudente($a,$nome,$cognome,$classe,$materia)){
		$numero=(float)$a[5];
		$totale +=$numero;
		$cont++;
		}
	}
	fclose($handle);
	if($cont != 0){
	$somma= $totale/$cont;
	return round($somma,2); // per vedere solo 2 numeri dopo la virgola
	}else{
		return 0;
	}
}

function Cerca_Studente($nome, $cognome, $classe, $materia){
	$result = "";

	if ($nome === '' && $cognome === '' && $classe === '' && $materia === '') return '';
	$handle = fopen ("random-grades.csv", "r");
	while(!feof($handle)){
		$line = fgets($handle);
		$a = explode(",",$line);
		if (count($a) <= 1) continue; 	
		if (MatchStudente($a,$nome,$cognome,$classe,$materia)){
		$result .= $line. "<br>";
		}
	}
	fclose($handle);
	return $result;
}

$media='';
$result='';
$messaggio='';

$result=Cerca_Studente($nome, $cognome, $classe, $materia);
$media=Leggi_mediatot($nome, $cognome, $classe, $materia);
if(isset($_POST['Aggiungi'])){
    $messaggio = Aggiungi_Voto($nome, $cognome, $materia, $classe, $voto,$tipo);
}


?>
<div class="contenitore";>
<form action ="./scuola.php" method = "post">
INSERISCI NOME:<input type="text" name="search" ><br><br>

INSERISCI COGNOME:<input type="text" name="search1" ><br><br>

INSERISCI MATERIA:<input type="text" name="search2" ><br><br>

INSERISCI CLASSE:<input type="text" name="search3" ><br><br>
<input type="Submit" name="Submit"><br><br><br>

INSERISCI UN VOTO: <input type="text" name="votoaggiungere" ><br><br>

TIPO VOTO:
<select name="tipovoto">
    <option value="">-- Scegli --</option>
    <option value="Pratico">Pratico</option>
	<option value="Pratico">Scritto</option>
    <option value="Orale">Orale</option>
</select><br><br>

<input type="Submit" name="Aggiungi"><br><br>

MEDIA TOTALE DELLO STUDENTE: <input type="text" value="<?php echo $media; ?>">
</form>

<h2> RICERCA RISULTATI </h2>
<?php 
if(isset($_POST['Submit'])){ //se il pulsante  è stato cliccato
	if($result !== ""){
		echo $result;
	}else{
		echo "NESSUN RISULTATO - RIPROVA";
	}
}

if($messaggio !== ''){ // Se $messaggio NON è vuoto
    echo $messaggio;
}

?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>