<?php

namespace Tests\Patch;

trait PhpUnitAssertBackport
{
    public static function assertEqualsCanonicalizing($expected, $actual, $message = '')
    {
        $constraint = new IsEqualCanonicalizing($expected);

        static::assertThat($actual, $constraint, $message);
    }
}
