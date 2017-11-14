<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    use Cake\ORM\Query;
    use Cake\Controller\Component\AuthComponent;

    class TicketsTable extends Table
    {
        public function initialize(array $config)
        {
            $this->table('tickets');
            $this->setPrimaryKey('cod_ticket');

            // Percente a um autor
            $this->belongsTo('Author', [
                'className' => 'Users',
                'foreignKey' => 'cod_creator',
                'propertyName' => 'author'
            ]);

            $this->belongsTo('Owner', [
                'className' => 'Users',
                'foreignKey' => 'cod_user',
                'propertyName' => 'user'
            ]);

            // "Percente" a um status
            $this->belongsTo('Status', [
                'className' => 'Status',
                'foreignKey' => 'cod_status',
                'propertyName' => 'status'
            ]);

            // "Percente" a um status
            $this->belongsTo('Priority', [
                'className' => 'Priority',
                'foreignKey' => 'cod_priority',
                'propertyName' => 'priority'
            ]);
        }

        public function getItem(int $cod, AuthComponent $Auth)
        {
            $alias = $this->alias();

            return
                $this->find()
                    ->contain(['Author', 'Priority', 'Owner'])
                    ->where([
                        "{$alias}.{$this->primaryKey()}" => $cod,
                        "{$alias}.cod_status !=" => 0,
                        'OR' => [
                            "{$alias}.cod_user" => $Auth->user('cod_user'),
                            "{$alias}.cod_creator" => $Auth->user('cod_user'),
                        ]
                    ])
                    ->firstOrFail();
        }

        public function getList(AuthComponent $Auth) : Query
        {
            $alias = $this->alias();

            return
                $this->find()
                    ->contain(['Author', 'Priority', 'Owner'])
                    ->where(["{$alias}.cod_status !=" => 0])
                    ->andWhere([
                        'OR' => [
                            "{$alias}.cod_user" => $Auth->user('cod_user'),
                            "{$alias}.cod_creator" => $Auth->user('cod_user')
                        ]
                    ])
                    ->order(["{$alias}.cod_priority" => 'DESC']);
        }
    }
