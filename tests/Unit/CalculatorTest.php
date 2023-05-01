<?php

namespace Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /** @test */
    public function can_get_length_from_grams(): void
    {
        $this->assertSame(332.6, (new Calculator)->gramsToLength('PLA', 1.75, 1000));
        $this->assertSame(399.76, (new Calculator)->gramsToLength('ABS', 1.75, 1000));
        $this->assertSame(327.36, (new Calculator)->gramsToLength('PETG', 1.75, 1000));
    }

    /** @test */
    public function can_get_grams_from_length(): void
    {
        $this->assertSame(1000.0, (new Calculator)->lengthToGrams('PLA', 1.75, 332.6));
        $this->assertSame(1000.0, (new Calculator)->lengthToGrams('ABS', 1.75, 399.76));
        $this->assertSame(999.99, (new Calculator)->lengthToGrams('PETG', 1.75, 327.36));
    }
}
