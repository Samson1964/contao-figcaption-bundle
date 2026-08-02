<?php

declare(strict_types=1);

/*
 * Dieses Bundle ersetzt in Bildunterschriften einen von Trennzeichen
 * umklammerten Text, damit sich z. B. der Fotograf gesondert auszeichnen lässt.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoFigcaptionBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

/**
 * Nachbearbeitung des fertigen Frontend-Markups.
 *
 * Der Hook "modifyFrontendPage" liefert das komplette, bereits gerenderte
 * HTML einer Seite. Dieser Weg wurde bewusst gewählt, weil Bildunterschriften
 * in Contao aus sehr vielen unterschiedlichen Templates stammen können
 * (Inhaltselemente, Nachrichten, Bildergalerien, eigene Templates). Ein Hook
 * pro Template müsste jedes davon einzeln kennen; die Nachbearbeitung des
 * Seitenpuffers erwischt dagegen jedes <figcaption>-Element.
 *
 * Die Registrierung erfolgt über das Attribut AsHook, das sowohl Contao 4.13
 * als auch Contao 5 kennt. Eine zusätzliche Eintragung in der config.php ist
 * deshalb nicht nötig und würde den Hook doppelt ausführen.
 */
#[AsHook('modifyFrontendPage')]
class FigcaptionListener
{
    /**
     * Die Quellenangabe bleibt innerhalb des <figcaption>-Elements und wird
     * dort dem übrigen Text vorangestellt. Verhalten bis Version 3.0.0.
     */
    public const POSITION_CAPTION = 'caption';

    /**
     * Die Quellenangabe wird aus dem <figcaption>-Element herausgelöst und
     * als Geschwisterelement unmittelbar davor in die <figure> gesetzt, also
     * direkt hinter das Bild.
     */
    public const POSITION_FIGURE = 'figure';

    /**
     * Ersetzt in allen Bildunterschriften der Seite die Quellenangabe.
     *
     * Wird von Contao am Ende des Seitenaufbaus aufgerufen. Ist die Ersetzung
     * in den Einstellungen abgeschaltet oder sind die Trennzeichen bzw. die
     * Ersetzung nicht gepflegt, wird das Markup unverändert zurückgegeben.
     *
     * @param string $buffer       Das komplette HTML der Seite
     * @param string $templateName Name des Seitenlayout-Templates (z. B. "fe_page"),
     *                             wird hier nicht ausgewertet, gehört aber zur
     *                             Signatur des Contao-Hooks
     *
     * @return string Das HTML mit ersetzten Bildunterschriften; im Fehlerfall
     *                (ungültiges Suchmuster) das unveränderte Original
     */
    public function __invoke(string $buffer, string $templateName): string
    {
        if (!Config::get('figcaption_active')) {
            return $buffer;
        }

        // Billiger Vorabtest: die meisten Seiten enthalten gar keine Bildunterschrift
        if (!str_contains($buffer, '<figcaption')) {
            return $buffer;
        }

        return $this->replaceCaptions(
            $buffer,
            (string) Config::get('figcaption_startTag'),
            (string) Config::get('figcaption_endTag'),
            (string) Config::get('figcaption_replace'),
            self::POSITION_FIGURE === Config::get('figcaption_position'),
        );
    }

    /**
     * Bearbeitet jedes <figcaption>-Element im übergebenen Markup.
     *
     * Die Methode ist bewusst öffentlich und nimmt die Einstellungen als
     * Parameter entgegen, statt sie selbst aus der Config zu lesen. Dadurch
     * lässt sie sich ohne laufendes Contao-Framework testen.
     *
     * @param string $startTag         Trennzeichen, an dem die Quellenangabe beginnt (z. B. "[")
     * @param string $endTag           Trennzeichen, an dem die Quellenangabe endet (z. B. "]")
     * @param string $replace          Vorlage der Ersetzung; "%s" steht für den Text
     *                                 zwischen den Trennzeichen (z. B. "<div class=\"rechte\">%s</div>")
     * @param bool   $moveOutOfCaption true setzt die Quellenangabe vor das
     *                                 <figcaption>-Element statt hinein; siehe
     *                                 die Konstanten POSITION_*
     *
     * @return string Das bearbeitete HTML. Ist eine der drei Einstellungen leer
     *                oder schlägt die Ersetzung fehl, kommt das Original zurück.
     */
    public function replaceCaptions(string $buffer, string $startTag, string $endTag, string $replace, bool $moveOutOfCaption = false): string
    {
        if ('' === $startTag || '' === $endTag || '' === $replace) {
            return $buffer;
        }

        if ($moveOutOfCaption) {
            $result = preg_replace_callback(
                '#<figure\b[^>]*>.*?</figure>#is',
                fn (array $matches): string => $this->moveSourceOutOfCaption($matches[0], $startTag, $endTag, $replace),
                $buffer,
            );

            $buffer = $result ?? $buffer;
        }

        // Zweiter Durchgang. Im Modus POSITION_CAPTION ist das der eigentliche
        // Weg; im Modus POSITION_FIGURE dient er als Rückfallebene für
        // Bildunterschriften, die gar nicht in einer <figure> stehen. Dort
        // bereits verarbeitete Elemente enthalten keine Trennzeichen mehr und
        // bleiben deshalb unberührt.
        $result = preg_replace_callback(
            '#<figcaption([^>]*)>(.+?)</figcaption>#is',
            fn (array $matches): string => '<figcaption'.$matches[1].'>'.$this->prependSource($matches[2], $startTag, $endTag, $replace).'</figcaption>',
            $buffer,
        );

        return $result ?? $buffer;
    }

