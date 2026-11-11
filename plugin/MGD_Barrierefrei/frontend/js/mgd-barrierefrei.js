/*! MGD Barrierefrei Widget 2.0.0 | Michael Gahn DESIGN | GPL-2.0-or-later
 * Gleiche Datei fuer JTL, WordPress, Shopware und Shopify. Quelle: MGD-Barrierefrei-JTL-Shop-5/widget/
 * Config kommt aus <script type="application/json" id="mgd-barrierefrei-config">
 */
(() => {
  'use strict';
  if (window.__mgdBarrierefrei) return;
  window.__mgdBarrierefrei = true;

  const KEY = 'mgd-barrierefrei-v2';
  const HOST_TAG = 'mgd-barrierefrei';
  const SIZE_MIN = -1;
  const SIZE_MAX = 5;
  const SIZE_STEP = 0.1;
  const TOGGLES = ['contrast', 'gray', 'spacing', 'font', 'links', 'focus', 'motion', 'cursor', 'guide'];

  // Texte DE / EN
  const T = {
    de: {
      label: 'Barrierefreiheit',
      title: 'Bedienhilfe',
      intro: 'Passe die Darstellung an. Die Auswahl bleibt nur auf diesem Gerät gespeichert.',
      close: 'Bedienhilfe schließen',
      lang: 'Sprache',
      dark: 'Dunkles Design',
      help: 'Erklärung zu',
      reset: 'Alles zurücksetzen',
      statement: 'Erklärung zur Barrierefreiheit',
      credit: 'MGD Barrierefrei von Michael Gahn DESIGN',
      newTab: '(öffnet in neuem Tab)',
      on: 'an',
      off: 'aus',
      smaller: 'Schrift kleiner',
      bigger: 'Schrift größer',
      f: {
        size: ['Schriftgröße', 'Vergrößert oder verkleinert alle Texte der Seite in Schritten von 10 Prozent. Das Layout bleibt erhalten.'],
        contrast: ['Hoher Kontrast', 'Zeigt Texte hell auf schwarzem Grund und Links in Gelb. Hilft bei Sehschwäche und blendendem Licht.'],
        gray: ['Graustufen', 'Entfernt alle Farben. Hilft bei Farbsehschwäche und reduziert Ablenkung.'],
        spacing: ['Mehr Abstand', 'Vergrößert Zeilen-, Wort- und Buchstabenabstand. Lange Texte lassen sich so leichter lesen.'],
        font: ['Lesbare Schrift', 'Ersetzt verspielte Schriften durch eine klare, gut lesbare Schrift. Hilft auch bei Lese-Rechtschreib-Schwäche.'],
        links: ['Links hervorheben', 'Unterstreicht und umrahmt alle Links. So erkennst du sofort, was anklickbar ist.'],
        focus: ['Fokus sichtbar', 'Zeigt einen kräftigen Rahmen um das Element, das du gerade mit der Tastatur (Tab-Taste) erreichst.'],
        motion: ['Bewegung stoppen', 'Hält Animationen, Übergänge und automatisch startende Videos an. Hilft bei Schwindel und Konzentrationsproblemen.'],
        cursor: ['Großer Mauszeiger', 'Zeigt einen großen, kontrastreichen Mauszeiger. Er ist leichter zu finden.'],
        guide: ['Lese-Lineal', 'Ein Balken folgt deiner Maus und hilft dir, beim Lesen in der Zeile zu bleiben.'],
      },
    },
    en: {
      label: 'Accessibility',
      title: 'Accessibility tools',
      intro: 'Adjust the display. Your choice is only saved on this device.',
      close: 'Close accessibility tools',
      lang: 'Language',
      dark: 'Dark theme',
      help: 'Explanation for',
      reset: 'Reset everything',
      statement: 'Accessibility statement',
      credit: 'MGD Barrierefrei by Michael Gahn DESIGN',
      newTab: '(opens in a new tab)',
      on: 'on',
      off: 'off',
      smaller: 'Smaller text',
      bigger: 'Larger text',
      f: {
        size: ['Text size', 'Makes all text on the page larger or smaller in steps of 10 percent. The layout stays intact.'],
        contrast: ['High contrast', 'Shows light text on a black background and links in yellow. Helps with low vision and glare.'],
        gray: ['Grayscale', 'Removes all colours. Helps with colour blindness and reduces distraction.'],
        spacing: ['More spacing', 'Increases line, word and letter spacing. Long texts become easier to read.'],
        font: ['Readable font', 'Replaces decorative fonts with a clear, easy-to-read font. Also helps with dyslexia.'],
        links: ['Highlight links', 'Underlines and outlines every link, so you can see right away what is clickable.'],
        focus: ['Visible focus', 'Shows a strong frame around the element you reach with the keyboard (Tab key).'],
        motion: ['Stop motion', 'Stops animations, transitions and autoplaying videos. Helps with dizziness and focus problems.'],
        cursor: ['Large cursor', 'Shows a large, high-contrast mouse pointer that is easier to find.'],
        guide: ['Reading guide', 'A bar follows your mouse and helps you stay on the line while reading.'],
      },
    },
  };

  // Icons, alle 24er Raster
  const P = 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
  const I = {
    access: '<svg viewBox="0 0 24 24" ' + P + ' stroke-width="2"><circle cx="12" cy="12" r="10.2" stroke-width="1.6"/><circle cx="12" cy="6.9" r="1.55" fill="currentColor" stroke="none"/><path d="M6.6 9.6 12 10.8l5.4-1.2"/><path d="M12 10.8v3.6"/><path d="m9.2 18.4 2.8-4 2.8 4"/></svg>',
    size: '<svg viewBox="0 0 24 24" ' + P + '><path d="M2.5 19 7.5 6l5 13M4.4 14.5h6.2"/><path d="m14 19 3.5-8.5L21 19M15.1 16.2h4.8"/></svg>',
    contrast: '<svg viewBox="0 0 24 24" ' + P + '><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 1 0 18z" fill="currentColor"/></svg>',
    gray: '<svg viewBox="0 0 24 24" ' + P + '><path d="M12 3.2s6 6.4 6 10.9a6 6 0 0 1-12 0c0-4.5 6-10.9 6-10.9z"/><path d="M12 20.1V8.4" /><path d="M12 14.5l3.4-3.1M12 18l4.6-4.1"/></svg>',
    spacing: '<svg viewBox="0 0 24 24" ' + P + '><path d="M3 6.5h11M3 12h11M3 17.5h11"/><path d="M19 4.5v15M17 6.5l2-2 2 2M17 17.5l2 2 2-2"/></svg>',
    font: '<svg viewBox="0 0 24 24" ' + P + '><path d="M3 18.5 8 5.5l5 13M4.9 14h6.2"/><circle cx="17.6" cy="15.3" r="3.2"/><path d="M20.8 12.1v6.4"/></svg>',
    links: '<svg viewBox="0 0 24 24" ' + P + '><path d="M10 14a4.2 4.2 0 0 0 6 0l3-3a4.2 4.2 0 0 0-6-6l-1 1"/><path d="M14 10a4.2 4.2 0 0 0-6 0l-3 3a4.2 4.2 0 0 0 6 6l1-1"/></svg>',
    focus: '<svg viewBox="0 0 24 24" ' + P + '><path d="M4 8.5V4h4.5M15.5 4H20v4.5M20 15.5V20h-4.5M8.5 20H4v-4.5"/><circle cx="12" cy="12" r="3.2"/></svg>',
    motion: '<svg viewBox="0 0 24 24" ' + P + '><circle cx="12" cy="12" r="9"/><path d="M10 9v6M14 9v6"/></svg>',
    cursor: '<svg viewBox="0 0 24 24" ' + P + '><path d="M5 3.5 19 10l-6.2 2.1L10.6 18.5z"/><path d="m13 12.3 5 5"/></svg>',
    guide: '<svg viewBox="0 0 24 24" ' + P + '><path d="M3 6h18M3 18h18" opacity=".45"/><rect x="2.5" y="10" width="19" height="4" rx="1.2"/></svg>',
    help: '<svg viewBox="0 0 24 24" ' + P + '><circle cx="12" cy="12" r="9"/><path d="M9.6 9.4a2.5 2.5 0 1 1 3.4 2.4c-.6.3-1 .8-1 1.5v.5"/><circle cx="12" cy="16.9" r=".7" fill="currentColor"/></svg>',
    close: '<svg viewBox="0 0 24 24" ' + P + ' stroke-width="2"><path d="M6 6l12 12M18 6 6 18"/></svg>',
    sun: '<svg viewBox="0 0 24 24" ' + P + '><circle cx="12" cy="12" r="4"/><path d="M12 2.5v2M12 19.5v2M4.6 4.6 6 6M18 18l1.4 1.4M2.5 12h2M19.5 12h2M4.6 19.4 6 18M18 6l1.4-1.4"/></svg>',
    moon: '<svg viewBox="0 0 24 24" ' + P + '><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5z"/></svg>',
    reset: '<svg viewBox="0 0 24 24" ' + P + '><path d="M3.5 12a8.5 8.5 0 1 0 2.6-6.1"/><path d="M3.5 4v4.5H8"/></svg>',
    minus: '<svg viewBox="0 0 24 24" ' + P + ' stroke-width="2.2"><path d="M6 12h12"/></svg>',
    plus: '<svg viewBox="0 0 24 24" ' + P + ' stroke-width="2.2"><path d="M12 6v12M6 12h12"/></svg>',
  };

  // Config lesen + absichern
  const readConfig = () => {
    let c = {};
    try {
      const el = document.getElementById('mgd-barrierefrei-config');
      const parsed = el ? JSON.parse(el.textContent || '{}') : (window.MGD_BARRIEREFREI_CONFIG || {});
      if (parsed && typeof parsed === 'object') c = parsed;
    } catch (_) { /* kaputte config > defaults */ }
    const pick = (v, list, def) => (list.includes(v) ? v : def);
    const str = (v) => (typeof v === 'string' ? v.trim().slice(0, 80) : '');
    const url = typeof c.statementUrl === 'string' && /^(\/(?![\/\\])|https:\/\/)[^\s"'<>\\]*$/.test(c.statementUrl.trim()) ? c.statementUrl.trim() : '';
    return {
      position: pick(c.position, ['left', 'right'], 'left'),
      accent: typeof c.accent === 'string' && /^#[0-9a-fA-F]{6}$/.test(c.accent) ? c.accent : '#b91c1c',
      lang: pick(c.lang, ['auto', 'de', 'en'], 'auto'),
      theme: pick(c.theme, ['auto', 'light', 'dark'], 'auto'),
      labels: { de: str(c.labels && c.labels.de), en: str(c.labels && c.labels.en) },
      statementUrl: url,
      offset: Math.max(0, Math.min(200, parseInt(c.offset, 10) || 0)),
    };
  };

  const loadState = () => {
    try {
      const s = JSON.parse(localStorage.getItem(KEY) || '{}');
      if (s && typeof s === 'object' && !Array.isArray(s)) return s;
    } catch (_) { /* storage gesperrt */ }
    return {};
  };
  const saveState = (s) => { try { localStorage.setItem(KEY, JSON.stringify(s)); } catch (_) { /* egal */ } };

  // Text auf Akzentfarbe: weiss oder schwarz je nach Kontrast
  const onAccent = (hex) => {
    const ch = [1, 3, 5].map((i) => parseInt(hex.slice(i, i + 2), 16) / 255).map((v) => (v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4));
    const lum = 0.2126 * ch[0] + 0.7152 * ch[1] + 0.0722 * ch[2];
    return (1.05 / (lum + 0.05)) >= ((lum + 0.05) / 0.05) ? '#ffffff' : '#111111';
  };

  // Stylesheets - adoptedStyleSheets wegen CSP, sonst <style>
  const sheet = (root, css) => {
    try {
      const s = new CSSStyleSheet();
      s.replaceSync(css);
      root.adoptedStyleSheets = [...root.adoptedStyleSheets, s];
      return s;
    } catch (_) { /* alter Browser */ }
    const st = document.createElement('style');
    st.textContent = css;
    (root === document ? document.head : root).appendChild(st);
    return null;
  };

  const CURSOR = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48' viewBox='0 0 24 24'%3E%3Cpath d='M4 2.5 20 10l-7 2.4-2.6 7.1z' fill='%23000' stroke='%23fff' stroke-width='1.5' stroke-linejoin='round'/%3E%3C/svg%3E\") 6 3, auto";
  const H = HOST_TAG;
  const NOT_MEDIA = ':not(img):not(picture):not(video):not(canvas):not(svg):not(svg *):not(iframe)';
  const ICON_FONTS = ':not(i):not([class*="icon"]):not([class*="fa-"]):not([class*="dashicons"]):not([class*="material-"]):not([data-icon]):not(.et-pb-icon):not([class^="et_pb_font_icon"])';

  // Effekte auf der Seite (light DOM), Widget selbst liegt im Shadow DOM und bleibt unberuehrt
  const PAGE_CSS = `
${H}{all:initial!important;display:block!important;position:static!important;width:0!important;height:0!important;}
html.mgd-a11y-gray{filter:grayscale(1)!important;}
html.mgd-a11y-contrast body,html.mgd-a11y-contrast body *${NOT_MEDIA}:not(${H}){background-color:#000!important;color:#fff!important;border-color:#fff!important;text-shadow:none!important;box-shadow:none!important;}
html.mgd-a11y-contrast body a${NOT_MEDIA}:not(${H}),html.mgd-a11y-contrast body a *${NOT_MEDIA}:not(${H}){color:#ffe600!important;}
html.mgd-a11y-contrast body :is(button,input,select,textarea):not(${H}){outline:1px solid #fff!important;}
html.mgd-a11y-contrast body :is(input,textarea)::placeholder{color:#ddd!important;}
html.mgd-a11y-spacing body *:not(${H}){line-height:1.75!important;letter-spacing:.08em!important;word-spacing:.16em!important;}
html.mgd-a11y-spacing body p:not(${H}){margin-bottom:1.6em!important;}
html.mgd-a11y-font body *${ICON_FONTS}:not(${H}){font-family:Verdana,Tahoma,"Segoe UI",Arial,sans-serif!important;}
html.mgd-a11y-links body a:not(${H}){text-decoration:underline!important;text-decoration-thickness:2px!important;text-underline-offset:.22em!important;outline:2px dashed currentColor!important;outline-offset:2px!important;}
html.mgd-a11y-focus body :focus:not(${H}){outline:4px solid #ffbf00!important;outline-offset:3px!important;box-shadow:0 0 0 7px #000!important;}
html.mgd-a11y-motion,html.mgd-a11y-motion *,html.mgd-a11y-motion *::before,html.mgd-a11y-motion *::after{animation-duration:.001ms!important;animation-delay:0s!important;animation-iteration-count:1!important;transition-duration:.001ms!important;transition-delay:0s!important;scroll-behavior:auto!important;}
html.mgd-a11y-cursor,html.mgd-a11y-cursor *{cursor:${CURSOR}!important;}
`;

  // Widget-CSS im Shadow DOM - Divi/Elementor CSS kommt hier nicht rein
  const UI_CSS = `
:host{all:initial;}
*,*::before,*::after{box-sizing:border-box;}
.w{--acc:#b91c1c;--on-acc:#fff;--bg:#ffffff;--bg2:#f3f5f8;--line:#d5dbe3;--txt:#141a22;--mut:#4a5563;--sh:0 24px 60px rgba(10,20,35,.28);
  font:400 15px/1.45 system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:var(--txt);-webkit-font-smoothing:antialiased;}
.w[data-theme="dark"]{--bg:#141a22;--bg2:#1e2631;--line:#334050;--txt:#eef2f6;--mut:#aab4c0;--sh:0 24px 60px rgba(0,0,0,.6);}
svg{width:100%;height:100%;display:block;}
button{font:inherit;color:inherit;margin:0;}
/* Trigger Button rund, Text nur bei Hover */
.trg{position:fixed;bottom:calc(20px + var(--off,0px));z-index:2147483000;display:flex;align-items:center;height:56px;min-width:56px;padding:0 0 0 13px;border:0;border-radius:999px;
  background:var(--acc);color:var(--on-acc);box-shadow:0 10px 28px rgba(0,0,0,.28),0 0 0 3px rgba(255,255,255,.9);cursor:pointer;transition:padding .25s ease,box-shadow .2s ease,transform .2s ease;}
.w[data-pos="left"] .trg{left:20px;} .w[data-pos="right"] .trg{right:20px;flex-direction:row-reverse;padding:0 13px 0 0;}
.trg .ico{width:30px;height:30px;flex:0 0 30px;}
.trg .lbl{max-width:0;overflow:hidden;white-space:nowrap;font-weight:600;font-size:15px;opacity:0;transition:max-width .3s ease,opacity .2s ease,margin .3s ease;}
.trg:focus-visible .lbl{max-width:240px;opacity:1;margin:0 18px 0 10px;}
.w[data-pos="right"] .trg:focus-visible .lbl{margin:0 10px 0 18px;}
@media (hover:hover){
  .trg:hover .lbl{max-width:240px;opacity:1;margin:0 18px 0 10px;}
  .w[data-pos="right"] .trg:hover .lbl{margin:0 10px 0 18px;}
  .trg:hover{transform:translateY(-2px);}
}
.trg:focus-visible{outline:3px solid var(--txt);outline-offset:3px;}
/* Panel */
.pnl{position:fixed;bottom:calc(88px + var(--off,0px));z-index:2147483001;width:min(400px,calc(100vw - 24px));max-height:calc(100vh - 110px - var(--off,0px));max-height:calc(100dvh - 110px - var(--off,0px));overflow:auto;overscroll-behavior:contain;
  background:var(--bg);border:1px solid var(--line);border-radius:20px;box-shadow:var(--sh);padding:0 0 14px;}
.pnl[hidden]{display:none;}
.w[data-pos="left"] .pnl{left:16px;} .w[data-pos="right"] .pnl{right:16px;}
.pnl:focus{outline:none;}
.hd{position:sticky;top:0;z-index:2;display:flex;align-items:center;gap:10px;padding:16px 16px 12px;background:var(--bg);border-bottom:1px solid var(--line);}
.hd .badge{width:38px;height:38px;flex:0 0 38px;padding:6px;border-radius:12px;background:var(--acc);color:var(--on-acc);}
.hd h2{flex:1;margin:0;font-size:17px;font-weight:700;line-height:1.2;color:var(--txt);}
.ib{width:38px;height:38px;flex:0 0 38px;display:grid;place-items:center;padding:8px;border:1px solid var(--line);border-radius:11px;background:var(--bg2);cursor:pointer;}
.ib:hover{border-color:var(--acc);}
.ib:focus-visible,.seg button:focus-visible,.card button:focus-visible,.rst:focus-visible,.ft a:focus-visible,.thm:focus-visible,.stp button:focus-visible,.pnl:focus-visible{outline:3px solid var(--txt);outline-offset:2px;box-shadow:0 0 0 5px var(--bg);}
.bar{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 16px 4px;}
.intro{margin:0;padding:8px 16px 4px;color:var(--mut);font-size:13.5px;}
.seg{display:inline-flex;padding:3px;border:1px solid var(--line);border-radius:11px;background:var(--bg2);}
.seg button{min-width:44px;height:32px;padding:0 10px;border:0;border-radius:8px;background:transparent;font-weight:600;font-size:13px;cursor:pointer;color:var(--mut);}
.seg button[aria-pressed="true"]{background:var(--acc);color:var(--on-acc);}
.thm[aria-pressed="true"]{border-color:var(--acc);}
.thm{display:inline-flex;align-items:center;gap:8px;height:38px;padding:0 12px 0 9px;border:1px solid var(--line);border-radius:11px;background:var(--bg2);cursor:pointer;font-size:13px;font-weight:600;}
.thm .ico{width:20px;height:20px;}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:12px 16px 4px;}
.card{position:relative;display:flex;flex-direction:column;border:1.5px solid var(--line);border-radius:14px;background:var(--bg2);transition:border-color .15s ease,background .15s ease;}
.card.wide{grid-column:1 / -1;}
.card[data-on="true"]{border-color:var(--acc);background:var(--bg);background:color-mix(in srgb,var(--acc) 12%,var(--bg));}
.main{position:relative;display:flex;flex-direction:column;align-items:flex-start;gap:8px;width:100%;min-height:92px;padding:12px 40px 12px 12px;border:0;border-radius:13px;background:transparent;text-align:left;cursor:pointer;}
.main .ico{width:26px;height:26px;color:var(--acc);}
.w[data-theme="dark"] .main .ico{color:#fff;color:color-mix(in srgb,var(--acc) 55%,#fff);}
.main .nm{font-weight:650;font-size:14px;line-height:1.25;}
.main .st{position:absolute;right:12px;bottom:12px;width:30px;height:18px;border-radius:999px;background:var(--line);transition:background .15s ease;}
.main .st::after{content:"";position:absolute;top:2px;left:2px;width:14px;height:14px;border-radius:50%;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,.3);transition:transform .15s ease;}
.card[data-on="true"] .st{background:var(--acc);} .card[data-on="true"] .st::after{transform:translateX(12px);}
.q{position:absolute;top:8px;right:8px;width:28px;height:28px;padding:4px;border:0;border-radius:50%;background:transparent;color:var(--mut);cursor:pointer;}
.q:hover,.q[aria-expanded="true"]{color:var(--acc);background:var(--bg);}
.tip{margin:0 12px 12px;padding:9px 11px;border-radius:10px;background:var(--bg);border:1px solid var(--line);font-size:13px;line-height:1.45;color:var(--txt);}
.tip[hidden]{display:none;}
.size{display:flex;align-items:center;gap:12px;padding:12px 40px 12px 12px;min-height:64px;}
.size .ico{width:26px;height:26px;color:var(--acc);flex:0 0 26px;}
.w[data-theme="dark"] .size .ico{color:#fff;color:color-mix(in srgb,var(--acc) 55%,#fff);}
.size .nm{flex:1;font-weight:650;font-size:14px;}
.stp{display:flex;align-items:center;gap:6px;}
.stp button{width:36px;height:36px;padding:8px;border:1px solid var(--line);border-radius:10px;background:var(--bg);cursor:pointer;}
.stp button[aria-disabled="true"]{opacity:.35;cursor:not-allowed;}
.stp output{min-width:48px;text-align:center;font-weight:700;font-variant-numeric:tabular-nums;}
.rst{display:flex;align-items:center;justify-content:center;gap:8px;width:calc(100% - 32px);margin:12px 16px 0;height:44px;border:1px solid var(--line);border-radius:12px;background:var(--bg2);font-weight:600;cursor:pointer;}
.rst .ico{width:18px;height:18px;}
.rst:hover{border-color:var(--acc);}
.ft{display:flex;flex-direction:column;gap:6px;padding:12px 16px 0;font-size:12.5px;}
.ft a{color:var(--mut);text-decoration:none;}
.ft a:hover{color:var(--acc);text-decoration:underline;}
.ft a.st-link{color:var(--txt);font-weight:600;font-size:13.5px;}
.sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;}
.guide{position:fixed;left:0;right:0;height:36px;z-index:2147482999;pointer-events:none;border-top:3px solid var(--acc);border-bottom:3px solid var(--acc);
  box-shadow:0 0 0 100vmax rgba(0,0,0,.35);transform:translateY(-50%);}
.guide[hidden]{display:none;}
@media (max-width:480px){
  .pnl{left:8px!important;right:8px!important;width:auto;bottom:calc(84px + var(--off,0px));border-radius:18px;}
  .trg{bottom:calc(14px + var(--off,0px));} .w[data-pos="left"] .trg{left:14px;} .w[data-pos="right"] .trg{right:14px;}
}
@media (prefers-reduced-motion:reduce){.trg,.lbl,.card,.st,.st::after{transition:none!important;}}
`;

  const el = (tag, attrs, kids) => {
    const n = document.createElement(tag);
    if (attrs) Object.entries(attrs).forEach(([k, v]) => { if (v !== null && v !== undefined && v !== false) n.setAttribute(k, v === true ? '' : String(v)); });
    (kids || []).forEach((k) => n.append(k));
    return n;
  };
  const icon = (name, cls) => { const s = el('span', { class: cls || 'ico', 'aria-hidden': 'true' }); s.innerHTML = I[name]; return s; };

  // Schriftgroesse - echte px Werte, klappt auch bei Divi/Elementor mit px Fonts
  const fontScaler = (host) => {
    const touched = new Map();
    const SKIP = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEMPLATE', 'IFRAME', 'svg', 'SVG', 'CANVAS', 'VIDEO', 'PICTURE', 'IMG', 'BR', 'HR']);
    const FORM = new Set(['INPUT', 'TEXTAREA', 'SELECT', 'BUTTON']);
    let scale = 1;
    let mo = null;
    let timer = 0;
    const restore = () => {
      touched.forEach((o, n) => {
        n.style.removeProperty('font-size');
        n.style.removeProperty('line-height');
        if (o.fs) n.style.setProperty('font-size', o.fs, o.fsp);
        if (o.lh) n.style.setProperty('line-height', o.lh, o.lhp);
      });
      touched.clear();
    };
    const hasText = (n) => {
      if (FORM.has(n.tagName)) return true;
      for (let c = n.firstChild; c; c = c.nextSibling) if (c.nodeType === 3 && c.nodeValue.trim() !== '') return true;
      return false;
    };
    const run = () => {
      if (mo) mo.disconnect();
      restore();
      if (scale !== 1 && document.body) {
        const list = [];
        const walk = document.createTreeWalker(document.body, NodeFilter.SHOW_ELEMENT, {
          acceptNode: (n) => (n === host || SKIP.has(n.tagName) || n.namespaceURI === 'http://www.w3.org/2000/svg' ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT),
        });
        for (let n = walk.nextNode(); n; n = walk.nextNode()) if (hasText(n)) list.push(n);
        // erst alles lesen, dann schreiben - kein Layout-Thrashing
        const sizes = list.map((n) => { const cs = getComputedStyle(n); return [parseFloat(cs.fontSize), cs.lineHeight]; });
        list.forEach((n, i) => {
          const [fs, lh] = sizes[i];
          if (!fs) return;
          touched.set(n, { fs: n.style.getPropertyValue('font-size'), fsp: n.style.getPropertyPriority('font-size'), lh: n.style.getPropertyValue('line-height'), lhp: n.style.getPropertyPriority('line-height') });
          n.style.setProperty('font-size', (fs * scale).toFixed(2) + 'px', 'important');
          if (lh.endsWith('px')) n.style.setProperty('line-height', (parseFloat(lh) * scale).toFixed(2) + 'px', 'important');
        });
        if (!mo) mo = new MutationObserver(onMutate);
        mo.observe(document.body, { childList: true, subtree: true });
      }
    };
    // nur neue Elemente zaehlen, Text-Ticker (Uhr, Zaehler) ignorieren
    const addsElements = (list) => list.some((m) => [...m.addedNodes].some((n) => n.nodeType === 1 && n !== host));
    let firstHit = 0;
    let runs = [];
    const schedule = () => {
      const now = Date.now();
      runs = runs.filter((t) => now - t < 10000);
      // Backoff falls Slider & Co dauernd umbauen
      const delay = runs.length > 6 ? 3000 : 300;
      if (!firstHit) firstHit = now;
      clearTimeout(timer);
      const wait = Math.max(0, Math.min(delay, firstHit + Math.max(1000, delay) - now));
      timer = setTimeout(() => { firstHit = 0; runs.push(Date.now()); run(); }, wait);
    };
    const onMutate = (list) => { if (scale !== 1 && addsElements(list)) schedule(); };
    let lastW = window.innerWidth;
    let rz = 0;
    // iOS feuert resize beim Scrollen (Adressleiste) > nur bei neuer Breite
    window.addEventListener('resize', () => {
      if (scale === 1 || window.innerWidth === lastW) return;
      lastW = window.innerWidth;
      clearTimeout(rz);
      rz = setTimeout(run, 250);
    });
    return (s) => { scale = s; run(); };
  };

  // Autoplay Videos anhalten und spaeter wieder starten
  const paused = new Set();
  const onPlay = (e) => { if (e.target instanceof HTMLMediaElement && e.target.tagName === 'VIDEO') { e.target.pause(); paused.add(e.target); } };
  const setVideos = (stop) => {
    if (stop) {
      document.querySelectorAll('video').forEach((v) => { if (!v.paused) { v.pause(); paused.add(v); } });
      // play blubbert nicht > capture
      document.addEventListener('play', onPlay, true);
    } else {
      document.removeEventListener('play', onPlay, true);
      paused.forEach((v) => { if (v.isConnected) v.play().catch(() => {}); });
      paused.clear();
    }
  };

  const init = () => {
    if (!document.body || document.querySelector(HOST_TAG)) return;
    const cfg = readConfig();
    const state = loadState();
    const html = document.documentElement;
    const mql = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

    const pageLang = () => {
      const l = (html.getAttribute('lang') || navigator.language || 'de').toLowerCase();
      return l.startsWith('en') ? 'en' : 'de';
    };
    const lang = () => (state.lang === 'de' || state.lang === 'en') ? state.lang : (cfg.lang === 'auto' ? pageLang() : cfg.lang);
    const theme = () => {
      const t = (state.theme === 'light' || state.theme === 'dark') ? state.theme : cfg.theme;
      return t === 'auto' ? (mql && mql.matches ? 'dark' : 'light') : t;
    };
    const size = () => { const n = Number(state.size); return Number.isFinite(n) ? Math.max(SIZE_MIN, Math.min(SIZE_MAX, Math.round(n))) : 0; };
    if (!state.f || typeof state.f !== 'object') state.f = {};

    // falls ein Shop-Script adoptedStyleSheets ueberschreibt > in applyPage wieder anhaengen
    const pageSheet = sheet(document, PAGE_CSS);
    const host = document.createElement(HOST_TAG);
    const root = host.attachShadow({ mode: 'open' });
    sheet(root, UI_CSS);
    const scaleFonts = fontScaler(host);

    // Aufbau
    const w = el('div', { class: 'w', 'data-pos': cfg.position });
    w.style.setProperty('--acc', cfg.accent);
    w.style.setProperty('--on-acc', onAccent(cfg.accent));
    w.style.setProperty('--off', cfg.offset + 'px');

    const lbl = el('span', { class: 'lbl', 'aria-hidden': 'true' });
    const trg = el('button', { class: 'trg', type: 'button', 'aria-haspopup': 'dialog', 'aria-expanded': 'false', 'aria-controls': 'mgd-a11y-panel' }, [icon('access'), lbl]);

    const h2 = el('h2', { id: 'mgd-a11y-title' });
    const closeBtn = el('button', { class: 'ib', type: 'button' }, [icon('close')]);
    const pnl = el('section', { class: 'pnl', id: 'mgd-a11y-panel', role: 'dialog', 'aria-modal': 'true', 'aria-labelledby': 'mgd-a11y-title', tabindex: '-1', hidden: true });
    const hd = el('div', { class: 'hd' }, [icon('access', 'badge'), h2, closeBtn]);

    const deBtn = el('button', { type: 'button', lang: 'de' }, ['DE']);
    const enBtn = el('button', { type: 'button', lang: 'en' }, ['EN']);
    const seg = el('div', { class: 'seg', role: 'group' }, [deBtn, enBtn]);
    const thmIco = el('span', { class: 'ico', 'aria-hidden': 'true' });
    const thmTxt = el('span');
    const thm = el('button', { class: 'thm', type: 'button' }, [thmIco, thmTxt]);
    const bar = el('div', { class: 'bar' }, [seg, thm]);
    const intro = el('p', { class: 'intro', id: 'mgd-a11y-intro' });
    pnl.setAttribute('aria-describedby', 'mgd-a11y-intro');

    const grid = el('div', { class: 'grid' });
    const tips = [];
    const mkHelp = (key, tipId) => {
      const q = el('button', { class: 'q', type: 'button', 'aria-expanded': 'false', 'aria-controls': tipId }, [icon('help')]);
      const tip = el('p', { class: 'tip', id: tipId, hidden: true });
      q.addEventListener('click', () => {
        const open = q.getAttribute('aria-expanded') !== 'true';
        tips.forEach(([oq, ot]) => { oq.setAttribute('aria-expanded', 'false'); ot.hidden = true; });
        q.setAttribute('aria-expanded', String(open));
        tip.hidden = !open;
      });
      tips.push([q, tip, key]);
      return [q, tip];
    };

    // Schriftgroesse Karte
    const sizeName = el('span', { class: 'nm', id: 'mgd-a11y-size-name' });
    const minus = el('button', { type: 'button' }, [icon('minus')]);
    const plus = el('button', { type: 'button' }, [icon('plus')]);
    const out = el('output', { 'aria-live': 'polite' });
    const [sq, stip] = mkHelp('size', 'mgd-a11y-tip-size');
    const sizeCard = el('div', { class: 'card wide', 'data-key': 'size' }, [
      el('div', { class: 'size', role: 'group', 'aria-labelledby': 'mgd-a11y-size-name' }, [icon('size'), sizeName, el('div', { class: 'stp' }, [minus, out, plus])]), sq, stip,
    ]);
    grid.append(sizeCard);

    const cards = {};
    const fineMouse = !window.matchMedia || window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    TOGGLES.filter((key) => key !== 'guide' || fineMouse).forEach((key) => {
      const nm = el('span', { class: 'nm' });
      const main = el('button', { class: 'main', type: 'button', 'aria-pressed': 'false', 'data-key': key }, [icon(key), nm, el('span', { class: 'st', 'aria-hidden': 'true' })]);
      const [q, tip] = mkHelp(key, 'mgd-a11y-tip-' + key);
      const card = el('div', { class: 'card', 'data-key': key, 'data-on': 'false' }, [main, q, tip]);
      cards[key] = { card, main, nm };
      grid.append(card);
    });

    const rstTxt = el('span');
    const rst = el('button', { class: 'rst', type: 'button' }, [icon('reset'), rstTxt]);
    const ft = el('div', { class: 'ft' });
    let stLink = null;
    if (cfg.statementUrl) { stLink = el('a', { class: 'st-link', href: cfg.statementUrl }); ft.append(stLink); }
    const credTxt = el('span');
    const credNew = el('span', { class: 'sr' });
    const cred = el('a', { href: 'https://michael-gahn.de', target: '_blank', rel: 'noopener noreferrer' }, [credTxt, credNew]);
    ft.append(cred);

    pnl.append(hd, bar, intro, grid, rst, ft);
    const guide = el('div', { class: 'guide', hidden: true, 'aria-hidden': 'true' });
    w.append(trg, pnl, guide);
    root.append(w);
    document.body.appendChild(host);

    // Texte + Zustand anwenden
    const render = () => {
      const L = lang();
      const t = T[L];
      const label = cfg.labels[L] || t.label;
      w.setAttribute('data-theme', theme());
      pnl.setAttribute('lang', L);
      trg.setAttribute('lang', L);
      lbl.textContent = label;
      trg.setAttribute('aria-label', label);
      h2.textContent = t.title;
      closeBtn.setAttribute('aria-label', t.close);
      seg.setAttribute('aria-label', t.lang);
      deBtn.setAttribute('aria-pressed', String(L === 'de'));
      enBtn.setAttribute('aria-pressed', String(L === 'en'));
      const dark = theme() === 'dark';
      thmIco.innerHTML = I.moon;
      thmTxt.textContent = t.dark;
      thm.setAttribute('aria-pressed', String(dark));
      intro.textContent = t.intro;
      sizeName.textContent = t.f.size[0];
      minus.setAttribute('aria-label', t.smaller);
      plus.setAttribute('aria-label', t.bigger);
      const sz = size();
      out.textContent = Math.round((1 + sz * SIZE_STEP) * 100) + ' %';
      minus.setAttribute('aria-disabled', String(sz <= SIZE_MIN));
      plus.setAttribute('aria-disabled', String(sz >= SIZE_MAX));
      sizeCard.setAttribute('data-on', String(sz !== 0));
      tips.forEach(([q, tip, key]) => { q.setAttribute('aria-label', t.help + ' ' + t.f[key][0]); tip.textContent = t.f[key][1]; });
      TOGGLES.forEach((key) => {
        if (!cards[key]) return;
        const on = state.f[key] === true;
        cards[key].nm.textContent = t.f[key][0];
        cards[key].main.setAttribute('aria-pressed', String(on));
        cards[key].card.setAttribute('data-on', String(on));
      });
      rstTxt.textContent = t.reset;
      if (stLink) stLink.textContent = t.statement;
      credTxt.textContent = t.credit;
      credNew.textContent = ' ' + t.newTab;
    };

    let lastScale = 1;
    let lastMotion = false;
    const applyPage = () => {
      TOGGLES.forEach((key) => { if (key !== 'guide') html.classList.toggle('mgd-a11y-' + key, state.f[key] === true); });
      const g = state.f.guide === true && fineMouse;
      if (g && guide.hidden) guide.style.top = Math.round(window.innerHeight / 2) + 'px';
      guide.hidden = !g;
      if (pageSheet && document.adoptedStyleSheets && !document.adoptedStyleSheets.includes(pageSheet)) document.adoptedStyleSheets = [...document.adoptedStyleSheets, pageSheet];
      const motion = state.f.motion === true;
      if (motion !== lastMotion) { setVideos(motion); lastMotion = motion; }
      const s = 1 + size() * SIZE_STEP;
      if (s !== lastScale) { lastScale = s; scaleFonts(s); }
    };
    const update = () => { saveState(state); render(); applyPage(); };

    // Events
    const isOpen = () => !pnl.hidden;
    const open = () => { pnl.hidden = false; trg.setAttribute('aria-expanded', 'true'); pnl.focus(); };
    const shut = (back = true) => {
      if (!isOpen()) return;
      pnl.hidden = true;
      trg.setAttribute('aria-expanded', 'false');
      // Safari setzt beim Klick keinen Fokus > immer zurueck auf den Button
      if (back) trg.focus();
    };
    trg.addEventListener('click', () => (isOpen() ? shut() : open()));
    closeBtn.addEventListener('click', () => shut());
    deBtn.addEventListener('click', () => { state.lang = 'de'; update(); });
    enBtn.addEventListener('click', () => { state.lang = 'en'; update(); });
    thm.addEventListener('click', () => { state.theme = theme() === 'dark' ? 'light' : 'dark'; update(); });
    // aria-disabled statt disabled, sonst fliegt der Fokus raus
    minus.addEventListener('click', () => { if (size() > SIZE_MIN) { state.size = size() - 1; update(); } });
    plus.addEventListener('click', () => { if (size() < SIZE_MAX) { state.size = size() + 1; update(); } });
    Object.values(cards).forEach(({ main }) => main.addEventListener('click', () => {
      const k = main.getAttribute('data-key');
      state.f[k] = state.f[k] !== true;
      update();
    }));
    rst.addEventListener('click', () => { state.f = {}; state.size = 0; update(); });
    if (mql && mql.addEventListener) mql.addEventListener('change', render);

    // Tastatur: Escape + Fokus im Dialog halten (Shadow DOM -> root.activeElement)
    const focusables = () => [...pnl.querySelectorAll('button:not([disabled]),a[href]')].filter((n) => n.getClientRects().length > 0);
    document.addEventListener('keydown', (e) => {
      if (!isOpen()) return;
      if (e.key === 'Escape') { e.preventDefault(); shut(); return; }
      if (e.key !== 'Tab') return;
      const items = focusables();
      if (!items.length) return;
      const first = items[0];
      const last = items[items.length - 1];
      const act = root.activeElement;
      if (e.shiftKey && (act === first || act === pnl || !act)) { e.preventDefault(); last.focus(); } else if (!e.shiftKey && (act === last || !act)) { e.preventDefault(); first.focus(); }
    });
    // Klick ausserhalb schliesst
    document.addEventListener('pointerdown', (e) => { if (isOpen() && !e.composedPath().includes(host)) shut(false); });
    // Lese-Lineal folgt der Maus
    document.addEventListener('pointermove', (e) => { if (!guide.hidden) guide.style.top = e.clientY + 'px'; }, { passive: true });

    render();
    applyPage();
  };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
