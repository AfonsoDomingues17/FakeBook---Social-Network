<article class="post" data-id="{{ $post->id }}">
    <a href="{{route('profile',['user_id' => $post->owner_id])}}">
    <section id="info">
        <img src="https://image.freepik.com/free-vector/instagram-post-template-with-notifications_23-2147815662.jpg" alt="">
        <span id="description"><p>{{$post->description}}</p></span>
        <span id="datecreation"><p>{{$post->datecreation}}</p></span>
    </section>
    </a>
</article>