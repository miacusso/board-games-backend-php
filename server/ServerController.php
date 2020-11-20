<?php
require_once("DatabaseConnector.php");

class ServerController {

    private $dbConnector;

    public function __construct() {
        $this->dbConnector = new DatabaseConnector();
    }

    public function redirect($method, $body) {
        $game = $method["game"];
        $action = $method["action"];
        switch ($action) {
            case "players" :
                return $this->getGamePlayers($game);
            case "result-table" :
                return $this->getGameResults($game);
            case "winner" :
                $winner = $body->winner->id;
                return $this->setGameResult($winner, $game);
            case "delete-result-table" :
                return $this->delGameResults($game);
        }
    }

    public function getGamePlayers($game) {
        return $this->dbConnector->retrievePlayersForGame($game);
    }

    public function getGameResults($game) {
        return $this->dbConnector->retrieveResultsCountForGame($game);
    }

    public function setGameResult($winner, $game) {
        return $this->dbConnector->insertGameResult($winner, $game);
    }

    public function delGameResults($game) {
        return $this->dbConnector->removeResultsForGame($game);
    }
}
?>