<?php
class Globals{
	// Variables de conexion a base de datos
	const TIPO_MYSQLI = 'mysqli';
	const TIPO_POSTGRES = 'postgres';
	const TIPO_MYSQL = 'mysql';
	const HOST = 'localhost';
	const PORT = null;
	const SOCKET = null;
	const DBNAME = 'hackembo';
	const USER = 'root';
	const PASS = 'root';
	
	// Variable de chequeo de seguridad
	const SECURID = 'securPRES1902';
	
	// Variables de sesion
	const S_USUARIO = 'usuario';	// ID usuario
	const S_PERMISO = 'permiso';	// Nivel de permiso
}
?>