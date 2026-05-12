<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use SoftDeletes;

    protected $table = 'vendas';

    public const STATUS_PENDENTE  = 'pendente';
    public const STATUS_PAGO      = 'pago';
    public const STATUS_ENVIADO   = 'enviado';
    public const STATUS_ENTREGUE  = 'entregue';
    public const STATUS_CANCELADO = 'cancelado';

    public static function statusesValidos(): array
    {
        return [
            self::STATUS_PENDENTE,
            self::STATUS_PAGO,
            self::STATUS_ENVIADO,
            self::STATUS_ENTREGUE,
            self::STATUS_CANCELADO,
        ];
    }

    protected $fillable = [
        'usuario_id', 'data', 'preco_total', 'desconto_id',
        'preco_total_com_desconto', 'status',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    // Relacionamento: Uma venda pertence a um cliente (Usuario)
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relacionamento: Uma venda tem muitos produtos
    public function produtos()
    {
        return $this->hasMany(ProdutoVenda::class, 'venda_id');
    }

    // Relacionamento: Uma venda pode conter (pertencer a) um desconto (cupom)
    public function desconto()
    {
        return $this->belongsTo(Desconto::class, 'desconto_id');
    }
}
