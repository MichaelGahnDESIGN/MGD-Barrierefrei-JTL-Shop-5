# Changelog

## 1.1.1 – 2026-11-10

- **Neu:** Admin-Menüpunkt **Updates**. Dort kannst du prüfen, ob es eine neue Version gibt. Das Plugin lädt ein neues Paket nur auf deinen Klick herunter und prüft es (Signatur, Prüfsumme, Inhalt). Es installiert nie selbst. Die Installation machst du in der JTL-Pluginverwaltung.
- Die Update-Prüfung ist standardmäßig aus. Es gibt keinen Hintergrund-Abruf. Vor dem Einschalten steht ein Datenschutz-Hinweis.
- Der Update-Kanal arbeitet im einfachen Modus: Eine kleine Textdatei auf theme.michael-gahn.de (`plugin-updates/mgd-barrierefrei.json`) nennt Version und SHA-256-Prüfsumme, das ZIP kommt von GitHub, die Prüfsumme wird beim Download nachgerechnet. Das ist eine Prüfsumme, keine Signatur.
- Neu im Plugin-Ordner: `UpdateClient/` (gemeinsamer MGD Update Client) und `update/root-keys.json` (öffentliche Schlüssel, derzeit leer).

## 1.1.0 – 2026-10-27

- **Neu:** Admin-Menüpunkt **Weitere Plugins**. Er zeigt weitere kostenlose Plugins für JTL-Shop 5 aus dem Katalog von theme.michael-gahn.de.
- Die Liste wird sechs Stunden zwischengespeichert. Ist der Katalog nicht erreichbar, läuft der Shop normal weiter. Es wird nichts automatisch installiert.
- Bilder werden nur im Admin geladen und nur, wenn der Katalog welche liefert (derzeit nicht). Im Shop für Besucher wird nichts von theme.michael-gahn.de geladen.
- Nach dem Update bitte den Systemcache im Backend leeren.

## 1.0.0 – 2026-10-23

- Erste kostenlose Version für JTL-Shop 5.7.2 oder neuer.
- Lokale Bedienhilfe und ein kopierbarer Entwurf der Erklärung zur Barrierefreiheit.
- Fertiges Release-ZIP mit SHA-256-Datei.
