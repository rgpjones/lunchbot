<?php


namespace RgpJones\Rotabot\Notifier;

interface Notifier
{
    public function send(string $text);
}
