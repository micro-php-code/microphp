<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;

use MicroPHP\Framework\Testing\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class IndexTest extends TestCase
{
    public function testIndex()
    {
        $result = $this->get('http://127.0.0.1:8080')->assertOk();
        $this->assertSame('Hello World', $result->getContent());
    }
}
