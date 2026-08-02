<?php

declare(strict_types=1);

/*
 * Dieses Bundle ersetzt in Bildunterschriften einen von Trennzeichen
 * umklammerten Text, damit sich z. B. der Fotograf gesondert auszeichnen lässt.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoFigcaptionBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * DependencyInjection-Extension des Bundles.
 *
 * Die Basisklasse aus dem HttpKernel wird bewusst verwendet: Sie existiert
 * sowohl in Symfony 5.4 (Contao 4.13) als auch in Symfony 7 (Contao 5.7),
 * während Symfony\Component\DependencyInjection\Extension\Extension erst ab
 * Symfony 6.1 zur Verfügung steht.
 */
class ContaoFigcaptionExtension extends Extension
{
    /**
     * Lädt die Service-Konfiguration des Bundles in den Container.
     *
     * @param array<mixed>    $configs   Zusammengeführte Bundle-Konfiguration; das
     *                                   Bundle besitzt keine eigene Semantik und
     *                                   wertet den Wert deshalb nicht aus
     * @param ContainerBuilder $container Der im Aufbau befindliche Service-Container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config'),
        );

        $loader->load('services.yaml');
    }
}
