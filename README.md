# MGD Barrierefrei für JTL-Shop 5

Kostenlose Bedienhilfe mit lokal speicherbaren Darstellungseinstellungen und einem konfigurierbaren Entwurf für eine Erklärung zur Barrierefreiheit.

## Funktionen

- Kontrast, Graustufen, sichtbarer Fokus, Schriftgröße und reduzierte Bewegung
- Einstellungen liegen ausschließlich als `localStorage` im Browser des Besuchers
- frei konfigurierbare Position, Beschriftung, Akzentfarbe und Zielseite
- Admin-Menüpunkt **Erklärung erzeugen** erstellt aus den Einstellungen einen kopierbaren Textentwurf
- Admin-Menüpunkt **Weitere Plugins** zeigt weitere kostenlose Plugins für JTL-Shop 5 (nur im Admin, nichts wird automatisch installiert)
- Admin-Menüpunkt **Updates** prüft auf neue Versionen (nur auf Klick, installiert nie selbst)
- sichtbarer, neuer Tab-Link auf [Michael-Gahn.de](https://michael-gahn.de) mit `rel="noopener noreferrer"`

## Installation

1. Lade `MGD_Barrierefrei-<Version>.zip` aus dem GitHub-Release.
2. Öffne im JTL-Backend die Pluginverwaltung und lade das ZIP hoch.
3. Installiere und aktiviere das Plugin.
4. Pflege die Angaben in **Plugins → MGD Barrierefrei → Einstellungen**.
5. Öffne **Erklärung erzeugen**, prüfe den Text und veröffentliche ihn als eigene Inhaltsseite.
6. Hinterlege deren relative Adresse, etwa `/barrierefreiheit`, in der Plugin-Einstellung **Link zur Erklärung**.

Das Release-ZIP enthält die fertigen CSS- und JavaScript-Dateien; ein manueller Build im JTL-Backend ist nicht erforderlich. JTL-Shop installiert Aktualisierungen weiterhin bewusst aus einem verifizierten ZIP über die Pluginverwaltung. Vor jedem Update Datenbank und Plugin-Dateien sichern.

## Weitere Plugins (Admin-Menüpunkt)

Im JTL-Backend unter **Plugins → MGD Barrierefrei → Weitere Plugins** siehst du weitere kostenlose Plugins von Michael Gahn DESIGN.

- Die Liste kommt von `https://theme.michael-gahn.de/api/catalog/plugins.json` und wird 6 Stunden zwischengespeichert (Datei im Ordner `templates_c`).
- Der Button **Liste aktualisieren** lädt sie neu (höchstens einmal pro Minute).
- Es wird nichts automatisch installiert. Du lädst das ZIP herunter und lädst es selbst in der JTL-Pluginverwaltung hoch.
- Ist der Katalog nicht erreichbar, steht dort nur ein Hinweis. Dein Shop ist nie betroffen.
- Der Katalog liefert derzeit keine Bilder. Falls er später welche liefert, lädt dein Browser sie nur auf dieser Admin-Seite (nur von theme.michael-gahn.de). Im Shop für Besucher gibt es keine Anfrage an theme.michael-gahn.de.

Mehr dazu im [Wiki: Weitere Plugins](docs/wiki/Weitere-Plugins.md).

## Updates (Admin-Menüpunkt)

Im JTL-Backend unter **Plugins → MGD Barrierefrei → Updates** prüfst du, ob es eine neue Version gibt.

So geht es Schritt für Schritt:

1. Öffne **Plugins → MGD Barrierefrei → Updates**.
2. Klicke auf **Jetzt nach Updates suchen**. Erst dann verbindet sich dein Shop-Server mit theme.michael-gahn.de.
3. Optional: Setze den Haken **Update-Prüfung einschalten** und speichere. Dann prüft das Plugin höchstens alle 12 Stunden, wenn du die Seite öffnest. Standard ist aus.
4. Gibt es eine neue Version, setze den Haken zur Bestätigung und klicke **Paket herunterladen und prüfen**.
5. Das Plugin prüft Signatur, Prüfsumme und Inhalt des ZIPs. Es **packt nichts aus** und **ändert keine Plugin-Dateien**.
6. Klicke **Geprüftes Paket als ZIP speichern**, mache eine Datensicherung und lade das ZIP in der **JTL-Pluginverwaltung** hoch. JTL macht das Update.

Wichtig zu wissen:

- Es gibt keine Ein-Klick-Installation und keine automatische Aktivierung.
- Der Server sieht die IP-Adresse deines Shop-Servers. Es werden keine Besucher-, Kunden- oder Bestelldaten gesendet.
- Der Zustand und das geprüfte ZIP liegen im Ordner `templates_c/mgd_barrierefrei_update` (mit Zugriffssperre). Leerst du `templates_c`, geht dieser Stand verloren. Das ist harmlos.
- ⚠️ Der Update-Kanal arbeitet im einfachen Modus: Eine kleine Textdatei auf theme.michael-gahn.de (`plugin-updates/mgd-barrierefrei.json`) nennt Version und SHA-256-Prüfsumme, das ZIP kommt von GitHub, die Prüfsumme wird beim Download nachgerechnet. Das ist eine Prüfsumme, keine Signatur. Solange die Textdatei noch nicht auf dem Server liegt, meldet der Bereich nur einen Fehler beim Abruf. Sonst passiert nichts.
- Technik: gemeinsamer MGD Update Client, Quelle im Pro-Repo `plugins/update-client/jtl5`, hier unter `plugin/MGD_Barrierefrei/UpdateClient`. Diesen Ordner nicht von Hand ändern, er wird neu erzeugt.

## Rechtlicher Hinweis

Der erzeugte Text ist ein anpassbarer Entwurf, keine Rechtsberatung und keine Konformitätszusage. Prüfe Geltungsbereich, Kontaktangaben, tatsächlichen Stand und bekannte Barrieren vor jeder Veröffentlichung.

## Lokaler Release-Build

```sh
find plugin -name '*.php' -print0 | xargs -0 -n1 php -l
php tests/catalog-check.php
php tests/update-smoke.php
node --check plugin/MGD_Barrierefrei/frontend/js/mgd-barrierefrei.js
sh scripts/build-release.sh
```

## Wiki

- [Start](docs/wiki/Home.md)
- [Einrichten](docs/wiki/Einrichten.md)
- [Erklärung prüfen](docs/wiki/Erklaerung-pruefen.md)
- [Weitere Plugins](docs/wiki/Weitere-Plugins.md)
- [Datenschutz und Sicherheit](docs/wiki/Datenschutz-und-Sicherheit.md)
