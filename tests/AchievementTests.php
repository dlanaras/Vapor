<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../classes/AchievementRepository.php");
require_once(__DIR__ . "/../classes/GameRepository.php");
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

    /** @test */
    public function CanAddAnAchievement(): void
    {
        $achievementRepository = new AchievementRepository();
        $gameRepository = new GameRepository();
        $achievementRepository->db = TestingDbConn::fillWithGames(TestingDbConn::getConnection());
        $achievementGame = $gameRepository->getAll()[0];
        $expectedAchievement = new Achievement(0, "test", "test", 0, "test.jpg", $achievementGame->gameId);

        $achievementRepository->add(new Achievement(0, "test", "test", 0, "test.jpg", $achievementGame->gameId));
        
        $result = $achievementRepository->getAll()[0];

        $this->assertEquals($expectedAchievement->achievementName, $result->achievementName);
        $this->assertEquals($expectedAchievement->thumbnail, $result->thumbnail);
        $this->assertEquals($expectedAchievement->isDisabled, $result->isDisabled);
        $this->assertEquals($expectedAchievement->description, $result->description);
        $this->assertEquals($expectedAchievement->gameId, $result->gameId);
    }

    /** @test */
    public function CanUpdateAchievement(): void
    {
        $achievementRepository = new AchievementRepository();
        $achievementRepository->db = TestingDbConn::fillWithGames(TestingDbConn::fillWithAchievements(TestingDbConn::getConnection()));
        $achievementToUpdate = $achievementRepository->getAll()[0];
        $expectedAchievement = new Achievement(0, "Become Ben10", $achievementToUpdate->description, $achievementToUpdate->isDisabled, $achievementToUpdate->thumbnail, $achievementToUpdate->gameId);

        $achievementToUpdate->achievementName = "Become Ben10";
        $achievementRepository->update($achievementToUpdate);

        $result = $achievementRepository->getById($achievementToUpdate->achievementId);

        $this->assertEquals($expectedAchievement->achievementName, $result->achievementName);
    }

}