@php
        $score = isset($score)?$score:0;
        $score = $score<0?0:$score;
        $score = $score>5?5:$score;
        $stars = [

        ];
        $round = floor($score);
        $remain = $score - $round;
        $half = $remain >= 0.5 ? 1: 0;
        for ($i = 1; $i<=$round; $i++):
        $stars[] = 'fa fa-star';
        endfor;
        if($half){
            $stars[] = 'fa fa-star-half-o';
        }
        $missing = 5 - ($round + $half);
        for ($i = 1; $i<=$missing; $i++):
        $stars[] = 'fa fa-star-o';
        endfor;
@endphp
<div class="rating-stars" title="{!! $score !!}">
    @foreach($stars as $star)
        <i class="{!! $star !!}"></i>
    @endforeach
</div>