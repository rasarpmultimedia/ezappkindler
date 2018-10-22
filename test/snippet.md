 switch($key){
               case 0:
                   $part = preg_replace("#[^a-z0-9-_]#i","",$part);
                   $this->addParam(["controller"=>$part]);
                  // var_dump($part);
                   break;
               case 1:
                   $part = preg_replace("#[^a-z0-9-_]#i","",$part);
                   $this->addParam(["action"=>$part]);
                    //var_dump($part);
                   break;
               case 2:
                   // url subparts
                    $subparts = explode(":",$part);
                        for($i=0; $i<count($subparts);$i++){
                            if($i==0){
                                $id = preg_replace("~[^0-9]~i","",$subparts[$i]);
                                $this->addParam(["id"=>$id]);
                               // var_dump($id);
                            }else{
                                $opttarget = preg_replace("~[^a-z0-9-_ ]~i","",$subparts[$i]);
                                $this->addParam(["target"=>$opttarget]);
                           //var_dump($opttarget);
                            }
                        }
                   break;
           }