<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../classes/GameRepository.php");
require_once(__DIR__ . "/TestingDbConn.php");


final class GameTests extends TestCase
{
    /** @test */
    public function GetAllReturnsArrayOfGames(): void
    {
        $gameRepository = new GameRepository();
        $gameRepository->db = TestingDbConn::fillWithGames(TestingDbConn::getConnection());
        
        $result = $gameRepository->getAll();

        $this->assertEquals(2, count($result));
    }

    /** @test */
    public function GetWithIdReturnsSingleEntry(): void
    {
        $gameRepository = new GameRepository();
        $gameRepository->db = TestingDbConn::fillWithGames(TestingDbConn::getConnection());
        
        $result = $gameRepository->getById(1);

        $this->assertNotEmpty($result);
    }

    /** @test */
    public function CanAddAGame(): void
    {
        $gameRepository = new GameRepository();
        $gameRepository->db = TestingDbConn::getConnection();
        $expectedGame = new Game(0, "test", 0, "test.jpg", "test", "00-00-0000", 0, "https://www.test.com");

        $gameRepository->add(new Game(0, "test", 0, "test.jpg", "test", "00-00-0000", 0, "https://www.test.com"));
        
        $result = $gameRepository->getAll()[0];

        $this->assertEquals($expectedGame->gameName, $result->gameName);
        $this->assertEquals($expectedGame->thumbnail, $result->thumbnail);
        $this->assertEquals($expectedGame->isDisabled, $result->isDisabled);
        $this->assertEquals($expectedGame->price, $result->price);
        $this->assertEquals($expectedGame->releaseDate, $result->releaseDate);
        $this->assertEquals($expectedGame->downloadLink, $result->downloadLink);
        $this->assertEquals($expectedGame->description, $result->description);
    }

    /** @test */
    public function CanUpdateGame(): void
    {
        $gameRepository = new GameRepository();
        $gameRepository->db = TestingDbConn::fillWithGames(TestingDbConn::getConnection());
        $gameToUpdate = $gameRepository->getAll()[0];
        $expectedGame = new Game(0, "Ben10", $gameToUpdate->price, $gameToUpdate->thumbnail, $gameToUpdate->description, $gameToUpdate->releaseDate, $gameToUpdate->isDisabled, $gameToUpdate->downloadLink);

        $gameToUpdate->gameName = "Ben10";
        $gameRepository->update($gameToUpdate);

        $result = $gameRepository->getById($gameToUpdate->gameId);

        $this->assertEquals($expectedGame->gameName, $result->gameName);
    }
}
