Feature: Funcionalidades do PedidoController

    Scenario: Obter lista de pedidos com pedidos existentes
        Given que existem pedidos registrados no sistema
        When eu chamar a função obterPedidos
        Then eu devo receber uma lista de todos os pedidos existentes

    Scenario: Obter lista de pedidos sem pedidos existentes
        Given que não existem pedidos registrados no sistema
        When eu chamar a função obterPedidos e não tiver pedidos no sistema
        Then eu devo receber uma lista de pedidos vazia

    Scenario: Atualizar status de pedido existente
        Given que existe um ID de pedido válido e um novo status
        When eu chamar a função atualizarStatusPedido
        Then eu devo receber uma resposta indicando que o status do pedido foi atualizado com sucesso

    Scenario: Atualizar status de pedido inexistente
        Given que existe um ID de pedido inválido e um novo status
        When eu chamar a função atualizarStatusPedido com o id de pedido inválido
        Then eu devo receber uma resposta indicando que o pedido não foi encontrado
