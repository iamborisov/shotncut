<?php

namespace AppBundle\Twig;

use AppBundle\Service\ContentService;

class TwigExtension extends \Twig_Extension
{
    /** @var ContentService */
    private $content;

    /**
     * TwigExtension constructor.
     * @param ContentService $content
     */
    public function __construct(ContentService $content)
    {
        $this->content = $content;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('content', array($this, 'content')),
        ];
    }

    public function getTokenParsers()
    {
        return [
            new ContentTokenParser()
        ];
    }

    public function getNodeVisitors()
    {
        return [
            new ContentNodeVisitor()
        ];
    }

    public function getName()
    {
        return 'TwigExtension';
    }

    public function content($key, $default = '')
    {
        return $this->content->get($key, $default);
    }
}
