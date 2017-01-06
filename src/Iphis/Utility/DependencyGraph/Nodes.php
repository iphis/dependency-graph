<?php
namespace Iphis\Utility\DependencyGraph;

use Iphis\Utility\DependencyGraph;

class Nodes
{
    /**
     * @var Map
     */
    private $_siblings;

    /**
     * Nodes constructor.
     */
    public function __construct()
    {
        $this->_siblings = new Map();
    }

    /**
     * @param Node $node
     * @return bool
     */
    public function hasSibling(DependencyGraph\Node $node)
    {
        return $this->getSibling($node)->nonEmpty();
    }

    /**
     * @param Node $node
     * @return bool
     */
    public function addSibling(Node $node)
    {
        if (!$this->hasSibling($node)) {
            $this->_siblings->push($node);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Node $node
     * @return Option\OptionInterface
     */
    public function getSibling(DependencyGraph\Node $node)
    {
        return $this->_siblings->findValue(
            function ($value, $key) use ($node) {
                /** @var Node $value */
                return $value->getName() == $node->getName();
            }
        );
    }

    /**
     * @return Map
     */
    public function getAllSiblings()
    {
        return $this->_siblings;
    }

    public function hasDependency(DependencyGraph\Node $node)
    {
        return $this->getDependencyNodes($node)->length() !== 0;
    }

    public function addDependency(
        DependencyGraph\Node $parent,
        DependencyGraph\Node $node
    ) {
        $this->getDependencyNodes($parent)->each(
            function ($val, $k) use ($node) {
                /** @var Node $val */
                $val->addDependency($node);
            }
        );
    }

    /**
     * @param Node $node
     * @return Map
     */
    public function getDependencyNodes(Node $node)
    {
        $init = new Map();

        return $this->_siblings->reduce(
            $init,
            function ($memo, $n) use ($node) {
                /** @var Node $n */
                /** @var Map $memo */
                if ($n->getName() == $node->getName()) {
                    $memo->push($node);
                } else {
                    $memo = $memo->merge($n->getDependencyNodes($node));
                }

                return $memo;
            }
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_siblings->map(
            function ($value, $key) {
                /** @var Node $value */
                return $value->toArray();
            }
        )->toArray();
    }

    /**
     * @return array
     */
    public function flatten()
    {
        $siblings = $this->_siblings->map(
            function ($value, $key) {
                /** @var Node $value */
                return $value->flatten();
            }
        )->toArray();

        return array_reduce(
            $siblings,
            function ($memo, $sibling) {
                return array_merge($memo, $sibling);
            },
            []
        );
    }
}