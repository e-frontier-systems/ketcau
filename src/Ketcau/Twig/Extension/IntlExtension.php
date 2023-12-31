<?php

namespace Ketcau\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IntlExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('date_day', [$this, 'date_day'], ['needs_environment' => true]),
            new TwigFilter('date_min', [$this, 'date_min'], ['needs_environment' => true]),
            new TwigFilter('date_sec', [$this, 'date_sec'], ['needs_environment' => true]),
            new TwigFilter('date_day_with_weekday', [$this, 'date_day_with_weekday'], ['needs_environment' => true]),
        ];
    }


    public function date_day(Environment $env, $date)
    {
        if (!$date) {
            return '';
        }

        return (new \Twig\Extra\Intl\IntlExtension())->formatDateTime($env, $date, 'medium', 'none');
    }

    public function date_min(Environment $env, $date)
    {
        if (!$date) {
            return '';
        }

        return (new \Twig\Extra\Intl\IntlExtension())->formatDateTime($env, $date, 'medium', 'short');
    }

    public function date_sec(Environment $env, $date)
    {
        if (!$date) {
            return '';
        }

        return (new \Twig\Extra\Intl\IntlExtension())->formatDateTime($env, $date, 'medium', 'medium');
    }

    public function date_day_with_weekday(Environment $env, $date)
    {
        if (!$date) {
            return '';
        }

        $date_day = (new \Twig\Extra\Intl\IntlExtension())->formatDate($env, $date, 'medium');
        // 曜日
        $dateFormatter = \IntlDateFormatter::create(
            'ja_JP@calendar=japanese',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            'Asia/Tokyo',
            \IntlDateFormatter::TRADITIONAL,
            'E'
        );
        return $date_day. '('. $dateFormatter->format($date).')';
    }
}