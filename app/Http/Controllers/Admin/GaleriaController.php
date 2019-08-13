<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Galeria;
use App\Imovel;

class GaleriaController extends Controller
{
    public function index($id)
    {
        $imovel = Imovel::find($id);
        $registros = $imovel->galeria()->orderBy('ordem')->get();
        return view('admin.galerias.index', compact('registros', 'imovel'));
    }

    public function adicionar($id)
    {
        $imovel = Imovel::find($id);
        return view('admin.galerias.adicionar', compact('imovel'));
    }

    public function salvar(Request $request, $id)
    {
        $imovel = Imovel::find($id);
        $dados = $request->all();
        if($imovel->galeria()->count()){
            $galeria = $imovel->galeria()->orderBy('order', 'desc')->first();
            $ordemAtual = $galeria->order;
        }else{
            $ordemAtual = 0;
        }
        if($request->hasFile('imagens')){
            $arquivos = $request->file('imagens');
            foreach($arquivos as $imagem){
                $registro = new Galeria();
                $rand = rand(11111, 99999);
                $diretorio = "img/imoveis/".str_slug($imovel->titulo, '_')."/";
                $ext = $imagem->guessClientExtension();
                $nomeArquivo = "_img_".$rand.".".$ext;
                $imagem->move($diretorio, $nomeArquivo);
                $registro->imovel_id = $imovel->id;
                $registro->order = $ordemAtual + 1;
                $ordemAtual++;
                $registro->imagem = $diretorio.'/'.$nomeArquivo;
                $registro->save();
            }
        }
        \Session::flash('mensagem', ['msg' => 'Imagens cadastradas com sucesso!', 'class' => 'light-green white-text']);
        return redirect()->route('admin.galerias', $imovel->id);
    }

    public function editar($id)
    {
        $registro = Imovel::find($id);
        $tipos = Tipo::all();
        $cidades = Cidade::all();
        return view('admin.imoveis.editar', compact('registro', 'tipos', 'cidades'));
    }

    public function atualizar(Request $request, $id)
    {
        $registro = Imovel::find($id);
        $dados = $request->all();
        $registro->titulo = $dados['titulo'];
        $registro->descricao = $dados['descricao'];
        $registro->status = $dados['status'];
        $registro->endereco = $dados['endereco'];
        $registro->cep = $dados['cep'];
        $registro->valor = $dados['valor'];
        $registro->dormitorios = $dados['dormitorios'];
        $registro->detalhes = $dados['detalhes'];
        $registro->publicar = $dados['publicar'];
        if(isset($dados['mapa']) && trim($dados['mapa']) != ""){
            $registro->mapa = trim($dados['mapa']);
        }else{
            $registro->mapa = null;
        }
        $registro->cidade_id = $dados['cidade_id'];
        $registro->tipo_id = $dados['tipo_id'];
        $file = $request->file('imagem');
        if($file){
            $rand = $rand(11111, 99999);
            $diretorio = "img/imoveis/".str_slug($dados['titulo'], '-')."/";
            $ext = $file->guessClientExtension();
            $nomeArquivo = "_img_".$rand.".".$ext;
            $file->move($diretorio, $nomeArquivo);
            $registro->imagem = $diretorio.'/'.$nomeArquivo;
        }
        $registro->update();
        \Session::flash('mensagem', ['msg' => 'Im�vel atualizado com sucesso!', 'class' => 'light-green white-text']);
        return redirect()->route('admin.imoveis');
    }

    public function deletar($id)
    {
        Imovel::find($id)->delete();
        \Session::flash('mensagem', ['msg' => 'Im�vel removido com sucesso!', 'class' => 'light-green white-text']);
        return redirect()->route('admin.imoveis');
    }
}
