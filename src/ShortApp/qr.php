<?php
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\QROptions;
require_once(AUTOLOAD_PATH);
if(class_exists('chillerlan\QRCode\QRCode', true)){
	$qr = (new QRCode(new QROptions([
		'addQuietzone'=> false,
		//'outputType'=> QRCode::OUTPUT_MARKUP_SVG,
		//'imageBase64'=> false,
		'scale'=> 2,
		'xmoduleValues'=> [
			1024=> [144,223,144],
			QRMatrix::M_ALIGNMENT=> [209, 255, 209],
			QRMatrix::M_FINDER=> [209, 255, 209],
			QRMatrix::M_FORMAT=> [144,223,144],
			QRMatrix::M_TIMING=> [144,223,144],

		],
	])));
	$vCard = 'BEGIN:VCARD
		VERSION:4.0
		ADR:;;;Akron;Ohio;;USA
		CATEGORIES:CSS,HTML,JavaScript,PHP
		FN:Toby Mackenzie
		EMAIL:public@tobymackenzie.com
		N:Mackenzie;Toby
		PHOTO:https://macn.me/_toby.jpg
		TITLE:Webmaster
		URL:https://www.tobmackenzie.com
		URL:https://macn.me
		END:VCARD'
	;
}
