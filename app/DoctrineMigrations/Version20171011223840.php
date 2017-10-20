<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171011223840 extends AbstractMigration
{
    private function addSetting($name, $title, $value = null) {
        $this->addSql(
            'INSERT INTO content (`name`, `value`, `title`) VALUES (:name, :value, :title)',
            [
                ':name' => $name,
                ':title' => $title,
                ':value' => $value
            ]
        );
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSetting('contacts.email', 'E-Mail на сайте', 'shotncut.film@gmail.com');
        $this->addSetting('contacts.email.contact', 'E-Mail получатель формы обратной связи', 'shotncut.film@gmail.com');
        $this->addSetting('contacts.email.brief', 'E-Mail получатель формы брифа', 'shotncut.film@gmail.com');
        $this->addSetting('contacts.email.from', 'E-Mail отправитель', 'no-reply@shotncut.ru');

        $this->addSetting('contacts.country', 'Страна', 'Республика Крым');
        $this->addSetting('contacts.address', 'Адрес', 'Симферополь, ул. Данилова 43');
        $this->addSetting('contacts.phone', 'Телефон', '+7 (978) 844 19 32');
        $this->addSetting('contacts.social.facebook', 'Ссылка Facebook');
        $this->addSetting('contacts.social.youtube', 'Ссылка Youtube');
        $this->addSetting('contacts.social.vimeo', 'Ссылка Vimeo');
        $this->addSetting('contacts.social.instagram', 'Ссылка Instagram');

        $this->addSetting('index.title', 'Заголовок');
        $this->addSetting('index.adv', 'Блок: Реклама');
        $this->addSetting('index.corp', 'Блок: Корпоративное видео');
        $this->addSetting('index.food', 'Блок: Food-съемка');
        $this->addSetting('index.free', 'Блок: Некоммерческое видео');
        $this->addSetting('index.fashion', 'Блок: Fashion');
        $this->addSetting('index.other', 'Блок: Другое');

        $this->addSetting('contact.title', 'Заголовок');

        $this->addSetting('about.title', 'Заголовок');
        $this->addSetting('about.text', 'Текст');

        $this->addSetting('blog.title', 'Заголовок');

        $this->addSetting('seo.index.keywords', 'Главная: Meta-Keywords', 'видео на заказ');
        $this->addSetting('seo.index.description', 'Главная: Meta-Description', 'Ищете, где заказать видеоролик в Крыму, Москве, Санкт-Петербурге? Shot’nCut - это видео на заказ, видеосъемка вирусного, корпоративного, рекламного видео, производство видео для бизнеса');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM content');
    }
}