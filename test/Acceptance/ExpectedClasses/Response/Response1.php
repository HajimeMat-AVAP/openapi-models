<?php

declare(strict_types=1);

namespace Api\Response;

use Api\Schema\Test1;
use Api\Schema\Test2;
use Api\Schema\Test3;
use Api\Schema\Test4;
use JsonSerializable;

/**
 * Response 1 for json requests
 */
readonly class Response1 implements JsonSerializable
{
    public function __construct(
        public string $id,
        public ?Test1 $test = null,
        /** @var Test2[]|null $items */
        public ?array $items = null,
        /** @var array<Test3|Test4>|null $whoKnows */
        public ?array $whoKnows = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        return array_filter(
            get_object_vars($this),
            static fn (mixed $value, string $key): bool => !(in_array($key, ['test', 'items', 'whoKnows'], true) && $value === null),
            ARRAY_FILTER_USE_BOTH
        );
    }
}
