<?php namespace Visiosoft\CatsModule\Http\Controller\Admin;

use Anomaly\Streams\Platform\Model\Cats\CatsCategoryEntryModel;
use Illuminate\Http\Request;
use Visiosoft\CatsModule\Category\CategoryCollection;
use Visiosoft\CatsModule\Category\CategoryModel;
use Visiosoft\CatsModule\Category\Form\CategoryFormBuilder;
use Visiosoft\CatsModule\Category\Table\CategoryTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

class CategoryController extends AdminController
{
    public function index(CategoryTableBuilder $table, Request $request)
    {
        if($this->request->action == "delete") {
            $CategoriesModel = new CategoryModel();
            foreach ($this->request->id as $item)
            {
                $CategoriesModel->deleteSubCategories($item);
            }
        }
        $categories = 1;
        if(!isset($request->cat) || $request->cat==""){
            $categories = CategoryModel::query()->where('parent_category_id', '')->orWhereNull('parent_category_id')->get();
            $categories = $categories->where('deleted_at', null);
        }else{
            $categories = CategoryModel::query()->where('parent_category_id', $request->cat)->whereNull('deleted_at')->get();
        }
        if (count($categories) == 0) {
            $this->messages->error('Selected category has no sub-categories.');
            return back();
        }
        $table->setTableEntries($categories);
        // dd($table);
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param CategoryFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(CategoryFormBuilder $form,Request $request)
    {
        if($this->request->action == "save") {
            $all = $this->request->all();
            $id = $all['parent_category'];
            $k = 1;
            for($i=0; $i<$k; $i++) {
                $cat1  = CategoryModel::query()->where('cats_category.id', $id)->first();
                if ($cat1 != null) {
                    $id = $cat1->parent_category_id;
                    $k++;
                }
            }
            if ($i >= 7) {
                $this->messages->error('You have reached your sub-category limit, you can only add 5 sub-categories.');

                return $this->redirect->back();
            }

            $form->make();
            if ($form->hasFormErrors()) {
                return $this->redirect->to('/admin/cats/create');
            }
            return $this->redirect->to('/admin/cats');
        }

        return $this->view->make('visiosoft.module.cats::cats/admin-cat');
    }

    /**
     * Edit an existing entry.
     *
     * @param CategoryFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(CategoryFormBuilder $form,Request $request, $id)
    {
        if ($request->action == "update") {
            $form->make($id);
            if ($form->hasFormErrors()) {
                return $this->redirect->back();
            }
        }
        return $this->view->make('visiosoft.module.cats::cats/admin-cat')->with('id', $id);
    }

    public function delete($id)
    {
        $Find_Categories = CategoryModel::query()
            ->where('deleted_at', null)
            ->find($id);
        if($Find_Categories != "")
        {
            $delete = new CategoryCollection();
            $delete = $delete->subCatDelete($id);
            header("Refresh:0");
        } else {
            return redirect('admin/cats')->with('success', ['Category and related sub-categories deleted successfully.']);
        }

    }
}
