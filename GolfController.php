<?php
	require('GolfModel.php');
	require('GolfView.php');

	class GolfController {
		private $model;
		private $views;
		
		private $orderBy = '';
		private $view = '';
		private $action = '';
		private $message = '';
		private $data = array();
	
		public function __construct() {
			$this->model = new GolfModel();
			$this->views = new GolfView();
			
			$this->view = $_GET['view'] ? $_GET['view'] : 'gamelist';
			$this->action = $_POST['action'];
		}
		
		public function __destruct() {
			$this->model = null;
			$this->views = null;
		}
		
		public function run() {
			if ($error = $this->model->getError()) {
				print $views->errorView($error);
				exit;
			}
			
			// Note: given order of handling and given processOrderBy doesn't require user to be logged in
			//...orderBy can be changed without being logged in
			$this->processOrderBy();
			
			$this->processLogout();

			switch($this->action) {
				case 'login':
					$this->handleLogin();
					break;
				case 'delete':
					$this->handleDelete();
					break;
				case 'add':
					$this->handleAddGame();
					break;
				case 'edit':
					$this->handleEditGame();
					break;
				case 'update':
					$this->handleUpdateGame();
					break;
				default:
					$this->verifyLogin();
			}
			
			switch($this->view) {
				case 'loginform': 
					print $this->views->loginFormView($this->data, $this->message);
					break;
				case 'gameform':
					print $this->views->gameFormView($this->model->getUser(), $this->data, $this->message);
					break;
				default: // 'golflist'
					list($orderBy, $orderDirection) = $this->model->getOrdering();
					list($games, $error) = $this->model->getGames();
					list($averages, $error) = $this->model->getAverages();
					$numOfGames = $this->model->getTotalGames();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->gameListView($this->model->getUser(), $games, $averages, $orderBy, $orderDirection, $numOfGames, $this->message);
			}
		
		}
		
		private function verifyLogin() {
			if (! $this->model->getUser()) {
				$this->view = 'loginform';
				return false;
			} else {
				return true;
			}
		}
		
		private function processOrderby() {
			if ($_GET['orderby']) {
				$this->model->toggleOrder($_GET['orderby']);
			}			
		}
		
		private function processLogout() {
			if ($_GET['logout']) {
				$this->model->logout();
			}
		}
		
		private function handleLogin() {
			$loginID = $_POST['loginid'];
			$password = $_POST['password'];
			
			list($success, $message) = $this->model->login($loginID, $password);
			if ($success) {
				$this->view = 'gamelist';
			} else {
				$this->message = $message;
				$this->view = 'loginform';
				$this->data = $_POST;
            }
		}
		
		private function handleDelete() {
			if (!$this->verifyLogin()) return;
		
			if ($error = $this->model->deleteGame($_POST['id'])) {
				$this->message = $error;
			}
			$this->view = 'gamelist';
		}
		
		
		private function handleAddGame() {
			if (!$this->verifyLogin()) return;
			
			if ($_POST['cancel']) {
				$this->view = 'gamelist';
				return;
			}
			
			$error = $this->model->addGame($_POST);
			if ($error) {
				$this->message = $error;
				$this->view = 'gameform';
				$this->data = $_POST;
			}
		}
		
		private function handleEditGame() {
			if (!$this->verifyLogin()) return;
			
			list($game, $error) = $this->model->getGame($_POST['id']);
			if ($error) {
				$this->message = $error;
				$this->view = 'gamelist';
				return;
			}
			$this->data = $game;
			$this->view = 'gameform';

		}
		
		private function handleUpdateGame() {
			if (!$this->verifyLogin()) return;
			if ($_POST['cancel']) {
				$this->view = 'gamelist';
				return;
			}
			
			if ($error = $this->model->updateGame($_POST)) {
				$this->message = $error;
				$this->view = 'gameform';
				$this->data = $_POST;
				return;
			}
			
			$this->view = 'gamelist';
		}
	}
?>