<?php
include_once('Globals.php');
include_once('DataBase.php');
class Pagina{
	
	private $id = null;
	private $idioma = null;
	private $titulo = null;
	private $subtitulo = null;
	private $contenido = null;
	private $activo = null;
	private $permalink = null;
	
	private $db = null;
	
	function __construct($db = null /*Link a la conexion*/)
	{
		if(!$db)
		{
			$this->db = new DataBase();	
		}else{
			$this->db = $db;
		}
	}
	
	/* 	
		*****************************************************************************
		INSERTAR FOTO
		*****************************************************************************
	*/
	public function insertar_foto($descripcion, 
								$texto_alt, 
								$id_album,
								$categorias/*array id_categoria*/, 
								$keywords/*array id_keyword*/, 
								$perma, 
								$imagen/*archivo en si*/, 
								$url_imagen,
								$activo = true, 
								$descripcion_gn = null, 
								$texto_alt_gn = null, 
								$descripcion_in = null,
								$texto_alt_in = null, 
								$descripcion_pt = null, 
								$texto_alt_pt = null
								)
	{
		$verID = $this->db->consultar("SELECT COUNT(*) as count FROM f_fotos");
		$id = $verID['count'] + 1;
		
		$usuario= $_SESSION(GLOBALS::S_USUARIO);
		$fecha = date("dd/mm/aaaa");
		
		$ins = $this->db->insertar("f_fotos",array("id_foto"=>$id,"descripcion"=>$descripcion,"texto_alternativo"=>$texto_alt, "id_album"=>$id_album, "permalink"=>$perma, "imagen"=>$imagen, "url_imagen"=>$url_imagen, "activo"=>$activo, "descripcion_gn"=>$descripcion_gn, "texto_alternativo_gn"=>$texto_alt_gn, "descripcion_in"=>$descripcion_in, "texto_alternativo_in"=>$texto_alt_in, "descripcion_pt"=>$descripcion_pt, "texto_alternativo_pt"=>$texto_alt_pt));
		
		if(!$ins){ return false; }
		
		foreach($categorias as $i => $value)
		{
			$this->db->insertar("f_fotos_categorias", array("id_foto"=>$id, "id_categoria"=>$categorias[$i]));
		}
		
		foreach($keywords as $i => $value)
		{
			$this->db->insertar("f_fotos_keywords", array("id_foto"=>$id, "id_keywords"=>$keywords[$i]));
		}
		
		return true;
	}
	
	/* 	
		*****************************************************************************
		INSERTAR NOTICIA
		*****************************************************************************
	*/
	public function insertar_noticia($titulo, 
							$subtitulo, 
							$contenido,
							$categorias/*array id_categoria*/, 
							$keywords/*array id_keyword*/, 
							$perma, 
							$activo = true, 
							$titulo_gn = null, 
							$subtitulo_gn = null, 
							$contenido_gn = null, 
							$titulo_in = null, 
							$subtitulo_in = null, 
							$contenido_in = null,
							$titulo_pt = null, 
							$subtitulo_pt = null, 
							$contenido_pt = null
								   )
	{
		$verID = $this->db->consultar("SELECT COUNT(*) as count FROM b_noticias");
		$id = $verID['count'] + 1;
		
		$usuario= $_SESSION(GLOBALS::S_USUARIO);
		$fecha = date("dd/mm/aaaa");
		
		$ins = $this->db->insertar("b_noticias", array("id_noticia"=>$id,"titulo"=>$titulo,"subtitulo"=>$subtitulo,"contenido"=>$contenido,
		"titulo_gn"=>$titulo_gn, "subtitulo_gn"=>$subtitulo_gn, "contenido_gn"=>$contenido_gn,
		"titulo_in"=>$titulo_in, "subtitulo_in"=>$subtitulo_in, "contenido_in"=>$contenido_in,
		"titulo_pt"=>$titulo_pt, "subtitulo_pt"=>$subtitulo_pt, "contenido_pt"=>$contenido_pt,
		"usuario_alta"=>$usuario, "fecha_alta"=>$fecha, "usuario_ultima_modificacion"=>$usuario,"fecha_ultima_modificacion"=>$fecha, "permalink"=>$perma,"activo"=>$activo));
		
		if(!$ins){ return false; }
		
		foreach($categorias as $i => $value)
		{
			$this->db->insertar("b_noticias_categorias", array("id_noticia"=>$id, "id_categoria"=>$categorias[$i]));
		}
		
		foreach($keywords as $i => $value)
		{
			$this->db->insertar("b_noticias_keywords", array("id_noticia"=>$id, "id_keywords"=>$keywords[$i]));
		}
		
		return true;
     
	}
	
