<?php

namespace App\Models;

class Role extends Base
{

    public function nodes(){
    	// Role表”belongsToMany” Node表
    	return $this -> belongsToMany(Node::class,'role_node','role_id','node_id');
    }
}
