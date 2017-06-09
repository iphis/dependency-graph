Dependency Graph
================
A dependency graph implementation.
Derived from the Work of JosephMoniz (plasmaconduit/dependency-graph).

[![Build Status](https://travis-ci.org/iphis/dependency-graph.svg?branch=master)](https://travis-ci.org/iphis/dependency-graph)
[![StyleCI](https://styleci.io/repos/93886535/shield?branch=master)](https://styleci.io/repos/93886535)

Examples
--------
```php
<?php
use iphis\DependencyGraph;
use iphis\DependencyGraph\Node;
use iphis\DependencyGraph\Nodes;

// Initialize the graph and some stand alone nodes
$graph = new DependencyGraph();
$nodeA = new Node("A");
$nodeB = new Node("B");
$nodeC = new Node("C");
$nodeD = new Node("D");
$nodeE = new Node("E");
$nodeF = new Node("F");
$nodeG = new Node("G");

// Add the root node A
$graph->addRoot($nodeA);
$graph->addDependency($nodeA, $nodeB);
$graph->addDependency($nodeA, $nodeC);
$graph->addDependency($nodeB, $nodeD);
$graph->addDependency($nodeC, $nodeE);
$graph->addDependency($nodeC, $nodeF);
// Tree Status:
//       A
//      / \
//     B   C
//    /   / \
//   D   E   F

echo json_encode($graph->toArray(), JSON_PRETTY_PRINT);
// Outputs:
//  [
//      {
//          "A": [
//              {
//                  "B": [
//                      "D"
//                  ]
//              },
//              {
//                  "C": [
//                      "D",
//                      "E"
//                  ]
//              }
//          ]
//      }
//  ]

// Try to create a circular dependency by making E dependent on A
// This should fail and refuse to fulfill the dependency
$graph->addDependency($nodeE, $nodeA);
echo json_encode($graph->toArray(), JSON_PRETTY_PRINT);
// Outputs:
//  [
//      {
//          "A": [
//              {
//                  "B": [
//                      "D"
//                  ]
//              },
//              {
//                  "C": [
//                      "D",
//                      "E"
//                  ]
//              }
//          ]
//      }
//  ]

// Add a node that already has dependencies to an adjacent branch
$graph->addDependency($nodeD, $nodeC);
// Tree Status:
//       A
//      / \
//     B   C
//    /   / \
//   D   E   F
//    \
//     C
//    / \
//   E   F

echo json_encode($graph->toArray(), JSON_PRETTY_PRINT);
// Outputs:
//  [
//      {
//          "A": [
//              {
//                  "B": [
//                      {
//                          "D": [
//                              {
//                                  "C": [
//                                      "E",
//                                      "F"
//                                  ]
//                              }
//                          ]
//                      }
//                  ]
//              },
//              {
//                  "C": [
//                      "E",
//                      "F"
//                  ]
//              }
//          ]
//      }
//  ]

echo implode(",", $graph->flatten());
```