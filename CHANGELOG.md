# Changelog

## 1.4.0 – 2026-11-14

- Gemeinsame Versionsnummer **1.4.0** für alle Produkte von Michael Gahn DESIGN. So sieht man auf einen Blick, welche Fassungen zusammengehören.
- Keine Änderung an den Funktionen.
- Nach dem Update bitte den Systemcache im Backend leeren.

## 1.2.1 – 2026-11-12

- **Fix:** Nach dem Download im Bereich **Updates** stand „geprüft (Signatur, SHA-256, Inhalt)“. Im einfachen Modus gibt es aber keine Signatur. Jetzt steht dort richtig „geprüft (SHA-256-Prüfsumme, Inhalt)“.
- Update-Client neu erzeugt. Sonst keine Änderungen.

## 1.2.0 – 2026-11-11

- **Neu: Bedienhilfe komplett überarbeitet.** Neues Symbol (Person im Kreis) statt Rollstuhl. Der Text daneben erscheint nur, wenn die Maus darüber fährt oder die Tastatur den Button erreicht.
- **Neu:** Besucher wählen selbst **DE oder EN** und **Hell oder Dunkel**. Was beim ersten Besuch gilt, stellst du im Backend ein (Standard-Sprache, Standard-Design). Dazu: englische Beschriftung und „Abstand nach oben“, falls der Button einen Chat-Button verdeckt.
- **Neu:** Jede Funktion hat ein Symbol und ein **(?)**, das kurz erklärt, was sie tut.
- **Neu:** 10 Funktionen, die wirklich wirken: Schriftgröße (90–150 %, auch bei festen Pixel-Schriften), hoher Kontrast, Graustufen, mehr Abstand, lesbare Schrift, Links hervorheben, Fokus sichtbar, Bewegung stoppen (auch Videos), großer Mauszeiger, Lese-Lineal.
- Die Bedienhilfe liegt in einem abgeschirmten Bereich (Shadow DOM). Theme- oder Builder-CSS kann sie nicht mehr verändern.
- Gespeichert wird weiter nur im Browser des Besuchers (localStorage). Frühere Einstellungen der Besucher werden einmalig zurückgesetzt.
- Update-Client neu erzeugt: kein veralteter `curl_close`-Aufruf mehr (PHP 8.5).
- Nach dem Update bitte den Systemcache im Backend leeren.

## 1.1.2 – 2026-11-11

- Doku korrigiert: Der Update-Bereich prüft im einfachen Modus die **Prüfsumme** (SHA-256) und den Inhalt des ZIPs. Eine Signatur gibt es noch nicht. README und Wiki sagen das jetzt richtig.
- Sonst keine Änderungen am Plugin. Nach dem Update bitte den Systemcache im Backend leeren.

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
