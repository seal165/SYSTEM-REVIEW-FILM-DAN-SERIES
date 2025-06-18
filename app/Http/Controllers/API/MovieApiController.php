namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    public function index() {
        return response()->json(Movie::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'genre' => 'required|string',
        ]);
        return response()->json(Movie::create($data), 201);
    }

    public function show($id) {
        $movie = Movie::find($id);
        return $movie ? response()->json($movie) : response()->json(['message' => 'Not Found'], 404);
    }

    public function update(Request $request, $id) {
        $movie = Movie::findOrFail($id);
        $movie->update($request->all());
        return response()->json($movie);
    }

    public function destroy($id) {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
