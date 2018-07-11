<html>
<head></head>
<body>
<h4>Hi {{$user->name}},</h4>
<p>Here is you score-card in {{$exam->name}} exam that you attended on {{$answer->created_at->format('d M, Y ')}} on our online exam portal <a href="//exams.aptitudetrivandrum.com">exams.aptitudetrivandrum.com</a></p>
{{--<br>--}}

{!! $answer->exam_summary_html !!}

{{--<h4>Total marks: {{$answer->total_marks}}</h4>--}}
{{--<h4>Total correct answers: {{$answer->correct_answers}}</h4>--}}
{{--<h4>Total wrong answers: {{$answer->wrong_answers}}</h4>--}}
<br>
<p>
    Regards, <br>
    Aptitude Centre
</p>
</body>
</html>
