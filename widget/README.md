# MGD Barrierefrei Widget (gemeinsam für alle Shopsysteme)

Diese Datei ist die **eine Quelle** für die Bedienhilfe in allen Fassungen:
JTL-Shop 5, WordPress, Shopware 6 und Shopify.

## Wo die Datei landet

| Fassung | Ziel |
|---|---|
| JTL-Shop 5 | `plugin/MGD_Barrierefrei/frontend/js/mgd-barrierefrei.js` |
| WordPress | `mgd-barrierefrei-wordpress/assets/js/frontend.js` |
| Shopware 6 | `src/Resources/app/storefront/src/js/mgd-barrierefrei.js`, danach `sh scripts/build-storefront.sh` |
| Shopify | `extensions/mgd-barrierefrei/assets/mgd-barrierefrei.js` |

## So änderst du das Widget

1. Nur `widget/mgd-barrierefrei-widget.js` bearbeiten.
2. `widget/demo.html` im Browser öffnen und testen (z. B. `python3 -m http.server 8765 --directory widget`).
3. Datei in alle vier Fassungen kopieren (Tabelle oben).
4. In jeder Fassung die Tests laufen lassen.

## Einstellungen (Config)

Jede Fassung gibt vor dem Skript ein JSON aus:

```html
<script type="application/json" id="mgd-barrierefrei-config">
{"position":"left","accent":"#b91c1c","lang":"auto","theme":"auto","labels":{"de":"","en":""},"statementUrl":"/barrierefreiheit","offset":0}
</script>
```

- `lang`: `auto`, `de` oder `en`
- `theme`: `auto`, `light` oder `dark`
- `labels`: leer lassen = Standardtext
- `offset`: 0 bis 200 Pixel nach oben
