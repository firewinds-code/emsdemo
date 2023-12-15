<?php
function encrypt($string, $type)
{
	$iv = 'cogenteservnoida';
	$key = "cogent";
	$options = 0;
	$ciphering = "AES-128-CTR";
	if ($type == "encrypt") {
		return openssl_encrypt($string, $ciphering, $key, $options, $iv);
	}
	if ($type == "decrypt") {
		return openssl_decrypt($string, $ciphering, $key, $options, $iv);
	}
}
//echo $a= encrypt("bachan","encrypt");
