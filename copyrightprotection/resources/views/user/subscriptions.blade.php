<x-app-layout>
    @include('components.orders-buttons')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    You will be charged ${{ number_format($plan->price, 2) }} for {{ $plan->name }} Plan
                </div>
                <div class="card-body">
                    <form id="payment-form" action="{{ route('subscription.create') }}" method="POST">
                        @csrf
                        <div class="card-body bg-light">
                            <div class="form-check mb-0">
                                <label class="form-check-label d-flex align-items-center mb-0"
                                       for="credit-card"><span class="fs-1 text-nowrap">Credit Card</span><img
                                        class="d-none d-sm-inline-block ms-2 mt-lg-0"
                                        src="{{ asset('assets/img/icons/icon-payment-methods.png') }}" height="20"
                                        alt=""/>
                                </label>
                            </div>
                            <p class="fs--1 mb-4">Safe money transfer using your bank accounts. Visa, maestro,
                                discover, american express.</p>
                            <div>
                                <div class="row gx-3 mb-3">
                                    <div class="col">
                                        <label
                                            class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                            for="cardNumber">Card Number</label>
                                        <div class="form-control" id="card-number"></div>
                                    </div>
                                    <div class="col">
                                        <label
                                            class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                            for="cardName">Name of Card</label>
                                        <input required class="form-control" name="cardName" id="cardName"
                                               placeholder="John Doe"
                                               type="text"
                                               style="color: #006fff "
                                        />
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-6 col-sm-3">
                                        <label
                                            class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                            for="expDate">Exp Date</label>
                                        <div class="form-control" id="card-expiry"></div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <label
                                            class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                            for="cvv">CVV<span class="ms-1" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="Card verification value"><span
                                                    class="fa fa-question-circle"></span></span></label>
                                        <div  class="form-control" id="card-cvc"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="plan" id="plan" value="{{ $plan->id }}">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <hr>
                                <button type="submit" class="btn btn-dark" id="card-button" data-secret="{{ $intent->client_secret }}">Purchase</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ env('STRIPE_KEY') }}')

            const elements = stripe.elements()

            const cardNumberElement = elements.create('cardNumber');
            cardNumberElement.update({ style: { base: { color: '#006fff' } } });
            cardNumberElement.mount('#card-number');

            const cardExpiryElement = elements.create('cardExpiry');
            cardExpiryElement.update({ style: { base: { color: '#006fff' } } });
            cardExpiryElement.mount('#card-expiry');

            const cardCvcElement = elements.create('cardCvc');
            cardCvcElement.update({ style: { base: { color: '#006fff' } } });
            cardCvcElement.mount('#card-cvc');

            const form = document.getElementById('payment-form')
            const cardBtn = document.getElementById('card-button')
            const cardHolderName = document.getElementById('cardName')

            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("card-button").addEventListener("click", function() {
                    if (confirm("Are you sure that you want to select this plan? If you have active plan for this project it will be canceled")) {
                        stripeSubmit()
                    }
                });
            });

            function stripeSubmit(){

                form.addEventListener('submit', async (e) => {
                    e.preventDefault()

                    cardBtn.disabled = true
                    const { setupIntent, error } = await stripe.confirmCardSetup(
                        cardBtn.dataset.secret, {
                            payment_method: {
                                card: cardNumberElement,
                                billing_details: {
                                    name: cardHolderName.value
                                }
                            }
                        }
                    )

                    if(error) {
                        cardBtn.disable = false
                    } else {
                        let token = document.createElement('input')
                        token.setAttribute('type', 'hidden')
                        token.setAttribute('name', 'token')
                        token.setAttribute('value', setupIntent.payment_method)
                        form.appendChild(token)
                        form.submit();
                    }

                })
            }
        </script>
    @endpush
</x-app-layout>
