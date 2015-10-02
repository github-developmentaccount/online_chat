<?php

class Chat 
{
	private $_db;
	public function __construct() 
	{
		$this->_db = DB::getInstance();
		
	}

	public function MatchLogin($login, $password)
	{
                        if($login == "" && $password == "") {
                                return false;
                        }
                                //validation
                                $password = trim($password);
                                $login = strip_tags(trim($login));
                                $password = hash('sha256', $password);
                                if(!preg_match('/^[0-9a-zA-Z]{4,9}$/', $login)) {
                                return false;
                        }

                        //SQL query forming
                        $sql = 'SELECT password, username, user_id, role 
                        FROM users
                        WHERE username = ?
                        LIMIT 1';

                        $q = $this->_db->prepare($sql);
                        $q->execute([$login]);
                        $q->setFetchMode(PDO::FETCH_ASSOC);

                //matching and setting session
                if($row = $q->fetch()) {
                         if($row['password'] === $password && $row['username'] === $login) 
                                {
                                        $_SESSION['user']['username'] = $row['username'];
                                        $_SESSION['user']['user_id'] = $row['user_id'];
                                        $_SESSION['user']['role'] = $row['role'];

                                }
                }
                else 
                {
                        return 0;
                }
        
                return true;
      

	}

	public function LogOut()
	{
		session_destroy();
	}
	//Pull login from id
	public function LoginToId($id)
	{
		$id = intval($id);
		$sql = 'SELECT username FROM users
				 WHERE user_id = ?';
		$q = $this->_db->prepare($sql);
		$q->execute([$id]);
		$q->setFetchMode(PDO::FETCH_ASSOC);

		return ($r = $q->fetch()) ? $r['username'] : NULL;

	}
	//Pull id from login
	public function IdToLogin($login)
	{
		$query = 'SELECT user_id FROM users
				WHERE username = ?';

		$res = $this->_db->prepare($query);
		$res->execute([$login]);
		$res->setFetchMode(PDO::FETCH_ASSOC);

		return ($row = $res->fetch()) ? $row['user_id'] : NULL;

	}
	public function newMessage($message, $ch_id)
	{
//                $message = trim($message);
//                if($message == '') return false;
//                
		$time = date("Y-m-d H:i:s");
		$uid = !empty($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] 
										: self::IdToLogin($_SESSION['user']['username']);

		$sql = 'INSERT INTO messages (time, text, uid, ch_id) VALUES (?,?,?,?)';
		$stmt = $this->_db->prepare($sql);
                
                return $stmt->execute([$time, $message, $uid, $ch_id]);
              
		


	}
	public function showMessage($ch_id=1)
	{
                
                if(!preg_match('/^[0-9]+$/', $ch_id)) return false;
                if(!$res = self::CheckCh($ch_id)) return false;
                
                
		$_sql =  'SELECT id, time, text, uid, ch_id FROM messages 
                                                        WHERE ch_id = '.$ch_id.' 
							ORDER by id DESC
                                                        LIMIT 10';
		$result = $this->_db->query($_sql, PDO::FETCH_ASSOC);
                if($result->rowCount() > 0){
		foreach($result as $row)
				{
					
					$output[] = [ 'login'=> htmlspecialchars(self::LoginToId($row['uid'])),
                                                      'time' => $row['time'],
                                                      'text' => htmlspecialchars($row['text']),
                                                      'uid' => $row['uid'],
                                                      'ch_id'=>$row['ch_id'],
                                                      'm_id' => $row['id']];
				}
                                
				return json_encode($output);
                }
                else return 2;

				

	}
        public function CheckCh($ch_id)
        {
            $sql = 'SELECT * FROM channels
                     WHERE channel_id ='.$ch_id.
                    ' LIMIT 1';
            $q = $this->_db->query($sql);
            if($q->rowCount() > 0 )  {
                return true;
            }else {
                return false;
            }
               
            
            
        }
        
        
        
