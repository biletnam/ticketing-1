<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;

    class StatusTable extends Table
    {
        public function initialize(array $config)
        {
            $this->table('status');
            $this->setPrimaryKey('cod_status');
            parent::initialize($config);
        }
    }
