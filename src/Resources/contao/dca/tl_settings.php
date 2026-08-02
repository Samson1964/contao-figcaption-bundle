<?php

declare(strict_types=1);

/**
 * Bildunterschriften ersetzen für Contao Open Source CMS
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 *
 * Hinweis zu den Labels: Sie werden als Referenz eingebunden (&$GLOBALS[…]).
 * Das ist wichtig, weil der DcaLoader beim Aufruf von contao:migrate keine
 * Sprachdateien lädt: Ein Referenzzugriff auf einen fehlenden Schlüssel legt
 * ihn stillschweigend an und trägt die Beschriftung nach, sobald die
 * Sprachdatei kommt. Ein lesender Zugriff — auch mit „?? null“ abgesichert —
 * würde den Wert dagegen beim Laden des DCA einfrieren.
 */

use Schachbulle\ContaoFigcaptionBundle\EventListener\FigcaptionListener;

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{figcaption_legend:hide},figcaption_active';

/**
 * Auswahlfeld anmelden.
 *
 * Ohne diesen Eintrag lehnt Contao das Ein- und Ausklappen der Unterpalette mit
 * „Bad request“ (HTTP 400) ab: Ajax::executePostActions prüft bei
 * toggleSubpalette, ob das Feld in __selector__ steht, und bricht sonst ab.
 * Die Zuweisung mit [] ist Absicht — tl_settings bringt von Haus aus keine
 * Selektorenliste mit, andere Erweiterungen können aber schon eine angelegt haben.
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'figcaption_active';

/**
 * Unterpalette: nur sichtbar, wenn die Ersetzung eingeschaltet ist
 */
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['figcaption_active'] = 'figcaption_startTag,figcaption_endTag,figcaption_replace,figcaption_position';

/**
 * Felder
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_active'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_active'],
	'inputType'                           => 'checkbox',
	'eval'                                => array
	(
		'tl_class'                        => 'w50',
		'isBoolean'                       => true,
		'submitOnChange'                  => true
	)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_startTag'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_startTag'],
	'inputType'                           => 'text',
	'eval'                                => array
	(
		'tl_class'                        => 'w50 clr'
	)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_endTag'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_endTag'],
	'inputType'                           => 'text',
	'eval'                                => array
	(
		'tl_class'                        => 'w50'
	)
);

/**
 * Die Ersetzung enthält bewusst HTML (Standard: <div class="rechte">%s</div>).
 * allowHtml lässt die Eingabe zu, decodeEntities=false verhindert, dass Contao
 * die spitzen Klammern beim Speichern in Entities zurückverwandelt.
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_replace'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_replace'],
	'inputType'                           => 'text',
	'eval'                                => array
	(
		'tl_class'                        => 'w50 clr',
		'allowHtml'                       => true,
		'decodeEntities'                  => false
	)
);

/**
 * Legt fest, wohin die fertige Ersetzung geschrieben wird. Die Werte stammen
 * aus den Konstanten POSITION_* des FigcaptionListener, damit Einstellung und
 * Auswertung nicht auseinanderlaufen können.
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_position'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_position'],
	'inputType'                           => 'select',
	'options'                             => array(FigcaptionListener::POSITION_CAPTION, FigcaptionListener::POSITION_FIGURE),
	'reference'                           => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_position_options'],
	'eval'                                => array
	(
		'tl_class'                        => 'w50'
	)
);
