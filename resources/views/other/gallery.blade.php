{{--Gallery--}}
<div class="row" v-if="session.photos.length > 0">
    <div class="gallery-item" v-for="photo in session.photos">
        <div style="position: relative;">
            {{--image--}}
            <a :href="photo.url" data-toggle="lightbox"
               data-gallery="gallery" :data-footer="photo.title">
                <img :src="photo.thumbnail_url" />
            </a>
            {{--delete button--}}
            @if ($user)
            <confirm-button button-text="X" button-class="btn btn-sm btn-danger abs-top-left fade-in"
                @click="confirmCallback = function() { deletePhoto(photo.id) }; confirmText = 'Delete photo?'" />
            @endif
        </div>
    </div>
</div>
<div class="small-text" v-if="session.photos.length == 0">
    no photos yet
</div>