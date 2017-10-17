<?php


namespace AppBundle\Twig;


class ContentNode extends \Twig_Node
{

    public function __construct($params, $lineno = 0, $tag = null)
    {
        parent::__construct(array ('params' => $params), array (), $lineno, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $count = count($this->getNode('params'));

        $compiler
            ->addDebugInfo($this);

        for ($i = 0; ($i < $count); $i++)
        {
            // argument is not an expression (such as, a \Twig_Node_Textbody)
            // we should trick with output buffering to get a valid argument to pass
            // to the functionToCall() function.
            if (!($this->getNode('params')->getNode($i) instanceof \Twig_Node_Expression))
            {
                $compiler
                    ->write('ob_start();')
                    ->raw(PHP_EOL);

                $compiler
                    ->subcompile($this->getNode('params')->getNode($i));

                $compiler
                    ->write(sprintf('$_content[%d][] = ob_get_clean();', $this->getAttribute('counter')))
                    ->raw(PHP_EOL);
            }
            else
            {
                $compiler
                    ->write(sprintf('$_content[%d][] = ', $this->getAttribute('counter')))
                    ->subcompile($this->getNode('params')->getNode($i))
                    ->raw(';')
                    ->raw(PHP_EOL);
            }
        }

        $compiler
            ->write('echo $this->env->getExtension(')
            ->string('AppBundle\Twig\TwigExtension')
            ->write(')->content(')
            ->raw(sprintf('$_content[%d][1]', $this->getAttribute('counter')))
            ->raw(sprintf(', $_content[%d][0]);', $this->getAttribute('counter')))
            ->raw(PHP_EOL);

        $compiler
            ->write(sprintf('unset($_content[%d]);', $this->getAttribute('counter')))
            ->raw(PHP_EOL);
    }

}