<!-- Other Options -->
@php
if($row instanceof Modules\Marketplace\Models\Marketplace) {
    $title = $row->title;
}
@endphp
<div class="other-options">
    <div class="social-share">
        @php
            $titleSharing = match ($row->marketplaceCategory->slug){
                'trainings' => 'Share this Announcement',
                'professional-resources' => 'Share this Announcement',
                'office-space' => 'Share this Listing',
                default => 'Share this page'
            }
        @endphp
        <h5>{{ __("$titleSharing") }}</h5>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $row->getDetailUrl() }}&amp;title={{ $title }}" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i> {{ __("Facebook") }}</a>
        <a href="https://twitter.com/share?url={{ $row->getDetailUrl() }}&amp;title={{ $title }}" target="_blank" class="twitter"><i class="fa-brands fa-x-twitter"></i> {{ __("Twitter") }}</a>
        <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ $row->getDetailUrl() }}" target="_blank" class="linkedin"><i class="fab fa-linkedin"></i> {{ __("Linkedin") }}</a>
{{--        <a href="http://pinterest.com/pin/create/button/?url={{ $row->getDetailUrl() }}&description={{ $title }}" target="_blank" class="google"><i class="fab fa-pinterest"></i> {{ __("Pinterest") }}</a>--}}
    </div>
</div>
