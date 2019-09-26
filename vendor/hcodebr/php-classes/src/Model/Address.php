<?php 

namespace Hcode\Model;

use Hcode\DB\Sql;
use Hcode\Model;

Class Address extends Model{

    const SESSION_ERROR = 'AddressError';

    public static function getCep($nrcep){

        $nrcep = str_replace('-', '', $nrcep);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/$nrcep/json/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $data;

    }

    public function loadFromCep($nrcep){

        $data = Address::getCep($nrcep);

        if (isset($data['logradouro']) && $data['logradouro']){

            $this->setdesaddress($data['logradouro']);
            $this->setdescomplement($data['complemento']);
            $this->setdesdistrict($data['bairro']);
            $this->setdescity($data['localidade']);
            $this->setdesstate($data['uf']);
            $this->setdescountry('Brasil');
            $this->setdeszipcode($nrcep);
        }

    }

    public function save(){

        $sql = new Sql();

    //    echo '1 - '.$this->getidaddress().'<br>';
     //   echo '2 - '.$this->getidperson().'<br>';
     //   echo '3 - '.$this->getdesaddress().'<br>';
     //   echo '4 - '.$this->getdescomplement().'<br>';
    //    echo '5 - '.$this->getdescity().'<br>';
    //    echo '6 - '.$this->getdesstate().'<br>';
    //    echo '7 - '.$this->getdescountry().'<br>';
    //    echo '8 - '.$this->getdeszipcode().'<br>';
    //    echo '9 - '.$this->getdesdistrict().'<br>';

    //    exit;

        $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
            ':idaddress'=>$this->getidaddress(),
            ':idperson'=>$this->getidperson(),
            ':desaddress'=>utf8_decode($this->getdesaddress()),
            ':descomplement'=>utf8_decode($this->getdescomplement()),
            ':descity'=>utf8_decode($this->getdescity()),
            ':desstate'=>utf8_decode($this->getdesstate()),
            ':descountry'=>utf8_decode($this->getdescountry()),
            ':deszipcode'=>$this->getdeszipcode(),
            ':desdistrict'=>utf8_decode($this->getdesdistrict())
        ]);

        if (count($results) > 0 ){
            $this->setData($results[0]);
        }
    }
    
    public static function setMsgError($msg){

        $_SESSION[Address::SESSION_ERROR] = $msg;
    
    }
    
    public static function getMsgError(){
    
        $msg = (isset($_SESSION[Address::SESSION_ERROR])) ? $_SESSION[Address::SESSION_ERROR] : "";		
    
        Address::clearMsgError();
    
        return $msg;
    
    }
    
    public static function clearMsgError(){
    
        $_SESSION[Address::SESSION_ERROR] = NULL;
    
    }

}

?>