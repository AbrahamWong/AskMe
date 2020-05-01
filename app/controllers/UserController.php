<?php

class UserController extends ControllerBase {

    public function loginAction() {
        
    }

    public function statusAction() {

        $sessions = $this->getDI()->getShared("session");

        if ($sessions->has("user")) {
            //if user is already logged we dont need to do anything 
            // so we redirect them to the main page
            return $this->response->redirect("/");
        }

        if ($this->request->isPost()) {
            $password = $this->request->getPost("password");
            $email = $this->request->getPost("email");
            
            // var_dump($email, $password); die();

            if ($email === "") {
                $this->flashSession->error("return enter your email");
                //pick up the same view to display the flash session errors
                return $this->view->pick("login");
            }

            if ($password === "") {
                $this->flashSession->error("return enter your password");
                //pick up the same view to display the flash session errors
                return $this->view->pick("login");
            }

            // $user = Users::findFirst([
            //     "conditions" => "email = ?0 AND password = ?1",
            //     "bind" == [
            //         0 => $email,
            //         1 => $this->security->hash($password)
            //     ]
            // ]);

            $user = Users::findFirst("email = '$email'");
            // var_dump($user->password, $password);
            if ($this->security->checkHash($password, $user->password) == false) {
                $user = false;
                echo "doggo";
            }

            // var_dump($user);
            // die();

            if (false === $user) {
                $this->flashSession->error("wrong user / password");
            } else {
                $this->view->user = $user;
                $sessions->set('auth', [
                    'id' => $user->id,
                    'name' => $user->name,
                ]);
                // var_dump($sessions->get('auth')); die();
                $this->response->redirect('/');
            }
        }

    }

    public function signupAction() {
        
    }

    public function registerAction() {
        $user = new Users();

        //assign value from the form to $user
        $user->assign(
            $this->request->getPost(),
            [
                'name' => 'name',
                'email' => 'email',
            ]
        );

        $user->password = $this->security->hash($this->request->getPost('password'));
        $user->account_created = date("Y-m-d H:i:s");;

        // Store and check for errors
        $success = $user->save();

        // passing the result to the view
        $this->view->success = $success;

        if ($success) {
            $message = "Thanks for registering!";
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                     . implode('<br>', $user->getMessages());
        }

        // passing a message to the view
        $this->view->message = $message;
    }

    public function logoutAction()
    {
        $this->session->destroy(true);
        $this->session->remove('auth');
        ini_set('session.gc_max_lifetime', 0);
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 1);
        unset($_COOKIE['PHPSESSID']);
        // $this->flashSession->success('Anda telah logout');
        $this->response->redirect('/');
    }

    public function profileAction($id) {
        $user = Users::findFirst("id = '$id'");
        
        // var_dump($this->request->getPost()); die();
        // Check incoming edit from editAction
        if ($this->request->has('name')) {
            $user->name = $this->request->get('name');

            // Change auth session's name 
            // Remind me that if you want to change session's data, always do it
            // like this, otherwise it won't work
            $temp = $this->session->get('auth');
            $temp['name'] = $user->name;
            $this->session->set('auth', $temp);

            $user->save();
        }

        $this->view->user = $user;
        $accountCreatedAt = date("j F Y",  strtotime($user->account_created)); 
        $this->view->account_created = $accountCreatedAt;
        // var_dump(date("j F Y",  strtotime($user->account_created))); die();
    }
    
    public function editAction($id) {
        $user = Users::findFirst("id = '$id'");
        $this->view->user = $user;
        // var_dump(date("j F Y",  strtotime($user->account_created))); die();
    }
}