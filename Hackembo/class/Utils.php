<?php
class Utils{

	// Valida si un email es valido, si el email tiene formato correcto devuelve true
	// caso contrario false. para versiones de php 5.2 +
	public function validarEmail($email)
	{
	  return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	//limpia un email y se asegura que todo este bien
	// versiones php 5.2+
	public function limpiarEmail($url)
	{
	  return filter_var($url, FILTER_SANITIZE_EMAIL);
	}

	// revisa que un email sea correcto, funcion muy similar a las anteriores
	// controla el email con otros calculos
	public function checkEmail($email)
	{
		$email_error = false;
		$Email = htmlspecialchars(stripslashes(strip_tags(trim($email)))); 
		if($Email == ''){ $email_error = true; }
		elseif (!eregi('^([a-zA-Z0-9._-])+@([a-zA-Z0-9._-])+\.([a-zA-Z0-9._-])([a-zA-Z0-9._-])+', $Email)) { $email_error = true; }
		else {
		list($Email, $domain) = split('@', $Email, 2);
			if (! checkdnsrr($domain, 'MX')) { $email_error = true; }
			else {
			$array = array($Email, $domain);
			$Email = implode('@', $array);
			}
		}
	 
		if (email_error) { return false; } else{return true;}
	}

	// devuelve true si el valor es del tipo numerico php5.2+
	public function validarNumerico($value)
	{
		return filter_var($value, FILTER_SANITIZE_NUMBER_INT); # int
	}


	//valida que lo que se ingresa sea un string o cadena de caracteres
	public function validarString($str)
	{
		return preg_match('/^[A-Za-z\s ]+$/', $str);
	}

	//limpia un string de caracteres extraños
	public function limpiarString($str)
	{
		return filter_var($str, FILTER_SANITIZE_STRIPPED); # only 'String' is allowed eg. '<br>HELLO</br>' => 'HELLO'
	}

	//valida que lo que se ingresa sea un alfanumerico, una variable compuesta de numeros y letras
	public function validarAlfanumerico($string)
	{
		return ctype_alnum ($string);
	}

	// comprueba la existencia de una URL
	public function url_exist($url)
	{
		$url = @parse_url($url);
	 
		if (!$url)
		{
			return false;
		}
	 
		$url = array_map('trim', $url);
		$url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];
		$path = (isset($url['path'])) ? $url['path'] : '';
	 
		if ($path == '')
		{
			$path = '/';
		}
	 
		$path .= (isset($url['query'])) ? '?$url[query]' : '';
	 
		if (isset($url['host']) AND $url['host'] != @gethostbyname($url['host']))
		{
			if (PHP_VERSION >= 5)
			{
				$headers = @get_headers('$url[scheme]://$url[host]:$url[port]$path');
			}
			else
			{
				$fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);
	 
				if (!$fp)
				{
					return false;
				}
				fputs($fp, 'HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n');
				$headers = fread($fp, 4096);
				fclose($fp);
			}
			$headers = (is_array($headers)) ? implode('\n', $headers) : $headers;
			return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
		}
		return false;
	}

	// valida que una url sea correcta , si esta bien escrita
	public function validarUrl($url){
		return preg_match('/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i', $url);
	}

	// limpia una url de caracteres raros
	public function limpiarUrl($url)
	{
	  return filter_var($url, FILTER_SANITIZE_URL);
	}

	// comprueba si existe una imagen , si la url de la imagen que se ingresa realmente existe.
	public function checkImagenUrl($url) {
		if(@file_get_contents($url,0,NULL,0,1)){return true;}else{ return false;}
	}

	// valida que la ip sea correcta, tenga todos sus numeros
	public function validarIP($IP){
		return preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$IP);
	}

	// comprueba si el usuario entro directamente o paso por un proxy para ser intentar visitar
	// el sitio de manera anonima, con este codigo se consigue la verdadera ip
	public function validarProxy(){
		if ($_SERVER['HTTP_X_FORWARDED_FOR']
		   || $_SERVER['HTTP_X_FORWARDED']
		   || $_SERVER['HTTP_FORWARDED_FOR']
		   || $_SERVER['HTTP_VIA']
		   || in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554))
		   || @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 30))
		{
			exit('Proxy detected');
		}
	}

	// valida un nombre de usuario con un minimo de 6 caracteres
	public function validarUsername($username){
		#alphabet, digit, @, _ and . are allow. Minimum 6 character. Maximum 50 characters (email address may be more)
		return preg_match('/^[a-zA-Z\d_@.]{6,50}$/i', $username);
	}

	// retorna true o false si el password es fuerte, tiene 8 caracteres al menos uno en mayuscula uno en minuscula y por lo menos un numero
	public function validarSeguridadPassword($password){
		#must contain 8 characters, 1 uppercase, 1 lowercase and 1 number
		return preg_match('/^(?=^.{8,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/', $password);
	}


	
	// funcion php para validar fechas
	public function validarFecha($date){
		/*
		#05/12/2109
		#05-12-0009
		#05.12.9909
		#05.12.99
		*/
		return preg_match('/^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.][0-9]?[0-9]?[0-9]{2})*$/', $date);
	}


	//funcion que valida si un color es valido
	public function validarColor($color){
		#CCC
		#CCCCC
		#FFFFF
		return preg_match('/^#(?:(?:[a-f0-9]{3}){1,2})$/i', $color);
	}


	
}
?>