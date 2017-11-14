<?php
    namespace App\View\Cell;

    use Cake\View\Cell;
    use Cake\Controller\Component\AuthComponent;

    class TicketsCell extends Cell{
        public function list(AuthComponent $Auth, string $query = null)
        {
            $this->loadModel('Tickets');

            $list = $this->Tickets->getList($Auth);

            if($query)
                $list->andWhere([
                    'OR' => [
                        'Tickets.name LIKE' => "%{$query}%",
                        'Tickets.description LIKE' => "%{$query}%",
                        'Owner.full_name LIKE' => "%{$query}%",
                        'Author.full_name LIKE' => "%{$query}%"
                    ]
                ]);

            $this->set('tickets', $list);
        }
    }
