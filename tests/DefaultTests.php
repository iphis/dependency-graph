<?php

namespace DependencyGraphTests;

use iphis\DependencyGraph;
use iphis\DependencyGraph\Node;
use PHPUnit\Framework\TestCase;

class DefaultsTest extends TestCase
{
    public function testBasicDependencies()
    {
        $graph = new DependencyGraph();
        $nodeA = new Node("A");
        $nodeB = new Node("B");
        $nodeC = new Node("C");

        $graph->addRoot($nodeA);
        $graph->addDependency($nodeA, $nodeB);
        $graph->addDependency($nodeA, $nodeC);

        $this->assertEquals([0 => ['A' => ['B', 'C']]], $graph->toArray());
    }

    /**
     * @expectedException \iphis\DependencyGraph\Exceptions\NoParentException
     */
    public function testNoParentDetection()
    {
        $graph = new DependencyGraph();
        $nodeA = new Node("A");
        $nodeB = new Node("B");
        $nodeC = new Node("C");

        $graph->addRoot($nodeA);
        $graph->addDependency($nodeC, $nodeB);
        $graph->toArray();
    }

    /**
     * @expectedException \iphis\DependencyGraph\Exceptions\CircularDependencyException
     */
    public function testCircularDependencyDetection()
    {
        $graph = new DependencyGraph();
        $nodeA = new Node("A");
        $nodeB = new Node("B");
        $nodeC = new Node("C");

        $graph->addRoot($nodeA);
        $graph->addDependency($nodeA, $nodeB);
        $graph->addDependency($nodeB, $nodeC);
        $graph->addDependency($nodeC, $nodeA);
        $graph->toArray();
    }
}