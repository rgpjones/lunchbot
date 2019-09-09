<?php
namespace RgpJones\Rotabot;

interface Command
{
    /**
     * @return string
     */
    public function getUsage();

    /**
     * @param array $args
     * @param $username
     *
     * @return string|null
     */
    public function run(array $args, $username);
}
