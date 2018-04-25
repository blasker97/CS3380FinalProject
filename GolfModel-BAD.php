<?php

	// NOTE: this version has beginning of support for logging in and supporting
	// multiple users.  This version does not yet have the database table for users
	// or the change to the database so that each task is affiliated with a user.

	require('User.php');

	class GolfModel {
		private $error = '';
		private $mysqli;
		private $orderBy = 'title';
		private $orderDirection = 'asc';
		private $user;
		
		public function __construct() {
			session_start();
			$this->initDatabaseConnection();
			$this->restoreOrdering();
			$this->restoreUser();
		}
		
		public function __destruct() {
			if ($this->mysqli) {
				$this->mysqli->close();
			}
		}
		
		public function getError() {
			return $this->error;
		}
		
		private function initDatabaseConnection() {
			require('db_credentials.php');
			$this->mysqli = new mysqli($servername, $username, $password, $dbname);
			if ($this->mysqli->connect_error) {
				$this->error = $mysqli->connect_error;
			}
		}
		
		private function restoreOrdering() {
			$this->orderBy = $_SESSION['orderby'] ? $_SESSION['orderby'] : $this->orderBy;
			$this->orderDirection = $_SESSION['orderdirection'] ? $_SESSION['orderdirection'] : $this->orderDirection;
		
			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}
		
		private function restoreUser() {
			if ($loginID = $_SESSION['loginid']) {
				$this->user = new User();
				if (!$this->user->load($loginID, $this->mysqli)) {
					$this->user = null;
				}
			}
		}
		
		public function getUser() {
			return $this->user;
		}
		
		public function login($loginID, $password) {
			// check if loginID and password are valid by comparing
			// encrypted version of password to encrypted password stored
			// in database for user with loginID
			
			$user = new User();
			if ($user->load($loginID, $this->mysqli) && password_verify($password, $user->hashedPassword)) {
				$this->user = $user;
				$_SESSION['loginid'] = $loginID;
				return array(true, "");
			} else {
				$this->user = null;
				$_SESSION['loginid'] = '';
				return array(false, "Invalid login information.  Please try again.");
			}
		}
		
		public function logout() {
			$this->user = null;
			$_SESSION['loginid'] = '';
		}
	
		public function toggleOrder($orderBy) {
			if ($this->orderBy == $orderBy)	{
				if ($this->orderDirection == 'asc') {
					$this->orderDirection = 'desc';
				} else {
					$this->orderDirection = 'asc';
				}
			} else {
				$this->orderDirection = 'asc';
			}
			$this->orderBy = $orderBy;
			
			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;			
		}
		
		public function getOrdering() {
			return array($this->orderBy, $this->orderDirection);
		}
		
		public function getGames() {
			$this->error = '';
			$games = array();
			
			if (!$this->user) {
				$this->error = "User not specified. Unable to get task.";
				return $this->error;
			}
		
			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($games, $this->error);
			}
		
			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);

			$sql = "SELECT * FROM games WHERE userID = $userIDEscaped ORDER BY $orderByEscaped $orderDirectionEscaped";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						array_push($games, $row);
					}
				}
				$result->close();
			} else {
				$this->error = $mysqli->error;
			}
			
			return array($games, $this->error);
		}
		
		public function getGame($id) {
			$this->error = '';
			$game = null;
			
			if (!$this->user) {
				$this->error = "User not specified. Unable to get task.";
				return $this->error;
			}
		
			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($game, $this->error);
			}
			
			if (! $id) {
				$this->error = "No id specified for task to retrieve.";
				return array($game, $this->error);
			}
			
			$idEscaped = $this->mysqli->real_escape_string($id);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);

			$sql = "SELECT * FROM games WHERE userID = $userIDEscaped AND id = '$idEscaped'";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					$game = $result->fetch_assoc();
				}
				$result->close();
			} else {
				$this->error = $this->mysqli->error;
			}
			
			return array($game, $this->error);		
		}
		
		public function addGame($data) {
			$this->error = '';
			
			if (!$this->user) {
				$this->error = "User not specified. Unable to add game.";
				return $this->error;
			}
			
			$hole1 = $data['hole1'];
			$hole2 = $data['hole2'];
			$hole3 = $data['hole3'];
			$hole4 = $data['hole4'];
			$hole5 = $data['hole5'];
			$hole6 = $data['hole6'];
			$hole7 = $data['hole7'];
			$hole8 = $data['hole8'];
			$hole9 = $data['hole9'];
			
			
			/*if (! $hole1 || ! $hole2 || ! $hole3 || ! $hole4 || ! $hole5 || ! $hole6 || ! $hole7 || ! $hole8 || ! $hole9) {
				$this->error = "Game not added. All holes must be recorded.";
				return $this->error;			
			}*/
			
			
			$hole1Escaped = $this->mysqli->real_escape_string($hole1);
			$hole2Escaped = $this->mysqli->real_escape_string($hole2);
			$hole3Escaped = $this->mysqli->real_escape_string($hole3);
			$hole4Escaped = $this->mysqli->real_escape_string($hole4);
			$hole5Escaped = $this->mysqli->real_escape_string($hole5);
			$hole6Escaped = $this->mysqli->real_escape_string($hole6);
			$hole7Escaped = $this->mysqli->real_escape_string($hole7);
			$hole8Escaped = $this->mysqli->real_escape_string($hole8);
			$hole9Escaped = $this->mysqli->real_escape_string($hole9);		
			

			$sql = "INSERT INTO games(hole1, hole2, hole3, hole4, hole5, hole6, hole7, hole8, hole9, datePlayed) VALUES ('$hole1Escaped', '$hole2Escaped', '$hole3Escaped', '$hole4Escaped', '$hole5Escaped', '$hole6Escaped', '$hole7Escaped', '$hole8Escaped', '$hole9Escaped', NOW())";
	
			if (! $result = $this->mysqli->query($sql)) {
				$this->error = $this->mysqli->error;
			}
			
			return $this->error;
		}
		
		public function updateGameCompletionStatus($id, $status) {
			$this->error = "";
			
			if (!$this->user) {
				$this->error = "User not specified. Unable to update task completion status.";
				return $this->error;
			}
		
			$completedDate = 'null';
			if ($status == 'completed') {
				$completedDate = 'NOW()';
			}
	
			if (!$id) {
				$this->error = "No task was specified to change completion status.";
			} else {
				$idEscaped = $this->mysqli->real_escape_string($id);
				$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);
				$sql = "UPDATE tasks SET completedDate = $completedDate WHERE userID = $userIDEscaped AND id = '$idEscaped'";
				if (! $result = $this->mysqli->query($sql) ) {
					$this->error = $this->mysqli->error;
				}
			}
	
			return $this->error;
		}
		
		public function updateGolf($data) {
			$this->error = '';
			
			if (!$this->user) {
				$this->error = "User not specified. Unable to update task.";
				return $this->error;
			}
			
			if (! $this->mysqli) {
				$this->error = "No connection to database. Unable to update task.";
				return $this->error;
			}
			
			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for task to update.";
				return $this->error;			
			}
			
			$title = $data['title'];
			if (! $title) {
				$this->error = "No title found for task to update. A title is required.";
				return $this->error;			
			}		
			
			$description = $data['description'];
			$category = $data['category'];
			
			$idEscaped = $this->mysqli->real_escape_string($id);
			$titleEscaped = $this->mysqli->real_escape_string($title);
			$descriptionEscaped = $this->mysqli->real_escape_string($description);
			$categoryEscaped = $this->mysqli->real_escape_string($category);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);
			$sql = "UPDATE games SET title='$titleEscaped', description='$descriptionEscaped', category='$categoryEscaped' WHERE userID = $userIDEscaped AND id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			} 
			
			return $this->error;
		}
		
		public function deleteGame($id) {
			$this->error = '';
			
			if (!$this->user) {
				$this->error = "User not specified. Unable to delete task.";
				return $this->error;
			}
			
			if (! $this->mysqli) {
				$this->error = "No connection to database. Unable to delete task.";
				return $this->error;
			}
			
			if (! $id) {
				$this->error = "No id specified for task to delete.";
				return $this->error;			
			}			
		
			$idEscaped = $this->mysqli->real_escape_string($id);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);
			$sql = "DELETE FROM games WHERE userID = $userIDEscaped AND id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}
			
			return $this->error;
		}

	
	}

?>