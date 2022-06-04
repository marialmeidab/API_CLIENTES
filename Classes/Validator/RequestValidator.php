<?php

namespace Validator;

use Repository\TokensAutorizadosRepository;
use Service\ClientesService;
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;

class RequestValidator
{
    private $request;
    private array $dadosRequest = [];
    private object $tokensAutorizadosRepository;

    const GET = 'GET';
    const DELETE = 'DELETE';
    const CLIENTES = 'CLIENTES';

    public function __construct($request)
    {
        $this->request = $request;
        $this->tokensAutorizadosRepository = new TokensAutorizadosRepository();
    }

    /**
     * @return string
     */
    public function processarRequest()
    {
        $retorno= utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

        //$this->request['metodo'] = 'POST';
        if(in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true))
        {
            $retorno = $this->direcionarRequest();
        }

        return $retorno;
    }

    private function direcionarRequest()
    {
        if($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE )
        {
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }
        $this->tokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);
        $metodo = $this->request['metodo'];
        return $this->$metodo();
    }

    private function get()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET, strict)){
            switch ($this->request['rota']){
                case self::CLIENTES:
                    $ClientesService = new ClientesService($this->request);
                    $retorno = $ClientesService->validarGet();
                    break;
                    default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }
        return $retorno;
    }

    private function delete()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE, true)) {
            switch ($this->request['rota']) {
                case self::CLIENTES:
                    $UsuariosService = new ClientesService($this->request);
                    $retorno = $UsuariosService->validarDelete();
                    break;
                default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }
        return $retorno;
    }

    private function post()
    {
        $retorno = null;
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST, true)) {
            switch ($this->request['rota']) {
                case self::CLIENTES:
                    $ClientesService = new ClientesService($this->request);
                    $ClientesService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $ClientesService->validarPost();
                    break;
                default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            }
            return $retorno;
        }
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
    }

    private function put()
    {
        $retorno = null;
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT, true)) {
            switch ($this->request['rota']) {
                case self::CLIENTES:
                    $ClientesService = new ClientesService($this->request);
                    $ClientesService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $ClientesService->validarPut();
                    break;
                default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            }
            return $retorno;
        }
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
    }
}