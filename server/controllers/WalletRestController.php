<?php

require_once('RESTController.php');
require_once('models/Wallet.php');

class WalletRESTController extends RESTController
{
    public function handleRequest()
    {
        switch ($this->method) {
            case 'GET':
                $this->handleGETRequest();
                break;
            case 'POST':
                $this->handlePOSTRequest();
                break;
            case 'PUT':
                $this->handlePUTRequest();
                break;
            case 'DELETE':
                $this->handleDELETERequest();
                break;
            default:
                $this->response('Method Not Allowed', 405);
                break;
        }
    }


    private function handleGETRequest()
    {

        if ($this->verb == null && sizeof($this->args) == 1) {
            $model = Wallet::get($this->args[0]);  // single purchase
            $this->response($model);
        } else if ($this->verb == null && empty($this->args)) {
            $model = Wallet::getAll();             // all purchases
            $this->response($model);
        }
        else if ($this->verb == null && sizeof($this->args) == 2) {
            $model = Wallet::getAllPurchasesFromWallet($this->args[0]);

            if($model == false) {
                $this->response("Not found", 404);
            } else {
                $this->response($model);
            }
        }
        else {
            $this->response("Bad request", 400);
        }

    }

    /**
     * create purchase: POST api.php?r=purchase
     */
    private function handlePOSTRequest()
    {
        $model = new Wallet();
        $model->setCurrency($this->getDataOrNull('currency'));
        $model->setName($this->getDataOrNull('name'));
        $model->setAmount($this->getDataOrNull('amount'));
        $model->setPrice($this->getDataOrNull('price'));

        if ($model->save()) {
            $this->response("OK", 201);
        } else {
            $this->response($model->getErrors(), 400);
        }
    }

    /**
     * update purchase: PUT api.php?r=purchase/25 -> args[0] = 25
     */
    private function handlePUTRequest()
    {
        if ($this->verb == null && sizeof($this->args) == 1) {

            $model = Wallet::get($this->args[0]);
            $model->setCurrency($this->getDataOrNull('currency'));
            //$model->setCurrency($this->getDataOrNull('currency'));
            $model->setName($this->getDataOrNull('name'));
            $model->setAmount($this->getDataOrNull('amount'));
            $model->setPrice($this->getDataOrNull('price'));


            if ($model->save()) {
                $this->response("OK");
            } else {
                $this->response($model->getErrors(), 400);
            }

        } else {
            $this->response("Not Found", 404);
        }
    }

    /**
     * delete purchase: DELETE api.php?r=purchase/25 -> args[0] = 25
     */
    private function handleDELETERequest()
    {
        if ($this->verb == null && sizeof($this->args) == 1) {
            Wallet::delete($this->args[0]);
            $this->response("OK", 200);
        } else {
            $this->response("Not Found", 404);
        }
    }

}
