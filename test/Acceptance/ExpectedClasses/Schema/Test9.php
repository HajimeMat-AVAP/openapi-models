<?php

declare(strict_types=1);

namespace Api\Schema;

use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements ArrayAccess<int, Test1|Test2>
 * @implements IteratorAggregate<Test1|Test2>
 */
readonly class Test9 implements IteratorAggregate, Countable, ArrayAccess, JsonSerializable
{
    /** @var array<Test1|Test2> $items */
    private array $items;

    public function __construct(Test1|Test2 ...$items)
    {
        $this->items = $items;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): Test1|Test2|null
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException('Object is readOnly');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException('Object is readOnly');
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
