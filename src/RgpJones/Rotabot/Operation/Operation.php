<?php
namespace RgpJones\Rotabot\Operation;

interface Operation
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
