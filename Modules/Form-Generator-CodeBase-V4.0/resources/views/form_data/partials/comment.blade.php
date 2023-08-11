@foreach($comments as $comment)
    <div class="direct-chat-msg">
        <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-left">
                {{$comment->commentedBy->name}}
            </span>
            <span class="direct-chat-timestamp pull-right">
                {{\Carbon\Carbon::parse($comment->created_at)->isoFormat("D/M/YY HH:mm A")}}
            </span>
        </div>
        <!-- /.direct-chat-info -->
        <img class="direct-chat-img" src="https://ui-avatars.com/api/?name={{$comment->commentedBy->name}}">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text">
            {!! $comment->comment !!}
            @if(Auth::user()->id == $comment->user_id)
                <i class="delete-comment fa fa-trash float-right text-danger cursor-pointer" data-comment_id="{{$comment->id}}" data-form_data_id="{{$comment->form_data_id}}"></i>
            @endif
        </div>
    <!-- /.direct-chat-text -->
    </div>
@endforeach