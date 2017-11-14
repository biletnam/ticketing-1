<?php
    $cod = "ticketManager-{$ticket->cod_ticket}";
?>
<tr class="collapsed" role="button" data-toggle="collapse" data-target="#<?= $cod ?>" data-ticket="<?= $ticket->cod_ticket ?>">
    <td>
        <abbr class="glyphicon glyphicon-record" style="color: #<?= $ticket->priority['color'] ?>" title="Prioridae: <?= $ticket->priority['name'] ?>"></abbr>
    </td>
    <td data-name="name"><?= $ticket->name ?></td>
    <td data-name="user"><?= $ticket->user['full_name'] ?></td>
    <td data-name="deadline"><?= $ticket->deadline->i18nFormat(\IntlDateFormatter::SHORT) ?></td>
    <td class="last col-xs-1">
        <abbr class="glyphicon glyphicon-pencil" title="Editar" data-action="edit-ticket"></abbr>
        <abbr class="glyphicon glyphicon-trash" title="Excluir" data-action="delete-ticket"></abbr>
    </td>
</tr>
<tr id="<?= $cod ?>" class="collapse">
    <td class="col-xs-12" colspan="5">
        <dl>
            <dt>Criador:</dt>
            <dl data-name="author"><?= $ticket->author['full_name'] ?></dl>
            <dt>Data de criação:</dt>
            <dl><?= $ticket->creation_date->i18nFormat(\IntlDateFormatter::FULL) ?></dl>
            <dt data-name="description">Deescrição:</dt>
            <dl><?= $ticket['description'] ?></dl>
        </dl>
    </td>
</tr>
