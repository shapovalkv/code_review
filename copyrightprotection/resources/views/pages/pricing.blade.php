<x-guest-layout>
    <div class="pt-5"></div>
    <div class="pt-5 mb-2"></div>
    <main class="main" id="top">
        @include('components.home.header')

        <section class="bg-light text-center">
            <div class="container">
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
    </main>
    <div class="pt-5 mb-8"></div>
    <div class="pt-5 mb-10"></div>
</x-guest-layout>

