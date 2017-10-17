<?php


namespace AppBundle\Twig;


class ContentNodeVisitor implements \Twig_NodeVisitorInterface
{

    private $counter = 0;

    public function enterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof ContentNode) {
            $node->setAttribute('counter', $this->counter++);
        }
        return $node;
    }

    public function leaveNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof ContentNode) {
            $node->setAttribute('counter', $this->counter--);
        }
        return $node;
    }

    public function getPriority()
    {
        return 0;
    }

}