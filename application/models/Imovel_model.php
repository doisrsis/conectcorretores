<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Imóveis - ConectCorretores
 * 
 * Gerencia operações relacionadas aos imóveis cadastrados
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Imovel_model extends CI_Model {

    protected $table = 'imoveis';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Criar novo imóvel
     * 
     * @param array $data Dados do imóvel
     * @return int|bool ID do imóvel criado ou false
     */
    public function create($data) {
        // Calcular valor por m² se não fornecido
        if (!isset($data['valor_m2']) && isset($data['preco']) && isset($data['area_privativa'])) {
            if ($data['area_privativa'] > 0) {
                $data['valor_m2'] = $data['preco'] / $data['area_privativa'];
            }
        }

        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Buscar imóvel por ID
     * 
     * @param int $id ID do imóvel
     * @param int $user_id ID do usuário (opcional, para verificar permissão)
     * @return object|null Dados do imóvel
     */
    public function get_by_id($id, $user_id = null) {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email, users.telefone as corretor_telefone');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');
        $this->db->where('imoveis.id', $id);

        if ($user_id) {
            $this->db->where('imoveis.user_id', $user_id);
        }

        return $this->db->get()->row();
    }

    /**
     * Listar imóveis
     * 
     * @param array $filters Filtros opcionais
     * @param int $limit Limite de resultados
     * @param int $offset Offset para paginação
     * @return array Lista de imóveis
     */
    public function get_all($filters = [], $limit = null, $offset = 0) {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');

        // Filtro por usuário
        if (isset($filters['user_id'])) {
            $this->db->where('imoveis.user_id', $filters['user_id']);
        }

        // Filtro por tipo de negócio
        if (isset($filters['tipo_negocio'])) {
            $this->db->where('imoveis.tipo_negocio', $filters['tipo_negocio']);
        }

        // Filtro por tipo de imóvel
        if (isset($filters['tipo_imovel'])) {
            $this->db->where('imoveis.tipo_imovel', $filters['tipo_imovel']);
        }

        // Filtro por estado
        if (isset($filters['estado'])) {
            $this->db->where('imoveis.estado', $filters['estado']);
        }

        // Filtro por cidade
        if (isset($filters['cidade'])) {
            $this->db->where('imoveis.cidade', $filters['cidade']);
        }

        // Filtro por bairro
        if (isset($filters['bairro'])) {
            $this->db->like('imoveis.bairro', $filters['bairro']);
        }

        // Filtro por quartos
        if (isset($filters['quartos'])) {
            $this->db->where('imoveis.quartos >=', $filters['quartos']);
        }

        // Filtro por vagas
        if (isset($filters['vagas'])) {
            $this->db->where('imoveis.vagas >=', $filters['vagas']);
        }

        // Filtro por preço mínimo
        if (isset($filters['preco_min'])) {
            $this->db->where('imoveis.preco >=', $filters['preco_min']);
        }

        // Filtro por preço máximo
        if (isset($filters['preco_max'])) {
            $this->db->where('imoveis.preco <=', $filters['preco_max']);
        }

        // Filtro por área mínima
        if (isset($filters['area_min'])) {
            $this->db->where('imoveis.area_privativa >=', $filters['area_min']);
        }

        // Filtro por área máxima
        if (isset($filters['area_max'])) {
            $this->db->where('imoveis.area_privativa <=', $filters['area_max']);
        }

        // Busca textual
        if (isset($filters['search'])) {
            $this->db->group_start();
            $this->db->like('imoveis.descricao', $filters['search']);
            $this->db->or_like('imoveis.endereco', $filters['search']);
            $this->db->or_like('imoveis.bairro', $filters['search']);
            $this->db->or_like('imoveis.cidade', $filters['search']);
            $this->db->group_end();
        }

        // Apenas ativos
        if (!isset($filters['include_inactive']) || !$filters['include_inactive']) {
            $this->db->where('imoveis.ativo', 1);
        }

        // Apenas destaques
        if (isset($filters['destaque']) && $filters['destaque']) {
            $this->db->where('imoveis.destaque', 1);
        }

        // Ordenação
        $order_by = isset($filters['order_by']) ? $filters['order_by'] : 'created_at';
        $order_dir = isset($filters['order_dir']) ? $filters['order_dir'] : 'DESC';
        $this->db->order_by('imoveis.' . $order_by, $order_dir);

        // Paginação
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    /**
     * Contar imóveis
     * 
     * @param array $filters Filtros opcionais
     * @return int Total de imóveis
     */
    public function count_all($filters = []) {
        // Aplicar os mesmos filtros do get_all
        if (isset($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }

        if (isset($filters['tipo_negocio'])) {
            $this->db->where('tipo_negocio', $filters['tipo_negocio']);
        }

        if (isset($filters['tipo_imovel'])) {
            $this->db->where('tipo_imovel', $filters['tipo_imovel']);
        }

        if (isset($filters['estado'])) {
            $this->db->where('estado', $filters['estado']);
        }

        if (isset($filters['cidade'])) {
            $this->db->where('cidade', $filters['cidade']);
        }

        if (isset($filters['search'])) {
            $this->db->group_start();
            $this->db->like('descricao', $filters['search']);
            $this->db->or_like('endereco', $filters['search']);
            $this->db->or_like('bairro', $filters['search']);
            $this->db->or_like('cidade', $filters['search']);
            $this->db->group_end();
        }

        if (!isset($filters['include_inactive']) || !$filters['include_inactive']) {
            $this->db->where('ativo', 1);
        }

        return $this->db->count_all_results($this->table);
    }

    /**
     * Atualizar imóvel
     * 
     * @param int $id ID do imóvel
     * @param array $data Dados para atualizar
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function update($id, $data, $user_id = null) {
        // Recalcular valor por m² se preço ou área mudou
        if ((isset($data['preco']) || isset($data['area_privativa'])) && !isset($data['valor_m2'])) {
            $imovel = $this->get_by_id($id);
            $preco = isset($data['preco']) ? $data['preco'] : $imovel->preco;
            $area = isset($data['area_privativa']) ? $data['area_privativa'] : $imovel->area_privativa;
            
            if ($area > 0) {
                $data['valor_m2'] = $preco / $area;
            }
        }

        $this->db->where('id', $id);
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }

        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar imóvel
     * 
     * @param int $id ID do imóvel
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function delete($id, $user_id = null) {
        $where = ['id' => $id];
        
        if ($user_id) {
            $where['user_id'] = $user_id;
        }

        return $this->db->delete($this->table, $where);
    }

    /**
     * Ativar/Desativar imóvel
     * 
     * @param int $id ID do imóvel
     * @param bool $ativo Status
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function toggle_status($id, $ativo, $user_id = null) {
        return $this->update($id, ['ativo' => $ativo ? 1 : 0], $user_id);
    }

    /**
     * Marcar/Desmarcar como destaque
     * 
     * @param int $id ID do imóvel
     * @param bool $destaque Status
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function toggle_destaque($id, $destaque, $user_id = null) {
        return $this->update($id, ['destaque' => $destaque ? 1 : 0], $user_id);
    }

    /**
     * Obter tipos de imóveis únicos
     * 
     * @return array Lista de tipos
     */
    public function get_tipos_imoveis() {
        $this->db->distinct();
        $this->db->select('tipo_imovel');
        $this->db->where('ativo', 1);
        $this->db->order_by('tipo_imovel', 'ASC');

        $result = $this->db->get($this->table)->result();
        return array_column($result, 'tipo_imovel');
    }

    /**
     * Obter estados únicos
     * 
     * @return array Lista de estados
     */
    public function get_estados() {
        $this->db->distinct();
        $this->db->select('estado');
        $this->db->where('ativo', 1);
        $this->db->order_by('estado', 'ASC');

        $result = $this->db->get($this->table)->result();
        return array_column($result, 'estado');
    }

    /**
     * Obter cidades por estado
     * 
     * @param string $estado UF do estado
     * @return array Lista de cidades
     */
    public function get_cidades_by_estado($estado) {
        $this->db->distinct();
        $this->db->select('cidade');
        $this->db->where('estado', $estado);
        $this->db->where('ativo', 1);
        $this->db->order_by('cidade', 'ASC');

        $result = $this->db->get($this->table)->result();
        return array_column($result, 'cidade');
    }

    /**
     * Obter estatísticas de imóveis
     * 
     * @param int $user_id ID do usuário (opcional)
     * @return object Estatísticas
     */
    public function get_stats($user_id = null) {
        $stats = new stdClass();

        // Total de imóveis
        $filters = ['include_inactive' => false];
        if ($user_id) {
            $filters['user_id'] = $user_id;
        }
        $stats->total = $this->count_all($filters);

        // Por tipo de negócio
        $filters['tipo_negocio'] = 'compra';
        $stats->venda = $this->count_all($filters);

        $filters['tipo_negocio'] = 'aluguel';
        $stats->aluguel = $this->count_all($filters);

        // Valor médio
        $this->db->select_avg('preco');
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where('ativo', 1);
        $result = $this->db->get($this->table)->row();
        $stats->preco_medio = $result->preco ? (float)$result->preco : 0;

        return $stats;
    }

    /**
     * Contar imóveis por usuário
     * 
     * @param int $user_id ID do usuário
     * @param bool $only_active Contar apenas ativos
     * @return int Total de imóveis
     */
    public function count_by_user($user_id, $only_active = true) {
        $this->db->where('user_id', $user_id);
        if ($only_active) {
            $this->db->where('ativo', 1);
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Inativar todos os imóveis de um usuário
     * 
     * @param int $user_id ID do usuário
     * @return bool Sucesso da operação
     */
    public function inativar_todos_by_user($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->update($this->table, ['ativo' => 0]);
    }
    
    /**
     * Desativar imóveis por plano vencido
     * 
     * @param int $user_id ID do usuário
     * @return bool Sucesso
     */
    public function desativar_por_plano_vencido($user_id) {
        $data = [
            'status_publicacao' => 'inativo_plano_vencido',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('user_id', $user_id);
        $this->db->where('status_publicacao', 'ativo');
        
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Reativar imóveis ao renovar plano
     * 
     * @param int $user_id ID do usuário
     * @return bool Sucesso
     */
    public function reativar_por_renovacao_plano($user_id) {
        $data = [
            'status_publicacao' => 'ativo',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('user_id', $user_id);
        $this->db->where_in('status_publicacao', ['inativo_plano_vencido', 'inativo_sem_plano']);
        
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Contar imóveis ativos por usuário
     * 
     * @param int $user_id ID do usuário
     * @return int Total de imóveis ativos
     */
    public function count_ativos_by_user($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('status_publicacao', 'ativo');
        
        return $this->db->count_all_results($this->table);
    }
    
    // ========================================
    // MÉTODOS DE VALIDAÇÃO DE IMÓVEIS (60 DIAS)
    // ========================================
    
    /**
     * Buscar imóveis que precisam de validação (60 dias)
     * 
     * Critérios:
     * - Cadastrados há 60 dias ou mais
     * - Ativos (ativo = 1)
     * - Status disponível (status_venda = 'disponivel')
     * - Nunca validados (validacao_enviada_em IS NULL)
     * 
     * @return array Lista de imóveis com dados do corretor
     */
    public function get_imoveis_para_validacao() {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email, users.telefone as corretor_telefone');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');
        
        // Imóveis cadastrados há 60 dias ou mais
        $this->db->where('DATE(imoveis.created_at) <=', date('Y-m-d', strtotime('-60 days')));
        
        // Ativos e disponíveis
        $this->db->where('imoveis.ativo', 1);
        $this->db->where('imoveis.status_venda', 'disponivel');
        
        // Nunca validados
        $this->db->where('imoveis.validacao_enviada_em IS NULL');
        
        $this->db->order_by('imoveis.created_at', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Buscar imóveis com validação expirada (72h sem resposta)
     * 
     * Critérios:
     * - Validação expirada (validacao_expira_em < NOW())
     * - Não confirmados (validacao_confirmada_em IS NULL)
     * - Ainda ativos (ativo = 1)
     * 
     * @return array Lista de imóveis com dados do corretor
     */
    public function get_imoveis_validacao_expirada() {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');
        
        // Validação expirada
        $this->db->where('imoveis.validacao_expira_em <', date('Y-m-d H:i:s'));
        
        // Não confirmados
        $this->db->where('imoveis.validacao_confirmada_em IS NULL');
        
        // Ainda ativos
        $this->db->where('imoveis.ativo', 1);
        
        $this->db->order_by('imoveis.validacao_expira_em', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Enviar validação (atualizar campos de controle)
     * 
     * @param int $imovel_id ID do imóvel
     * @param string $token Token único de validação
     * @return bool Sucesso
     */
    public function enviar_validacao($imovel_id, $token) {
        $data = [
            'validacao_enviada_em' => date('Y-m-d H:i:s'),
            'validacao_expira_em' => date('Y-m-d H:i:s', strtotime('+72 hours')),
            'validacao_token' => $token,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $imovel_id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Buscar imóvel por token de validação
     * 
     * @param string $token Token de validação
     * @return object|null Dados do imóvel com corretor
     */
    public function get_by_token($token) {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');
        $this->db->where('imoveis.validacao_token', $token);
        
        return $this->db->get()->row();
    }
    
    /**
     * Confirmar que imóvel ainda está disponível
     * 
     * @param string $token Token de validação
     * @return bool Sucesso
     */
    public function confirmar_disponibilidade($token) {
        // Verificar se token é válido e não expirou
        $imovel = $this->get_by_token($token);
        
        if (!$imovel) {
            return false;
        }
        
        // Verificar se não expirou
        if ($imovel->validacao_expira_em && strtotime($imovel->validacao_expira_em) < time()) {
            return false;
        }
        
        // Confirmar disponibilidade
        $data = [
            'validacao_confirmada_em' => date('Y-m-d H:i:s'),
            'validacao_expira_em' => null,
            'validacao_token' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('validacao_token', $token);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Marcar imóvel como vendido ou alugado
     * 
     * @param string $token Token de validação
     * @param string $status_venda 'vendido' ou 'alugado'
     * @return bool Sucesso
     */
    public function marcar_como_negociado($token, $status_venda) {
        // Verificar se token é válido
        $imovel = $this->get_by_token($token);
        
        if (!$imovel) {
            return false;
        }
        
        // Validar status
        if (!in_array($status_venda, ['vendido', 'alugado'])) {
            return false;
        }
        
        // Marcar como vendido/alugado e desativar
        $status_publicacao = $status_venda === 'vendido' ? 'inativo_vendido' : 'inativo_alugado';
        
        $data = [
            'status_venda' => $status_venda,
            'data_venda' => date('Y-m-d'),
            'ativo' => 0,
            'status_publicacao' => $status_publicacao,
            'validacao_confirmada_em' => date('Y-m-d H:i:s'),
            'validacao_expira_em' => null,
            'validacao_token' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('validacao_token', $token);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Desativar imóvel por validação expirada
     * 
     * @param int $imovel_id ID do imóvel
     * @return bool Sucesso
     */
    public function desativar_por_validacao_expirada($imovel_id) {
        $data = [
            'ativo' => 0,
            'status_publicacao' => 'inativo_por_tempo',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $imovel_id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Limpar campos de validação ao reativar imóvel
     * 
     * @param int $imovel_id ID do imóvel
     * @return bool Sucesso
     */
    public function limpar_validacao($imovel_id) {
        $data = [
            'validacao_enviada_em' => null,
            'validacao_expira_em' => null,
            'validacao_confirmada_em' => null,
            'validacao_token' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $imovel_id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Estatísticas de validação de imóveis
     * 
     * @return object Estatísticas
     */
    public function get_stats_validacao() {
        $stats = new stdClass();
        
        // Total de imóveis ativos
        $this->db->where('ativo', 1);
        $stats->total_ativos = $this->db->count_all_results($this->table);
        
        // Imóveis que precisam validação (60 dias)
        $this->db->where('DATE(created_at) <=', date('Y-m-d', strtotime('-60 days')));
        $this->db->where('ativo', 1);
        $this->db->where('status_venda', 'disponivel');
        $this->db->where('validacao_enviada_em IS NULL');
        $stats->precisam_validacao = $this->db->count_all_results($this->table);
        
        // Validações pendentes (aguardando resposta)
        $this->db->where('validacao_enviada_em IS NOT NULL');
        $this->db->where('validacao_confirmada_em IS NULL');
        $this->db->where('validacao_expira_em >', date('Y-m-d H:i:s'));
        $this->db->where('ativo', 1);
        $stats->validacoes_pendentes = $this->db->count_all_results($this->table);
        
        // Validações expiradas (sem resposta)
        $this->db->where('validacao_expira_em <', date('Y-m-d H:i:s'));
        $this->db->where('validacao_confirmada_em IS NULL');
        $this->db->where('ativo', 1);
        $stats->validacoes_expiradas = $this->db->count_all_results($this->table);
        
        // Imóveis confirmados
        $this->db->where('validacao_confirmada_em IS NOT NULL');
        $stats->confirmados = $this->db->count_all_results($this->table);
        
        // Imóveis vendidos
        $this->db->where('status_venda', 'vendido');
        $stats->vendidos = $this->db->count_all_results($this->table);
        
        // Imóveis alugados
        $this->db->where('status_venda', 'alugado');
        $stats->alugados = $this->db->count_all_results($this->table);
        
        return $stats;
    }
}
