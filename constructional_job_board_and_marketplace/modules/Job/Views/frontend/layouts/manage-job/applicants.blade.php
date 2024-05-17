@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')
    <div
        id="manage-applicants"
        data-page-with-vue
        class="manage-page manage-page--applicants"
        data-filters="{{ json_encode($filters) }}"
        data-rows="{{ json_encode($rows) }}"
        data-pagination="{{ json_encode($pagination) }}"
    >

        <div class="manage-page-header">
            <div class="manage-page-header__title-wrap">
                <h3 class="manage-page-header__title">{{ __("Applicants") }}</h3>
            </div>

            <div class="manage-page-header__search">
                <b-button
                    class="manage-page-header__search-btn"
                    href="{{ route('user.applicants.create') }}"
                    variant="outline-primary"
                >{{__("Add applicant")}}</b-button>

{{--                <div class="manage-page-header__input-wrap">--}}
{{--                    <b-form-input--}}
{{--                        type="text"--}}
{{--                        name="keywords"--}}
{{--                        value="{{ request()->input('keywords') }}"--}}
{{--                        placeholder="{{__('Search')}}"--}}
{{--                        class="manage-page-header__search-input"--}}
{{--                        v-model="keywords"--}}
{{--                    ></b-form-input>--}}
{{--                    <i class="manage-page-header__search-icon ri-search-line"></i>--}}
{{--                </div>--}}
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
                        :options="filters.orderby.items"
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
                            href="{{ route('user.applicants.export') }}"
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
                        @{{ selectedItems.length }} applicants selected
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
            <div v-if="isScreenSM" class="manage-page__items">
                <template v-if="items.length > 0">
                    <applicant-item-dashboard-mobile
                        v-for="item in items"
                        :key="item.id"
                        :item="item"
                        v-model="selectedItems"
                        @open-change-status-modal="handleOpenChangeStatusModal"
                    ></applicant-item-dashboard-mobile>
                </template>

                <div v-else class="manage-page__items">
                    <div class="manage-page__items-empty">
                        <div class="manage-page__items-empty-title">you have no applicants</div>

                        <a
                            href="{{ route('user.applicants.create') }}"
                            class="f-btn primary-btn theme-btn"
                        >{{__("Add applicant")}}</a>
                    </div>
                </div>
            </div>

            <div v-if="!isScreenSM" class="table-outer manage-page__table-outer">
                <table class="default-table manage-page-table">
                    <thead>
                        <tr>
                            <th class="manage-page-table__checkbox">
                                <b-form-checkbox
                                    :indeterminate="isIndeterminate"
                                    v-model="mainCheckbox"
                                    @input="handleChangeMainCheckbox"
                                ></b-form-checkbox>
                            </th>
                            <th class="manage-page-table__applicant">{{ __("Applicant") }}</th>
                            <th>{{ __("Category") }}</th>
                            <th>{{ __("Applied for") }}</th>
                            <th>{{ __("Date") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th class="manage-page-table__dropdown"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-if="items.length > 0">
                            <applicant-item-dashboard-desktop
                                v-for="item in items"
                                :key="item.id"
                                :item="item"
                                v-model="selectedItems"
                                @update:model-value="handleChangeChildCheckbox"
                                @open-change-status-modal="handleOpenChangeStatusModal"
                            ></applicant-item-dashboard-desktop>
                        </template>

                        <tr v-else>
                            <td colspan="7">
                                <div class="manage-page__items">
                                    <div class="manage-page__items-empty">
                                        <div class="manage-page__items-empty-title">you have no applicants</div>

                                        <a
                                            href="{{ route('user.applicants.create') }}"
                                            class="f-btn primary-btn theme-btn"
                                        >{{__("Add applicant")}}</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="manage-page__pagination-wrap">
                <f-pagination :pagination="pagination" @update-page="updateActiveFilters" ></f-pagination>
            </div>
        </div>

        <Teleport to="body">
            <filters-mobile
                v-model:is-show-filters="isShowFilters"
                :filters="filters"
                :visible-filters="visibleFilters"
                class="manage-page-filters"
                button-text="applicants"
                @update-page="updateActiveFilters"
                @reset-page-filters="resetActiveFilters"
            ></filters-mobile>
        </Teleport>

        <modal-change-status
            :visible-modal-name="visibleModalName"
            :item="activeItem"
            :jobs="items"
            :statuses="optionsMainActions"
            @close-modal="handleCloseModal"
        ></modal-change-status>
@endsection

@section('footer')
    <script src="{{ mix('js/manageApplicants.js', $manifestDir) }}"></script>
@endsection
