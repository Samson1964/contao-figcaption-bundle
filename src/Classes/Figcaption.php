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

	public function Parser($buffer, $templateName)
	{
		return $buffer;
	}
}
