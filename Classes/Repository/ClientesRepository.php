<?php

namespace Repository;

use DB\MySQL;

class ClientesRepository
{
    private object $MySQL;
    public const TABELA = "clientes";

    /**
     * ClientesRepository constructor
     */
    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param $nome
     * @return int
     */
    public function getRegistroByNome($nome)
    {
        $consulta = 'SELECT * FROM ' . self::TABELA . ' WHERE nome = :nome';
        $stmt = $this->MySQL->getDb()->prepare($consulta);
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $nome
     * @param $telefone
     * @param $cpf
     * @param $placa
     * @return int
     */
    public function insertCliente($nome, $telefone, $cpf, $placa)
    {
        $consultaInsert = 'INSERT INTO ' . self::TABELA . ' (nome, telefone, cpf, placa) 
        VALUES (:nome, :telefone, :cpf, :placa)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':placa', $placa);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $id
     * @param $dados
     * @return int
     */
    public function updateCliente($id, $dados)
    {
        $consultaUpdate = 'UPDATE ' . self::TABELA . ' 
        SET nome = :nome, telefone = :telefone, cpf = :cpf, placa = :placa WHERE id = :id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':telefone', $dados['telefone']);
        $stmt->bindValue(':cpf', $dados['cpf']);
        $stmt->bindValue(':placa', $dados['placa']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @return MySQL|object
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }
}