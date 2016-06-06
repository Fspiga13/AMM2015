<h2 class="icon-title" id="h-prenotazioni">Viaggi prenotati</h2>
<ul class="none">
    <li><strong>Nome:</strong> <?= $user->getNome() ?></li>
    <li><strong>Cognome:</strong> <?= $user->getCognome() ?></li>
</ul>

<?php if (count($prenotazioni) > 0) { ?>
    <table>
        <thead>
            <tr>
                <th class="prenotazione-col-large">Nazione</th>
                <th class="prenotazione-col-small">Citt&agrave;</th>
                <th class="prenotazione-col-large">Data Partenza</th>
                <th class="prenotazione-col-large">Data Ritorno</th>
                <th class="prenotazione-col-small">Cancella Prenotazione</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $c = 0;
            foreach ($prenotazioni as $prenotazione) {
                if ($prenotazione->inLista($user)) {
                    ?>
                    <tr <?= $c % 2 == 0 ? 'class="alt-row"' : '' ?>>                      
                        <td><?= $prenotazione->getSede()->getNazione() ?></td>
                        <td><?= $prenotazione->getSede()->getCitta() ?></td>
                        <td><?= $prenotazione->getDataPartenza()->format('d/m/Y') ?></td>
                        <td><?= $prenotazione->getDataRitorno()->format('d/m/Y') ?></td>
                        <td><a href="utente/prenotazione?cmd=cancella&prenotazione=<?= $prenotazione->getId() ?><?= $vd->scriviToken('&') ?>" title="Cancella la tua prenotazione">Elimina</a></td>
                    </tr>
                    <?php
                    $c++;
                }
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php } else { ?>
    <p> Nessun viaggio prenotato </p>
<?php } ?>
