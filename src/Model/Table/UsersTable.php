<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(array $config)
        {
            $this->table('users');
            $this->setPrimaryKey('cod_user');
            $this->entityClass('App\Model\Entity\User');
        }
    }
