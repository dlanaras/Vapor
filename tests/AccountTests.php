<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../classes/AccountRepository.php");
require_once(__DIR__ . "/TestingDbConn.php");


final class AccountTests extends TestCase
{
    /** @test */
    public function GetAllReturnsArrayOfAccounts(): void
    {
        $accountRepository = new AccountRepository();
        $accountRepository->db = TestingDbConn::fillWithAccounts(TestingDbConn::getConnection());
        $result = $accountRepository->getAll();

        $this->assertEquals(2, count($result));
    }

    /** @test */
    public function GetWithIdReturnsSingleEntry(): void
    {
        $accountRepository = new AccountRepository();
        $result = $accountRepository->getById(1);

        $this->assertNotEmpty($result);
    }
}
