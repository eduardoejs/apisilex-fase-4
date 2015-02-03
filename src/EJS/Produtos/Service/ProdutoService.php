<?php

namespace EJS\Produtos\Service;

use EJS\Database\Conexao;
use EJS\Produtos\Entity\Produto;
use EJS\Produtos\Mapper\ProdutoMapper;

class ProdutoService {

    private $produto;
    private $produtoMapper;
    private $conexao;

    function __construct(Produto $produto, ProdutoMapper $produtoMapper, Conexao $conexao) {
        $this->produto = $produto;
        $this->produtoMapper = $produtoMapper;
        $this->conexao = $conexao;
    }

    public function listProdutos()
    {
        $produtoMapper = $this->produtoMapper;
        $result = $produtoMapper->listProdutos($this->conexao);

        return $result;
    }

    public function listProdutoById($id){
        return $this->produtoMapper->listProdutoById($id, $this->conexao);
    }

    public function insertProduto($data){
        $this->produto->setNome($data['nome'])
                      ->setDescricao($data['descricao'])
                      ->setValor($data['valor']);
        if(empty($data['nome']) or empty($data['descricao']) or empty($data['valor'])){
            return ["STATUS" => "Erro: Você deve informar todos os valores"];
        }elseif(!is_numeric($data['valor'])){
            return ["STATUS" => "O formato do campo Valor está incorreto. (Não use vírgula)"];
        }
        else{
            if($this->produtoMapper->insertProduto($this->produto, $this->conexao)){
                return ["STATUS" => "Registro cadastrado com sucesso"];
            }
        }
    }

    public function alterarProduto($data){
        $this->produto->setId($data['id'])
                      ->setNome($data['nome'])
                      ->setDescricao($data['descricao'])
                      ->setValor($data['valor']);

        if(empty($data['nome']) or empty($data['descricao']) or empty($data['valor'])){
            return ["STATUS" => "Erro: Você deve informar todos os valores"];
        }elseif(!is_numeric($data['valor'])){
            return ["STATUS" => "O formato do campo Valor está incorreto. (Não use vírgula)"];
        }
        else{
            if($this->produtoMapper->updateProduto($this->produto, $this->conexao)){
                return ["STATUS" => "Registro alterado com sucesso"];
            }
        }
    }

    public function deleteProduto($data)
    {
        $this->produto->setId($data);
        return $this->produtoMapper->deleteProduto($this->produto, $this->conexao);
    }
}