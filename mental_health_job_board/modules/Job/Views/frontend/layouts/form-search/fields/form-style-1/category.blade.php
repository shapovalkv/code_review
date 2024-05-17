<!-- Filter Block -->
@if($list_categories && Route::currentRouteName() != 'job.search.practicum')
    <div class="filter-block">
        <h4>{{ $val['title'] }}</h4>
        <div class="form-group categories_select">
        <span class="categories_icon flaticon-briefcase"></span>
            <select id="categories" class="form-control" name="categories[]" multiple="multiple" style="height:100px;padding-top: 0">
                <option value=""></option>
                    <?php
                    foreach ($list_categories as $oneCategories) {
                        $selected = '';
                        if (!empty($categories = $active_search_params['categories'] ?? '')) {
                            foreach ($active_search_params['categories'] as $category) {
                                if ($oneCategories->id == $category) {
                                    $selected = 'selected';
                                }
                            }
                        }
                        $trans = $oneCategories->translateOrOrigin(app()->getLocale());
                        printf("<option value='%s' %s>%s</option>", $oneCategories->id, $selected, $oneCategories->name);
                    }
                    ?>
            </select>
        </div>
    </div>
@endif
