<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Dto\Request;

use MicroPHP\Data\Data;
use MicroPHP\Framework\Dto\ConstraintsTrait;
use MicroPHP\Framework\Exception\ValidateException;
use MicroPHP\Framework\Http\ServerRequest;
use TypeError;

class BaseReq extends Data
{
    use ConstraintsTrait;

    /**
     * @throws
     */
    public static function fromRequest(array|ServerRequest $request): static
    {
        return static::_catch($request);
    }

    /**
     * @throws
     */
    protected static function _catch(array|ServerRequest $request): static
    {
        $instance = new static();
        try {
            $instance->fill($request instanceof ServerRequest ? array_merge($request->all(), $request->getUploadedFiles()) : $request);
        } catch (TypeError $e) {
            $pattern = '/[A-Za-z0-9_\\\]+::\$(\w+)/';
            $message = preg_replace($pattern, '$1', $e->getMessage());
            throw new ValidateException($message);
        }
        $instance->validate($instance);

        return $instance;
    }
}
