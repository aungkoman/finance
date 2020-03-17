<?php
class TITLE{
        public $title;
        public function __construct(){
                $this->title = R::dispense('title');
        }
        public function insert($data){
                $this->title->name = (string) isset($data['name']) ? sanitize_str($data['name'],"title->insert : name") :  return_fail('title->insert : name is not defined in requested data');

                $currency = (int) isset($data['currency']) ? sanitize_int($data['currency'],"title->insert : currency") :  return_fail('title->insert : currency is not defined in requested data');
                $currency = R::load('currency',$currency);
                if($currency->id == 0 ) return_fail('title->insert : currency can not find ');
                $this->title->currency = $currency;

                $this->title->calculation = (string) isset($data['calculation']) ? sanitize_str($data['calculation'],"title->insert : calculation") :  return_fail('title->insert : calculation is not defined in requested data');
                
                try{
                        $id = R::store($this->title);
                        $test = $this->title->currency;
                        return_success("title->insert",$this->title);
                }catch(Exception $exp){
                        return_fail("title->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $titles = R::find('title',' id > ? ', [ $last_id ]);
                else $titles = R::find('title', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                $test;
                foreach($titles AS $index=>$title){
                        $test = $title->currency; // to get relate data
                        $return_data[] = $title;
                }
                return_success("title->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('title->update : id is not defined in requested data');
                $title = R::load( 'title', $id );
                if($title->id == 0 ) return_fail("title->update : no data for requested id");

                $title->name = (string) isset($data['name']) ? sanitize_str($data['name'],"title->update : name") :  $title->name;

                $title->currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"title->update : currency") :  $title->currency_id;

                $title->calculation = (string) isset($data['calculation']) ? sanitize_str($data['calculation'],"title->update : calculation") :  $title->calculation;
                
                try{
                        R::store($title);
                        $test = $title->currency;
                        return_success("title->update",$title);
                }catch(Exception $exp){
                        return_fail("title->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('title->delete : id is not defined in requested data');
                $title = R::load( 'title', $id );
                if($title->id == 0 ) return_fail("title->delete : no data for requested id");
                try{
                        R::trash($title);
                        return_success("title->delete",$title);
                }catch(Exception $exp){
                        return_fail("title->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>