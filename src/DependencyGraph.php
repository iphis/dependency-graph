<?php

namespace iphis;

use iphis\DependencyGraph\Either\Right;
use iphis\DependencyGraph\Exceptions\CircularDependencyException;
use iphis\DependencyGraph\Exceptions\NoParentException;
use iphis\DependencyGraph\Map;
use iphis\DependencyGraph\Node;
use iphis\DependencyGraph\Nodes;

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
                throw new NoParentException("Parent node not present in graph.");
            }
        } else {
            throw new CircularDependencyException("Refusing to add circular dependency.");
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

        return ($node->nonEmpty() && $node->get()->hasDependency($parent));
    }
}