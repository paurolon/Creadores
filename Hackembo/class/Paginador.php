<?php
include_once('Globals.php');
include_once('DataBase.php');

class Paginador{
    
	private $inicio;
    private $cantidad;
    private $fin;
    private $tabla;
	private $query;
	private $campos;
	private $where;
    
    private $db;
    
    function __construct($tabla, $campos, $where = null, $db  = null) {
        
       if(!$db){
            $this->db = new DataBase();
        }
        else{
            $this->db = $db;
        }
        
		$this->tabla = $tabla;
        $this->campos = $campos;
		$this->where = $where;
        
    }
	
	public function paginar($inicio, $cantidad)
	{
		$this->inicio = $inicio;
        $this->cantidad = $cantidad;
        $this->fin = $this->inicio + $this->cantidad;
		$query = "SELECT ";
		$first = true;
		
		foreach($this->campos as $i)
		{
                        if($first){
                            $query.= $i;
                            $first = FALSE;
                        }
                        else {
                            $query.= ",".$i;
                        }
		}
                
        $query.= " FROM $this->tabla";
        
        if ($where){
            $query.=" WHERE $this->where";
        }
        
        $query.= " LIMIT $this->inicio, $this->fin;";
		        
        $res = $this->db->consultar($query);
		return $res;
	}
    
}
?>
