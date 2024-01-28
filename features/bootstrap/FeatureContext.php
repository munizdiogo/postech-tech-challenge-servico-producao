<?php

require "./src/External/MySqlConnection.php";
require "./src/Controllers/PedidoController.php";

use Producao\External\MySqlConnection;
use PHPUnit\Framework\TestCase;
use Behat\Behat\Context\Context;
use Producao\Controllers\PedidoController;

class FeatureContext extends TestCase implements Context
{
    private $resultado;
    private $pedidoController;
    private $exceptionMessage;
    private $exceptionCode;
    private $dadosPedido;
    private $idPedido;
    private $pedidos;
    private $novoStatusPedido;
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new MySqlConnection();
        $this->pedidoController = new PedidoController();
    }

    /**
     * @Given que existem pedidos registrados no sistema
     */
    public function queExistemPedidosRegistradosNoSistema()
    {
        $this->dadosPedido = '{
            "cpf": "42157363823",
            "produtos": [
                {
                    "id": 2,
                    "nome": "Produto 1",
                    "descricao": "Descrição do Produto 1",
                    "preco": 20.99,
                    "categoria": "lanche"
                },
                 {
                    "id": 3,
                    "nome": "Produto 2",
                    "descricao": "Descrição do Produto 2",
                    "preco": 15.99,
                    "categoria": "bebida"
                }
            ]
        }';
        $dadosPedido = json_decode($this->dadosPedido, true);
        $this->idPedido = $this->pedidoController->cadastrar($this->dbConnection, $dadosPedido);
        $this->assertIsInt($this->idPedido);
    }

    /**
     * @When eu chamar a função obterPedidos
     */
    public function euChamarAFuncaoObterpedidos()
    {
        $this->pedidos = $this->pedidoController->obterPedidos($this->dbConnection);
    }

    /**
     * @Then eu devo receber uma lista de todos os pedidos existentes
     */
    public function euDevoReceberUmaListaDeTodosOsPedidosExistentes()
    {
        $this->assertArrayHasKey("idPedido", $this->pedidos[0]);
        $pedidoExcluido = $this->pedidoController->excluir($this->dbConnection, $this->idPedido);
        $this->assertTrue($pedidoExcluido);
    }

    /**
     * @Then eu devo receber uma lista de pedidos vazia
     */
    public function euDevoReceberUmaListaDePedidosVazia()
    {
        $this->assertEquals([], $this->pedidos);
    }

    /**
     * @Given que existe um ID de pedido válido e um novo status
     */
    public function queExisteUmIdDePedidoValidoEUmNovoStatus()
    {
        $dadosPedido = '{
            "cpf": "42157363823",
            "produtos": [
                {
                    "id": 2,
                    "nome": "Produto 1",
                    "descricao": "Descrição do Produto 1",
                    "preco": 20.99,
                    "categoria": "lanche"
                },
                 {
                    "id": 3,
                    "nome": "Produto 2",
                    "descricao": "Descrição do Produto 2",
                    "preco": 15.99,
                    "categoria": "bebida"
                }
            ]
        }';

        $dadosValidos = json_decode($dadosPedido, true);
        $this->idPedido = $this->pedidoController->cadastrar($this->dbConnection, $dadosValidos);
        $this->assertIsInt($this->idPedido);
    }

    /**
     * @Then eu devo receber uma resposta indicando que o status do pedido foi atualizado com sucesso
     */
    public function euDevoReceberUmaRespostaIndicandoQueOStatusDoPedidoFoiAtualizadoComSucesso()
    {
        $this->assertTrue($this->resultado);
        $pedidoExcluido = $this->pedidoController->excluir($this->dbConnection, $this->idPedido);
        $this->assertTrue($pedidoExcluido);
    }

    /**
     * @Given que existe um ID de pedido inválido e um novo status
     */
    public function queExisteUmIdDePedidoInvalidoEUmNovoStatus()
    {
        $this->idPedido = 999999999999999999;
        $this->novoStatusPedido = "em_preparacao";
    }

    /**
     * @Given que não existem pedidos registrados no sistema
     */
    public function queNaoExistemPedidosRegistradosNoSistema()
    {
        $this->pedidos = [];
    }

    /**
     * @When eu chamar a função obterPedidos e não tiver pedidos no sistema
     */
    public function euChamarAFuncaoObterpedidosENaoTiverPedidosNoSistema()
    {
        $this->pedidos = [];
    }

    /**
     * @When eu chamar a função atualizarStatusPedido com o id de pedido inválido
     */
    public function euChamarAFuncaoAtualizarstatuspedidoComOIdDePedidoInvalido()
    {
        try {
            $this->pedidoController->atualizarStatusPedido($this->dbConnection, $this->idPedido, $this->novoStatusPedido);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then eu devo receber uma resposta indicando que o pedido não foi encontrado
     */
    public function euDevoReceberUmaRespostaIndicandoQueOPedidoNaoFoiEncontrado()
    {
        $this->assertEquals("Não foi encontrado um pedido com o ID informado.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @When eu chamar a função atualizarStatusPedido
     */
    public function euChamarAFuncaoAtualizarstatuspedido()
    {
        $novoStatus = "em_preparacao";
        $this->resultado = $this->pedidoController->atualizarStatusPedido($this->dbConnection, $this->idPedido, $novoStatus);
        $this->assertTrue($this->resultado);
    }
}
