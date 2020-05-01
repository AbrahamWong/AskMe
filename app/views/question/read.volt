<!-- menu bar beside -->
<!-- navbar -->

<!-- If question exists -->
{% if question !== null %}
    <h2>{{question.question_title}}</h2>
    <p>{{question.question_content}}</p>

    {% if session.get('auth') !== null %}
        {% if session.get('auth')['id'] === question.questioner_id %}    
            <div class="form-inline my-3">
                <form action='/question/edit/{{question.question_id}}' class="mx-3">
                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
                <form action='/question/delete/{{question.question_id}}' class="mx-3">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div> 
        {% endif %}

        <!-- See all answer here -->
        <h5>Answers:</h5>
        {% for answer in answers %}
            {% if answer.best_answer === '1' %}
            <div class="card card-body my-2 text-white bg-success">
            {% else %}
            <div class="card card-body my-2">
            {% endif %}
                <div class="row justify-content-between">
                   <div class="px-4">
                        <h5>
                            {% for user in users %}
                                {% if user.id === answer.user_id %}
                                    {{user.name}}
                                {% endif %}
                            {% endfor %}
                        </h5>
                        <p>{{answer.answer_content}}</p>
                   </div>
                   <div class="px-4">
                    {% if session.get('auth')['id'] === question.questioner_id %}
                        <a href='/question/best/{{question.question_id}}/{{answer.answer_id}}' style="text-decoration: none;">     
                            <img src="/drawable/check.png" alt="Pick as best answer" 
                                class="mx-2" width="25" height="25">
                        </a>
                        <a href="/question/deleteAns/{{question.question_id}}/{{answer.answer_id}}" 
                            style="text-decoration: none;">
                            <img src="/drawable/delete.png" alt="Delete Answer" 
                                class="mx-2" width="25" height="25">
                        </a>
                            
                       {% endif %}
                   </div>
                </div>
            </div>
        {% endfor %}

        <br>
        <p onclick="onClicked()" class="text-primary">Answer Question.</p>
        <form action='/question/answer/{{question.question_id}}' method="POST" class="invisible form-group" id="ans">
            <textarea  rows="6" cols="40" name="answer" class="form-control"></textarea>
            <br>
            <button type="submit" class="btn btn-primary" class="form-control">Submit Answer</button>
        </form>
        <!-- Create options to rate answer, or something like that -->
    {% else %}
        <p><a href="/login">Log in</a> to answer this question.</p>
    {% endif %}

<!-- If not... -->
{% else %}
    <!-- 404 not found -->
{% endif %}

<script>
    function onClicked() {
        document.getElementById('ans').className = 'visible form-group';
    }
</script>
