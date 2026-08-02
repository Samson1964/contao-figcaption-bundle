<?php

declare(strict_types=1);

/*
 * Dieses Bundle ersetzt in Bildunterschriften einen von Trennzeichen
 * umklammerten Text, damit sich z. B. der Fotograf gesondert auszeichnen lässt.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

use Schachbulle\ContaoFigcaptionBundle\EventListener\FigcaptionListener;

/*
 * -------------------------------------------------------------------------
 * Voreinstellungen für System -> Einstellungen
 * -------------------------------------------------------------------------
 *
 * Diese Werte greifen so lange, bis sie im Backend gespeichert und damit in
 * die system/config/localconfig.php geschrieben werden. Der Hook selbst wird
 * nicht mehr hier registriert, sondern über das Attribut AsHook am
 * FigcaptionListener (siehe src/Resources/config/services.yaml).
 */

$GLOBALS['TL_CONFIG']['figcaption_active'] = '1';
$GLOBALS['TL_CONFIG']['figcaption_startTag'] = '[';
$GLOBALS['TL_CONFIG']['figcaption_endTag'] = ']';
$GLOBALS['TL_CONFIG']['figcaption_replace'] = '<div class="rechte">%s</div>';
$GLOBALS['TL_CONFIG']['figcaption_position'] = FigcaptionListener::POSITION_CAPTION;
