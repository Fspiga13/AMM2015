<h2 id="help" class="icon-title">Istruzioni</h2>
<?php
switch ($vd->getSottoPagina()) {
    case 'anagrafica': ?>
        <p>
            In questa sezione puoi modificare i tuoi dati personali.
        </p>
        <ul>
            <li>
                Il tuo <strong>indirizzo</strong> di residenza.
            </li>
            <li>
                Il tuo indirizzo <strong>email</strong>.
            </li>
            <li>
                La tua <strong>password</strong>
            </li>
        </ul>
        <?php break; ?>

    <?php case 'viaggi': ?>
        <p>
            In questa sezione puoi visualizzare i viaggi disponibili.
	   		Per ogni viaggio vengono riportati:
        </p>
        <ul>
            <li>
                La nazione di destinazione del viaggio.
            </li>
            <li>
                La citt&agrave; di destinazione.
            </li>
            <li>
                La data di partenza.
            </li>
            <li>
                La data di rientro.
            </li>
            <li>
                Il numero di posti ancora disponibili.
            </li>
            <li>
                Il costo del viaggio.
            </li>
        </ul>
        <p>&Egrave; possibile prenotare un determinato viaggio 
            cliccando sul link <em>Prenota ora!</em> della riga corrispondente.

        </p>

        <?php break; ?>

    <?php case 'prenotazioni': ?>
        <p>
            In questa sezione puoi vedere quali viaggi hai prenotato. 
			Per ogni prenotazione sono riportati:
        </p>
        <ul>
            <li>
                La nazione di destinazione del viaggio.
            </li>
            <li>
                La citt&agrave; di destinazione.
            </li>
            <li>
                La data di partenza.
            </li>
            <li>
                La data di rientro.
            </li>
            <li>
                La data della prenotazione.
            </li>
        </ul>
        <p>&Egrave; possibile cancellare la prenotazione di un determinato viaggio 
            cliccando sul link <em>Elimina</em> della riga corrispondente.

        </p>
        <?php break; ?>
        <?php  default:
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
                <strong>Viaggi</strong> per visualizzare e prenotare i viagggi disponibili.
            </li>
            <li>
                <strong>Prenotazioni</strong> per visualizzare i viaggi gi&agrave;
                prenotati.
            </li>
        </ol>
        <?php break; ?>
<?php } ?>
