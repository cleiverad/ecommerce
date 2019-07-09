<?php 

namespace Hcode\Model;

use Hcode\DB\Sql;
use Hcode\Model;
use \Hcode\Mailer;

Class Category extends Model{

	public static function listAll(){

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");

	}

	public function save(){

		$Sql = new Sql();

		$results = $Sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory()
		));

		$this->setData($results[0]);

	}

	public function get($idcategory){

		$Sql = new Sql();

		$results = $Sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
			":idcategory"=>$idcategory
		]);

		$this->setData($results[0]);

	}

	public function delete(){

		$Sql = new Sql();

		$Sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory)", [
			":idcategory"=>$this->getidcategory()
		]);

	}

}

?>