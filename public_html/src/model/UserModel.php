<?php

	class UserModel {

		private $userId;
		private $uniqueId;
		private $username;
		private $password;

		public function __construct ($uniqueId, $username, $password) {

			$this->uniqueId = $uniqueId;
			$this->username = $username;
			$this->password = $password;
		}

		public function getUnique () {

			return $this->uniqueId;
		}

		public function getUsername () {

			return $this->username;
		}

		public function getPassword () {

			return $this->password;
		}
	}