        public function ConvList(){
        if(!isset($_SESSION['user']['user_id']) || !preg_match('/^[0-9]+$/', $_SESSION['user']['user_id'])) 
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

               
                $output = [];
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

    public function UsList()
    {
        $std = self::ConvList();        
        if(isset($std) and !empty($std[0]['user_id']))
        { 
            $res = '';
            foreach($std as $uid) {
                $res .= $uid['user_id'].','; 
                }
             
                $res .= $_SESSION['user']['user_id'];
                
                $sql = "SELECT user_id, username
                FROM users
                WHERE user_id NOT IN({$res}) ORDER BY username";
        } else {
            $sql = "SELECT user_id, username
                FROM users
                WHERE user_id NOT IN({$_SESSION['user']['user_id']}) ORDER BY username";
        }

        
        $q = $this->_db->query($sql, PDO::FETCH_ASSOC);
        if($q->rowCount() > 0)
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
    
    
    public function CreatePR($user_two)
    {
        if(!isset($user_two) || !preg_match('/^[1-9]{1,11}$/', $user_two)) 
        {
            return false;
            
        }
        if(!isset($_SESSION['user']['user_id']) || !preg_match('/^[0-9]+$/', $_SESSION['user']['user_id'])) 
        {
            return false;
        }
        $user_one = $_SESSION['user']['user_id']; 
        
//       $user_one = 1;
//       $user_two = 5;
        $time = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
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
                  if(!isset($c_id) || !preg_match('/^[0-9]+$/', $c_id)) return false;
                  
                 $sql = 'SELECT R.cr_id,R.time,R.reply,U.user_id,U.username

                    FROM users U, conversation_reply R

                    WHERE R.user_id_fk=U.user_id AND R.c_id_fk= :c_id 

                    ORDER BY R.cr_id 
                    
                    DESC';
                 
                 
                    $q = $this->_db->prepare($sql);
                    $q->bindValue(':c_id', $c_id);
                    $q->execute();

                   
                        while($row = $q->fetch(PDO::FETCH_ASSOC))
                           {
                                        
					$output[] = ['login'=> $row['username'],
                                                     'time' => $row['time'],
                                                     'text' => $row['reply'],
                                                     'user_id' => $row['user_id']];
			    }
				return json_encode($output);                      
                        
                    
           
        
    }
    public function CidToUid($c_id){
        if(!isset($_SESSION['user']['user_id']) || empty($_SESSION['user']['user_id'])){
            return false;
        }
        $uid = (int)$_SESSION['user']['user_id'];
                 $sql = "SELECT c_id FROM conversation 
                            WHERE (user_one={$uid} or user_two={$uid})";
                 
             $q = $this->_db->query($sql);
             
             if($q->rowCount() > 0){
                    foreach($q as $r) {
                        $result[]  = $r['c_id'];
                                       
                        }
    
                    } 
                    else {
                        return false;
                    }
                    
               if(in_array($c_id, $result)) {
                   return false;
               } else {
                  return true;
                   
               }
    }
    
    public function newReply($c_id, $message)
    {
        if(self::CidToUid($c_id)) {
            
            return false;
        }
       
        $time = date("Y-m-d H:i:s");
        $uid = !empty($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] 
                                                                        : self::IdToLogin($_SESSION['user']['username']);
        $ip = getenv('REMOTE_ADDR');
        
        $sql = 'INSERT INTO conversation_reply (reply, user_id_fk, ip, time, c_id_fk) VALUES (?,?,?,?,?)';
        $stmt = $this->_db->prepare($sql);

        if($stmt->execute([$message, $uid, $ip, $time, $c_id])){
            echo 1;
        }else 
        {
            echo 0;
        }
        
        
        
        
    }
    
    public function DropChannel($channel_id) 
    {
        if($_SESSION['user']['role'] != 'admin') {
            return false;
        }
        if(!preg_match('/^[0-9]+$/', $channel_id)) return false;
        $sql = "DELETE FROM channels  WHERE channels.channel_id = {$channel_id}";
        if($this->_db->query($sql)){
            $sql1 = "DELETE FROM messages WHERE ch_id={$channel_id}";
            
            return $this->_db->query($sql1);
        }
        return false;
        
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
            $uid = $_SESSION['user']['user_id'];
                $sql = "INSERT INTO channels 
                         (
                          title,
                         created_at, 
                         admin_id)
                          VALUES (
                                    ?,
                                    ?,
                                    ?);";
                         
                        
                 $stmt = $this->_db->prepare($sql);
		 
                if($stmt->execute([$title,$time, $uid])){
                    return true;
                } return false;
                
        
        
        
    }

    public function RemoveMsg($m_id) 
    {
        
         if(!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 'admin')
            {
              return false;
            } 
            
        if(!isset($m_id) || !preg_match('/^[0-9]+$/', $m_id))  {
           
            return false;
        }       
        
        $sql = "DELETE FROM messages WHERE id={$m_id}";
        
        return $this->_db->query($sql);
     
        
        
    }
}

