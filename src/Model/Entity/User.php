<?php
    namespace App\Model\Entity;

    use Cake\ORM\Entity;

    class User extends Entity
    {
        protected $_hidden = ['social_id']; // Esconde os campos sigilosos
    }
