<?php
    use Cake\ORM\TableRegistry;
?>
<form name="ticket-form" class="form hidden" method="post" action="/tickets" data-form="tickets">
    <div class="form-group">
        <label for="name">Título</label>
        <input class="form-control" type="text" name="name">
    </div>

    <div class="form-group">
        <label for="cod_user">Usuário</label>
        <?=
            $this->Form->select(
                'cod_user',
                TableRegistry::get('Users')
                    ->find('list', [
                        'keyField' => 'cod_user',
                        'valueField' => 'full_name'
                    ])
                    ->order('Users.full_name'),
                [
                    'class' => 'form-control',
                    'default' => $Auth->user('cod_user')
                ]
            );
        ?>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="cod_priority">Prioridade</label>
            <?=
                $this->Form->select(
                    'cod_priority',
                    TableRegistry::get('Priorities')
                        ->find('list', [
                            'keyField' => 'cod_priority',
                            'valueField' => 'name'
                        ])
                        ->order('Priorities.cod_priority'),
                    [
                        'class' => 'form-control',
                        'default' => $Auth->user('cod_user')
                    ]
                );
            ?>
        </div>
        <div class="form-group col-sm-6">
            <label for="status">Situação</label>
            <?=
                $this->Form->select(
                    'cod_status',
                    TableRegistry::get('Status')
                        ->find('list', [
                            'keyField' => 'cod_status',
                            'valueField' => 'name',
                            'where' => [
                                'cod_status !=' => 0
                            ]
                        ]),
                    [
                        'class' => 'form-control'
                    ]
                );
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cod_priority">Data de Entrega</label>
        <input class="form-control" type="text" data-type="datetime" name="deadline">
    </div>
    <div class="form-group">
        <label for="name">Descrição</label>
        <textarea class="form-control" name="description"></textarea>
    </div>
    <div class="form-group text-center">
        <button class="btn btn-primary" type="submit">
            Salvar
        </button>
    </div>
</form>
