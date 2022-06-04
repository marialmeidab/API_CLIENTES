<?php

namespace Service;

use Repository\ClientesRepository;
use Util\ConstantesGenericasUtil;

class ClientesService
{
    public const TABELA = 'clientes';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;
    private array $dadosCorpoRequest;

    /**
     * @var object|ClientesRepository
     */
    private object $ClientesRepository;

    /**
     * @param $dados
     */
    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->ClientesRepository = new ClientesRepository();
    }

    public function validarGet()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_GET, strict)){
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
        }else{
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_DELETE, true)) {
            if ($this->dados['id'] > 0) {
                $retorno = $this->$recurso();
            } else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_POST, true)) {
            $retorno = $this->$recurso();
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_PUT, true)) {
            if ($this->dados['id'] > 0) {
                $retorno = $this->$recurso();
            } else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    private function getOneByKey()
    {
        return $this->ClientesRepository->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    public function setDadosCorpoRequest($dadosCorpoRequest)
    {
        $this->dadosCorpoRequest = $dadosCorpoRequest;
    }

    private function listar()
    {
        return $this->ClientesRepository->getMySQL()->getAll(self::TABELA);
    }

    private function cadastrar()
    {
        [$nome, $telefone, $cpf, $placa] =
            [   $this->dadosCorpoRequest['nome'],
                $this->dadosCorpoRequest['telefone'],
                $this->dadosCorpoRequest['cpf'],
                $this->dadosCorpoRequest['placa']
            ];

        if ($nome && $telefone && $cpf && $placa) {
            if ($this->ClientesRepository->getRegistroByNome($nome) > 0) {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_EXISTENTE);
            }

            if ($this->ClientesRepository->insertCliente($nome, $telefone, $cpf, $placa) > 0) {
                $idInserido = $this->ClientesRepository->getMySQL()->getDb()->lastInsertId();
                $this->ClientesRepository->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            }

            $this->ClientesRepository->getMySQL()->getDb()->rollBack();

            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }

    private function atualizar()
    {
        if ($this->ClientesRepository->updateCliente($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->ClientesRepository->getMySQL()->getDb()->commit();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }
        $this->ClientesRepository->getMySQL()->getDb()->rollBack();
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
    }

    private function deletar()
    {
        return $this->ClientesRepository->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

}