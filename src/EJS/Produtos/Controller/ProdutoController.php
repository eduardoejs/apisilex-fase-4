<?php

namespace EJS\Produtos\Controller;

use EJS\Produtos\Entity\Produto;
use EJS\Produtos\Mapper\ProdutoMapper;
use EJS\Produtos\Service\ProdutoService;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


class ProdutoController implements ControllerProviderInterface {

    private $produto;

    public function connect(Application $app)
    {
        $produtoController = $app['controllers_factory'];

        //APIs Publicas
        //API para listar todos os produtos
        $app->get("/api/produtos", function() use($app){
           $dados = $app['produtoService']->listProdutos();
            return $app->json($dados);
        });

        //API para listar 1 registro apenas
        $app->get("/api/produtos/{id}", function($id) use($app){
           $dados = $app['produtoService']->listProdutoById($id);
           if($dados){
               return $app->json($dados);
           }else{
               return $app->json(['ERRO' => 'Não foi possível exibir o registro']);
           }
        });

        //API para inserir novo registro
        $app->post("/api/produtos", function(Request $request) use($app){
            $dados['nome'] = $request->get('nome');
            $dados['descricao'] = $request->get('descricao');
            $dados['valor'] = $request->get('valor');

            if($app['produtoService']->insertProduto($dados)){
                return $app->json(['SUCCESSO' => 'Registro cadastrado com sucesso']);
            }else{
                return $app->json(['ERRO' => 'Não foi possível cadastrar o registro']);
            }
        });

        //API para alterar um registro
        $app->put("/api/produtos/{id}", function($id, Request $request) use($app){

            $data['id'] =  $id;
            $data['nome'] = $request->request->get('nome');
            $data['descricao'] = $request->request->get('descricao');
            $data['valor'] = $request->request->get('valor');

            $dados = $app['produtoService']->listProdutoById($id);

            if($dados){
                if($app['produtoService']->alterarProduto($data)){
                    return $app->json(['SUCCESSO' => 'Registro alterado com sucesso']);
                }else{
                    return $app->json(['ERRO' => 'Não foi possível alterar o registro']);
                }
            }else{
                return $app->json(['ERRO' => 'Registro não encontrado']);
            }
        });

        //API para remover um registro
        $app->delete("/api/produtos/{id}", function($id) use($app){

            $dados = $app['produtoService']->listProdutoById($id);

            if($dados){
                if($app['produtoService']->deleteProduto($id)){
                    return $app->json(['SUCCESSO' => 'Registro excluído com sucesso']);
                }else{
                    return $app->json(['ERRO' => 'Não foi possível excluir o registro']);
                }
            }else{
                return $app->json(['ERRO' => 'Registro não encontrado']);
            }
        });

        //fim APIs

        return $produtoController;
    }
} 