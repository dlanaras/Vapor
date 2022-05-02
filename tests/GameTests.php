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
        $result = $gameRepository->getById(1);

        $this->assertNotEmpty($result);
    }
}