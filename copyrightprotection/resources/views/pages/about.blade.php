<x-guest-layout>
    <div class="pt-5"></div>
    <div class="pt-5 mb-2"></div>
    <main class="main" id="top">
        <div class="container" data-layout="container">
            @include('components.home.header')
            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.remove('container');
                    container.classList.add('container-fluid');
                }
            </script>
            <div class="content">
                @include('parts.flash-message')
                <div class="row g-0">
                    <div class="col-lg-12 pe-lg-2">
                        <div class="card mb-3">
                            <div class="card-header pb-0">
                                <h1 class="mb-0 text-center">About</h1>
                            </div>
                            <div class="card-body bg-light">
                                <p>Our mission is simple: to provide expedited and discreet legal removal services for
                                    content ranging from copyright violations to illegal pornography, all while ensuring
                                    your privacy remains paramount.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4>
                                    Expedited Removals Without Empty Promises
                                </h4>
                                <p>
                                    We understand that when you're faced with the urgent need to remove sensitive
                                    content, time is of the essence. We do not make empty promises or guarantees we
                                    can't fulfill. Instead, we rely on our expertise and a network of legal
                                    professionals to get the job done swiftly and discreetly.
                                </p>
                                <hr class="my-3">
                                <h4>
                                    Privacy
                                </h4>
                                <p>
                                    Your privacy is our utmost concern. We know how sensitive these matters can be, and
                                    we are dedicated to ensuring your personal information remains confidential
                                    throughout the removal process to ensure that your identity is protected at all
                                    times.
                                </p>
                                <hr class="my-3">
                                <h4>
                                    Pro-Bono Legal Advice
                                </h4>
                                <p>
                                    We believe in equal access to justice. That's why we offer pro-bono legal advice for
                                    those who may require it. We understand that not everyone has the means to pursue
                                    legal action, and we are committed to helping those in need find the assistance they
                                    deserve. For more information, please contact us here.
                                </p>
                                <hr class="my-3">
                                <h4>
                                    Empowering Change
                                </h4>
                                <p>
                                    We invite you to join us in our mission to empower individuals and protect their
                                    rights in the digital realm. Whether you require assistance with copyright removal
                                    or need to address issues related to illegal content, we are here to support you
                                    every step of the way. Together, we can create a safer, more just online environment
                                    for all.
                                </p>
                                <p>
                                    Your trust in our services is the driving force behind our commitment to making the
                                    digital world a better place for everyone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
