# MGD Barrierefrei für JTL-Shop 5

Kostenlose Bedienhilfe mit lokal speicherbaren Darstellungseinstellungen und einem konfigurierbaren Entwurf für eine Erklärung zur Barrierefreiheit.

## Funktionen

- neue Bedienhilfe: rundes Symbol, Text nur bei Mauskontakt, DE/EN und Hell/Dunkel für Besucher, Standard im Backend wählbar
- 10 Funktionen mit Symbol und (?)-Erklärung: Schriftgröße, hoher Kontrast, Graustufen, mehr Abstand, lesbare Schrift, Links hervorheben, Fokus sichtbar, Bewegung stoppen, großer Mauszeiger, Lese-Lineal
- Einstellungen liegen ausschließlich als `localStorage` im Browser des Besuchers
- frei konfigurierbare Position, Beschriftung, Akzentfarbe und Zielseite
- Admin-Menüpunkt **Erklärung erzeugen** erstellt aus den Einstellungen einen kopierbaren Textentwurf
- Admin-Tab **Übersicht** (ab 1.6.0) mit Info, Updates (nur auf Klick, installiert nie selbst), weiteren Plugins und dem Schalter „Installation melden“
- Admin-Tab **Weitere Plugins** mit weiteren Plugins von Michael Gahn DESIGN (nur im Admin, nichts wird automatisch installiert)
- sichtbarer, neuer Tab-Link auf [Michael-Gahn.de](https://michael-gahn.de) mit `rel="noopener noreferrer"`

## Installation

1. Lade `MGD_Barrierefrei-<Version>.zip` aus dem GitHub-Release.
2. Öffne im JTL-Backend die Pluginverwaltung und lade das ZIP hoch.
3. Installiere und aktiviere das Plugin.
4. Pflege die Angaben in **Plugins → MGD Barrierefrei → Einstellungen**.
5. Öffne **Erklärung erzeugen**, prüfe den Text und veröffentliche ihn als eigene Inhaltsseite.
6. Hinterlege deren relative Adresse, etwa `/barrierefreiheit`, in der Plugin-Einstellung **Link zur Erklärung**.

Das Release-ZIP enthält die fertigen CSS- und JavaScript-Dateien; ein manueller Build im JTL-Backend ist nicht erforderlich. JTL-Shop installiert Aktualisierungen weiterhin bewusst aus einem verifizierten ZIP über die Pluginverwaltung. Vor jedem Update Datenbank und Plugin-Dateien sichern.

## Weitere Plugins (ab 1.6.0)

Im JTL-Backend unter **Plugins → MGD Barrierefrei → Weitere Plugins** (und in der Übersicht) siehst du weitere Plugins von Michael Gahn DESIGN.

- Die Liste kommt von theme.michael-gahn.de und wird 15 Minuten zwischengespeichert. Antwortet der Server nicht, siehst du die letzte gute Liste.
- Du kannst suchen, nach Preis filtern und nach Kategorie wählen. **Details** öffnet ein kleines Fenster, **Ansehen** die Plugin-Seite.
- Die Bilder holt dein Shop-Server selbst und legt sie in `mediafiles/mgd-kit-catalog/` ab. Dein Browser lädt keine Bilder von fremden Seiten.
- Es wird nichts automatisch installiert. Installiert wird von Hand in der JTL-Pluginverwaltung.
- Im Shop für Besucher gibt es keine Anfrage an theme.michael-gahn.de.

Mehr dazu im [Wiki: Weitere Plugins](docs/wiki/Weitere-Plugins.md).

## Updates

Ab 1.6.0 steht der Update-Bereich im Tab **Übersicht**. Dort prüfst du, ob es eine neue Version gibt.

So geht es Schritt für Schritt:

1. Öffne **Plugins → MGD Barrierefrei → Übersicht**.
2. Klicke auf **Jetzt nach Updates suchen**. Erst dann verbindet sich dein Shop-Server mit theme.michael-gahn.de.
3. Optional: Setze den Haken **Update-Prüfung einschalten** und speichere. Dann prüft das Plugin höchstens alle 12 Stunden, wenn du die Seite öffnest. Standard ist aus.
4. Gibt es eine neue Version, setze den Haken zur Bestätigung und klicke **Paket herunterladen und prüfen**.
5. Das Plugin prüft Prüfsumme und Inhalt des ZIPs. Es **packt nichts aus** und **ändert keine Plugin-Dateien**.
6. Klicke **Geprüftes Paket als ZIP speichern**, mache eine Datensicherung und lade das ZIP in der **JTL-Pluginverwaltung** hoch. JTL macht das Update.

Wichtig zu wissen:

- Es gibt keine Ein-Klick-Installation und keine automatische Aktivierung.
- Der Server sieht die IP-Adresse deines Shop-Servers. Es werden keine Besucher-, Kunden- oder Bestelldaten gesendet.
- Der Zustand und das geprüfte ZIP liegen im Ordner `templates_c/mgd_barrierefrei_update` (mit Zugriffssperre). Leerst du `templates_c`, geht dieser Stand verloren. Das ist harmlos.
- ⚠️ Der Update-Kanal arbeitet im einfachen Modus: Eine kleine Textdatei auf theme.michael-gahn.de (`plugin-updates/mgd-barrierefrei.json`) nennt Version und SHA-256-Prüfsumme, das ZIP kommt von GitHub, die Prüfsumme wird beim Download nachgerechnet. Das ist eine Prüfsumme, keine Signatur.
- Technik (ab 1.6.0): gemeinsamer Plugin-Baukasten aus dem Pro-Repo (`php bin/build-plugin-kit.php plugin/MGD_Barrierefrei --platform=jtl5 --namespace='Plugin\MGD_Barrierefrei\Kit' --product=mgd-barrierefrei --plugin-id=MGD_Barrierefrei --name='MGD Barrierefrei' --catalog-slug=plugin-barrierefrei-jtl --repo=MGD-Barrierefrei-JTL-Shop-5`), hier unter `plugin/MGD_Barrierefrei/Kit`. Diesen Ordner nicht von Hand ändern, er wird neu erzeugt. Er enthält auch den Update-Client.

## Anmeldung der Installation (ab 1.5.0)

Meldet sich höchstens einmal am Tag bei theme.michael-gahn.de (Plugin, Version, Shopsystem, Domain, zufällige ID). Abschaltbar in den Einstellungen.

1. Die Meldung ist ab Werk eingeschaltet. Sie läuft auch, wenn die Update-Prüfung aus ist.
2. Gemeldet wird beim Installieren, Aktivieren und Updaten und danach höchstens einmal am Tag beim Öffnen des Backends. Nie im Shop-Frontend.
3. Abschalten: **Plugins → MGD Barrierefrei → Übersicht** öffnen (bis 1.5.0: Tab „Updates“), den Haken **Installation bei Michael Gahn DESIGN melden** entfernen und speichern. Dann wird der Eintrag auf der Plattform gelöscht.
4. Deinstallierst du das Plugin (ab 1.6.0), wird der Eintrag auf der Plattform ebenfalls gelöscht.
5. Das Plugin funktioniert immer voll, auch ohne Meldung oder wenn die Plattform nicht erreichbar ist.
- Technik: ab 1.6.0 kommt der Install-Client aus dem Baukasten (`plugin/MGD_Barrierefrei/Kit/Install`). Die bisherige Installations-ID wird beim ersten Start übernommen.

## Rechtlicher Hinweis

Der erzeugte Text ist ein anpassbarer Entwurf, keine Rechtsberatung und keine Konformitätszusage. Prüfe Geltungsbereich, Kontaktangaben, tatsächlichen Stand und bekannte Barrieren vor jeder Veröffentlichung.

## Lokaler Release-Build

```sh
find plugin -name '*.php' -print0 | xargs -0 -n1 php -l
php tests/catalog-check.php
php tests/update-smoke.php
node --check plugin/MGD_Barrierefrei/frontend/js/mgd-barrierefrei.js
php tests/frontend-config.php
sh scripts/build-release.sh
```

## Wiki

- [Start](docs/wiki/Home.md)
- [Einrichten](docs/wiki/Einrichten.md)
- [Bedienhilfe](docs/wiki/Bedienhilfe.md)
- [Erklärung prüfen](docs/wiki/Erklaerung-pruefen.md)
- [Weitere Plugins](docs/wiki/Weitere-Plugins.md)
- [Datenschutz und Sicherheit](docs/wiki/Datenschutz-und-Sicherheit.md)
