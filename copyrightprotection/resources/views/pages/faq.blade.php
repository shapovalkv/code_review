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
                                <h5 class="mb-0 text-center">FAQ</h5>
                            </div>
                            <div class="card-body bg-light">
                                <div class="accordion border rounded overflow-hidden" id="accordionFaq">
                                    <div class="card shadow-none rounded-bottom-0 border-bottom">
                                        <div class="accordion-item border-0">
                                            <div class="card-header p-0" id="faqAccordionHeading1">
                                                <button
                                                    class="accordion-button btn btn-link text-decoration-none d-block w-100 py-2 px-3 collapsed border-0 text-start rounded-0 shadow-none"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFaqAccordion1"
                                                    aria-expanded="false" aria-controls="collapseFaqAccordion1"><span
                                                        class="fas fa-caret-right accordion-icon me-3"
                                                        data-fa-transform="shrink-2"></span><span
                                                        class="fw-medium font-sans-serif text-900">How does Copyright Removal work?</span>
                                                </button>
                                            </div>
                                            <div class="accordion-collapse collapse" id="collapseFaqAccordion1"
                                                 aria-labelledby="faqAccordionHeading1" data-parent="#accordionFaq">
                                                <div class="accordion-body p-0">
                                                    <div class="card-body pt-2">
                                                        <p class="ps-3 mb-0">Copyright removal typically involves the
                                                            following steps:
                                                            <br>
                                                            <br>
                                                            1. Identify the infringing content: Determine where your
                                                            copyrighted material is being used without authorization.
                                                            <br>
                                                            2. Gather evidence: Collect evidence to demonstrate your
                                                            ownership of the copyrighted material.
                                                            <br>
                                                            3. Send a takedown notice: Submit a Digital Millennium
                                                            Copyright Act (DMCA) takedown notice or an equivalent notice
                                                            to the hosting platform or service provider.
                                                            <br>
                                                            4. Review and removal: The platform reviews your notice and,
                                                            if valid, removes the infringing content.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card shadow-none rounded-0 border-bottom">
                                        <div class="accordion-item border-0">
                                            <div class="card-header p-0" id="faqAccordionHeading2">
                                                <button
                                                    class="accordion-button btn btn-link text-decoration-none d-block w-100 py-2 px-3 collapsed border-0 text-start rounded-0 shadow-none"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFaqAccordion2"
                                                    aria-expanded="false" aria-controls="collapseFaqAccordion2"><span
                                                        class="fas fa-caret-right accordion-icon me-3"
                                                        data-fa-transform="shrink-2"></span><span
                                                        class="fw-medium font-sans-serif text-900">How does Illegal Content removal work?</span>
                                                </button>
                                            </div>
                                            <div class="accordion-collapse collapse" id="collapseFaqAccordion2"
                                                 aria-labelledby="faqAccordionHeading2" data-parent="#accordionFaq">
                                                <div class="accordion-body p-0">
                                                    <div class="card-body pt-2">
                                                        <p class="ps-3 mb-0">The removal of illegal content, such as
                                                            revenge porn, typically involves several steps, but it's
                                                            important to note that the exact process may vary depending
                                                            on the platform hosting the content and the applicable laws
                                                            in your jurisdiction. Here is a general overview of how the
                                                            removal of revenge porn might work:
                                                            <br>
                                                            <br>
                                                            <b>1.Identify the Content and Gather Evidence:</b>
                                                            First,
                                                            identify the specific instances of revenge porn. This
                                                            could include explicit images or videos of you that were
                                                            shared without your consent. Gather evidence to
                                                            demonstrate that you did not consent to the distribution
                                                            of these materials. This may include text messages,
                                                            emails, or other communication that shows the lack of
                                                            consent.
                                                            <br>
                                                            <br>
                                                            <b>2. Consult with Legal Professionals:</b> In cases of
                                                            revenge porn, it's advisable to consult with legal
                                                            professionals, such as an attorney specializing in
                                                            cybercrimes or privacy laws. They can provide guidance on
                                                            the best course of action based on the laws in your
                                                            jurisdiction.
                                                            <br>
                                                            <br>
                                                            We know that legal guidance can be costly, which is why we
                                                            offer our expertise free of charge with a subscription. For
                                                            pro bono legal advice or a case consultation, <a
                                                                href="{{ route('pages.contact') }}">please contact
                                                                us here.</a>
                                                            <br>
                                                            <br>
                                                            <b>3. Contact the Hosting Platform:</b> Once you have legal
                                                            advice and evidence, we contact our network to seek removal
                                                            of the infringing content. This is typically done by sending
                                                            a formal takedown notice or report to the platform's
                                                            designated abuse or legal department.
                                                            <br>
                                                            <br>
                                                            <b>4. Platform Review: </b> The hosting platform will review
                                                            your report and the evidence provided. If they determine
                                                            that the content violates their terms of service or
                                                            applicable laws, they may take action to remove it. Many
                                                            platforms have established procedures for handling such
                                                            reports.
                                                            <br>
                                                            <br>
                                                            <b>5. Legal Action:</b> In some cases, legal action against
                                                            the individual who posted the revenge porn may be necessary.
                                                            Your attorney can advise you on whether pursuing legal
                                                            action is appropriate in your situation.
                                                            <br>
                                                            <br>
                                                            <b>6. Support and Counseling:</b> First,
                                                            Dealing with revenge porn can be violating and emotionally
                                                            distressing. Consider seeking support from mental health
                                                            professionals or support groups specializing in this issue
                                                            to help you cope with the emotional impact.
                                                            <br>
                                                            <br>
                                                            It's crucial to remember that laws regarding revenge porn
                                                            vary by jurisdiction, and what is considered illegal in one
                                                            place may not be in another. Therefore, consulting with
                                                            legal professionals who are knowledgeable about the laws in
                                                            your area is essential.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card shadow-none rounded-0 border-bottom">
                                        <div class="accordion-item border-0">
                                            <div class="card-header p-0" id="faqAccordionHeading3">
                                                <button
                                                    class="accordion-button btn btn-link text-decoration-none d-block w-100 py-2 px-3 collapsed border-0 text-start rounded-0 shadow-none"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFaqAccordion3"
                                                    aria-expanded="false" aria-controls="collapseFaqAccordion3"><span
                                                        class="fas fa-caret-right accordion-icon me-3"
                                                        data-fa-transform="shrink-2"></span><span
                                                        class="fw-medium font-sans-serif text-900">How long do removals take?</span>
                                                </button>
                                            </div>
                                            <div class="accordion-collapse collapse" id="collapseFaqAccordion3"
                                                 aria-labelledby="faqAccordionHeading3" data-parent="#accordionFaq">
                                                <div class="accordion-body p-0">
                                                    <div class="card-body pt-2">
                                                        <p class="ps-3 mb-0">
                                                            The time it takes to remove content can vary widely. It
                                                            depends on factors like the responsiveness of the hosting
                                                            platform, the complexity of the case, and whether the
                                                            infringer disputes the claim.
                                                            <br>
                                                            <br>
                                                            In some cases, removals can happen within hours, while
                                                            others may take several weeks.Thanks to our network, we are
                                                            usually able to remove infringing content from search
                                                            engines and social media sites within 24 hours.
                                                            <br>
                                                            <br>
                                                            For specific information regarding the length of removal,
                                                            please contact your account manager.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card shadow-none rounded-0 border-bottom">
                                        <div class="accordion-item border-0">
                                            <div class="card-header p-0" id="faqAccordionHeading4">
                                                <button
                                                    class="accordion-button btn btn-link text-decoration-none d-block w-100 py-2 px-3 collapsed border-0 text-start rounded-0 shadow-none"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFaqAccordion4"
                                                    aria-expanded="false" aria-controls="collapseFaqAccordion4"><span
                                                        class="fas fa-caret-right accordion-icon me-3"
                                                        data-fa-transform="shrink-2"></span><span
                                                        class="fw-medium font-sans-serif text-900">How much is the service?</span>
                                                </button>
                                            </div>
                                            <div class="accordion-collapse collapse" id="collapseFaqAccordion4"
                                                 aria-labelledby="faqAccordionHeading4" data-parent="#accordionFaq">
                                                <div class="accordion-body p-0">
                                                    <div class="card-body pt-2">
                                                        <p class="ps-3 mb-0">Please see our service pricing on <a href="{{ route('pages.pricing') }}">Pricing page</a>.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card shadow-none rounded-0 ">
                                        <div class="accordion-item border-0">
                                            <div class="card-header p-0" id="faqAccordionHeading5">
                                                <button
                                                    class="accordion-button btn btn-link text-decoration-none d-block w-100 py-2 px-3 collapsed border-0 text-start rounded-0 shadow-none"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFaqAccordion5"
                                                    aria-expanded="false" aria-controls="collapseFaqAccordion4"><span
                                                        class="fas fa-caret-right accordion-icon me-3"
                                                        data-fa-transform="shrink-2"></span><span
                                                        class="fw-medium font-sans-serif text-900">What can I get removed?</span>
                                                </button>
                                            </div>
                                            <div class="accordion-collapse collapse" id="collapseFaqAccordion5"
                                                 aria-labelledby="faqAccordionHeading5" data-parent="#accordionFaq">
                                                <div class="accordion-body p-0">
                                                    <div class="card-body pt-2">
                                                        <p class="ps-3 mb-0">
                                                            We typically focus on unauthorized use of text, images, videos,
                                                            music, software, or other works. These services help you get
                                                            infringing copies or instances of your copyrighted material
                                                            removed from websites, social media platforms, or other online
                                                            locations.
                                                            <br>
                                                            <br>
                                                            What is DMCA?
                                                            DMCA stands for the Digital Millennium Copyright Act, a U.S.
                                                            copyright law that provides a legal framework for addressing
                                                            copyright infringement on the internet. It includes provisions
                                                            for copyright holders to send takedown notices to online service
                                                            providers when their copyrighted material is being used without
                                                            permission. Compliance with DMCA takedown notices is a
                                                            requirement for many online platforms and websites.
                                                            <br>
                                                            <br>
                                                            Who will be my account manager?
                                                            The account manager for copyright removal services will
                                                            typically be assigned by the specific service provider you
                                                            choose to work with. This person or team will guide you through
                                                            the process, help gather necessary information, draft and submit
                                                            takedown notices, and provide updates on the progress of your
                                                            removal requests. It's important to inquire with the service
                                                            provider about the qualifications and experience of your account
                                                            manager and the level of support they will provide.
                                                            <br>
                                                            <br>
                                                            Remember that the specifics of copyright removal can vary
                                                            depending on the platform, the type of copyrighted material
                                                            involved, and the legal jurisdiction. It's advisable to consult
                                                            with a legal expert or a copyright removal service provider for
                                                            personalized assistance tailored to your situation.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>

