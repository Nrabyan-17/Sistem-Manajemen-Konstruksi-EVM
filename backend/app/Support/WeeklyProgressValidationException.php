<?php

namespace App\Support;

use InvalidArgumentException;

/** Pure structured error; remains catchable as InvalidArgumentException. */
final class WeeklyProgressValidationException extends InvalidArgumentException
{
    /** @param list<array{path:string,field:string,item_id:?string,message:string}> $errors */
    public function __construct(private array $errors)
    {
        parent::__construct(implode(' ', array_column($errors, 'message')));
    }

    public static function forField(string $field, string $message): self
    {
        return new self([['path' => $field, 'field' => $field, 'item_id' => null, 'message' => $message]]);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function withContext(string $prefix = '', ?string $itemId = null): self
    {
        return new self(array_map(fn ($error) => array_replace($error, [
            'path' => $prefix === '' ? $error['path'] : $prefix.'.'.$error['path'],
            'item_id' => $itemId,
        ]), $this->errors));
    }
}
