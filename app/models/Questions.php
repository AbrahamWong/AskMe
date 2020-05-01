<?php

    use Phalcon\Mvc\Model;

    class Questions extends Model {
        public $question_id;
        public $questioner_id;
        public $question_title;
        public $question_content;
        public $question_date;
    }