<?php
namespace engine\components;
use engine\{core\SQLRecords,lib\HTMLHelper,core\Settings};
/**Site Navication Class**/
class SiteNav{
  public $nav;
  public $html;
  protected $database;
  private $settings;
  private $data = [];
  
  public function __construct() {
      $this->settings = new Settings;
      $this->database = new SQLRecords;   
  }
  
  public function menu(){
       $sql = $this->database;$data =[];
       $category_res = $sql->readQuery("SELECT * FROM category");
       $category_res = (!empty($category_res)?$category_res:[]);
       return $category_res;
  }
  
  public function menuChildren($parentid=null,$menulevel=null,$menulevel_id=null){
      $sql = $this->database; $data =[];
       if($parentid!=null){
           $sql->placeholder = [$parentid];
           $cate_level = $sql->readQuery("SELECT * FROM category LEFT JOIN category_level on category.CategoryId = category_level.ParentId WHERE category_level.ParentId=?");
           $data = $cate_level;
       }elseif ($menulevel!=null) {
           $sql->placeholder = [$parentid,$menulevel];
           $cate_level = $sql->readQuery("SELECT * FROM category LEFT JOIN category_level on category.CategoryId = category_level.ParentId WHERE category_level.ParentId=? and category_level.CategoryLevel=?");
           $data = $cate_level; 
        }
       $this->data = (!empty($data)?$data:[]);
       return $this->data;       
  }  
    
}
/** How to generate a Menu list *
$nav  = new \engine\components\SiteNav;
 $html = new \engine\lib\HTMLHelper; //$html::prettyLink($url, $addfile, $linktext,$params='',$id='')
 //$html::prettyLink(".",".","action","home/index","")
       $nav_menus = $nav->menu();
       $menu_children = $nav->menuChildren();
       $navigation = '<ul>';
       $navigation .= '<li>Home</li>';
       foreach($nav_menus as $menu){
           $navigation .= '<li>'.$menu->CategoryName;
           $has_children = $nav->menuChildren($menu->CategoryId);
           //var_dump($has_children);page/business,home/politics
           if($has_children){
               $navigation .= '<ul >';
               foreach ($has_children as $submenu) {  
                   $has_sub_children = $nav->menuChildren($menu->CategoryId,$submenu->CategoryLevel,$submenu->CateLevelId);
                   $navigation .= '<li>'.$submenu->CateLevelName.'</li>'; 
               } 
               $navigation .= '</ul>';
           }
           $navigation .= '</li>';           
       }
       $navigation .= '</ul>';
       echo $navigation;*/

