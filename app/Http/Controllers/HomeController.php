<?php

namespace App\Http\Controllers;

use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LDAP\Result;
use PDO;
use PDOException;
use ReflectionObject;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd("Conectado");
        /*
        try {
            $conn = new PDO("sqlsrv:Server=sts450wk16;Database=dbRH", NULL, NULL);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            dd("Connected to SQL Server\n");
            return $conn;
        } catch (PDOException $e) {
            die("Error connecting to SQL Server" . $e->getMessage());
        }
        
        $sql1 = "SELECT * from incident";
        $result = json_encode(DB::select($sql1));
        $backlogResult = $this->backlog($result);
        $ticket = $this->tickets($result);
        $statusResult = $this->status($result);
        $resolved = $statusResult[0];
        $notStarted = $statusResult[1];
        $progress = $statusResult[2];
        $slaResult = $this->sla($result);
        $topsalver = $this->topresolvers($result);
        $toptables = $this->toptable($result);
        $topcis = $this->topci($result);
        $backlogResult = json_encode($backlogResult);
        $toptables = json_encode($toptables);
        $topsalver = json_encode($topsalver);
        $topcis = json_encode($topcis);
        
        return view('home',compact('ticket','slaResult','resolved','notStarted','progress','topsalver','topcis','toptables','backlogResult'));
        */
    }
    //retorna o status de todos os tickets
    public function status($valores)
    {
        $re = json_decode($valores);
        $resolved = 0;
        $notStarted = 0;
        $inProgress = 0;

        foreach ($re as $val) {
            if ($val->state == 'Open' || $val->state == 'New') {
                $notStarted++;
            } else if ($val->state == 'On Hold' || $val->state == 'Work in Progress' || $val->state == 'Pending' || $val->state == 'In Progress') {
                $inProgress++;
            } else {
                $resolved++;
            }
        }

        $total = count($re);
        $resultResolved = number_format(($resolved * 100) / $total, 0);
        $resultNotStarted = number_format(($notStarted * 100) / $total, 0);
        $resultInProgress = number_format(($inProgress * 100) / $total, 0);
        return [$resultResolved, $resultNotStarted, $resultInProgress];
    }
    //retirna a procentagem em inteiro das sla que nao ultrapassaram 100%
    public function sla($valores)
    {
        $re = json_decode($valores);
        $total = count($re);
        $naoQuebraram = 0;
        foreach ($re as $v) {
            if ($v->percentage <= 100) {
                $naoQuebraram++;
            }
        }

        $ResultnaoQuebraram = number_format(($naoQuebraram * 100) / $total, 2);
        return $ResultnaoQuebraram;
    }
    //retorna a porcentagem de ticket e incidents
    public function tickets($valores)
    {
        $re = json_decode($valores);
        $total = count($re);
        $incidents = 0;
        $services = 0;
        foreach ($re as $v) {
            if ($v->category == "service request" || $v->category == "-") {
                $services++;
            } else {
                $incidents++;
            }
        }
        $ResultIncidents = number_format(($incidents * 100) / $total, 0);
        $ResultServices = number_format(($services * 100) / $total, 0);
        return [$ResultServices, $ResultIncidents, $total];
    }

    //retorna os 5 primeiros incidents que estejam com a maior sla
    public function toptable($valores)
    {
        $re = json_decode($valores);
        $incidents = array();

        foreach ($re as $v) {
            if ($v->incident != "-" && $v->state != 'Closed' && $v->state != 'Resolved') {
                array_push($incidents, $v);
            }
        }

        $incidents =  json_encode($incidents);
        $dd = json_decode($incidents);
        for ($i = 0; $i < count($dd); $i++) {
            for ($j = 0; $j < count($dd); $j++) {
                if ($dd[$i]->percentage > $dd[$j]->percentage) {
                    $aux = $dd[$i];
                    $dd[$i] = $dd[$j];
                    $dd[$j] = $aux;
                }
            }
        }
        $dd = array_slice($dd, 0, 5);
        return $dd;
    }
    //retorna a quantidade d evezes que cada ci foi instanciada
    public function topci($valores)
    {
        $re = json_decode($valores);
        $ciAll = array();
        foreach ($re as $v) {
            $valor = explode(" ", $v->ci);
            $resu = array_slice($valor, 0, -1);
            $val = "";
            if (count($resu) == 0) {
                $val = "NÃO REGISTRADO";
            } else {
                if (count($resu) > 1) {
                    $resu = array_slice($valor, 0, -3);
                }
                foreach ($resu as $chave => $t) {
                    if ($chave < count($resu) - 1) {
                        $val .= $t . " ";
                    } else {
                        $val .= $t;
                    }
                }
            }
            array_push($ciAll, $val);
        }
        $ret = array_unique($ciAll);
        $resultado = array();
        foreach ($ret as $v) {
            $cont = 0;
            for ($i = 0; $i < count($ciAll); $i++) {
                if ($v == $ciAll[$i]) {
                    $cont++;
                }
            }
            $resultado[] = ["nome" => $v, "value" => $cont];
        }

        for ($i = 0; $i < count($resultado); $i++) {
            for ($j = 0; $j < count($resultado); $j++) {
                if ($resultado[$i]['value'] > $resultado[$j]['value']) {
                    $aux = $resultado[$i];
                    $resultado[$i] = $resultado[$j];
                    $resultado[$j] = $aux;
                }
            }
        }

        $resultado = array_slice($resultado, 0, 5);
        return $resultado;
    }
    //faz um filtro com as 5 ultimas semanas do ano a contar do dia incident
    public function filterSemanasQuantidade()
    {
        $date = new DateTime('2023-01-01');
        $dias = $date->diff(new DateTime())->days;
        $ate = intdiv($dias, 7);
        $resto = $dias % 7;
        $re = array();
        $result = array();
        if ($ate < 1) {
            return null;
        } else {
            //com semanas  
            $interval = new DateInterval('P7D');
            for ($i = 0; $i < $ate; $i++) {
                $va = $date->add($interval);
                $o = new ReflectionObject($va);
                $p = $o->getProperty('date');
                $rep = $p->getValue($va);
                array_push($re, substr($rep, 0, 10));
            }
            $cont = 0;
            $sair = 0;
            $incr = count($re) - 1;
            while ($cont != 5 && $sair != 1) {
                $dateIncial = new DateTime($re[$incr]);
                $ret = $dateIncial->sub($interval);
                $o = new ReflectionObject($ret);
                $p = $o->getProperty('date');
                $rep = $p->getValue($ret);
                $result[] = [substr($rep, 0, 10), $re[$incr], $incr + 1];
                $incr--;
                $cont++;
                if ($incr < 0) {
                    $sair = 1;
                }
            }
            if ($resto > 0) {
                $date = new DateTime($re[count($re) - 1]);
                $diasX = $date->diff(new DateTime())->days;
                $interval = new DateInterval('P1D');
                $diasRestantes = array();
                for ($i = 0; $i < $diasX; $i++) {
                    $va = $date->add($interval);
                    $o = new ReflectionObject($va);
                    $p = $o->getProperty('date');
                    $rep = $p->getValue($va);
                    array_push($diasRestantes, substr($rep, 0, 10));
                }
                array_unshift($result, [$re[count($re) - 1], $diasRestantes[count($diasRestantes) - 1], $ate + 1]);
            }
            $result = array_slice($result, 0, 5);
            krsort($result);
            $data = array();
            foreach ($result as $v) {
                array_push($data, [date('d-m-Y', strtotime($v[0])), date('d-m-Y', strtotime($v[1])), $v[2]]);
            }
            //dd($data); //vejo as datas
            return $data;
        }
    }
    //retorna as 5 ultimas semanas com backlog, abertos e fechados
    public function backlog($valores)
    {
        $dataArray = json_decode($valores);
        $dataSemanas = $this->filterSemanasQuantidade();
        $result = array();
        $semanas = array();
        $periodo = array();

        foreach ($dataSemanas as $v) {
            $periodo = [];
            for ($i = 0; $i < count($dataArray); $i++) {
                if (
                    date('Y-m-d', strtotime(substr($dataArray[$i]->created, 0, 10))) >= date('Y-m-d', strtotime($v[0])) &&
                    date('Y-m-d', strtotime(substr($dataArray[$i]->created, 0, 10))) < date('Y-m-d', strtotime($v[1]))
                ) {
                    $periodo[] = [$dataArray[$i]];
                }
            }
            array_push($result, $periodo);
            array_push($semanas, $v[2]);
        }
        foreach ($result as $chave => $val) {
            $resolved = 0;
            $open = 0;
            foreach ($val as $v) {
                if ($v[0]->state == 'Resolved' || $v[0]->state == 'Closed' || $v[0]->state == 'Closed Complete' || $v[0]->state == 'Closed Incomplete' || $v[0]->state == 'Closed Skipped') {
                    $resolved++;
                } else {
                    $open++;
                }
            }
            $backlog[] = ['backlog' => $open, 'resolved' => $resolved, 'open' => count($val), 'semana' => $semanas[$chave]];
        }
        return $backlog;
    }

    //retorna todos os  incidents ou tasks que na qual apresentem resolved by
    //sendo esse incident ou task igual a closed closed complet e resolved
    public function topresolvers($valores)
    {
        $re = json_decode($valores);
        $ciAll = array();
        foreach ($re as $v) {
            if ($v->resolved != '-' && ($v->state == "Closed Complete" || $v->state == "Closed" || $v->state == "Resolved")) {
                array_push($ciAll, $v->resolved);
            }
        }
        $ret = array_unique($ciAll);
        $resultado = array();
        foreach ($ret as $v) {
            $cont = 0;
            for ($i = 0; $i < count($ciAll); $i++) {
                if ($v == $ciAll[$i]) {
                    $cont++;
                }
            }
            $resultado[] = ["nome" => $v, "valor" => $cont];
        }

        for ($i = 0; $i < count($resultado); $i++) {
            for ($j = 0; $j < count($resultado); $j++) {
                if ($resultado[$i]['valor'] > $resultado[$j]['valor']) {
                    $aux = $resultado[$i];
                    $resultado[$i] = $resultado[$j];
                    $resultado[$j] = $aux;
                }
            }
        }
        return $resultado;
    }
}
