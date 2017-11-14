<?php
    namespace App\Controller;

    use Cake\Event\Event;
    use App\Controller\AppController;
    use Cake\Network\Exception\UnauthorizedException;
    use Cake\Network\Exception\NotAcceptableException;

    /**
     * Controller para Tarefas
     */
    class TicketsController extends AppController
    {
        public function initialize()
        {
            parent::initialize();

            $this->loadModel('Tickets');
        }
        public function beforeFilter(Event $event)
        {
            $this->Auth->allow();

            if(!$this->Auth->user())
                throw new UnauthorizedException('Entre no sistema');
        }
        // Exclui o ticket
        public function delete($cod)
        {
            // Seleciona o ticket
            $ticket = $this->Tickets->get($cod);

            // Verifica a propriedade
            if($ticket->cod_user != $this->Auth->user('cod_user'))
                throw new UnauthorizedException('Não pode alterar este ticket');

            // Modifica o status
            $ticket->cod_status = 0;
            $this->Tickets->save($ticket);

            // Resposta vazia
            $this->response->statusCode(204);
        }

        public function put($cod)
        {
            // Seleciona o ticket
            $ticket = $this->Tickets->get($cod);

            // Verifica a propriedade
            if($ticket->cod_user != $this->Auth->user('cod_user'))
                throw new UnauthorizedException('Não pode alterar este ticket');

            // Lê os dados
            $this->Tickets->patchEntity($ticket, $this->request->data);

            $this->Tickets->save($ticket);

            // Verifica se tem erros
            if($ticket->errors())
                throw new NotAcceptableException('Verifique os dados');

            $this->set($this->Tickets->getItem($ticket->cod_ticket, $this->Auth)->toArray());
        }

        public function post()
        {
            $ticket = $this->Tickets->newEntity($this->request->data);
            // Define o usuário Atual como o criador
            $ticket->cod_creator = $this->Auth->user('cod_user');

            $this->Tickets->save($ticket);

            if($ticket->errors())
                throw new NotAcceptableException('Verifique os dados');

            $this->set($this->Tickets->getItem($ticket->cod_ticket, $this->Auth)->toArray());
        }

        public function getItem($cod)
        {
            $this->set($this->Tickets->getItem($cod, $this->Auth)->toArray());
        }
    }
