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
            new \Twig_SimpleFunction('seo', array($this, 'seo'), ['is_safe' => ['html']]),
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

    public function seo($type, $page, $value = '')
    {
        if (!in_array($type, ['keywords', 'description'])) {
            throw new \Twig_Error_Runtime("Unsupported SEO type '$type'");
        }

        $content = addslashes(
            trim($value)
                ? trim($value)
                : trim($this->content->get("seo.$page.$type", ''))
        );

        return $content ? "<meta name='$type' content='$content'>" : '';
    }
}
