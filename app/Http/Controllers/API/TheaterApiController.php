namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Theater;

class TheaterApiController extends Controller
{
    public function index()
    {
        return response()->json(Theater::all());
    }

    public function show($id)
    {
        $theater = Theater::find($id);
        if (!$theater) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json($theater);
    }
}
