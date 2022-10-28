<?php

    require_once "constants.php";

class Dashboard {

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;
    public $usuariosAtivos;
    public $usuariosInativos;
    public $totalDespesas;
    public $totalReclamacoes;
    public $totalElogios;
    public $totalSugestoes;


    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
        return $this;
    }
}

//classe conexao com o banco

class Conexao {
    
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    public function conectar() {
        try {

            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );

            //
            $conexao->exec('set charset utf8');

            return $conexao;

        } catch(PDOException $e) {
            echo '<p>' . $e->getMessage() .'<p/>';
        }
    }
}

//classe (model)

class Bd {
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard) {
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas() {
        $query = '
                select
                    count(*) as numero_vendas
                from
                    tb_vendas
                where
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function getTotalVendas() {
        $query = '
                select
                    SUM(total) as total_vendas
                from
                    tb_vendas
                where
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }

    public function getUsuariosAtivos() {
        $query = '
                select
                    count(*) as total_ativos
                from
                    tb_clientes
                where
                    cliente_ativo = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_ativos;
    }

    public function getUsuariosInativos() {
        $query = '
                select
                    count(*) as total_inativos
                from
                    tb_clientes
                where
                    cliente_ativo = 0';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_inativos;
    }

    public function getTotalDespesas() {
        $query = '
                select
                    SUM(total) as total_despesas
                from
                    tb_despesas
                where
                    data_despesa between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
    }

    public function getTotalReclamacoes() {
        $query = '
            select
                count(*) as total_reclamacoes
            from
                tb_feedbacks
            where
                data_feedback between :data_inicio and :data_fim
            and status = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
    }
    
    public function getTotalElogios() {
        $query = '
            select
                count(*) as total_elogios
            from
                tb_feedbacks
            where
                data_feedback between :data_inicio and :data_fim
            and status = 2';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
    }

    public function getTotalSugestoes() {
        $query = '
            select
                count(*) as total_sugestoes
            from
                tb_feedbacks
            where
                data_feedback between :data_inicio and :data_fim
            and status = 3';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
    }
}


//lógica do script
$dashboard = new Dashboard;

$conexao = new Conexao;

$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio', $ano.'-'.$mes.'-'.'01');
$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);

$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('usuariosAtivos', $bd->getUsuariosAtivos());
$dashboard->__set('usuariosInativos', $bd->getUsuariosInativos());
$dashboard->__set('totalDespesas', $bd->getTotalDespesas());
$dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
$dashboard->__set('totalElogios', $bd->getTotalElogios());
$dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());

echo json_encode($dashboard);

?>