{{--Gallery--}}
<div class="row" v-if="session.photos.length > 0">
    <div class="gallery-item" v-for="photo in session.photos">
        <div style="position: relative;">
            {{--image--}}
            <a :href="photo.url" data-toggle="lightbox"
               data-gallery="gallery" :data-footer="photo.title">
                <img :src="photo.thumbnail_url" />
            </a>
            @if ($user)
                <div class="abs-top-left">
                    {{--rotate buttons--}}
                    <button class="btn btn-sm btn-primary fade-in" @click="rotatePhoto(photo.id, 'ccw')">
                        <i class="fa fa-undo" title="rotate"></i>
                    </button>
                    <button class="btn btn-sm btn-primary fade-in" @click="rotatePhoto(photo.id, 'cw')">
                        <i class="fa fa-repeat" title="rotate"></i>
                    </button>
                    {{--delete button--}}
                    <confirm-button button-text="X" button-class="btn btn-sm btn-danger fade-in"
                    @click="confirmCallback = function() { deletePhoto(photo.id) }; confirmText = 'Delete photo?'" />
                </div>
            @endif
        </div>
    </div>
</div>
<div class="small-text" v-if="session.photos.length == 0">
    no photos yet
</div>