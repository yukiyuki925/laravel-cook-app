<x-app-layout>
  <div class="grid grid-cols-3 gap-4">
    <div class="col-span-2 bg-white rounded p-4">
      {{ Breadcrumbs::render('index') }}
      <div class="mb-4"></div>
      @foreach($recipes as $recipe)
      @include('recipes.partial.horizontal-card')
      @endforeach
      {{ $recipes->links() }}
    </div>
    <div class="col-span-1 bg-white p-4 h-max sticky top-4">
      <form action="{{route('recipe.index')}}" method="GET">
        <div class="flex">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="w-6 h-6 text-gray-700 mr-2">
            <path fill-rule="evenodd"
              d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z"
              clip-rule="evenodd" />
          </svg>
          <h3 class="text-xl text-gray-800 font-bold mb-4">レシピ検索</h3>
        </div>

        <!-- 評価 -->
        <div class="mb-4 p-6 border border-gray-300">
          <label class="text-large text-gray-800">評価</label>
          <div class="ml-4 mb-2">
            <!-- もしratingのフィルターがあったら、空文字を返す、そうでなければ ’checked'を返す -->
            <input type="radio" name="rating" value="0" id="rating0"
              {{ ($filters['rating'] ?? null) == null ? 'checked' : '' }}>
            <label for="rating0">指定しない</label>
          </div>
          <div class="ml-4 mb-2">
            <input type="radio" name="rating" value="3" id="rating3"
              {{ ($filters['rating'] ?? null) == "3" ? 'checked' : ''}}>
            <label for="rating3">3以上</label>
          </div>
          <div class="ml-4 mb-2">
            <input type="radio" name="rating" value="4" id="rating4"
              {{ ($filters['rating'] ?? null) == "4" ? 'checked' : ''}}>
            <label for="rating4">4以上</label>
          </div>
        </div>

        <!-- カテゴリー -->
        <div class="mb-4 p-6 border border-gray-300">
          <label class="text-large text-gray-800">カテゴリー</label>
          @foreach($categories as $category)
          <div class="ml-4 mb-2">
            <input type="checkbox" name="categories[]" value="{{$category['id']}}" id="category{{$category['id']}}"
              {{ (in_array($category['id'], $filters['categories'] ?? [] )) ? 'checked' : '' }}>
            <!-- $category['id']が$filters['categories']に含まれているか判定 -->
            <label for="category{{$category['id']}}">{{$category['name']}}</label>
          </div>
          @endforeach
        </div>
        <input type="text" name="title" value="{{ $filters['title'] ?? '' }}" placeholder="レシピ名を入力"
          class="border border-gray-300 p-2 mb-4 w-full">
        <div class="text-center">
          <button type="submit"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">検索</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>