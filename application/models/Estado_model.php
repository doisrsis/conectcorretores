<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Estado Model
 * 
 * Gerencia operações relacionadas aos estados brasileiros
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Estado_model extends CI_Model
{
    /**
     * Tabela principal
     */
    private $table = 'estados';

    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buscar todos os estados
     * 
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('id, uf, nome')
            ->from($this->table)
            ->order_by('nome', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Buscar estado por UF
     * 
     * @param string $uf
     * @return object|null
     */
    public function get_by_uf($uf)
    {
        return $this->db
            ->select('id, uf, nome')
            ->from($this->table)
            ->where('uf', strtoupper($uf))
            ->get()
            ->row();
    }

    /**
     * Buscar estado por ID
     * 
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id)
    {
        return $this->db
            ->select('id, uf, nome')
            ->from($this->table)
            ->where('id', $id)
            ->get()
            ->row();
    }

    /**
     * Buscar ID do estado pela UF
     * 
     * @param string $uf
     * @return int|null
     */
    public function get_id_by_uf($uf)
    {
        $estado = $this->get_by_uf($uf);
        return $estado ? $estado->id : null;
    }
}
