<h2 id="help" class="icon-title">Istruzioni</h2>
<?php
switch ($vd->getSottoPagina()) {
    case 'anagrafica':
        ?>
        <p>
            In questa sezione puoi modificare i tuoi dati personali.
        </p>
        <ul>
            <li>
                L'<strong>indirizzo</strong> del tuo ufficio.
            </li>
            <li>
                I tuoi contatti  (<strong>email</strong>).
            </li>
            <li>
                La tua <strong>password</strong>.
            </li>
        </ul>
        <?php break; ?>

    <?php case 'viaggi': ?>
        <p>
            In questa sezione puoi visualizzare i viaggi da te organizzati.
            In particolare:
        </p>
        <ul>
            <li>
                Puoi crearne uno nuovo premendo il pulsante <em>Organizza Viaggio</em>.
            </li>
            <li>
                Puoi modificarne uno esistente premendo il pulsante <em>Modifica</em>, 
                identificabile dall'icona matita <img  src="../images/edit_icon.png" alt="icona modifica">
            </li>
            <li>
                Puoi eliminarne uno esistente premendo il pulsante <em>Elimina</em>, 
                identificabile dall'icona cestino <img  src="../images/delete_icon.png" alt="icona elimina">
            </li>
        </ul>
        <p>Per l'inserimento e la modifica &egrave; necessario specificare
            la Nazione e la Citt&agrave; di destinazione, le data (Andata e Ritorno),
	    i posti disponibili ed il prezzo. 
        </p>
        <?php break; ?>

    <?php default:
        ?>
        <p>
            Seleziona una delle seguenti funzionalit&agrave; disponibili per 
            la gestione dei tuoi viaggi:
        </p>
        <ol>
            <li>
                <strong>Anagrafica</strong> per modificare i tuoi dati 
                anagrafici e la tua password.
            </li>
            <li>
                <strong>Viaggi Organizzati</strong> per visualizzare e/o creare viaggi.
            </li>
            <li>
                <strong>Elenco Esami</strong> per visualizzare gli statini di esame.
            </li>
        </ol>
        <?php break; ?>
<?php } ?>
