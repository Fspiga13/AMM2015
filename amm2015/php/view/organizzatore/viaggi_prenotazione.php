<div class="input-form">
    <h3>Prenotati al viaggio per  <?= $mod_viaggio->getSede()->getNazione()?> - <?= $mod_viaggio->getSede()->getCitta() ?>  con partenza 
        <?= $mod_viaggio->getDataPartenza()->format('d/m/Y')  ?> e rientro <?= $mod_viaggio->getDataRitorno()->format('d/m/Y') ?>
    </h3>
    <ol>
        <?php
        foreach ($prenotati as $utente) {
            ?>
            <li><?= $utente->getCognome() ?> <?= $utente->getNome() ?></li>
            <?php
        }
        ?>
    </ol>
    <form method="get" action="organizzatore/viaggi<?= $vd->scriviToken('?')?>">
        <button type="submit" name="cmd" value="v_annulla">Chiudi</button>
    </form>

</div>
