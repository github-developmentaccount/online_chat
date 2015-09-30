<?php
class PC
{
	private $_db;
	public function __construct() 
	{
		$this->_db = DB::getInstance();
                
		
        }
        public function ShowConversations(){
        if(!isset($_SESSION['user']['user_id']) || !preg_match('/^[1-9]{1,11}$/', $_SESSION['user']['user_id'])) 
        {
           return false;
            
        }
        $user_id = (int)$_SESSION['user']['user_id'];  
       
            
        $sql = 'SELECT U.user_id, C.c_id, U.username
                        FROM users U, conversation C
                        WHERE 

                        CASE 

                        WHEN C.user_one = '.$user_id.'  
                        THEN C.user_two = U.user_id
                        WHEN C.user_two = '.$user_id.' 
                        THEN C.user_one = U.user_id
                        END 

                        AND 
                        (C.user_one = '.$user_id.' OR C.user_two = '.$user_id.' )
                            ORDER BY C.c_id 
                            DESC
                        ';

               

                $res = $this->_db->query($sql, PDO::FETCH_ASSOC);
                foreach($res as $row)
                        {
                                 
                                $output[] = ['c_id'=> $row['c_id'],
                                             'username' => $row['username'],
                                             'user_id' => $row['user_id']];
                        }
                        return $output; 
                        /*
                         * $output contains: array>c_id(int)+username(string)
                         * 
                         */
               
         
}

 public function SelectUsersPC()
    {
        $std = self::ShowConversations();
        print_r($std);
         
        if(isset($std) and !empty($std[0]['user_id']))
        { 
        $res = '';
        foreach($std as $uid) {
            $res .= $uid['user_id'].','; 
            
        }
        echo $res;
        } else return false;
        
        $res = substr($res, 0, strlen($std)-1);
        
        $sql = "SELECT user_id, username
                FROM users
                WHERE user_id NOT IN({$res})";
        
        $q = $this->_db->query($sql, PDO::FETCH_ASSOC);
        if($q)
            {
                foreach($q as $row)
                {
                    $output[] = [$row['user_id'],
                                 $row['username']];
                }
                
            }
              else return false;
            return $output;
                
    }


    public function CreatePrivateRoom($user_two)
    {
        if(!isset($user_two) || empty($user_two)) return false;
        if(!isset($_SESSION['user']['user_id']) || !preg_match('/^[1-9]{1,11}$/', $_SESSION['user']['user_id'])) 
        {
            return false;
        }
        $user_one = (int)$_SESSION['user']['user_id']; 
        
       
        $time = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        if(!preg_match('/^[1-9]{1,11}$/', $user_two)) return false;
        $sql = 'INSERT INTO conversation (
                                            user_one,
                                            user_two,
                                            ip,
                                            time
                                            )
                                            VALUES (
                                             ?,  ?,  ?,  ?
                                            )';
        $stmt = $this->_db->prepare($sql);
        return $stmt->execute([$user_one, 
                                $user_two,
                                $ip,
                                $time]); 
                            
        
        
        
        
        
    }
    
    public function ExpandConversation($c_id) 
    {
                  if(!isset($c_id) || !preg_match('/^[1-9]{1,11}$/', $c_id)) return false;
                  
                 $sql = 'SELECT R.cr_id,R.time,R.reply,U.user_id,U.username

                    FROM users U, conversation_reply R

                    WHERE R.user_id_fk=U.user_id AND R.c_id_fk= :c_id 

                    ORDER BY R.cr_id 
                    
                    DESC';
                 
                 
                    $q = $this->_db->prepare($sql);
                    $q->bindValue(':c_id', $c_id);
                    $q->execute();

                    if ($q->rowCount() > 0){
                        while($row = $q->fetch(PDO::FETCH_ASSOC))
                           {
                                        
					$output[] = ['login'=> $row['username'],
                                                     'time' => $row['time'],
                                                     'text' => $row['reply'],
                                                     'user_id' => $row['user_id']];
			    }
				return json_encode($output);                      
                        
                    }
                    else return false;
           
        
    }
    
    
   
    
    public function CreateChannel($title) 
    {
        if(!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 'admin')
            {
                return false;
            } 
            
        if(!isset($title) || strlen($title) < 6)  {
            return false;
        }
           
            $time = date('Y-m-d H:i:s');
            $uid = $_SESSION['user']['id'];
                $sql = "INSERT INTO channels 
                         (channel_id,
                          title,
                         created_at, 
                         admin_id)
                          VALUES (NULL,
                                    ?,
                                    ?,
                                    ?);";
                         
                         
                 $stmt = $this->_db->prepare($sql);
		 
                return $stmt->execute([$title,$time, $uid]);
                
       
        
        
        
    }
    
    public function ChannelsList()
    {
        
        $sql = 'SELECT title, channel_id, created_at'
                . ' FROM channels '
                . ' ORDER BY channel_id';
        $q = $this->_db->query($sql);
        if($q) {
            foreach($q as $r) {
                $output[]  = ['title'=>$r['title'],
                               'channel_id'=> $r['channel_id'],
                                'created_at'=> $r['created_at']];
            }
        } else return false;
        return $output;



    }

    public function DropChannel($channel_id) 
    {
        if(!preg_match('/^[1-9]{1,11}$/', $channel_id)) return false;
        $sql = "DELETE FROM channels  WHERE channels.channel_id = {$channel_id}";
        return $this->_db->query($sql);
  
        
    }
    
    
    

}

$obj = new PC();
$obj->SelectUsersPC();
print_r($obj->ShowConversations());