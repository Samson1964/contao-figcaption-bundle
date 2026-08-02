<?php

declare(strict_types=1);

/*
 * Dieses Bundle ersetzt in Bildunterschriften einen von Trennzeichen
 * umklammerten Text, damit sich z. B. der Fotograf gesondert auszeichnen lässt.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoFigcaptionBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Schachbulle\ContaoFigcaptionBundle\EventListener\FigcaptionListener;

/**
 * Testet die Ersetzung der Quellenangabe in Bildunterschriften.
 *
 * Geprüft wird ausschließlich replaceCaptions(): Diese Methode bekommt die
 * Einstellungen als Parameter und kommt deshalb ohne laufendes
 * Contao-Framework aus. __invoke() liest lediglich Contao\Config aus und
 * reicht die Werte weiter.
 */
class FigcaptionListenerTest extends TestCase
{
    private const START = '[';
    private const END = ']';
    private const REPLACE = '<div class="rechte">%s</div>';

    /**
     * Stellt sicher, dass die Quellenangabe aus der Mitte des Textes
     * herausgelöst und als eigenes Element vorangestellt wird.
     */
    public function testErsetztQuellenangabeInDerMitte(): void
    {
        $html = '<figcaption>Links steht Hans Mustermann,[Holger Mustermann] rechts Berta Mustermann.</figcaption>';

        $this->assertSame(
            '<figcaption><div class="rechte">Holger Mustermann</div>Links steht Hans Mustermann, rechts Berta Mustermann.</figcaption>',
            $this->replace($html),
        );
    }

    /**
     * Bildunterschriften ohne Trennzeichen müssen unverändert bleiben.
     */
    public function testLaesstBildunterschriftOhneQuellenangabeUnveraendert(): void
    {
        $html = '<figcaption>Eine ganz normale Bildunterschrift.</figcaption>';

        $this->assertSame($html, $this->replace($html));
    }

    /**
     * Attribute am figcaption-Tag (z. B. class oder itemprop) dürfen beim
     * Zusammenbauen nicht verloren gehen und nicht mit dem Tag-Namen
     * verschmelzen.
     */
    public function testErhaeltAttributeDesFigcaptionTags(): void
    {
        $html = '<figcaption class="caption" itemprop="caption">Bild [Foto: Meier]</figcaption>';

        $this->assertSame(
            '<figcaption class="caption" itemprop="caption"><div class="rechte">Foto: Meier</div>Bild </figcaption>',
            $this->replace($html),
        );
    }

    /**
     * Mehrere Bilder auf einer Seite müssen einzeln bearbeitet werden. Ein
     * gieriges Suchmuster würde alles zwischen der ersten und der letzten
     * Bildunterschrift zusammenfassen.
     */
    public function testBearbeitetMehrereBildunterschriftenEinzeln(): void
    {
        $html = '<figure><figcaption>Erstes [A]</figcaption></figure>'
            .'<figure><figcaption>Zweites [B]</figcaption></figure>';

        $this->assertSame(
            '<figure><figcaption><div class="rechte">A</div>Erstes </figcaption></figure>'
            .'<figure><figcaption><div class="rechte">B</div>Zweites </figcaption></figure>',
            $this->replace($html),
        );
    }

    /**
     * Trennzeichen sind frei konfigurierbar und dürfen auch mehrstellig sein
     * oder Sonderzeichen der Regex-Syntax enthalten. Ohne preg_quote wäre das
     * Suchmuster ungültig.
     */
    public function testVertraegtMehrstelligeUndRegexRelevanteTrennzeichen(): void
    {
        $html = '<figcaption>Bild ((Foto: Schulz))</figcaption>';

        $listener = new FigcaptionListener();

        $this->assertSame(
            '<figcaption><div class="rechte">Foto: Schulz</div>Bild </figcaption>',
            $listener->replaceCaptions($html, '((', '))', self::REPLACE),
        );
    }

    /**
     * Enthält die Ersetzungsvorlage ein Prozentzeichen, das nicht zu einer
     * Formatangabe gehört, darf das nicht zum Abbruch führen. sprintf() würde
     * unter PHP 8 einen ValueError werfen.
     */
    public function testVertraegtProzentzeichenInDerVorlage(): void
    {
        $html = '<figcaption>Bild [Foto: Meier]</figcaption>';

        $listener = new FigcaptionListener();

        $this->assertSame(
            '<figcaption><span style="width:50%">Foto: Meier</span>Bild </figcaption>',
            $listener->replaceCaptions($html, '[', ']', '<span style="width:50%">%s</span>'),
        );
    }

