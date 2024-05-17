<x-guest-layout>
    <main class="main" id="top">
        @include('components.home.header')

        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section class="py-0 overflow-hidden" id="banner" data-bs-theme="light">

            <div class="bg-holder overlay"
                 style="background-image:url(../assets/img/generic/Landing-Background.png);background-position: center bottom;"></div>
            <!--/.bg-holder-->

            <div class="container">
                <div class="row flex-center pt-8 pt-lg-10 pb-lg-9 pb-xl-0">
                    <div class="col-md-11 col-lg-8 col-xl-4 pb-7 pb-xl-9 text-center text-xl-start">
                        <h1 class="text-white fw-light text-center" style="color: black">The most personalized content
                            protection tool for creators.</h1>
                        <p class="lead text-white opacity-75">
                            We know how important protecting your content is to you. Take control of what's yours with
                            our bulletproof, easy-t-use cybersecurity platform.
                        </p>
                        <a class="btn btn-outline-light border-2 rounded-pill btn-lg mt-4 fs-0 py-2"
                           href="{{ route('createProject') }}">Get started<span class="fas fa-play ms-2"
                                                                                data-fa-transform="shrink-6 down-1"></span></a>
                        <a class="btn btn-outline-light border-2 rounded-pill btn-lg mt-4 fs-0 py-2"
                           href="{{ route('pages.contact').'?option=free_consultation' }}">Free consultation<span
                                class="fas fa-play ms-2" data-fa-transform="shrink-6 down-1"></span></a>
                    </div>1
                </div>
            </div>a
            <!-- end of .container-->

        </section>
        <!-- <section> close ============================-->
        <!-- ============================================-->

        <section>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <p class="lead">Say goodbye to bogus DMCA takedown requests and “Al-Powered” filings that leave
                            your content on the Internet.</p>
                    </div>
                </div>
                <div class="row flex-center mt-8">
                    <div class="tree-text">
                        <div class="item">
                            <div>
                                <div><h3>24/7 manual and Al-powered monitoring. </h3></div>
                                <p>We see what you see. Unlike other
                                    companies who solely rely on crawlers and
                                    bots for content monitoring, each one of
                                    our users has a dedicated account
                                    manager who manually searches the web
                                    for your keywords to ensure that no
                                    infringing content is left.</p>
                            </div>
                        </div>
                        <div class="item">
                            <div>
                                <div><h3>Reporting and Removal.</h3></div>
                                <p>All of our agents have substantial
                                    backgrounds in legal operations, and we
                                    hold deep partnerships with major website
                                    hosting providers and social media
                                    platforms. We take the accuracy of our
                                    complaints very seriously, and will not send
                                    bogus DMCA removal requests that put your
                                    identity at risk.</p>
                            </div>
                        </div>
                        <div class="item">
                            <div>
                                <div><h3>Individualized care.</h3></div>
                                <p>We know how important this is to you,
                                    which is why we have dedicated
                                    (human!) account managers for each
                                    and every single user. Text us, email us,
                                    call us. We're here to protect your online
                                    presence and content.</p>
                            </div>
                        </div>
                        <div class="item">
                            <div>
                                <div><h3>Transparency.</h3></div>
                                <p>If we can't get it down, no one can. We will
                                    always fight for your content, but will never
                                    sugar-coat what we cannot remove from the
                                    Internet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- <section> close ============================-->
        <!-- ============================================-->


        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section class="bg-light text-center">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="fs-2 fs-sm-4 fs-md-5">If we aren't working out, you can cancel anytime.<br>No hidden
                            fees. No contract.</h1>
                    </div>
                </div>
                <div class="row mt-6 justify-content-center">
                    @foreach($plans as $plan)
                        <div
                            class="col-md-12 col-xxl-3 mx-4 mb-4 bg-white p-5 rounded-lg shadow d-flex flex-column align-items-center justify-content-center"
                            style="border: 1px solid #fff;">
                            <h3 class="h1 font-weight-bold">${{ $plan->price }}<span
                                    class="text-small font-weight-normal ml-2">/month</span></h3>

                            <ul class="list-unstyled my-5 text-small text-left font-weight-normal">
                                @foreach(json_decode($plan->content) as $content)
                                    <li class="mb-3">
                                        <i class="fa fa-check mr-2 text-primary"></i> {{ $content }}
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('plans.show', $plan->slug) }}"
                               class="btn btn-dark btn-block mt-auto shadow rounded-pill">Get Started</a>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- end of .container-->
        </section>
        <!-- <section> close ============================-->
        <!-- ============================================-->
    </main>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const themeToggle = document.getElementById('themeControlToggle');
                themeToggle.addEventListener('change', function () {
                    console.log(123)

                    const isDarkTheme = themeToggle.checked;

                    changeTheme(isDarkTheme);
                });
            });

            function changeTheme(isDarkTheme) {
                const root = document.documentElement;

                root.style.setProperty('--tree-text-background', isDarkTheme ? '#fff' : '#000');
                root.style.setProperty('--tree-text-line-color', isDarkTheme ? '#fff' : '#000');
            }
        </script>
    @endpush
</x-guest-layout>
