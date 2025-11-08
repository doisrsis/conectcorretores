<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Debug - Status de Assinatura
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 07/11/2025
 */
class Debug_subscription extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Subscription_model');
        $this->load->model('User_model');
        $this->load->helper('subscription');
    }

    /**
     * Debug do status da assinatura
     */
    public function index() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            echo "❌ Você precisa estar logado";
            return;
        }

        $user_id = $this->session->userdata('user_id');
        
        echo "<h1>🔍 Debug de Assinatura</h1>";
        echo "<style>
            body { font-family: Arial; padding: 20px; }
            .box { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
            .success { background: #d4edda; border-left: 4px solid #28a745; }
            .error { background: #f8d7da; border-left: 4px solid #dc3545; }
            .warning { background: #fff3cd; border-left: 4px solid #ffc107; }
            pre { background: white; padding: 10px; border-radius: 3px; overflow-x: auto; }
        </style>";

        // 1. Buscar assinatura diretamente do banco
        echo "<div class='box'>";
        echo "<h2>1️⃣ Assinatura do Banco (get_active_by_user)</h2>";
        $subscription = $this->Subscription_model->get_active_by_user($user_id);
        
        if ($subscription) {
            echo "<div class='success'>";
            echo "<strong>✅ Assinatura encontrada!</strong><br>";
            echo "<strong>ID:</strong> {$subscription->id}<br>";
            echo "<strong>Status:</strong> <code>{$subscription->status}</code><br>";
            echo "<strong>Data Fim:</strong> {$subscription->data_fim}<br>";
            echo "<strong>Plano:</strong> {$subscription->plan_nome}<br>";
            echo "</div>";
            echo "<pre>" . print_r($subscription, true) . "</pre>";
        } else {
            echo "<div class='error'>";
            echo "<strong>❌ Nenhuma assinatura encontrada!</strong>";
            echo "</div>";
        }
        echo "</div>";

        // 2. Verificar helper
        echo "<div class='box'>";
        echo "<h2>2️⃣ Status via Helper (get_status_assinatura)</h2>";
        $status_plano = get_status_assinatura($user_id);
        
        echo "<div class='box'>";
        echo "<strong>tem_plano:</strong> " . ($status_plano->tem_plano ? '✅ true' : '❌ false') . "<br>";
        echo "<strong>plano_ativo:</strong> " . ($status_plano->plano_ativo ? '✅ true' : '❌ false') . "<br>";
        echo "<strong>plano_pendente:</strong> " . ($status_plano->plano_pendente ? '⚠️ true' : '❌ false') . "<br>";
        echo "<strong>plano_vencido:</strong> " . ($status_plano->plano_vencido ? '🔴 true' : '✅ false') . "<br>";
        echo "<strong>data_fim:</strong> {$status_plano->data_fim}<br>";
        echo "</div>";
        
        echo "<pre>" . print_r($status_plano, true) . "</pre>";
        echo "</div>";

        // 3. Verificar query SQL
        echo "<div class='box'>";
        echo "<h2>3️⃣ Query SQL Executada</h2>";
        
        $this->db->select('subscriptions.*, plans.nome as plan_nome');
        $this->db->from('subscriptions');
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');
        $this->db->where('subscriptions.user_id', $user_id);
        $this->db->where_in('subscriptions.status', ['ativa', 'pendente']);
        $this->db->where('subscriptions.data_fim >=', date('Y-m-d'));
        
        echo "<pre>" . $this->db->get_compiled_select() . "</pre>";
        echo "</div>";

        // 4. Buscar TODAS as assinaturas do usuário
        echo "<div class='box'>";
        echo "<h2>4️⃣ Todas as Assinaturas do Usuário</h2>";
        
        $this->db->select('id, status, data_fim, plan_id, created_at');
        $this->db->from('subscriptions');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $all_subs = $this->db->get()->result();
        
        if ($all_subs) {
            echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Status</th><th>Data Fim</th><th>Plan ID</th><th>Criado Em</th></tr>";
            foreach ($all_subs as $sub) {
                $color = '';
                if ($sub->status == 'ativa') $color = 'background: #d4edda;';
                if ($sub->status == 'pendente') $color = 'background: #fff3cd;';
                if ($sub->status == 'cancelada') $color = 'background: #f8d7da;';
                
                echo "<tr style='$color'>";
                echo "<td>{$sub->id}</td>";
                echo "<td><strong>{$sub->status}</strong></td>";
                echo "<td>{$sub->data_fim}</td>";
                echo "<td>{$sub->plan_id}</td>";
                echo "<td>{$sub->created_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='error'>❌ Nenhuma assinatura encontrada</div>";
        }
        echo "</div>";

        // 5. Verificar condição da view
        echo "<div class='box'>";
        echo "<h2>5️⃣ Qual Mensagem Deve Aparecer?</h2>";
        
        if (!$status_plano->tem_plano) {
            echo "<div class='warning'>";
            echo "<strong>📋 Mensagem: SEM PLANO</strong><br>";
            echo "Condição: !tem_plano = true";
            echo "</div>";
        } elseif ($status_plano->plano_pendente) {
            echo "<div class='warning'>";
            echo "<strong>⚠️ Mensagem: PERÍODO DE GRAÇA</strong><br>";
            echo "Condição: plano_pendente = true";
            echo "</div>";
        } elseif ($status_plano->plano_vencido) {
            echo "<div class='error'>";
            echo "<strong>🔴 Mensagem: PLANO VENCIDO</strong><br>";
            echo "Condição: plano_vencido = true";
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<strong>✅ Mensagem: PLANO ATIVO</strong><br>";
            echo "Nenhum aviso deve aparecer";
            echo "</div>";
        }
        echo "</div>";

        // 6. Ações
        echo "<div class='box'>";
        echo "<h2>6️⃣ Ações</h2>";
        echo "<a href='" . base_url('dashboard') . "' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>🏠 Ir para Dashboard</a>";
        echo "<a href='" . base_url('test_payment_failure') . "' style='display: inline-block; padding: 10px 20px; background: #ffc107; color: #333; text-decoration: none; border-radius: 5px; margin: 5px;'>🧪 Testar Falhas</a>";
        echo "<a href='javascript:location.reload()' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>🔄 Recarregar</a>";
        echo "</div>";
    }
}
