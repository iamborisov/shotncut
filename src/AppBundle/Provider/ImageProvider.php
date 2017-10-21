<?php

namespace AppBundle\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Resizer\ResizerInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;

class ImageProvider extends \Sonata\MediaBundle\Provider\ImageProvider
{
    /**
     * @var ImagineInterface
     */
    protected $imagineAdapter;

    /**
     * @param string                   $name
     * @param Filesystem               $filesystem
     * @param CDNInterface             $cdn
     * @param GeneratorInterface       $pathGenerator
     * @param ThumbnailInterface       $thumbnail
     * @param array                    $allowedExtensions
     * @param array                    $allowedMimeTypes
     * @param ImagineInterface         $adapter
     * @param MetadataBuilderInterface $metadata
     * @param ResizerInterface         $resizer
     */
    public function __construct(
        $name,
        Filesystem $filesystem,
        CDNInterface $cdn,
        GeneratorInterface $pathGenerator,
        ThumbnailInterface $thumbnail,
        array $allowedExtensions,
        array $allowedMimeTypes,
        ImagineInterface $adapter,
        MetadataBuilderInterface $metadata,
        ResizerInterface $resizer
    ) {
        parent::__construct(
            $name,
            $filesystem,
            $cdn,
            $pathGenerator,
            $thumbnail,
            $allowedExtensions,
            $allowedMimeTypes,
            $adapter,
            $metadata
        );

        $this->imagineAdapter = $adapter;
        $this->resizer = $resizer;
    }

    /**
     * @param MediaInterface $media
     * @return bool
     */
    protected function isSVG(MediaInterface $media) {
        if ($media->getContentType() === null) {
            return $media->getBinaryContent()->getMimeType() === 'image/svg+xml';
        } else {
            return $media->getContentType() === 'image/svg+xml';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        if ($this->isSVG($media)) {
            $attributes = array(
                'alt' => $media->getName(),
                'title' => $media->getName(),
                'src' => $this->generatePublicUrl($media, MediaProviderInterface::FORMAT_REFERENCE)
            );

            $resizerFormat = $this->getFormat($format);
            if ($resizerFormat !== false) {
                if ($resizerFormat['height'] !== false) {
                    $attributes['height'] = $resizerFormat['height'];
                }
                if ($resizerFormat['width'] !== false) {
                    $attributes['width'] = $resizerFormat['width'];
                }
            }

            return array_merge($attributes, $options);
        }

        return parent::getHelperProperties($media, $format, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = true)
    {
        if ($this->isSVG($media)) {
            if (!$media->getBinaryContent() instanceof \SplFileInfo) {
                // this is now optimized at all!!!
                $path = tempnam(sys_get_temp_dir(), 'sonata_update_metadata');
                $fileObject = new \SplFileObject($path, 'w');
                $fileObject->fwrite($this->getReferenceFile($media)->getContent());
            } else {
                $fileObject = $media->getBinaryContent();
            }

            $media->setSize($fileObject->getSize());
            $media->setWidth(0);
            $media->setHeight(0);
        } else {
            parent::updateMetadata($media, $force);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($this->isSVG($media)) {
            return $this->getCdn()->getPath(
                $this->getReferenceImage($media),
                $media->getCdnIsFlushable()
            );
        }

        return parent::generatePublicUrl($media, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        if ($this->isSVG($media)) {
            return $this->getReferenceImage($media);
        }

        return parent::generatePrivateUrl($media, $format);
    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        if ($this->isSVG($media)) {
            FileProvider::doTransform($media);
            $media->setWidth(1);
            $media->setHeight(1);
            $media->setProviderStatus(MediaInterface::STATUS_OK);
        } else {
            parent::doTransform($media);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateThumbnails(MediaInterface $media)
    {
        if (!$this->isSVG($media)) {
            parent::generateThumbnails($media);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeThumbnails(MediaInterface $media, $formats = null)
    {
        if (!$this->isSVG($media)) {
            parent::removeThumbnails($media, $formats);
        }
    }


}