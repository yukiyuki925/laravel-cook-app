<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function home()
    {
      // recipesテーブルから取ってくる
      $recipes = Recipe::select('recipes.id', 'recipes.title', 'recipes.description', 'recipes.created_at', 'recipes.image', 'users.name')
      ->join('users', 'users.id', '=', 'recipes.user_id')
      ->orderBy('recipes.created_at', 'desc')
      ->limit(3)
      ->get();
      // dd($recipes);

      // 人気のレシピを取ってくる
      $popular = Recipe::select('recipes.id', 'recipes.title', 'recipes.description', 'recipes.created_at', 'recipes.image', 'recipes.views', 'users.name')
      ->join('users', 'users.id', '=', 'recipes.user_id')
      ->orderBy('recipes.views', 'desc')
      ->limit(2)
      ->get();

      return view('home', compact('recipes','popular'));
    }
    
    public function index(Request $request)
    {
      $filters = $request->all();
      // dd($filters);
      $query = Recipe::query()->select('recipes.id', 'recipes.title', 'recipes.description', 'recipes.created_at', 'recipes.image', 'recipes.views', 'users.name', DB::raw('AVG(reviews.rating) as rating'))
      ->join('users', 'users.id', '=', 'recipes.user_id')
      ->leftJoin('reviews', 'reviews.recipe_id', '=', 'recipes.id')
      ->groupBy('recipes.id')
      ->orderBy('recipes.views', 'desc');

      if(!empty($filters)){
        // もしカテゴリーが選択されていたら
        if(!empty($filters['categories'])){
          // カテゴリーで絞り込み選択したカテゴリーIDが含まれているレシピを取得
          $query->whereIn('recipes.category_id', $filters['categories']);
        }

        if(!empty($filters['rating'])){
          $query->havingRaw('AVG(reviews.rating) >= ?', [$filters['rating']]);
        }

        if(!empty($filters['title'])){
          // タイトルで絞り込み
          $query->where('recipes.title', 'like', '%'.$filters['title'].'%');
        }
      }
      
      $recipes = $query->paginate(5);
      // dd($recipes);

      $categories = Category::all(); 

      return view('recipes.index', compact('recipes', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $recipe = Recipe::with(['ingredients', 'steps', 'reviews'])
      ->where('recipes.id', $id)
      ->get();
      $recipe = $recipe[0];

      $recipe_recode = Recipe::find($id);
      $recipe_recode->increment('views');

      // dd($recipe);

      return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}