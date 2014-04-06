<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

class NodeTest {

    private $networkId;
    private $targetNodeId;
    /** @var SimpleNode[] */
    private $testNodes;
    /** @var CubicGrid */
    private $grid;
    /**
     * @param Node[] $nodes
     */
    public function __construct($networkId, $targetNodeId) {
        $this->networkId = $networkId;
        $this->targetNodeId = $targetNodeId;
        $this->grid = new CubicGrid();
        /** @var Node[] $nodes */
        $nodes = Node::getNodesInNetwork($networkId);
        $targetNode = Node::getNode($targetNodeId);
        $targetNodeGps = $targetNode->getCoordinates();
        $dx = 0 - $targetNodeGps->getX();
        $dy = 0 - $targetNodeGps->getY();
        $dz = 0 - $targetNodeGps->getZ();
        $this->testNodes[] = new SimpleNode($targetNodeId, $targetNode->getName(), 0, 0, 0);
        foreach($nodes as $node) {
            if($node->getId() == $targetNodeId) continue;
            $nodeCoord = $node->getCoordinates();
            $this->testNodes[] = new SimpleNode($node->getId(), $node->getName(), $nodeCoord->getX()+$dx, $nodeCoord->getY()+$dy, $nodeCoord->getZ()+$dz);
        }
        $minX = null;
        $maxX = null;
        $minY = null;
        $maxY = null;
        $minZ = null;
        $maxZ = null;
        foreach($this->testNodes as $node) {
            if($minX === null || $node->x < $minX) {
                $minX = $node->x;
            }
            if($maxX === null || $node->x > $maxX) {
                $maxX = $node->x;
            }
            if($minY === null || $node->y < $minY) {
                $minY = $node->y;
            }
            if($maxY === null || $node->y > $maxY) {
                $maxY = $node->y;
            }
            if($minZ === null || $node->z < $minZ) {
                $minZ = $node->z;
            }
            if($maxZ === null || $node->z > $maxZ) {
                $maxZ = $node->z;
            }
        }
        $this->grid->x = $minX; $this->grid->y = $minY; $this->grid->z = $minZ;
        $this->grid->width = ($minX !== null && $maxX !== null) ? abs($maxX - $minX) : 0;
        $this->grid->height = ($minY !== null & $maxY !== null) ? abs($maxY - $minY) : 0;
        $this->grid->depth = ($minZ !== null && $maxZ !== null) ? abs($maxZ - $minZ) : 0;
    }

    /**
     * @return CubicGrid
     */
    public function getGrid() {
        return $this->grid;
    }

    /**
     * @return SimpleNode[]
     */
    public function getTestNodes() {
        return $this->testNodes;
    }
}


class SimpleNode {

    public $nodeId;
    public $nodeName;
    public $x;
    public $y;
    public $z;

    public function __construct($nodeId, $name, $x, $y, $z) {
        $this->nodeId = $nodeId;
        $this->nodeName = $name;
        $this->x = round($x);
        $this->y = round($y);
        $this->z = round($z);
    }

}

class CubicGrid {
    public $x;
    public $y;
    public $z;
    public $width;
    public $height;
    public $depth;
}