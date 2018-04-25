<?php

	class GolfView {
		private $stylesheet = 'GolfStyleSheet.css';
		private $pageTitle = 'Golf Tracker';
		
		public function __construct() {

		}
		
		public function __destruct() {

		}
		
		public function gameListView($user, $games, $orderBy = 'title', $orderDirection = 'asc', $message = '') {
			$body = "<h1>Golf Tracker for {$user->firstName} {$user->lastName}</h1>\n";
		
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
		
			$body .= "<p><a class='taskButton' href='index.php?view=gameform'>+ Add Game</a> <a class='taskButton' href='index.php?logout=1'>Logout</a></p>\n";
	
			if (count($games) < 1) {
				$body .= "<p>No games to display!</p>\n";
				return $this->page($body);
			}
	
			$body .= "<table>\n";
			$body .= "<tr><th>Delete</th><th>Edit</th>";
		
			$columns = array(array('name' => 'addDate', 'label' => 'Add Date'), 
							 array('name' => 'gameNumber', 'label' => 'Game Number'), 
							 array('name' => 'hole1', 'label' => 'Hole 1'), 
							 array('name' => 'hole2', 'label' => 'Hole 2'),
							 array('name' => 'hole3', 'label' => 'Hole 3'),
							 array('name' => 'hole4', 'label' => 'Hole 4'),
							 array('name' => 'hole5', 'label' => 'Hole 5'),
							 array('name' => 'hole6', 'label' => 'Hole 6'),
							 array('name' => 'hole7', 'label' => 'Hole 7'),
							 array('name' => 'hole8', 'label' => 'Hole 8'),
							 array('name' => 'hole9', 'label' => 'Hole 9'));
		
			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ÃÂ¢ÃÂÃÂ¼
					} else {
						$label .= " &#x25B2;";  // ÃÂ¢ÃÂÃÂ²
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
			
			
				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$id</td><td>$addDate</td><td>$hole1</td><td>$hole2</td><td>$hole3</td><td>$hole4</td><td>$hole4</td><td>$hole5</td><td>$hole6</td><td>$hole7</td><td>$hole8</td><td>$hole9</td>";
				$body .= "</tr>\n";
			}
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
            
           
			$body = "<h1>Games for {$user->firstName} {$user->lastName}</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
		
			$body .= "<form action='index.php' method='post'>";
		
			if ($data['id']) {
				$body .= "<input type='hidden' name='action' value='update' />";
				$body .= "<input type='hidden' name='id' value='{$data['id']}' />";
			} else {
				$body .= "<input type='hidden' name='action' value='add' />";
			}
		
			$body .= <<<EOT2
        
  <h1>Scorecard<br /> </h1>
<p>Hole 1 <br />
  <input type="text" name="title" value="$hole1" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 2<br />
  <input type="text" name="title" value="$hole2" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 3<br />
  <input type="text" name="title" value="$hole3" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 4<br />
  <input type="text" name="title" value="$hole4" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 5<br />
  <input type="text" name="title" value="$hole5" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 6<br />
  <input type="text" name="title" value="$hole6" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 7<br />
  <input type="text" name="title" value="$hole7" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 8<br />
  <input type="text" name="title" value="$hole8" placeholder="score" maxlength="2" size="5"></p>

<p>Hole 9<br />
  <input type="text" name="title" value="$hole9" placeholder="score" maxlength="2" size="5"></p>
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
		
			$body = "<h1>Golf Tracker :)</h1>\n";
			
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
			
			$body .= <<<EOT
<form action='index.php' method='post'>
<input type='hidden' name='action' value='login' />
<p>Username<br />
  <input type="text" name="loginid" value="$loginID" placeholder="login id" maxlength="255" size="80"></p>
<p>Password<br />
  <input type="password" name="password" value="" placeholder="password" maxlength="255" size="80"></p>
  <input type="submit" name='submit' value="Login">
</form>	
EOT;
			
			return $this->page($body);
		}
		
		public function errorView($message) {	
			$body = "<h1>Tasks</h1>\n";
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
<p>&copy; 2018 Golf Tracker Incoporated. All rights reserved.</p>
</body>
</html>
EOT;
			return $html;
		}

}