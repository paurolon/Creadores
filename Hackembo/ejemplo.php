<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CORErembo test</title>
</head>

<body>
<?php
	include_once('class/Globals.php');
	include_once('class/DataBase.php');
	include_once('class/Paginador.php');
	include_once('class/Pagina.php');
	
	$db = new DataBase();

//INSERTAR(a);
	$db->insertar("usuarios",array("nombre"=>"Pau","ci"=>446544));
	$db->insertar("usuarios",array("nombre"=>"Eladio","ci"=>123456));
	$ins = $db->insertar("usuarios",array("nombre"=>"Pes","ci"=>553171));
	echo "<p>Insertar() ".$ins."</p>";

//CONSULTAR();
//Utilizar para SELECTS 
//Retorna un array con los resultados de la consulta
	$c = $db->consultar("SELECT ci, nombre FROM usuarios");
	
	print_r($c);	// retorna array
	echo "<p>Consultar() ".$c[0]['nombre']."</p>";

//SQLDELETE();
//Utilizar para DELETEs 
//Retorna TRUE si se realizo correctamente
	$a = $db->sqlDelete("Delete from usuarios where ci = 553171");
	echo "<p>SQLDETE() ".$a."</p>";

//ACTUALIZAR();
//Retorna True si se realizo correctamente"
	$b = $db->actualizar("usuarios", array("nombre"=>"Eladio"), "ci = 123456");
	echo "<p>ACTUALIZAR() ".$b."</p>";

//PAGINADOR
//Retorna un array con las filas
	$paginador = new Paginador("usuarios",array("nombre","ci"));
	$paginador_result = $paginador->paginar(0,3);
	
	foreach($paginador_result as $key=>$item)
	{
		echo "<p>Nombre:".$item['nombre']." Ci:".$item['ci']."</p>";
	}
	
	// para las siguientes 3
	$paginador_result = $paginador->paginar(3,3);
	
	foreach($paginador_result as $key=>$item)
	{
		echo "<p>Nombre:".$item['nombre']." Ci:".$item['ci']."</p>";
	}

// PAGINAS
	$pags = new Pagina($db);
	//or
	//$pags = new Pagina();
	$pags->insertar_agenda(/*varios parametros*/);
	$pags->insertar_archivo(/*varios parametros*/);
	$pags->insertar_foto(/*varios parametros*/);
	$pags->insertar_noticia(/*varios parametros*/);
	$pags->insertar_pagina(/*varios parametros*/);
	$pags->insertar_video(/*varios parametros*/);
	
?>
</body>
</html>
