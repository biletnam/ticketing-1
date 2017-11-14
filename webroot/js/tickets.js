var App = {};

// Mensagens gerais do sistema
App.messages= {
    200: 'Feito!',
    404: 'Ops!<br> O que você procura não está aqui!',
    403: 'Acesso Negado!',
    406: 'Dados inválidos!',
    401: 'Você não pode fazer isso!',
    400: 'Entrenovamente no sistema',
    500: 'Ops!<br>Estamos trabalhando para corrigir este erro.'
};
/*
    Utilitário - Le os dados do formuário
    $form DOM object
    return DataObject
*/
App.utils = {
    formData : function($form){
        var paramObj = {};
        $.each($form.serializeArray(), function(_, kv) {
          paramObj[kv.name] = kv.value;
        });

        return paramObj;
    }
};

// Alertas gerais
App.alerts = {
    400: {
        msg: App.messages[400],
        onClose: function(){
            window.location.href = '/login?redirect_uri='+(window.location.pathname||'/')
        }
    }
};

// Widget dos Tickets
App.tickets = function($e) {
    this.uri = '/tickets/';

    this.$elem = $($e);

    this.templates = {
        ticket: this.$elem.find('[data-template=ticket]'),
    };

    this.addTicket = function(data){
        var requestObject = $.ajax({
            method: 'POST',
            url: this.uri,
            data: data
        });

        return requestObject;
    };

    this.editTicket = function(cod, data){
        var requestObject = $.ajax({
            method: 'PUT',
            url: this.uri+cod,
            data: data
        });

        return requestObject;
    };

    this.getTicket = function(cod){
        var requestObject = $.ajax({
            width: 768,
            url: this.uri+cod,
            dataType: 'json'
        });

        return requestObject;
    };

    this.ticketForm = function(data){
        this.$form.jModal({
            onShow: function(data){
                var $form = $(this);

                // Preenche o formulário de edição
                if(data)
                    data.each(function(i, val){
                        $form.find('[name='+i+']').val(val);
                    });
            }
        });
    };

    // Adiciona um elemento à lista
    this.addElement = function(data){
        data.cod = 'lista-tickets-'+data.cod_ticket;

        this.$elem.find('[data-render=list]')
            .prepend(
                this.templates.ticket.clone()
                    .find('[data-name]')
                        // Peenche os dados pra visualização
                        .each(function(i, val){
                            val = $(val);

                            val.text(data[val.data('name')] || '');
                        })
                            .filter('[data-name=priority]')
                                .css('color', '#'+data.priority.color)
                                .text('')
                                .end()
                            .filter('[data-name=author]')
                                .text(data.author.full_name)
                                .end()
                            .filter('[data-name=user]')
                                .text(data.user.full_name)
                                .end()
                        .end()
                    // Efeito mostrar mais
                    .find('tr:eq(0)')
                        .attr('data-target', data.cod)
                        .end()
                    .find('tr:eq(1)')
                        .attr('id', data.cod)
                        .end()
                    .find('tbody')
                        .html()
            );
    };

    /*
        Exclui um ticket
        cod int código do ticket
        return xhrObject
    */
    this.deleteTicket = function(cod) {
        var reqObject =
            $.ajax({
                url: this.uri+cod,
                method: 'DELETE'
            });

        return reqObject;
    };

    return this;
};

// Validação Simples - > Apenas Demonstração
$.fn.validator = function(){
    var $form = $(this);

    $form
        .find('.form-control')
            .each(function(i, val){
                val = $(val);
                if($.trim(val.val()))
                    val.closest('.form-group')
                        .removeClass('has-error');
                else
                    val.closest('.form-group')
                        .addClass('has-error');
            });

    return !$form.find('.has-error').size();
};

$.fn.validate

$(function(){
    var $tickets = new App.tickets($('[data-widget=tickets]'));

    $tickets
        .$elem
        // Excluir
        .on('click', '[data-action=delete-ticket]', function(){
            // Seleciona o ticket
            var $ticket = $(this).closest('[data-ticket]');

            jModal({
                msg: 'Deseja excluir o ticket "'+ $ticket.find('[data-name=name]').text()+'"?',
                onConfirm: function(){
                    $tickets
                        .deleteTicket($ticket.data('ticket'))
                            .done(function(){
                                // Exibe a mensagen de sucesso
                                jModal(App.messages[200]);
                                // Remove os detalhes e o ticket
                                $ticket.next().remove();
                                $ticket.remove();
                            })
                            .fail(function(xhr, status, errorThrown){
                                // Exibe a mensagem de erro
                                jModal(App.alerts[xhr.status] || App.messages[xhr.status] || App.alerts[500]);
                            });
                },
                onCancel: function(){

                }
            });

        })
        // Edit Ticket
        .on('click', '[data-action=edit-ticket]', function(){
            var
                $ticket = $(this).closest('[data-ticket]');

            $tickets.getTicket($ticket.data('ticket'))
                .done(function(data){
                    var tJ = $tickets.$form.jModal({
                        width: 768,
                        onShow: function(){
                            var
                                $f = $(this).find('form')
                                    .submit(function(){
                                        if(!$f.validator()){
                                            jModal({
                                                msg: 'Dados inválidos',
                                                onClose: function(){
                                                    $f.find('.error :input').focus();
                                                }
                                            });
                                        };

                                        $tickets.editTicket($ticket.data('ticket'), App.utils.formData($f))
                                            .done(function(data){
                                                jModal({
                                                    msg: App.messages[200],
                                                    onClose: function(){
                                                        window.location.reload();
                                                    }
                                                });
                                            })
                                            .fail(function(xhr, status, errorThrown){
                                                jModal(App.alerts[xhr.status] || App.messages[xhr.status] || App.alerts[500]);
                                            });

                                        return false;
                                    })
                                    .find('[name]')
                                        .each(function(i, val){
                                            val = $(val);

                                            val.val(data[val.attr('name')] || '');
                                        })
                                        .end();
                        }
                    });
                })
                .fail(function(xhr, status, errorThrown){
                    jModal(App.alerts[xhr.status] || App.messages[xhr.status] || App.alerts[500]);
                });
        })
        // Criar novo
        .on('click', '[data-action=new-ticket]', function(){
            var
                $ticket = $(this).closest('[data-ticket]'),
                $tJ = $tickets.$form.jModal({
                    width: 768,
                    onShow: function(){
                        var
                            $f = $(this).find('form')
                                .submit(function(){
                                    // Valida
                                    if(!$f.validator()){
                                        jModal({
                                            msg: 'Dados inválidos',
                                            onClose: function(){
                                                $f.find('.error :input').focus();
                                            }
                                        });

                                        return false;
                                    };

                                    // Salva
                                    $tickets.addTicket(App.utils.formData($f))
                                        // Adiciona à lista
                                        .done(function(data){
                                            jModal(jModal(App.messages[200]));
                                            $tickets.addElement(data);
                                            $tJ.close();
                                        })
                                        // Exibe a mensagem de erro
                                        .fail(function(xhr, status, errorThrown){
                                            jModal(App.alerts[xhr.status] || App.messages[xhr.status] || App.alerts[500]);
                                        });

                                    return false;
                                });
                    }
                });
        });


    $tickets.$form = $('[data-form=tickets]');
});
