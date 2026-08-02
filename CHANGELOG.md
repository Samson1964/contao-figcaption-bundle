# Bildunterschriften ersetzen Changelog

## Version 3.0.0 (2026-08-02)

* Add: Unterstützung für Contao 5 (getestet mit 4.13.58 und 5.7.7)
* Add: Neue Einstellung „Position der Quellenangabe“ – die Ersetzung kann jetzt wahlweise in der Bildunterschrift bleiben (Voreinstellung, wie bisher) oder aus ihr herausgelöst und direkt hinter das Bild in die `figure` gesetzt werden
* Add: Englische Sprachdatei, damit die Einstellungen auch auf nicht-deutschen Installationen beschriftet sind
* Add: Unit-Tests für die Ersetzung (tests/) samt phpunit.xml.dist
* Add: README ausführlich erweitert um Installation, Einstellungen, Verwendung, eigene Vorlagen und Fehlersuche
* Change: Mindestanforderung ist jetzt PHP 8.1 und Contao 4.13; die Vererbung von `\Frontend` ist entfallen
* Change: Die Hook-Klasse heißt jetzt `EventListener\FigcaptionListener` (vorher `Classes\Figcaption`) und wird über das Attribut `#[AsHook]` statt über die `config.php` registriert
* Change: `services.yml` in `services.yaml` umbenannt; der Block `_instanceof` ist entfallen, der unter Contao 5 den Container-Aufbau blockiert hätte
* Fix: Die Sprachdatei schaltete sich unter Contao 5 selbst ab (Prüfung auf die dort entfallene Konstante `TL_ROOT`), sämtliche Beschriftungen wären leer geblieben
* Fix: Attribute am `figcaption`-Tag (z. B. `class`) verschmolzen mit dem Tag-Namen, aus `<figcaption class="…">` wurde `<figcaptionclass="…">`
* Fix: Mehrstellige Trennzeichen erzeugten ein ungültiges Suchmuster, weil nur das erste Zeichen maskiert wurde
* Fix: Ein Prozentzeichen in der Ersetzungsvorlage (etwa in einem `style`-Attribut) brach die Ausgabe unter PHP 8 mit einem `ValueError` ab
* Fix: Leere Trennzeichen oder eine leere Ersetzung führten zu unsinniger Ausgabe; die Ersetzung unterbleibt jetzt
* Fix: Die Quelldateien lagen teilweise in kaputter Zeichenkodierung vor und sind jetzt durchgängig UTF-8 ohne BOM

## Version 2.0.3 (2026-07-30)

* Change: Beschreibung, Keywords und Homepage in der composer.json ergänzt, damit Packagist das Paket verständlich darstellt und über die Suche auffindbar macht

## Version 2.0.2 (2024-05-21)

* Fix: README Bildverlinkung

## Version 2.0.1 (2024-05-21)

* Fix: tl_settings Codestruktur
* Add: Anleitung der Einbindung in README

## Version 2.0.0 (2024-05-21)

* Add: PHP8-Unterstützung

## Version 1.1.0 (2021-09-29)

* Add: Ersetzung der Bildunterschriften abschaltbar gemacht in den System-Einstellungen

## Version 1.0.0 (2021-06-10)

* Alphaversion

## Version 0.0.1 (2021-06-09)

* Initialversion als Contao-4-Bundle
