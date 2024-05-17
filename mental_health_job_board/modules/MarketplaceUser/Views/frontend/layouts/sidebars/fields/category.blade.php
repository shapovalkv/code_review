<!-- Filter Block -->
@if($list_categories)
    <div class="filter-block">
        <h4>{{ $val['title'] }}</h4>
        <div class="form-group categories_select">
            <span class="categories_icon flaticon-briefcase"></span>
            <select id="categories" class="form-control categories_select" name="categories[]" multiple="multiple">
                <option value=""></option>
                    <?php
                    foreach ($list_categories as $oneCategories) {
                        $selected = '';
                        if (!empty($categories = $active_search_params['categories'] ?? '')) {
                            foreach ($categories as $category) {
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
            <span class="icon flaticon-briefcase"></span>
        </div>
    </div>
@endif
