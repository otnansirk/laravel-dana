<?php

namespace Tests\Unit;

use Tests\TestCase;
use Otnansirk\Dana\Helpers\Calculation;

class CalculationTest extends TestCase
{
    public function test_can_get_tax_value(): void
    {
        $calculation = new Calculation();
        $taxValue = $calculation->taxValue();
        
        $this->assertIsFloat($taxValue);
        $this->assertEquals(0.11, $taxValue);
    }

    public function test_can_get_fees(): void
    {
        $calculation = new Calculation();
        $fees = $calculation->fees();
        
        $this->assertIsArray($fees);
        $this->assertArrayHasKey('CREDIT_CARD', $fees);
        $this->assertArrayHasKey('DEBIT_CARD', $fees);
        $this->assertArrayHasKey('BALANCE', $fees);
        $this->assertArrayHasKey('DIRECT_DEBIT_CREDIT_CARD', $fees);
        $this->assertArrayHasKey('DIRECT_DEBIT_DEBIT_CARD', $fees);
        $this->assertArrayHasKey('VIRTUAL_ACCOUNT', $fees);
        $this->assertArrayHasKey('ONLINE_CREDIT', $fees);
    }

    public function test_can_calculate_mdr_for_balance(): void
    {
        $calculation = new Calculation();
        $result = $calculation->calculateMDR(100000, 'BALANCE');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('mdr_percent', $result);
        $this->assertArrayHasKey('mdr_before_tax', $result);
        $this->assertArrayHasKey('mdr_include_tax', $result);
        $this->assertArrayHasKey('tax_percent', $result);
        $this->assertArrayHasKey('tax', $result);
        $this->assertArrayHasKey('payment_method', $result);
        $this->assertArrayHasKey('settle_amount', $result);
        $this->assertEquals('BALANCE', $result['payment_method']);
        $this->assertEquals(0.012, $result['mdr_percent']);
    }

    public function test_can_calculate_mdr_for_credit_card(): void
    {
        $calculation = new Calculation();
        $result = $calculation->calculateMDR(100000, 'CREDIT_CARD');
        
        $this->assertIsArray($result);
        $this->assertEquals('CREDIT_CARD', $result['payment_method']);
        $this->assertEquals(0.018, $result['mdr_percent']);
    }
} 