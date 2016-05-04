<?php
	namespace Application\Block\ExternalForm\Form\Controller;
	use Concrete\Core\Controller\AbstractController;
	use Core;
	use UserInfo;
	use Page;
	class MailForm extends AbstractController {
		
		public function view(){
			$this->set('bid', $this->bID);
			$this->set('section', 'edit');
			$this->set('errors', array());
			$this->set('input', array());
		}
		
		public function action_confirm() {
			
			$input = $this->getpost($input);
			
			if ($this->validate()) {
                $section = 'confirm';
				$this->set('input', $input);
            } else {
                $section = 'edit';
            }
            $this->set('section', $section);
			$message = trim($this->post('message'));   		
			$this->set('response', $message);  		
			return true;    	
		}
		
		public function action_send() {
			
			$input = $this->getpost($input);
				
			$mail = Core::make('helper/mail');
			if ($this->validate()) {
				$adminUserInfo = UserInfo::getByID(USER_SUPER_ID);
                $formFormEmailAddress = $adminUserInfo->getUserEmail();
				
				$body = 'Name: ' . $input['name'] . "\r\n";
	            $body .= 'Email: ' . $input['email'] . "\r\n\r\n";
	            $body .= "Message: \r\n" . $input['message'];
	            
	            // Send the mail
	            $mail->setSubject('問い合わせフォーム');
	            $mail->setBody($body);
	            $mail->to($formFormEmailAddress, 'admin');
	            $mail->from($input['email'], $input['name']);
	            $mail->replyto($input['email'], $input['name']);
	            $mail->sendMail();
	            $c = Page::getCurrentPage();
		        header('location: '.Core::make('helper/navigation')->getLinkToCollection($c, true).'/complete');
		        exit;
			}
		}
		
		public function action_complete() {
            $this->set('section','complete');
        }
		
		public function action_back() {
            $this->set('section','edit');
        }
        
        private function validate() {
	        $isvalid = true;
			
			$data = $_POST;
			$vf = Core::make('helper/validation/form');
			$vf->setData($data);
			$vf->addRequiredToken($this->bID.'ask');
	        if(!$vf->test()){
		        $isvalid = false;
		    }
		    
		    $val = Core::make('helper/validation/strings');
	        if (!strlen(trim($this->post('name')))) {
		        $errors['name'] = '名前を入力してください';
		        $isvalid = false;
			}
	        if(!$val->email($this->post('email'))){
		        $errors['email'] = 'メールアドレスの形式を確認してください';
		        $isvalid = false;
	        }
			if (!strlen(trim($this->post('name')))) {
		        $errors['message'] = 'メッセージを入力してください';
		        $isvalid = false;
			}
	        $this->set('errors', $errors);
	        return $isvalid;
	    }
	    
	    private function getpost($array) {
		    $text = Core::make('helper/text');
		    $array['name'] = $text->sanitize($this->post('name'));
			$array['email'] = $text->sanitize($this->post('email'));
			$array['message'] = $text->sanitize($this->post('message'));
			return $array;
		}
	}