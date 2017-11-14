<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;

    class PriorityTable extends Table
    {
        public function initialize(array $config)
        {
            $this->table('priorities');
            $this->setPrimaryKey('cod_priority');
            parent::initialize($config);
        }
    }
