<?php
/**
 * CLASE PARA ENCRIPTAR Y DESENCRIPTAR CONTRASEÑAS
 
 * UTILIZACIÓN
 $texto = "Por ejemplo";
 
 // Encriptamos el texto
 $texto_encriptado = Encrypter::encrypt($texto);
 
 // Desencriptamos el texto
 $texto_original = Encrypter::decrypt($texto_encriptado);
 
 if ($texto == $texto_original) echo 'Encriptación / Desencriptación realizada correctamente.';
 
 *
 * @author DIEGO GONZALEZ
 */
class Encrypter {
	private static $Key = "centrodecomputos"; // SEMILLA PARA USARLA DE CLAVE PARA ENCRIPTAR
	public static function encrypt($input) {
		$output = base64_encode ( mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, md5 ( Encrypter::$Key ), $input, MCRYPT_MODE_CBC, md5 ( md5 ( Encrypter::$Key ) ) ) );
		return $output;
	}
	public static function decrypt($input) {
		$output = rtrim ( mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, md5 ( Encrypter::$Key ), base64_decode ( $input ), MCRYPT_MODE_CBC, md5 ( md5 ( Encrypter::$Key ) ) ), "\0" );
		return $output;
	}
}

?>