<?php

namespace AppBundle\Service;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\Translator;

class MailerService {

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var ContentService */
    private $settings;

    /** @var EngineInterface */
    private $templating;

    /** @var Translator */
    private $translator;

    /** @var string[] */
    public $required = [];

    /** @var array */
    public $fields = [];

    /** @var string */
    public $subject;

    /** @var string */
    public $recipient;

    /** @var string */
    public $sender;

    /** @var string */
    public $template;

    /** @var string */
    public $contentType;

    /**
     * MailerService constructor.
     * @param \Swift_Mailer $mailer
     * @param ContentService $settings
     * @param EngineInterface $templating
     * @param Translator $translator
     */
    public function __construct(
        \Swift_Mailer $mailer,
        ContentService $settings,
        EngineInterface $templating,
        Translator $translator
    ) {
        $this->mailer = $mailer;
        $this->settings = $settings;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param \Swift_Mailer $mailer
     */
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @return ContentService
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param ContentService $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return EngineInterface
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param EngineInterface $templating
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validate($data = []) {
        foreach ($this->required as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @return \Swift_Message
     */
    public function create($recipient, $data = []) {
        return (new \Swift_Message())
            ->setSubject($this->getSubject())
            ->setTo($this->getTo($recipient))
            ->setFrom($this->getFrom())
            ->setBody(
                $this->getBody($data),
                $this->getContentType()
            );
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function send($recipient, $data = []) {
        if (!$this->validate($data)) {
            return false;
        }

        // anti-spam
        if (isset($_POST['tsp']) && empty($_POST['tsp'])) {
            return $this->mailer->send(
                $this->create($recipient, $data)
            );
        } else {
            return 1;
        }
    }

    /**
     * @return string
     */
    protected function getSubject() {
        return trim(
            $this->translator->trans(
                $this->subject
            )
        );
    }

    /**
     * @return string
     */
    protected function getFrom() {
        return trim(
            $this->settings->get(
                $this->sender
            )
        );
    }

    /**
     * @param string|string[] $recipient
     * @return array
     */
    protected function getTo($recipient) {
        $result = [];

        if ($recipient) {
            if (is_string($recipient)) {
                $result = explode(',', $recipient);
            } elseif (is_array($recipient)) {
                $result = $recipient;
            }
        }

        $admins = $this->settings->get(
            $this->recipient
        );

        $result = array_merge(
            $result,
            explode(',', $admins)
        );

        foreach ($result as &$email) {
            $email = trim($email);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function getBody($data = []) {
        return trim(
            $this->templating->render(
                $this->template,
                $this->prepareData($data)
            )
        );
    }

    /**
     * @return string
     */
    protected function getContentType() {
        return $this->contentType;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareData($data = []) {
        $result = [];

        foreach ($this->fields as $field => $default) {
            $result[$field] = isset($data[$field]) && (gettype($data[$field]) == gettype($default))
                ? $data[$field]
                : $default;
        }

        return $result;
    }

}