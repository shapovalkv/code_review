@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')
<div
    id="manage-job"
    data-page-with-vue
    class="manage-page"
    data-filters="{{ json_encode($filters) }}"
    data-rows="{{ json_encode($rows) }}"
    data-pagination="{{ json_encode($pagination) }}"
    data-is-candidate="{{ is_candidate() }}"
>
    <div class="manage-page-header">
        <div class="manage-page-header__title-wrap">
            <h3 class="manage-page-header__title">{{ __("My Job Posts") }}</h3>
        {{--            TODO change numbers to real    --}}
            <div class="manage-page-header__subtitle">{{ __("20 total job slots  / 6 available") }}</div>
        </div>

{{--        <div class="manage-page-header__search">--}}
{{--            <div class="manage-page-header__input-wrap">--}}
{{--                <b-form-input--}}
{{--                    type="text"--}}
{{--                    name="keywords"--}}
{{--                    value="{{ request()->input('keywords') }}"--}}
{{--                    placeholder="{{__('Search')}}"--}}
{{--                    class="manage-page-header__search-input"--}}
{{--                    v-model="keywords"--}}
{{--                ></b-form-input>--}}
{{--                <i class="manage-page-header__search-icon ri-search-line"></i>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="manage-page-header__tabs">
            <ul class="manage-page-header__tabs-list nav nav-pills" id="pills-tab" role="tablist">
                <li class="manage-page-header__tabs-item nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 0 }]"
                        type="button"
                        role="tab"
                        aria-controls="all-job"
                        aria-selected="true"
                        @click="handleSetActiveTab(0, '')"
                    >
                        All Jobs (@{{ counts.all }})
                    </button>
                </li>
                <li class="manage-page-header__tabs-item nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 1 }]"
                        type="button"
                        role="tab"
                        aria-controls="active-job"
                        aria-selected="false"
                        @click="handleSetActiveTab(1, 'publish')"
                    >
                        Active (@{{ counts.active }})
                    </button>
                </li>
                <li class="manage-page-header__tabs-item nav-item" role="presentation">
                    <button
                        :class="['manage-page-header__tabs-btn nav-link', { active: activeTab === 2 }]"
                        type="button"
                        role="tab"
                        aria-controls="inactive-job"
                        aria-selected="false"
                        @click="handleSetActiveTab(2, 'draft')"
                    >
                        Inactive (@{{ counts.inactive }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="manage-page-header__sort">
            <div class="manage-page-header__sort-top">
                <f-dropdown
                    class="job-info-header__search-dropdown"
                    v-model="limit"
                    :options="filters.limit.items"
                    custom-label="name"
                    custom-id="slug"
                    title="Per page:"
                    :offset="[0, 4]"
                    is-right
                    icon="ri-file-list-line"
                    @update:model-value="handleUpdateDropdowns($event, 'limit')"
                >
                    <template #button-content>
                        @{{ limit?.name }}
                    </template>
                </f-dropdown>

                <f-dropdown
                    class="job-info-header__search-dropdown"
                    v-model="orderby"
                    :options="filters?.orderby?.items"
                    custom-label="name"
                    custom-id="slug"
                    title="Sort by:"
                    :offset="[0, 4]"
                    is-right
                    @update:model-value="handleUpdateDropdowns($event, 'orderby')"
                >
                    <template #button-content>
                        @{{ orderby?.name }}
                    </template>
                </f-dropdown>

                <div class="manage-page-header__sort-btns">
                    <a
                        href="{{ route('user.job.export') }}"
                        target="_blank"
                        title="{{ __("Export to excel") }}"
                        class="manage-page-header__sort-btn btn-icon"
                    >
                        <i class="ri-file-excel-2-line"></i>
                    </a>
                    <button class="manage-page-header__sort-btn btn-icon" @click="handleChangeVisibleFilters">
                        <i class="ri-filter-line"></i>
                    </button>
                </div>
            </div>

            <div class="manage-page-header__sort-bottom">
                <div class="manage-page-header__sort-action-selected">
                    @{{ selectedItems.length }} jobs selected
                </div>

                <f-dropdown-select
                    v-model="dropdownActions"
                    title="Action"
                    :options="optionsActions"
                    :is-show-title="false"
                    :disabled="selectedItems.length === 0"
                    :is-set-active-item="false"
                    class="manage-page-header__sort-action-dropdown"
                    @update:model-value="handleSelectActions"
                ></f-dropdown-select>
            </div>
        </div>
    </div>

    @include('admin.message')

    <div class="manage-page__body">
        <b-tabs v-model="activeTab" class="tab-content" id="pills-tabContent">
            <b-tab>
                <div class="manage-page__items">
                    <template v-if="items.length > 0">
                        <job-item-dashboard
                            v-for="item in items"
                            :key="item.id"
                            :item="item"
                            :is-candidate="isCandidate"
                            type="job"
                            v-model="selectedItems"
                            @add-sponsored="handleAddSponsored"
                        ></job-item-dashboard>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you have no open jobs</div>

                            <a
                                href="{{ route('user.create.job') }}"
                                class="f-btn primary-btn theme-btn"
                            >{{ __("Post a job") }}</a>
                        </div>
                    </div>
                </div>

                <div class="manage-page__pagination-wrap">
                    <f-pagination :pagination="pagination" @update-page="updateActiveFilters" ></f-pagination>
                </div>
            </b-tab>

            <b-tab>
                <div class="manage-page__items">
                    <template v-if="items.length > 0">
                        <job-item-dashboard
                            v-for="item in items"
                            :key="item.id"
                            :item="item"
                            :is-candidate="isCandidate"
                            type="job"
                            v-model="selectedItems"
                            @add-sponsored="handleAddSponsored"
                        ></job-item-dashboard>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you have no open jobs</div>

                            <a
                                href="{{ route('user.create.job') }}"
                                class="f-btn primary-btn theme-btn"
                            >{{ __("Post a job") }}</a>
                        </div>
                    </div>
                </div>

                <div class="manage-page__pagination-wrap">
                    <f-pagination :pagination="pagination" @update-page="updateActiveFilters" ></f-pagination>
                </div>
            </b-tab>

            <b-tab>
                <div class="manage-page__items">
                    <template v-if="items.length > 0">
                        <job-item-dashboard
                            v-for="item in items"
                            :key="item.id"
                            :item="item"
                            :is-candidate="isCandidate"
                            type="job"
                            v-model="selectedItems"
                            @add-sponsored="handleAddSponsored"
                        ></job-item-dashboard>
                    </template>

                    <div v-else class="manage-page__items">
                        <div class="manage-page__items-empty">
                            <div class="manage-page__items-empty-title">you have no open jobs</div>
                        </div>
                    </div>
                </div>

                <div class="manage-page__pagination-wrap">
                    <f-pagination :pagination="pagination" @update-page="updateActiveFilters" ></f-pagination>
                </div>
            </b-tab>
        </b-tabs>
    </div>

    <Teleport to="body">
        <filters-mobile
            v-model:is-show-filters="isShowFilters"
            :filters="filters"
            :visible-filters="visibleFilters"
            class="manage-page-filters"
            @update-page="updateActiveFilters"
            @reset-page-filters="resetActiveFilters"
        ></filters-mobile>
    </Teleport>

    <modal-promote-product
        :visible-modal-name="visibleModalName"
        :item="activeJob"
        @close-modal="handleClosePromoteModal"
    ></modal-promote-product>

    <modal-pay-product
        :visible-modal-name="visibleModalName"
        :item="activeJob"
        @close-modal="handleClosePromoteModal"
    ></modal-pay-product>
</div>
@endsection

@section('footer')
    <script src="{{ mix('js/manageJob.js', $manifestDir) }}"></script>
@endsection
