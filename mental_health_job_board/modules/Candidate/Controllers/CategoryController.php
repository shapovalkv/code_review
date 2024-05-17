<?php
namespace Modules\Candidate\Controllers;

use Illuminate\Http\Request;
use Modules\Candidate\Models\Category;
use Modules\FrontendController;

class CategoryController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, $slug)
    {
        $cat = Category::where('slug', $slug)->first();
        if (empty($cat)) {
            return redirect('/category');
        }
        $listCategories = Category::query();
        $listCategories->select("bc_categories.*")
                ->where("bc_categories.status", "publish");

        $translation = $cat->translateOrOrigin(app()->getLocale());

        $data = [
            'rows'           => $listCategories->with("getAuthor")->paginate(5),
            'breadcrumbs'    => [
                [
                    'name' => __('Category'),
                    'url'  => route('category.index')
                ],
                [
                    'name'  => $translation->name,
                    'class' => 'active'
                ],
            ],
            'page_title'=>$translation->name,
            'seo_meta'  => $cat->getSeoMetaWithTranslation(app()->getLocale(),$translation),
            'translation'=>$translation
        ];
        return view('Candidate::frontend.index', $data);
    }
}
