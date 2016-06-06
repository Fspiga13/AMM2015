<h2 class="icon-title">Utente</h2>
<ul>
    <li class="<?= $vd->getSottoPagina() == 'home' || $vd->getSottoPagina() == null ? 'current_page_item' : ''?>"><a href="utente<?= $vd->scriviToken('?')?>">Home</a></li>
    <li class="<?= $vd->getSottoPagina() == 'anagrafica' ? 'current_page_item' : '' ?>"><a href="utente/anagrafica<?= $vd->scriviToken('?')?>">Anagrafica</a></li>
    <li class="<?= $vd->getSottoPagina() == 'viaggi' ? 'current_page_item' : '' ?>"><a href="utente/viaggi<?= $vd->scriviToken('?')?>">Viaggi</a></li>
    <li class="<?= $vd->getSottoPagina() == 'prenotazioni' ? 'current_page_item' : '' ?>"><a href="utente/prenotazioni<?= $vd->scriviToken('?')?>">Prenotazioni</a></li>
</ul>
