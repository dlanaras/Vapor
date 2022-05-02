<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../classes/AchievementRepository.php");
require_once(__DIR__ . "/TestingDbConn.php");


final class AchievementTests extends TestCase
{
    /** @test */
    public function GetAllReturnsArrayOfAccounts(): void
    {
        $achievementRepository = new AchievementRepository();
        $achievementRepository->db = TestingDbConn::fillWithAchievements(TestingDbConn::getConnection());
        $result = $achievementRepository->getAll();

        $this->assertEquals(2, count($result));
    }

    /** @test */
    public function GetWithIdReturnsSingleEntry(): void
    {
        $achievementRepository = new AchievementRepository();
        $achievementRepository->db = TestingDbConn::fillWithAchievements(TestingDbConn::getConnection());
        $result = $achievementRepository->getById(1);

        $this->assertNotNull($result);
    }
}