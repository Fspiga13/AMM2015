<h2 class="icon-title" id="h-viaggi">Viaggi</h2>
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
                <th class="viaggi-col-small">Posti Liberi</th>
                <th class="viaggi-col-small">Prezzo</th>
                <th class="viaggi-col-small">Prenota</th>
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
                    <td><?= $viaggio->getPostiLiberi() ?></td>
		    <td><?= $viaggio->getPrezzo() ?></td>
		    <td><a href="utente/viaggi?cmd=prenota&viaggio=<?= $i ?><?= $vd->scriviToken('&') ?>" title="Prenota viaggio">Prenota ora!</a></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php } else { ?>
    <p> Nessun viaggio disponibile</p>
<?php } ?>
