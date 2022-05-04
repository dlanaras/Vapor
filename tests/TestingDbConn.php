<?php

class TestingDbConn { //IMPORTANT: CANNOT TEST WITH PROCEDURES BECAUSE SQLITE DOESNT SUPPORT THAT
    static function getConnection() {
        try {
            $db = new PDO("sqlite:" . __DIR__ . "/mysqlite3.db");
            $db->exec("
                DELETE FROM account_tbl;
                DELETE FROM game_tbl;
                DELETE FROM achievement_tbl;
            ");
            return $db;
        } catch(Exception $e) {
            echo "error with creating DB-Connection: $e";
        }
    }

    static function fillWithAccounts($db) {
        $db->exec("
        insert into account_tbl (Id, username, firstName, lastName, Email, isAdmin, biography, isBanned, password) values (1, 'kstlouis0', 'Katheryn', 'St Louis', 'kstlouis0@stumbleupon.com', false, 'Ext fistulizat esoph NEC', true, '0EKQ52QsSaZ');
        insert into account_tbl (Id, username, firstName, lastName, Email, isAdmin, biography, isBanned, password) values (2, 'fgard1', 'Fidelia', 'Gard', 'fgard1@cnn.com', false, 'Opn abltn liver les/tiss', false, 'gKz9neANx90j');
        ");
        
        return $db;
    }

    static function fillWithGames($db) {
        $db->exec("
        insert into game_tbl (Id, name, description, isDisabled, thumbnail, price, releaseDate, downloadLink) values (1, 'nascetur ridiculus mus', 'Autonomic dysreflexia', false, 'VolutpatInCongue.avi', 173.87, '2/28/2022', 'http://eepurl.com/nec/condimentum/neque/sapien/placerat/ante.jsp?condimentum=volutpat');
insert into game_tbl (Id, name, description, isDisabled, thumbnail, price, releaseDate, downloadLink) values (2, 'at velit eu', 'Displaced fracture of intermediate cuneiform of left foot', true, 'PurusSitAmet.jpeg', 372.61, '9/17/2021', 'https://zdnet.com/ac/est/lacinia/nisi/venenatis.html?tempus=eu&vivamus=magna');
        ");

        return $db;
    }

    static function fillWithAchievements($db) {
        $db->exec("
        insert into achievement_tbl (Id, name, description, isDisabled, thumbnail, game_Id) values (1, 'commodo placerat', 'Other age-related incipient cataract, bilateral', false, 'PosuereCubiliaCurae.tiff', 1);
insert into achievement_tbl (Id, name, description, isDisabled, thumbnail, game_Id) values (2, 'ligula vehicula consequat', 'Other neurofibromatosis', true, 'Amet.jpeg', 2);
        ");

        return $db;
    }
}
