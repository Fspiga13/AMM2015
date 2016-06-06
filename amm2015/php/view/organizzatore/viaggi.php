<h2 class="icon-title" id="h-viaggi">Viaggi Organizzati</h2>
<ul class="none">
    <li><strong>Nome:</strong> <?= $user->getNome() ?></li>
    <li><strong>Cognome:</strong> <?= $user->getCognome() ?></li>
</ul>

<?php if (count($viaggi) > 0) { ?>
    <table>
        <thead>
            <tr>
                <th class="viaggi-col-large">Nazione</th>
                <th class="viaggi-col-large">Citt&agrave;</th>
                <th class="viaggi-col-large">Data Partenza</th>
                <th class="viaggi-col-large">Data Ritorno</th>
                <th class="viaggi-col-small">Prezzo</th>                
                <th class="viaggi-col-small">Prenotati</th>
                <th class="viaggi-col-small">Modifica</th>
                <th class="viaggi-col-small">Cancella</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($viaggi as $viaggio) {
                ?>
                <tr <?= $i % 2 == 0 ? 'class="alt-row"' : '' ?>>
                    <td><?= $viaggio->getSede()->getNazione() ?></td>
                    <td><?= $viaggio->getSede()->getCitta() ?></td>
                    <td><?= $viaggio->getDataPartenza()->format('d/m/Y') ?></td>
                    <td><?= $viaggio->getDataRitorno()->format('d/m/Y') ?></td>
                    <td><?= $viaggio->getPrezzo() ?></td>
                    <td>
                        <a href="organizzatore/viaggi_prenotazione?viaggio=<?= $viaggio->getId() ?><?= $vd->scriviToken('&') ?>" title="Lista prenotazioni">
                            <?= $viaggio->getNumeroPrenotati() ?>/<?= $viaggio->getCapienza() ?>
                        </a>
                    </td>
                    <td>
                        <a href="organizzatore/viaggi_modifica?viaggio=<?= $viaggio->getId() ?><?= $vd->scriviToken('&') ?>" title="Modifica il viaggio">
                            <img  src="../images/edit_icon.png" alt="Modifica">
                        </a>
                    </td>
                    <td>
                        <a href="organizzatore/viaggi?cmd=v_cancella&viaggio=<?= $viaggio->getId() ?><?= $vd->scriviToken('&') ?>" title="Elimina il viaggio">
                            <img  src="../images/delete_icon.png" alt="Elimina">
                        </a>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Nessun viaggio organizzato</p>
<?php } ?>
<div class="input-form">

    <form method="post" action="organizzatore/viaggi_crea<?= $vd->scriviToken('?') ?>">
        <button type="submit"name="cmd" value="v_crea">Organizza Viaggio</button>
    </form>
</div>

