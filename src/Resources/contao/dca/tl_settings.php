<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   fen
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'figcaption_active';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{figcaption_legend:hide},figcaption_active';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['figcaption_active'] = 'figcaption_startTag,figcaption_endTag,figcaption_replace';

/**
 * fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_active'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_active'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true, 'submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_startTag'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_startTag'],
	'inputType'                           => 'text',
	'eval'                                => array
	(
		'tl_class'                        => 'w50 clr',
	),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_endTag'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_endTag'],
	'inputType'                           => 'text',
	'eval'                                => array
	(
		'tl_class'                        => 'w50',
	),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['figcaption_replace'] = array
(
	'label'                               => &$GLOBALS['TL_LANG']['tl_settings']['figcaption_replace'],
	'inputType'                           => 'text',
	'eval'                                => array
	(
		'tl_class'                        => 'w50',
		'allowHtml'                       => true,
		'decodeEntities'                  => false
	),
);
