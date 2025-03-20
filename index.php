<?php

class Produto {
    private string $nome;
    private float $preco;
    private int $quantidade;

    public function __construct(string $nome, float $preco, int $quantidade) {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->quantidade = $quantidade;
    }

    public function exibirInformacoes(): void {
        echo "Produto: {$this->nome}, Preço: R$ " . number_format($this->preco, 2, ',', '.') . ", Quantidade: {$this->quantidade}\n";
    }

    public function aplicarDesconto(float $percentual): void {
        if ($percentual > 0 && $percentual <= 100) {
            $this->preco -= ($this->preco * ($percentual / 100));
            echo "Desconto de {$percentual}% aplicado. Novo preço: R$ " . number_format($this->preco, 2, ',', '.') . "\n";
        }
    }

    public function atualizarQuantidade(int $quantidade): void {
        if ($quantidade >= 0) {
            $this->quantidade = $quantidade;
            echo "Quantidade atualizada para {$this->quantidade}\n";
        }
    }

    public function getPreco(): float {
        return $this->preco;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }
}

class Estoque {
    private array $produtos = [];

    public function adicionarProduto(Produto $produto): void {
        $this->produtos[] = $produto;
    }

    public function listarProdutos(): void {
        foreach ($this->produtos as $produto) {
            $produto->exibirInformacoes();
        }
    }

    public function calcularValorTotal(): float {
        $total = 0;
        foreach ($this->produtos as $produto) {
            $total += $produto->getPreco() * $produto->getQuantidade();
        }
        return $total;
    }
}

$estoque = new Estoque();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0;
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 0;

    if (!empty($nome) && $preco > 0 && $quantidade > 0) {
        $produto = new Produto($nome, $preco, $quantidade);
        $estoque->adicionarProduto($produto);
        echo "Produto adicionado com sucesso!<br>";
    } else {
        echo "Preencha todos os campos corretamente!<br>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
</head>
<body>
    <h2>Adicionar Produto</h2>
    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        
        <label for="preco">Preço:</label>
        <input type="number" step="0.01" name="preco" required><br>
        
        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" required><br>
        
        <button type="submit">Adicionar Produto</button>
    </form>

    <h2>Lista de Produtos</h2>
    <?php $estoque->listarProdutos(); ?>

    <h3>Valor total do estoque: R$ <?php echo number_format($estoque->calcularValorTotal(), 2, ',', '.'); ?></h3>
</body>
</html>
