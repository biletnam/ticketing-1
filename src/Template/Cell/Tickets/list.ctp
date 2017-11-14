<div class="row">
    <div class="col-xs-12 table-responsive" data-widget="tickets" data-action="/taks" data-limit="1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Título</th>
                    <th>Responsável</th>
                    <th>Entrega</th>
                    <th class="last">
                        <abbr class="glyphicon glyphicon-plus" data-action="new-ticket" title="Novo"></abbr>
                    </th>
                </tr>
            </thead>
            <tbody data-render="list">
                <?=
                    $tickets->count() ?
                    join(
                        '',
                        array_map(
                            function($ticket){
                                return $this->Element('ticket', ['ticket' => $ticket]);
                            },
                            $tickets->toArray()
                        )
                    ):
                    '<tr><td class="text-danger" colspan="4">Nenhum resultado encontrado</td></tr>'
                ?>
            </tbody>
        </table>
        <table class="hidden" data-template="ticket">
            <tbody>
                <tr class="collapsed" role="button" data-toggle="collapse" data-target="" data-ticket="">
                    <td>
                        <abbr class="glyphicon glyphicon-record" data-name="priority"></abbr>
                    </td>
                    <td data-name="name"></td>
                    <td data-name="user"></td>
                    <td data-name="deadline"></td>
                    <td class="last col-xs-1">
                        <abbr class="glyphicon glyphicon-pencil" title="Editar" data-action="edit-ticket"></abbr>
                        <abbr class="glyphicon glyphicon-trash" title="Excluir" data-action="delete-ticket"></abbr>
                    </td>
                </tr>
                <tr class="collapse">
                    <td class="col-xs-12" colspan="5">
                        <dl>
                            <dt>Criador:</dt>
                            <dl data-name="author"></dl>
                            <dt>Data de criação:</dt>
                            <dl data-name="creation_date"></dl>
                            <dt>Deescrição:</dt>
                            <dl data-name="description"></dl>
                        </dl>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
