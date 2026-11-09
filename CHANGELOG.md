# Changelog

## Unveröffentlicht

- **Neu:** Admin-Menüpunkt **Updates**. Dort kannst du prüfen, ob es eine neue Version gibt. Das Plugin lädt ein neues Paket nur auf deinen Klick herunter und prüft es (Signatur, Prüfsumme, Inhalt). Es installiert nie selbst. Die Installation machst du in der JTL-Pluginverwaltung.
- Die Update-Prüfung ist standardmäßig aus. Es gibt keinen Hintergrund-Abruf. Vor dem Einschalten steht ein Datenschutz-Hinweis.
- Noch ohne Wirkung: Der Update-Kanal ist noch nicht eingerichtet (Schlüssel fehlen, Blocker B1) und der Update-Server kennt das Produkt `mgd-barrierefrei` noch nicht (Blocker B2). Bis dahin meldet der Bereich: „Update-Kanal noch nicht eingerichtet“.
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
