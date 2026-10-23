# MGD Barrierefrei für JTL-Shop 5

Kostenlose Bedienhilfe mit lokal speicherbaren Darstellungseinstellungen und einem konfigurierbaren Entwurf für eine Erklärung zur Barrierefreiheit.

## Funktionen

- Kontrast, Graustufen, sichtbarer Fokus, Schriftgröße und reduzierte Bewegung
- Einstellungen liegen ausschließlich als `localStorage` im Browser des Besuchers
- frei konfigurierbare Position, Beschriftung, Akzentfarbe und Zielseite
- Admin-Menüpunkt **Erklärung erzeugen** erstellt aus den Einstellungen einen kopierbaren Textentwurf
- sichtbarer, neuer Tab-Link auf [Michael-Gahn.de](https://michael-gahn.de) mit `rel="noopener noreferrer"`

## Installation

1. Lade `MGD_Barrierefrei-<Version>.zip` aus dem GitHub-Release.
2. Öffne im JTL-Backend die Pluginverwaltung und lade das ZIP hoch.
3. Installiere und aktiviere das Plugin.
4. Pflege die Angaben in **Plugins → MGD Barrierefrei → Einstellungen**.
5. Öffne **Erklärung erzeugen**, prüfe den Text und veröffentliche ihn als eigene Inhaltsseite.
6. Hinterlege deren relative Adresse, etwa `/barrierefreiheit`, in der Plugin-Einstellung **Link zur Erklärung**.

Das Release-ZIP enthält die fertigen CSS- und JavaScript-Dateien; ein manueller Build im JTL-Backend ist nicht erforderlich. JTL-Shop installiert Aktualisierungen weiterhin bewusst aus einem verifizierten ZIP über die Pluginverwaltung. Vor jedem Update Datenbank und Plugin-Dateien sichern.

## Rechtlicher Hinweis

Der erzeugte Text ist ein anpassbarer Entwurf, keine Rechtsberatung und keine Konformitätszusage. Prüfe Geltungsbereich, Kontaktangaben, tatsächlichen Stand und bekannte Barrieren vor jeder Veröffentlichung.

## Lokaler Release-Build

```sh
find plugin -name '*.php' -print0 | xargs -0 -n1 php -l
sh scripts/build-release.sh
```

## Wiki

- [Start](docs/wiki/Home.md)
- [Einrichten](docs/wiki/Einrichten.md)
- [Erklärung prüfen](docs/wiki/Erklaerung-pruefen.md)
- [Datenschutz und Sicherheit](docs/wiki/Datenschutz-und-Sicherheit.md)
