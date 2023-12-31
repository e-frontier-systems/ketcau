<?php

namespace Ketcau\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ConvertKanaListener implements EventSubscriberInterface
{
    protected $option;

    protected $encoding;


    public function __construct($option = 'a', $encoding = 'utf-8')
    {
        $this->option = $option;
        $this->encoding = $encoding;
    }


    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }


    public function onPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        if (is_array($data)) {
            foreach ($data as &$value) {
                $value = mb_convert_kana($value, $this->option, $this->encoding);
            }
        }
        else {
            $data = mb_convert_kana($data, $this->option, $this->encoding);
        }

        $event->setData($data);
    }
}