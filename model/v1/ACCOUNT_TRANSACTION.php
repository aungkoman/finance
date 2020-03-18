<?php
class ACCOUNT_TRANSACTION{
        public $account_transaction;
        /*
            1. account : FK
            2. description : adding opening balance, or may be other description
            3. operation : income / expense  ; we have to use int or reference key; more on later....
            4. amount : int 
            5. balance : int ; this is latest balance for that time , may be at once insert after insert/update to account
            6. opening_date : the official date for this transaction
            7. 

        */
        public function __construct(){
                $this->account_transaction = R::dispense('account_transaction');
        }
        public function insert($data){
                // 1. name
                $this->account_transaction->name = (string) isset($data['name']) ? sanitize_str($data['name'],"account_transaction->insert : name") :  return_fail('account_transaction->insert : name is not defined in requested data');

                // 2. currency
                $currency = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account_transaction->insert : currency") :  return_fail('account_transaction->insert : currency is not defined in requested data');
                $currency = R::load('currency',$currency);
                if($currency->id == 0 ) return_fail('account_transaction->insert : currency can not find ');
                $this->account_transaction->currency = $currency;

                // 3. bank
                $bank = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account_transaction->insert : bank") :  return_fail('account_transaction->insert : bank is not defined in requested data');
                $bank = R::load('bank',$bank);
                if($bank->id == 0 ) return_fail('account_transaction->insert : bank can not find ');
                $this->account_transaction->bank = $bank;

                // created date
                // modified date
                // opening date
                // balance 
                /*
                        These fields are added in 2020-03-18 10:42
                */

                // 4. opening_date
                $this->account_transaction->opening_date = (string) isset($data['opening_date']) ? sanitize_str($data['opening_date'],"account_transaction->insert : opening_date") :  return_fail('account_transaction->insert : opening_date is not defined in requested data');
                $opening_date = strtotime($this->finance->opening_date); // time to unix
                $this->account_transaction->opening_date = date("Y-m-d",$opening_date); // well formated time

                // 5. balance
                $this->account_transaction->balance = (int) isset($data['balance']) ? sanitize_int($data['balance'],"account_transaction->insert : balance") :  return_fail('account_transaction->insert : balance is not defined in requested data');

                // 6. created_date
                $this->account_transaction->created_date = date("Y-m-d h:m:s");

                // 7. modified_date
                $this->account_transaction->modified_date = date("Y-m-d h:m:s");

                try{
                        $id = R::store($this->account_transaction);
                        $test = $this->account_transaction->currency;
                        $test = $this->account_transaction->bank;
                        return_success("account_transaction->insert",$this->account_transaction);
                }catch(Exception $exp){
                        return_fail("account_transaction->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $account_transactions = R::find('account_transaction',' id > ? ', [ $last_id ]);
                else $account_transactions = R::find('account_transaction', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                $test;
                foreach($account_transactions AS $index=>$account_transaction){
                        $test = $account_transaction->bank; // to get related foreign data
                        $test = $account_transaction->currency; // to get relate data
                        $return_data[] = $account_transaction;
                }
                return_success("account_transaction->select ".count($return_data),$return_data);
        }
        public function update($data){
                // 1. id
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('account_transaction->update : id is not defined in requested data');
                $account_transaction = R::load( 'account_transaction', $id );
                if($account_transaction->id == 0 ) return_fail("account_transaction->update : no data for requested id");

                // 2. name
                $account_transaction->name = (string) isset($data['name']) ? sanitize_str($data['name'],"account_transaction->update : name") :  $account_transaction->name;

                // 3. currency
                $currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account_transaction->update : currency") :  $account_transaction->currency_id;
                $currency = R::load('currency',$currency_id);
                if($currency->id == 0 ) return_fail('account_transaction->update : currency can not find '+$currency_id);
                $account_transaction->currency = $currency;

                //$account_transaction->currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account_transaction->update : currency") :  $account_transaction->currency_id;

                // 4. bank
                $bank_id = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account_transaction->update : bank") :  $account_transaction->bank_id;
                $bank = R::load('bank',$bank_id);
                if($bank->id == 0 ) return_fail('account_transaction->update : bank can not find '+$currency_id);
                $account_transaction->currency = $currency;
                //$account_transaction->bank_id = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account_transaction->update : bank") :  $account_transaction->bank_id;

                // 5. modified_date
                $account_transaction->modified_date = date("Y-m-d h:m:s");

                // 6. opening_date
                $account_transaction->opening_date = (string) isset($data['opening_date']) ? sanitize_str($data['opening_date'],"account_transaction->update : opening_date") :  $account_transaction->opening_date;
                $opening_date = strtotime($account_transaction->opening_date); // time to unix
                $account_transaction->opening_date = date("Y-m-d",$opening_date); // well formated time

                // 7. balance
                $account_transaction->balance = (int) isset($data['balance']) ? sanitize_int($data['balance'],"account_transaction->update : balance") :  $account_transaction->balance;

                // 8. we just omit created_date :D

                try{
                        R::store($account_transaction);
                        $test = $account_transaction->currency;
                        $test = $account_transaction->bank;
                        return_success("account_transaction->update",$account_transaction);
                }catch(Exception $exp){
                        return_fail("account_transaction->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('account_transaction->delete : id is not defined in requested data');
                $account_transaction = R::load( 'account_transaction', $id );
                if($account_transaction->id == 0 ) return_fail("account_transaction->delete : no data for requested id");
                try{
                        R::trash($account_transaction);
                        return_success("account_transaction->delete",$account_transaction);
                }catch(Exception $exp){
                        return_fail("account_transaction->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>