<?php

class DBTest
{
	public function update($id = null){
		if ($id !== 0)
		{
			return $id;
		}
		else
		{
			return false;
		}

	}

	public function insert($id){
		if($id){
			return $id;
		}else{
			return false;
		}
	}

}
