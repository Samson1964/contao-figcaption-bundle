<?php

declare(strict_types=1);

/*
 * Dieses Bundle ersetzt in Bildunterschriften einen von Trennzeichen
 * umklammerten Text, damit sich z. B. der Fotograf gesondert auszeichnen lässt.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoFigcaptionBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Schachbulle\ContaoFigcaptionBundle\ContaoFigcaptionBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * Meldet das Bundle beim Contao Manager an.
     *
     * Das Bundle wird nach dem Contao-Core geladen, damit dessen DCA-Dateien
     * (hier tl_settings) bereits vorhanden sind und nur noch erweitert werden.
     *
     * @param ParserInterface $parser Wird von Contao für verschachtelte
     *                                Konfigurationen benötigt, hier ungenutzt
     *
     * @return array<BundleConfig> Die Bundle-Konfiguration dieses Pakets
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoFigcaptionBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
