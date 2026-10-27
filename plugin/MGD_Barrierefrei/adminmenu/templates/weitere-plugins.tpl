{* Weitere Plugins - Admin-Tab, Daten kommen aus Presentation/CatalogTab.php *}
{literal}
<style>
.mgd-cat{max-width:1100px;padding:16px 0}
.mgd-cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;margin:16px 0}
.mgd-cat-item{--mgd-accent:#9aa8b6;display:flex;flex-direction:column;gap:8px;padding:16px;border:1px solid #d1d9e0;border-left:4px solid var(--mgd-accent);border-radius:6px;background:#fff}
.mgd-cat-item h3{margin:0;font-size:16px}
.mgd-cat-image{display:block;width:100%;height:auto;max-height:160px;object-fit:cover;border-radius:4px}
.mgd-cat-head{display:flex;align-items:center;gap:10px}
.mgd-cat-icon{flex:none;width:36px;height:36px;border-radius:6px;object-fit:cover}
.mgd-cat-icon-fallback{display:flex;align-items:center;justify-content:center;background:#eef2f6;color:#1f2d3d;font-weight:700}
.mgd-cat-badges{display:flex;flex-wrap:wrap;gap:6px;margin:0;padding:0;list-style:none}
.mgd-cat-badge{padding:2px 8px;border-radius:10px;background:#eef2f6;color:#1f2d3d;font-size:12px}
.mgd-cat-badge-rec{background:#1f2d3d;color:#fff}
.mgd-cat-text{margin:0;flex:1}
.mgd-cat-links{display:flex;flex-wrap:wrap;gap:6px 14px;margin:0}
.mgd-cat-note{margin:12px 0 0;font-size:13px;color:#52667a}
.mgd-cat-hint{padding:16px;border:1px dashed #9aa8b6;border-radius:6px}
.mgd-cat .sr-only{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}
</style>
{/literal}
<section class="mgd-cat" aria-labelledby="mgd-cat-title">
    <h1 id="mgd-cat-title">Weitere Plugins von Michael Gahn DESIGN</h1>
    <p>Hier siehst du weitere Plugins für JTL-Shop 5. Es wird nichts automatisch installiert. Zum Installieren lädst du das ZIP herunter und lädst es selbst in der Pluginverwaltung hoch.</p>

    <form method="post">
        {$mgdCatalog.tokenInput nofilter}
        <button type="submit" name="mgd_catalog_refresh" value="1" class="btn btn-primary">Liste aktualisieren</button>
    </form>

    {if !$mgdCatalog.available}
        <p class="mgd-cat-hint" role="status">Die Liste ist gerade nicht erreichbar. Dein Shop läuft ganz normal weiter. Versuche es später noch einmal.</p>
    {else}
        {if $mgdCatalog.stale}
            <p class="mgd-cat-hint" role="status">Achtung: Die Liste konnte nicht neu geladen werden. Du siehst den letzten gespeicherten Stand.</p>
        {/if}
        {if !$mgdCatalog.hasItems}
            <p class="mgd-cat-hint">Im Moment gibt es keine weiteren Plugins für JTL-Shop 5.</p>
        {else}
            <div class="mgd-cat-grid">
                {foreach $mgdCatalog.items as $item}
                    <article class="mgd-cat-item"{if $item.accent} style="--mgd-accent:{$item.accent|escape:'html'}"{/if}>
                        {if $item.imageUrl}<img class="mgd-cat-image" src="{$item.imageUrl|escape:'html'}" alt="{$item.imageAlt|escape:'html'}" loading="lazy" referrerpolicy="no-referrer">{/if}
                        <div class="mgd-cat-head">
                            {if $item.iconUrl}
                                <img class="mgd-cat-icon" src="{$item.iconUrl|escape:'html'}" alt="" width="36" height="36" loading="lazy" referrerpolicy="no-referrer">
                            {else}
                                <span class="mgd-cat-icon mgd-cat-icon-fallback" aria-hidden="true">{$item.initial|escape:'html'}</span>
                            {/if}
                            <h3>{$item.name|escape:'html'}</h3>
                        </div>
                        <ul class="mgd-cat-badges">
                            {if $item.recommended}<li class="mgd-cat-badge mgd-cat-badge-rec">Empfohlen</li>{/if}
                            <li class="mgd-cat-badge">{if $item.tier === 'free'}Kostenlos{else}Kostenpflichtig{/if}</li>
                            {if $item.status === 'announced'}<li class="mgd-cat-badge">Demnächst</li>{/if}
                            {if $item.version}<li class="mgd-cat-badge">Version {$item.version|escape:'html'}</li>{/if}
                        </ul>
                        <p class="mgd-cat-text">{$item.description|escape:'html'}</p>
                        <p class="mgd-cat-links">
                            {if $item.downloadUrl}<a href="{$item.downloadUrl|escape:'html'}" target="_blank" rel="noopener noreferrer">ZIP herunterladen <span class="sr-only">(öffnet in neuem Tab)</span></a>{/if}
                            <a href="{$item.detailsUrl|escape:'html'}" target="_blank" rel="noopener noreferrer">Mehr erfahren <span class="sr-only">(öffnet in neuem Tab)</span></a>
                            {if $item.repositoryUrl}<a href="{$item.repositoryUrl|escape:'html'}" target="_blank" rel="noopener noreferrer">Quellcode auf GitHub <span class="sr-only">(öffnet in neuem Tab)</span></a>{/if}
                        </p>
                    </article>
                {/foreach}
            </div>
        {/if}
        {if $mgdCatalog.updatedAt}<p class="mgd-cat-note">Stand der Liste: {$mgdCatalog.updatedAt|escape:'html'}</p>{/if}
    {/if}

    <p class="mgd-cat-note">Datenschutz: Zum Laden der Liste verbindet sich dein Shop-Server mit theme.michael-gahn.de. Es werden keine Shop-, Kunden- oder Zugangsdaten gesendet, der Server sieht aber die IP-Adresse deines Shop-Servers. Die Bilder lädt dein Browser, nur auf dieser Admin-Seite, ebenfalls von theme.michael-gahn.de; dabei sieht der Server deine IP-Adresse. Besucher deines Shops sind nicht betroffen.</p>
</section>
