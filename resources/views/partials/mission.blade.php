<section class="mission" id="mission" data-fx-mission>
    <div class="mission__art">
        <canvas class="mission__dots" aria-hidden="true"></canvas>
        <div class="mission__frame">
            @include('partials.img', ['src' => config('sando.images.mission'), 'alt' => 'Ilustrasi TADA SWEETSANDO', 'class' => 'mission__img'])
            <span class="mission__glare" aria-hidden="true"></span>
        </div>
    </div>
    <div class="mission__copy">
        <h2 class="display-title display-title--mission">{{ config('sando.mission.title') }}</h2>
        @foreach(config('sando.mission.points', []) as $pt)
            <article class="mission__point" style="--i: {{ $loop->index }}">
                <h3>{{ $pt['title'] }}</h3>
                <p>{{ $pt['text'] }}</p>
            </article>
        @endforeach
    </div>
</section>