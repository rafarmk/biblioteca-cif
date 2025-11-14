namespace App\Imports;

use App\Models\Libro;
use Maatwebsite\Excel\Concerns\ToModel;

class BibliotecaImport implements ToModel
{
    public function model(array $row)
    {
        return new Libro([
            'id_libro' => $row[1],
            'titulo' => $row[2],
            'autor' => $row[3],
            'genero' => $row[4],
            'anio_publicacion' => $row[5],
            'estado' => $row[6],
            'ubicacion' => $row[7],
            'observaciones' => $row[8],
            'ejemplares' => $row[9],
            'prestado_por' => $row[10],
            'fecha_devolucion' => $row[11],
        ]);
    }
}

