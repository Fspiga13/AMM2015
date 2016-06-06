<?php
switch ($vd->getSottoPagina()) {
    case 'anagrafica':
        include_once 'anagrafica.php';
        break;

    case 'viaggi':
        include_once 'viaggi.php';
        break;

    case 'prenotazioni':
        include_once 'prenotazioni.php';
        break;
    default:
        
        ?>
        <h2 class="icon-title" id="h-home">Pannello di Controllo</h2>
        <p>
            Benvenuto, <?= $user->getNome() ?>
        </p>
        <p>
            Scegli una fra le seguenti sezioni:
        </p>
        <ul class="panel">
            <li><a href="utente/anagrafica<?= $vd->scriviToken('?')?>" id="pnl-anagrafica">Anagrafica</a></li>
            <li><a href="utente/viaggi<?= $vd->scriviToken('?')?>" id="pnl-viaggi">Viaggi</a></li>
            <li><a href="utente/prenotazioni<?= $vd->scriviToken('?')?>" id="pnl-prenotazioni">Prenotazioni</a></li>
        </ul>
        <?php
        break;
}
?>


