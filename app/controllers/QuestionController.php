<?php
declare(strict_types=1);

class QuestionController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function createAction() {
        if ($this->session->has('auth') === false) {
            $this->response->redirect('/login');
        }
    }

    public function submitAction ()
    {
        $question = new Questions();
        $question->assign(
            $this->request->getPost(),
            [
                'question_title' => 'question_title',
                'question_content' => 'question_content'
            ]
        );

        $question->questioner_id = $this->session->get('auth')['id'];
        $question->question_date = date("Y-m-d H:i:s");

        // Store and check for errors
        $success = $question->save();
        $this->response->redirect('/');
    }

    // Read questions with the answer
    public function readAction(int $id) {
        $id = (int) $id;
        $question = Questions::findFirst("question_id = '$id'");
        if ($question != null) {
            $this->view->question = $question;
            
            // Also don't forget to fetch all the answer
            $answer = Answers::find("question_id = '$id'");
            $this->view->answers = $answer;
            $user = Users::find();
            $this->view->users = $user;
        }
        else {
            // 404 not found
        }
        // return "<h1>Say Test to $id</h1>";
    }

    public function answerAction(int $id) {
        $id = (int) $id;
        // store answer to answer model
        // Read will also wait for answers from another user.
        if ($this->request->has('answer') === true) {
            $submittedAnswer = new Answers();
            $submittedAnswer->assign(
                [
                    'answer_content' => $this->request->getPost('answer'),
                    'question_id' => $id,
                    'user_id' => $this->session->get('auth')['id'],
                    'answer_date' => date("Y-m-d H:i:s")
                ]
            );
            // var_dump($submittedAnswer); die();
            $submittedAnswer->save();

            $user = Users::findFirst("id = $submittedAnswer->user_id");
            $user->answered_question += 1;
            $user->save();

            $this->response->redirect("/question/read/$id");
        }
        // store answer to view
        // the user that answers got +1 answered questions
    }

    // brings to edit page
    public function editAction (int $id) {
        // Capture the id of edited question
        $question = Questions::findFirst("question_id = '$id'");
        if ($question != null) {
            $this->view->question = $question;
        }
    }

    // actually update the question
    public function updateAction(int $id) {
        $question = Questions::findFirst("question_id = '$id'");
        // var_dump($this->request->getPost()); die();
        if ($question != null) {
            $question->question_title = $this->request->getPost('question_title');
            $question->question_content = $this->request->getPost('question_content');
            $question->save();
            $this->response->redirect("/question/read/$id");
        }
    }

    public function deleteAction(int $id) {
        $question = Questions::findFirst("question_id = '$id'");
        if ($question != null) {
            // $this->view->deletedQuestion = $question;
            $question->delete();
            $this->response->redirect('/');
        }
    }

    public function bestAction (int $QId, int $AId) {
        // var_dump($QId, $AId); die();
        $question = Questions::findFirst("question_id = '$QId'");
        $answer = Answers::findFirst("answer_id = '$AId'");
        if ($question->question_id === $answer->question_id) {
            if ($answer->best_answer == 0) {
                $answer->best_answer = strval(1);
            }
            else {
                $answer->best_answer = strval(0);
            }
            $answer->save();
        }
        $this->response->redirect("/question/read/$QId");
    }
    
    public function deleteAnsAction (int $QId, int $AId) {
        // var_dump($QId, $AId); die();
        $question = Questions::findFirst("question_id = '$QId'");
        $answer = Answers::findFirst("answer_id = '$AId'");
        if ($question->question_id === $answer->question_id) {
            $answer->delete();
        }
        $this->response->redirect("/question/read/$QId");
    }

    public function searchAction() {
        $searched = $this->request->get('search');
        if ($searched !== null) {
            $questions = Questions::find("question_content LIKE '%" . $searched. "%' 
                OR question_title LIKE '%" . $searched . "%'");
            // var_dump($questions['question_id']); die();
            $this->view->questions = $questions;
        }
        $this->view->searched = $searched;
    }
}

