<h2 class="icon-title">Organizzatore</h2>
<ul>
    <li class="<?= $vd->getSottoPagina() == 'home' || $vd->getSottoPagina() == null ? 'current_page_item' : ''?>"><a href="organizzatore/home<?= $vd->scriviToken('?')?>">Home</a></li>
    <li class="<?= $vd->getSottoPagina() == 'anagrafica' ? 'current_page_item' : '' ?>"><a href="organizzatore/anagrafica<?= $vd->scriviToken('?')?>">Anagrafica</a></li>
    <li class="<?= $vd->getSottoPagina() == 'viaggi' ? 'current_page_item' : '' ?>"><a href="organizzatore/viaggi<?= $vd->scriviToken('?')?>">Viaggi Organizzati</a></li>
</ul>
