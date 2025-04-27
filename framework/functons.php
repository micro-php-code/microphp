<?php

declare(strict_types=1);
function base_path(string $var): string
{
    return BASE_PATH . $var;
}

function get_os(): string
{
    return DIRECTORY_SEPARATOR === '\\' ? OS_TYPE_LINUX : OS_TYPE_WINDOWS;
}
