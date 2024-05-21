# Bildunterschriften ersetzen für Contao

Mit dieser Erweiterung können Sie einen von Platzhaltern umklammerten Text in Bildunterschriften ersetzen. Damit kann z.B. der Fotograf des Bildes separatiert werden.

## Anleitung ##

Die Einstellungen für die Ersetzung in Bildunterschriften (figcaption-Tag) nehmen Sie unter System -> Einstellungen vor:
 
![](docs/bildunterschriften.png)

Die Beispielkonfiguration in der Grafik würde die Bildunterschrift 

```
Links steht Hans Mustermann,[Holger Mustermann] rechts Berta Mustermann.
```

ersetzen mit

```
Links steht Hans Mustermann, rechts Berta Mustermann.<div class="rechte">Holger Mustermann</div>
```

Die Ersetzung wird dabei immer an das Ende des Textes verschoben, egal an welcher Stelle der von Platzhaltern umklammerte Text eingegeben wurde.

## Entwickler ##

**Frank Hoppe**

