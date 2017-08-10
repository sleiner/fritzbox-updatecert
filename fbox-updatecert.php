#!/usr/local/bin/php
<?php
    $xml = simplexml_load_file($argv[1]);
    $fbox = "https://" . (string) $xml->fbox;
    $fboxpasswd = (string) $xml->fboxpasswd;
    $certpasswd = (string) $xml->certpasswd;
    $certdata = file_get_contents($argv[2]) . file_get_contents($argv[3]);

    $context = stream_context_create(array(
	'ssl' => array(
	    'verify_peer'      => false,
	    'verify_peer_name' =>false,
	),
    ));
    libxml_set_streams_context($context);

    $xml = simplexml_load_file($fbox . "/login_sid.lua");
    $sid = (string) $xml->SID;
    $challenge = (string) $xml->Challenge;

    $response = $challenge . "-" . md5(mb_convert_encoding($challenge . '-' . $fboxpasswd, "UCS-2LE", "UTF-8"));
    $xml = simplexml_load_file($fbox . "/login_sid.lua?sid=" . $sid . "&username=&response=" . $response);
    $sid = (string) $xml->SID;

    $boundary = "---------------------------" . strftime("%Y%m%d%H%M%S");
    $data = "--" . $boundary . "\r\n" .
    	        "Content-Disposition: form-data; name=\"sid\"\r\n" .
		"\r\n" .
		$sid . "\r\n" .
	     "--" . $boundary . "\r\n" .
		"Content-Disposition: form-data; name=\"BoxCertPassword\"\r\n" .
		"\r\n" .
		$certpasswd . "\r\n" .
	     "--" . $boundary . "\r\n" .
		"Content-Disposition: form-data; name=\"BoxCertImportFile\"; filename=\"BoxCert.pem\"\r\n" .
		"Content-Type: application/octet-stream\r\n" .
		"\r\n" .
		$certdata . "\r\n" .
	     "--" . $boundary . "--";

    $context = stream_context_create(array(
        'http' => array(
            'method'  => "post",
	    'header'  => "Content-type: multipart/form-data boundary=" . $boundary,
	    'content' => $data,
	),
	'ssl' => array(
	    'verify_peer'      => false,
	    'verify_peer_name' =>false,
	),
    ));

    $response = file_get_contents($fbox . "/cgi-bin/firmwarecfg", false, $context);
    print_r($response);
    echo "\n";
?>
