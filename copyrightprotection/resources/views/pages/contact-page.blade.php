<x-guest-layout>
    <div class="pt-5"></div>
    <div class="pt-5 mb-2"></div>
    <main class="main" id="top">
        @include('components.home.header')

        <div class="container" data-layout="container">
            <div class="content">
                @include('parts.flash-message')
                <div class="row g-0">
                    <div class="col-lg-12 pe-lg-2">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0 text-center">Contact Us</h5>
                            </div>
                            <div class="card-body bg-light pb-0">
                                @if(!$agentData->isempty())
                                    <div class="card-body bg-light text-center">
                                        <p>Your Account Manager is {{ $agentData['agent_name'] }}. You can
                                            reach {{ $agentData['agent_name']  }} at:
                                            @if($agentData['agent_phone'] )
                                                <br>Mobile: <a
                                                    href="tel:+{{ $agentData['agent_phone'] }}">+{{ $agentData['agent_phone'] }}</a>
                                            @endif
                                            @if($agentData['agent_email'] )
                                                <br>Mail: <a
                                                    href="mailto:{{ $agentData['agent_email'] }}">{{ $agentData['agent_email'] }}</a>
                                            @endif
                                            <br>
                                            <br>
                                        </p>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="card-body bg-light text-center">
                                        <p>For addition feedback or assistance, please email us at: <br><a
                                                href="mailto:contact@goaine.com">contact@goaine.com</a>
                                            <br>
                                            <br>
                                            Alternatively, please fill out the form below and we will get back to you
                                            during business hours.
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <form method="post" action="{{ route('contact.send') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Name"
                                                   name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" placeholder="Email"
                                                   name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email">Subject</label>
                                            <input type="text" class="form-control" id="subject" placeholder="Subject"
                                                   name="subject" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="message">Question</label>
                                            <textarea type="text" class="form-control" id="message"
                                                      placeholder="Enter your message here" name="message"
                                                      required> </textarea>
                                        </div>
                                        <button class="btn btn-dark btn-sm px-4 mb-2" type="submit"><span
                                                class="fas fa-paper-plane me-2" aria-hidden="true"></span>Send
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
