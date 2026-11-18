# Datenschutz und Sicherheit

Es werden keine Analyse-, Werbe- oder Fontdienste geladen. Die gewählten Darstellungshilfen liegen lokal im Browser. Das Plugin schreibt selbst keine Besucherdaten in die Datenbank und benötigt keine Zugangsdaten für GitHub oder Michael-Gahn.de.

Eine Ausnahme gibt es nur im Admin: Der Menüpunkt **Weitere Plugins** ruft vom Shop-Server aus die öffentliche Liste auf theme.michael-gahn.de ab. Dabei sieht der Server die IP-Adresse des Shop-Servers, aber keine Shop-, Kunden- oder Zugangsdaten. Der Katalog liefert derzeit keine Bilder. Falls er später welche liefert, lädt der Browser sie nur auf dieser Admin-Seite; dann sieht der Server die IP-Adresse der angemeldeten Person. Für Besucher des Shops entsteht keine Verbindung zu theme.michael-gahn.de.

Auch der Menüpunkt **Updates** verbindet sich nur im Admin mit theme.michael-gahn.de, und nur auf deinen Klick oder wenn du die Update-Prüfung selbst eingeschaltet hast (Standard: aus). Dabei sieht der Server die IP-Adresse des Shop-Servers. Es werden keine Besucher-, Kunden-, Bestell- oder Zugangsdaten gesendet. Das Plugin installiert nie selbst. Neue Pakete werden vor dem Ablegen auf Prüfsumme (SHA-256) und Inhalt geprüft. Eine Signatur gibt es im einfachen Modus noch nicht. Siehe README, Abschnitt Updates.

## Anmeldung der Installation (ab 1.5.0)

Das Plugin meldet sich einmal bei theme.michael-gahn.de an und danach höchstens einmal am Tag.

- **Was wird gesendet?** Nur Plugin-Name, Version, Shopsystem, die Domain deines Shops und eine zufällige Installations-ID. Keine Besucher-, Kunden-, Bestell- oder Zugangsdaten.
- **Wann?** Beim Installieren, Aktivieren und Updaten. Danach höchstens einmal am Tag, wenn jemand das Backend öffnet. Nie im Shop-Frontend.
- **Wozu?** So wissen wir, wie viele Shops das Plugin nutzen, und können Updates bereitstellen.
- **Abschalten:** Plugins › MGD Barrierefrei › **Updates** › Haken bei „Installation bei Michael Gahn DESIGN melden“ entfernen › Speichern. Dann wird der Eintrag auf der Plattform gelöscht und nichts mehr gesendet.
- Das Plugin funktioniert immer voll, auch ohne Meldung und wenn die Plattform nicht erreichbar ist.
- Der Server sieht dabei die IP-Adresse deines Shop-Servers. Trage diese Verbindung bei Bedarf in deine Datenschutzdokumentation ein.
