
<?php
require 'simple_html_dom.php';
$url = 'http://scores.espn.go.com/nhl/scoreboard';
$output = file_get_html($url); 

//$ret = $output->find('div[id=gamesLeft class=leader-value]');
$ret = $output->find('td[class=team-name], td[class=team-score]');
$table = $output->find('table[class=game-header-table]');
$team_img = $output->find('div[class=logo-small]');

$con = mysqli_connect("localhost","user","pass","table");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$date = date("Y-m-d h:i:sa");

$sc = $output->find('td[class=team-score]');

	foreach($table as $s){
			$link1 = $s->find('a',0)->plaintext;
			$link2 = $s->find('a',1)->plaintext;
			$img = $s->find('img');

			$sc = $s->find('td[class=team-score]');

			$html1 = $img[0];
			$html2 = $img[1];

			$doc1 = new DOMDocument();
			$doc2 = new DOMDocument();
			$doc1->loadHTML($html1);
			$doc2->loadHTML($html2);
			$xpath1 = new DOMXPath($doc1);
			$xpath2 = new DOMXPath($doc2);
			$src1 = $xpath1->evaluate("string(//img/@src)");
			$src2 = $xpath2->evaluate("string(//img/@src)");
			
			echo $src1 . " <br><br> " . $link1 . " " . $sc[0] . "<br><br>";
			echo $src2 . " <br><br> " . $link2 . " " . $sc[1] . "<br><br>";
			
			$get_team1 = "SELECT Id FROM Team WHERE Name = '$link1'";
			$get_team2 = "SELECT Id FROM Team WHERE Name = '$link2'";
			
			
			$result = $con->query($get_team1);
			$team1 = $result->fetch_assoc();
			$t1 = $team1["Id"];

			$result2 = $con->query($get_team2);
			$team2 = $result2->fetch_assoc();
			$t2 = $team2["Id"];
		
			
			$sql = "INSERT INTO games (team1, t1img, score1, team2, t2img, score2, date)
			VALUES ('".$link1."', '".$src1."','".$sc[0]->plaintext."','".$link2."','".$src2."', '".$sc[1]->plaintext."','".$date."')";

			
			if ($con->query($sql) === TRUE) {
	   			echo "good";
			} else {
			   echo "Error: " . $sql . "<br>" . $con->error;
			   
			}

	}


$con->close();

?>


