<?php
include_once('Globals.php');
class DataBase{
	private $host=null;
	private $port=null;
	private $socket=null;
	private $dbname=null;
	private $user=null;
	private $password=null;
	private $options=null;
	private $persistente=null;
	private $tipo=null;
	
	private $conexion=null;
	/*
	 * Contructor y Destructor
	 */
	
	function __construct($options = null, $persistente = false)
	{
		$this->tipo = Globals::TIPO_MYSQLI;
		$this->host = Globals::HOST;
		$this->port = Globals::PORT;
		$this->dbname = Globals::DBNAME;
		$this->user = Globals::USER;
		$this->password = Globals::PASS;
		$this->socket = Globals::SOCKET;
		$this->options = $options;
		$this->persistente = $persistente;
		
		
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
				if(!$this->port){ $this->port = ini_get("mysqli.default_port"); }
				if(!$this->socket){ $this->socket = ini_get("mysqli.default_socket"); }
				if(!$this->host){ $this->host = ini_get("mysqli.default_host"); }
				if(!$this->user){ $this->user = ini_get("mysqli.default_user"); }
				if(!$this->password){ $this->password = ini_get("mysqli.default_pw"); }
		
				$this->conexion = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port, $this->socket);
				if($this->conexion->connect_errno)
				{
					echo "<div id='hackeError'>ERROR AL REALIZAR LA CONEXION: ".mysqli_connect_error()."</div>";
					exit();
				}
				$this->conexion->set_charset("utf8");
				break;
				
			case Globals::TIPO_MYSQL:
				if(!$this->host){ $this->host = ini_get("mysql.default_host"); }
				if(!$this->user){ $this->user = ini_get("mysql.default_user"); }
				if(!$this->password){ $this->password = ini_get("mysql.default_password"); }
				
				$this->conexion = mysql_connect($this->host.":".$this->port,$this->user, $this->password);
				mysql_select_db($this->dbname);
				if(!$this->conexion)
				{
					echo "<div id='hackeError'>ERROR AL REALIZAR LA CONEXION: ".mysql_error()."</div>";
					exit();
				}
				
				break;
				
			case Globals::TIPO_POSTGRES:
				$connStr = "host=".$this->host." port=".$this->port." dbname=".$this->dbname." user=postgres".$this->user." password=".$this->password." options=".$this->options;
				if($this->persistente)
				{
					if(!($this->conexion = pg_pconnect($connStr)))
					{
						echo "<div id='hackeError'>ERROR AL REALIZAR LA CONEXION: PG_PCONNECT</div>";
						exit();
					}
				}else{
					if(!($this->conexion = pg_connect($connStr)))
					{
						echo "<div id='hackeError'>ERROR AL REALIZAR LA CONEXION: PG_CONNECT</div>";
						exit();
					}
				}
				
