<?php

declare(strict_types=1);

use JTL\Plugin\PluginInterface;
use Plugin\MGD_Barrierefrei\Presentation\StatementBuilder;

if (!defined('PFAD_ROOT') || !isset($oPlugin) || !$oPlugin instanceof PluginInterface) {
    http_response_code(403);
    echo 'Dieser Bereich ist nur in der JTL-Administration verfügbar.';
    return;
}

$config = $oPlugin->getConfig();
$text = (new StatementBuilder())->build([
    'organisation' => (string) $config->getValue('organisation'),
    'responsible_name' => (string) $config->getValue('responsible_name'),
    'contact_email' => (string) $config->getValue('contact_email'),
    'known_barriers' => (string) $config->getValue('known_barriers'),
    'statement_status' => (string) $config->getValue('statement_status'),
]);
?>
<section class="mgd-a11y-admin" aria-labelledby="mgd-a11y-statement-title">
    <style>.mgd-a11y-admin{max-width:900px;padding:24px;font:16px/1.55 system-ui,sans-serif}.mgd-a11y-admin h1{margin-top:0}.mgd-a11y-admin textarea{box-sizing:border-box;width:100%;min-height:520px;padding:16px;border:1px solid #b8c1cc;border-radius:10px;font:14px/1.5 ui-monospace,monospace}.mgd-a11y-admin button{margin-top:12px;padding:10px 16px;border:0;border-radius:7px;color:#fff;background:#b91c1c;cursor:pointer}.mgd-a11y-admin a{text-decoration:none}.mgd-a11y-admin a:hover{opacity:.75}</style>
    <h1 id="mgd-a11y-statement-title">Erklärung zur Barrierefreiheit</h1>
    <p>Der Text ist ein Entwurf aus den gespeicherten Einstellungen. Prüfen Sie Inhalt, Kontakt, Geltungsbereich und Rechtslage vor der Veröffentlichung.</p>
    <label for="mgd-a11y-statement">Kopierbarer Entwurf</label>
    <textarea id="mgd-a11y-statement" readonly><?= htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
    <br><button type="button" data-copy-statement>Text kopieren</button>
    <p><a href="https://michael-gahn.de" target="_blank" rel="noopener noreferrer">Michael-Gahn.de</a></p>
</section>
<script>document.querySelector('[data-copy-statement]')?.addEventListener('click',function(){const e=document.getElementById('mgd-a11y-statement');e.select();navigator.clipboard?.writeText(e.value);this.textContent='Kopiert';});</script>
