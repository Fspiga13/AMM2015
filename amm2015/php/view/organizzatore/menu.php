<ul>
    <li class="<?= strpos($vd->getSottoPagina(),'home') !== false || $vd->getSottoPagina() == null ? 'current_page_item' : ''?>"><a href="organizzatore/home<?= $vd->scriviToken('?')?>">Home</a></li>
    <li class="<?= strpos($vd->getSottoPagina(),'anagrafica') !== false ? 'current_page_item' : '' ?>"><a href="organizzatore/anagrafica<?= $vd->scriviToken('?')?>">Anagrafica</a></li>
    <li class="<?= strpos($vd->getSottoPagina(), 'viaggi') !== false ? 'current_page_item' : '' ?>"><a href="organizzatore/viaggi<?= $vd->scriviToken('?')?>">Viaggi Organizzati</a></li>
</ul>
