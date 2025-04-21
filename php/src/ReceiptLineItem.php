<?php

namespace Supermarket;
class ReceiptLineItem
{
    public function __construct(private int $columns, private string $name, private string $value)
    {
        
    }

    public function __toString(): string
    {
        $whitespaceSize = $this->columns - strlen($this->name) - strlen($this->value);
        return $this->name . str_repeat(' ', $whitespaceSize) . $this->value;
    }
}