    /**
     * Sind Trennzeichen oder Ersetzung nicht gepflegt, wird nichts angefasst.
     * Ohne diese Prüfung würde ein leeres Suchmuster an jeder Position greifen.
     *
     * @dataProvider unvollstaendigeEinstellungen
     *
     * @param string $startTag Start-Trennzeichen aus den Einstellungen
     * @param string $endTag   Ende-Trennzeichen aus den Einstellungen
     * @param string $replace  Ersetzungsvorlage aus den Einstellungen
     */
    public function testLaesstMarkupBeiUnvollstaendigenEinstellungenUnveraendert(string $startTag, string $endTag, string $replace): void
    {
        $html = '<figcaption>Bild [Foto: Meier]</figcaption>';

        $listener = new FigcaptionListener();

        $this->assertSame($html, $listener->replaceCaptions($html, $startTag, $endTag, $replace));
    }

    /**
     * Liefert die Kombinationen unvollständiger Einstellungen für den
     * zugehörigen Test.
     *
     * @return array<string, array{string, string, string}>
     */
    public function unvollstaendigeEinstellungen(): array
    {
        return [
            'ohne Start-Trennzeichen' => ['', ']', self::REPLACE],
            'ohne Ende-Trennzeichen' => ['[', '', self::REPLACE],
            'ohne Ersetzung' => ['[', ']', ''],
        ];
    }

    /**
     * Im Modus POSITION_FIGURE wird die Quellenangabe aus der Bildunterschrift
     * herausgelöst und als Geschwisterelement davor gesetzt, also direkt hinter
     * das Bild.
     */
    public function testZiehtQuellenangabeAusDerBildunterschriftHeraus(): void
    {
        $html = '<figure class="image_container">'
            .'<a href="bild.jpg"><img src="klein.jpg" alt=""></a>'
            .'<figcaption class="caption">Links steht Hans,[Holger Mustermann] rechts Berta.</figcaption>'
            .'</figure>';

        $this->assertSame(
            '<figure class="image_container">'
            .'<a href="bild.jpg"><img src="klein.jpg" alt=""></a>'
            .'<div class="rechte">Holger Mustermann</div>'
            .'<figcaption class="caption">Links steht Hans, rechts Berta.</figcaption>'
            .'</figure>',
            $this->replace($html, true),
        );
    }

    /**
     * Auch im Modus POSITION_FIGURE muss jede <figure> für sich bearbeitet
     * werden, damit die Quellenangaben nicht beim falschen Bild landen.
     */
    public function testZiehtQuellenangabeBeiMehrerenBildernJeweilsEinzelnHeraus(): void
    {
        $html = '<figure><img src="1.jpg"><figcaption>Erstes [A]</figcaption></figure>'
            .'<figure><img src="2.jpg"><figcaption>Zweites [B]</figcaption></figure>';

        $this->assertSame(
            '<figure><img src="1.jpg"><div class="rechte">A</div><figcaption>Erstes </figcaption></figure>'
            .'<figure><img src="2.jpg"><div class="rechte">B</div><figcaption>Zweites </figcaption></figure>',
            $this->replace($html, true),
        );
    }

    /**
     * Steht eine Bildunterschrift nicht in einer <figure>, greift auch im Modus
     * POSITION_FIGURE die Rückfallebene. Sonst blieben die Trennzeichen
     * sichtbar im Text stehen.
     */
    public function testErsetztImModusFigureAuchBildunterschriftenOhneFigure(): void
    {
        $html = '<div class="eigenes"><figcaption>Bild [Foto: Meier]</figcaption></div>';

        $this->assertSame(
            '<div class="eigenes"><figcaption><div class="rechte">Foto: Meier</div>Bild </figcaption></div>',
            $this->replace($html, true),
        );
    }

    /**
     * Eine <figure> ohne Quellenangabe darf im Modus POSITION_FIGURE nicht
     * angefasst werden – auch nicht durch den zweiten Durchgang.
     */
    public function testLaesstFigureOhneQuellenangabeUnveraendert(): void
    {
        $html = '<figure><img src="1.jpg"><figcaption>Ganz normale Bildunterschrift.</figcaption></figure>';

        $this->assertSame($html, $this->replace($html, true));
    }

    /**
     * Ruft die Ersetzung mit der Standardkonfiguration des Bundles auf.
     *
     * @param string $html             Das zu bearbeitende Markup
     * @param bool   $moveOutOfCaption true entspricht dem Modus POSITION_FIGURE
     *
     * @return string Das Ergebnis der Ersetzung
     */
    private function replace(string $html, bool $moveOutOfCaption = false): string
    {
        return (new FigcaptionListener())->replaceCaptions($html, self::START, self::END, self::REPLACE, $moveOutOfCaption);
    }
}
