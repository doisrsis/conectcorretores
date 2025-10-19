<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cidade Model
 * 
 * Gerencia operações relacionadas às cidades brasileiras
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Cidade_model extends CI_Model
{
    /**
     * Tabela principal
     */
    private $table = 'cidades';

    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buscar cidades por estado
     * 
     * @param int $estado_id
     * @return array
     */
    public function get_by_estado($estado_id)
    {
        return $this->db
            ->select('id, nome')
            ->from($this->table)
            ->where('estado_id', $estado_id)
            ->order_by('nome', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Buscar cidade por ID
     * 
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id)
    {
        return $this->db
            ->select('c.id, c.nome, c.estado_id, e.uf, e.nome as estado_nome')
            ->from($this->table . ' c')
            ->join('estados e', 'c.estado_id = e.id')
            ->where('c.id', $id)
            ->get()
            ->row();
    }

    /**
     * Buscar ou criar cidade
     * 
     * @param int $estado_id
     * @param string $nome
     * @param string|null $ibge_code
     * @return int ID da cidade
     */
    public function get_or_create($estado_id, $nome, $ibge_code = null)
    {
        // Buscar cidade existente
        $cidade = $this->db
            ->select('id')
            ->from($this->table)
            ->where('estado_id', $estado_id)
            ->where('nome', $nome)
            ->get()
            ->row();

        if ($cidade) {
            return $cidade->id;
        }

        // Criar nova cidade
        $data = [
            'estado_id' => $estado_id,
            'nome' => $nome,
            'ibge_code' => $ibge_code
        ];

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Verificar se cidade existe
     * 
     * @param int $estado_id
     * @param string $nome
     * @return bool
     */
    public function exists($estado_id, $nome)
    {
        $count = $this->db
            ->from($this->table)
            ->where('estado_id', $estado_id)
            ->where('nome', $nome)
            ->count_all_results();

        return $count > 0;
    }

    /**
     * Contar cidades por estado
     * 
     * @param int $estado_id
     * @return int
     */
    public function count_by_estado($estado_id)
    {
        return $this->db
            ->from($this->table)
            ->where('estado_id', $estado_id)
            ->count_all_results();
    }
}
