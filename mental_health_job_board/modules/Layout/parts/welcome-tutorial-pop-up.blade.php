<div class="tutorial-popup modal" id="tutorial-pop-up">
    <!-- Welcome modal -->
    <div id="welcome-pop-up-modal" class="welcome-popup">
        <div class="apply-job-form default-form">
            <div class="form-inner">
                @if(is_employer())
                    {!! setting_item('employer_tutorial_text') !!}
                @endif
                @if(is_candidate())
                    {!! setting_item('candidate_tutorial_text') !!}
                @endif
                @if(is_marketplace_user())
                    {!! setting_item('marketplace_user_tutorial_text') !!}
                @endif
            </div>
        </div>
    </div>
</div>
