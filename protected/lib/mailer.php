<?php
require_once(APP_DIR.'/protected/lib/turboEmail/TurboApiClient.php');
class mailer
{
    private $sendTo;
    private $smtpServer;
    private $port;
    private $userName;
    private $password;
    private $from;
    private $site_name;
    
    function __construct($sendTo)
    {
        $this->sendTo = $sendTo;
        $this->from = "myuan@ssca-bc.org";
    /*
        $this->smtpServer = $GLOBALS['smtp_server'];
        $this->port = $GLOBALS['smtp_port'];
        $this->userName = $GLOBALS['smtp_user'];
        $this->password = $GLOBALS['smtp_password'];
        $this->site_name = $GLOBALS['site_name'];
    */
    }
/*    
    function __construct($sentTo,$from,$smtpServer,$port,$userName,$password)
    {
        $this->sendTo = $sendTo;
        $this->from = $from;
        $this->smtpServer = $smtpServer;
        $this->port = $port;
        $this->userName = $userName;
        $this->password = $password;
        $this->site_name = $GLOBALS['site_name'];
    }
 */   
    
    public function sendTestEmail()
    {
       include(APP_DIR.DS.'plugin'.DS.'phpmailer'.DS.'PHPMailerAutoload.php');
        $mailer = new PHPMailer();
        $mailer->SMTPDebug = 4;
        $mailer->isSMTP();
        $mailer->CharSet = 'UTF-8';
        $mailer->SMTPAuth = TRUE;                 
        $mailer->Host = "a2plcpnl0189.prod.iad2.secureserver.net";
        $mailer->Port = 465;
        $mailer->Username = "info@vanfruits.com";
        $mailer->Password = "123456789";
/*        
        $mailer->SMTPAuth = FALSE; 
        $mailer->Host = "localhost";
        $mailer->Port = 25;
        $mailer->Username = "";
        $mailer->Password = "";
*/
        $mailer->SetFrom("info@vanfruits.com", "vanfruits.com");
            
        $mailer->addAddress($this->sendTo,$this->sendTo);
        $mailer->isHTML(TRUE);
        $mailer->Subject = "test email from vanfruits.com";
                    
        $mailer->SMTPAuth   = true;                  // enable SMTP authentication
	    $mailer->SMTPSecure = "ssl";         // sets the prefix to the servier
        $mailer->WordWrap   = 50; // set word wrap
        

	    $mailer->MsgHTML("<p>This is a test email</p>");
        
        if($mailer->send())
            return array('status' => 'success');
        else
            return array('status' => 'error','msg' => $mailer->ErrorInfo);
       
    }
    
    private function sendMailViaTurboSMTP($subject,$body,$addBCC=false)
    {
        $email = new Email();
        $email->setFrom($this->from);
        $email->setToList($this->sendTo);
        if($addBCC)
            $email->setBccList("michaelyuan21@live.com");
        $email->setSubject($subject);
        $email->setHtmlContent($body);

        $turboApiClient = new TurboApiClient("myuan@ssca-bc.org", "ycyLLGn4");


        $response = $turboApiClient->sendEmail($email);
        if($response['message'] == "OK")
            return array('status' => 'success');
        else
            return array('status' => 'error','msg' => "turbosmtp error.");
        
    }
    
    private function sendMail($subject,$body,$addBCC=false)
    {
        include(APP_DIR.DS.'plugin'.DS.'phpmailer'.DS.'PHPMailerAutoload.php');
        $mailer = new PHPMailer();
//        $mailer->SMTPDebug = 4;
        $mailer->isSMTP();
        $mailer->CharSet = 'UTF-8';
        $mailer->SMTPAuth = TRUE;                 
        $mailer->Host = $this->smtpServer;
        $mailer->Port = $this->port;
        $mailer->Username = $this->userName;
        $mailer->Password = $this->password;

        $mailer->SetFrom($this->from, $this->site_name);
            
        $mailer->addAddress($this->sendTo,$this->sendTo);
        $mailer->isHTML(TRUE);
        $mailer->Subject = $subject;
                    
        $mailer->SMTPAuth   = true;                  // enable SMTP authentication
	    $mailer->SMTPSecure = "ssl";         // sets the prefix to the servier
        $mailer->WordWrap   = 50; // set word wrap
        
        if($addBCC)
            $mailer->addBCC('vanfruitsapp@gmail.com','vanfruits order');

	    $mailer->MsgHTML($body);
        
        if($mailer->send())
            return array('status' => 'success');
        else
            return array('status' => 'error','msg' => $mailer->ErrorInfo);
    }
    
    public function sendWelcomeEmail($new_comer)
    {
        ob_start();
        include(INCL_DIR.DS."email_template".DS."email_welcome.html.php");
        $body = ob_get_contents();
        ob_end_clean(); 
        
        return $this->sendMailViaTurboSMTP("欢迎您来到南素里基督教会/Wecome to South Surrey Christian Assembly",$body);
    }
    
    public function sendRegistrationEmail($activation_code,$user_id)
    {
        $activation_url = url('mobile/main','verify',array('code' => $activation_code,'status' => $user_id));
        
        ob_start();
        include(INCL_DIR.DS."email_template".DS."email_registration.html.php");
        $body = ob_get_contents();
        ob_end_clean();
 
        
//        return $this->sendMail("溫鮮生用戶註冊",$body);
        return $this->sendMailViaTurboSMTP("溫鮮生用戶註冊",$body);
    }
    
    public function sendResetPasswordEmail($body)
    {
        $ret =  $this->sendMailViaTurboSMTP('温鲜生 -- 重设密码',$body);
        if($ret['status'] = 'success')
            return true;
        else
            return false;
    }
    
    public function ResendOrderConfirmationEmail($order_number,$body)
    {
       $ret = $this->sendMailViaTurboSMTP("重发：溫鮮生定單--".$order_number,$body,true); 
       if($ret['status'] == 'success')
           return true;
       else
           return false;
    }
    
    public function sendOrderConfirmationEmail($order_number)
    {
        $order_model = new order_model();
        $order_products_model = new order_products_model();
        $sa_model = new order_shippingaddress_model();
        $order_discount_model = new order_discount_model();
        $voucher_model = new voucher_model();
        $user_addr_model = new user_address_model();
        $marketing_model = new marketing_model();
        
        $order = $order_model->find(array('order_number' => $order_number));
        if($order)
        {
            $shipping_addr = $sa_model->find(array('order_id' => $order['id']));
            $products_list = $order_products_model->get_products_list($order['id']);
            $sql = "SELECT a.*, b.code FROM {$order_discount_model->table_name} AS a LEFT JOIN {$voucher_model->table_name} AS b ON a.voucher_id=b.voucher_id WHERE a.order_id={$order['id']}";
            $discount_list = $order_discount_model->query($sql);
            
            $user_addr = $user_addr_model->findAll(array('user_id'=>$order['user_id']),null,"*",array(0,1));
            
            ob_start();
            include(INCL_DIR.DS."email_template".DS."email_order_confirmation.html.php");
            $body = ob_get_contents();
            ob_end_clean();
            
            $order_email = new order_email_model();
            $data = array(
                'order_id' => $order['id'],
                'user_id'  => $order['user_id'],
                'body' => $body,
                'sent_ts' => $_SERVER['REQUEST_TIME'],
            );
            $order_email->create($data);
     
//            return $this->sendMail("溫鮮生定單--".$order_number,$body,true);
            return $this->sendMailViaTurboSMTP("溫鮮生定單--".$order_number,$body,true);
        }
    }

    
}
