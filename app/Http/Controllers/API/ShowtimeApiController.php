namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use Illuminate\Http\Request;

class ShowtimeApiController extends Controller
{
    public function index() {
        return response()->json(Showtime::with('movie', 'theater')->get());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'theater_id' => 'required|exists:theaters,id',
            'showtime' => 'required|date',
        ]);
        return response()->json(Showtime::create($data), 201);
    }

    public function show($id) {
        $showtime = Showtime::with('movie', 'theater')->find($id);
        return $showtime ? response()->json($showtime) : response()->json(['message' => 'Not Found'], 404);
    }

    public function update(Request $request, $id) {
        $showtime = Showtime::findOrFail($id);
        $showtime->update($request->all());
        return response()->json($showtime);
    }

    public function destroy($id) {
        $showtime = Showtime::findOrFail($id);
        $showtime->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
