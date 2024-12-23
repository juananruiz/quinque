<?php
// src/Twig/AppQuinqueExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppQuinqueExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('dias_a_periodo', [$this, 'diasAPeriodo']),
            new TwigFilter('fecha_es', [$this, 'fechaEspanol']),
        ];
    }

    public function diasAPeriodo(int $dias): string
    {
        $anos = floor($dias / 365);
        $diasRestantes = $dias % 365;
        $meses = floor($diasRestantes / 30);
        $dias = $diasRestantes % 30;

        $partes = [];
        if ($anos > 0) {
            $partes[] = $anos . ' año' . ($anos != 1 ? 's' : '');
        }
        if ($meses > 0) {
            $partes[] = $meses . ' mes' . ($meses != 1 ? 'es' : '');
        }
        if ($dias > 0) {
            $partes[] = $dias . ' día' . ($dias != 1 ? 's' : '');
        }

        return implode(', ', $partes);
    }

    public function fechaEspanol(\DateTimeInterface $fecha): string
    {
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre'
        ];
        
        $fechaFormateada = $fecha->format('j \d\e F \d\e Y');
        return strtr($fechaFormateada, $meses);
    }
}
