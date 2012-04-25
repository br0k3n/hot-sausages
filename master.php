<?php

	/**
	File di configurazione.
	
	Questo file definisce le seguenti configurazioni: impostazioni MySQL e
	Chiavi Segrete.
	E' possibile ottenere le impostazioni per MySQL dal proprio fornitore
	di hosting.
	 
	**/
	
	
	/**
	Sezione DB; host, user, password e nome db.
	**/
	
	define('HOST', 'dev.bamboostudio.it');
	
	define('USER', 'bamboost');
	
	define('PASSWD', 's9;$]WE2hLSw');
	
	define('DB', 'bamboost_impresapercassi');
	
	define('LOGINTAB', 'autenticati');
	
	define('FILESTAB', 'files');
	
	/**
	Chiavi univoche di autenticazione.
	**/
	
	define('SALT', '$kKG[%b2~B|+;dlfmC.;A$?,^8|gngBOpc4L.S%JnayH#/DqH0F59*|F*r6ot}l0');
	
	define('KEY', '34rvb408u3t3f3f3aad323');
	
	/**
	Path di upload
	**/
	
	define('TMP_UPLOAD', '../tmp/file.tmp');
	
	define('FILES_UPLOAD', '../upload/files/');
	
	define('IMAGES_UPLOAD', '../upload/files/');
	
	/**
	Dati filtrati
	**/
	
	define('FILTER', serialize(array("submit")));
	
	/**
	Immagini
	**/
	
	define('IMAGES', serialize(array("scale" => "scaleMax", "width" => 1000, "height" => 1000)));
	
	/**
	Require classes
	**/
	
	require_once "../include/classes/class.root.php";
	require_once "../include/classes/class.auth.php";
	require_once "../include/classes/class.object.php";
	require_once "../include/classes/class.db.php";
	require_once "../include/classes/class.document.php";
	require_once "../include/classes/class.file.php";
	require_once "../include/classes/class.image.php";
	require_once "../include/classes/class.vimeo.php";
	require_once "../include/classes/functions.php";
?>