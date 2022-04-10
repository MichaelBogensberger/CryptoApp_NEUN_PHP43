<?php

require_once 'DatabaseObject.php';

class Purchase implements DatabaseObject, JsonSerializable
{
    private $id;
    private $date;
    private $amount;
    private $price;
    //private $currency;
    private $wallet_id;

    private $errors = [];

    public function validate()
    {
        return $this->validateDate() & $this->validateAmount() & $this->validatePrice();
    }

    /**
     * create or update an object
     * @return boolean true on success
     */
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

    /**
     * Creates a new object in the database
     * @return integer ID of the newly created object (lastInsertId)
     */
    public function create()
    {
        $db = Database::connect();
        $sql = "INSERT INTO purchase (date, amount, price, wallet_id) values(?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->date, $this->amount, $this->price, $this->wallet_id));
        $lastId = $db->lastInsertId();
        Database::disconnect();
        return $lastId;
    }

    /**
     * Saves the object to the database
     */
    public function update()
    {
        $db = Database::connect();
        $sql = "UPDATE purchase set date = ?, amount = ?, price = ?, wallet_id = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->date, $this->amount, $this->price, $this->wallet_id, $this->id));
        Database::disconnect();
    }

    /**
     * Get an object from database
     * @param integer $id
     * @return object single object or null
     */
    public static function get($id)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM purchase WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        $item = $stmt->fetchObject('Purchase');  // ORM
        Database::disconnect();
        return $item !== false ? $item : null;
    }

    /**
     * Get an array of objects from database
     * @return array array of objects or empty array
     */
    public static function getAll()
    {
        $db = Database::connect();
        $sql = 'SELECT * FROM purchase ORDER BY date DESC';
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // fetch all datasets (rows), convert to array of Purchase-objects (ORM)
        $items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Purchase');

        Database::disconnect();

        return $items;
    }


    /**
     * Get an array of objects from database
     * @param $currency
     * @return array array of objects or empty array
     */
    public static function getAllGroupByCurrency($currency = '')
    {
        // TODO
        return [];
    }

    /**
     * Deletes the object from the database
     * @param integer $id
     */
    public static function delete($id)
    {
        $db = Database::connect();
        $sql = "DELETE FROM purchase WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        Database::disconnect();
    }

    private function validateDate() {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $this->date);
        if ($d && $d->format('Y-m-d H:i:s') == $this->date) {
            return true;
        } else {
            $this->errors['date'] = "Ungueltiges Datum";
            return false;
        }
    }

    private function validateAmount() {
        if (!is_numeric($this->amount) || $this->amount <= 0) {
            $this->errors['amount'] = "Menge ungueltig";
            return false;
        } else {
            unset($this->errors['amount']);
            return true;
        }
    }

    private function validatePrice() {
        if (!is_numeric($this->price) || $this->price <= 0) {
            $this->errors['price'] = "Preis ungueltig";
            return false;
        } else {
            unset($this->errors['price']);
            return true;
        }
    }

    /*
    private function validateCurrency() {
        if (strlen($this->currency) == 0) {
            $this->errors['currency'] = "Waehrung ungueltig";
            return false;
        } else if (strlen($this->currency) > 32) {
            $this->errors['currency'] = "Waehrung zu lang (max. 32 Zeichen)";
            return false;
        } else {
            unset($this->errors['currency']);
            return true;
        }
    } */

    /**
     * define attributes which are part of the json output
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            "id" => intval($this->id),
            "date" => $this->date,
            "amount" => doubleval($this->amount),
            "price" => doubleval($this->price),
            "wallet_id" => doubleval($this->wallet_id),
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /*

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    */
    

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return array
     */
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




    public function getWalletId()
    {
        return $this->wallet_id;
    }


    public function setWalletId($wallet_id)
    {
        $this->wallet_id = $wallet_id;
    }



}
