<?php
$table1 = [
    [
        "id" => 1,
        "date" => '2012-01-01',
        "engine" => "google",
        "searchTerm" => "widgets",
        "ranking" => 7
    ], [
        "id" => 2,
        "date" => '2012-01-01',
        "engine" => "yahoo",
        "searchTerm" => "widgets",
        "ranking" => 6
    ], [
        "id" => 3,
        "date" => '2012-01-01',
        "engine" => "bing",
        "searchTerm" => "widgets",
        "ranking" => 8
    ], [
        "id" => 4,
        "date" => '2012-01-01',
        "engine" => "google",
        "searchTerm" => "green widgets",
        "ranking" => 20
    ], [
        "id" => 5,
        "date" => '2012-01-01',
        "engine" => "yahoo",
        "searchTerm" => "green widgets",
        "ranking" => 0
    ], [
        "id" => 6,
        "date" => '2012-01-01',
        "engine" => "bing",
        "searchTerm" => "green widgets",
        "ranking" => 29
    ]
];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            table, tr {
                padding: 0;
                width: 100%;
                border-bottom:1px solid black;
            }
            td {
                padding: 5px;
                border-top:1px solid black;
                border-left:1px solid black;
            }
            td:last-child{
                border-right:1px solid black;
            }
        </style>
    </head>
    <body>
        <h3>table 1</h3>
        <table cellpadding="0" cellspacing="0">
            <tr><td>id</td><td>date</td><td>engine</td><td>searchTerm</td><td>ranking</td></tr>
            <?php
            foreach ($table1 as $r) {
                echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%d</td></tr>', $r["id"], $r["date"], $r["engine"], $r["searchTerm"], $r["ranking"]);
            }
            ?>
        </table>
        <h3>table 2</h3>
        <table cellpadding="0" cellspacing="0">
            <tr><td>id</td><td>date</td><td>searchTerm</td><td>visibility</td></tr>
            <?php
            $startTime = microtime(true);
            for ($i = 0; $i < 1000000; $i++) {
                $table2Data = calculateTable2::generateData($table1);
            }
            echo "Time: " . number_format(( microtime(true) - $startTime), 4) . " Seconds\n";

            foreach ($table2Data as $r) {
                echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%01.1f</td></tr>', $r["id"], $r["date"], $r["searchTerm"], $r["visibility"]);
            }
            ?>
        </table>
    </body>
</html>
<?php
unset($table1);
unset($table2Data);

class calculateTable2 {

    const mulitplers = ["google" => 17, "yahoo" => 2, "bing" => 1];

    static function generateData(&$table1) {
        $proccessData = [];
        $searchTermRankings = [];
        foreach ($table1 as $row) {
            $searchTermRankings[$row["date"]][$row["searchTerm"]][$row["engine"]] = $row["ranking"];
        }
        foreach ($searchTermRankings as $d => $searchterms) {
            foreach ($searchterms as $searchterm => $ranks) {
                $proccessData[] = [
                    "id" => sizeof($proccessData) + 1,
                    "date" => $d,
                    "searchTerm" => $searchterm,
                    "visibility" => self::calculate($ranks)
                ];
            }
        }
        return $proccessData;
    }

    private static function calculate(Array &$ranks) {
        $sum = 0;
        foreach (self::mulitplers as $engine => $multipler) {
            $sum += (31 - self::getArrayValue($engine, $ranks)) * $multipler;
        }
        return ($sum / 600) * 100;
    }

    private static function getArrayValue(string $key, array &$arr) {
        return (array_key_exists($key, $arr) ? $arr[$key] : 0);
    }

}
?>