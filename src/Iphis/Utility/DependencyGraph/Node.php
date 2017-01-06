<?php
namespace Iphis\Utility\DependencyGraph;

class Node
{
    private $_name;

    private $_dependencies;

    public function __construct($name)
    {
        $this->_name = $name;
        $this->_dependencies = new Nodes();
    }

    public function getName()
    {
        return $this->_name;
    }

    public function hasDependency(Node $node)
    {
        return $this->_dependencies->hasDependency($node);
    }

    public function addDependency(Node $node)
    {
        $this->_dependencies->addSibling($node);
    }

    public function getImmediateDependencyNodes()
    {
        $this->_dependencies->getAllSiblings();
    }

    public function getDependencyNodes(Node $node)
    {
        return $this->_dependencies->getDependencyNodes($node);
    }

    public function toArray()
    {
        $dependencies = $this->_dependencies->toArray();
        if (count($dependencies)) {
            $memo = [];
            $memo[$this->_name] = $dependencies;

            return $memo;
        } else {
            return $this->_name;
        }
    }

    public function flatten()
    {
        $dependencies = $this->_dependencies->flatten();
        $memo = [];
        $memo[$this->_name] = 1;

        if (count($dependencies)) {
            return array_merge($dependencies, $memo);
        } else {
            return $memo;
        }
    }
}