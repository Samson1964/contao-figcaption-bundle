<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   figcaption
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2021
 */

/**
 * -------------------------------------------------------------------------
 * Frontend-Hooks
 * -------------------------------------------------------------------------
 */

$GLOBALS['TL_HOOKS']['modifyFrontendPage'][] = array('Schachbulle\ContaoFigcaptionBundle\Classes\Figcaption', 'Parser');

/**
 * -------------------------------------------------------------------------
 * Voreinstellungen Contao-BE System -> Einstellungen
 * -------------------------------------------------------------------------
 */

$GLOBALS['TL_CONFIG']['figcaption_startTag'] = '[';
$GLOBALS['TL_CONFIG']['figcaption_endTag'] = ']';
$GLOBALS['TL_CONFIG']['figcaption_replace'] = '<div class="rechte">%s</div>';
