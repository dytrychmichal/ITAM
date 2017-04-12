<?php
  class verify
  {
    public function verify()
    {
      if(!isset($_SESSION)) 
      { 
          session_start(); 
      } 
      if(!isset($_SESSION['name']) || !isset($_SESSION['roles']))
      {
          header('Location: unauthorized.php');
      }
    }
    
    public function verifyAdmin()
    {
      if(!isset($_SESSION)) 
      { 
          session_start(); 
      }
      $admin=false;
      $ccowner=false;
      $user=false;
      
      foreach($_SESSION['roles'] as $role)
      {
        if($role['name'] == 'admin')
        {
            $admin=true;
        }
        else if($role['name'] == 'costcenter_owner')   //not necessary
        {
            $ccowner=true;
        
        }
        else if($role['name'] == 'user')              //not necessary
        {
            $user=true;
        
        }
      
      } 
      if(!$admin)
      {
         header('Location: unauthorized.php');
      }
    }
    
    public function isAdmin()
    {
      if(!isset($_SESSION)) 
      { 
          session_start(); 
      }
      $admin=false;
      $ccowner=false;
      $user=false;
      
      foreach($_SESSION['roles'] as $role)
      {
        if($role['name'] == 'admin')
        {
            $admin=true;
        }
        else if($role['name'] == 'costcenter_owner')   //not necessary
        {
            $ccowner=true;
        
        }
        else if($role['name'] == 'user')              //not necessary
        {
            $user=true;
        
        }
      
      } 
      if($admin)
      {
         return true;
      }
      
      return false;
    }
    
    
  
  
  }


?>