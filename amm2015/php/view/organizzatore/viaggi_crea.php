<div class="input-form">
    <h3>Organizza Viaggio</h3>
    <form method="post" action="organizzatore/viaggi_crea<?= $vd->scriviToken('?')?>">
        <input type="hidden" name="cmd" value="v_nuovo"/>
        <label for="sede">Sede</label>
        <select name="sede" id="sede">
            <?php foreach ($sedi as $sede) { ?>
                <option value="<?= $sede->getId() ?>"><?= $sede->getNazione() ?> - <?= $sede->getCitta() ?></option>
            <?php } ?>
        </select>
        <br/>
        <label for="data_partenza">Data Partenza</label>
        <input type="text" name="data_partenza" id="data_partenza" />
        <br/>
        <label for="data_ritorno">Data Ritorno</label>
        <input type="text" name="data_ritorno" id="data_ritorno" />
        <br/>
        <label for="prezzo">Prezzo</label>
        <input type="text" name="prezzo" id="prezzo" />
        <br/>
        <label for="posti">Capienza</label>
        <input type="text" name="posti" id="posti" />
        <br/>
        <div class="btn-group">
            <button type="submit" name="cmd" value="v_nuovo">Salva</button>
            <button type="submit" name="cmd" value="v_annulla">Annulla</button>
        </div>
    </form>
</div>
