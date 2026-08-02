<?php

declare(strict_types=1);

/**
 * Bildunterschriften ersetzen für Contao Open Source CMS
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 *
 * Contao lädt immer zuerst die englischen Sprachdateien und überschreibt sie
 * anschließend mit der eingestellten Backend-Sprache. Ohne diese Datei bleiben
 * die Felder auf einer englischsprachigen Installation unbeschriftet.
 */

/**
 * Legende
 */
$GLOBALS['TL_LANG']['tl_settings']['figcaption_legend'] = 'Image captions';

/**
 * Felder
 */
$GLOBALS['TL_LANG']['tl_settings']['figcaption_active'] = array('Enable replacement', 'Enable the replacement inside image captions (figcaption).');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_startTag'] = array('Opening delimiter', 'Opening delimiter inside the image caption. The source credit starts here.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_endTag'] = array('Closing delimiter', 'Closing delimiter inside the image caption. The source credit ends here.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_replace'] = array('Replace with', 'The placeholder %s contains the text between the delimiters.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_position'] = array('Position of the source credit', 'Defines whether the source credit stays inside the image caption or is moved out of it and placed right after the image.');

/**
 * Auswahlwerte
 */
$GLOBALS['TL_LANG']['tl_settings']['figcaption_position_options'] = array
(
	'caption' => 'Inside the image caption',
	'figure'  => 'Before the image caption, right after the image'
);
