<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Dto;

use MicroPHP\Data\Util\Str;
use MicroPHP\Framework\Dto\Request\BaseReq;
use MicroPHP\Framework\Exception\ValidateException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validation;

trait ConstraintsTrait
{
    /**
     * @var null|array<string, Constraint>
     */
    protected static ?array $_constraints = null;

    /**
     * 添加默认验证器
     */
    public function _initConstraints(): void
    {
        if (is_null($this::$_constraints)) {
            $attributeRules = [];
            foreach ($this->getStaticReflection()->getProperties() as $property) {
                if ($this->isInsideProperty($property)) {
                    continue;
                }
                $snakePropertyName = Str::snake($property->getName());
                if (! $property->getType()->allowsNull() && ! $property->hasDefaultValue()) {
                    $attributeRules[$snakePropertyName][] = [new NotNull()];
                }
            }
            $this::$_constraints = $attributeRules;
        }
    }

    /**
     * 自定义验证器
     */
    public static function loadValidatorMetadata(?ClassMetadata $metadata = null): void
    {
        foreach (static::$_constraints ?: [] as $property => $constraints) {
            $metadata->addPropertyConstraint($property, $constraints);
        }
    }

    /**
     * @throws ValidateException
     */
    protected function validate(BaseReq $data): void
    {
        $data->_initConstraints();
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
        $this->throw($validator->validate($data));
        $this->throw($validator->validate($data));
    }

    /**
     * @throws ValidateException
     */
    protected function throw(ConstraintViolationListInterface $errors): void
    {
        foreach ($errors as $violation) {
            throw new ValidateException($violation->getPropertyPath() . ': ' . $violation->getMessage() . PHP_EOL);
        }
    }
}
