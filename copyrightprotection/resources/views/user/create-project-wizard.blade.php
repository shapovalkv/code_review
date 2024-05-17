<x-app-layout>
    <div class="row g-3 mb-3">
        <div class="col-lg-12 col-xl-12 col-xxl-12 h-100">
            <div class="d-flex mb-4">
                <div class="col">
                    <h5 class="mb-0 text-primary position-relative"><span class="bg-white text-dark pe-3">Create new Project</span><span class="border position-absolute top-50 translate-middle-y w-100 start-0 z-n1"></span></h5>
                </div>
            </div>
            <div>
                @livewire('create-project-wizard')
            </div>
        </div>
        <div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px">
                <div class="modal-content position-relative p-5">
                    <div class="d-flex align-items-center">
                        <div class="lottie me-3" data-options='{"path":"../../assets/img/animated-icons/warning-light.json"}'></div>
                        <div class="flex-1">
                            <button class="btn btn-link text-danger position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="modal"><span class="fas fa-times"></span></button>
                            <p class="mb-0">You don't have access to the link. Please try again.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
