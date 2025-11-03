<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Cron - ConectCorretores
 * 
 * Tarefas agendadas (cron jobs)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 19/10/2025
 */
class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Carregar models
        $this->load->model('Subscription_model');
        $this->load->model('Plan_model');
        
        // Carregar libraries
        $this->load->library('stripe_lib');
    }

    /**
     * Sincronizar todas as assinaturas com Stripe
     * 
     * Executar diariamente via cron:
     * 0 3 * * * curl http://localhost/conectcorretores/cron/sync_subscriptions
     */
    public function sync_subscriptions() {
        // Verificar se está sendo executado via CLI ou com token de segurança
        if (!$this->_is_cli() && !$this->_verify_cron_token()) {
            show_404();
            return;
        }

        $start_time = microtime(true);
        $total = 0;
        $updated = 0;
        $errors = 0;

        echo "=== Sincronização de Assinaturas com Stripe ===\n";
        echo "Início: " . date('Y-m-d H:i:s') . "\n\n";

        // Buscar todas as assinaturas ativas e pendentes
        $subscriptions = $this->Subscription_model->get_all([
            'status' => ['ativa', 'pendente', 'trial']
        ]);

        echo "Total de assinaturas para sincronizar: " . count($subscriptions) . "\n\n";

        foreach ($subscriptions as $subscription) {
            $total++;
            
            if (!$subscription->stripe_subscription_id) {
                echo "[$total] Assinatura ID {$subscription->id} - Sem stripe_subscription_id, pulando...\n";
                continue;
            }

            echo "[$total] Sincronizando assinatura ID {$subscription->id} (User: {$subscription->user_nome})...\n";

            try {
                // Buscar no Stripe
                $stripe_result = $this->stripe_lib->retrieve_subscription($subscription->stripe_subscription_id);

                if (!$stripe_result['success']) {
                    echo "  ❌ Erro: {$stripe_result['error']}\n";
                    $errors++;
                    continue;
                }

                $stripe_sub = $stripe_result['subscription'];
                $update_data = [];

                // Verificar status
                $stripe_status = $this->_map_stripe_status($stripe_sub->status);
                if ($stripe_status !== $subscription->status) {
                    $update_data['status'] = $stripe_status;
                    echo "  📝 Status: {$subscription->status} → {$stripe_status}\n";
                }

                // Verificar data de fim
                $stripe_end_date = date('Y-m-d', $stripe_sub->current_period_end);
                $stripe_start_date = date('Y-m-d', $stripe_sub->current_period_start);
                
                // Validar se data fim é maior que data início
                if ($stripe_end_date > $stripe_start_date && $stripe_end_date !== $subscription->data_fim) {
                    $update_data['data_fim'] = $stripe_end_date;
                    echo "  📅 Data fim: {$subscription->data_fim} → {$stripe_end_date}\n";
                } elseif ($stripe_end_date <= $stripe_start_date) {
                    echo "  ⚠️ Data fim inválida no Stripe (fim <= início). Ignorando.\n";
                }

                // Verificar plano
                $stripe_price_id = $stripe_sub->items->data[0]->price->id;
                $current_plan_stripe_price_id = isset($subscription->plan_stripe_price_id) ? $subscription->plan_stripe_price_id : null;
                
                if ($stripe_price_id !== $current_plan_stripe_price_id) {
                    $plan = $this->Plan_model->get_by_stripe_price_id($stripe_price_id);
                    if ($plan) {
                        $update_data['plan_id'] = $plan->id;
                        echo "  📦 Plano: {$subscription->plan_nome} → {$plan->nome}\n";
                    }
                }

                // Atualizar se houver mudanças
                if (!empty($update_data)) {
                    $update_data['updated_at'] = date('Y-m-d H:i:s');
                    $this->Subscription_model->update($subscription->id, $update_data);
                    echo "  ✅ Atualizado com sucesso!\n";
                    $updated++;
                } else {
                    echo "  ✓ Já está sincronizado\n";
                }

            } catch (Exception $e) {
                echo "  ❌ Exceção: {$e->getMessage()}\n";
                $errors++;
            }

            echo "\n";
            
            // Delay para não sobrecarregar API do Stripe
            sleep(1);
        }

        $end_time = microtime(true);
        $duration = round($end_time - $start_time, 2);

        echo "=== Resumo ===\n";
        echo "Total processado: $total\n";
        echo "Atualizados: $updated\n";
        echo "Erros: $errors\n";
        echo "Tempo: {$duration}s\n";
        echo "Fim: " . date('Y-m-d H:i:s') . "\n";
    }

    /**
     * Diagnóstico de assinaturas
     * 
     * Ver todas as assinaturas e seus detalhes
     * http://localhost/conectcorretores/cron/diagnostico?token=SEU_TOKEN
     */
    public function diagnostico() {
        // Verificar se está sendo executado via CLI ou com token de segurança
        if (!$this->_is_cli() && !$this->_verify_cron_token()) {
            show_404();
            return;
        }

        echo "=== Diagnóstico de Assinaturas ===\n";
        echo "Data: " . date('Y-m-d H:i:s') . "\n\n";

        // Buscar TODAS as assinaturas (sem filtro)
        $all_subscriptions = $this->Subscription_model->get_all();

        echo "📊 TOTAL DE ASSINATURAS NO BANCO: " . count($all_subscriptions) . "\n\n";

        if (empty($all_subscriptions)) {
            echo "❌ Nenhuma assinatura encontrada no banco de dados!\n";
            return;
        }

        echo "┌─────┬──────────────────────┬────────────┬─────────────┬──────────────────────┬─────────────────────┐\n";
        echo "│ ID  │ Usuário              │ Status     │ Plano       │ Stripe Sub ID        │ Data Fim            │\n";
        echo "├─────┼──────────────────────┼────────────┼─────────────┼──────────────────────┼─────────────────────┤\n";

        foreach ($all_subscriptions as $sub) {
            $user_nome = str_pad(substr($sub->user_nome, 0, 20), 20);
            $status = str_pad($sub->status, 10);
            $plano = str_pad(substr($sub->plan_nome, 0, 11), 11);
            $stripe_id = $sub->stripe_subscription_id ? str_pad(substr($sub->stripe_subscription_id, 0, 20), 20) : str_pad('(vazio)', 20);
            $data_fim = str_pad($sub->data_fim, 19);

            echo "│ " . str_pad($sub->id, 3) . " │ {$user_nome} │ {$status} │ {$plano} │ {$stripe_id} │ {$data_fim} │\n";
        }

        echo "└─────┴──────────────────────┴────────────┴─────────────┴──────────────────────┴─────────────────────┘\n\n";

        // Filtrar por status
        echo "📋 ASSINATURAS POR STATUS:\n\n";
        
        $status_count = [];
        foreach ($all_subscriptions as $sub) {
            if (!isset($status_count[$sub->status])) {
                $status_count[$sub->status] = 0;
            }
            $status_count[$sub->status]++;
        }

        foreach ($status_count as $status => $count) {
            echo "  • {$status}: {$count}\n";
        }

        echo "\n";

        // Verificar quais serão sincronizadas
        $sync_subscriptions = $this->Subscription_model->get_all([
            'status' => ['ativa', 'pendente', 'trial']
        ]);

        echo "🔄 ASSINATURAS QUE SERÃO SINCRONIZADAS: " . count($sync_subscriptions) . "\n";
        echo "   (Status: ativa, pendente ou trial)\n\n";

        if (count($sync_subscriptions) > 0) {
            foreach ($sync_subscriptions as $sub) {
                echo "  ✓ ID {$sub->id} - {$sub->user_nome} - Status: {$sub->status}\n";
                
                if ($sub->stripe_subscription_id) {
                    echo "    Stripe ID: {$sub->stripe_subscription_id}\n";
                } else {
                    echo "    ⚠️ SEM stripe_subscription_id (será pulada na sincronização)\n";
                }
                
                echo "\n";
            }
        }

        echo "=== Fim do Diagnóstico ===\n";
    }

    /**
     * Forçar sincronização de uma assinatura específica
     * 
     * http://localhost/conectcorretores/cron/sync_one?token=SEU_TOKEN&id=4
     */
    public function sync_one() {
        // Verificar se está sendo executado via CLI ou com token de segurança
        if (!$this->_is_cli() && !$this->_verify_cron_token()) {
            show_404();
            return;
        }

        $subscription_id = $this->input->get('id');

        if (!$subscription_id) {
            echo "❌ Erro: Informe o ID da assinatura\n";
            echo "Exemplo: /cron/sync_one?token=SEU_TOKEN&id=4\n";
            return;
        }

        echo "=== Sincronização Forçada - Assinatura ID {$subscription_id} ===\n";
        echo "Data: " . date('Y-m-d H:i:s') . "\n\n";

        // Buscar assinatura
        $subscription = $this->Subscription_model->get_by_id($subscription_id);

        if (!$subscription) {
            echo "❌ Assinatura não encontrada!\n";
            return;
        }

        echo "📋 DADOS ATUAIS NO BANCO:\n";
        echo "  • ID: {$subscription->id}\n";
        echo "  • Usuário: {$subscription->user_nome}\n";
        echo "  • Status: {$subscription->status}\n";
        echo "  • Plano: {$subscription->plan_nome}\n";
        echo "  • Data Início: {$subscription->data_inicio}\n";
        echo "  • Data Fim: {$subscription->data_fim}\n";
        echo "  • Stripe Subscription ID: " . ($subscription->stripe_subscription_id ?: '(vazio)') . "\n";
        echo "  • Stripe Price ID (Plano): " . ($subscription->plan_stripe_price_id ?: '(vazio)') . "\n\n";

        if (!$subscription->stripe_subscription_id) {
            echo "❌ Esta assinatura não tem stripe_subscription_id!\n";
            echo "   Não é possível sincronizar com Stripe.\n";
            return;
        }

        echo "🔄 CONSULTANDO STRIPE...\n\n";

        try {
            $stripe_result = $this->stripe_lib->retrieve_subscription($subscription->stripe_subscription_id);

            if (!$stripe_result['success']) {
                echo "❌ Erro ao consultar Stripe: {$stripe_result['error']}\n";
                return;
            }

            $stripe_sub = $stripe_result['subscription'];

            echo "📋 DADOS NO STRIPE:\n";
            echo "  • ID: {$stripe_sub->id}\n";
            echo "  • Status: {$stripe_sub->status}\n";
            echo "  • Current Period Start: " . date('Y-m-d H:i:s', $stripe_sub->current_period_start) . "\n";
            echo "  • Current Period End: " . date('Y-m-d H:i:s', $stripe_sub->current_period_end) . "\n";
            echo "  • Price ID: {$stripe_sub->items->data[0]->price->id}\n";
            echo "  • Amount: " . ($stripe_sub->items->data[0]->price->unit_amount / 100) . "\n\n";

            // Comparar dados
            echo "🔍 COMPARAÇÃO:\n\n";

            $update_data = [];

            // Status
            $stripe_status = $this->_map_stripe_status($stripe_sub->status);
            if ($stripe_status !== $subscription->status) {
                echo "  📝 Status: {$subscription->status} → {$stripe_status}\n";
                $update_data['status'] = $stripe_status;
            } else {
                echo "  ✓ Status: OK ({$subscription->status})\n";
            }

            // Data de fim
            $stripe_end_date = date('Y-m-d', $stripe_sub->current_period_end);
            $stripe_start_date = date('Y-m-d', $stripe_sub->current_period_start);
            
            // Validar se data fim é maior que data início
            if ($stripe_end_date > $stripe_start_date) {
                if ($stripe_end_date !== $subscription->data_fim) {
                    echo "  📅 Data Fim: {$subscription->data_fim} → {$stripe_end_date}\n";
                    $update_data['data_fim'] = $stripe_end_date;
                } else {
                    echo "  ✓ Data Fim: OK ({$subscription->data_fim})\n";
                }
            } else {
                echo "  ⚠️ Data Fim: INVÁLIDA no Stripe (fim <= início: {$stripe_start_date} a {$stripe_end_date})\n";
                echo "     Mantendo data atual do banco: {$subscription->data_fim}\n";
            }

            // Plano
            $stripe_price_id = $stripe_sub->items->data[0]->price->id;
            $current_plan_stripe_price_id = isset($subscription->plan_stripe_price_id) ? $subscription->plan_stripe_price_id : null;
            
            if ($stripe_price_id !== $current_plan_stripe_price_id) {
                $plan = $this->Plan_model->get_by_stripe_price_id($stripe_price_id);
                if ($plan) {
                    echo "  📦 Plano: {$subscription->plan_nome} → {$plan->nome}\n";
                    $update_data['plan_id'] = $plan->id;
                } else {
                    echo "  ⚠️ Plano no Stripe ({$stripe_price_id}) não encontrado no banco\n";
                }
            } else {
                echo "  ✓ Plano: OK ({$subscription->plan_nome})\n";
            }

            echo "\n";

            // Atualizar
            if (!empty($update_data)) {
                echo "💾 ATUALIZANDO BANCO DE DADOS...\n";
                $update_data['updated_at'] = date('Y-m-d H:i:s');
                
                if ($this->Subscription_model->update($subscription->id, $update_data)) {
                    echo "✅ Assinatura atualizada com sucesso!\n";
                } else {
                    echo "❌ Erro ao atualizar banco de dados!\n";
                }
            } else {
                echo "✓ Nenhuma atualização necessária. Dados já estão sincronizados!\n";
            }

        } catch (Exception $e) {
            echo "❌ Exceção: {$e->getMessage()}\n";
        }

        echo "\n=== Fim ===\n";
    }

    /**
     * Verificar assinaturas expiradas
     * 
     * Executar diariamente via cron:
     * 0 4 * * * curl http://localhost/conectcorretores/cron/check_expired
     */
    public function check_expired() {
        // Verificar se está sendo executado via CLI ou com token de segurança
        if (!$this->_is_cli() && !$this->_verify_cron_token()) {
            show_404();
            return;
        }

        echo "=== Verificação de Assinaturas Expiradas ===\n";
        echo "Início: " . date('Y-m-d H:i:s') . "\n\n";

        // Buscar assinaturas ativas que já expiraram
        $this->db->where('status', 'ativa');
        $this->db->where('data_fim <', date('Y-m-d'));
        $expired = $this->db->get('subscriptions')->result();

        echo "Total de assinaturas expiradas: " . count($expired) . "\n\n";

        foreach ($expired as $subscription) {
            echo "Expirando assinatura ID {$subscription->id} (User ID: {$subscription->user_id})...\n";
            
            $this->Subscription_model->update($subscription->id, [
                'status' => 'expirada',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            echo "  ✅ Status alterado para 'expirada'\n\n";
        }

        echo "Fim: " . date('Y-m-d H:i:s') . "\n";
    }

    /**
     * Verificar se está sendo executado via CLI
     */
    private function _is_cli() {
        return (php_sapi_name() === 'cli');
    }

    /**
     * Verificar token de segurança para execução via HTTP
     */
    private function _verify_cron_token() {
        $token = $this->input->get('token');
        $valid_token = $this->config->item('cron_token') ?: 'seu_token_secreto_aqui';
        
        return ($token === $valid_token);
    }

    /**
     * Mapear status do Stripe para status local
     */
    private function _map_stripe_status($stripe_status) {
        $status_map = [
            'active' => 'ativa',
            'past_due' => 'pendente',
            'canceled' => 'cancelada',
            'unpaid' => 'pendente',
            'incomplete' => 'pendente',
            'incomplete_expired' => 'expirada',
            'trialing' => 'trial',
            'paused' => 'pausada',
        ];

        return $status_map[$stripe_status] ?? 'pendente';
    }
    
    /**
     * Desativar imóveis de usuários com plano vencido
     * 
     * Executar diariamente via cron:
     * http://localhost/conectcorretores/cron/desativar_imoveis_planos_vencidos?token=SEU_TOKEN
     */
    public function desativar_imoveis_planos_vencidos() {
        // Verificar token
        if (!$this->_verify_cron_token()) {
            show_404();
            return;
        }
        
        echo "=== Desativar Imóveis - Planos Vencidos ===\n";
        echo "Início: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Buscar usuários com plano vencido
        $usuarios = $this->Subscription_model->get_usuarios_plano_vencido();
        
        echo "Usuários com plano vencido: " . count($usuarios) . "\n\n";
        
        $total_imoveis_desativados = 0;
        
        foreach ($usuarios as $usuario) {
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "Usuário: {$usuario->nome} (ID: {$usuario->id})\n";
            echo "Email: {$usuario->email}\n";
            echo "Plano vencido em: {$usuario->data_fim}\n";
            
            // Contar imóveis ativos
            $count = $this->Imovel_model->count_ativos_by_user($usuario->id);
            echo "Imóveis ativos: {$count}\n";
            
            if ($count > 0) {
                // Desativar
                if ($this->Imovel_model->desativar_por_plano_vencido($usuario->id)) {
                    echo "✅ {$count} imóveis desativados\n";
                    $total_imoveis_desativados += $count;
                    
                    // Atualizar status da assinatura
                    $this->Subscription_model->update_status_by_user($usuario->id, 'expirada');
                    echo "✅ Assinatura marcada como expirada\n";
                } else {
                    echo "❌ Erro ao desativar imóveis\n";
                }
            } else {
                echo "ℹ️ Nenhum imóvel ativo para desativar\n";
            }
            
            echo "\n";
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Total de imóveis desativados: {$total_imoveis_desativados}\n";
        echo "Fim: " . date('Y-m-d H:i:s') . "\n";
    }
}
