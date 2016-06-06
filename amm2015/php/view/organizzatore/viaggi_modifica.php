<div class="input-form">
    <h3>Modifica Viaggio</h3>
    <form method="post" action="organizzatore/viaggi_modifica<?= $vd->scriviToken('?')?>">
        <input type="hidden" name="viaggio" value="<?= $mod_viaggio->getId() ?>"/>
        <label for="sede">Sede</label>
        <select name="sede" id="sede">
            <?php foreach ($sedi as $sede) { ?>
                <option value="<?= $sede->getId() ?>" <?= $mod_viaggio->getSede()->equals($sede) ? 'selected' : ''?> >  <?= $sede->getNazione() ?> - <?= $sede->getCitta() ?></option>
            <?php } ?>
        </select>
        <br/>
        <label for="data_partenza">Data Partenza</label>
        <input type="text" name="data_partenza" id="data_partenza" value="<?= $mod_viaggio->getDataPartenza()->format('d/m/Y') ?>"/>
        <br/>
        <label for="data_ritorno">Data Ritorno</label>
        <input type="text" name="data_ritorno" id="data_ritorno" value="<?= $mod_viaggio->getDataRitorno()->format('d/m/Y') ?>"/>
        <br/>
        <label for="prezzo">Prezzo</label>
        <input type="text" name="prezzo" id="prezzo" value="<?= $mod_viaggio->getPrezzo() ?>"/>
        <br/>
        <label for="posti">Capienza</label>
        <input type="text" name="posti" id="posti" value="<?= $mod_viaggio->getCapienza() ?>"/>
        <br/>
        <div class="btn-group">
            <button type="submit" name="cmd" value="v_salva">Salva</button>
            <button type="submit" name="cmd" value="v_annulla">Annulla</button>
        </div>
    </form>
</div>
