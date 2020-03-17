<?php
class ACCOUNT{
        public $account;
        public function __construct(){
                $this->account = R::dispense('account');
        }
        public function insert($data){
                $this->account->name = (string) isset($data['name']) ? sanitize_str($data['name'],"account->insert : name") :  return_fail('account->insert : name is not defined in requested data');
                $currency = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account->insert : currency") :  return_fail('account->insert : currency is not defined in requested data');
                $currency = R::load('currency',$currency);
                if($currency->id == 0 ) return_fail('account->insert : currency can not find ');
                $this->account->currency = $currency;
                $bank = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account->insert : bank") :  return_fail('account->insert : bank is not defined in requested data');
                $bank = R::load('bank',$bank);
                if($bank->id == 0 ) return_fail('account->insert : bank can not find ');
                $this->account->bank = $bank;
                try{
                        $id = R::store($this->account);
                        $test = $this->account->currency;
                        $test = $this->account->bank;
                        return_success("account->insert",$this->account);
                }catch(Exception $exp){
                        return_fail("account->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $accounts = R::find('account',' id > ? ', [ $last_id ]);
                else $accounts = R::find('account', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                $test;
                foreach($accounts AS $index=>$account){
                        $test = $account->bank; // to get related foreign data
                        $test = $account->currency; // to get relate data
                        $return_data[] = $account;
                }
                return_success("account->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('account->update : id is not defined in requested data');
                $account = R::load( 'account', $id );
                if($account->id == 0 ) return_fail("account->update : no data for requested id");
                $account->name = (string) isset($data['name']) ? sanitize_str($data['name'],"account->update : name") :  $account->name;
                $account->currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account->update : currency") :  $account->currency_id;
                $account->bank_id = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account->update : bank") :  $account->bank_id;
                try{
                        R::store($account);
                        $test = $account->currency;
                        $test = $account->bank;
                        return_success("account->update",$account);
                }catch(Exception $exp){
                        return_fail("account->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('account->delete : id is not defined in requested data');
                $account = R::load( 'account', $id );
                if($account->id == 0 ) return_fail("account->delete : no data for requested id");
                try{
                        R::trash($account);
                        return_success("account->delete",$account);
                }catch(Exception $exp){
                        return_fail("account->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>