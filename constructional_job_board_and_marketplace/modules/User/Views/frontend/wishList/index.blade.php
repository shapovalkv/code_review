@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('head')
@endsection
@section('content')
<div
    id="manage-bookmarks"
    data-page-with-vue
    class="manage-page"
    data-rows="{{ json_encode($rows) }}"
    data-filters="{{ json_encode($filters) }}"
    data-pagination="{{ json_encode($pagination) }}"
    data-job-list="{{ json_encode($job_list) }}"
    data-bookmark-count="{{ json_encode($bookmark_counter) }}"
    data-is-candidate="{{ is_candidate() }}"
>
    <div class="manage-page-header manage-page-header--bookmark">
        <div class="manage-page-header__title-wrap manage-page-header__title-wrap--bookmark">
            <h3 class="manage-page-header__title manage-page-header__title--bookmark">{{ __("Bookmarks") }}</h3>
        </div>

        <div class="manage-page-header__search manage-page-header__search--bookmark">
            <div class="manage-page-header__input-wrap manage-page-header__input-wrap--bookmark">
                <b-form-input
                    type="text"
                    name="keywords"
                    placeholder="{{__('Search')}}"
                    class="manage-page-header__search-input"
                    v-model="keywords"
                    @keydown.enter="handleSearchByKeywords"
                ></b-form-input>
                <i class="manage-page-header__search-icon ri-search-line"></i>
            </div>

            <b-button
                variant="primary"
                class="manage-page-header__search-button ri-search-line"
                @click="handleSearchByKeywords"
            ></b-button>
        </div>

        <div class="manage-page-header__tabs manage-page-header__tabs--bookmark">
            <ul class="manage-page-header__tabs-list nav nav-pills flex-nowrap" id="pills-tab" role="tablist">
                <li class="manage-page-header__tabs-item manage-page-header__tabs-item--bookmark nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 0 }]"
                        type="button"
                        role="tab"
                        aria-controls="resume"
                        aria-selected="true"
                        @click="handleSetActiveTab(0, 'candidate')"
                    >
                        Resumes (@{{ counts.candidate }})
                    </button>
                </li>
                <li class="manage-page-header__tabs-item manage-page-header__tabs-item--bookmark nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 1 }]"
                        type="button"
                        role="tab"
                        aria-controls="jobs"
                        aria-selected="false"
                        @click="handleSetActiveTab(1, 'job')"
                    >
                        Jobs (@{{ counts.job }})
                    </button>
                </li>
                <li class="manage-page-header__tabs-item manage-page-header__tabs-item--bookmark nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 2 }]"
                        type="button"
                        role="tab"
                        aria-controls="equipment"
                        aria-selected="false"
                        @click="handleSetActiveTab(2, 'equipment')"
                    >
                        Equipment (@{{ counts.equipment }})
                    </button>
                </li>
                <li class="manage-page-header__tabs-item manage-page-header__tabs-item--bookmark nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 3 }]"
                        type="button"
                        role="tab"
                        aria-controls="company"
                        aria-selected="false"
                        @click="handleSetActiveTab(3, 'company')"
                    >
                        Company (@{{ counts.company }})
                    </button>
                </li>
            </ul>
        </div>
    </div>

    @include('admin.message')

    <div class="manage-page__body">
        <b-tabs v-model="activeTab" class="tab-content" id="pills-tabContent">
            <b-tab>
                <div class="manage-page__items manage-page__items--bookmark">
                    <template v-if="items.length > 0">
                        <candidate-item
                            v-for="item in items"
                            :key="item.id"
                            :candidate="item"
                            :is-show-footer="true"
                            :is-check-user-by-request="false"
                            :is-dashboard="true"
                            class="manage-page__candidate-item"
                            @open-change-status-modal="handleOpenChangeStatusModal"
                        ></candidate-item>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you don't have any bookmarks saved</div>
                        </div>
                    </div>
                </div>
            </b-tab>

            <b-tab>
                <div class="manage-page__items manage-page__items--bookmark">
                    <template v-if="items.length > 0">
                        <job-page-item
                            v-for="item in items"
                            :key="item.id"
                            :job="item"
                            :is-show-apply-btn="false"
                            :is-show-footer="true"
                            :is-check-user-by-request="false"
                            :is-dashboard="true"
                            :is-candidate="isCandidate"
                        ></job-page-item>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you don't have any bookmarks saved</div>
                        </div>
                    </div>
                </div>
            </b-tab>

            <b-tab>
                <div class="manage-page__items manage-page__items--bookmark">
                    <template v-if="items.length > 0">
                        <equipment-card
                            v-for="equipment in items"
                            :key="equipment.id"
                            :card="equipment"
                            :is-show-footer="true"
                            :is-check-user-by-request="false"
                            :is-dashboard="true"
                        ></equipment-card>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you don't have any bookmarks saved</div>
                        </div>
                    </div>
                </div>
            </b-tab>

            <b-tab>
                <div class="manage-page__items manage-page__items--bookmark">
                    <template v-if="items.length > 0">
                        <company-page-item
                            v-for="company in items"
                            :key="company.id"
                            :company="company"
                            :is-show-footer="true"
                            :is-check-user-by-request="false"
                            :is-dashboard="true"
                            :is-candidate="isCandidate"
                        ></company-page-item>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you don't have any bookmarks saved</div>
                        </div>
                    </div>
                </div>
            </b-tab>
        </b-tabs>

        <div v-if="isShowLoadMoreItems" class="load_more_box"></div>
    </div>

    <modal-change-status
        :visible-modal-name="visibleModalName"
        :item="activeItem"
        :jobs="jobList"
        :statuses="optionsMainActions"
        :is-disabled-job-input="false"
        @close-modal="handleUpdateModal"
        @update-modal="handleUpdateModal"
    ></modal-change-status>

    <modal-result-request
        :visible-modal-name="visibleModalName"
        :type="activeItem?.type || 'error'"
        sub-title-success="You have successfully switched the applicant!"
        @close-modal="handleUpdateModal"
    ></modal-result-request>
</div>
@endsection
@section('footer')
    <script src="{{ mix('js/manageBookmarks.js', $manifestDir) }}"></script>
@endsection