	/* 	
		*****************************************************************************
		INSERTAR PAGINA
		*****************************************************************************
	*/
	public function insertar_pagina(
                            $titulo, 
							$subtitulo, 
							$contenido,
							$categorias/*array*/, 
							$keywords/*array*/, 
							$perma, 
							$activo = true, 
                            $titulo_gn = null, 
							$subtitulo_gn = null, 
							$contenido_gn = null, 
                            $titulo_in = null, 
							$subtitulo_in = null, 
							$contenido_in = null,
                            $titulo_pt = null, 
							$subtitulo_pt = null, 
							$contenido_pt = null //OPCIONALES LOS PARAMETROS PARA IDIOMAS
							)
	{
		$verID = $this->db->consultar("SELECT COUNT(*) as count FROM p_paginas");
		$id = $verID['count'] + 1;
		
		$usuario= $_SESSION(GLOBALS::S_USUARIO);
		$fecha = date("dd/mm/aaaa");
        
		$ins = $this->db->insertar("p_paginas", array("id_pagina"=>$id,"titulo"=>$titulo,"subtitulo"=>$subtitulo,"contenido"=>$contenido,
		"titulo_gn"=>$titulo_gn, "subtitulo_gn"=>$subtitulo_gn, "contenido_gn"=>$contenido_gn,
		"titulo_in"=>$titulo_in, "subtitulo_in"=>$subtitulo_in, "contenido_in"=>$contenido_in,
		"titulo_pt"=>$titulo_pt, "subtitulo_pt"=>$subtitulo_pt, "contenido_pt"=>$contenido_pt,
		"usuario_alta"=>$usuario, "fecha_alta"=>$fecha, "usuario_ultima_modificacion"=>$usuario,"fecha_ultima_modificacion"=>$fecha, "permalink"=>$perma,"activo"=>$activo));
		
		if(!$ins){ return false; }
		
		foreach($categorias as $i => $value)
		{
			$this->db->insertar("p_paginas_categorias", array("id_pagina"=>$id, "id_categoria"=>$categorias[$i]));
		}
		
		foreach($keywords as $i => $value)
		{
			$this->db->insertar("p_paginas_keywords", array("id_pagina"=>$id, "id_keywords"=>$keywords[$i]));
		}
		
		return true;
            
	}
        
        
        /* 	
		*****************************************************************************
		INSERTAR VIDEO
		*****************************************************************************
	*/
                public function insertar_video(
                                                        $descripcion, 
                                                        $texto_alt, 
                                                        $id_album,
                                                        $categorias/*array id_categoria*/, 
                                                        $keywords/*array id_keyword*/, 
                                                        $perma, 
                                                        $video/*archivo en si OPCIONAL*/, 
                                                        $url_youtube,
                                                        $url_vimeo/*OPCIONAL*/,
                                                        $thumb /*URL DEL THUMBNAIL*/,
                                                        $activo = true, 
                                                        $descripcion_gn = null, 
                                                        $texto_alt_gn = null, 
                                                        $descripcion_in = null,
                                                        $texto_alt_in = null, 
                                                        $descripcion_pt = null, 
                                                        $texto_alt_pt = null
                                               )
	{
		$verID = $this->db->consultar("SELECT COUNT(*) as count FROM v_videos");
		$id = $verID['count'] + 1;
		
		$usuario= $_SESSION(GLOBALS::S_USUARIO);
		$fecha = date("dd/mm/aaaa");
		
		$ins = $this->db->insertar("v_videos",array("id_video"=>$id,"descripcion"=>$descripcion,"texto_alternativo"=>$texto_alt, 
                    "id_album"=>$id_album, "permalink"=>$perma, "video"=>$video, "url_imagen"=>$url_imagen, "activo"=>$activo, 
                    "descripcion_gn"=>$descripcion_gn, "texto_alternativo_gn"=>$texto_alt_gn, "descripcion_in"=>$descripcion_in, 
                    "texto_alternativo_in"=>$texto_alt_in, "descripcion_pt"=>$descripcion_pt, "texto_alternativo_pt"=>$texto_alt_pt,"url_youtube"=>$url_youtube,
                    "url_vimeo"=>$url_vimeo, "thumb"=>$thumb));
		
		if(!$ins){ return false; }
		
		foreach($categorias as $i => $value)
		{
			$this->db->insertar("v_videos_categorias", array("id_video"=>$id, "id_categoria"=>$categorias[$i]));
		}
		
		foreach($keywords as $i => $value)
		{
			$this->db->insertar("v_videos_keywords", array("id_video"=>$id, "id_keywords"=>$keywords[$i]));
		}
		
		return true;
	}
        
        
        /* 	
		*****************************************************************************
		INSERTAR AGENDA
		*****************************************************************************
	*/
                public function insertar_agenda(        $titulo, 
                                                        $descripcion, 
                                                        $fecha_agenda,
                                                        $hora_agenda,
                                                        $categorias/*array id_categoria*/, 
                                                        $keywords/*array id_keyword*/, 
                                                        $perma, 
                                                        $activo = true, 
                                                        $descripcion_gn = null, 
                                                        $titulo_gn = null, 
                                                        $descripcion_in = null,
                                                        $titulo_in = null, 
                                                        $descripcion_pt = null, 
                                                        $titulo_pt = null
                                                        )
	{
		$verID = $this->db->consultar("SELECT COUNT(*) as count FROM a_agenda");
		$id = $verID['count'] + 1;
		
		$usuario= $_SESSION(GLOBALS::S_USUARIO);
		$fecha = date("dd/mm/aaaa");
		
		$ins = $this->db->insertar("a_agenda",array("id_agenda"=>$id,"descripcion"=>$descripcion,"titulo"=>$titulo, 
                                            "permalink"=>$perma,"activo"=>$activo, "descripcion_gn"=>$descripcion_gn, "titulo_gn"=>$titulo_gn, 
                                            "descripcion_in"=>$descripcion_in, "titulo_in"=>$titulo_in, "descripcion_pt"=>$descripcion_pt, 
                                            "titulo_pt"=>$titulo_pt,"fecha_agenda"=>$fecha_agenda, "hora_agenda"=>$hora_agenda));
		
		if(!$ins){ return false; }
		
		foreach($categorias as $i => $value)
		{
			$this->db->insertar("a_agenda_categorias", array("id_agenda"=>$id, "id_categoria"=>$categorias[$i]));
		}
		
		foreach($keywords as $i => $value)
		{
			$this->db->insertar("a_agenda_keywords", array("id_agenda"=>$id, "id_keywords"=>$keywords[$i]));
		}
		
		return true;
	}
	