				break;
		}
	}
	
	function __destruct()
	{
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
				$this->conexion->close();
				break;
			case Globals::TIPO_MYSQL:
				mysql_close($this->conexion);
				break;
			case Globals::TIPO_POSTGRES:
				if($this->persistente)
				{
					pg_close($this->conexion);
				}
				break;	
		}
		
	}
	
	/*
	 * ******************************************************************************************************
	 * CONSULTAS
	 * ******************************************************************************************************
	 */
	
	public function consultar($query) // DEVUELVE ARRAY CON LOS DATOS CONSULTADOS
	{	
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
				
				$query = $this->conexion->real_escape_string($query);
				$result = $this->conexion->query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query: ".$sql."->".$query."</div>";
					exit();
				}
				break;
			case Globals::TIPO_MYSQL:
				$query = mysql_real_escape_string($query);
				$result = mysql_query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query: ".mysql_error()."->".$result."</div>";
					exit();
				}
				break;
			case Globals::TIPO_POSTGRES:
				$query = pg_escape_string($query);
				$result = pg_query($this->conexion,$query);
				if(!$result)
				{
					echo "<div id='hackeError'>A ocurrido un error en la consulta</div>";
					exit();
				}
				break;	
		}
		return $this->getResult($result);
	}
	
	private function getResult($r)
	{
		$data = array();
		$globaldata = array();
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
			
				while($row = $r->fetch_array(MYSQL_ASSOC))
				{
					while($nombreKey = current($row))
					{
						$data[key($row)] = $row[key($row)];
						next($row);
					}
					$globaldata[] = $data;
				}
				$r->close();
				break;
			case Globals::TIPO_MYSQL:
				while($row = mysql_fetch_array($r, MYSQL_ASSOC))
				{
					while($nombreKey = current($row))
					{
						$data[key($row)] = $row[key($row)];
						next($row);
					}
					$globaldata[] = $data;
				}
				mysql_free_result($r);
				break;
			case Globals::TIPO_POSTGRES:
				while($row = pg_fetch_array($r, NULL, PGSQL_ASSOC))
				{
					while($nombreKey = current($row))
					{
						$data[key($row)] = $row[key($row)];
						next($row);
					}
					$globaldata[] = $data;
				}
				break;	
		}
		//echo "\nEL RESULTADO ES:".$data."\n\n";
		
		return $globaldata;
	}
	
	/*
	 * ******************************************************************************************************
	 * sqlDelete
	 * Devuelve true si se realizo correctamente o false si hubo un problema
	 * ******************************************************************************************************
	 */
	 public function sqlDelete($query)
	 {
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
				$query = $this->conexion->real_escape_string($query);
				$result = $this->conexion->query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query: ".$result." MYSQLI</div>";
					exit();
				}
				break;
			case Globals::TIPO_MYSQL:
				$query = mysql_real_escape_string($query);
				$result = mysql_query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query: ".mysql_error()." MYSQL</div>";
					exit();
				}break;
			case Globals::TIPO_POSTGRES:
				$query = pg_escape_string($query);
				$result = pg_query($this->conexion,$query);
				if(!$result)
				{
					echo "<div id='hackeError'>A ocurrido un error en la consulta</div>";
					exit();
				}
				break;
		}
		
		return true;
	 }
	
	/*
	 * ******************************************************************************************************
	 * ACTUALIZAR
	 * $tabla = 'nombre de la tabla'
	 * $arr = array( campo1 => valor, campo2 => valor, campo3 => valor, campoN => valor )
	 * $where = "campo1 = valor AND campo2 = valor OR etc"
	 * ******************************************************************************************************
	 */
	 public function actualizar($tabla, $arr, $where)
	 {
		$query = "UPDATE ".$tabla." set ";
		$campos = "";
		$valores = "";
		$first = true;
		foreach($arr as $key => $item)
		{
			if($first)
			{
				$campos .= $key;
				$first = false;
				
				switch($this->tipo)
				{
					case Globals::TIPO_MYSQLI:
						$valores .= "'".$this->conexion->real_escape_string($item)."'";
						break;
					case Globals::TIPO_MYSQL:
						$valores .= "'".mysql_real_escape_string($item)."'";
						break;
					case Globals::TIPO_POSTGRES:
						$valores .= "'".pg_escape_string($item)."'";
						break;
				}
				
				$query .= $campos."=".$valores;
				
				
			}else{
				$campos .= ",".$key;
				switch($this->tipo)
				{
					case Globals::TIPO_MYSQLI:
						$valores .= ",'".$this->conexion->real_escape_string($item)."'";
						break;
					case Globals::TIPO_MYSQL:
						$valores .= ",'".mysql_real_escape_string($item)."'";
						break;
					case Globals::TIPO_POSTGRES:
						$valores .= ",'".pg_escape_string($item)."'";
						break;
				}
				$query .= " ,".$campos."=".$valores;
			}
			
		}
		$query .= " WHERE ".$where;
		
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
				$result = $this->conexion->query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query UPDATE: ".$query."</div>";
					exit();	
				}
				break;
			case Globals::TIPO_MYSQL:
				$result = mysql_query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query UPDATE: ".mysql_error()."</div>";
					exit();
				}
				break;
			case Globals::TIPO_POSTGRES:
				$result = pg_query($this->conexion,$query);
				if(!$result)
				{
					echo "<div id='hackeError'>A ocurrido un error en la consulta</div>";
					exit();
				}
				break;
		}
		
		return true;
	 }
	
	/*
	 * ******************************************************************************************************
	 * INSERTAR
	 * $tabla = 'nombre de la tabla'
	 * $arr = array( campo1 => valor, campo2 => valor, campo3 => valor, campoN => valor )
	 * ******************************************************************************************************
	 */
	 public function insertar($tabla, $arr)
	 {
		$query = "INSERT INTO ".$tabla."(";
		$campos = "";
		$valores = "";
		$first = true;
		foreach($arr as $key => $item)
		{
			if($first)
			{
				switch($this->tipo)
				{
					case Globals::TIPO_MYSQLI:
						$valores .= "'".$this->conexion->real_escape_string($item)."'";
						break;
					case Globals::TIPO_MYSQL:
						$valores .= "'".mysql_real_escape_string($item)."'";
						break;
					case Globals::TIPO_POSTGRES:
						$valores .= "'".pg_escape_string($item)."'";
						break;
				}
				$campos .= $key;
				
				$first = false;
			}else{
				$campos .= ",".$key;
				
				switch($this->tipo)
				{
					case Globals::TIPO_MYSQLI:
						$valores .= ",'".$this->conexion->real_escape_string($item)."'";
						break;
					case Globals::TIPO_MYSQL:
						$valores .= ",'".mysql_real_escape_string($item)."'";
						break;
					case Globals::TIPO_POSTGRES:
						$valores .= ",'".pg_escape_string($item)."'";
						break;
				}
				
			}
		}
		$query .= $campos.") VALUES(".$valores.")";
		
		switch($this->tipo)
		{
			case Globals::TIPO_MYSQLI:
				$result = $this->conexion->query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query: ".$sql." Query: ".$query."</div>";
					exit();	
				}
				break;
			case Globals::TIPO_MYSQL:
				$result = mysql_query($query);
				if(!$result)
				{
					echo "<div id='hackeError'>Problema con el query: ".mysql_error()."</div>";
					exit();
				}break;
			case Globals::TIPO_POSTGRES:
				$result = pg_query($this->conexion,$query);
				if(!$result)
				{
					echo "<div id='hackeError'>A ocurrido un error en la consulta</div>";
					exit();
				}
				break;
		}
		
		return true;
	 }
	 
	 // funcion que limpia un string para ser insertado en base de datos de manera segura  evitando sqlinjection
	/* para usarlo ... 
	_clean($_POST);
	_clean($_GET);
	_clean($_REQUEST);
	*/
	public function limpiarString($str){
		return is_array($str) ? array_map('_clean', $str) : str_replace('\\', '\\\\', htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES));
	}
	
}
?>