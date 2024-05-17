<x-app-layout>

    @include('components.orders-buttons')

    <section class="bg-light text-center">
        <div class="container">
            <div class="row mt-6 justify-content-center">
                @foreach($plans as $plan)
                    <div
                        class="col-md-12 col-xxl-3 mx-4 mb-4 bg-white p-5 rounded-lg shadow d-flex flex-column align-items-center justify-content-center"
                    {{ $plan->id === $user_project->projectSubscription->plan->id && $user_project->subscription($user_project->projectSubscription->name)->active() ? 'selected-box' : '' }}
                    "
                    style="border: 1px solid #fff;"
                    >
                    <h1 class="h6 text-uppercase font-weight-bold mb-4">{{ $plan->id === $user_project->projectSubscription->plan->id && $user_project->subscription($user_project->projectSubscription->name)->active() ? 'Current Plan: ' : '' }}{{ $plan->name }}</h1>
                    <h3 class="h1 font-weight-bold">${{ $plan->price }}<span
                            class="text-small font-weight-normal ml-2">/month</span></h3>

                    <ul class="list-unstyled my-5 text-small text-left font-weight-normal">
                        @foreach(json_decode($plan->content) as $content)
                            <li class="mb-3">
                                <i class="fa fa-check mr-2 text-primary"></i> {{ $content }}
                            </li>
                        @endforeach
                    </ul>
                    @if($plan->id == auth()->user()->selectedProject->projectSubscription->plan->id && auth()->user()->selectedProject->projectSubscription->stripe_status === 'active')
                        <form id="cancel-form" action="{{ route('subscription.cancel') }}"
                              method="post">
                            @csrf
                            <a class="btn btn-danger  btn-block mt-auto shadow rounded-pill"
                               id="cancel-button" type="button">Cancel</a>
                        </form>
                    @else
                        <a href="{{ route('plans.show', $plan->slug) }}"
                           class="btn btn-dark  btn-block mt-auto shadow rounded-pill">Buy Now</a>
                    @endif
            </div>
            @endforeach
        </div>
        <!-- end of .container-->
    </section>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("cancel-button").addEventListener("click", function () {
                        document.getElementById("cancel-form").submit();
                });
            });
        </script>
    @endpush
</x-app-layout>