        /* 	
		*****************************************************************************
		INSERTAR ARCHIVOS
		*****************************************************************************
	*/
                public function insertar_archivo(       $titulo, 
                                                        $descripcion, 
                                                        $url,
                                                        $archivo /*OPCIONAL*/,
                                                        $extension,
                                                        $categorias/*array id_categoria*/, 
                                                        $keywords/*array id_keyword*/, 
                                                        $perma, 
                                                        $activo = true, 
                                                        $descripcion_gn = null, 
                                                        $titulo_gn = null, 
                                                        $descripcion_in = null,
                                                        $titulo_in = null, 
                                                        $descripcion_pt = null, 
                                                        $titulo_pt = null
                                                        )
	{
		$verID = $this->db->consultar("SELECT COUNT(*) as count FROM r_archivos");
		$id = $verID['count'] + 1;
		
		$usuario= $_SESSION(GLOBALS::S_USUARIO);
		$fecha = date("dd/mm/aaaa");
		
		$ins = $this->db->insertar("r_archivos",array("id_archivo"=>$id,"descripcion"=>$descripcion,"titulo"=>$titulo, 
                                            "permalink"=>$perma,"activo"=>$activo, "descripcion_gn"=>$descripcion_gn, "titulo_gn"=>$titulo_gn, 
                                            "descripcion_in"=>$descripcion_in, "titulo_in"=>$titulo_in, "descripcion_pt"=>$descripcion_pt, 
                                            "titulo_pt"=>$titulo_pt,"url"=>$url, "archivo"=>$archivo,"extension"=>$extension ));
		
		if(!$ins){ return false; }
		
		foreach($categorias as $i => $value)
		{
			$this->db->insertar("r_archivos_categorias", array("id_archivo"=>$id, "id_categoria"=>$categorias[$i]));
		}
		
		foreach($keywords as $i => $value)
		{
			$this->db->insertar("r_archivos_keywords", array("id_archivo"=>$id, "id_keywords"=>$keywords[$i]));
		}
		
		return true;
	}
        
       
        
}

?>