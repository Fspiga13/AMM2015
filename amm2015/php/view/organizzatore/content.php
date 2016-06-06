<?php
switch ($vd->getSottoPagina()) {
    case 'anagrafica':
        include 'anagrafica.php';
        break;

    case 'viaggi':
        include 'viaggi.php';
        break;
    
    case 'viaggi_modifica':
        include 'viaggi.php';
        include 'viaggi_modifica.php';
        break;
    
    case 'viaggi_crea':
        include 'viaggi.php';
        include 'viaggi_crea.php';
        break;
    
    case 'viaggi_prenotazione':
        include 'viaggi.php';
        include 'viaggi_prenotazione.php';
        break;
        ?>
        

    <?php default: ?>
        <h2 class="icon-title" id="h-home">Pannello di Controllo</h2>
        <p>
            Benvenuto, <?= $user->getNome() ?>
        </p>
        <p>
            Scegli una fra le seguenti sezioni:
        </p>
        <ul class="panel">
            <li><a href="organizzatore/anagrafica<?= $vd->scriviToken('?')?>" id="pnl-anagrafica">Anagrafica</a></li>
            <li><a href="organizzatore/viaggi<?= $vd->scriviToken('?')?>" id="pnl-viaggi">Viaggi Organizzati</a></li>
        </ul>
        <?php
        break;
}
?>


