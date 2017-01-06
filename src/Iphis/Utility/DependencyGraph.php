<?php
namespace Iphis\Utility;

use Iphis\Utility\DependencyGraph\Node;
use Iphis\Utility\DependencyGraph\Nodes;
use Iphis\Utility\DependencyGraph\Either\Left;
use Iphis\Utility\DependencyGraph\Either\Right;
use Iphis\Utility\DependencyGraph\Map;

class DependencyGraph
{
    private $_roots;

    private $_registry;

    public function __construct()
    {
        $this->_roots = new Nodes();
        $this->_registry = new Map();
    }

    public function addRoot(Node $node)
    {
        $optionalNode = $this->_registry->get($node->getName());
        $canonicalNode = $optionalNode->getOrElse($node);
        if ($this->_roots->addSibling($canonicalNode)) {
            if ($optionalNode->isEmpty()) {
                $this->_addNodeToRegistry($canonicalNode);
            }

            return true;
        } else {
            return false;
        }
    }

    public function addDependency(
        Node $parent,
        Node $node
    ) {
        if (!$this->_hasCircularDependency($parent, $node)) {
            $canonicalParent = $this->_registry->get($parent->getName());
            if ($canonicalParent->nonEmpty()) {
                $optionalNode = $this->_registry->get($node->getName());
                $canonicalNode = $optionalNode->getOrElse($node);
                $canonicalParent->get()->addDependency($canonicalNode);
                if ($optionalNode->isEmpty()) {
                    $this->_addNodeToRegistry($canonicalNode);
                }

                return new Right(true);
            } else {
                return new Left("Parent node not present in graph.");
            }
        } else {
            return new Left("Refusing to add circular dependency.");
        }
    }

    public function toArray()
    {
        return $this->_roots->toArray();
    }

    public function flatten()
    {
        return array_keys($this->_roots->flatten());
    }

    private function _addNodeToRegistry(Node $node)
    {
        $this->_registry->set($node->getName(), $node);
    }

    private function _hasCircularDependency(
        Node $parent,
        Node $node
    ) {
        $node = $this->_registry->get($node->getName());

        return $node->nonEmpty() && $node->get()->hasDependency($parent);
    }
}