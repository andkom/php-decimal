<?php

namespace AndKom;

class DecimalImmutable extends Decimal
{
    protected function mutate(): DecimalInterface
    {
        return new static($this->value, $this->scale);
    }
}