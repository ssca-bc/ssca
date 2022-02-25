<?php
class UsherController extends BaseController
{
    function actionAdd()
    {
        if(request('step') == 'submit')
        {
            $new_comers_model = new new_comers_model();
            $name = strip_tags(trim(request('name','N/a')));
            if(strlen($name) > 255)
                $email = substr($name,0,255);
            $phone = strip_tags(request('phone',''));
            $email = strip_tags(trim(request('email','')));
            if(strlen($email) > 255)
                $email = substr($email,0,255);
            $referral = strip_tags(trim(request('referral','')));
            if(strlen($referral)>255)
                $referral = substr($referral,0,255);
            $address = strip_tags(trim(request('address','')));
            if(strlen($address)>255)
                $referral = substr($address,0,255);
            $baptized = (int)request('baptized',0);
            $contact = (int)request('contact',0);
            
            $data = array(
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'referral' => $referral,
                'address' => $address,
                'baptized' => $baptized,
                'contact' => $contact,
                'ts' => $_SERVER['REQUEST_TIME'],
                'created_date' => date("Y-m-d",$_SERVER['REQUEST_TIME']),
            );
            
            $id = $new_comers_model->create($data);
            $this->id = $id;
            if($id)
            {
                $new_comer = $new_comers_model->find(array('user_id' => $id));
                switch($new_comer['contact'])
                {
                    case 3: $new_comer['contact_msg'] = '不用了';break;
                    case 0: $new_comer['contact_msg'] = '电话联络';break;
                    case 1: $new_comer['contact_msg'] = '电邮联络';break;
                    case 2: $new_comer['contact_msg'] = '电话或电邮联络';break;
                    default:$new_comer['contact_msg'] = '不用了';break;
                }
                $this->new_comer = $new_comer;
            }
            $this->display('admin/usher/new_comer_submitted.html');
            
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $this->display('admin/usher/new_comer.html');
        }
    }
    
    function actionEdit()
    {
        $id = (int)request('id',0);
        $new_comers_model = new new_comers_model();
        if(request('step') == 'submit')
        {
            $name = trim(request('name','N/a'));
            $phone = request('phone','');
            $email = trim(request('email',''));
            $referral = trim(request('referral',''));
            if(strlen($referral)>255)
                $referral = substr($referral,0,255);
            $address = trim(request('address',''));
            $baptized = (int)request('baptized',0);
            $contact = (int)request('contact',0);
            
            $data = array(
                'name' => substr($name,0,255),
                'phone' => $phone,
                'email' => substr($email,0,60),
                'referral' => $referral,
                'address' => substr($address,0,255),
                'baptized' => $baptized,
                'contact' => $contact,
 //               'ts' => $_SERVER['REQUEST_TIME'],
 //               'created_date' => date("Y-m-d",$_SERVER['REQUEST_TIME']),
            ); 
            
            $new_comers_model->update(array('user_id' => $id),$data);
            
            $this->id = $id;
            if($id)
            {
                $new_comer = $new_comers_model->find(array('user_id' => $id));
                switch($new_comer['contact'])
                {
                    case 3: $new_comer['contact_msg'] = '不用了';break;
                    case 0: $new_comer['contact_msg'] = '电话联络';break;
                    case 1: $new_comer['contact_msg'] = '电邮联络';break;
                    case 2: $new_comer['contact_msg'] = '电话或电邮联络';break;
                    default:$new_comer['contact_msg'] = '不用了';break;
                }
                $this->new_comer = $new_comer;
            }
            $this->display('admin/usher/new_comer_submitted.html');
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $this->new_comer = $new_comers_model->find(array('user_id' => $id));
            $this->display('admin/usher/new_comer.html');
        }
    }
}