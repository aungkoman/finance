<?php
class CALCULATION{
        public $calculation;
        public function __construct(){
                $this->calculation = R::dispense('calculation');
        }
        public function trail($data){
                /*
                        Data
                        1. start date
                        2. end date

                        Data Processing
                        1.  select opening balance for each account at start date
                        2.  select closig balance for each account at end date
                        3.  select total income for each account between start date and end date
                        4.  select total expense for each account 


                */
                //  1. start date
                $this->calculation->start_date = (string) isset($data['start_date']) ? sanitize_str($data['start_date'],"calculation->trail : start_date") :  return_fail('calculation->trail : start_date is not defined in requested data'); // do we need to reformat as date 

                //  2. end date
                $this->calculation->end_date = (string) isset($data['end_date']) ? sanitize_str($data['end_date'],"calculation->trail : end_date") :  return_fail('calculation->trail : end_date is not defined in requested data'); // do we need to reformat as date


        }
        public function insert($data){
                $this->calculation->name = (string) isset($data['name']) ? sanitize_str($data['name'],"calculation->insert : name") :  return_fail('calculation->insert : name is not defined in requested data');
                try{
                        $id = R::store($this->calculation);
                        return_success("calculation->insert",$this->calculation);
                }catch(Exception $exp){
                        return_fail("calculation->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $calculations = R::find('calculation',' id > ? ', [ $last_id ]);
                else $calculations = R::find('calculation', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                foreach($calculations AS $index=>$calculation){
                        $return_data[] = $calculation;
                }
                return_success("calculation->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('calculation->update : id is not defined in requested data');
                $calculation = R::load( 'calculation', $id );
                if($calculation->id == 0 ) return_fail("calculation->update : no data for requested id");
                $calculation->name = (string) isset($data['name']) ? sanitize_str($data['name'],"calculation->update : name") :  $calculation->name;
                try{
                        R::store($calculation);
                        return_success("calculation->update",$calculation);
                }catch(Exception $exp){
                        return_fail("calculation->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('calculation->delete : id is not defined in requested data');
                $calculation = R::load( 'calculation', $id );
                if($calculation->id == 0 ) return_fail("calculation->delete : no data for requested id");
                try{
                        R::trash($calculation);
                        return_success("calculation->delete",$calculation);
                }catch(Exception $exp){
                        return_fail("calculation->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>