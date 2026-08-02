<?php

declare(strict_types=1);

/**
 * Bildunterschriften ersetzen für Contao Open Source CMS
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 *
 * Der frühere Schutz „if (!defined('TL_ROOT')) die(...)“ steht hier bewusst
 * nicht mehr: Die Konstante TL_ROOT gibt es in Contao 5 nicht mehr, die Datei
 * hätte sich dort selbst abgeschaltet und alle Beschriftungen wären leer
 * geblieben. Der direkte Aufruf ist heute schon dadurch ausgeschlossen, dass
 * das Verzeichnis src/ nicht im Web-Root liegt.
 */

/**
 * Legende
 */
$GLOBALS['TL_LANG']['tl_settings']['figcaption_legend'] = 'Bildunterschriften';

/**
 * Felder
 */
$GLOBALS['TL_LANG']['tl_settings']['figcaption_active'] = array('Ersetzung aktivieren', 'Ersetzung der Bildunterschriften (figcaption) aktivieren.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_startTag'] = array('Start-Trennzeichen', 'Start-Trennzeichen in der Bildunterschrift. Hier beginnt die Quellenangabe.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_endTag'] = array('Ende-Trennzeichen', 'Ende-Trennzeichen in der Bildunterschrift. Hier endet die Quellenangabe.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_replace'] = array('Ersetzen durch', 'Der Platzhalter %s enthält den Text, der zwischen den Trennzeichen steht.');
$GLOBALS['TL_LANG']['tl_settings']['figcaption_position'] = array('Position der Quellenangabe', 'Legt fest, ob die Quellenangabe in der Bildunterschrift bleibt oder aus ihr herausgelöst und direkt hinter das Bild gesetzt wird.');

/**
 * Auswahlwerte
 */
$GLOBALS['TL_LANG']['tl_settings']['figcaption_position_options'] = array
(
	'caption' => 'In der Bildunterschrift',
	'figure'  => 'Vor der Bildunterschrift, direkt hinter dem Bild'
);
