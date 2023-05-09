<?php

function dijkstra_algorithm($graph, $start_node)
{
    $unvisited_nodes = $graph->get_nodes();

    // salvar o custo de visitar cada nó e atualizá-lo
    $shortest_path = array();

    // salvar o caminho mais curto conhecido
    $previous_nodes = array();

    // inicializar o valor "infinito" dos nós não visitados
    $max_value = PHP_INT_MAX;
    foreach ($unvisited_nodes as $node) {
        $shortest_path[$node] = $max_value;
    }
    // inicializar valor do nó de partida com 0
    $shortest_path[$start_node] = 0;

    // execucao
    while (!empty($unvisited_nodes)) {
        //  encontra o nó com a menor pontuação
        $current_min_node = null;
        foreach ($unvisited_nodes as $node) {
            if ($current_min_node === null || $shortest_path[$node] < $shortest_path[$current_min_node]) {
                $current_min_node = $node;
            }
        }

        // recupera os vizinhos do nó atual e atualiza suas distâncias
        $neighbors = $graph->get_outgoing_edges($current_min_node);
        foreach ($neighbors as $neighbor) {
            $tentative_value = $shortest_path[$current_min_node] + $graph->value($current_min_node, $neighbor);
            if ($tentative_value < $shortest_path[$neighbor]) {
                $shortest_path[$neighbor] = $tentative_value;
              
                $previous_nodes[$neighbor] = $current_min_node;
            }
        }

        // marcacao dos pontos visitados
        unset($unvisited_nodes[array_search($current_min_node, $unvisited_nodes)]);
    }

    return array($previous_nodes, $shortest_path);
}

class Graph
{
    private $nodes;
    private $graph;

    public function __construct($nodes, $init_graph)
    {
        $this->nodes = $nodes;
        $this->graph = $this->construct_graph($nodes, $init_graph);
    }

    private function construct_graph($nodes, $init_graph)
    {
        $graph = array();
        foreach ($nodes as $node) {
            $graph[$node] = array();
        }

        $graph = array_merge($graph, $init_graph);

        foreach ($graph as $node => $edges) {
            foreach ($edges as $adjacent_node => $value) {
                if (!isset($graph[$adjacent_node][$node])) {
                    $graph[$adjacent_node][$node] = $value;
                }
            }
        }

        return $graph;
    }

    public function get_nodes()
    {
        return $this->nodes;
    }

    public function get_outgoing_edges($node)
    {
        $connections = array();
        foreach ($this->nodes as $out_node) {
            if (isset($this->graph[$node][$out_node])) {
                $connections[] = $out_node;
            }
        }
        return $connections;
    }

    public function value($node1, $node2)
    {
        return $this->graph[$node1][$node2];
    }
}

function print_result($previous_nodes, $shortest_path, $start_node, $target_node)
{
    $path = array();
    $node = $target_node;

    while ($node != $start_node) {
        $path[] = $node;
        $node = $previous_nodes[$node];
    }

    // Adicione o nó de partida manualmente
    $path[] = $start_node;

    echo "O melhor camino sera: {$shortest_path[$target_node]}." . PHP_EOL;
    echo implode(" -- ", array_reverse($path)) . PHP_EOL;
}

$nodes = ["Oradea", "Zerind", "Arad", "Sibiu", "Fagaras", "Timisoara", "Lugoj", "Mehadia", "Dobreta", "Craiova", "Rimnicu Vilcea", "Pitesti", "Bucharest", "Girgiu", "Urziceni", "Hirsova", "Vaslui", "Eforie", "Iasi", "Neamt"];

$init_graph = array();
foreach ($nodes as $node) {
    $init_graph[$node] = array();
}

$init_graph["Pitesti"]["Bucharest"] = 101;
$init_graph["Pitesti"]["Rimnicu Vilcea"] = 97;
$init_graph["Pitesti"]["Craiova"] = 138;


$init_graph["Fagaras"]["Bucharest"] = 211;
$init_graph["Fagaras"]["Sibiu"] = 99;

$init_graph["Bucharest"]["Girgiu"] = 90;
$init_graph["Bucharest"]["Pitesti"] = 101;
$init_graph["Bucharest"]["Fagaras"] = 211;
$init_graph["Bucharest"]["Urziceni"] = 85;

$init_graph["Girgiu"]["Bucharest"] = 90;

$init_graph["Urziceni"]["Bucharest"] = 85;
$init_graph["Urziceni"]["Vaslui"] = 142;
$init_graph["Urziceni"]["Hirsova"] = 98;

$init_graph["Hirsova"]["Eforie"] = 86;
$init_graph["Hirsova"]["Urziceni"] = 98;

$init_graph["Eforie"]["Hirsova"] = 86;

$init_graph["Vaslui"]["Iasi"] = 92;
$init_graph["Vaslui"]["Urziceni"] = 142;

$init_graph["Iasi"]["Neamt"] = 87;
$init_graph["Iasi"]["Vaslui"] = 92;

$init_graph["Neamt"]["Iasi"] = 87;
$init_graph["Oradea"]["Zerind"] = 71;
$init_graph["Oradea"]["Sibiu"] = 151;

$init_graph["Zerind"]["Arad"] = 75;
$init_graph["Zerind"]["Oradea"] = 71;

$init_graph["Sibiu"]["Oradea"] = 151;
$init_graph["Sibiu"]["Arad"] = 140;
$init_graph["Sibiu"]["Fagaras"] = 99;
$init_graph["Sibiu"]["Rimnicu Vilcea"] = 80;

$init_graph["Arad"]["Timisoara"] = 118;
$init_graph["Arad"]["Zerind"] = 175;
$init_graph["Arad"]["Sibiu"] = 140;

$init_graph["Timisoara"]["Lugoj"] = 111;
$init_graph["Timisoara"]["Arad"] = 118;

$init_graph["Lugoj"]["Mehadia"] = 70;
$init_graph["Lugoj"]["Timisoara"] = 111;

$init_graph["Mehadia"]["Dobreta"] = 75;
$init_graph["Mehadia"]["Lugoj"] = 70;

$init_graph["Dobreta"]["Craiova"] = 120;
$init_graph["Dobreta"]["Mehadia"] = 75;

$init_graph["Craiova"]["Dobreta"] = 120;
$init_graph["Craiova"]["Rimnicu Vilcea"] = 146;
$init_graph["Craiova"]["Pitesti"] = 138;

$init_graph["Rimnicu Vilcea"]["Pitesti"] = 97;
$init_graph["Rimnicu Vilcea"]["Sibiu"] = 80;
$init_graph["Rimnicu Vilcea"]["Craiova"] = 146;



$graph = new Graph($nodes, $init_graph);
list($previous_nodes, $shortest_path) = dijkstra_algorithm($graph, "Lugoj");
print_result($previous_nodes, $shortest_path, "Lugoj", "Mehadia");
