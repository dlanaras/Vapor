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
        $accountRepository->db = TestingDbConn::fillWithAccounts(TestingDbConn::getConnection());

        $result = $accountRepository->getById(1);

        $this->assertNotEmpty($result);
    }

    /** @test */
    public function CanAddAnAccount(): void
    {
        $accountRepository = new AccountRepository();
        $accountRepository->db = TestingDbConn::getConnection();
        $expectedAccount = new Account("test", 0, "test", "test", "test", 1, "", "test@test.test", 0);

        $accountRepository->add(new Account("test", 0, "test", "test", "test", 1, "", "test@test.test", 0));
        $result = $accountRepository->getAll()[0];

        $this->assertEquals($expectedAccount->firstName, $result->firstName);
        $this->assertEquals($expectedAccount->lastName, $result->lastName);
        $this->assertEquals($expectedAccount->email, $result->email);
        $this->assertEquals($expectedAccount->isBanned, $result->isBanned);
        $this->assertEquals($expectedAccount->isAdmin, $result->isAdmin);
        $this->assertEquals($expectedAccount->userName, $result->userName);
        $this->assertTrue(password_verify($expectedAccount->password, $result->password));
    }

    /** @test */
    public function CanUpdateAccount(): void
    {
        $accountRepository = new AccountRepository();
        $accountRepository->db = TestingDbConn::fillWithAccounts(TestingDbConn::getConnection());
        $accToUpdate = $accountRepository->getAll()[0];
        $expectedAccount = new Account("", $accToUpdate->accountId, "Ben", $accToUpdate->firstName, $accToUpdate->lastName, $accToUpdate->isAdmin, $accToUpdate->biography, $accToUpdate->email, $accToUpdate->isBanned);

        $accToUpdate->userName = "Ben";
        $accountRepository->update($accToUpdate);

        $result = $accountRepository->getById($accToUpdate->accountId);

        $this->assertEquals($expectedAccount->userName, $result->userName);
    }

    /** @test */
    public function AccountGetsBannedWhenBanningIt(): void
    {
        $accountRepository = new AccountRepository();
        $accountRepository->db = TestingDbConn::fillWithAccounts(TestingDbConn::getConnection());
        $accToBan = $accountRepository->getAll()[0];

        $accountRepository->ban($accToBan->accountId);

        $result = $accountRepository->getById($accToBan->accountId);

        $this->assertTrue((bool) $result->isBanned);
    }
}
