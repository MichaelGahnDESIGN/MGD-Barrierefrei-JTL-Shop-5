# Sicherheitsrichtlinie

Melde Sicherheitslücken vertraulich über das Sicherheitsformular des Repositories. Keine Shopzugänge, Kundendaten oder Tokens in öffentlichen Issues teilen.

Im Shop für Besucher lädt das Plugin keine externen Dienste. Nur der Admin-Menüpunkt "Weitere Plugins" liest die öffentliche Liste von theme.michael-gahn.de (nur `https`, nur dieser Host, keine Weiterleitungen, kurzes Zeitlimit und Größenlimit). Links, Bilder, Versionen und Farben werden vor der Anzeige geprüft, Texte werden escaped; Aktualisieren geht nur per POST mit JTL-Token. Das Plugin speichert keine Besucherdaten auf dem Server. Die Bedienhilfe verwendet ausschließlich lokalen Browser-Speicher. Vor jeder ZIP-Aktualisierung sind Datenbank und bisheriger Plugin-Ordner zu sichern.
