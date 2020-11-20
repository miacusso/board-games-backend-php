<?php
class DatabaseConnector {

    private $servername;
    private $database;
    private $username;
    private $password;

    public function __construct() {
        $this->servername = "localhost";
        $this->database = "id15411513_board_games";
        $this->username = "id15411513_miacusso";
        $this->password = 'Maximiliano1acu$$o';
    }

    private function connect() {
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            return $conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function retrievePlayersForGame($game) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM players WHERE (game = :game)");
        $stmt->bindParam(':game', $game);
        $stmt->execute();

        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $conn = null;

        return $stmt->fetchAll();
    }

    public function retrieveResultsCountForGame($game) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT players.name, COUNT(winner) as wins FROM players LEFT JOIN results ON players.id = results.winner WHERE (players.game = :game) GROUP BY players.name;");
        $stmt->bindParam(':game', $game);
        $stmt->execute();

        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $conn = null;

        $response = [];
        foreach ($stmt->fetchAll() as $e) {
            $response[$e["name"]] = $e["wins"];
        }

        return $response;
    }

    public function insertGameResult($winner, $game) {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO results(date, winner, game) VALUES (:date, :winner, :game)");
        $now = date('Y-m-d H:i:s');
        $stmt->bindParam(':date', $now);
        $stmt->bindParam(':winner', $winner);
        $stmt->bindParam(':game', $game);
        $stmt->execute();

        return "OK";
    }

    public function removeResultsForGame($game) {
        $conn = $this->connect();
        $stmt = $conn->prepare("DELETE FROM results WHERE (game = :game)");
        $stmt->bindParam(':game', $game);
        $stmt->execute();

        return "OK";
    }
}
?>