<?php

namespace AppBundle\Twig;

use AppBundle\Service\ContentService;
use Symfony\Component\Translation\TranslatorInterface;

class TwigExtension extends \Twig_Extension
{
    /** @var ContentService */
    private $content;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * TwigExtension constructor.
     * @param ContentService $content
     */
    public function __construct(ContentService $content, TranslatorInterface $translator)
    {
        $this->content = $content;
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('month', array($this, 'month')),
        ];
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

    public function month(\DateTime $date) {
        return $this->translator->trans('month.'.$date->format('n'));
    }
}
