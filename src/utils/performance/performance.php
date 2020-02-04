<?php

function getTime($time = false)
{
    return $time === false? microtime(true) : microtime(true) - $time;
}

function getMemory($memory = false)
{
    return $memory === false? memory_get_usage() : memory_get_usage() - $memory;
}