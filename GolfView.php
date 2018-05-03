<?php

	class GolfView {
		private $stylesheet = 'GameTable.css';
		private $pageTitle = 'Golf Tracker';
		
		public function __construct() {

		}
		
		public function __destruct() {

		}
		
		public function gameListView($user, $games, $averages, $orderBy = 'title', $orderDirection = 'asc', $numOfGames, $message = '') {
			$body = "<h1>Golf Tracker for {$user->firstName} {$user->lastName}</h1>\n";
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
        
			$body .= "<p><a class='golfButton' style='margin-left: 6px;' href='index.php?view=gameform'> + Add Game </a><a class='golfButton' style='margin-left: 6px;' href='index.php?logout=1'> Logout </a><a class='gamesPlayed' style='margin-left: 6px;'> Games Played: $numOfGames </a></p>\n";
	
			/*if (count($games) < 1) {
				$body .= "<p>No games to display!</p>\n";
				return $this->page($body);
			}*/
	
			$body .= "<table>\n";
			$body .= "<tr><th>Delete</th><th>Edit</th>";
		
			$columns = array(array('name' => 'addDate', 'label' => 'Date Played'), 
							 array('name' => 'hole1', 'label' => 'Hole 1'), 
							 array('name' => 'hole2', 'label' => 'Hole 2'),
							 array('name' => 'hole3', 'label' => 'Hole 3'),
							 array('name' => 'hole4', 'label' => 'Hole 4'),
							 array('name' => 'hole5', 'label' => 'Hole 5'),
							 array('name' => 'hole6', 'label' => 'Hole 6'),
							 array('name' => 'hole7', 'label' => 'Hole 7'),
							 array('name' => 'hole8', 'label' => 'Hole 8'),
							 array('name' => 'hole9', 'label' => 'Hole 9'),
							 array('name' => 'total', 'label' => 'Total'));
		
			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  
					} else {
						$label .= " &#x25B2;"; 
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}
         
			foreach ($games as $game) {
				$id = $game['id'];
				$addDate=$game['addDate'];
				$hole1 = $game['hole1'];
				$hole2 = $game['hole2'];
				$hole3 = $game['hole3'];
				$hole4 = $game['hole4'];
				$hole5 = $game['hole5'];
				$hole6 = $game['hole6'];
				$hole7 = $game['hole7'];
				$hole8 = $game['hole8'];
				$hole9 = $game['hole9'];
				$total = end($game);
           
				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$addDate</td><td>$hole1</td><td>$hole2</td><td>$hole3</td><td>$hole4</td><td>$hole5</td><td>$hole6</td><td>$hole7</td><td>$hole8</td><td>$hole9</td><td>$total</td>";
				$body .= "</tr>\n";
			}

			$avg1 = round($averages['AVG(hole1)'], 2);
			$avg2 = round($averages['AVG(hole2)'], 2);
			$avg3 = round($averages['AVG(hole3)'], 2);
			$avg4 = round($averages['AVG(hole4)'], 2);
			$avg5 = round($averages['AVG(hole5)'], 2);
			$avg6 = round($averages['AVG(hole6)'], 2);
			$avg7 = round($averages['AVG(hole7)'], 2);
			$avg8 = round($averages['AVG(hole8)'], 2);
			$avg9 = round($averages['AVG(hole9)'], 2);
			$totalAvg = round($averages['SUM(hole1+hole2+hole3+hole4+hole5+hole6+hole7+hole8+hole9)/COUNT(*)'],2);
                        
            $body .= "<tr class='average'><td></td><td></td><td> Hole Average</td><td>$avg1</td><td>$avg2</td><td>$avg3</td><td>$avg4</td><td>$avg5</td><td>$avg6</td><td>$avg7</td><td>$avg8</td><td>$avg9</td><td>$totalAvg</td></tr>";
			$body .= "</table>\n";
	
			return $this->page($body);
		}
		
		public function gameFormView($user, $data = null, $message = '') {
			$hole1 = '';
			$hole2 = '';
			$hole3 = '';
            $hole4 = '';
			$hole5 = '';
			$hole6 = '';
            $hole7 = '';
			$hole8 = '';
			$hole9 = '';

			if ($data) {
				$hole1 = $data['hole1'];
				$hole2 = $data['hole2'];
				$hole3 = $data['hole3'];
				$hole4 = $data['hole4'];
				$hole5 = $data['hole5'];
				$hole6 = $data['hole6'];
				$hole7 = $data['hole7'];
				$hole8 = $data['hole8'];
				$hole9 = $data['hole9'];
			}
            

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
		
			$body .= "<form action='index.php' method='post' class= 'scorecard'>";
		
			if ($data['id']) {
				$body .= "<input type='hidden' name='action' value='update' />";
				$body .= "<input type='hidden' name='id' value='{$data['id']}' />";
			} else {
				$body .= "<input type='hidden' name='action' value='add' />";
			}
		
			$body .= <<<EOT2
        
  <h1>Scorecard<br /> </h1>
<p>Hole 1 <br />
  <input type="text" name="hole1" value="$hole1" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 2<br />
  <input type="text" name="hole2" value="$hole2" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 3<br />
  <input type="text" name="hole3" value="$hole3" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 4<br />
  <input type="text" name="hole4" value="$hole4" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 5<br />
  <input type="text" name="hole5" value="$hole5" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 6<br />
  <input type="text" name="hole6" value="$hole6" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 7<br />
  <input type="text" name="hole7" value="$hole7" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 8<br />
  <input type="text" name="hole8" value="$hole8" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 9<br />
  <input type="text" name="hole9" value="$hole9" placeholder="score" maxlength="2" size="5"></p>
<input type="submit" name='submit' value="Submit"> <input type="submit" name='cancel' value="Cancel">
</form>
EOT2;

			return $this->page($body);
		}
		
		
		public function loginFormView($data = null, $message = '') {
			$loginID = '';
			if ($data) {
				$loginID = $data['loginid'];
			}
		
			$body = "<h1 class ='websiteInfo'>Golf Tracker</h1>\n";
			
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
			
			$body .= <<<EOT
<form action='index.php' method='post'><center>
<input type='hidden' name='action' value='login' class='alignmentOnly'   />
<p class='websiteInfo'>Username<br />
  <input type="text" name="loginid" value="$loginID" placeholder="login id" maxlength="64" size="35" class='alignmentOnly'></p>
<p class='websiteInfo'>Password<br />
  <input type="password" name="password" value="" placeholder="password" maxlength="64" size="35" class='alignmentOnly'></p>
  <input type="submit" name='submit' value="Login" class='alignmentOnly' ></center>
</form>	
EOT;
			
			return $this->page($body);
		}
		
		public function errorView($message) {	
			$body = "<h1>Golf Tracker</h1>\n";
			$body .= "<p>$message</p>\n";
			
			return $this->page($body);
		}
		
		private function page($body) {
			$html = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>{$this->pageTitle}</title>
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
</head>
<body>
$body
<p class='websiteInfo'>&copy; 2018 Golf Tracker Incoporated. All rights reserved.</p>
</body>
</html>
EOT;
			return $html;
		}

}