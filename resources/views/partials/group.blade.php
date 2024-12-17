<article class="group" data-id="{{ $group->id }}">
    <a href="#">
        <section id="info">
            <img src="{{ route('groupPhoto', ['group_id' => $group->id]) }}" width="100"  height="100" alt="group picture">
            <div class="group-info">
                <span id="name"><p>{{$group->name}}</p></span>
            </div>
    </a> 
        </section>
</article>