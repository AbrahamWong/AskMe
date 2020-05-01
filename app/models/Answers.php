<?php

    use Phalcon\Mvc\Model;

    class Answers extends Model {
        public $answer_id;
        public $question_id;
        public $user_id;
        public $answer_content;
        public $answer_date;
        public $best_answer;
    }