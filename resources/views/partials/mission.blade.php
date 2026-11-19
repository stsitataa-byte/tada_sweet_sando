<section class="mission" id="mission">
    <div class="mission__art">
        @include('partials.img', ['src' => config('sando.images.mission'), 'alt' => 'Ilustrasi TADA SWEETSANDO', 'class' => 'mission__img'])
    </div>
    <div class="mission__copy">
        <h2 class="display-title display-title--mission">{{ config('sando.mission.title') }}</h2>
        @foreach(config('sando.mission.points', []) as $pt)
            <h3>{{ $pt['title'] }}</h3>
            <p>{{ $pt['text'] }}</p>
        @endforeach
    </div>
</section>