    /**
     * Zieht die Quellenangabe aus der Bildunterschrift einer einzelnen <figure>
     * heraus und setzt sie unmittelbar vor das <figcaption>-Element.
     *
     * Damit landet die Quellenangabe als direktes Kind der <figure> hinter dem
     * Bild. Bewusst wird nicht versucht, sie in den umgebenden Link oder einen
     * Container des Themes einzufügen: Diese Struktur unterscheidet sich von
     * Theme zu Theme, die Position vor der <figcaption> gibt es dagegen immer.
     * Für die Darstellung genügt ohnehin CSS – wird die Quellenangabe absolut
     * positioniert, ist ihre Stelle im Quelltext ohne Bedeutung.
     *
     * @param string $figure   Das komplette Markup einer <figure> inklusive der Tags
     * @param string $startTag Trennzeichen, an dem die Quellenangabe beginnt
     * @param string $endTag   Trennzeichen, an dem die Quellenangabe endet
     * @param string $replace  Vorlage der Ersetzung mit dem Platzhalter "%s"
     *
     * @return string Die umgebaute <figure>, oder das Original, wenn sie keine
     *                Bildunterschrift oder keine Quellenangabe enthält
     */
    private function moveSourceOutOfCaption(string $figure, string $startTag, string $endTag, string $replace): string
    {
        if (1 !== preg_match('#<figcaption([^>]*)>(.*?)</figcaption>#is', $figure, $matches, PREG_OFFSET_CAPTURE)) {
            return $figure;
        }

        $extracted = $this->extractSource($matches[2][0], $startTag, $endTag);

        if (null === $extracted) {
            return $figure;
        }

        [$source, $rest] = $extracted;

        $offset = $matches[0][1];
        $length = \strlen($matches[0][0]);

        return substr($figure, 0, $offset)
            .$this->fillTemplate($replace, $source)
            .'<figcaption'.$matches[1][0].'>'.$rest.'</figcaption>'
            .substr($figure, $offset + $length);
    }

    /**
     * Stellt die Quellenangabe dem übrigen Text der Bildunterschrift voran.
     *
     * Der von den Trennzeichen umklammerte Text wird an seiner ursprünglichen
     * Stelle entfernt und – in die Vorlage eingesetzt – vorangestellt. Das
     * Voranstellen ist Absicht: die Standardvorlage ist ein eigenes Element
     * (<div class="rechte">), das per CSS ausgezeichnet wird.
     *
     * @param string $caption  Der reine Inhalt eines <figcaption>-Elements
     * @param string $startTag Trennzeichen, an dem die Quellenangabe beginnt
     * @param string $endTag   Trennzeichen, an dem die Quellenangabe endet
     * @param string $replace  Vorlage der Ersetzung mit dem Platzhalter "%s"
     *
     * @return string Die umgebaute Bildunterschrift, oder das Original, wenn
     *                keine Quellenangabe gefunden wurde
     */
    private function prependSource(string $caption, string $startTag, string $endTag, string $replace): string
    {
        $extracted = $this->extractSource($caption, $startTag, $endTag);

        if (null === $extracted) {
            return $caption;
        }

        [$source, $rest] = $extracted;

        return $this->fillTemplate($replace, $source).$rest;
    }

    /**
     * Schneidet die Quellenangabe aus einer Bildunterschrift heraus.
     *
     * Es wird nur das erste Vorkommen berücksichtigt; mehrere Quellenangaben in
     * einer einzelnen Bildunterschrift sind nicht vorgesehen.
     *
     * @param string $caption  Der reine Inhalt eines <figcaption>-Elements
     * @param string $startTag Trennzeichen, an dem die Quellenangabe beginnt
     * @param string $endTag   Trennzeichen, an dem die Quellenangabe endet
     *
     * @return array{0: string, 1: string}|null Der Text zwischen den Trennzeichen
     *                                          und die um ihn bereinigte
     *                                          Bildunterschrift, oder null, wenn
     *                                          keine Quellenangabe enthalten ist
     */
    private function extractSource(string $caption, string $startTag, string $endTag): ?array
    {
        // preg_quote ist zwingend: die Trennzeichen sind frei konfigurierbar und
        // dürfen auch mehrstellig oder Sonderzeichen der Regex-Syntax sein.
        $pattern = '#'.preg_quote($startTag, '#').'(.*?)'.preg_quote($endTag, '#').'#s';

        if (1 !== preg_match($pattern, $caption, $matches, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $offset = $matches[0][1];
        $length = \strlen($matches[0][0]);

        return [
            $matches[1][0],
            substr($caption, 0, $offset).substr($caption, $offset + $length),
        ];
    }

    /**
     * Setzt den Text der Quellenangabe in die konfigurierte Vorlage ein.
     *
     * Verwendet str_replace statt sprintf: enthält die Vorlage ein
     * Prozentzeichen, das nicht zu einer gültigen Formatangabe gehört (etwa in
     * "width:50%"), wirft sprintf unter PHP 8 einen ValueError.
     *
     * @param string $replace Vorlage mit dem Platzhalter "%s"
     * @param string $source  Der Text zwischen den Trennzeichen
     *
     * @return string Die fertige Ersetzung
     */
    private function fillTemplate(string $replace, string $source): string
    {
        return str_replace('%s', $source, $replace);
    }
}
