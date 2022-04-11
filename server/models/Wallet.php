<?php

require_once 'DatabaseObject.php';
require_once 'Purchase.php';

class Wallet implements DatabaseObject, JsonSerializable
{

    private $id;
    private $currency;
    private $name;
    private $amount;
    private $price;

    private $errors = [];

    public function validate()
    {
        
    }


    public function save()
    {
        if ($this->validate()) {
            if ($this->id != null && $this->id > 0) {
                $this->update();
            } else {
                $this->id = $this->create();
            }

            return true;
        }

        return false;
    }



    public function create()
    {
        $db = Database::connect();
        $sql = "INSERT INTO wallet (currency, name, amount, price) values(?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->currency, $this->name, $this->amount, $this->price));
        $lastId = $db->lastInsertId();
        Database::disconnect();
        return $lastId;

    }


    public function update()
    {
        $db = Database::connect();
        $sql = "UPDATE wallet set currency = ?, name = ?, amount = ?, price = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->currency, $this->name, $this->amount, $this->price, $this->id));
        Database::disconnect();
    }


    public static function getAll()
    {
        $db = Database::connect();
        $sql = 'SELECT * FROM wallet';
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // fetch all datasets (rows), convert to array of Purchase-objects (ORM)
        $items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Wallet');

        Database::disconnect();

        return $items;
    }


    public static function get($id)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM wallet WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        $item = $stmt->fetchObject('Wallet');  // ORM
        Database::disconnect();
        return $item !== false ? $item : null;
    }



    public static function delete($id)
    {
        $db = Database::connect();
        $sql = "DELETE FROM wallet WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        Database::disconnect();
    }



    public static function getAllPurchasesFromWallet($id) {
        $db = Database::connect();
        $sql = "SELECT * FROM `purchase` WHERE wallet_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));


        $items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Purchase');

        Database::disconnect();
        return empty($items) ? false : $items;
    }


    public function jsonSerialize()
    {
        return [
            "id" => intval($this->id),
            "currency" => $this->currency,
            "amount" => doubleval($this->amount),
            "name" => $this->name,
            "price" => doubleval($this->price),
        ];
    }



    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    


    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCurrency(){
		return $this->currency;
	}

	public function setCurrency($currency){
		$this->currency = $currency;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}












}