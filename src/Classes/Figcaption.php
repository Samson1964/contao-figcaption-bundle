<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   fh-counter
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

namespace Schachbulle\ContaoFigcaptionBundle\Classes;

class Figcaption extends \Frontend
{

	static function replaceCopyright($string)
	{
		$tag1 = $GLOBALS['TL_CONFIG']['figcaption_startTag'];
		$tag2 = $GLOBALS['TL_CONFIG']['figcaption_endTag'];
		$replace = $GLOBALS['TL_CONFIG']['figcaption_replace'];
		
		// Nach Copyright per Regex suchen
		$found = preg_match('/(\\'.$tag1.'.+\\'.$tag2.')/', $string, $treffer, PREG_OFFSET_CAPTURE);
		if($found)
		{
			// Copyright gefunden, Länge und Position inkl. Start- und Ende-Tag speichern
			$cplen = strlen($treffer[0][0]);
			$cppos = $treffer[0][1];
			// Copyright ersetzen
			//$cpstr = str_replace($tag1,"<div class=\"rechte\">",$treffer[0][0]);
			//$cpstr = str_replace($tag2,"</div>",$cpstr);
			$cpstr = sprintf($replace, substr($treffer[0][0],strlen($tag1),-strlen($tag2))); // Ersetzung, dabei die Tags vorher ausfiltern
			// Restliche Bildunterschrift extrahieren
			$string = substr($string,0,$cppos).substr($string,$cppos+$cplen);
			// Copyright hinzufügen
			return $cpstr.$string;
		}

		return $string;
	}

	public function Parser($buffer, $templateName)
	{
		$pattern = '/<figcaption ?(.*)>(.+)<\/figcaption>/Usi';
		$neu = preg_replace_callback($pattern, array($this,'text2html_callback'), $buffer);
		
		return $neu;
	}

	static function text2html_callback($treffer)
	{
		// $treffer[0] enthält das komplette Suchmuster
		// $treffer[1] enthält nur die Attribute von figcaption
		// $treffer[2] enthält nur den Inhalt von figcaption
	
		$string = self::replaceCopyright($treffer[2]);
		//return $treffer[0];
		return '<figcaption'.$treffer[1].'>'.$string.'</figcaption>';
	}
}
