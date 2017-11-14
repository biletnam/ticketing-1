<?php
    $this->Html->script('tickets', ['block' => true]);

    echo
        $this->Cell('Tickets::list', [$Auth, $query ?? '']).
        $this->Element('ticket_form');
