<?php


require_once('RESTController.php');
require_once('models/Purchase.php');

header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");


class PurchaseRESTController extends RESTController
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
            case 'OPTIONS':
                    $this->response("OK", 201);
                    break;
            default:
                $this->response('Method Not Allowed', 405);
                break;
        }
    }

    /**
     * get single/all purchase or search purchase
     * all purchase: GET api.php?r=purchase
     * single purchase: GET api.php?r=purchase/25 -> args[0] = 25
     * all purchases group by currency: GET api.php?r=purchase/currency/BTC -> verb = currency, args[0] = BTC
     */
    private function handleGETRequest()
    {

        if ($this->verb == null && sizeof($this->args) == 1) {
            $model = Purchase::get($this->args[0]);  // single purchase
            $this->response($model);
        } else if ($this->verb == null && empty($this->args)) {
            $model = Purchase::getAll();             // all purchases
            $this->response($model);
        } else {
            $this->response("Bad request", 400);
        }

    }

    /**
     * create purchase: POST api.php?r=purchase
     */
    private function handlePOSTRequest()
    {
        
        $model = new Purchase();
        $model->setDate($this->getDataOrNull('date'));
        $model->setAmount($this->getDataOrNull('amount'));
        $model->setPrice($this->getDataOrNull('price'));
        //$model->setCurrency($this->getDataOrNull('currency'));
        $model->setWalletId($this->getDataOrNull('wallet_id'));

        if ($model->save()) {
            $this->response("OKAY", 201);
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

            $model = Purchase::get($this->args[0]);
            $model->setDate($this->getDataOrNull('date'));
            //$model->setCurrency($this->getDataOrNull('currency'));
            $model->setAmount($this->getDataOrNull('amount'));
            $model->setPrice($this->getDataOrNull('price'));
            $model->setWalletId($this->getDataOrNull('wallet_id'));


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
            Purchase::delete($this->args[0]);
            $this->response("OK", 200);
        } else {
            $this->response("Not Found", 404);
        }
    }

}
