<p>
    Dear {{$brf_model_user_instance->name}}<br>
    <br>
    Your book request <strong>"{{$brf_model_instance->title}}"</strong> has been <strong>updated</strong> by the Library with the following remarks:
</p>
<p>
    <strong>{!! nl2br(e($brf_model_instance->remarks)) !!}</strong>
</p>

<div style="margin: 15px 0; padding: 0px;">
    ---<br>
    BRF ID: {{$brf_model_instance->id}} </br>
    BRF Request Date: <span>{{ \Carbon\Carbon::parse($brf_model_instance->created_at)->format('Y-m-d') }}</span></br>
    Author: {{$brf_model_instance->author}}</br>
    Title: {{$brf_model_instance->title}}</br>
    Publisher: {{$brf_model_instance->publisher}}</br>
    ISBN: {{$brf_model_instance->isbn}}</br>
    ---
</div>

<p>
    This is an automatically generated Email, kindly do not reply to this mail.<br>
    <br>
    Regards,<br>
    Dy. Librarian<br>
    Ph 4954
</p>