{{--Gallery--}}
<div class="row">
    <div class="gallery-item" v-for="photo in session.photos">
        <div style="position: relative;">
            {{--image--}}
            <a :href="photo.url" data-toggle="lightbox"
               data-gallery="gallery" :data-footer="photo.title">
                <img :src="photo.thumbnail_url" />
            </a>
            {{--delete button--}}
            <button type="button" class="btn btn-sm btn-danger abs-top-left fade-in" @click.prevent="deletePhoto(photo.id)">
                X
            </button>
        </div>
    </div>
</div>