<?php

class dataSource {

	private $mongo_username = "";
	private $mongo_pw = "";
	private $mongo_database_name = "";
	private $mongo_url = "";
	private $mongo_port = "";
	private $mongo_connection;
	private $mongo_database;

	private function connect(){
		if(!$this->mongo_connection = new Mongo("mongodb://$this->mongo_username:$this->mongo_pw@$this->mongo_url:$this->mongo_port/$this->mongo_database_name")){
			throw new Exception("Unable to connect to database");
		}
                if(!$this->mongo_database = $this->mongo_connection->selectDB($this->mongo_database_name)){
			throw new Exception("Unable to select database");
		}
	}

	public function getCollection($collection){
		try {
			$this->connect();
		} catch(Exception $e) {
			echo "Caught Exception: ", $e->getMessage(), "\n";
		}
                $collection = $this->mongo_database->selectCollection($collection);
		return $collection;
	}

}

?>

