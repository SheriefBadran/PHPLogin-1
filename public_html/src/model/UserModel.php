<?php

	class UserModel {

		private $userId;
		private $uniqueId;
		private $username;
		private $password;

		public function __construct ($uniqueId, $username, $password) {

			if (preg_match('/\W/', $username) === 1) {

				throw new \Exception('Username contains invalid characters.');
			}

			$this->uniqueId = $uniqueId;
			$this->username = preg_replace('/\W/', '', $username);
			$this->password = $password;
		}

		public function getUniqueId () {

			return $this->uniqueId;
		}

		public function getUsername () {

			return $this->username;
		}

		public function getPassword () {

			return $this->password;
		}
	}